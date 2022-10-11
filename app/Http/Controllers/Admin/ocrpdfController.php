<?php

namespace App\Http\Controllers\Admin;
use Auth;
//use App\loc_request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\ocrpdf_model;
use Illuminate\Support\Facades\Gate;
//use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\loc_languages;

class OcrpdfController extends Controller
{

        /* start index  */
        public function index(){
        
        $ocrpdf_model = new ocrpdf_model;
        $userid=Auth::user()->id;
        $marketing_campaign = ocrpdf_model::get();
        return view('admin.ocrpdf.index', compact('marketing_campaign','ocrpdf_model'));
        // return view('admin.ocrpdf.show',compact('arr_dowload_listings'));
      }
    
      /* Import ocrpdf Memory start here */
      public function create(){		
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status','ACTIVE')->get();
        return view('admin.ocrpdf.create', compact('loc_languages'));   	
        }	
       public function import_kpt_tool_processing(){  
                  
                    if($_FILES['translation_file']['name'] !="" && $_FILES['translation_file']['tmp_name'] !=""){
                
                        $file_name = $_FILES['translation_file']['name'];     //file name
                        $act_filename=$file_name;
                        $file_size = $_FILES['translation_file']['size'];     //file size
                        $file_temp = $_FILES['translation_file']['tmp_name']; //file temp
                        
                                            
                            
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); 
                        // Using strtolower to overcome						
                        $uploads_dir = base_path()."/public/storage/ocrpdf/";			
                        $filename  = md5(file_get_contents($_FILES['translation_file']['tmp_name']));
                        $file_name = $filename.".".$file_ext;			
                        $upload_status = move_uploaded_file($file_temp, "$uploads_dir/$act_filename");
                        $supported_files = array('pdf','PDF');
    
                                        
                        if (in_array($file_ext, $supported_files)) {					
                                $exce_path = $_SERVER['DOCUMENT_ROOT'].$uploads_dir.$file_name;
                                
                                 $kpt_reference_code = date("Ydmhmi");
                                 $get_uploaded_status = "Done";
                                 $get_uploaded_date = date("Y-m-d H:i:s");
                                 
                                 /* insert into database  */
                                 $org_filename = $_FILES['translation_file']['name'];
                                 $arr_uploadvalues = array($kpt_reference_code,$org_filename,$file_name,$get_uploaded_status,$get_uploaded_date);				 					 
                                      $ocrpdf=new ocrpdf_model;
                                      $ocrpdf->kpt_reference_code=$kpt_reference_code;
                                      $ocrpdf->org_upload_file_name=$org_filename;
                                      $ocrpdf->upload_file_name=$exce_path;
                                      $ocrpdf->uploaded_status=$get_uploaded_status;
                                      $ocrpdf->uploaded_date=$get_uploaded_date;
                                      $result=$ocrpdf->save();                
                                 //$result = $this->ocrpdf_model;						 
                                // $result = $this->ocrpdf_model->call_sp('xp_ocrpdf_upload_insert',$arr_uploadvalues,'output',TRUE);						 
                               
                                return redirect()->route('admin.ocrpdf.index');					
                                exit;				
                            
                            }else{
                                print 'No Support to uploaded file.';
                                exit;	
                            }	
                            
                        }else{
                            print 'Please upload a .pdf File';
                            exit;				
                        }	
                
       }   
    //    /* Import Translation Memory end here */
          
          
    //    /* View Translation Memory start here */
      public function viewdownloads($arg1='',$arg2=''){
             $user_role = $this->current_user->role_name;
                    
                    
             if($user_role == 'Admin'){	 
                             
                 $arr_dowload_listings =ocrpdf_model;
                 
                 
                 
                 Template::set('logedinuser', $this->current_user);		 				
                 Template::set('arr_dowload_listings', $arr_dowload_listings);			 
                 Template::set_view('view_kpttool');		
                 Template::render();
                 
            }else{
                    print 'No Access to this feature';
             }	  
      }
    //   /* View Translation Memory end here */
      
      
    //   /* Zip download file */
        public function zip_download_file($filename,$org_filename){
            
            if($filename !="" && $org_filename !=""){		
                
                $arr_org_filename = pathinfo($org_filename);
                $arr_main_filename = pathinfo($filename);			
                
                            
                $outputfile = $arr_main_filename['filename']."_output.txt";
                $filename = "/var/www/html/assets/uploadfiles/ocrpdf/".$outputfile;			
                $filename1 = $arr_org_filename["filename"].".txt";		
                            
                
                
              if (file_exists($filename)) {
                
                    header("Content-Type: text/plain");
                     header('Content-Disposition: attachment; filename="'.basename($filename1).'"');
                     header('Content-Length: ' . filesize($filename));
                     flush();
                     readfile($filename);
                     exit;
                }
            }		
        }
        /* Zip download file */
        
        
        
        /* Highlighted Text function start here */
        public function uploadhightlighttext(){		
            $user_role = $this->current_user->role_name;
            
             if($user_role == 'Admin'){
                 Template::set('logedinuser', $this->current_user);		 				
                 Template::set_view('upload_highlighttext');		
                 Template::render();
                 
            }else{
                    print 'No Access to this feature';
             }	  
            
        }
    //   /* Highlighted Text function end here */
      
      
       public function import_kpt_tool_highlightedtext_processing(){
                $user_role 	= $this->current_user->role_name;
                $user_id 	= $this->current_user->user_id;			
                            
                if($user_role == 'Admin'){			
                                
                    if($_FILES['translation_file']['name'] !="" && $_FILES['translation_file']['tmp_name'] !=""){
                
                        $file_name = $_FILES['translation_file']['name'];     //file name
                        $file_size = $_FILES['translation_file']['size'];     //file size
                        $file_temp = $_FILES['translation_file']['tmp_name']; //file temp
                        
                        $language_name = $_POST["language_name"];							
                            
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); 
                        // Using strtolower to overcome						
                        $uploads_dir = 'assets/uploadfiles/xliffsegmentation';			
                        $filename  = md5(file_get_contents($_FILES['translation_file']['tmp_name']));
                        $file_name = $filename.".".$file_ext;			
                        $upload_status = move_uploaded_file($file_temp, "$uploads_dir/$file_name");
                        $supported_files = array('zip');			
                    
                        if (in_array($file_ext, $supported_files)) {					
                                $exce_path = $_SERVER['DOCUMENT_ROOT']."/assets/uploadfiles/xliffsegmentation/".$file_name;
                                
                                 $kpt_reference_code = date("Ydmhmi");
                                 $get_uploaded_status = "inprogress";
                                 $get_uploaded_date = date("Y-m-d H:i:s");
                                 
                                 /* insert into database  */
                                 $org_filename = $_FILES['translation_file']['name'];
                                 $arr_uploadvalues = array($kpt_reference_code,$org_filename,$file_name,$get_uploaded_status,$get_uploaded_date,$language_name);				 					 
                                                         
                                 $result = $this->kpttool_model->call_sp('xp_highlightedtexttool_upload_insert',$arr_uploadvalues,'output',TRUE);					 
                                
                                echo $result;						
                                exit;				
                            
                            }else{
                                print 'No Support to uploaded file.';
                                exit;	
                            }	
                            
                        }else{
                            print 'Request must be POST.';
                            exit;				
                        }	
                }
       }   
       /* Import Translation Memory end here */
      
      
      
      
      
      
      /* View Highlighted Text Download */
      public function viewdownloads1(){
          
            $user_role = $this->current_user->role_name;				
             if($user_role == 'Admin'){	 
                             
                 $arr_dowload_listings = $this->kpttool_model->viewhightlighttextdownloads_listing();		 
                 
                 
                 Template::set('logedinuser', $this->current_user);		 				
                 Template::set('arr_dowload_listings', $arr_dowload_listings);			 
                 Template::set_view('view_highlighttext');		
                 Template::render();
                 
            }else{
                    print 'No Access to this feature';
             }	  
          
      }
      /* View Highlighted Text Download */
      
      
      
       /* Zip download file */
        public function zip_download_highlighttext_file($filename,$org_filename){
            
            if($filename !="" && $org_filename !=""){		
                
                $arr_org_filename = pathinfo($org_filename);
    
                
                            
                $outputfile = $filename."_output.zip";
                $filename = "/var/www/html/languageconnector/assets/uploadfiles/highlightedtexttool/".$outputfile;
                
                
                $filename1 = $arr_org_filename["filename"]."_output.zip";
                
                
                
              if (file_exists($filename)) {
                  
                
                     header('Content-Type: application/zip');
                     header('Content-Disposition: attachment; filename="'.basename($filename1).'"');
                     header('Content-Length: ' . filesize($filename1));
                     flush();
                     readfile($filename);
                }
            }		
        }
        /* Zip download file */
    }
        
        
        
    



