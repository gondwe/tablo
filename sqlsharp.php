
<?php 
// require_once("db.php");

/* author:gondwe benard */

/* handle post data */
if(!empty($_POST)){
	if($_SESSION["action"] == "insert") insert();
	if($_SESSION["action"] == "update") update();
}


class tablo {
	protected $table;
	protected $data;
	protected $fields;
	protected $fieldnames;
	protected $rowcount;
	public $sql;
	private $db;
	private $fieldtypes;
	
	private $reserved = ["id","date"];
	
	
	function __construct($tbl){
		global $db;
		$this->table = $tbl;
		$this->db = $db;
	}
	
	
	function init($id=null){
		$i = gettype($this->table);
		switch($i){
			case "string" : 
			$this->sql = str_word_count($i) > 1 ? $this->table : "select * from `$this->table`";
			$this->query($id);
			break;
		}
		$this->fieldtypes = ["3"=>"number","7"=>"date","252"=>"textarea","253"=>"text",];
	}
	
	
	function query($id=null){
		if(is_null($id)){
			$this->data = $this->get($this->sql);
		}else{
			$this->data = $this->get($this->sql." where id = '$id'");
		}

	}
	
	public function table(){
		$this->init();
		
		echo "<table border=1 cellspacing=0 cellpadding=2 class='table table-striped'>";
		echo "<thead>";
			echo "<tr>";
				echo "<th>Sno</th>";
			foreach($this->fieldnames as $ff):
				if(!in_array($ff,$this->reserved)){
				echo "<th>$ff</th>";
				}
			endforeach;
			echo "<th></th>";
			echo "<th></th>";
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			$x = 1;
			foreach($this->data as $d=>$dd):
			echo "<tr>";
			echo "<td>$x</td>";
				foreach($dd as $db=>$da){
					if(!in_array($db, $this->reserved)){ echo "<td>$da</td>"; }
				}
			echo "<td><a href='./edit.php?a=".$dd['id']."'>Edit</a></td>";
			echo "<td><a href='./delete.php?a=".$dd['id']."&b=".$this->table."'>Delete</a></td>";
			echo "</tr>";
			$x++;
			
			endforeach;
			
		echo "</tbody>";
		echo "</table>";
	}
	
	public function newform(){
		$this->init();
		$_SESSION["action"] = "insert";
		echo "<form action='' class='' method='post'>";
		foreach($this->fields as $id=>$f):
		$this->fieldset($f);
		endforeach;
		echo "<input type='submit' name='$this->table' value='SAVE' class='btn btn-success'>";
		echo "</form>";
		
		
	}
	
	public function edit($a){
		$this->init($a);
		$_SESSION["action"] = "update";
		echo "<form action='' class='' method='post'>";
		foreach($this->fields as $id=>$f):
		$this->fieldset($f);
		endforeach;
		echo "<input type='hidden' name='rowid' value='".$this->data[0]["id"]."'>";
		echo "<input type='submit' name='$this->table' value='SAVE' class='btn btn-success'>";
		echo "</form>";
	}
	
	
	function fieldset($d){
		$value = $_SESSION["action"] == "update" ? $this->data[0][$d->name] : NULL;
		if(!in_array($d->name,$this->reserved)){
			echo "<div class=''>";
			$this->label($d->name);
				
			if($d->type == "252"){
				echo "<textarea type='text' name='$d->name'>$value</textarea>";
			}else{
				echo "<input type='".$this->fieldtypes[$d->type]."' name='$d->name' value='$value'>";
			}
			echo "</div>";
		}
	}
	
	
	function label($n){ 
		echo "<div>$n</div>";
	}
		
	
	function get($i){ 
		global $db;
		$j = $db->query($i); 
		$l = [];
		if($db->errno ) die($db->error);
		$fields = $j->fetch_fields();
		while($k=$j->fetch_assoc()){
			$l[] = $k;
		}
		$this->fields = $fields;
		// spill($fields);
		$this->fieldnames = array_column((array)$fields, "name");
		return $l;
	}
	
	
}