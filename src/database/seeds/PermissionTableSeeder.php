<?php

use Illuminate\Database\Seeder;
use \Kodeine\Acl\Models\Eloquent\Role,
    \Kodeine\Acl\Models\Eloquent\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRole();
        $this->createPermission();
        $this->accessRole('administrador',1);
        $this->accessRole('moderador',2);
        $this->associationPermission(1,1);
        Artisan::call('passport:client',['--password'=>true,'--name'=>'Admin']);
        Artisan::call('passport:client',['--password'=>true,'--name'=>'WebSite']);
        $this->novaPermissao(2,2,[
            'name'        => 'user.moderador',
            'slug'        => [          // pass an array of permissions.
                'view'       => false,
            ],
            'description' => 'manage user permissions'
        ],'moderador',1);
        $this->ClietApiAssocUser(1,1);
        $this->ClietApiAssocUser(2,2);
        //$this->createUserPermission(1);
        //$this->removeRule();
    }
    public function ClietApiAssocUser($clientId, $userId){
        $client = \Laravel\Passport\Client::find($clientId);
        $client->user_id = $userId;
        $client->update();
    }
    private function novaPermissao($userId,$ruleId,$data,$role=null,$permissionInternshipId=null){
        $roleAdmin = Role::find($ruleId);
        if($permissionInternshipId){
            $permissionInternship = Permission::find($permissionInternshipId);
            $data['inherit_id'] = $permissionInternship->getKey();
        }
        Permission::create($data);
        $roleAdmin->assignPermission($data['name']);
        $user = \BetaGT\UserAclManager\Models\User::find($userId);
        if($role)
        $user->assignRole($role);
    }

    private function associationPermission($roleId,$permissionId){
        $roleAdmin = Role::find($roleId); // administrator
        // permission as an object
        $roleAdmin->assignPermission(Permission::find($permissionId));
        // as an id
        //$roleAdmin->assignPermission($permUser->id);
        // or by name
        //$roleAdmin->assignPermission('user');
        // or by collection
        //$roleAdmin->assignPermission(Permission::all());
    }

    private function createPermission(){
        $permission = new Permission();
        $permUser = $permission->create([
            'name'        => 'user',
            'slug'        => [          // pass an array of permissions.
                'store'     => true,
                'view'       => true,
                'show'       => true,
                'update'     => true,
                'delete'     => true,
            ],
            'description' => 'manage user permissions'
        ]);

        /*$permission = new Permission();
        $permPost = $permission->create([
            'name'        => 'pagina',
            'slug'        => [          // pass an array of permissions.
                'create'     => true,
                'view'       => true,
                'update'     => true,
                'delete'     => true,
            ],
            'description' => 'manage post permissions'
        ]);*/
    }
    private function createUserPermission($id)
    {
        $user = \BetaGT\UserAclManager\Models\User::find($id);

        // create crud permissions
        // create.user, view.user, update.user, delete.user
        // returns false if alias exists.
        //$user->addPermission('user');

        // update permission on user alias
        // set its permission to false
        //$user->addPermission('update.user', false);
       // $user->addPermission('view.phone.user', true);

        // pass permissions array to user alias
        $user->addPermission('user', [
            'view.phone' => true,
            'view.blog' => false
        ]);
    }

    private function removePermission($id){
        $user = \BetaGT\UserAclManager\Models\User::find($id);
        // remove an alias
        $user->removePermission('user');

        // remove update permission from user
        $user->removePermission('update.user');

        $user->removePermission('user', [
            'view.phone',
             'view.blog'
        ]);
    }

    private function accessRole($rule='administrator',$userId){
        $user = \BetaGT\UserAclManager\Models\User::find($userId);
        // by object
        //$user->assignRole($roleAdmin);
        // or by id
        //$user->assignRole($roleAdmin->id);
        // or by just a slug
        $user->assignRole($rule);
    }

    private function removeRule($id){
        $user = \BetaGT\UserAclManager\Models\User::find($id);
        $user->revokeAllRoles();
    }
    private function removePermissionMe($id){
        $roleAdmin = Role::find($id);
        if($roleAdmin){
            $roleAdmin->revokeAllPermissions();
        }
    }

    private function createRole(){

        $role = new Role();
        $roleAdmin = $role->create([
            'name' => 'Administrador',
            'slug' => 'administrador',
            'description' => 'Descrição da regra'
        ]);

        $role = new Role();
        $roleModerator = $role->create([
            'name' => 'Moderador',
            'slug' => 'moderador',
            'description' => 'Descrição da regra'
        ]);

        $role = new Role();
        $roleAnunciante = $role->create([
            'name' => 'Anunciante',
            'slug' => 'anunciante',
            'description' => 'Descrição da regra'
        ]);

        $role = new Role();
        $roleImobiliaria = $role->create([
            'name' => 'Imobiliária',
            'slug' => 'imobiliaria',
            'description' => 'Descrição da regra'
        ]);
    }
}
