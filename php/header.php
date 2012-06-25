<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
	<meta charset="utf-8"/>
	<link rel="icon" type="image/png" href="pic/favicon.png" />
	<link media="screen" href="css/main.css" rel="stylesheet"/>
	<?php if(file_exists('css/'.$_SESSION['content'].'.css')){?><link media="screen" href="css/<?php echo $_SESSION['content'];?>.css" rel="stylesheet"/><?php }?>
	<?php if(file_exists('css/'.$_SESSION['content'].'-print.css')){?><link media="print" href="css/<?php echo $_SESSION['content'];?>-print.css" rel="stylesheet"/><?php }?>
	<script src="js/jquery-1.5.2.min.js"></script>
	<?php if(file_exists('js/'.$_SESSION['content'].'.js')){?><script src="js/<?php echo $_SESSION['content'];?>.js"></script><?php }?>
	<title>Hangman</title>
</head>
<body>
	<header class="wrapper" id="header">
		<h1><a href="?content=presentation">Hangman</a></h1>
	
		<nav class="wrapper" id="menu" role="navigation">
			<ul>
				<li><a href="?content=presentation"<?php if($_SESSION['content']=='presentation')echo ' class="selected"'?>>Présentation</a></li>
				<li><a href="?content=regles"<?php if($_SESSION['content']=='regles')echo ' class="selected"'?>>Règles</a></li>
				<li><a href="?content=jouer"<?php if($_SESSION['content']=='jouer')echo ' class="selected"'?>>Jouer</a></li>
				<li><a href="?content=proposition"<?php if($_SESSION['content']=='proposition')echo ' class="selected"'?>>Proposer</a></li>
				<li><a href="?content=equipe"<?php if($_SESSION['content']=='equipe')echo ' class="selected"'?>>Equipe</a></li>
			</ul>
		</nav>
	</header>
	
	<div class="wrapper">
		<div id="content">
			