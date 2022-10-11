<?php
use App\loc_languages;
use App\loc_request;
use App\user;
use Spatie\Permission\Models\Role;
use App\settings;
use App\currencies;
use App\models\Historylogs;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

function checkpermission($per,$action='view'){
	if ($per != '') {
			//$roleid=Auth::user()->roles[0]->id;
			$roles=Auth::user()->roles;
			$userid=Auth::user()->id;
			foreach ($roles as $role) {
				$roleid=$role->id;
            	$res=DB::table('role_has_permissions as rp')->join('permissions as p','rp.permission_id', '=','p.id')->where(['rp.role_id'=>$roleid,'p.name'=>$per,'rp.'.$action=>'1'])->get();
	            if(count($res) > 0){
	            	return true;
	            }
        	}
        }
        return false;
}

function checkallpermission($per,$action='view'){
	if (count($per) > 0) {
		// $roleid=Auth::user()->roles[0]->id;
		$roles=Auth::user()->roles;
		$userid=Auth::user()->id;
		foreach ($roles as $role) {
			$roleid=$role->id;
            $res=DB::table('role_has_permissions as rp')->join('permissions as p','rp.permission_id', '=','p.id')->where(['rp.role_id'=>$roleid,'rp.'.$action=>'1'])->whereIn('p.name',$per)->get();
            if(count($res) > 0){
            	return true;
            }
		}
        }
        return false;
}

function checkrolepermission($role_id,$permission_id,$action='view')
{
	$permissions=App\models\Role_permissions::where(['role_id'=> $role_id,'permission_id'=>$permission_id,$action => '1'])->get()->toArray();
	if(count($permissions) > 0){
		return true;
	}
	return false;
}

function get_user_org($type = "id",$sub_par=''){
	$userid= Auth::user()->id;
	if($type == 'org'){
		$org=App\user_orgizations::where(['user_id'=> $userid])->get();
		return $org[0]->$sub_par;
	}elseif($type == 'clientorg'){
		$org=App\client_user_orgizations::where(['user_id'=> $userid])->get();
		return $org[0]->$sub_par;
	}else{
		$role= Auth::user()->roles[0]->$type;
		return $role;
	}
}
function getlangbyid($langcode)
{
$res=loc_languages::select('lang_name')->where('lang_id',$langcode)->first();
return $res['lang_name'];
}
function getusernamebyid($user_id,$field='name')
{
	if($user_id != ''){
		$res=user::where('id',$user_id)->first();
		if($res){
			return $res[$field];
		}
	}
	return '';
}

function getrolename($field='')
{
	if($field != ''){
		$res=Role::where('name',$field)->first();
		return $res['label'];
	}
	return '';
}
function gettabledata($table,$field='',$where='')
{
	if($where){
		$res=DB::table($table)->where($where)->get()->toArray();
	}else{
		$res=DB::table($table)->get()->toArray();
	}
	if($field != ''){
		if($res){
			return $res[0]->$field;
		}else{
			return '';
		}
	}else{
		return $res;
	}
}

function getcrstatus($status='')
{ $text='';
	if($status == 'new')   //switch ($i) { case ($status == 'new') : $text='New'; break;
	{
		$text='New';
	}elseif($status == 'approve')
	{
		$text='Accept';
	}
	elseif($status == 'tr_assign')
	{
		$text='Assign '.getrolename('translator');
	}
	elseif($status == 'tr_inprogress')
	{
		$text= getrolename('translator').' in Progress';
	}
	elseif($status == 'tr_completed')
	{
		$text=getrolename('translator'). 'Completed';
	}
	elseif($status == 'v_assign')
	{
		$text='Assign' .getrolename('vendor');
	}
	elseif($status == 'v_inprogress')
	{
		$text=getrolename('vendor').' in Progress';
	}
	elseif($status == 'v_completed')
	{
		$text=getrolename('vendor'). 'Completed';
	}
	elseif($status == 'qa_assign')
	{
		$text='Assign to QA';
	}
	elseif($status == 'qa_inprogress')
	{
		$text='QA in Progress';
	}
	elseif($status == 'qa_accept')
	{
		$text='QA Accept';
	}
	elseif($status == 'qa_reject')
	{
		$text=' QA Reject';
	}
	elseif($status == 'pr_assign')
	{
		$text='Assign To PR';
	}
	elseif($status == 'pr_inprogress')
	{
		$text='PR in Progress';
	}
	elseif($status == 'pr_accept')
	{
		$text='PR Accept';
	}
	elseif($status == 'pr_reject')
	{
		$text='PR Reject';
	}
	elseif($status == 'publish')
	{
		$text='Publish';
	}
	elseif($status == 're_accept')
	{
		$text='Reviewer Accept';
	}
	elseif($status == 're_reject')
	{
		$text='Reviewer Reject';
	}
	elseif($status == 'pm_reject')
	{
		$text='Reject the Request';
	}
	elseif($status == 'client_cancel')
	{
		$text='cancel the Request';
	}


	return $text;
}

function showcrstatus($status='')
{ $text='';
	if($status == 'new')
	{
		$text='Waiting for Approval';

	}elseif($status == 'approve')
	{
		$text='New Request';

	}
	elseif($status == 'tr_assign')
	{
		$text= getrolename('translator'). 'Assigned';

	}
	elseif($status == 'tr_inprogress')
	{
		$text=getrolename('translator').'is Working';
	}
	elseif($status == 'tr_completed')
	{
		$text=getrolename('translator').' Completed';
	}
	elseif($status == 'v_assign')
	{
		$text=getrolename('Vendor').'Assigned';

	}
	elseif($status == 'v_inprogress')
	{
		$text= getrolename('Vendor').'is Working';
	}
	elseif($status == 'v_completed')
	{
		$text=getrolename('Vendor').' Completed';
	}
	elseif($status == 'qa_assign')
	{
		$text='Assigned to Quality Analyst';
	}
	elseif($status == 'qa_inprogress')
	{
		$text='Quality Analyst is Working';
	}
	elseif($status =='qa_reject')
	{
		$text='Quality Analyst has Rejected';
	}
	elseif($status == 'qa_accept')
	{
		$text='Quality Analyst has Approved';
	}
	elseif($status == 'pr_assign')
	{
		$text='Assigned to Proof Reader';
	}
	elseif($status == 'pr_inprogress')
	{
		$text='Proof Reader is Working';
	}
	elseif($status == 'pr_reject')
	{
		$text='Rejected by Proof Reader';
	}
	elseif($status == 'pr_accept')
	{
		$text='Approved by Proof Reader';
	}
	elseif($status == 'publish')
	{
		$text='Published by Project Manager';
	}
	elseif($status == 're_reject')
	{
		$text='Rejected by Reviewer';
	}
	elseif($status == 're_accept')
	{
		$text='Accepted by Proof Reader';
	} 
	elseif($status == 'pm_reject')
	{
		$text='Reject By Project Manager';
	}
	elseif($status == 'client_cancel')
	{
		$text='cancelled by Approver/Requester';
	}
	return $text;
}

function settingsdata($key)
{
	$res=settings::select('description')->where('key',$key)->first();
	return $res['description'];
}
function checkcurrency($amount,$currency,$round=false,$symbol=true,$type='id',$page='web')
{
	$symbol_sign='₹';
	$where=[$type=>$currency];
	$res=currencies::where($where)->first();
	if($res){
		//$total = $res->unit*($amount/$res->inr);
		$g_total=$amount;
		if($symbol){
			if(strtolower($res->currency_code) == 'inr' && $page =='pdf'){
				$symbol_sign='<span style="font-family: DejaVu Sans;">&#x20B9;</span>';
			}else{
				$symbol_sign=$res->currency_symbol;
			}
		}
	}else{
		$g_total=$amount;
	}
	if($round){
		$g_total=round($g_total);
	}else{
		$g_total=number_format($g_total,2);
	}
	if($symbol){
		$final=$symbol_sign.' '.$g_total;
	}else{
		$final=$g_total;
	}
	return $final;
} 
function convertcurrency($price,$main_currency,$sub_currency)
{
	// $where=['id'=>$main_currency];
	// $res_main_currency=currencies::where($where)->first();
//echo $price.','.$main_currency.','.$sub_currency.','.$currency_cost;die;
	// $where1=['id'=>$sub_currency];
	// $res_sub_currency=currencies::where($where1)->first();
	// if($currency_cost == ''){
	// 	$currency_cost = $res_main_currency->inr;
	// }
	// if($main_currency > $sub_currency){
	// 	$total = ($price/$main_currency);
	// }else{
	// 	$total = ($price*$sub_currency);
	// }
	$total=($price*$sub_currency)/$main_currency;
	//return checkcurrency($total,$main_currency);
	return $total;
}
function get_image_s3($path){
	$url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
}

function encimagedata($url,$a_type='image')
    {
        //return $url;    
        $type = pathinfo($url, PATHINFO_EXTENSION);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec ($ch);
        curl_close ($ch); 
        $data=$content;
        //$data = file_get_contents($url);
        $logo_path = 'data:'.$a_type.'/' . $type . ';base64,' . base64_encode($data);
        return $logo_path;
    }

function side_menu_open($menu_open)
{
	if(is_countable($menu_open)){
		for ($i=0; $i < count($menu_open); $i++) { 
			if(request()->is('admin/'.$menu_open[$i]) || request()->is('admin/'.$menu_open[$i].'/*')){
				return true;
			}
		}
	}
	return false;
}
function getuserbasesroleslist($user_role = '')
{
	if($user_role == "administrator"){
		$roles = Role::select('id','label','name')->get()->whereIn('name', ['orgadmin','departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);		
	}
	elseif($user_role == "orgadmin" || $user_role == "projectmanager"){
		$roles = Role::select('id','label','name')->get()->whereIn('name', ['departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);
	}
	elseif($user_role == "suborgadmin"){
		$roles = Role::select('id','label','name')->get()->whereIn('name', ['departmentadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);
	}
	else{

		$roles = Role::select('id','label','name')->get()->whereIn('name', ['requester','approval','reviewer']);
	}
	return $roles;
}

function generateemployeeID()
{ 
	return random_strings();
}
function random_strings()
{
    $str_result = '0123456789';
    $res='RL'.substr(str_shuffle($str_result), 
                       0, 4);
    $res_data=user::where('employee_id',$res)->count();
    if($res_data > 0){
        return random_strings();
    }
    return $res;
}
function createlog($action,$message,$main_id='',$lookup_table='loc_request',$type='request',$created_by='')
{
	$roles=Auth::user()->roles;
	$userid=Auth::user()->id;
	$data['action']=$action;
	$data['message']=$message;
	$data['main_id']=$main_id;
	$data['lookup_table']=$lookup_table;
	$data['type']=$type;
	if($created_by != ''){
		$data['created_by']=$created_by;	
	}else{
		$data['created_by']=$userid;
	}
	return Historylogs::insert($data);
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}