<?
session_start();
require("public/rq/connect.php");
if($_SESSION["login"]==false){header("Location:login.php");}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home</title>
<style>
.box{
	width:350px;
	border-color:#000;
	border-style:solid;
	border-width:1px;
	background-color:#F3F3F3;
	border-radius:5px;
	padding:10px;
	margin:5px;
}
</style>
</head>

<body>

<div class="box" align="center">
<?
$qry_if = mysql_query("SELECT * FROM account WHERE username LIKE '".$_SESSION["username"]."'");
$rst_if = mysql_fetch_array($qry_if);
?>
ชื่อ : <strong><?=$_SESSION["username"]?></strong> | เงิน : <strong><?=$rst_if["point"]?></strong> | <a href="logout.php">ออกระบบ</a>
</div>

<div class="box" align="center">
<?
if(!$_GET){
$sql_p = "SELECT * FROM `product` ORDER BY id DESC";
$qry_p = mysql_query($sql_p);
while($rst_p = mysql_fetch_array($qry_p)){
echo "<div><img width='74' src='".$rst_p["picture"]."' />".$rst_p["name"]." - ".$rst_p["price"]." (<a href='?id=".$rst_p["code_id"]."'>สั่งซื้อ</a>)</div>";	
}
} else{
	
	if($_GET){
	if(isset($_GET["id"]) && !$_GET["act"] && !$_GET["cmd"]){
		$sql_p = "SELECT * FROM `product` WHERE `code_id` LIKE '".mysql_real_escape_string($_GET["id"])."'";
		$qry_p = mysql_query($sql_p);
		$rst_p = mysql_fetch_array($qry_p);
		
		$sql_status = "SELECT * FROM `code` WHERE `code_id` LIKE '".mysql_real_escape_string($_GET["id"])."' AND `status`='1'";
		$qry_status = mysql_query($sql_status);
		$num_status = mysql_num_rows($qry_status);
		
		if($num_status){
			$status = $num_status;
		} else{
			$status = "<font color='red'>หมด</font>";
		}
		
		if($num_status){
			$buy_btn = "<a href='?id=".mysql_real_escape_string($_GET["id"])."&act=buy'>สั่งซื้อ</a>";
		}
		
		echo "<img src='".$rst_p["picture"]."' /><br />ชื่อ : <strong>".$rst_p["name"]."</strong><br />ยศที่ต้องการ : <strong>".$rst_p["rank"]."</strong><br />ราคา : <strong>".$rst_p["price"]."</strong> บาท<br />สถานะ : <strong>".$status."</strong><br/>".$buy_btn;
	} 
	if($_GET["id"] && $_GET["act"] && $_GET["act"]=="buy" && !$_GET["cmd"]){
		$qry_c = mysql_query("SELECT * FROM `code` WHERE `code_id` LIKE '".mysql_real_escape_string($_GET["id"])."' AND `status`=1");
		$num_c = mysql_num_rows($qry_c);
		
		if($num_c){
		echo '<a href="?id='.mysql_real_escape_string($_GET["id"]).'&act=buy&cmd=confirm">ยืนยัน</a> | <a href="index.php">นอนยัน</a>';
		} else{
		echo 'สินค้าหมด';
		}
	} else if($_GET["id"] && $_GET["act"] && $_GET["act"]=="buy" && $_GET["cmd"] && $_GET["cmd"]=="confirm"){
		
		$id_code = mysql_real_escape_string($_GET["id"]);
		
		$qry_a = mysql_query("SELECT * FROM account WHERE username LIKE '".$_SESSION["username"]."'");
		$rst_a = mysql_fetch_array($qry_a);
		
		//Purchase Product
		$sql_p = "SELECT * FROM product WHERE code_id LIKE '".$id_code."'";
		$qry_p = mysql_query($sql_p);
		$rst_p = mysql_fetch_array($qry_p);
		
		if($rst_p["price"]>$rst_a["point"]){
			echo 'ยอดเงินของคุณไม่พอ';
		} else{
		
		$qry_c = mysql_query("SELECT * FROM `code` WHERE `code_id` LIKE '".$id_code."' AND `status`=1");
		$num_c = mysql_num_rows($qry_c);
		$rst_c = mysql_fetch_array($qry_c);
		
		$sql_buy = "UPDATE `".$config["db"]."`.`account` SET `point` = `point`-'".$rst_p["price"]."' WHERE `account`.`id` =".$_SESSION['id']." LIMIT 1 ;";
		$sql_own = "INSERT INTO `".$config["db"]."`.`owner` (`id` ,`username` ,`price` ,`txid` ,`code_id` ,`code`)VALUES ";
		$sql_own .= "(NULL,'".$_SESSION["username"]."','".$rst_p["price"]."','".strtoupper(uniqid())."','".$rst_c["code_id"]."','".$rst_c["code"]."');";
		$sql_sta = "UPDATE `".$config["db"]."`.`code` SET `status` =  '0' WHERE  `code`.`id` =".$rst_c["id"]." LIMIT 1 ;";
		
		if($num_c){
		if(mysql_query($sql_buy) && mysql_query($sql_own) && mysql_query($sql_sta)){
			echo 'สั่งซื้อสำเร็จ (<a href="view.php">ดูโค้ด</a>)';
		} else{
			echo 'ไม่สามารถสั่งซื้อได้ (<a href="index.php">ย้อนกลับ</a>)';
		}
		} else{
			echo 'สินค้าหมด (<a href="index.php">ย้อนกลับ</a>)';
		}
		
		}
		
		//Purchase Product
		
	} else{
		echo 'Not Found function! <script>window.location="?id='.mysql_real_escape_string($_GET["id"]).'&act=buy";</script>';
	}
	
	if(!$_GET["id"] && !$_GET["act"]){
		die("404");
	}
	
	} //$_GET
	
}
?>
</div>

</body>
</html>
