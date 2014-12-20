<?
session_start();
require("public/rq/connect.php");
require("public/rq/security.php");
$security = new security;

if($_SESSION["login"]==true){header("Location: index.php");}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
</head>

<body>

<?
if(!$_POST){
?>
<form action="" method="post">

<input type="text" placeholder="Username" name="user" required autocomplete="off" /><br>
<input type="password" placeholder="Password" name="pass" required autocomplete="off" /><br>

<input type="submit" value="Login" />

</form>
<?
} else{
	
	$user = $_POST["user"];
	$pass = $_POST["pass"];
	
	if( empty($user) or empty($pass) ){
		$error = "กรอกข้อมูลให้ครบ";
	} else{
		$objLogin = $security->checkLogin($user,$pass);
		if($objLogin==true){
			$qry = mysql_query("SELECT * FROM `account` WHERE `username` LIKE '".$user."'");
			$rst = mysql_fetch_array($qry);
			$_SESSION["login"]=true;
			$_SESSION["username"]=mysql_escape_string($rst["username"]);
			$_SESSION["id"]=$rst["id"];
			$_SESSION["email"]=$rst["email"];
			$error = "เข้าสู่ระบบสำเร็จ <script>window.location='index.php';</script>";
		} else{
			$error = "ชื่อผู้ใช้งานหรือรหัสผ่านผิด".$objLogin;
		}
	}
	
	if(isset($error)){echo $error;}
	
}
?>

</body>
</html>
