<?php
	$message = 'Veuillez saisir un mot';
	$message_id = 'information';
	
	// L'utilisateur a saisie du contenu dans les deux champs
	if (!empty($_POST['proposition-en'])&&!empty($_POST['proposition-fr'])){
		$results = $db->query('SELECT id FROM hangman_words
									WHERE
										en="'.mysql_real_escape_string($_POST['proposition-en']).'" and
										fr="'.mysql_real_escape_string($_POST['proposition-fr']).'"');
		
		if(mysql_num_rows($results)==0)	// Si le mot n'existe pas déjà
			$db->query('INSERT INTO hangman_words (en,fr)
							VALUES(
								"'.mysql_real_escape_string($_POST['proposition-en']).'",
								"'.mysql_real_escape_string($_POST['proposition-fr']).'"
							)');
		
		$message = 'Votre mot a bien été enregistré.';
		$message_id = 'success';
	}
?>
<fieldset>
	<legend>Proposer un nouveau mot</legend>
	<div id="proposition-description">
		<p>Vous souhaitez insérer un nouveau mot dans le jeu ? Rien de plus simple !</p>
		<p>Il suffit pour cela d'entrer votre mot ainsi que sa traduction anglaise dans le formulaire de saisie.</p>
		<p>Votre mot sera soumis à notre équipe afin d'être validé, et ainsi figurer parmi l'ensemble des mots proposés.</p>
	</div>
	<form action="?" id="proposition-form" method="post">
		<p class="message" id="<?php echo $message_id;?>"><?php echo $message;?></p>
		<p>
			<input name="proposition-fr" type="text" placeholder="Mot en français"/>
			<input name="proposition-en" type="text" placeholder="Mot en anglais"/>
			<input value="Proposer" type="submit"/>
		</p>
	</form>
</fieldset>