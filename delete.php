<div class='container'>
<?php 
require ("db.php");
require ("sqlsharp.php");


if(isset($_GET["a"])){
	$a = $_GET["a"];
	$table = $_GET["b"];
	$db->query("delete from `$table` where id = '$a'");
	header("location:".$_SERVER["HTTP_REFERER"]);
}else{
	echo "select a record from VIEW and delete";
}
$d = new tablo("users");
$d->table();