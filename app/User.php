<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;
	use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile','email', 'password','employee_id','user_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	
	
	public function setPasswordAttribute($password)
    {   
        $this->attributes['password'] = bcrypt($password);
    }
	
	/* Getting administrator users lists */
	public static function getadminUsers($user_id='')
    { 
		$admin_users = User::whereHas(
				'roles', function($q) {
					$q->where('name', 'administrator');					
				}
			)->get();

		return $admin_users;
	
	}
	/* Getting administrator users lists */
	
	
	/* Getting Orgadmin users lists */
	public static function getOrgUsers($user_id='')
    {        
		if($user_id !=""){
			$org_users = User::whereHas(
				'roles', function($q) {
					$q->where('name', ['orgadmin','departmentadmin','suborgadmin','requester','approval','reviewer','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);
					
				}
			)->where('users.created_by',$user_id)->get();
		}else{			
			$org_users = User::whereHas(
				'roles', function($q) {
					$q->where('name',['orgadmin','departmentadmin','suborgadmin','requester','approval','reviewer','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);					
				}
			)->get();				
		}
		
        return $org_users;
    }
	/* Getting Orgadmin users lists */
	
	
	/* Getting SubOrgadmin users lists */
	public static function getSubOrgUsers($user_id='')
    {
        if($user_id !=""){
		$sub_org_users = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'suborgadmin');
            }
        )->where('users.created_by',$user_id)->get();
		}else{			
			$sub_org_users = User::whereHas(
				'roles', function($q) {
					$q->where('name', 'suborgadmin');
				}
			)->get();			
		}		
		
        return $sub_org_users;
	}
	/* Getting SubOrgadmin users lists */
	
	
	
	/* Getting Deptadmin users lists */
	public static function getDepartmentUsers($user_id='')
    {
        if($user_id !=""){
			$dept_users = User::whereHas(
				'roles', function($q) {
					$q->where('name', 'departmentadmin');
				}
			)->where('users.created_by',$user_id)->get();		
		}else{			
			$dept_users = User::whereHas(
            'roles', function($q) {
                $q->where('name', 'departmentadmin');
            }
			)->get();
			
			
		}		
		
        return $dept_users;
	}
	/* Getting Deptadmin users lists */
	
	
	/* Getting Translator & Reviewer Users lists */
	public static function getAuthenticatedUsers($org_id='',$roles_filter=[])
    {
    	if(count($roles_filter) == 0){
    		$roles_filter=['orgadmin','departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor'];
    	}
       if($org_id != ""){
			$authenticated_users = User::whereHas(
				'roles', function($q) use ($roles_filter)  {
					$q->whereIn('name', $roles_filter);
				}
			)->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$org_id)->orderBy('id', 'desc')->get();
	   }else{
		   $authenticated_users = User::whereHas(
            'roles', function($q) use ($roles_filter) {
                $q->whereIn('name', $roles_filter);
            }
			)->orderBy('id', 'desc')->get();	   
	   }
	   
        return $authenticated_users;
	}
	public static function getUserrolesforedit($user_id='')
	{
	
		$authenticated_users = User::whereHas(
			'roles', function($q) {
				$q->whereIn('name', ['orgadmin','departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor','clientuser','requester','approval','reviewer']);
			}
		)->where('users.id',$user_id)->get();
		return $authenticated_users;
	}
	/*  get client roles list*/ 
	public static function getClientUser($org_id='',$client_org_id='',$roles_filter=[])
    {
	    if(count($roles_filter) == 0){
	    	$roles_filter=['clientuser','requester','approval','reviewer'];
	    }
       if($org_id != "" && $client_org_id != ''){
			$client_users = User::whereHas(
				'roles', function($q) use ($roles_filter) {
                	$q->whereIn('name', $roles_filter);
				}
			)->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.org_id',$client_org_id)->get();
	   }elseif($org_id !="" && $client_org_id ==''){
			$client_users = User::whereHas(
				'roles', function($q) use ($roles_filter) {
                	$q->whereIn('name', $roles_filter);
				}
			)->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->whereIn('client_user_orgizations.org_id', $org_id)->get();
	   }else{
		   $client_users = User::whereHas(
            'roles', function($q) use ($roles_filter) {
                $q->whereIn('name', $roles_filter);
            }
			)->get();	   
	   }
	   
        return $client_users;
	}
	/*  get client roles list*/ 
	public static function getClientOrgUser($org_id='',$org_type='kpt_org')
    {
       if($user_id !=""){
			$client_users = User::whereHas(
				'roles', function($q) {
					$q->whereIn('name', ['clientuser','requester','approval','reviewer']);
				}
			)->where('users.created_by',$user_id)->get();
	   }else{
		   $client_users = User::whereHas(
            'roles', function($q) {
                $q->whereIn('name', ['clientuser','requester','approval','reviewer']);
            }
			)->get();	   
	   }
	   
        return $client_users;
	}
	/*  get client roles list*/  

	/* Getting Translator & Reviewer Users lists */
	public static function getuserdetails($user_id)
	{
		if($user_id){
			$user=User::whereHas(
				'roles', function($q) {
					$q->whereIn('roles.name', ['requester','approval','reviewer','clientuser']);
				}
			)->where(['users.id'=>$user_id])->first();
			return $user;
		}
		return array();
	}
}
