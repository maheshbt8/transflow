<?php
    
namespace App\Console\Commands;
use App\Kptorganization;
use App\KptSubOrganizations;
use App\User;
use App\user_orgizations;
use Auth;
    
use Illuminate\Console\Command;
use App\models\loc_translation_master;


class ReadyInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:ready';
     
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ready for Invoice';
     
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
     
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $translation = loc_translation_master::whereIn('client_amnt_status', ['booked'])->where('pm_id','!=','')->whereDate('created_at','>=','2022-07-01 00:00:00')->orderBy('created_at', 'DESC')->get();
  
        $subject = '';
        foreach ($translation as $tr) {
            $unix=date('Y-m-d 00:00:00',strtotime($tr->pm_assigned_date));
               
            $week=$tr->weeks ?? 1;
          
            $add = date("Y-m-d H:i:s", strtotime($unix) + ($week*7*86400));
            $newDate = $add;
            $currentDate=date("Y-m-d H:i:s");
            $days = (strtotime($currentDate) - strtotime($newDate)) / (60 * 60 * 24);
            $days= round($days, 0);
            if($days == 15){
                $subject="Long-Overdue for invoicing";
                $sendmail=1;
            }elseif($days == 8){
                $subject="Overdue Payment";
                $sendmail=1;
            }elseif($days == 1){
                $subject="Ready for invoice";
                $sendmail=1;
            }else{
                $subject="Waiting for response";
                $sendmail=0;
            }
            if($sendmail == 1){
                //$tr = loc_translation_master::where(['translation_quote_id'=>18])->first();
                // \Log::info(json_encode($tr));die;
                 $org_id=$tr->organization;
                 $authenticated_users = User::whereHas(
                     'roles',
                     function ($q) {
                         $q->where('name', 'orgadmin');
                     }
                 )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get();
              
                 $orgadmin = $authenticated_users->toArray();
               
                 $orgadmin_emails = array_column((array)$orgadmin, 'email');
               
                 $client_c_name=$tr->client_comp_name;
                 $first_name=$tr->first_name;
                 $last_name=$tr->last_name;
                 $email=$tr->email;
                 $created_at=$tr->created_at;
                 $userid=$tr->translation_user_id;
                 $order_id=$tr->quote_code;
                 $maintblid=$tr->translation_quote_id;
                 $final_grand_total=$tr->grand_total;
                 $currency=$tr->currency_cost;
                 $mob_number=$tr->mob_number;
                 $pm_id=$tr->pm_id;
                 $sales_email = getusernamebyid($userid, 'email');
                 $pm_email = getusernamebyid($pm_id, 'email');
                 $email_m = [$sales_email];
                 $ccemail = [$pm_email]; //[$email];
                 $ccemail = array_merge($orgadmin_emails, $ccemail);
                 $mailData = [
                     'title' => $subject.'|'. $order_id . '|' . checkcurrency($final_grand_total,$currency,false, true).'|'.ucwords($client_c_name).'|'.ucwords(getusernamebyid($userid)),
                     'subject' => $subject.'|'.$order_id.'|'. checkcurrency($final_grand_total,$currency,false,true,'id',false,false,'id',false,'pdf').'|'.ucwords($client_c_name).'|'.ucwords(getusernamebyid($userid)),
                     'translation_quote_id'=> getclientid($maintblid), 
                     'quote_code' => $order_id,
                     'company_name' => ucwords($client_c_name),
                     'name' => ucwords($first_name . ' ' . $last_name),
                     "number" => $mob_number,
                     'email' => $email,
                     'date' => $created_at,
                     'created_by' => ucwords(getusernamebyid($userid)),
                     'quote_url' => env('APP_URL') . '/admin/editquote/' . $order_id
                 ];
             
                 $res = sendquotemail($email_m, $mailData, $ccemail);
         
                
               
            }
        }

       
        
        /*
           Write your database logic we bellow:
           Item::create(['name'=>'hello new']);
        */
    }
}