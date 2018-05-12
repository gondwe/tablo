

<link rel="stylesheet" href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css'>

<style>
	ul {list-style-type:none; }
	li {display:inline-block;padding:2px;}
	a {text-decoration:none}
	#tt { background:green; margin:3px; padding:10px; }
	#tt a { color:white}
	.active {background:yellow}
</style>

<ul>
	<li><a href='./create.php'>Create</a></li>
	<li><a href='./view.php'>View</a></li>
	<li><a href='./edit.php'>Edit</a></li>
	<li><a href='./delete.php'>Delete</a></li>
</ul>


<?php
$tt = get("show tables");
$tables = array_column($tt, "Tables_in_tablo");
// spill($tables);

echo "<hr>";
echo "<ul>";
foreach($tables as $t)
{
	$active = $t == $table ? "background:#961919;border-bottom:5px solid black;" : null;
	echo "<li class='btn btn-xs' id='tt' style='$active'><a  href='./set_table.php?t=$t'>$t</a></li>";
}
echo "</ul>";
echo "<hr>";