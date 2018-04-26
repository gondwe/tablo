<?php 

session_start();

function db($db = null){
	$USER = "root"; 
	$HOST = "localhost";
	$PWD = "toor";
	$DB = is_null($db) ? "sharp" : $db;
	
	$i = $con = new mysqli($HOST, $USER, $PWD, $DB);
	return $i;
	
}

$db = db();
$table = "users";


function spill($i){ echo "<pre>"; print_r($i); echo "</pre>"; } // var_dump et al

function get($i){ 
	global $db;
	$j = $db->query($i); 
	$l = [];
	if($db->errno ) die($db->error);
	while($k=$j->fetch_assoc()){
		$l[] = $k;
	}
	return $l;
}

function process($sql){
	global $db;
	spill($db->query($sql)? "Success" : $db->error);
}

function insert($table = null){
	$a =array_keys($_POST);$table = array_pop($a); array_pop($_POST);
	$fields = implode("`,`",array_keys($_POST));
	$values = implode("','",array_values($_POST));
	$sql = "insert into $table (`$fields`) values ('$values')";
	process($sql);
}

function update(){
	$a =array_keys($_POST);
	$table = array_pop($a);array_pop($_POST);$id = array_pop($_POST); 
	
	foreach($_POST as $k=>$v){ $fields[] = "`$k`='$v'"; }
	$fields = implode(", ",$fields);
	
	$sql = "update $table set $fields where id = '$id'";
	process($sql);
}

include("nav.php");