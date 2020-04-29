<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offline_report;//add model directory
use Response;
use Image;
use File;
use Session;
use Exception;
use Carbon\Carbon;//this a dependency for time
use PDF;//the pdf dependency::////////

class Offline_reportController extends Controller
{
    //
        public $username = "bankowner";               // Use your username....
        public $password = "pass1234";             // and your password....
        public $database = "192.168.1.109:1521/orcl";   // and the connect string to connect to your database....


    public function __construct()
    { 
        
        //Log out Back
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0"); // Proxies.
        
        //this code will collect the document info in folder and insert it into the database.
        $add_data = new Offline_report;
        $add_data->document_name = "Amos Document Sample";
        $add_data->ref_number = "RISKMODEL-00001-09102019" ;
        $add_data->description = "PDF";
        $add_data->branch_name = "00001";
        $add_data->department = "";
        $add_data->document_path = "";
        $add_data->date_created = "09/10/2019";
       // $add_data->save();
        //die("report");
        $root = public_path()."/offlinePDF";
        //$root = "http://localhost/reportsapp/public/offlinePDF/"; 
        //echo "<pre>";print_r(File::allFiles($root));die;
        //filename,pathname, relativepathname, relativepath:;
         foreach (File::allFiles($root) as $file) {
         $data[] =  $file->getrelativePath();
             } 
       // echo "<pre>"; print_r($data);die; 


        $file_name = "test.pdf";//file name
        $path = public_path()."/documents"."/".Carbon::now()->format("d-M-Y");

        if(is_dir($path) && file_exists($path)) { 
    
            //fopen($path."/".$file_name, "w");//write file
            return true;
        
        //:::return view('offline_report.offline_report');

        }  else  {
   
            File::makeDirectory($path);
            //create file in folder//
            //fopen($path."/".$file_name, "w");//write file


        }
       
                 
    } 
    

    public function index()
    {
        return redirect('/login');
    }

    
    public function login(Request $request)
    {
      $data = $request->all();
      if(!empty($data['username'])&& !empty($data['password'])){
      $username = strtoupper($data['username']);
      //$email = $data['email'];
      $password = $data['password'];
       
       try{
        $conn = oci_connect($this->username, $this->password, $this->database);
        // if (!$conn) {
        // $m = oci_error();
        // trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
        // }else{
        // //echo 'connected';
        // }//die;
        

        $c = oci_parse($conn, "begin :get :=my_encrypt('$password','$username'); end;");
        oci_bind_by_name($c, ':get', $hashPasswd, 200);
        oci_execute($c);
        //echo $hashPasswd;die;
        // $b_code = $res;EE9652076F005A739BBC
        Session::put('user',$username);
        Session::put('pass',$hashPasswd);
        $session_user = Session::get('user');
        $session_pass = Session::get('pass');
        $current_user =oci_parse($conn,"Select COUNT(*) from menu_users WHERE USER_NAME = '$session_user' AND PASSWD = '$session_pass'");
        oci_execute($current_user);

        //$user_count = array();
        while($rows = oci_fetch_array($current_user,OCI_RETURN_NULLS+OCI_ASSOC)) {
        //echo $rows;die;
        $user_count = $rows['COUNT(*)'];

        }

        } catch(Exception $e) {

        return 'Message: '.$e->getMessage();

        }

        if($user_count>0){
        
        return redirect('/reports')->with('flash_message_success','Login Success, Welcome '.$session_user);
        //echo"<pre>";print_r($user_count);die;
       } else {

        return redirect('/login')->with('flash_message_error','Invalid Login Details!');

       }

      }
      //echo"<pre>";print_r($data);die;
       //abort(404);exit;
 
      return view('Offline_report.login');
      
    }
   

    public function logout()
    { 
     Session::flush();
     return redirect('/login')->with('flash_message_success','Successfully Signed Out');

    }


    public function doc_info() 
    {
        // $query = "select * from m70f1.users";
        try{
        $conn = oci_connect($this->username, $this->password, $this->database);
        // if (!$conn) {
        // $m = oci_error();
        // trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
        // }else{
        // //echo 'connected';
        // }//die;
        
        $vw_reports =oci_parse($conn,"Select * from tb_offline_report_data");
        oci_execute($vw_reports);

        $doc_info = array();
        while($rows = oci_fetch_array($vw_reports,OCI_RETURN_NULLS+OCI_ASSOC)) {
        //echo $rows;die;
        $doc_info[] = $rows;
        }
        
        } catch(Exception $e) {

        return 'Message: '.$e->getMessage();

        }

        return $doc_info;
    }


    public function branch_code() 
    {
        // $query = "select * from m70f1.users";
        try{
        $conn = oci_connect($this->username, $this->password, $this->database);
        // if (!$conn) {
        // $m = oci_error();
        // trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
        // }else{
        // //echo 'connected';
        // }//die;
        

        $vw_reports =oci_parse($conn,"Select distinct BRANCHNAME from tb_offline_report_data");
        oci_execute($vw_reports);

        $branch_code = array();
        while($rows = oci_fetch_array($vw_reports,OCI_RETURN_NULLS+OCI_ASSOC)) {
        //echo $rows;die;
        $branch_code[] = $rows;
        }
      
        } catch(Exception $e) {

        return 'Message: '.$e->getMessage();

        }


        return $branch_code;
    }


    public function doc_name() 
    {
        // $query = "select * from m70f1.users";
        try{
        $conn = oci_connect($this->username, $this->password, $this->database);
        // if (!$conn) {
        // $m = oci_error();
        // trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
        // }else{
        // //echo 'connected';
        // }//die;
       

        $vw_reports =oci_parse($conn,"Select distinct FILE_PATH from tb_offline_report_data");
        oci_execute($vw_reports);

        $doc_name = array();
        while($rows = oci_fetch_array($vw_reports,OCI_RETURN_NULLS+OCI_ASSOC)) {
        //echo $rows;die;
        $document = explode('\\',$rows["FILE_PATH"]);

        $doc_name[] = array('FILENAME'  => $document[5]);

        }

        } catch(Exception $e) {

        return 'Message: '.$e->getMessage();

        }        

        return $doc_name;
    }


    public function doc_description() 
    {
        // $query = "select * from m70f1.users";
        try{
        $conn = oci_connect($this->username, $this->password, $this->database);
        // if (!$conn) {
        // $m = oci_error();
        // trigger_error('Could not connect to database: '. $m['message'], E_USER_ERROR);
        // }else{
        // //echo 'connected';
        // }//die;
        

        $vw_reports =oci_parse($conn,"Select distinct REP_TYPE from tb_offline_report_data");
        oci_execute($vw_reports);

        $doc_description = array();
        while($rows = oci_fetch_array($vw_reports,OCI_RETURN_NULLS+OCI_ASSOC)) {
        //echo $rows;die;
        //$document = explode('\\',$rows["FILE_PATH"]);

        $doc_description[] = array("DESCRIPTION" => $rows["REP_TYPE"]);

        }

        } catch(Exception $e) {

        return 'Message: '.$e->getMessage();

        }

        return $doc_description;
    }


    public function view_offline_report()
    {
        try{
        $doc_info  = $this->doc_info();
        $branch_code = $this->branch_code();
        $doc_name = $this->doc_name();
        $doc_description = $this->doc_description();
        $user = Session::get('user');
        if(empty($user)){
        return redirect('/login')->with('flash_message_error','Please You Must Login First');
        }
        //Fetch items in database and display:://////
        //$thumbnailImage = Image::make($originalImage);
        $root = "..\offlinePDF";
        //$root = "http://localhost/reportsapp/public/offlinePDF/"; 
        //echo "<pre>";print_r(File::allFiles($root));die;
        //filename,pathname, relativepathname, relativepath:;
        foreach (File::allFiles($root) as $file) {
          $fileNames[] = $file->getFilename();
          $filePaths[] =   $file->getPathname();
          $path[] = $file->getrelativepath();
             }  

             
        $report = Offline_report::get();
        $branch = Offline_report::select('branch_name')->distinct()->get();//select distinct branch_name:::////
        $branch_dropdown = "<option value='' selected>--Select Branch--</option>";
        $document_name_dropdown = "<option value='' selected>--Select--</option>";
        $document_description_dropdown = "<option value='' selected>--Select Doc Description--</option>";

        foreach($branch_code as $a => $b){
        $branch_dropdown .= "<option value='".$b["BRANCHNAME"]."'>".$b["BRANCHNAME"]."</option>";
               } 

        foreach($doc_description as $a => $b){
        $document_description_dropdown .= "<option value='".$b["DESCRIPTION"]."'>".$b["DESCRIPTION"]."</option>";
               } 


        foreach($doc_name as $pro => $val){
        // $category_name = Category::where(['id'=>$val->category_id])->first();
         //echo $category_name->name;die;
        // $cat_name = $category_name['name'];
           $filename = explode('.',$val['FILENAME']);//remove extension
           $document_name_dropdown .= "<option value='".$filename[0]."'>".$filename[0]."</option>";
         //$products[$pro]->category_name = $cat_name ; //adding another field to products table array for displaying
         //echo $cat_name;die;
               }
        //echo $products[$pro]->price;die;
        //    echo $products;die;
        //return view('admin.products.view_products')->with(compact('products'));
        //die; 
         }catch(Exception $e){

          return "Message: ".$e->getMessage();

         }
        
       // echo "<pre>"; print_r($doc_name);die;  
        return view('offline_report.offline_report')->with(compact('branch_dropdown','document_name_dropdown','filePaths','doc_info','document_description_dropdown','user'));

    }
 
    public function fileDownload($hashPath)
    { 
        try{
        //file in a particular location::///
        $file_location = public_path()."/documents/amos.pdf";//this location is when pdf is stored under public/download folder::///
        //$file_loc = "..\\".$path.'\\'.$subpath.'\\'.$filename; //::: file with its location:::::://
        //$doc_info = $this->doc_info();
        //echo $file_loc;die;
        $root = "..\offlinePDF";
        foreach (File::allFiles($root) as $file) {

          $hashActualPath = md5($file->getPathname());

         if($hashActualPath == $hashPath) { //compare hash from url to hash folder

            $file_path = $file->getPathname();
           // $paths[] =   $file->getPathname();
            $new_file_name = $file->getFilename();

                  }
             }  

        //   $actual_file_path = str_replace('\\','/',$file_path);

          $headers = array(

            'Content-Type: application/pdf',

             );
        
        }catch(Exception $e){

                return "Message: ".$e->getMessage();
      
           }
 
        return Response::download($file_path, $new_file_name, $headers);
    }



    public function printPDF()
    {

        //Fetch items in database and display:://////
        //$thumbnailImage = Image::make($originalImage);
        $report = Offline_report::get();
        $branch = Offline_report::select('branch_name')->distinct()->get();//select distinct branch_name:::////
        $branch_dropdown = "<option value='' selected>--Select--</option>";
        $document_name_dropdown = "<option value='' selected>--Select---</option>";
   
        foreach($branch as $a => $b){
        $branch_dropdown .= "<option value='".$b->branch_name."'>".$b->branch_name."</option>";
               } 
 
        foreach($report as $pro => $val){
          
        // $category_name = Category::where(['id'=>$val->category_id])->first();
         //echo $category_name->name;die;
        // $cat_name = $category_name['name'];
        
        $document_name_dropdown .= "<option value='".$val->document_name."'>".$val->document_name."</option>";
         //$products[$pro]->category_name = $cat_name ; //adding another field to products table array for displaying
         //echo $cat_name;die;
        }

       // This  $data array will be passed to our PDF blade       
    //    $data = [          
    //        'title' => 'First PDF for Medium',          
    //        'heading' => 'Hello from 99Points.info',          
    //        'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'        
    //         ];
       
         

        $pdf = PDF::loadView('offline_report.offline_report',compact('report','branch_dropdown','document_name_dropdown'));  
        $print= $pdf->download('medium.pdf');

        return view('offline_report.offline_report')->with(compact('report','branch_dropdown','document_name_dropdown','print'));
        //return $pdf->download('medium.pdf');

    }


    
    public function fetch_all_folder_files()
    {
        $entries = scandir(public_path());

        $root = public_path();

        foreach (File::allFiles($root) as $file) {
           $data[] =   $file->getFilename();
        }
           echo "<pre>"; print_r($data);die;

    }
}
