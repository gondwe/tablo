<div class='container'>
<?php 
require ("db.php");
require ("sqlsharp.php");


$d = new tablo($table);

if(isset($_GET["a"])){
	$a = $_GET["a"];
	$d->edit($a);
}else{
	echo "select a record from VIEW to update";
}

$d->table();