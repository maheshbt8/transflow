<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\models\Role_permissions;
class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('cache:clear');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $path = base_path() . '/database/seeds/permissions.sql';
        $sql = file_get_contents($path);
        $re=DB::unprepared($sql);

		
        $path = base_path() . '/database/seeds/role_permissions.sql';
        $sql = file_get_contents($path);
        $re=DB::unprepared($sql);

        // $permissions=Permission::where('type','!=','parent')->get();
        // $data=array(
        // 	'role_id'=>1,
        // 	'add'=>'1',
        // 	'view'=>'1',
        // 	'update'=>'1',
        // 	'delete'=>'1'
        // );
        // foreach ($permissions as $per) {
        // 	$data['permission_id']=$per->id;
        // 	Role_permissions::create($data);
        // }

       /* Permission::create(['name' => 'users_manage']);
		Permission::create(['name' => 'org_manage']);
		Permission::create(['name' => 'org_users_manage']);
		Permission::create(['name' => 'sub_organization_manage']);
		Permission::create(['name' => 'sub_org_users_manage']);
		Permission::create(['name' => 'departments_manage']);
		Permission::create(['name' => 'department_users_manage']);
		Permission::create(['name' => 'authenticate_user_manage']);
		Permission::create(['name' => 'authenticate_user']);
		Permission::create(['name' => 'aem_qatar_client_manage']);
		Permission::create(['name' => 'aem_kpt_pm_manage']);
		Permission::create(['name' => 'aem_qatar_pm_manage']);
		Permission::create(['name' => 'aem_translator_manage']);
		Permission::create(['name' => 'aem_qreviewer_manage']);
		Permission::create(['name' => 'aem_kreviewer_manage']);	
		Permission::create(['name' => 'marketing_campaign']);	
		Permission::create(['name' => 'request_todo_activities']);	
		Permission::create(['name' => 'email_settings']);	
		Permission::create(['name' => 'create_request']);	
		Permission::create(['name' => 'translation_tool']);	
		Permission::create(['name' => 'translation_memory']);*/
    }
}
