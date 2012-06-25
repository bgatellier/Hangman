<?php
// Callback PHP lorsque Javascript ne peut fonctionner
include('php/config/hangman.php');

/**
 * Remplace les caractères accentués d'une chaîne par leurs équivalents non-accentués
 * @param $str Chaîne de caractères à traiter
 * @param $charset Encodage des caractères (par défaut, utf-8)
 * @return Chaîne de caractères dépourvue d'accents
 */
function wd_remove_accents($str, $charset='utf-8'){
	$str = htmlentities($str, ENT_NOQUOTES, $charset);

	$str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
	$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

	return $str;
}


// Si l'utilisateur souhaite recommencer une partie
if(!isset($_SESSION['mot_selectionne'])||isset($_GET['play-again'])){
	// Sélection d'un mot au hasard
	$mot_results=$db->query('SELECT fr,en FROM hangman_words WHERE date_validation IS NOT NULL ORDER BY RAND() LIMIT 0,1');
	$_SESSION['mot_selectionne']=mysql_fetch_assoc($mot_results);

	$_SESSION['lettres_saisies'] = array();
	$_SESSION['lettres_trouves'] = array();

	$_SESSION['nb_tours_restants'] = $turns_max;
}
// Si l'utilisateur clique sur une lettre et qu'elle n'a pas déjà été jouée
elseif(isset($_GET['letter'])&&!in_array(wd_remove_accents($_GET['letter']),$_SESSION['lettres_saisies'])){
	$_SESSION['lettres_saisies'][] = $_GET['letter'];	// Sauvegarde de la lettre saisie

	// Si la lettre fait partie d'un des mots, on l'enregistre comme lettre trouvée
	if(stripos(wd_remove_accents($_SESSION['mot_selectionne']['fr']),$_GET['letter'])!==false||stripos(wd_remove_accents($_SESSION['mot_selectionne']['en']),$_GET['letter'])!==false)
	$_SESSION['lettres_trouves'][] = $_GET['letter'];
	elseif($_SESSION['nb_tours_restants']>0)	// Si la lettre ne fait pas partie d'aucun des 2 mots et qu'il reste des tours de jeu
	$_SESSION['nb_tours_restants']--;
}


// Si le mode cheat est activé, on ajoute l'ensemble des lettre du mot et de ses traductions aux lettres déjà trouvées
if(isset($_GET['cheat'])){
	$_SESSION['lettres_trouves'] = array();
	foreach($_SESSION['mot_selectionne'] as $word){
		$word_length = mb_strlen($word,'utf-8');

		for($i=0;$i<$word_length;$i++)	// Parcours de l'ensemble des lettres du mot
		$_SESSION['lettres_trouves'][] = wd_remove_accents(mb_substr($word,$i,1,'utf-8'));
	}
}
?>
<div id="jeu">
	<div id="hangman">
		<img
			src="pic/hangman/hangman-<?php echo ($turns_max-$_SESSION['nb_tours_restants']);?>.png" />
	</div>
	<div id="words">
		<img alt="Mot en français" src="pic/lang/fr.png" />
		<ul class="word-to-find" id="word-fr">
			
			
			
			
			
			
			
		<?php
		$fr_length = mb_strlen($_SESSION['mot_selectionne']['fr'],'utf-8');
		for($i=0;$i<$fr_length;$i++){
			// Récupération du i-ème charactère du mot français
			$char = mb_substr($_SESSION['mot_selectionne']['fr'],$i,1,'utf-8');
			// Le caractère non-accentué fait parti des lettres trouvées
			$char_found = in_array(wd_remove_accents($char),$_SESSION['lettres_trouves']);
			?>
			<li  
			<?php if($_SESSION['nb_tours_restants']==0&&!$char_found){?>
				class="lettre-manquante"        <?php }?>><?php
				if($_SESSION['nb_tours_restants']==0||$char_found)
				echo $char;
				else
				echo '&nbsp;';
				?>
			</li>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<?php }?></ul>
		<img alt="Mot en anglais" src="pic/lang/gb.png" />
		<ul class="word-to-find" id="word-en">
			
			
			
			
			
			
			
		<?php
		$en_length = mb_strlen($_SESSION['mot_selectionne']['en'],'utf-8');
		for($i=0;$i<$en_length;$i++){
			// Récupération du i-ème caractère du mot anglais
			$char = mb_substr($_SESSION['mot_selectionne']['en'],$i,1,'utf-8');
			// Le caractère non-accentué fait parti des lettres trouvées
			$char_found = in_array(wd_remove_accents($char),$_SESSION['lettres_trouves']);
			?>
			<li  
			<?php if($_SESSION['nb_tours_restants']==0&&!$char_found){?>
				class="lettre-manquante"        <?php }?>><?php
				if($_SESSION['nb_tours_restants']==0||$char_found)
				echo $char;
				else
				echo '&nbsp;';
				?>
			</li>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<?php }?></ul>
		<form action="?" id="play-form" method="get">
			<p>
				<input type="submit" id="play-again" name="play-again"
					value="Rejouer" />
			</p>
			<ul>







			<?php
			/**
			 * Parcours de la table de caractères ascii,
			 * et conversion de chacun des identifiants des éléments de la plage en caractère
			 */
			for($lettre_ascii=97;$lettre_ascii<123;$lettre_ascii++){
				?>
				<li><input type="submit" name="letter"
					value="<?php echo chr($lettre_ascii);?>"
						






					<?php if($_SESSION['nb_tours_restants']==0||in_array(chr($lettre_ascii),$_SESSION['lettres_saisies'])){?>
					disabled="disabled"        <?php }?> /></li>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				<?php }?>
			</ul>
		</form>
	</div>
</div>
