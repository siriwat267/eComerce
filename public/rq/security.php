<?
class security{
	public function checkLogin($user,$pass){
		require("public/rq/connect.php");
		$sql = "SELECT * FROM `account` WHERE `username` LIKE '".$user."'";
		$qry = mysql_query($sql);
		$rst = mysql_fetch_array($qry);
		$pwd = md5($pass);
		if($pwd==$rst["password"]){
			return(true);
		} else{
			return(false);
		}
		
	}
}
?>
