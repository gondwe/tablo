<?php 

require("db.php");
$t = $_GET["t"];
$_SESSION["table"] = $t;
header("location:".$_SERVER["HTTP_REFERER"]);