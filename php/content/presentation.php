<h3>Installation</h3>
<p>Afin de pouvoir faire fonctionner le jeu avec une base minimale de mots, il faut importer le fichier <em>hangman.sql</em> dans une BDD nommée <em>hangman</em>.</p>
<p>La configuration de l'accès au serveur mysql s'effectue dans le fichier <em>php/config/db.php</em>. Les identifiants sont initialement <em>root</em> sans mot de passe.</p>
<h3>Administration</h3>
<p>Il est possible depuis l'interface d'administration des mots, de&nbsp;:</p>
<ul>
	<li>Parcourir l'ensemble des mots par tranche de 10</li>
	<li>Ajouter, modifier ou supprimer un nouveau mot (attention, lors de la suppression d'un mot, il ne sera pas demandé de confirmation)</li>
	<li>Valider ou dévalider un mot</li>
	<li>Rechercher un mot (en français, en anglais, ou les deux)</li>
	<li>Trier les mots en cliquant sur les entêtes de colonne (Status, Français, Anglais)</li>
</ul>
<h3>Jeu</h3>
<p>Le jeu fonctionne à l'aide de la technologie <em>Ajax</em>, bien qu'il soit également accessible sans <em>Javascript</em>.</p>
<p>Remarques&nbsp;:</p>
<ul>
	<li>La fin de partie n'est pas particulièrement signalée (autrement que part la découverte de l'ensemble des lettres du mot)</li>
	<li>Il est possible de continuer l'affichage du pendu bien que toutes les lettres aient été trouvées.</li>
</ul>
<p>Bug&nbsp;:</p>
<ul>
	<li>Lorsque l'utilisateur active le mode triche (argument <em>cheat</em> de l'URL) lors d'une partie en cours, et si le Javascript est activé, la solution donnée ne correspond pas forcément à celle affichée, car ce mode est géré en PHP&nbsp;; Il n'a pas été possible de capturer en javascript la validation d'une URL.</li>
</ul>
<h3>Compatibilité</h3>
<p>Le jeu a été testé et est complètement fonctionnel sur les navigateurs suivants&nbsp;:</p>
<ul>
	<li>Firefox, versions 3.6, 4</li>
	<li>Chrome, version 10</li>
	<li>Opera, version 11.10</li>
	<li>Internet Explorer, version 9</li>
	<li>Safari, version 5</li>
</ul>
<h3>Images</h3>
<p>Les icônes utilisées sont issues du travail de <a href="http://www.famfamfam.com/">Mark James</a> et <a href="http://p.yusukekamiyamane.com/">Yusuke Kamiyamane</a>.</p>