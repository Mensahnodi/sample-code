<?php 
@session_start();
require_once("mysql.class.php");
require_once("swift_required.php");
class Application extends MYSQL{
    private $transport;
    private $mailer;
	public function __construct(){
		parent::__construct();
        $this->transport=Swift_SmtpTransport::newInstance("gator4109.hostgator.com",465,"ssl")->setUsername("adfa@asdfaaa.edu.gh")
            ->setPassword("gas*adfaa");
        $this->mailer=Swift_Mailer::newInstance($this->transport);
	}	
	
	public function CompleteApplication(){
		
		
		
	}
	public function getStudID(){
		$generated_pwd=autoGenerateStudentID();
		$sql=new MySQL();
		$sql->Query("select * from application_main where studID='$generated_pwd'");
		if($sql->RowCount()>0){
			$this->getStudID();
		}else{
        return $generated_pwd;
		}
	}
	 public function autoGenerateStudentID() {
        // Letters to be used for the auto generated password
        $letters = "01234567899876543210543215678901234567890122344556788900";

        $possible_letters = str_split($letters);
        $genpassword="";
        for($i=0; $i<10; $i++){
            $genpassword .= $possible_letters[rand(0,51)].rand(1,10);
        }
		$generated_pwd=$genpassword;
		
        return $genpassword;
		
    }
	public function sendRegistrationConfirmation($email,$name,$id){
		$file=file_get_contents("../pages/registration_confirmation.php");
		$input=str_replace("{name}",$name,$file);
		$input1=str_replace("{email}",$email,$input);
		//$link="http://localhost/abot/pages/activate.php?v=".sha1($email)."&o=".sha1($id);
		$link="http://apply.abotcollege.edu.gh/pages/activate.php?v=".sha1($email)."&o=".sha1($id);
		$input2=str_replace("{link}",$link,$input1);
		$message=Swift_Message::newInstance()
		->setSubject("ABOT College User Registration")
		->setFrom(array("info@abotcollege.edu.gh"=>"ABOT College of Health Sciences and Technology"))
		->setTo(array($email,"registration@abotcollege.edu.gh"))

		->setBody($input2,"text/html");
		$rs=$this->mailer->send($message);
	}
	public function SendAdmissionMail($studentID="",$studentname="",$email){

        $file=file_get_contents("pages/student_message.php");
        $inputs=str_replace('{serial}',$studentID,$file);
        $input1=str_replace('{name}',$studentname,$inputs);
		$message= Swift_Message::newInstance()
            ->setSubject("Admission Office - ABOT College")
		    ->setFrom(array("sdf@adfasa.edu.gh"=>"ABOT College of Health Sciences and Technology"))
		    ->setTo(array($email,"dafasf@afasfa.edu.gh"))
		    ->setBody($input1,"text/html");
            $rs=$this->mailer->send($message);
	
	}
}
?>