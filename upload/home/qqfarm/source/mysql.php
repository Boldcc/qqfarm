<?php

# 数据库操作类
# modify by seaif@zealv.com

class dbstuff {

	var $querynum = 0;
	var $charset;
	var $link;

	public function __construct($myCFG) {
		$CFG = array('dbhost' => '', 'dbuser' => '', 'dbpwd' => '', 'dbname' => '', 'charset' => '', 'pconnect' => 0);
		extract(array_merge($CFG, array_intersect_key($myCFG, $CFG)));
		$this->charset = $charset;
		$this->connect($dbhost, $dbuser, $dbpwd, $dbname, $pconnect);
	}

	private function connect($dbhost, $dbuser, $dbpwd, $dbname = '', $pconnect = 0, $halt = true) {
		if($pconnect) {
			if(!$this->link = @mysql_pconnect($dbhost, $dbuser, $dbpwd)) {
				$halt && $this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = @mysql_connect($dbhost, $dbuser, $dbpwd, 1)) {
				$halt && $this->halt('Can not connect to MySQL server');
			}
		}
		if($this->version() > '4.1') {
			if($this->charset) {
				@mysql_query("SET character_set_connection=$this->charset, character_set_results=$this->charset, character_set_client=binary", $this->link);
			}
			if($this->version() > '5.0.1') {
				@mysql_query("SET sql_mode=''", $this->link);
			}
		}
		if($dbname) {
			@mysql_select_db($dbname, $this->link);
		}
	}

	public function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	public function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	public function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link)) && $type != 'SILENT') {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		return $query;
	}

	public function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	public function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	public function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	public function num_fields($query) {
		return mysql_num_fields($query);
	}

	public function free_result($query) {
		return mysql_free_result($query);
	}

	public function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	public function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	public function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	public function version() {
		return mysql_get_server_info($this->link);
	}

	public function close() {
		return mysql_close($this->link);
	}

	public function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	public function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	private function halt($message = '', $sql = '') {
		if(FARM_DEBUG) {
			$errdata = 
				"Message: {$message} \r\n" .
				"    URI: {$_SERVER['REQUEST_URI']} \r\n" .
				"    SQL: {$sql} \r\n" .
				"  Error: {$this->error()} \r\n" .
				"  Errno: {$this->errno()} \r\n \r\n";
			error_log($errdata, 3, "data/logs/#mysql_error.log");
		}
		die("MySQL Error: {$this->errno()}.");
	}
}

?>