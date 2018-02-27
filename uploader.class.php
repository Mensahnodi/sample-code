<?php
@session_start();
class File_Uploader{
private $fileName;
private $result;
private $permDir;
private $fileSize;
private $fileError;
private $sql;
public function __construct(){
$this->fileName="";
$this->result="";
$this->permDir="../applications/";
$this->fileError="";
/*$this->fileSize=2000;*/
}
public static function checkError($file){
	//echo "Welcome!";
	$message="";
	switch ($file['error']) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message .= "<p class='alert alert-danger'>The uploaded file exceeds the upload_max_filesize directive in php.ini</p>";
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message .= "<p class='alert alert-danger'>The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form</p>"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message .= "<p class='alert alert-danger'>The uploaded file was only partially uploaded</p>"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message .= "<p class='alert alert-danger'>No file was uploaded</p>"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message .= "<p class='alert alert-danger'>Missing a temporary folder</p>"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message .= "<p class='alert alert-danger'>Failed to write file to disk</p>"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message .= "<p class='alert alert-danger'>File upload stopped by extension</p>"; 
                break; 

            default: 
                $message = true; 
                break; 
        } 
        if($message == true) return 1;
		else return $message; 
    } 


public function receiveFile($fname,$type){
$this->fileName=$fname;

$is_error= File_Uploader::checkError($this->fileName);

if($is_error==1){
		switch ($type){
			case "image":
			$imgtype=array("image/jpeg","image/png","image/gif","image/jp2","image/bmp");
			$this->permDir="applications/images/";
			break;
			case "doc":
			
			$imgtype=array("application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/pdf");
			$this->permDir="applications/documents/";
			break;
			
			default:
			$imgtype=array("image/jpeg","image/png","image/gif","image/jp2","image/bmp");
			$this->permDir="applications/images/";
			
		}
		
		//echo "Wooow";
		$finfo = finfo_open(FILEINFO_MIME_TYPE); 
		$ext=finfo_file($finfo,$this->fileName['tmp_name']);
		if(in_array($ext,$imgtype)){
		$output=$this->permDir.rand(1,10000).$this->fileName['name'];
		if(move_uploaded_file($this->fileName['tmp_name'],$output)){
			return $output;
		} else {
			return "File upload failed.";	
		}
		
		
		}

	}else{return  $is_error;}
    
}

public function processForm($file){

$name=explode(".",$file);

$name[0]=strtolower($name[0]);
$name[0]=sanitizeForm($name[0])."";
$name[0]=$name[0]. Maths.rand()*10;
$final=implode(".",$name);
return $final;
}
public static function getMsg(){
return $this->fileError;
}
public function sanitaizeForm($txt){
$ch=array('%','/','\\','^','$','`','`','-',' ','#','&','*','+','|','&','=','(',')');

$name=str_replace($ch,"a",$txt);

return $name;
}
};


/*
$uploader=new File_Uploader();
$file=$_FILES['logo'];
$msg=$uploader->receiveFile($file);
if(isset($_FILES['uploadedFile'])){
if($msg){
$_SESSION['msg']="File Upload was Successful!";
 $_SESSION['typeOfMessage']="success";
header("Location:../index.php");
}else{
$_SESSION['msg']="".$uploader->getMsg();
 $_SESSION['typeOfMessage']="error";
header("Location:../index.php");
}

}

header("Location:../index.php");*/
?>