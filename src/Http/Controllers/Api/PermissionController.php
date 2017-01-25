<?php

namespace BetaGT\UserAclManager\Http\Controllers\Api;

use BetaGT\UserAclManager\Criteria\OrderCriteria;
use BetaGT\UserAclManager\Criteria\PermissionCriteria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use BetaGT\UserAclManager\Criteria\BetweenCriteria;
use BetaGT\UserAclManager\Http\Controllers\BaseController;
use BetaGT\UserAclManager\Http\Requests\PermissionRequest;
use BetaGT\UserAclManager\Repositories\PermissionRepository;
use BetaGT\UserAclManager\Repositories\RoleRepository;
use BetaGT\UserAclManager\Repositories\UserRepository;
use BetaGT\UserAclManager\Services\PermissionService;
use Validator;


/**
 * @resource API Permissão de Usuários
 *
 * Essa API é responsável pelo gerenciamento de Permissão de Usuários no BetaGT\UserAclManager qImob.
 * Os próximos tópicos apresenta os endpoints de Consulta, Cadastro, Edição e Deleção.
 */
class PermissionController extends BaseController
{


    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository,
        UserRepository $userRepository,
        PermissionService $permissionService
    ){
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
        $this->permissionService = $permissionService;
    }


    /**
     * Consultar Permissão
     *
     * Endpoint para consulta de permissão cadastrados paginadas
     *
     * @return mixed
     */
    public function index(Request $request){

        try{
            return $this->permissionRepository
                ->pushCriteria(new PermissionCriteria($request))
                ->pushCriteria(new OrderCriteria($request))
                ->paginate(parent::$_PAGINATION_COUNT);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }


    /**
     * Consultar Permissão por ID
     *
     * Endpoint para consultar permisão passando o ID como parametro
     *
     * @param $id
     * @return mixed
     */
    public function show($id){
        try{
            return $this->permissionRepository->find($id);
        }catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
    }


    /**
     * Cadastrar Permissão
     *
     * Endpoint para cadastrar permissão usuário
     *
     */
    public function store(PermissionRequest $permissionRequest){
        $data = $permissionRequest->all();
        try{
            return $this->permissionRepository->create($data);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Alterar Permissão por ID
     * @param $id
     */
    public function update(PermissionRequest $permissionRequest,$id){
        $data = $permissionRequest->all();
        try{
            return $this->permissionRepository->update($data,$id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Deletar Permissão
     *
     * @param $id
     */
    public function destroy($id){
        try{
            $this->permissionRepository->delete($id);
            return parent::responseSuccess(parent::HTTP_CODE_OK, parent::MSG_REGISTRO_EXCLUIDO);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     *
     * Associar Permissão a Regra
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assocPermissionRole(Request $request){
        $data = $request->all();
        Validator::make($data, [
            'role_id' => [
                'required',
                'exists:roles,id',
                'integer',
                Rule::unique('permission_role')->where(function ($query) use ($data){
                    $query->where('permission_id', $data['permission']);
                    $query->where('role_id', $data['role_id']);
                }),
            ],
            'permission' => [
                'required',
                'exists:permissions,id',
                'integer',
                Rule::unique('permission_role')->where(function ($query) use ($data){
                    $query->where('permission_id', $data['permission']);
                    $query->where('role_id', $data['role_id']);
                }),
            ],
        ])->validate();
        try{
            $this->permissionService->assocPermissionRole($data['role_id'],$data['permission']);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Listar todas permissões do grupo.
     *
     * @param $id
     * @return mixed
     */
    public function permissionByRole($id){
        return $this->roleRepository->getPermissions($id);
    }

    /**
     * Listar todas permissões do usuário.
     *
     * @param $id
     * @return mixed
     */
    public function permissionByUser($id){
        return $this->roleRepository->getPermissions($id);
    }

    /**
     * Revogar todas as Permissões do usuário
     * @param $id
     */
    public function revokeAllPermissions($id){
        $this->roleRepository->revokeAllPermissions($id);
    }

    /**
     * Remover uma permissão do usuário
     *
     * @param Request $request
     * @return mixed
     */
    public function removePermission(PermissionRequest $request){
        $data = $request->all();
        Validator::make($data, [
            'user_id' => [
                'required',
                'exists:users,id',
                'integer'
            ],
        ])->validate();
        try{
            return $this->userRepository->removePermission($data['name'],$data['slug']);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Listar tipos de permissões de requisições
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSlugs(){
        return response()->json(['data'=>PermissionService::defaltSlug],parent::HTTP_CODE_OK);
    }

    /**
     * Criar uma permissão para um unico usuário.
     *
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assocPermissionUser(PermissionRequest $request){
        $data = $request->all();
        Validator::make($data, [
            'user_id' => [
                'required',
                'exists:users,id',
                'integer'
            ],
        ])->validate();
        try{
            $this->permissionService->assocPermissionUser($data);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }
}
