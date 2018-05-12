
<?php 
// require_once("db.php");

/* author:gondwe benard */

/* handle post data */
if(!empty($_POST)){
	if($_SESSION["action"] == "insert") insert();
	if($_SESSION["action"] == "update") update();
}


class tablo extends fieldsets{
	protected $table;
	protected $data;
	protected $fields;
	protected $fieldnames;
	protected $rowcount;
	protected $combos;
	public $sql;
	private $db;
	private $fieldtypes;
	
	private $reserved = ["id","date"];
	
	
	function __construct($tbl){
		global $db;
		$this->table = $tbl;
		$this->db = $db;
	}
	
	function __destruct(){
	}
	
	
	function init($id=null){
		$i = gettype($this->table);
		switch($i){
			case "string" : 
			$this->sql = str_word_count($i) > 1 ? $this->table : "select * from `$this->table`";
			$this->query($id);
			break;
		}
		$this->fieldtypes = ["3"=>"number","7"=>"date","252"=>"textarea","blob"=>"text","253"=>"text",];
		$this->fsets();
	}
	
	
	function query($id=null){
		if(is_null($id)){
			$this->data = $this->get($this->sql);
		}else{
			$this->data = $this->get($this->sql." where id = '$id'");
		}

	}
	
	/* auto fill form combo boxes  */
	public function combo($a,$b){
		$data = arrlist(get($b));
		$this->combos[$a] = $data;
	}
	
	
	/* display a table */
	public function table(){
		$this->init();
		echo "<link href='css/jquery.dataTables.min.css' rel='stylesheet'>";
		echo '<table id="example" class="row-border">';
		echo "<thead>";
			echo "<tr>";
				echo "<th>Sno</th>";
			foreach($this->fieldnames as $ff):
				if(!in_array($ff,$this->reserved)){
				echo "<th>".strtoupper($ff)."</th>";
				}
			endforeach;
			echo "<th>ACTION</th>";
			
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			$x = 1;
			foreach($this->data as $d=>$dd):
			echo "<tr>";
			echo "<td>$x</td>";
				foreach($dd as $db=>$da){
					if(!in_array($db, $this->reserved)){ 
						if(isset($this->combos[$db])){
							/* populate cbo data */
							echo "<td>".@$this->combos[$db][$da]."</td>";
						}else{
							echo "<td>$da</td>";
						}
							
					}
				}
			echo "<td><a href='./edit.php?a=".$dd['id']."'>Edit</a> | ";
			echo "<a href='./delete.php?a=".$dd['id']."&b=".$this->table."'>Delete</a></td>";
			echo "</tr>";
			$x++;
			
			endforeach;
			
		echo "</tbody>";
		echo "</table>";
		echo "<script src='js/jquery-1.12.4.js'></script>";
		echo "<script src='js/jquery.dataTables.min.js'></script>";
		echo "
		<script>
		$(document).ready(function() {
			$('#example').DataTable();
		} );
		</script>
		";
		
	}
	
	public function newform(){
		$this->init();
		$_SESSION["action"] = "insert";
		echo "<form action='' class='' method='post'>";
		foreach($this->fields as $id=>$f):
		$this->fieldset($f);
		endforeach;
		echo $this->savebutton();
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
		echo $this->savebutton();
		echo "</form>";
	}
	
	function savebutton(){
		return "<input style='margin:10px' type='submit' name='$this->table' value='SAVE' class='btn btn-success'>";
	}
	
	
	function fieldset($d){
		$value = $_SESSION["action"] == "update" ? $this->data[0][$d->name] : NULL;
		
		echo "<div class=''>";
			$this->label($d->name);
			$this->reserve_filter($d,$value);
			$this->combo_filter($d, $value);
		echo "</div>";
	}
	
	/* filter out reserved fields */
	function reserve_filter($d,$v){
		if(!in_array($d->name,$this->reserved) && !isset( $this->combos[$d->name])){
			
				
			if($d->type == "252"){
				echo "<textarea class='col-md-4' type='text' name='$d->name'>$v</textarea>";
			}else{
				echo "<input class='col-md-4' type='".$this->fieldtypes[$d->type]."' name='$d->name' value='$v'>";
			}
			
		}
		
	}
	
	
	/* fill in selectbox bound fields */
	function combo_filter($d,$v){
		if(isset($this->combos[$d->name])){
			echo "<select class='col-md-4' name='$d->name' >";
				foreach($this->combos[$d->name] as $i=>$j){
					$selected = $i == $v ? "selected" : null;
					echo "<option value='$i' $selected >$j</option>";
				}
			echo "</select>";
		}
	}
	
	function label($n){ 
		if(!in_array($n,$this->reserved) ){
			echo "<div>".strtoupper($n)."</div>";
		}
	}
		
	
	function get($i){ 
		global $db;
		
		if(@!$db->query($i)) die("Connection Problems");
		$j = $db->query($i); 
		$l = [];
		// if($db->errno ) die($db->error);
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


/* actions via ajax */
?>

<script>
	function del(){
		
	}
	
	function edit(){
		
	}
	
</script>