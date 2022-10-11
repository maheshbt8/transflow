<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\KptDepartments;
use App\Kptorganization;
use App\KptSubOrganizations;
use App\User;
use App\user_orgizations;
use Auth;
use App\models\ratecard_model;
use App\models\quotegeneration_model;
use App\models\my_model;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Exports\QuoteExport;
use Maatwebsite\Excel\Facades\Excel;
use App\quote_generation_vendor;
use App\locService;
use App\locQuoteService;
use App\loc_request;
use App\locQuoteSourcelang;
use App\loc_languages;
use App\locrequestassigned;
use App\loc_ratecard;
use File;
use App\Clientorganization;
use App\models\loc_translation_child;
use App\models\loc_translation_master;
use LocQuoteSourcelang as GlobalLocQuoteSourcelang;
use phpDocumentor\Reflection\Types\Null_;
use App\currencies;
use Storage;
use App\address;
use App\loc_po;
use App\terms_conditions;
use App\loc_quote_history;
use PhpParser\Node\Expr\Print_;
use Stancl\Tenancy\Features\Timestamps;

class QuotegenerationController extends Controller
{
    public function test()
    {

        $mailData = [
            'title' => 'Quote Generated',
            'subject' => 'Generated New Quote with 123',
            'quote_code' => '123',
            'company_name' => 'Mahi',
            'name' => 'MKai',
            "number" => 'sadsa',
            'email' => 'dsadsa',
            'date' => 'dsadsad',
            'created_by' => 'dsadsadsa',
            'quote_url' => env('APP_URL') . '/admin/editquote/123'
        ];
        print_r($mailData);
        die;
        //return view('Email.quotemail', compact('mailData'));
        $res = sendquotemail(['spilli@keypoint-tech.com'], $mailData, ['pillisaikumar4200@gmail.com']);
        /*
   $res=sendquotemail($email_m,$mailData,$ccemail);*/
        /*$file_name = 'file.pdf';
$file_url = "https://d1458le5lr1pr2.cloudfront.net/request/target/'.$file_name";
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"".$file_name."\"");
readfile($file_url);*/
        exit;
    }
    public function index()
    {
        if (!checkpermission('qoute_generation')) {
            return abort(401);
        }

        $req_status = 'booked';
        if (isset($_GET['status']) && $_GET['status'] != '') {
            $req_status = $_GET['status'];
        }
        $where_req = [];
        if (isset($_GET['sales']) && $_GET['sales'] != '') {
            $where_req['translation_user_id'] = $_GET['sales'];
        }
        if (isset($_GET['client']) && $_GET['client'] != '') {
            $where_req['client_org_id'] = $_GET['client'];
        }
        $sdate = date('Y-m-d', strtotime('today - 29 days'));
        $edate = date('Y-m-d');
        if (isset($_GET['sdate']) && $_GET['sdate'] != '') {
            $sdate = $_GET['sdate'];
        }
        if (isset($_GET['edate']) && $_GET['edate'] != '') {
            $edate = $_GET['edate'];
        }

        $user_role = Auth::user()->roles[0]->name;
        if ($user_role == 'administrator') {
            $created_by = '';
            $org_id = '';
        } else {
            $created_by = Auth::user()->id;
            $org_id = get_user_org('org', 'org_id');
        }

        $date_range = [$sdate . ' 00:00:00', $edate . ' 23:59:59'];
        $getst_lang = new loc_translation_master();

        $role_type = 'projectmanager';
        if ($user_role == 'administrator') {
            $pm_users = json_encode(user::select('id', 'name')->whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'projectmanager');
                }
            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get());
        } else {
            $pm_users = json_encode(user::select('id', 'name')->whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'projectmanager');
                }
            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->where('id', '!=', Auth::user()->id)->get());
        }

        if ($user_role == 'orgadmin') {
            // echo $user_role;die;
            //$translation = loc_translation_master::whereIn('translation_status', ['Assign', 'invoiced', 'assign_vendor'])->where('organization', $org_id)->orderBy('created_at', 'DESC')->get();
            $where_status = 'organization = ' . $org_id;
            if ($req_status == 'all') {
                $translation = loc_translation_master::whereIn('translation_status', ['Open', 'Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->whereRaw($where_status)->where($where_req)->orderBy('created_at', 'DESC')->get();
            } else {
                $where_status .= ' and client_amnt_status = "' . $req_status . '"';
                $translation = loc_translation_master::whereIn('translation_status', ['Open', 'Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->whereRaw($where_status)->where($where_req)->orderBy('created_at', 'DESC')->get();
            }
        } elseif ($user_role == 'administrator') {
            //  $where_status='organization = '.$org_id;
            if ($req_status == 'all') {
                $translation = loc_translation_master::whereIn('translation_status', ['Open', 'Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->where($where_req)->orderBy('created_at', 'DESC')->get();
            } else {
                // $where_status .=' and client_amnt_status = "'.$req_status.'"';
                $translation = loc_translation_master::whereIn('translation_status', ['Open', 'Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->where($where_req)->orderBy('created_at', 'DESC')->get();
            }
        } elseif ($user_role == 'projectmanager') {
            // echo $user_role;die;
            /*$translation = loc_translation_master::whereIn('translation_status', ['Assign', 'invoiced', 'assign_vendor'])->where(['organization' => $org_id, 'pm_id' => Auth::user()->id])->orderBy('created_at', 'DESC')->get();*/
            $where_status = 'organization = ' . $org_id;
            if ($req_status == 'all') {
                $translation = loc_translation_master::whereIn('translation_status', ['Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->whereRaw($where_status)->where($where_req)->orderBy('created_at', 'DESC')->get();
            } else {
                $where_status .= ' and client_amnt_status = "' . $req_status . '" and pm_id = ' . Auth::user()->id;
                $translation = loc_translation_master::whereIn('translation_status', ['Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->whereRaw($where_status)->where($where_req)->orderBy('created_at', 'DESC')->get();
            }
        } elseif ($user_role == 'sales') {
            //$translation = loc_translation_master::whereIn('translation_status', ['Open', 'Assign', 'invoiced', 'assign_vendor'])->where(['organization' => $org_id, 'translation_user_id' => Auth::user()->id])->orderBy('created_at', 'DESC')->get();

            $where_status = 'organization = ' . $org_id;
            if ($req_status == 'all') {
                $translation = loc_translation_master::whereIn('translation_status', ['Open', 'Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->whereRaw($where_status)->where($where_req)->orderBy('created_at', 'DESC')->get();
            } else {
                $where_status .= ' and client_amnt_status = "' . $req_status . '" and translation_user_id = ' . Auth::user()->id;
                $translation = loc_translation_master::whereIn('translation_status', ['Open', 'Assign', 'invoiced', 'assign_vendor'])->whereBetween('created_at', $date_range)->whereRaw($where_status)->where($where_req)->orderBy('created_at', 'DESC')->get();
            }
        } elseif ($user_role == 'vendor') {
            //$translation = loc_translation_master::whereIn('translation_status', 'invoiced')->orderBy('created_at', 'DESC')->get();
        } else {
            $translation = array();
        }
        $loc_request = new loc_request();

        $request_data = loc_request::where('reference_id')->first();

        return view('admin.quotegeneration.index', compact('created_by', 'translation', 'pm_users', 'getst_lang', 'loc_request', 'request_data', 'req_status', 'sdate', 'edate'))->with(['page_title' => 'Generated Quotes List']);
    }
    public function create()
    {
        if (!checkpermission('qoute_generation')) {
            return abort(401);
        }

        $user_role = Auth::user()->roles[0]->name;
        if ($user_role == 'administrator') {
            $created_by = '';
        } else {
            $created_by = Auth::user()->id;
        }

        $users = User::getAuthenticatedUsers($created_by);
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->get();
        $loc_services = locService::orderBy('id', 'ASC')->get();
        $org_list = Kptorganization::get();

        $currency_list = currencies::where('status', 'Active')->get();
        if ($user_role == 'administrator') {
            $org_id = '';
        } else {
            $org_id = get_user_org('org', 'org_id');
        }


        $org_addr = address::where(["user_id" => $org_id, "type" => "org"])->get();

        // $client_org = clientorganization::where(["org_id"=>$request_data->client_org_id])->first();
        //$clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $org_id])->get();

        if ($user_role == 'orgadmin' || $user_role == 'projectmanager') {
            $clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $org_id])->get();
        } elseif ($user_role == 'sales') {
            $clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $org_id, 'created_by' => Auth::user()->id])->get();
        } elseif ($user_role == 'administrator') {
            $clientorganizations = Clientorganization::where(['org_status' => '1'])->get();
        }

        //$client_org_id=$clientorganizations[0]->org_id;

        /*$client_users = User::whereHas(
            'roles',
            function ($q) {
                $q->whereIn('name', ['clientuser']);
            }
        )->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.org_id', $client_org_id)->get();*/
        $client_users = [];
        $users = User::getAuthenticatedUsers($created_by);
        $terms = terms_conditions::get();
        return view('admin.quotegeneration.create', compact('users', 'loc_languages', 'org_list', 'loc_services', 'currency_list', 'org_addr', 'clientorganizations', 'client_users', 'terms'))->with(['page_title' => 'Generation New Quote']);
    }
    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userid = Auth::user()->id;
        // echo "<pre>";print_r($userid);die;
        $employe_id = User::where('id', $userid)->first();
        $org_id = get_user_org('org', 'org_id');
        //  print_r($org_id);die;
        //  $project = loc_translation_master::get();

        //  $client_org_id= $project[0]->client_org_id;




        // echo "<pre/>";     print_r($project_counts);die;

        $org_addr = address::where(["user_id" => $org_id, "type" => "org"])->get();
        $emp_id = $employe_id->employee_id;
        if ($_POST['Generate_Quote'] == "Generate Quote") {
            $pdf = new Dompdf();
            $validated = $request->validate([]);

            // dd($_POST);
            $request_id = $_POST['request_id'] ?? '';
            $first_name        = $_POST['first_name'];
            $last_name         = $_POST['last_name'];
            $mob_number        = $_POST['mob_number'];
            $email             = $_POST['email'];
            $org               = $_POST['org'];
            $client_org        = $_POST['client_org'];
            $to_address        = $to_address_db  = gettabledata('address', 'address', ['id' => $_POST['to_address']]);
            $to_address_id        = $_POST['to_address'];
            $subject             = nl2br($_POST['subject']);
            $termsofuse            = nl2br($_POST['terms_of_use']);
            $current_date        = $_POST['current_date'];
            $gst_type            = $_POST['gst_type'];
            $quote_type            = $_POST['quote_type'];
            $currency            = $_POST['currency'];
            $currency_cost       = gettabledata('currencies', 'inr', ['id' => $currency]);
            $quote_heading       = $_POST['quote_heading'];
            $pmcost       =  $_POST["pm_cost"];
            $source_lan            =  $_POST["source_language"];
            $client_c_name = $_POST['comp_name'];
            $product_delivery = $_POST['weeks'];
            $aa_dd = $_POST["org_adrs"];
            $org_addr = address::where(["id" => $aa_dd, "type" => "org"])->first();

            $created_at = date('Y-m-d H:i:s');
            $kpt_org = Kptorganization::where('org_id', $org)->first();
            $orgs = $kpt_org->org_name;

            if ($current_date == "") {
                $current_date = date("d/m/Y");
            }

            $quote_generation = new loc_translation_master;
            $q = (($quote_type == 'quote') ? "Q" : "S");

            if (preg_match("/(india)/i", $org_addr->address)) {
                $qst = $q . 'IN';
            } elseif (preg_match("/(usa|us)/i", $org_addr->address)) {
                $qst = $q . 'US';
            } elseif (preg_match("/(UK|Scotland)/i", $org_addr->address)) {
                $qst = $q . 'UK';
            } else {
                $qst = $q;
            }
            $order_id =  $qst . date("ymd");
            $quote_generation->quote_code = $order_id;
            $quote_generation->first_name = $first_name;
            $quote_generation->last_name = $last_name;
            $quote_generation->client_comp_name = $client_c_name;
            $fullname = $quote_generation->first_name . " " . $quote_generation->first_name;

            $quote_generation->email = $email;
            $quote_generation->organization = $org;
            $quote_generation->client_org_id = $client_org;
            $quote_generation->mob_number = $mob_number;
            $quote_generation->translation_quote_date = $current_date;
            $quote_generation->translation_quote_subject = ucfirst($subject);
            $quote_generation->translation_quote_gst = $gst_type;
            $quote_generation->quote_type = $quote_type;
            $quote_generation->translation_quote_termuse = $termsofuse;
            $quote_generation->translation_quote_currency = $currency;
            $quote_generation->currency_cost = $currency_cost;
            $quote_generation->to_address_id = $to_address_id;

            $quote_generation->translation_quote_address = ucfirst($to_address_db);
            $quote_generation->payment_type = 'partial';
            $quote_generation->translation_status = 'open';
            $quote_generation->translation_user_id = $userid;
            $quote_generation->created_at = $created_at;

            $quote_generation->address_id = $aa_dd;
            $quote_generation->weeks = $product_delivery;

            $quote_generation->save();


            $maintblid = $quote_generation->id;



            //echo $maintblid;die;

            if ($maintblid > 0) {
                $order_id = $qst . date("ymd") . '' . $maintblid . '_HY' . $emp_id;
                $q_id_update = array('quote_code' => $order_id, 'quote_file' => $order_id . '.pdf', 'project_id' => getclientid($maintblid));

                $res =    loc_translation_master::where('translation_quote_id', $maintblid)->update($q_id_update);
                //  print_r($res);die;
                if ($request_id != '') {
                    $locrequestassige = loc_request::where('req_id', $request_id)->update(['quote_gen_id' => $maintblid]);
                }
                //$image = base_path() . "/public/storage/org_images/" . $org . '.png';
                //$project=   getclientid();
                //  print_r($project);die;


                $image = env('AWS_CDN_URL') . '/org_images/' . $org . '.png';
                /*if (file_exists($image)) {
                echo "string";die;*/
                $type = pathinfo($image, PATHINFO_EXTENSION);
                $data = file_get_contents($image);
                $logo_path = 'data:image/' . $type . ';base64,' . base64_encode($data);
                /*} else {
                $logo_path = '';
            }*/
                $html = '<html><head>
            <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
            <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
        </head>';
                $html .= '<table width="100%" cellpadding="5" border="0">
             <tbody>
             <tr>
             <td style="width:50%;text-align:left;"><img src="' . $logo_path . '" width="174" height="60" border="0"></td>          
             <td style="font-family:sans-serif; width=50%;text-align:right;font-size:13px">' . $org_addr->address . '</td>
                </tr>
                </tbody>
                </table>';
                $html .= '<hr style="width: 100%;
                border: 6px dotted #5184AF;
                border-style: none none dotted" />';


                // $html .= '<div style="border:1px solid #000; height:1px; bachground-color:#000;">&nbsp;</div><br>';


                if ($quote_heading == "yes") {
                    $html .= '<div style="font-family:sans-serif; text-align:center;font-size:13px;color:#528bbb;">PROFORMA INVOICE </div>';
                } elseif ($quote_heading == "po") {
                    $html .= '<div style="font-family:sans-serif; text-align:center;font-size:13px;color:#528bbb;">PURCHASE ORDER</div>';
                }

                $html .= '<div class="col-md-6" style="text-align:left; font-family:sans-serif;font-size:13px">Quote ID: ' . $order_id . '</div><div class="col-md-6" style=" margin-top:-22px;text-align:right; font-family:sans-serif;font-size:13px">MSME Registration Number: <b>UDYAM-TS-09-0000576</b></div>';
                // $html .= '<div style="text-align:right; font-family:sans-serif;">MSME Registration Number: <b>UDYAM-TS-09-0000576</b></div><br>';              

                $html .= ' <div style="text-align:right; font-family:sans-serif;font-size:13px">Reference ID: ' .  getclientid($maintblid) . '</div>';
                $html .= '<div style="text-align:right; font-family:sans-serif;font-size:13px">DATE: ' . date("j M, Y", strtotime($current_date)) . '</div>          
                <div style="font-family:sans-serif;font-size:13px"> To,<br>' . ucfirst($first_name) . ' ' . $last_name . ',<br>' . $client_c_name . ',<br>'  . ucfirst($to_address_db) . '<br>Email: ' . $email . ',<br> Mobile Number: ' . $mob_number . '</div>';
                $html .= '<div style="font-family:sans-serif;font-size:13px">Subject: ' . ucfirst($subject) . '</div><br>
                <div>
                <table style="width:100%; margin-left: auto;
                margin-right: auto; border: 1px solid black;border-collapse: collapse;" cellpadding="5" >';

                $currency_value        = "";
                $currency_code = gettabledata('currencies', 'currency_code', ['id' => $currency]);
                $currency_label     = "Amount (" . $currency_code . ")";


                loc_translation_master::where('translation_quote_id', $maintblid)->update(['quote_code' => $order_id]);
                if ($request_id) {
                    loc_request::where('req_id', $request_id)->update(['reference_id' => $order_id]);
                }

                $grand_total = 0;
                $html .= '<tr style="background-color:#babec1;font-family:sans-serif;font-size:14px">
                        <td style="border: 1px solid black;">SN.</td>
                        <td style="border: 1px solid black;">Work Type</td>
                        <td style="border: 1px solid black;">Language Pair</td>
                        <td style="border: 1px solid black;">Description</td>
                        <td style="border: 1px solid black;">Item</td>
                        <td style="border: 1px solid black;">Unit/rate</td>
                        <td style="border: 1px solid black;">' . $currency_label . '</td>
                        </tr>';
                $coun = 1;
                for ($i = 0; $i < count($source_lan); $i++) {
                    $target_lan               = ((isset($_POST["destination_language_" . $i]) && $_POST["destination_language_" . $i] != '') ? $_POST["destination_language_" . $i] : Null);
                    if ($target_lan != '') {
                        $description = ((isset($_POST['description_' . $i]) && $_POST['description_' . $i] != '') ? $_POST['description_' . $i] : Null);
                        $words_count = ((isset($_POST['words_count_' . $i]) && $_POST['words_count_' . $i] != '') ? $_POST['words_count_' . $i] : Null);
                        $cost_per_word            = ((isset($_POST['cost_per_word_' . $i]) && $_POST['cost_per_word_' . $i] != '') ? $_POST['cost_per_word_' . $i] : Null);
                        $word_fixed_cost          = ((isset($_POST['word_fixed_cost_' . $i]) && $_POST['word_fixed_cost_' . $i] != '') ? $_POST['word_fixed_cost_' . $i] : Null);
                        $page_count               = ((isset($_POST['page_count_' . $i]) && $_POST['page_count_' . $i] != '') ? $_POST['page_count_' . $i] : Null);
                        $cost_per_page            = ((isset($_POST['cost_per_page_' . $i]) && $_POST['cost_per_page_' . $i] != '') ? $_POST['cost_per_page_' . $i] : Null);
                        $page_fixed_cost          = ((isset($_POST['page_fixed_cost_' . $i]) && $_POST['page_fixed_cost_' . $i] != '') ? $_POST['page_fixed_cost_' . $i] : Null);
                        $minute_words_count       = ((isset($_POST['minute_words_count_' . $i]) && $_POST['minute_words_count_' . $i] != '') ? $_POST['minute_words_count_' . $i] : Null);
                        $minute_cost_per_word     = ((isset($_POST['minute_cost_per_word_' . $i]) && $_POST['minute_cost_per_word_' . $i] != '') ? $_POST['minute_cost_per_word_' . $i] : Null);
                        $minute_fixed_cost        = ((isset($_POST['minute_fixed_cost_' . $i]) && $_POST['minute_fixed_cost_' . $i] != '') ? $_POST['minute_fixed_cost_' . $i] : Null);
                        $resource_words_count       = ((isset($_POST['resource_words_count_' . $i]) && $_POST['resource_words_count_' . $i] != '') ? $_POST['resource_words_count_' . $i] : Null);
                        $cost_per_resource     = ((isset($_POST['resource_cost_per_word_' . $i]) && $_POST['resource_cost_per_word_' . $i] != '') ? $_POST['resource_cost_per_word_' . $i] : Null);
                        $resource_fixed_cost        = ((isset($_POST['resource_fixed_cost_' . $i]) && $_POST['resource_fixed_cost_' . $i] != '') ? $_POST['resource_fixed_cost_' . $i] : Null);
                        $target_lan               = ((isset($_POST["destination_language_" . $i]) && $_POST["destination_language_" . $i] != '') ? $_POST["destination_language_" . $i] : Null);
                        $source_language_text = loc_languages::where('lang_id', $source_lan[$i])->first();
                        $source_language_text = $source_language_text->lang_name;
                        $service = ((isset($_POST['service_type_' . $i]) && $_POST['service_type_' . $i] != '') ? $_POST['service_type_' . $i] : Null);
                        //$types_service=implode(',',$service);

                        if ($request_id == '') {
                            $language = new locQuoteSourcelang();
                            $language->quote_id = $maintblid;
                            $language->sourcelang_id = $source_lan[$i];
                            $language->description = $description;
                            $language->save();
                            $source_id = $language->id;
                        } else {
                            $update_id = array('quote_id' => $maintblid, 'description' => $description);
                            $locquotesource = locQuoteSourcelang::where(['request_id' => $request_id, 'sourcelang_id' => $source_lan[$i]])->update($update_id);
                            $loc_source_id_get = locQuoteSourcelang::where(['request_id' => $request_id, 'sourcelang_id' => $source_lan[$i]])->first();
                            $source_id = $loc_source_id_get->id;
                        }

                        $getdesc = locQuoteSourcelang::where('id', $source_id)->first();

                        $getdescription = $getdesc->description;

                        $loc_service1 = locService::whereIn('id', $service)->get('service_type')->toArray();

                        $service_types = implode(', ', array_column((array)$loc_service1, 'service_type'));

                        for ($j = 0; $j < count($target_lan); $j++) {
                            $total = 0;
                            $target_language_text = loc_languages::where('lang_id', $target_lan[$j])->first();
                            $target_language_text = $target_language_text->lang_name;
                            $loc_quotegeneration = array('quote_id' => $maintblid, 'loc_source_id' => $source_id, 'target_language' => $target_lan[$j], 'service_type' => $service[$j]);
                            if ($word_fixed_cost) {
                                $loc_quotegeneration['word_count'] = $words_count;
                                $loc_quotegeneration['word_fixed_cost'] = $word_fixed_cost;
                                $total = $total + $word_fixed_cost;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . ucfirst($getdescription) . '</td>
                                        <td style=" border: 1px solid black;">' .  $words_count . ' (Words)</td>
                                        <td style="text-align: right;border: 1px solid black;">NA</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($word_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            } elseif ($words_count != '' && $cost_per_word != '') {
                                $loc_quotegeneration['word_count'] = $words_count;
                                $loc_quotegeneration['per_word_cost'] = trim($cost_per_word);
                                $row_total = ($words_count * $cost_per_word);
                                $total = $total + $row_total;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' .  $words_count . ' (Words)</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($cost_per_word, $currency, false, true, 'id', 'pdf') . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            }
                            if ($page_fixed_cost) {
                                $loc_quotegeneration['page_count'] = $page_count;
                                $loc_quotegeneration['page_fixed_cost'] = $page_fixed_cost;
                                $total = $total + $page_fixed_cost;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style=" border: 1px solid black;">' .  $page_count . ' (Pages)</td>
                                        <td style="text-align: right;border: 1px solid black;">NA</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($page_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            } elseif ($page_count != '' && $cost_per_page != '') {
                                $loc_quotegeneration['page_count'] = $page_count;
                                $loc_quotegeneration['per_page_cost'] = $cost_per_page;
                                $row_total = ($page_count * $cost_per_page);
                                $total = $total + $row_total;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . $page_count . ' (Pages)</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($cost_per_page, $currency, false, true, 'id', 'pdf') . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            }
                            if ($minute_fixed_cost) {
                                $loc_quotegeneration['minute_count'] = $minute_words_count;
                                $loc_quotegeneration['minute_fixed_cost'] = $minute_fixed_cost;
                                $total = $total + $minute_fixed_cost;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style=" border: 1px solid black;">' .  $minute_words_count . ' (Minutes)</td>
                                        <td style="text-align: right;border: 1px solid black;">NA</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($minute_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            } elseif ($minute_words_count != '' && $minute_cost_per_word != '') {

                                $loc_quotegeneration['minute_count'] = $minute_words_count;
                                $loc_quotegeneration['per_minute_cost'] = $minute_cost_per_word;
                                $row_total = ($minute_words_count * $minute_cost_per_word);
                                $total = $total + $row_total;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . $minute_words_count . ' (Minutes)</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($minute_cost_per_word, $currency, false, true, 'id', 'pdf') . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            }

                            if ($resource_fixed_cost) {

                                $loc_quotegeneration['resource_count'] = $resource_words_count;
                                $loc_quotegeneration['resource_fixed_cost'] = $resource_fixed_cost;
                                $total = $total + $resource_fixed_cost;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style=" border: 1px solid black;">' .  $resource_words_count . ' (Resource)</td>
                                        <td style="text-align: right;border: 1px solid black;">NA</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($resource_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            } elseif ($resource_words_count != '' && $cost_per_resource != '') {
                                $loc_quotegeneration['resource_count'] = $resource_words_count;
                                $loc_quotegeneration['cost_per_resource'] = $cost_per_resource;
                                $row_total = ($resource_words_count * $cost_per_resource);
                                $total = $total + $row_total;
                                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . $resource_words_count . ' (Resource)</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($cost_per_resource, $currency, false, true, 'id', 'pdf') . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                                $coun++;
                            }

                            $loc_quotegeneration['total'] = $total;
                            $grand_total = $grand_total + $total;
                            if ($request_id == '') {
                                $locrequestassige = locrequestassigned::insert($loc_quotegeneration);
                            } else {
                                //, 'service_type' => $service[$j]
                                $locrequestassige = locrequestassigned::where(['request_id' => $request_id, 'loc_source_id' => $source_id, 'target_language' => $target_lan[$j]])->update($loc_quotegeneration);
                            }
                        }

                        for ($k = 0; $k < count($service); $k++) {
                            $servicevalue = array('quote_id' => $maintblid, 'loc_source_id' => $source_id, 'service_type' => $service[$k]);
                            $loc_service = locQuoteService::insert($servicevalue);
                        }
                    }
                }


                $pm_cost = $pmcost;
                if ($pm_cost > 0) {
                    $pm_per = ($pm_cost / 100);
                    $pm_total = $grand_total * $pm_per;
                    $pm_total_display = $pm_total;
                } else {
                    $pm_total = 0;
                    $pm_total_display = null;
                }

                if ($gst_type == 'yes') {
                    $gst_per = 18;
                    $g_per = ($gst_per / 100);
                    $gst_total = ($grand_total + $pm_total) * $g_per;
                    $gst_total_display = $gst_total;
                } else {
                    $gst_per = 0;
                    $gst_total = 0;
                    $gst_total_display = null;
                }

                $final_grand_total = $grand_total + $pm_total + $gst_total;

                $update_qa = array('total_amount' => $grand_total, 'gst' => $gst_per, 'pm_cost' => $pm_cost, 'grand_total' => $final_grand_total);
                $update_quote_master = loc_translation_master::where('translation_quote_id', $maintblid)->update($update_qa);

                /*$html .= '<tr style="font-family:sans-serif;">
                            <td colspan="7" style=" border: 1px solid black;">&nbsp;</td>
                            </tr>';*/
                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 0px 1px 1px 1px solid black;">Total</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($grand_total, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>';
                if ($pm_cost > 0) {
                    $html .= '<tr style="font-family:sans-serif;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black;">Project Management Cost ' . $pm_cost . '%</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($pm_total_display, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>';
                }
                if ($gst_type == 'yes') {
                    $html .= '<tr style="font-family:sans-serif;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">GST ' . $gst_per . '%</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($gst_total_display, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>';
                }
                /*$html .= '<tr>
                            <td colspan="7">&nbsp;</td>
                            </tr>';*/
                $html .= '<tr style="font-family:sans-serif;;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">Grand Total (' . $currency_code . ')</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($final_grand_total, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>
                            </table>            

                            </div><br>';
                $html .= '<div style="font-family:sans-serif;font-size:13px">Terms & Conditions:<br>' . $termsofuse . '</div>';
                $html .= '<br/><br/><br/><br/><div style="width: 100%;"><span style="font-family:sans-serif;" position: absolute;
  left: 0px;
  width: 50%;font-size:13px"><b>For ' . $client_c_name . '.</b></span><br/><br/><br/><br/><span style="position: absolute;
  left: 0px;
  width: 50%;font-family:sans-serif;font-size:13px;">(Authorized Signatory)</span><br></div>';
                $html .= '<footer style=" position:fixed;bottom:10px;!important;;font-size:13px"><b>This is a computer-generated document. No signature is required. </b></footer>';
                $html .= '</html>';


                //   $quote_generation = new loc_translation_master;
                //   echo "<pre>";
                //   print_r($quote_generation);die;



                $authenticated_users = User::whereHas(
                    'roles',
                    function ($q) {
                        $q->where('name', 'orgadmin');
                    }
                )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org)->get();
                $orgadmin = $authenticated_users->toArray();

                $orgadmin_emails = array_column((array)$orgadmin, 'email');
                $sales_email = getusernamebyid($userid, 'email');
                $email_m = [$sales_email];
                $ccemail = []; //[$email];
                $ccemail = array_merge($orgadmin_emails, $ccemail);
                $mailData = [
                    'title' => 'Quote Generated ' . '|' . $order_id . '|' . checkcurrency($final_grand_total, $currency, false, true) . '|' . ucwords($client_c_name) . '|' . ucwords(getusernamebyid($userid)),
                    'subject' => 'Quote Generated ' . '|' . $order_id . '|' . checkcurrency($final_grand_total, $currency, false, true, 'id', false, false, 'id', false, 'pdf') . '|' . ucwords($client_c_name) . '|' . ucwords(getusernamebyid($userid)),
                    'translation_quote_id' => getclientid($maintblid),
                    'quote_code' => $order_id,
                    'company_name' => ucwords($client_c_name),
                    'name' => ucwords($first_name . ' ' . $last_name),
                    "number" => $mob_number,
                    'email' => $email,
                    'date' => $created_at,
                    'created_by' => ucwords(getusernamebyid($userid)),
                    'quote_url' => env('APP_URL') . '/admin/editquote/' . $order_id,
                    'purches_url' => env('APP_URL') . '/admin/upload_po?q='.base64_encode(base64_encode($order_id)).'&s=' . base64_encode(base64_encode($userid))
                ];
                // echo "<pre/>"; print_r($mailData);die;
                $res = sendquotemail($email_m, $mailData, $ccemail);
                createlog('create_quote', 'Generated New Quote with ' . $order_id, $maintblid, 'loc_translation_qoute_generation_master', 'quote');
                $qfiledate = date("Ymd");
                $pdf->loadHtml($html);
                // output the HTML content
                $pdf->setPaper('A4', 'landscape');
                // Render the HTML as PDF  
                $pdf->render();
                $act_filename = $order_id . '.pdf';

                $temp_file = tempnam(sys_get_temp_dir(), $act_filename);

                $filePath = 'quotegeneration/' . $qfiledate . '/' . $act_filename;

                $res = Storage::disk('s3')->put($filePath, $pdf->output());
                //    return response()->json($res);



                $quote_file = $order_id . '_' . time() . '.pdf';
                $temp_file = tempnam(sys_get_temp_dir(), $quote_file);

                $filePath = 'quotegeneration/quotegeneration_history/' . $qfiledate . '/' . $quote_file;

                $res = Storage::disk('s3')->put($filePath, $pdf->output());


                $quote_history_files = array('quote_id' => $maintblid, 'file_name' => $quote_file, 'created_at' => $created_at);
                $quote_history = loc_quote_history::insert($quote_history_files);
                $pdf->stream($order_id . '.pdf', array("Attachment" => 1));

                die;
            }
        }
        return view('admin.quotegeneration.create', compact('users', 'loc_languages', 'orgs', 'org_addr'));
    }



    /**
     * Show the form for editing Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($quote_code)

    {
        if (!checkpermission('qoute_generation')) {
            return abort(401);
        }
        $orgs = Kptorganization::where('org_status', 1)->get()->pluck('org_name', 'org_id');

        $arr_sales_quote_generation_details =  loc_translation_master::where('quote_code', $quote_code)
            ->leftJoin('loc_request_assigned', 'loc_request_assigned.quote_id', 'loc_translation_qoute_generation_master.translation_quote_id')->first();
        // echo "<pre/>";   print_r($arr_sales_quote_generation_details);die;
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->join('loc_quote_sourcelang', 'loc_quote_sourcelang.sourcelang_id', 'loc_languages.lang_id')->get();
        //$loc_source_language=locQuoteSourcelang::where('quote_id',$arr_sales_quote_generation_details->translation_quote_id)->get();
        $getst_lang = new loc_translation_master();
        $loc_source_language = $getst_lang->quote_lang_select($arr_sales_quote_generation_details->translation_quote_id);
        // echo "<pre/>";
        // print_r($loc_languages);die;
        // $quote_history_files=array('quote_id'=>$maintblid,'file_name'=>$quote_file,'created_at'=>$created_at );          
        //             $quote_history=loc_quote_history::insert($quote_history_files);
        //             $pdf->stream($order_id . '.pdf', array("Attachment" => 1));


        $kptorganization     = Kptorganization::get()->pluck('org_name', 'org_id');
        // print_r($kptorganization);die;
        $loc_services = locService::get();
        $terms = terms_conditions::get();
        $language = loc_languages::orderBy('lang_name', 'ASC')->get();
        $currency_list = currencies::where('status', 'Active')->get();
        $user_role = Auth::user()->roles[0]->name;
        if ($user_role == 'administrator') {
            $org_id = $arr_sales_quote_generation_details->organization;
        } else {
            $org_id = get_user_org('org', 'org_id');
        }

        //$clientorganizations = Clientorganization::get();
        if ($user_role == 'orgadmin' || $user_role == 'projectmanager') {
            $clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $org_id])->get();
        } elseif ($user_role == 'sales') {
            $clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $org_id, 'created_by' => Auth::user()->id])->get();
        } elseif ($user_role == 'administrator') {
            $clientorganizations = Clientorganization::where(['org_status' => '1'])->get();
        }
        $org_addr = address::where(["user_id" => $org_id, "type" => "org"])->get();
        $client_org_addr = address::where(["user_id" => $arr_sales_quote_generation_details->client_org_id, "type" => "client_org"])->get();

        $req_quote_count = loc_request::where('quote_gen_id', $arr_sales_quote_generation_details->translation_quote_id)->count();
        $client_org_id = $arr_sales_quote_generation_details->client_org_id;

        $client_users = User::whereHas(
            'roles',
            function ($q) {
                $q->whereIn('name', ['clientuser']);
            }
        )->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.org_id', $client_org_id)->get();
        return view('admin.quotegeneration.edit', compact('arr_sales_quote_generation_details', 'currency_list', 'loc_languages', 'orgs', 'loc_services', 'language', 'loc_source_language', 'org_addr', 'getst_lang', 'req_quote_count', 'clientorganizations', 'client_org_addr', 'kptorganization', 'client_users', 'terms'))->with(['page_title' => 'Edit Quote - ' . $quote_code]);
    }

    /**
     * Update Role in storage.
     *
     * @param  \App\Http\Requests\UpdateRolesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    public function request_dynamic_fields()
    {
        $repeat_section = $_POST['repeat_section'];
        $source_languages = $target_language = $service_types = $service_type_id = $description = $word_count = $cost_per_word = $word_fixed_cost = $page_count = $cost_per_page = $fixed_page_count = $minute_count = $minute_per_cost = $minute_fixed_cost = $resource_count = $resource_per_cost = $resource_fixed_cost = '';
        if ($repeat_section == 'yes') {
            $source_languages =  $_POST['source_language_0'];
            $target_language = $_POST['destination_language_0'];
            $service_types     = $_POST['service_type_0'];
            $service_type_id = $_POST['service_type'];
            $description     = $_POST['description_0'];
            if ($service_types == 'word' || $service_types == '1') {
                $word_count =  $_POST['words_count_0'];
                $cost_per_word = $_POST['cost_per_word_0'];
                $word_fixed_cost = $_POST['word_fixed_cost_0'];
                $page_count = '';
                $cost_per_page = '';
                $fixed_page_count = '';
                $minute_count = '';
                $minute_per_cost = '';
                $minute_fixed_cost = '';
                $resource_count = '';
                $resource_per_cost = '';
                $resource_fixed_cost = '';
            } elseif ($service_types == 'page' || $service_types == '2') {
                $page_count = $_POST['page_count_0'];
                $cost_per_page = $_POST['cost_per_page_0'];
                $fixed_page_count = $_POST['page_fixed_cost_0'];
                $word_count =  '';
                $cost_per_word = '';
                $word_fixed_cost = '';
                $minute_count = '';
                $minute_per_cost = '';
                $minute_fixed_cost = '';
                $resource_count = '';
                $resource_per_cost = '';
                $resource_fixed_cost = '';
            } elseif ($service_types == 'minute' || $service_types == 'slab_minute'  || $service_types == '3') {
                $minute_count = $_POST['minute_words_count_0'];
                $minute_per_cost = $_POST['minute_cost_per_word_0'];
                $minute_fixed_cost = $_POST['minute_fixed_cost_0'];
                $word_count =  '';
                $cost_per_word = '';
                $word_fixed_cost = '';
                $page_count = '';
                $cost_per_page = '';
                $fixed_page_count = '';
                $resource_count = '';
                $resource_per_cost = '';
                $resource_fixed_cost = '';
            } elseif ($service_types == 'resource') {
                $resource_count = $_POST['resource_words_count_0'];
                $resource_per_cost = $_POST['resource_cost_per_word_0'];
                $resource_fixed_cost = $_POST['resource_fixed_cost_0'];
                $word_count =  '';
                $cost_per_word = '';
                $word_fixed_cost = '';
                $page_count = '';
                $cost_per_page = '';
                $fixed_page_count = '';
                $minute_count = '';
                $minute_per_cost = '';
                $minute_fixed_cost = '';
            }
        }
        $loc_services = locService::orderBy('id', 'ASC')->get();
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
        $addrow = $_REQUEST['addrow'];
        $increment_div = $addrow + 1;
        $html = '<div class="col-md-6" id="page_dyanmic_fields_' . $addrow . '"><div class="card">
      <div class="card-header">
        Section ' . $increment_div . '
        <div class="card-tools">
          <button type="button" class="remove_field btn btn-tool bg-danger" id="' . $addrow . '" onClick="javascript:validate_dynamic_field_page(' . $addrow . ');">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
        <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="source_language" class="required">Source Language</label>
                        <select name="source_language[]" id="source_language source_language_' . $addrow . '" class="form-control select2" required>
                            <option value="">Select Source Language</option>';
        foreach ($loc_languages as $key => $lang) {

            $html .= '<option  value="' . $lang->lang_id . '"  ' . (($lang->lang_id == $source_languages) ? 'selected' : '') . '>' . $lang->lang_name . '</option>';
        }
        $html .= '</select>';
        $html .= ' <input type="hidden" value="" name="db_source_lang_id[]" /> <input type="hidden" value="' . $addrow . '" name="db_source_lang_index[]" /> <div class="invalid-feedback" for="">Must Select your Source Language</div>
                   </div>


                    <div class="form-group col-md-4">
                        <label for="destination_language" class="required">Target Language</label>
                        <select name="destination_language_' . $addrow . '[]" id="destination_language_' . $addrow . '" class="form-control select2" required >
                            <option value="">Select Target Language</option>';
        foreach ($loc_languages as $key => $lang) {

            $html .= '<option value="' . $lang->lang_id . '" ' . (($lang->lang_id == $target_language) ? 'selected' : '') . ' >'  . $lang->lang_name . '</option>';
        }
        $html .= '</select>';
        $html .=    '<div class="invalid-feedback" for="">Must Select your Target Language</div>
                   </div>
                    <div class="form-group col-md-4">
                        <label class="required">Type of Service</label>
                        <select name="service_type_' . $addrow . '[]" id="service_type_' . $addrow . '" onchange="getservice_type(this.value,' . $addrow . ')" data-index="' . $addrow . '" class="form-control select2 service_type" required>';
        $html .= '<option value="">Select Service Type</option>';
        // print_r($loc_services);die;
        foreach ($loc_services as $service) {
            $html .= '<option value="' . $service->id . '"  data-type="' . $service->type . '" ' . (($service->id == $service_type_id) ? "selected" : "") . '>' . $service->service_type . '</option>';
        }
        $html .= '</select>
                       <div class="invalid-feedback" for="">Must select one of the Requests</div>
                    </div>
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6  ' . (($service_type_id != '') ? "" : "hide_price_div") . ' " id="description_div_' . $addrow . '">
                                
                                   <div class="form-group">
                                        <label for="description">Description: </label>
                                        
                                        <textarea name="description_' . $addrow . '" id="description_' . $addrow . '" class="form-control" rows="5" >' . (($description != '') ? $description : (($source_languages != '' && $target_language != '' && $service_types == 'word') ? getlangbyid($source_languages) . ' - ' . getlangbyid($target_language) : '')) . '</textarea>
                                   </div>
                                </div>
                                <div class="col-md-6">
                        <div class="form-group ' . (($service_types == 'word') ? "" : "hide_price_div") . ' " id="word_price_div_' . $addrow . '">
                            <label>Word Count & Cost Per word: *</label>
                            <div class="form-row">

                                <div class="col-md-12"><input type="number" min="1" max="1000000000" id="words_count_' . $addrow . '" name="words_count_' . $addrow . '" class="form-control" placeholder="word count" onkeyup="getpercost(1,' . $addrow . ',this.value)" value="' . $word_count . '"><br></div>
                                <div class="col-md-12"><input type="text"  id="cost_per_word_' . $addrow . '" name="cost_per_word_' . $addrow . '" class="form-control float-number" placeholder="cost for word" value="' . $cost_per_word . '"><br>  
                                </div>
                                   <div class="col-md-12"><input type="text"  name="word_fixed_cost_' . $addrow . '" class="form-control float-number" id="word_fixed_cost_' . $addrow . '" placeholder="fixed cost"  value="' . $word_fixed_cost . '">
                                </div>
                                
                            </div>
                           
                        </div>
                        <div class="form-group  ' . (($service_types == 'page') ? "" : "hide_price_div") . ' " id="page_price_div_' . $addrow . '">
                            <label>Page Count & Cost Per Page: </label>
                            <div class="form-row">
                                <div class="col-md-12"><input type="number" min="1" max="1000000000" id="page_count_' . $addrow . '" name="page_count_' . $addrow . '" class="form-control" placeholder="Page count" onkeyup="getpercost(2,' . $addrow . ',this.value)"   value="' . $page_count . '"><br></div>
                                <div class="col-md-12"><input type="text"  id="cost_per_page_' . $addrow . '" name="cost_per_page_' . $addrow . '" class="form-control float-number" placeholder="cost for page" value="' . $cost_per_page . '"><br>  
                                </div>
                                   <div class="col-md-12"> <input type="text"  name="page_fixed_cost_' . $addrow . '" id="page_fixed_cost_' . $addrow . '" class="form-control float-number" placeholder="fixed cost" value="' . $fixed_page_count . '">
                                </div>
                                
                            </div>
                            
                        </div>
                        <div class="form-group  ' . (($service_types == 'resource') ? "" : "hide_price_div") . ' " id="resource_price_div_' . $addrow . '">
                            <label>Resource Count & Cost Per Resource: </label>
                            <div class="form-row">
                                <div class="col-md-12"><input type="number" min="1" max="1000000000" id="resource_count_' . $addrow . '" name="resource_count_' . $addrow . '" class="form-control" placeholder="Resource count" onkeyup="getpercost(2,' . $addrow . ',this.value)"   value="' . $resource_count . '"><br></div>
                                <div class="col-md-12"><input type="text"  id="cost_per_resource_' . $addrow . '" name="cost_per_resource_' . $addrow . '" class="form-control float-number" placeholder="cost for resource" value="' . $resource_per_cost . '"><br>  
                                </div>
                                   <div class="col-md-12"> <input type="text"  name="resource_fixed_cost_' . $addrow . '" id="page_fixed_cost_' . $addrow . '" class="form-control float-number" placeholder="resource cost" value="' . $resource_fixed_cost . '">
                                </div>
                            </div>
                        </div>
                        <div class="form-group  ' . (($service_types == 'minute' || $service_types == 'slab_minute') ? "" : "hide_price_div") . '" id="minute_price_div_' . $addrow . '">
                                    <label>Minutes Cost & Cost Per Minute: *</label>
                                    <div class="form-row">
                                           <div class="col-md-12"> <input type="number" min="1" id="minute_words_count_' . $addrow . '" max="1000000000" name="minute_words_count_' . $addrow . '" class="form-control" placeholder="Minute count" 
                                        
                                           onkeyup="getpercost(3,' . $addrow . ',this.value)"  
                                              value="' . $minute_count . '"><br></div>
                                           <div class="col-md-12"> <input type="text"  id="minute_cost_per_word_' . $addrow . '" name="minute_cost_per_word_' . $addrow . '" class="form-control float-number" placeholder="cost for Minute" value="' . $minute_per_cost . '"><br></div>
                                           <div class="col-md-12"> <input type="text"  name="minute_fixed_cost_' . $addrow . '" id="minute_fixed_cost_' . $addrow . '" class="form-control float-number" placeholder="fixed cost" value="' . $minute_fixed_cost . '"></div> 
                                           
                                </div>
                    </div>
                    </div>
                    </div>
                </div>
            </div></div></div>';
        //$html .= '<div id="add_rows_page_quote_' . $increment_div . '"></div>';

        echo $html;
        exit;
    }
    public function editquote($quote_code)
    {

        $user_role = Auth::user()->roles[0]->name;

        if ($user_role == 'translator' || $user_role == 'vendor' || $user_role == 'proofreader' || $user_role == 'qualityanalyst') {
            return abort(401);
        }


        $quote_details =  loc_translation_master::where('quote_code', $quote_code)->first();

        $translation_id = $quote_details->translation_quote_id;
        $quote_history = loc_quote_history::where('quote_id', $translation_id)->get();

        $orgs = Kptorganization::get()->pluck('org_name', 'org_id');
        $terms = terms_conditions::get();

        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->get();

        return view('admin.quotegeneration.editquote', compact('quote_details', 'orgs', 'loc_languages', 'quote_history'));
    }

    public function update(Request  $request,  $quote_code)
    {
        $userid = Auth::user()->id;

        $employe_id = User::where('id', $userid)->first();
        $emp_id = $employe_id->employee_id;
        if ($_POST['Generate_Quote'] == "Generate Quote") {
            $pdf = new Dompdf();
            $validated = $request->validate([]);

            $first_name        = $_POST['first_name'];
            $client_comp_name        = $_POST['comp_name'];
            $last_name         = $_POST['last_name'];
            $mob_number        = $_POST['mob_number'];
            $email             = $_POST['email'];
            $org               = $_POST['org'];
            $client_org               = $_POST['client_org'];
            $to_address        = $to_address_db  = gettabledata('address', 'address', ['id' => $_POST['to_address']]);
            $to_address_id        = $_POST['to_address'];
            $subject             = nl2br($_POST['subject']);
            $termsofuse            = nl2br($_POST['terms_of_use']);
            $current_date        = $_POST['current_date'];
            $gst_type            = $_POST['gst_type'];
            $quote_type            = $_POST['quote_type'];
            $currency            = $_POST['currency'];
            $quote_heading        = $_POST['quote_heading'];
            $pmcost       =  $_POST["pm_cost"];
            $source_lan  =  $_POST["source_language"];
            $db_source_lang_id = $_POST['db_source_lang_id'];
            $db_source_lang_index = $_POST['db_source_lang_index'];
            $aa_dd = $_POST["org_adrs"];
            $product_delivery = $_POST['weeks'];
            $org_addr = address::where(["id" => $aa_dd, "type" => "org"])->first();
            if ($current_date == "") {
                $current_date =  date("Y-m-d");
            }
            $kpt_org = Kptorganization::where('org_id', $org)->first();
            $orgs = $kpt_org->org_name;
            $quote_generation = array(
                "first_name" => $first_name,
                "last_name" => $last_name,
                "client_comp_name" => $client_comp_name,
                "mob_number" => $mob_number,
                "email" => $email,
                "translation_quote_date" => $current_date,
                "translation_quote_subject" => $subject,
                "translation_quote_gst" => $gst_type,
                "quote_type" => $quote_type,
                "organization" => $org,
                "client_org_id" => $client_org,
                "translation_quote_termuse" => $termsofuse,
                "translation_quote_currency" => $currency,
                "translation_quote_address" => $to_address_db,
                "to_address_id" => $to_address_id,
                "payment_type" => 'partial',
                "translation_status" => 'open',
                "translation_user_id" => $userid,
                "address_id" => $aa_dd,
                "weeks" => $product_delivery
            );


            $update_quote_generation = loc_translation_master::where('quote_code', $quote_code)->update($quote_generation);
            //echo "<pre/>";   print_r($update_quote_generation);die;
            $translation_id = loc_translation_master::where('quote_code', $quote_code)->first();
            $maintblid = $translation_id->translation_quote_id;
            if ($maintblid > 0) {
                // $image = base_path() . "/public/storage/org_images/" . $org . '.png';
                // if (file_exists($image)) {
                //     $type = pathinfo($image, PATHINFO_EXTENSION);
                //     $data = file_get_contents($image);
                //     $logo_path = 'data:image/' . $type . ';base64,' . base64_encode($data);
                // } else {
                //     $logo_path = '';
                // }
                $image = env('AWS_CDN_URL') . '/org_images/' . $org . '.png';
                $type = pathinfo($image, PATHINFO_EXTENSION);
                $data = file_get_contents($image);
                $logo_path = 'data:image/' . $type . ';base64,' . base64_encode($data);

                $html = '<div><table width="100%" cellpadding="5" border="0">
             <tbody>
             <tr>
             <td style="width:50%;text-align:left;"><img src="' . $logo_path . '" width="174" height="60" border="0"></td>          
             <td style="font-family:sans-serif; width=50%;text-align:right">' . $org_addr->address . '</td>
                </tr>
                </tbody>
                </table><hr style="width: 100%;
                border: 6px dotted #5184AF;
                border-style: none none dotted;
                color: grey;-webkit-border-radius:50%;">';



                if ($quote_heading == "yes") {
                    $html .= '<div style="font-family:sans-serif; text-align:center;font-size:13px;color:#528bbb;">PROFORMA INVOICE </div>';
                } elseif ($quote_heading == "po") {
                    $html .= '<div style="font-family:sans-serif; text-align:center;font-size:13px;color:#528bbb;">PURCHASE ORDER</div>';
                }

                $html .= '<div class="col-md-6" style="text-align:left; font-family:sans-serif;font-size:13px">Quote ID: ' . $quote_code . '</div><div class="col-md-6" style=" margin-top:-22px;text-align:right; font-family:sans-serif;">MSME Registration Number: <b>UDYAM-TS-09-0000576</b></div>';


                $html .= ' <div style="text-align:right; font-family:sans-serif;font-size:13px">Reference ID: ' .  getclientid($maintblid) . '</div>';
                $html .= '<div style="text-align:right; font-family:sans-serif;font-size:13px">DATE: ' . date("j M, Y", strtotime($current_date)) . '</div>';

                $html .= '   <div style="font-family:sans-serif;font-size:13px"> To,<br>' . ucfirst($first_name) . ' ' . $last_name . ',<br>' . $client_comp_name . ',<br>' . ucfirst($to_address_db) . '<br>Email: ' . $email . ',<br> Mobile Number: ' . $mob_number . '</div>';
                $html .= '<div style="font-family:sans-serif;font-size:13px">Subject: ' . ucfirst($subject) . ',<br></div>
                <div>
                <table style="width:100%; margin-left: auto;
                margin-right: auto; border: 1px solid black;border-collapse: collapse;" cellpadding="5" >';

                $currency_value        = "";
                $currency_code = gettabledata('currencies', 'currency_code', ['id' => $currency]);
                $currency_label     = "Amount (" . $currency_code . ")";
                loc_translation_master::where('translation_quote_id', $maintblid)->update(['quote_code' => $quote_code]);
                $grand_total = 0;
                $html .= '<tr style="background-color:#babec1;font-family:sans-serif;font-size:13px">
                        <td style="border: 1px solid black;">SN.</td>
                        <td style="border: 1px solid black;">Work Type</td>
                        <td style="border: 1px solid black;">Language Pair</td>
                        <td style="border: 1px solid black;">Description</td>
                        <td style="border: 1px solid black;">Item</td>
                        <td style="border: 1px solid black;">Unit/rate</td>
                        <td style="border: 1px solid black;">' . $currency_label . '</td>
                        </tr>';
                $coun = 1;

                // locQuoteSourcelang::where('quote_id', $maintblid)->delete();
                // locQuoteService::where('quote_id', $maintblid)->delete();
                // locrequestassigned::where('quote_id', $maintblid)->delete();

                $get_source_lang = loc_translation_master::quote_lang_select($maintblid);
                $get_source_lang = array_column((array)$get_source_lang->toArray(), 'id');
                $my_source = $db_source_lang_id;
                // print_r($my_source);die;
                $db_source = $get_source_lang;
                $insert_source = array_values(array_diff($my_source, $db_source));
                $delete_source = array_values(array_diff($db_source, $my_source));
                for ($ds = 0; $ds < count($delete_source); $ds++) {
                    $source_get_first = locQuoteSourcelang::where(['quote_id' => $maintblid, 'id' => $delete_source[$ds]])->first();
                    locrequestassigned::where(['quote_id' => $maintblid, 'loc_source_id' => $source_get_first->id])->delete();
                    locQuoteSourcelang::where(['id' => $source_get_first->id])->delete();
                }

                // for ($hk=0; $hk < count($db_source_lang_id); $hk++) { 
                //     if($db_source_lang_id[$hk] != ''){
                //         if(!in_array($db_source_lang_id[$hk],$get_source_lang)){
                //             $source_get_first=locQuoteSourcelang::where(['quote_id'=> $maintblid,'id'=>$db_source_lang_id[$hk]])->count(); 
                //             locrequestassigned::where(['quote_id'=> $maintblid,'loc_source_id'=>$source_get_first->id])->delete();
                //             locQuoteSourcelang::where(['id'=> $source_get_first->id])->delete(); 
                //         }
                //     }
                // }




                //locQuoteService::where(['quote_id'=> $maintblid])->delete();

                for ($i = 0; $i < count($source_lan); $i++) {
                    $index = $db_source_lang_index[$i];
                    $description = ((isset($_POST['description_' . $index]) && $_POST['description_' . $index] != '') ? $_POST['description_' . $index] : Null);
                    $words_count = ((isset($_POST['words_count_' . $index]) && $_POST['words_count_' . $index] != '') ? $_POST['words_count_' . $index] : Null);
                    $cost_per_word            = ((isset($_POST['cost_per_word_' . $index]) && $_POST['cost_per_word_' . $index] != '') ? $_POST['cost_per_word_' . $index] : Null);
                    $word_fixed_cost          = ((isset($_POST['word_fixed_cost_' . $index]) && $_POST['word_fixed_cost_' . $index] != '') ? $_POST['word_fixed_cost_' . $index] : Null);
                    $page_count               = ((isset($_POST['page_count_' . $index]) && $_POST['page_count_' . $index] != '') ? $_POST['page_count_' . $index] : Null);
                    $cost_per_page            = ((isset($_POST['cost_per_page_' . $index]) && $_POST['cost_per_page_' . $index] != '') ? $_POST['cost_per_page_' . $index] : Null);
                    $page_fixed_cost          = ((isset($_POST['page_fixed_cost_' . $index]) && $_POST['page_fixed_cost_' . $index] != '') ? $_POST['page_fixed_cost_' . $index] : Null);
                    $minute_words_count       = ((isset($_POST['minute_words_count_' . $index]) && $_POST['minute_words_count_' . $index] != '') ? $_POST['minute_words_count_' . $index] : Null);
                    $minute_cost_per_word     = ((isset($_POST['minute_cost_per_word_' . $index]) && $_POST['minute_cost_per_word_' . $index] != '') ? $_POST['minute_cost_per_word_' . $index] : Null);
                    $minute_fixed_cost        = ((isset($_POST['minute_fixed_cost_' . $index]) && $_POST['minute_fixed_cost_' . $index] != '') ? $_POST['minute_fixed_cost_' . $index] : Null);
                    $resource_words_count       = ((isset($_POST['resource_words_count_' . $i]) && $_POST['resource_words_count_' . $index] != '') ? $_POST['resource_words_count_' . $index] : Null);
                    $cost_per_resource     = ((isset($_POST['resource_cost_per_word_' . $i]) && $_POST['resource_cost_per_word_' . $index] != '') ? $_POST['resource_cost_per_word_' . $index] : Null);
                    $resource_fixed_cost        = ((isset($_POST['resource_fixed_cost_' . $i]) && $_POST['resource_fixed_cost_' . $index] != '') ? $_POST['resource_fixed_cost_' . $index] : Null);
                    $target_lan               = ((isset($_POST["destination_language_" . $index]) && $_POST["destination_language_" . $index] != '') ? $_POST["destination_language_" . $index] : Null);
                    $source_language_text = loc_languages::where('lang_id', $source_lan[$i])->first();
                    $source_language_text = $source_language_text->lang_name;
                    $service = ((isset($_POST['service_type_' . $index]) && $_POST['service_type_' . $index] != '') ? $_POST['service_type_' . $index] : Null);

                    $types_service = implode(',', $service);

                    $source_get_first = locQuoteSourcelang::where(['quote_id' => $maintblid, 'id' => $db_source_lang_id[$i]])->first();
                    if ($source_get_first) {
                        $update_id = array('quote_id' => $maintblid, 'description' => $description);
                        $locquotesource = locQuoteSourcelang::where(['quote_id' => $maintblid, 'id' => $source_get_first->id])->update($update_id);
                        $source_id = $source_get_first->id;
                    } else {
                        $language1 = new locQuoteSourcelang();
                        $language1->quote_id = $maintblid;
                        $language1->sourcelang_id = $source_lan[$i];
                        $language1->description = $description;
                        $language1->save();
                        $source_id = $language1->id;
                    }
                    /*$checkservice_type=locQuoteService::where(['quote_id' => $maintblid, 'loc_source_id' => $source_id,'service_type'=>$service[0]])->count();
                            if($checkservice_type > 0){
                                locQuoteService::where(['quote_id' => $maintblid, 'loc_source_id' => $source_id,'service_type'=>$service[0]])->delete();
                            }*/
                    // $language =new locQuoteSourcelang();
                    // $language->quote_id=$maintblid;
                    // $language->sourcelang_id=$source_lan[$i];
                    // $language->description=$description;
                    // $language->save();
                    // $source_id=$language->id;

                    $getdesc = locQuoteSourcelang::where('id', $source_id)->first();
                    $getdescription = $getdesc->description;
                    $loc_service1 = locService::whereIn('id', $service)->get('service_type')->toArray();
                    $service_types = implode(', ', array_column((array)$loc_service1, 'service_type'));

                    $requestassigned = array('quote_id' => $maintblid, 'loc_source_id' => $source_id);
                    $get_target_lang = locrequestassigned::where($requestassigned)->get();
                    $get_target_lang = array_column((array)$get_target_lang->toArray(), 'target_language');
                    $my_target = $target_lan;
                    $db_target = $get_target_lang;
                    $insert_target = array_values(array_diff($my_target, $db_target));
                    $delete_target = array_values(array_diff($db_target, $my_target));
                    for ($ds = 0; $ds < count($delete_target); $ds++) {
                        $checkservice_type = locrequestassigned::where(['quote_id' => $maintblid, 'loc_source_id' => $source_id, 'target_language' => $delete_target[$ds]])->count();
                        if ($checkservice_type > 0) {
                            locrequestassigned::where(['quote_id' => $maintblid, 'loc_source_id' => $source_id, 'target_language' => $delete_target[$ds]])->delete();
                        }
                    }
                    // $checkservice_type=locrequestassigned::where(['quote_id' => $maintblid, 'loc_source_id' => $source_id,'target_language'=>$target_lan[0],'service_type'=>$service[0]])->count();
                    // if($checkservice_type > 0){
                    //     locrequestassigned::where(['quote_id' => $maintblid, 'loc_source_id' => $source_id,'target_language'=>$target_lan[0],'service_type'=>$service[0]])->delete();
                    // }

                    for ($j = 0; $j < count($target_lan); $j++) {
                        $total = 0;
                        $target_language_text = loc_languages::where('lang_id', $target_lan[$j])->first();
                        $target_language_text = $target_language_text->lang_name;
                        $loc_quotegeneration = array('quote_id' => $maintblid, 'loc_source_id' => $source_id, 'target_language' => $target_lan[$j], 'service_type' => $service[0]);
                        if ($word_fixed_cost) {
                            $loc_quotegeneration['word_count'] = $words_count;
                            $loc_quotegeneration['word_fixed_cost'] = $word_fixed_cost;
                            $total = $total + $word_fixed_cost;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style=" border: 1px solid black;">' . $words_count . ' (Words)</td>
                                        <td style="text-align: right;border: 1px solid black;">NA</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($word_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                            $coun++;
                        } elseif ($words_count != '' && $cost_per_word != '') {
                            $loc_quotegeneration['word_count'] = $words_count;
                            $loc_quotegeneration['per_word_cost'] = $cost_per_word;
                            $row_total = ($words_count * $cost_per_word);
                            $total = $total + $row_total;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . $words_count . ' (Words)</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($cost_per_word, $currency, false, true, 'id', 'pdf') . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                            $coun++;
                        }
                        if ($page_fixed_cost) {
                            $loc_quotegeneration['page_count'] = $page_count;
                            $loc_quotegeneration['page_fixed_cost'] = $page_fixed_cost;
                            $total = $total + $page_fixed_cost;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style=" border: 1px solid black;">' . $page_count . ' (Pages)</td>
                                        <td style="text-align: right;border: 1px solid black;">NA</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($page_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                            $coun++;
                        } elseif ($page_count != '' && $cost_per_page != '') {
                            $loc_quotegeneration['page_count'] = $page_count;
                            $loc_quotegeneration['per_page_cost'] = $cost_per_page;
                            $row_total = ($page_count * $cost_per_page);
                            $total = $total + $row_total;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . $page_count . ' (Pages)</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($cost_per_page, $currency, false, true, 'id', 'pdf') . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                            $coun++;
                        }
                        if ($minute_fixed_cost) {
                            $loc_quotegeneration['minute_count'] = $minute_words_count;
                            $loc_quotegeneration['minute_fixed_cost'] = $minute_fixed_cost;
                            $total = $total + $minute_fixed_cost;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style=" border: 1px solid black;">' . $minute_words_count . ' (Minutes)</td>
                                        <td style="text-align: right;border: 1px solid black;">NA</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($minute_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                            $coun++;
                        } elseif ($minute_words_count != '' && $minute_cost_per_word != '') {

                            $loc_quotegeneration['minute_count'] = $minute_words_count;
                            $loc_quotegeneration['per_minute_cost'] = $minute_cost_per_word;
                            $row_total = ($minute_words_count * $minute_cost_per_word);
                            $total = $total + $row_total;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                        <td style=" border: 1px solid black;">' . $coun . '</td>
                                        <td style=" border: 1px solid black;">' . $service_types . '</td>
                                        <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                        <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . $minute_words_count . ' (Minutes)</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($minute_cost_per_word, $currency, false, true, 'id', 'pdf') . '</td>
                                        <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                        </tr>';
                            $coun++;
                        }



                        if ($resource_fixed_cost) {

                            $loc_quotegeneration['resource_count'] = $resource_words_count;
                            $loc_quotegeneration['resource_fixed_cost'] = $resource_fixed_cost;
                            $total = $total + $resource_fixed_cost;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                    <td style=" border: 1px solid black;">' . $coun . '</td>
                                    <td style=" border: 1px solid black;">' . $service_types . '</td>
                                    <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                    <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                    <td style=" border: 1px solid black;">' .  $resource_words_count . ' (Minutes)</td>
                                    <td style="text-align: right;border: 1px solid black;">NA</td>
                                    <td style="text-align: right;border: 1px solid black;">' . checkcurrency($resource_fixed_cost, $currency, false, true, 'id', 'pdf') . '</td>
                                    </tr>';
                            $coun++;
                        } elseif ($resource_words_count != '' && $cost_per_resource != '') {
                            $loc_quotegeneration['resource_count'] = $resource_words_count;
                            $loc_quotegeneration['cost_per_resource'] = $cost_per_resource;
                            $row_total = ($resource_words_count * $cost_per_resource);
                            $total = $total + $row_total;
                            $html .= '<tr style="font-family:sans-serif;font-size:13px">
                                    <td style=" border: 1px solid black;">' . $coun . '</td>
                                    <td style=" border: 1px solid black;">' . $service_types . '</td>
                                    <td style=" border: 1px solid black;">' . $source_language_text . ' - ' . $target_language_text . '</td>
                                    <td style=" border: 1px solid black;">' . $getdescription . '</td>
                                    <td style="text-align: right;border: 1px solid black;">' . $resource_words_count . ' (Pages)</td>
                                    <td style="text-align: right;border: 1px solid black;">' . checkcurrency($cost_per_resource, $currency, false, true, 'id', 'pdf') . '</td>
                                    <td style="text-align: right;border: 1px solid black;">' . checkcurrency($row_total, $currency, false, true, 'id', 'pdf') . '</td>
                                    </tr>';
                            $coun++;
                        }
                        $loc_quotegeneration['total'] = $total;
                        $grand_total = $grand_total + $total;
                        $check_target_lang = locrequestassigned::where(['quote_id' => $maintblid, 'loc_source_id' => $source_id, 'target_language' => $target_lan[$j]]);
                        if ($check_target_lang->count() == 0) {
                            $locrequestassige = locrequestassigned::insert($loc_quotegeneration);
                        } else {
                            $t_id = $check_target_lang->first();
                            $locrequestassige = locrequestassigned::where('id', $t_id->id)->update(['word_count' => null, 'per_word_cost' => null, 'word_fixed_cost' => null, 'page_count' => null, 'per_page_cost' => null, 'page_fixed_cost' => null, 'minute_count' => null, 'per_minute_cost' => null, 'minute_fixed_cost' => null]);
                            $locrequestassige = locrequestassigned::where('id', $t_id->id)->update($loc_quotegeneration);
                        }
                    }

                    // for ($k = 0; $k < count($service); $k++) { 
                    //     $servicevalue= array('quote_id' => $maintblid, 'loc_source_id' => $source_id, 'service_type' => $service[$k]);
                    //     $checkservice_type=locQuoteService::where($servicevalue)->count();
                    //     if($checkservice_type == 0){
                    //         $loc_service=locQuoteService::insert($servicevalue);
                    //     }
                    // }
                }

                $pm_cost = $pmcost;
                if ($pm_cost > 0) {
                    $pm_per = ($pm_cost / 100);
                    $pm_total = $grand_total * $pm_per;
                    $pm_total_display = $pm_total;
                } else {
                    $pm_total = 0;
                    $pm_total_display = null;
                }

                if ($gst_type == 'yes') {
                    $gst_per = 18;
                    $g_per = ($gst_per / 100);
                    $gst_total = ($grand_total + $pm_total) * $g_per;
                    $gst_total_display = $gst_total;
                } else {
                    $gst_per = 0;
                    $gst_total = 0;
                    $gst_total_display = null;
                }

                $final_grand_total = $grand_total + $pm_total + $gst_total;

                $update_qa = array('total_amount' => $grand_total, 'gst' => $gst_per, 'pm_cost' => $pm_cost, 'grand_total' => $final_grand_total);
                $update_quote_master = loc_translation_master::where('translation_quote_id', $maintblid)->update($update_qa);

                /*$html .= '<tr style="font-family:sans-serif;">
                            <td colspan="7" style=" border: 1px solid black;">&nbsp;</td>
                            </tr>';*/
                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">Total</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($grand_total, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>';
                if ($pm_cost > 0) {
                    $html .= '<tr style="font-family:sans-serif;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black;">Project Management Cost ' . $pm_cost . '%</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($pm_total_display, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>';
                }
                if ($gst_type == 'yes') {
                    $html .= '<tr style="font-family:sans-serif;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">GST ' . $gst_per . '%</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($gst_total_display, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>';
                }
                /*$html .= '<tr>
                            <td colspan="7">&nbsp;</td>
                            </tr>';*/
                $html .= '<tr style="font-family:sans-serif;font-size:13px">
                            <td colspan="5" style=" border: 1px solid black;">&nbsp;</td>
                            <td style=" border: 1px solid black;">Grand Total (' . $currency_code . ')</td>
                            <td style="text-align: right;border: 1px solid black;">' . checkcurrency($final_grand_total, $currency, false, true, 'id', 'pdf') . '</td>
                            </tr>
                            </table>            

                            </div><br>';
                $html .= '<div style="font-family:sans-serif;font-size:13px">Terms & Conditions:<br>' . $termsofuse . '</div>';
                $html .= '<br/><br/><br/><br/><div style="width: 100%;"><span style="font-family:sans-serif;" position: absolute;
  left: 10px;
  width: 50%;font-size:13px"><b>For   ' . $client_comp_name . '.</b></span><span style="position: absolute;
  right: -350px;
  width: 50%; font-family:sans-serif;font-size:13px;"></span><br/><br/><br/><br/><span style="position: absolute;
  left: 0px;
  width: 50%;font-family:sans-serif;font-size:13px;">(Authorized Signatory)</span></div>';
                $html .= '<footer style=" position:fixed;bottom:10px;!important;font-size:13px"><b>This is a computer-generated document. No signature is required.</b></footer>';
                $html .= '</html>';

                createlog('create_quote', 'Quote updated with ' . $quote_code, $maintblid, 'loc_translation_qoute_generation_master', 'quote');

                $pdf->loadHtml($html);
                // output the HTML content
                $pdf->setPaper('A4', 'landscape');
                // Render the HTML as PDF  
                $pdf->render();
                //$qfiledate=date("Ymd");
                $quote_date = loc_translation_master::where('translation_quote_id', $maintblid)->first();
                $qfiledate = date("Ymd", strtotime($quote_date->created_at));
                $act_filename = $quote_code . '.pdf';
                $temp_file = tempnam(sys_get_temp_dir(), $act_filename);

                $filePath = 'quotegeneration/' . $qfiledate . '/' . $act_filename;

                $res = Storage::disk('s3')->put($filePath, $pdf->output());

                $qfiledate = date("Ymd");
                $created_at = date('Y-m-d H:i:s');
                $quote_file = $quote_code . '_' . time() . '.pdf';
                $temp_file = tempnam(sys_get_temp_dir(), $quote_file);

                $filePath = 'quotegeneration/quotegeneration_history/' . $qfiledate . '/' . $quote_file;

                $res = Storage::disk('s3')->put($filePath, $pdf->output());


                $quote_history_files = array('quote_id' => $maintblid, 'file_name' => $quote_file, 'created_at' => $created_at);
                $quote_history = loc_quote_history::insert($quote_history_files);


                $pdf->stream($quote_code . '.pdf', array("Attachment" => 1));
                die;
            }
        }
        return view('admin.quotegeneration.create', compact('users', 'loc_languages', 'orgs'));
    }


    public function request_change_quote_status(Request $request)
    {


        $date = date("Y-m-d H:i:s");
        $userid = Auth::user()->id;
        $user_role = Auth::user()->roles[0]->name;
        $quote_generation = new loc_translation_master();
        $quote_vendor = new quote_generation_vendor();
        $getorgid = user_orgizations::where('user_id', $userid)->first();
        // print_r($getorgid->org_id);die;
        $authenticated_users = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'orgadmin');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $getorgid->org_id)->get();
        $orgadmin = $authenticated_users->toArray();
        $orgadmin_emails = array_column((array)$orgadmin, 'email');
        // print_r($orgadmin);die;
        $quote_code = $request['quote_code'];
        $translation_status = $request['translation_status'];
        $assign_data = $request['assign_data'];
        //echo $quote_code;die;
        $quote_data = $quote_generation::where('quote_code', $quote_code)->select(['translation_quote_id', 'pm_id', 'mob_number', 'first_name', 'last_name', 'client_comp_name', 'email'])->first();
        //print_r($quote_data->translation_quote_id);die;  
        $quote_id = $quote_data->translation_quote_id;


        if ($quote_id != '' && $translation_status != '') {
            $quote_generation::where(['translation_quote_id' => $quote_id])->update(['translation_status' => $translation_status]);
            if ($translation_status == 'Assign') {
                $assign_id = $assign_data;
                $ass_data = array(
                    'pm_id' => $assign_id,
                    'pm_assigned_date' => date('Y-m-d H:i:s')
                );
                if ($quote_data->pm_id == '') {
                    $emp_id = $quote_code . '_LO' . getusernamebyid($assign_id, 'employee_id');
                } else {
                    $any = explode('_', $quote_code);
                    $emp_id = $any[0] . '_' . $any[1] . '_LO' . getusernamebyid($assign_id, 'employee_id');
                }
                $ass_data['quote_code'] = $emp_id;
            }

            $quote_generation = loc_translation_master::where('translation_quote_id', $quote_id)->update($ass_data);
            $req_quote = loc_request::where('quote_gen_id', $quote_id)->first();
            if ($req_quote) {
                $quote_generation = loc_request::where('quote_gen_id', $quote_id)->update(['reference_id' => $ass_data['quote_code']]);
            }
            $pm_id = $assign_id;
            // $orgadmin_mail=getusernamebyid($orgadmin,'email');
            $pm_email = getusernamebyid($pm_id, 'email');
            $sales_email = getusernamebyid($userid, 'email');
            $email = [$pm_email];
            $ccemail = [$sales_email];
            $ccemail = array_merge($orgadmin_emails, $ccemail);
            $mailData = [
                'title' => 'Quote Assigned to Project Manager',
                'subject' => 'Quote Assigned to Project Manager',
                'quote_code' => $ass_data['quote_code'],
                'company_name' => ucwords($quote_data->client_comp_name),
                'name' => ucwords($quote_data->first_name . ' ' . $quote_data->last_name),
                "number" => $quote_data->mob_number,
                'email' => $quote_data->email,
                'date' => $date,
                'created_by' => ucwords(getusernamebyid($userid)),
                'quote_url' => env('APP_URL') . '/admin/editquote/' . $ass_data['quote_code']
            ];
            $res = sendquotemail($email, $mailData, $ccemail);
            createlog('change_quote_status', 'Quote ID:' . $ass_data['quote_code'] . ' Assigned to Project Manager:' . ucwords(getusernamebyid($pm_id)), $quote_id, 'loc_translation_qoute_generation_master', 'quote');
            //   if($res){
            //       echo true;
            //   }else{
            //       echo false;
            //   }
            Session()->flash('message', 'Request Status successfully updated');
        } else {
            Session()->flash('error_message', 'Request Status not updated');
        }
    }
    public function get_request_quote_assign_data(Request $request)
    {

        $quote_generation = new loc_translation_master();
        $quote_vendor = new quote_generation_vendor();
        $userid = Auth::user()->id;
        $quote_code = $request['quote_code'];
        $quote_id = $quote_generation::where('quote_code', $quote_code)->select('translation_quote_id')->first();
        $quote_id = $quote_id->translation_quote_id;
        $translation_status = $request['translation_status'];
        $org_id_req = $quote_generation::where('quote_code', $quote_code)->select('organization')->first();
        $org_id_req = $org_id_req->organization;
        // print_r($org_id_req);die;
        $role_type = '';
        $users = array();
        if ($translation_status == 'assign_vendor') {
            $role_type = 'vendor';
            $users = user::select('id', 'name')->whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'vendor');
                }
            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id_req)->get();
        }

        $options = '<option value="">Select User</option>';
        foreach ($users as $user) {
            $options .= '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
        }


        $source_lang = $quote_vendor->vendor_lang_select($quote_id);
        // echo $quote_code;die;
        $target_lang = $quote_vendor->vendor_lang_select($quote_id, 'target');
        $i = 0;
        $out_data = '';
        foreach ($target_lang as $sl) {
            $out_data .= '<tr><td>' . $source_lang[$i]->lang_name . ' - ' . $sl->lang_name . '</td><td><select  class="form-control select2 translators_list_select" name="vendor_list_select" required>' . $options . '</select></td></tr>';
            $i++;
        }
        echo $out_data;
    }

    public function createquote($id)
    {
        if (!checkpermission('qoute_generation')) {
            return abort(401);
        }

        $user_role = Auth::user()->roles[0]->name;
        if ($user_role == 'administrator') {
            $created_by = '';
        } else {
            $created_by = Auth::user()->id;
        }
        //  echo "<pre/>";
        //print_r($_POST);die;
        // $loc_req=loc_request::where('reference_id', $id)->count();
        // if($loc_req == 0){
        $getst_lang = new loc_translation_master();
        // $user_role = Auth::user()->roles[0]->name;
        //  $client_org= loc_request::where('client_org_id', $id)->first();
        $request_data = loc_request::where('reference_id', $id)->first();

        $client_org = clientorganization::where(["org_id" => $request_data->client_org_id])->first();
        //  echo "<pre/>";
        // print_r($client_org);die;
        // $client_org_id =$client_org->org_id;
        $client_name = $client_org->org_name;
        $client_users = User::whereHas(
            'roles',
            function ($q) {
                $q->whereIn('name', ['clientuser', 'requester', 'approval', 'reviewer']);
            }
        )->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.org_id', $request_data->client_org_id)->first();

        $users = User::getAuthenticatedUsers($created_by);
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->get();
        $loc_services = locService::orderBy('id', 'ASC')->get();


        $org = Kptorganization::get()->pluck('org_name', 'org_id');
        //print_r($org);die;
        $currency_list = currencies::where('status', 'Active')->get();

        $org_id = get_user_org('org', 'org_id');
        $client_org_addr = address::where(["user_id" => $client_users->client_org_id, "type" => "client_org"])->get();
        $org_addr = address::where(["user_id" => $org_id, "type" => "org"])->get();

        return view('admin.quotegeneration.requestquote', compact('users', 'loc_languages', 'org', 'loc_services', 'currency_list', 'org_addr', 'request_data', 'getst_lang', 'client_org', 'client_users', 'client_org_addr'))->with(['page_title' => 'Generation New Quote']);
    }
    public function get_quote_rate(Request  $request)
    {
        $source_language =  $_POST['source_language'];
        $target_language = $_POST['target_language'];
        $service_type     = $_POST['service_type'];
        $currency     = $_POST['currency'];
        $count     = $_POST['count'];
        $org_id = get_user_org('org', 'org_id');
        $loc_ratecard = new loc_ratecard();
        $rate_card = $loc_ratecard->get_target_price($org_id, $service_type, $currency, $source_language, $target_language);
        if ($rate_card) {
            $ser_type = gettabledata('loc_service', 'type', ['id' => $service_type]);
            $rate_price = 0;
            if ($ser_type == 'slab_minute') {
                if ($count > 60) {
                    $mycost = $rate_card->minute_cost_60;
                    $rate_price = check_rate_cost($mycost, $count, 60);
                } elseif ($count <= 60 && $count > 45) {
                    $rate_price = $rate_card->minute_cost_60;
                } elseif ($count <= 45 && $count > 30) {
                    $rate_price = $rate_card->minute_cost_45;
                } elseif ($count <= 30 && $count > 15) {
                    $rate_price = $rate_card->minute_cost_30;
                } elseif ($count <= 15 && $count > 1) {
                    $rate_price = $rate_card->minute_cost_15;
                }
            } elseif ($ser_type == 'page') {
                $rate_price = $rate_card->page_cost;
            } elseif ($ser_type == 'minute') {
                $rate_price = $rate_card->minute_cost;
            } else {
                $rate_price = $rate_card->word_cost;
            }
            $total = $rate_price;
        } else {
            $total = 0;
        }
        echo $total;
    }
    public function change_terms(Request $request)
    {
        $terms_id = $_POST['id'];
        $terms = terms_conditions::where('id', $terms_id)->first();
        echo $terms->description;
    }


    public function get_client_details(Request $request)
    {
        $client_org = $_POST['client_id'];
        $client_users = User::whereHas(
            'roles',
            function ($q) {
                $q->whereIn('name', ['clientuser', 'requester', 'approval', 'reviewer']);
            }
        )->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.org_id', $client_org)->get();

        $html = '<option value="">Select Client Users</option>';
        foreach ($client_users as $row) {
            $html .= '<option value="' . $row->id . '" data-name="' . $row->name . '" data-email="' . $row->email . '" data-mobile="' . $row->mobile . '" >' . $row->name . '</option>';
        }
        $org_adress = address::where(["user_id" => $client_org, "type" => "client_org"])->get();
        $address = '<option value="">Select Client Address</option>';
        foreach ($org_adress as $row) {
            $address .= '<option value="' . $row->id . '">' . $row->address . '</option>';
        }
        return json_encode(array('users' => $html, 'address' => $address));
    }

    public function quote_cancel(Request $request, $translation_quote_id)
    {
        $date = date("Y-m-d H:i:s");
        $userid = Auth::user()->id;

        $user_role = Auth::user()->roles[0]->name;
        $getorgid = user_orgizations::where('user_id', $userid)->first();
        $org_id = $getorgid->org_id;
        $org_id = get_user_org('org', 'org_id');

        $authenticated_users = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'orgadmin');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->first();
        $orgadmin = $authenticated_users->toArray();
        $orgadmin_emails = array_column((array)$orgadmin, 'email');


        $quote_generation = new loc_translation_master();
        //  $quote_vendor = new quote_generation_vendor();



        $req_status = 'cancel';
        $quote_data = loc_translation_master::where('translation_quote_id', $translation_quote_id)->first();

        $quote_code = $quote_data->quote_code;
        $translation_quote_id = $quote_data->translation_quote_id;
        // getclientid($translation_quote_id);die;
        // echo "<pre/>"; print_r($translation_quote_id);die;

        $quote_comp = $quote_data->client_comp_name;
        $client_org_id = $quote_data->client_org_id;

        // $res=  getclientid($client_org_id);
        //  echo "<pre/>"; print_r($res);die;
        $translation_user_id = $quote_data->translation_user_id;

        $quote_generation = loc_translation_master::where('translation_quote_id', $translation_quote_id)->update(['client_amnt_status' => $req_status, 'vendor_amnt_status' => $req_status]);








        loc_request::where('quote_gen_id', $translation_quote_id)->update(['request_status' => $req_status]);
        Session()->flash('message', 'Project Cancel successfully ');



        // $quote_code = $request['quote_code'];
        // $translation_status = $request['translation_status'];
        //$assign_data = $request['assign_data'];


        $org_id = $userid;

        // $orgadmin_mail=getusernamebyid($orgadmin,'email');
        $org_email = getusernamebyid($org_id, 'email');

        $sales_email = getusernamebyid($translation_user_id, 'email');
        //  print_r($sales_email);die;
        $email = [$sales_email];
        $ccemail = [$org_email];
        $ccemail = array_merge($email, $ccemail);
        $mailData = [
            'title' => 'Quote Cancelled ',
            'subject' => 'Quote cancelled ' . $quote_code,
            'quote_code' => $quote_code,
            'client_org_id' => getclientid($translation_quote_id),
            'company_name' => ucwords($quote_data->client_comp_name),
            'name' => ucwords($quote_data->first_name . ' ' . $quote_data->last_name),
            "number" => $quote_data->mob_number,
            'email' => $sales_email,
            'date' => $date,
            'created_by' => ucwords(getusernamebyid($userid)),
            'quote_url' => env('APP_URL') . '/admin/quote_cancel/' . $quote_code
        ];

        $res = sendquotemail($email, $mailData, $ccemail);

        Session()->flash('message', 'Request Status successfully updated');










        //  echo "<pre/>"; print_r($quote_code);die;
        return redirect()->back();
    }


    public function upload_po_order()
    {
        // print_r($_GET);die;
        extract($_GET);
        if (isset($q) && $q != '' && isset($s) && $s != '') {
            $quote_code = base64_decode(base64_decode($q));
            $sales_id = base64_decode(base64_decode($s));

            $upload_op = loc_translation_master::where('quote_code', $quote_code)->first();
            if($upload_op){
                $db_quote_code = $upload_op->quote_code;
                $db_user_id = $upload_op->translation_user_id;
                if ($sales_id == $db_user_id ) {
                    return view('admin.upload_po.index', compact('quote_code'));
                }else{
                    echo "Something went wrong.";
                }  
            } else {
                echo "Quote not found.";
            }
        }
    }


    public function submit_po(Request $request)
    {
        $quote_code=$_REQUEST['id'];
        $order_type=$_REQUEST['order_type'];
        if($order_type == 'yes'){
        $this->validate($request, ['po_file' => ['required']]);
        $this->validate($request, ['po_no' => ['required']]);
        }else{
            $this->validate($request, ['po_comment'      => ['required']]); 
        }
     

        $upload_op = loc_translation_master::where('quote_code', $quote_code)->first();
     
 //echo "<pre/>";    print_r($upload_op);die;
     
        // $quote_id="4222";
    
   
      //  print_r($_POST);die;
      if($upload_op){
        if($order_type == 'yes'){
        $quote_id= $upload_op->translation_quote_id;
        $details = new loc_po();

        $details->quote_id = $request['id'];

        $details->po_order_no = $request['po_no'];

        $details->save();
        $maintblid = $details->id;

        if ($maintblid > 0) {
            $invoicedate = date("Ymd");
            $po_no = $details['po_no'];
           
            if (isset($_FILES['po_file']['name']) && $_FILES['po_file']['name'] != "") {
                //Source File upload
                $file_name5 = $_FILES['po_file']['name'];     //file name
                $file_size45 = $_FILES['po_file']['size'];     //file size
                $file_temp1 = $_FILES['po_file']['tmp_name']; //file temp 
                $ext5 = strtolower(pathinfo($file_name5, PATHINFO_EXTENSION));
                $act_filename1 = $po_no . '_' . time() . '.' . $ext5;

                $filePath2 = 'po_order/client/' . $invoicedate . '/' . $act_filename1;
                $file2 = $request->file('po_file');
                $res = Storage::disk('s3')->put($filePath2, file_get_contents($file2));
                if ($res) {

                    $file2 = loc_po::where('id', $maintblid)->update(['po_file_path' => $act_filename1]);
                    Session()->flash('message', 'Request Status successfully updated');
                }
            }
        }

            $po_no= $_REQUEST['po_no'];
            $title_lab='PO uploaded';
        }else{
            $comments= $_REQUEST['po_comment'];
            $title_lab='PO not uploaded';
        }
        $userid = Auth::user()->id;
        $quote_id=$upload_op->organization;
        $org=$upload_op->organization;
        $client_c_name=$upload_op->client_comp_name;
        $mob_number=$upload_op->mob_number;
        $first_name=$upload_op->first_name;
        $last_name=$upload_op->last_name;
        $email=$upload_op->email;
        $created_at=$upload_op->created_at;
        $order_id= $upload_op->translation_quote_id;
        $final_grand_total= $upload_op->grand_total;
        $currency= $upload_op->currency_cost;
        // $pm_id= $upload_op->pm_id;
        $authenticated_users = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'orgadmin');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org)->get();
        
        $orgadmin = $authenticated_users->toArray();
        

        $orgadmin_emails = array_column((array)$orgadmin, 'email');
        
        $sales_email = getusernamebyid($userid, 'email');   
        
        $email_m = [$sales_email];
        $ccemail = []; //[$email];
        if(isset($upload_op->pm_id) && $upload_op->pm_id != ''){
            $pm_email=getusernamebyid($upload_op->pm_id,'email'); 
            $ccemail = array_merge($pm_email, $ccemail);
        }
        $ccemail = array_merge($orgadmin_emails, $ccemail);
        $mailData = [
            'title' => $title_lab . '|' . $quote_code . '|' . checkcurrency($final_grand_total, $currency, false, true) . '|' . ucwords($client_c_name) . '|' . ucwords(getusernamebyid($userid)),
            'subject' => $title_lab . '|' . $quote_code . '|' . checkcurrency($final_grand_total, $currency, false, true, 'id', false, false, 'id', false, 'pdf') . '|' . ucwords($client_c_name) . '|' . ucwords(getusernamebyid($userid)),
            'translation_quote_id' => getclientid($quote_id),
            'quote_code' => $order_id,
            'company_name' => ucwords($client_c_name),
            'name' => ucwords($first_name . ' ' . $last_name),
            "number" => $mob_number,
            'email' => $email,
            'date' => $created_at,
            'created_by' => ucwords(getusernamebyid($userid)),
            'purches_url' => env('APP_URL') . '/admin/upload_po?q='.base64_encode(base64_encode($order_id)).'&s=' . base64_encode(base64_encode($userid))
        ];
        if($order_type == 'yes'){
            $mailData['po_no']=$po_no;
        }else{
            $mailData['comments']=$comments;
        }
        //echo "<pre/>"; print_r($mailData);die;
        $res = sendpurches($email_m, $mailData, $ccemail);
        return redirect()->back()->with('message', 'File Uploaded Successfully');
    } else{

        echo "quote id not matching";
       
    }
}
}
