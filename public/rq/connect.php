<?
//===
$config = array(
'host'=>"localhost",
'user'=>"admin",
'pass'=>"MySQL",
'db'=>"pbcode"
);

$cn =mysql_connect($config["host"],$config["user"],$config["pass"]);
$db = mysql_select_db($config["db"]);
@mysql_query("SET NAMES UTF8");
if(!$cn&&!$db){die(mysql_error());}
//===
?>
