<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Kptorganization;
use App\user_orgizations;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Super Admin block */
        $user = User::create([
            'name' => 'Admin',
            'mobile'=>'8121815502',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'employee_id' => 'KPT001'
        ]);
        $user->assignRole('administrator');
		
		/*$data['created_by'] = $user->id;
        $data['org_name'] = "Qatar";
        $org = Kptorganization::firstOrCreate (
            ['org_name' => $data['org_name']],
            ['created_by' => $data['created_by']]
        );*/


		
		/* Super Admin block */
		
		 /* Org Admin block */
		/*$user1 = User::firstOrNew([
            'name' => 'Org Admin',
            'email' => 'orgadmin@admin.com',
            'password' => 'password'
        ]);
        $user1->assignRole('orgadmin');
		
			
		$arr_user_organizations = array("user_id"=>$user1->id,"org_id"=>1);
		user_orgizations::firstOrNew($arr_user_organizations);	*/
		
		/* Org Admin block */
		
		
		
		
		 /* Sub Org Admin block 
		 $user2 = User::create([
            'name' => 'Sub Org Admin',
            'email' => 'suborgadmin@admin.com',
            'password' => 'password'
        ]);
         $user2->assignRole('suborgadmin');
		/* Sub Org Admin block */
		
		
		
		
		
    }
}
