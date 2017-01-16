<?php

namespace BetaGT\UserAclManager\Http\Controllers\Api;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use BetaGT\UserAclManager\Criteria\BetweenCriteria;
use BetaGT\UserAclManager\Http\Controllers\BaseController;
use BetaGT\UserAclManager\Http\Requests\UserChangePasswordRequest;
use BetaGT\UserAclManager\Http\Requests\UserRequest;
use BetaGT\UserAclManager\Http\Requests\UserResetPasswordRequest;
use BetaGT\UserAclManager\Http\Requests\UserResetSendEmailRequest;
use BetaGT\UserAclManager\Repositories\UserRepository;
use BetaGT\UserAclManager\Services\CacheService;
use BetaGT\UserAclManager\Services\ImageUploadService;
use Validator;

/**
 * @resource API Usuário
 *
 * Essa API é responsável pelo gerenciamento de Usuários no BetaGT\UserAclManager qImob.
 * Os próximos tópicos apresenta os endpoints de Consulta, Cadastro, Edição e Deleção.
 */
class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    private $passwordBroker;
    /**
     * @var ImageUploadService
     */
    private $imageUploadService;
    /**
     * @var CacheService
     */
    private $cacheService;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param PasswordBroker $passwordBroker
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordBroker $passwordBroker,
        ImageUploadService $imageUploadService,
        CacheService $cacheService
    ){
        $this->userRepository = $userRepository;
        $this->passwordBroker = $passwordBroker;
        $this->setPathFile(public_path('arquivos/img/user'));
        $this->imageUploadService = $imageUploadService;
        $this->cacheService = $cacheService;
    }

    /**
     * Consultar Usuários
     *
     * Endpoint para consulta de usuários cadastrados paginadas
     *
     * @return mixed
     */
    public function index(Request $request){

        return $this->userRepository
            ->pushCriteria(new BetweenCriteria('created_at',$request))
            ->pushCriteria(new BetweenCriteria('updated_at',$request))
            ->paginate(parent::$_PAGINATION_COUNT);
    }

    /**
     * Consultar Usuário por ID
     *
     * Endpoint para consultar usuário passando o ID como parametro
     *
     * @param $id
     * @return mixed
     */
    public function show($id){
        try{
            return $this->userRepository->find($id);
        }catch (ModelNotFoundException $e){
            return response()->json(parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage()), parent::HTTP_CODE_NOT_FOUND);
        }
    }

    /**
     * Cadastrar Usuário
     *
     * Endpoint para cadastrar página usuário
     *
     */
    public function store(UserRequest $userRequest){
        try{
            return $this->userRepository->create($userRequest->all());
        }catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Alterar Usuário por ID
     * @param $id
     */
    public function update(UserRequest $userRequest, $id){
        try{
            return $this->userRepository->update($userRequest->all(), $id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Alterar Imagem Usuário logado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function changeImage(Request $request){
        $data = $request->all();
        Validator::make($data, [
            'imagem' => [
                'required',
                'image',
                'mimes:jpg,jpeg,bmp,png'
            ]
        ])->validate();
        try{
            $this->imageUploadService->upload('imagem',$this->getPathFile(),$data);
            return $this->userRepository->update($data,$request->user()->id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Alterar Imagem Administrativo
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function changeImageAdmin(Request $request,$id){
        $data = $request->all();
        Validator::make($data, [
            'imagem' => [
                'required',
                'image',
                'mimes:jpg,jpeg,bmp,png'
            ]
        ])->validate();
        try{
            $this->imageUploadService->upload('imagem',$this->getPathFile(),$data);
            return $this->userRepository->update($data,$id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Solicitar Nova Senha
     *
     * Enviar email com link para usuário recuperar senha
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function solicitarNovaSenha(UserResetSendEmailRequest $request)
    {
        $response = $this->passwordBroker->sendResetLink($request->only('email'), function($m)
        {
            $m->subject($this->getEmailSubject());
        });

        switch ($response)
        {
            case PasswordBroker::RESET_LINK_SENT:
                return parent::responseSuccess(parent::HTTP_CODE_OK, "O link de recuperação de senha foi enviado para seu endereço de e-mail");
            case PasswordBroker::INVALID_USER:
                return parent::responseError(parent::HTTP_CODE_NOT_FOUND, "Não é possível encontrar um usuário com esse endereço de e-mail");
        }
    }

    /**
     * Criar Nova Senha
     *
     * Enviar dados para criar a nova senha solicitada pelo usuário
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function criarNovaSenha(UserResetPasswordRequest $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = $this->passwordBroker->reset($credentials, function($user, $password)
        {
            $user->forceFill([
                'password' => $password,
                'remember_token' => Str::random(60),
            ])->save();
        });

        switch ($response)
        {
            case PasswordBroker::PASSWORD_RESET:
                return parent::responseSuccess(parent::HTTP_CODE_OK, "Senha alterada com sucesso.");
            case PasswordBroker::INVALID_TOKEN:
                return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "O Token de reinicialização inválida ou já expirou.");
            case PasswordBroker::INVALID_USER:
                return parent::responseError(parent::HTTP_CODE_NOT_FOUND, "Não é possível encontrar um usuário com esse endereço de e-mail");
            default:
                return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "Erro inesperado.");
        }
    }

    /**
     * Alterar Senha
     *
     * Enviar dados para alterar a senha
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alterarSenha(UserChangePasswordRequest $request)
    {
        $user = $request->user();
        if(password_verify($request->get('old_password'), $user->password)) {
            $this->userRepository->update(['password' => $request->get('new_password')], $user->id);
            return parent::responseSuccess(parent::HTTP_CODE_OK, "Senha alterada com sucesso.");
        }
        return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "A senha atual não confere.");
    }

    /**
     * Deletar Usuário
     * @param $id
     */
    public function destroy($id){
         try{
             $this->userRepository->delete($id);
             return parent::responseSuccess(parent::HTTP_CODE_OK, parent::MSG_REGISTRO_EXCLUIDO);
         }
         catch (ModelNotFoundException $e){
             return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
         }
         catch (\Exception $e){
             return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
         }
    }

}
