<?php
	session_start();
	include('php/class/database.php');
	$db = new Database();
	
	
	/*
	 * Contenus
	 */
	if(!isset($_SESSION['contents_authorized'])){
		$dirpath = 'php/content/';
		$dh = opendir($dirpath);
		while(($file=readdir($dh)) !== false){
			if(is_file($dirpath.$file))
				$_SESSION['contents_authorized'][] = htmlspecialchars(preg_replace('/\..*$/', '', $file));
		}
		closedir($dh);
	}
	
	if( !isset($_SESSION['content']) )
		$_SESSION['content'] = 'presentation';
	elseif(isset($_GET['content'])&&in_array($_GET['content'],$_SESSION['contents_authorized']))
		$_SESSION['content'] = $_GET['content'];
		
	
	/*
	 * Déconnexion de l'administration
	 */
	if(isset($_GET['disconnect'])&&isset($_SESSION['user']))
		unset($_SESSION['user']);
	
	
	/*
	 * Création de la page
	 */
	include('php/header.php');
	include('php/content/'.$_SESSION['content'].'.php');
	include('php/footer.php');
	
	
	$db->close();
?>