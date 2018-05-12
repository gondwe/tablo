<?php 


class fieldsets {

	function fsets(){
		switch($this->table){
			case "users" : 
			$this->combo("country","select id, name from countries");
			break;
		}
		
	}

}