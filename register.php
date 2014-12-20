<?
session_start();
require("public/rq/connect.php");
require("public/rq/security.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
</head>

<body>

<?
if(!$_POST){
?>

<form action="" method="post">
<input type="text" autocomplete="off" placeholder="Username" name="user" required /><br>
<input type="password" autocomplete="off" placeholder="Password" name="pass" required /><br>
<input type="password" autocomplete="off" placeholder="Confirm Password" name="c_pwd" required /><br>
<input type="email" autocomplete="off" placeholder="E-Mail" name="email" required /><br>

<input type="submit" value="Register" />

</form>

<?
} else{

$user = $_POST["user"];
$pass = $_POST["pass"];
$cpwd = $_POST["c_pwd"];
$mail = $_POST["email"];
$spwd = md5($pass);
$sql = "INSERT INTO `".$config["db"]."`.`account` (`id`,`username`,`password`,`email`,`point`,`status`)VALUES (NULL,'".$user."','".$spwd."','".$mail."','0','1');";

$sql_check = "SELECT * FROM account WHERE username LIKE '".$user."' OR email LIKE '".$email."'";
$qry_check = mysql_query($sql_check);
$num_check = mysql_num_rows($qry_check);

if(isset($user) || isset($pass) || isset($mail) || isset($cpwd)){

if($num_check){
	$error = "ผู้ใช้งานนี้หรืออีเมลนี้มีในระบบแล้ว";
} elseif($pass!=$cpwd){
	$error = "รหัสผ่านไม่ตรงกัน";
} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	$error = "รูปแบบอีเมลไม่ถูกต้อง";
} else{
	if(mysql_query($sql)){
		$error = "สมัครสมาชิกสำเร็จ";
	} else{
		$error = "ไม่สามารถสมัครสมาชิกได้";
	}
}

} else{
	$error = "กรุณากรอกข้อมูลให้ครบ";
}

}

if(isset($error)){echo $error;}

?>

</body>
</html>
