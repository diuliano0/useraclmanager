<?php

namespace BetaGT\UserAclManager\Http\Controllers\Api;

use BetaGT\UserAclManager\Criteria\OrderCriteria;
use BetaGT\UserAclManager\Criteria\RoleCriteria;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use BetaGT\UserAclManager\Criteria\BetweenCriteria;
use BetaGT\UserAclManager\Http\Controllers\BaseController;
use BetaGT\UserAclManager\Http\Requests\RoleRequest;
use BetaGT\UserAclManager\Repositories\PermissionRepository;
use BetaGT\UserAclManager\Repositories\RoleRepository;
use BetaGT\UserAclManager\Repositories\UserRepository;
use BetaGT\UserAclManager\Services\RoleService;
use Validator;

/**
 * @resource API Role(Perfil)
 *
 * Essa API é responsável pelo gerenciamento de role(perfil) de Usuários no BetaGT\UserAclManager qImob.
 * Os próximos tópicos apresenta os endpoints de Consulta, Cadastro, Edição e Deleção.
 */
class RoleController extends BaseController
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
     * @var RoleService
     */
    private $roleService;

    public function __construct(
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository,
        UserRepository $userRepository,
        RoleService $roleService)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
        $this->roleService = $roleService;
    }

    /**
     * Consultar Regras
     *
     * Endpoint para consulta de regras cadastrados paginadas
     *
     * @return mixed
     */
    public function index(Request $request){
        try{
            return $this->roleRepository
                ->pushCriteria(new RoleCriteria($request))
                ->pushCriteria(new OrderCriteria($request))
                ->paginate(parent::$_PAGINATION_COUNT);
        }catch (\PDOException $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Consultar Regra por ID
     *
     * Endpoint para consultar role(perfil) passando o ID como parametro
     *
     * @param $id
     * @return mixed
     */
    public function show($id){
        try{
            return $this->roleRepository->find($id);
        }catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
    }

    /**
     * Cadastrar Role(Perfil)
     *
     * Endpoint para cadastrar role(perfil) de usuário
     *
     */
    public function store(RoleRequest $roleRequest){
        $data = $roleRequest->all();
        try{
            return $this->roleRepository->create($data);
        }catch (Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Role(Perfil) do Usuário
     *
     * Busca todos os perfis do usuário
     * @param $id
     * @return array
     */
    public function roleByUser($id){
        return $this->userRepository->getRoles($id);
    }


    /**
     *  Revogar Regra Role(Perfil)
     *
     * Revogar todas as regras do role(perfil)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function revokeRole(Request $request){
        $data = $request->all();
        Validator::make($data, [
            'role_id' => [
                'required',
                'exists:roles,id',
                'integer'
            ],
            'user_id' => [
                'required',
                'exists:users,id',
                'integer'
            ],
        ])->validate();
        try{
            return $this->userRepository->revokeRole($data['user_id'],$data['role_id']);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Revogar Todas Regras Usuário
     *
     * Revogar todas as regras de um usuário
     * @param $id
     * @return mixed
     */
    public function revokeAllRoles($id){
        return $this->userRepository->revokeAllRoles($id);
    }

    /**
     * Sicronizar Permissões p/ Role(Perfil)
     *
     * Sicronizar todas as permissões para um role(perfil)
     * @param Request $request
     * @return mixed|void
     */
    public function syncPermissions(Request $request){
        $data = $request->all();
        $rules = [
            'role_id' => [
                'required',
                'exists:roles,id',
                'integer'
            ],
            'permissions'=>'required|array'
        ];

        $permissions = $request->get('permissions',[]);
        foreach ($permissions as $key => $val){
            $rules["permissions.$key"] = [
                'required',
                'exists:permissions,id',
                'integer'
            ];
        }

        Validator::make($data, $rules)->validate();

        try{
            return $this->roleRepository->syncPermissions($data['role_id'],$data['permissions']);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Sicronizar Perfil
     *
     * Sicronizar perfis
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function syncRoles(Request $request){
        $data = $request->all();
        $rules = [
            'user_id' => [
                'required',
                'exists:users,id',
                'integer'
            ],
            'rules'=>'required|array'
        ];

        $permissions = $request->get('rules',[]);
        foreach ($permissions as $key => $val){
            $rules["rules.$key"] = [
                'required',
                'exists:rules,id',
                'integer'
            ];
        }

        Validator::make($data, $rules)->validate();
        try{
            return $this->userRepository->syncRoles($data['user_id'],$data['rules']);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }


    /**
     * Associar Role(Perfil) ao Usuário
     *
     * Endpoint para associar um role(perfil) a um usuário
     *
     */
    public function assocRuleUser(Request $request){
        $data = $request->all();
        Validator::make($data, [
            'role_id' => [
                'required',
                'exists:roles,id',
                'integer',
                Rule::unique('role_user')->where(function ($query) use ($data){
                    $query->where('user_id', $data['user_id']);
                    $query->where('role_id', $data['role_id']);
                }),
            ],
            'user_id' => [
                'required',
                'exists:users,id',
                'integer',
                Rule::unique('role_user')->where(function ($query) use ($data){
                    $query->where('user_id', $data['user_id']);
                    $query->where('role_id', $data['role_id']);
                }),
            ],
        ])->validate();
        try{
            $this->roleService->createAssoc($data);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Alterar Role(Perfil) por ID
     * @param $id
     */
    public function update(RoleRequest $roleRequest,$id){
        $data = $roleRequest->all();
        try{
            return $this->roleRepository->update($data,$id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, $e->getMessage());
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Deletar Role(Perfil)
     * @param $id
     */
    public function destroy($id){
        try{
            $this->roleRepository->delete($id);
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
