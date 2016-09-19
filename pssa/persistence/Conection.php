<?php
class Conection {
	var $con;
	var $result;

	function Conection() {
		$this -> con = mysql_connect("localhost", "root", "");
		mysql_select_db("pssa", $this -> con);
		mysql_query("SET NAMES 'UTF8'");
	}

	function run($query) {
		$this -> result = mysql_query($query, $this -> con) or die(mysql_error());
		return $this -> result;
	}

	function free() {
		mysql_free_result($this -> result);
	}

	function close() {
		mysql_close($this -> con);
	}

	function numRows() {
		return mysql_num_rows($this -> result);
	}

	function numFields() {
		return mysql_num_fields($this -> result);
	}

	function fetch() {
		return mysql_fetch_row($this -> result);
	}
}
?>
