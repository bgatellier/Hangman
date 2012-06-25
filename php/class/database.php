<?php
	class Database{
		private $connection_id;
		private $db_name;
		private $host;
		private $login;
		private $pass;
		
		/*	Constructor	*/
		function __construct($connect=true){
			$this->host = 'localhost';
			$this->login = 'bastiengdev';
			$this->pass = 'redalert51';
			$this->db_name = 'bastiengdev';
			
			if($connect)
				$this->connect();
		}
		
		/*
		 * Connection to DB
		 */
		private function connect(){
			$this->connection_id = mysql_connect($this->host,$this->login,$this->pass)
								or die(
									'<div class="debug">'.
									'<h6 id="title">Connexion à <em>'.$this->host.'</em> impossible</h6>'.
									'<dl>'.
										'<dt>Raison</dt><dd>'.mysql_error().'</dd>'.
									'</dl>'.
									'</div>'
								);
			mysql_select_db($this->db_name,$this->connection_id);
			mysql_query("SET NAMES 'utf8'");
		}
		
		
		/*
		 * Disconnection from DB
		 */
		function close(){
			mysql_close($this->connection_id);
		}
		
		
		/*
		 * Query
		 */
		function query($query){
			$results = mysql_query($query,$this->connection_id)
							or die(
								'<div class="debug">'.
								'<h6 id="title">Erreur lors de l\'exécution d\'une requête</h6>'.
								'<dl>'.
									'<dt>Requête concernée</dt><dd>'.$query.'</dd>'.
									'<dt>Raison</dt><dd>'.mysql_error($this->connection_id).'</dd>'.
								'</dl>'.
								'</div>'
							);
			return $results;
		}
	}
?>