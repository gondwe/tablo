<div class='container'>
<?php 
require ("db.php");
require ("sqlsharp.php");


$d = new tablo($table);
$d->newform();
$d->table();