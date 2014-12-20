<?
session_start();
if($_SESSION["login"]==false){header("Location: login.php");}
require("public/rq/connect.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Code(s)</title>
<style>
td{
	padding:5px;
}
</style>
</head>

<body>

<table border="1">
<tr>
<td>#ID</td> <td>ชื่อสินค้า</td> <td>โค้ด</td> <td>#TXID</td>
</tr>
<?
$sql = "SELECT * FROM owner WHERE username LIKE '".$_SESSION["username"]."'";
$qry = mysql_query($sql);
$num = mysql_num_rows($qry);
if(!$num){
	echo '<tr><td colspan="4"><div align="center">ไม่พบ</div></td></tr>';
} else{
while($rst = mysql_fetch_array($qry)){
	$qry_p = mysql_query("SELECT * FROM `product` WHERE `code_id` LIKE  '".$rst["code_id"]."'");
	$rst_p = mysql_fetch_array($qry_p);
	echo '<tr> <td>'.$rst["id"].'</td> <td>'.$rst_p["name"].'</td> <td>'.$rst["code"].'</td> <td>'.$rst["txid"].'</td> </tr>';
}
}
?>
</table>

</body>
</html>
