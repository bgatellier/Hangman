<?php
	include('../config/hangman.php');
	include('../class/database.php');
	$db = new Database();
			
	// Choix d'un mot au hasard
	$mot_results=$db->query('SELECT fr,en FROM hangman_words WHERE date_validation IS NOT NULL ORDER BY RAND() LIMIT 0,1');
	$langs = mysql_fetch_assoc($mot_results);
	$db->close();
	
	// Création du fichier XML
	$xml_file = '<?xml version="1.0" encoding="UTF-8"?>';
	$xml_file.= '<hangman>';
	$xml_file.= '<turns>'.$turns_max.'</turns>';
	$xml_file.= '<word>';
	foreach($langs as $lang=>$translation)
		$xml_file.= '<writing lang="'.$lang.'"><![CDATA['.$translation.']]></writing>';
	$xml_file.= '</word>';
	$xml_file.= '</hangman>';
	
	echo $xml_file;
?>