<?php
class Account{

	private $con;
	private $errorArray;

	public function __construct($con){
		$this->con = $con;
		$this->errorArray  = array();

	}

	public function login($un, $pw){
		$pw = md5($pw);

		$check = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

		if(mysqli_num_rows($check) == 1){
			return true;
		}
		else{
			array_push($this->errorArray, Constants::$loginfailed);
			return false;
		}
	}

	public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
			$this->validateusername($un);
			$this->validatefirstname($fn);
			$this->validatelastname($ln);
			$this->validateemails($em , $em2);
			$this->validatepasswords($pw , $pw2);

			if(empty($this->errorArray) == true){
				//insert into database
				return $this->insertuserdetails($un, $fn, $ln, $em, $pw);
			}
			else{
				return false;
				// echo "erroe bdjbd";
			}


			}

			public function getError($error){
				if(!in_array($error, $this->errorArray)){
					$error = "";
				}
				return "<span class='errorMessage'>$error</span>";

	}

	private function insertuserdetails($un, $fn, $ln, $em, $pw)
	{
		$encryptedPw=md5($pw);
		$profilepic="assests/images/profile-pic/z1.png";

		$date = date("Y-m-d");

		$result = mysqli_query($this->con, "INSERT INTO users VALUES (NULL,'$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilepic')");
		// echo "error:" . mysqli_error($con);

		return $result;	
	}

	private function validateusername($un){
		if(strlen($un) > 25 || strlen($un) < 5) {
			array_push($this->errorArray, Constants::$usernameCharacter);
			return;
		}
		$checkusername = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
		if(mysqli_num_rows($checkusername) != 0){
			array_push($this->errorArray, Constants::$usernametaken);
			return;
		}
	}

	private function validatefirstname($fn){
		if(strlen($fn) > 25 || strlen($fn) < 2) {
			array_push($this->errorArray, Constants::$firstnameCharacter);
			return;
		}

	}

	private function validatelastname($ln){
		if(strlen($ln) > 25 || strlen($ln) < 2) {
			array_push($this->errorArray, Constants::$lastnameCharacter);
			return;
		}
	
	}

	private function validateemails($em , $em2){
		if($em != $em2){
			array_push($this->errorArray, Constants::$emailDoNotMatch);
			return;
		}

		if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
			array_push($this->errorArray, Constants::$emailInvalid);
			return;
		}
		$checkemail= mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
		if(mysqli_num_rows($checkemail) != 0){
			array_push($this->errorArray, Constants::$emailtaken);
			return;
		}
	
	}

	private function validatepasswords($pw , $pw2){
		if($pw != $pw2){
			array_push($this->errorArray, Constants::$passwordDoNotMatch);
			return;
		}

		if(preg_match('/[^A-Za-z0-9]/', $pw)){
			array_push($this->errorArray, Constants::$passwordsNotAlphnumeric);
			return;
		}

		if(strlen($pw) > 30 || strlen($pw) < 5) {
			array_push($this->errorArray, Constants::$passwordCharacters);
			return;
		}
	
	}
}


?>