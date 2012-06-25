<?php
	if(isset($_POST['login'])&&isset($_POST['pass'])){
		$results_connection = $db->query('SELECT id,nom,prenom FROM hangman_users WHERE login="'.addslashes($_POST['login']).'" AND pass="'.md5($_POST['pass']).'"');
				
		/**
		 * Si la requête ne retourne qu'1 résultat,
		 * on connecte l'utilisateur en enregistrant
		 * son login et mot de passe dans des variables de session
		 */
		if(mysql_num_rows($results_connection)==1)
			while($user=mysql_fetch_assoc($results_connection)){
				$_SESSION['user']['id'] = $user['id'];
				$_SESSION['user']['nom'] = $user['nom'];
				$_SESSION['user']['prenom'] = $user['prenom'];
			}
	}
	
	if(isset($_SESSION['user'])){
		// Opérations autorisées
		$actions_authorized = array('add','delete','edit','save','undo','validate','search');
		$action_current = null;
		
		// Pagination
		$nb_results_per_page = 10;
		// Initialisation de la pagination
		if(!isset($_SESSION['page']))
			$_SESSION['page'] = 1;
		// Si l'utilisateur souhaite afficher une page, on vérifie que la valeur transmise dans l'URL est un nombre
		elseif(isset($_GET['page'])&&preg_match('`^[[:digit:]]*$`',$_GET['page']))
			$_SESSION['page'] = $_GET['page'];
		
		// Tri
		$sort_type = array('ASC','DESC');
		// Initialisation du tri
		if(!isset($_SESSION['sort']))
			$_SESSION['sort'] = array('column'=>'date_validation','type'=>$sort_type[0]);
		// Si l'utilisateur souhaite trier une colonne, on vérifie que la valeur transmise dans l'URL est une des valeurs autorisées
		elseif(isset($_GET['sort'])&&preg_match('`^(date_validation|fr|en)$`', $_GET['sort'])){
			$_SESSION['page'] = 1;	// Le tri affiche les résultats à partir de la première page
			
			// Si la colonne sur laquelle le tri est demandé est la dernière a avoir été triée,
			// on inverse le sens de tri
			if($_SESSION['sort']['column']==$_GET['sort'])
				$_SESSION['sort']['type'] = array_shift(array_diff($sort_type, array($_SESSION['sort']['type'])));
			// Sinon on défini le nouveau tri
			else{
				$_SESSION['sort']['column'] = $_GET['sort'];
				$_SESSION['sort']['type'] = $sort_type[0];
			}
		}
		
		// Initialisation de la recherche
		if(!isset($_SESSION['search']))
			$_SESSION['search'] = array('en'=>'','fr'=>'');
		
		if(!empty($_GET)){
			// Si l'utilisateur a demandé à effectuer une opération,
			// on vérifie qu'elle fait bien partie de celles qui sont autorisées
			$action_current = array_shift(array_intersect($actions_authorized,array_keys($_GET)));
			
			switch($action_current){
				case 'add':
					if(!empty($_GET['fr-new'])&&!empty($_GET['en-new']))
						$db->query('INSERT INTO hangman_words (fr,en,date_proposition,date_validation)
										VALUES (
											"'.mysql_real_escape_string($_GET['fr-new']).'",
											"'.mysql_real_escape_string($_GET['en-new']).'",
											NOW(),
											NOW()
										)');
					break;
				case 'delete':
					$db->query('DELETE FROM hangman_words
									WHERE
										id="'.mysql_real_escape_string($_GET[$action_current]).'"');
					break;
				case 'save':
					if(!empty($_GET['fr-edit'])&&!empty($_GET['en-edit']))
						$db->query('UPDATE hangman_words
										SET
											fr="'.mysql_real_escape_string($_GET['fr-edit']).'",
											en="'.$_GET['en-edit'].'"
										WHERE
											id="'.mysql_real_escape_string($_GET[$action_current]).'"');
					break;
				case 'search':
					if(isset($_GET['fr-new'])&&isset($_GET['en-new'])){
						$_SESSION['search']['en'] = addslashes($_GET['en-new']);
						$_SESSION['search']['fr'] = addslashes($_GET['fr-new']);
					}
					break;
				case 'undo':
					$db->query('UPDATE hangman_words
									SET
										date_validation=NULL
									WHERE
										id="'.mysql_real_escape_string($_GET[$action_current]).'"');
					break;
				case 'validate':
					$db->query('UPDATE hangman_words
									SET
										date_validation="'.date('Y-m-d h:i:s',time()).'"
									WHERE
										id="'.mysql_real_escape_string($_GET[$action_current]).'"');
					break;
				default:
					break;
			}
		}
?>
<form action="?" id="form-admin-words" method="get">
	<table>
		<caption>Gestion des mots</caption>
		<thead>
			<tr>
				<?php
					$id_sorts = array('date_validation'=>'','fr'=>'','en'=>'');
					$id_sorts[$_SESSION['sort']['column']] = ' id="sort-'.strtolower($_SESSION['sort']['type']).'"';
				?>
				<th class="status"><a<?php echo $id_sorts['date_validation'];?> href="?sort=date_validation">Status</a></th>
				<th id="fr"><a<?php echo $id_sorts['fr'];?> href="?sort=fr">Français</a></th>
				<th id="gb"><a<?php echo $id_sorts['en'];?> href="?sort=en">Anglais</a></th>
				<th>Opérations</th>
			</tr>
			<tr>
				<td><img alt="Validé" src="pic/admin/status/status.png"/></td>
				<td><input type="text" name="fr-new" placeholder="Mot en français" value="<?php echo $_SESSION['search']['fr'];?>"/></td>
				<td><input type="text" name="en-new" placeholder="Mot en anglais" value="<?php echo $_SESSION['search']['en'];?>"/></td>
				<td class="operations">
					<button type="submit" name="search"><img alt="Recherche" src="pic/admin/actions/magnifier.png"/></button>
					<button type="submit" name="add"><img alt="Ajouter" src="pic/admin/actions/plus.png"/></button>
				</td>
			</tr>
		</thead>
<?php
			// Création de la requête
			$query_words = 'SELECT id,fr,en,date_validation FROM hangman_words
								WHERE en LIKE "%'.$_SESSION['search']['en'].'%" AND fr LIKE "%'.$_SESSION['search']['fr'].'%"
								ORDER BY '.$_SESSION['sort']['column'].' '.$_SESSION['sort']['type'];
			$nb_words = mysql_num_rows($db->query($query_words));
			$nb_pages = ceil($nb_words/$nb_results_per_page);
			
			if( $nb_words>0 ){
				// Affichage du footer de pagination si les résultats tiennent sur plus d'1 page
				if($nb_pages>1){
					// Redéfinition du numéro de page lorsque l'utilisateur en indique un trop important
					if($_SESSION['page']>$nb_pages) 
						$_SESSION['page'] = $nb_pages;
					
					// Ajout à la requête des $nb_results_per_page mots à afficher
					$query_words.= ' LIMIT '.($nb_results_per_page*($_SESSION['page']-1)).','.$nb_results_per_page;
?>
		<tfoot class="pagination">
			<tr>
				<td colspan="4">
					<p>Page&nbsp;: <?php echo $_SESSION['page'].'/'.$nb_pages;?></p>
					<ul>
						<li><button type="submit" name="page" value="1"<?php if($_SESSION['page']==1){?> disabled="disabled"<?php }?>><img alt="Première page" src="pic/admin/pagination/control-skip-180<?php if($_SESSION['page']==1){?>-disabled<?php }?>.png"/></button></li>
						<li><button type="submit" name="page" value="<?php echo ($_SESSION['page']-1);?>"<?php if($_SESSION['page']==1){?> disabled="disabled"<?php }?>><img alt="Page précédente" src="pic/admin/pagination/control-180<?php if($_SESSION['page']==1){?>-disabled<?php }?>.png"/></button></li>
						<?php for($i=1;$i<=$nb_pages;$i++){?>
						<li><button type="submit" name="page" value="<?php echo $i;?>"<?php if($i==$_SESSION['page']){?> disabled="disabled"<?php }?>><?php echo $i;?></button></li>
						<?php }?>
						<li><button type="submit" name="page" value="<?php echo ($_SESSION['page']+1);?>"<?php if($_SESSION['page']==$nb_pages){?> disabled="disabled"<?php }?>><img alt="Page suivante" src="pic/admin/pagination/control<?php if($_SESSION['page']==$nb_pages){?>-disabled<?php }?>.png"/></button></li>
						<li><button type="submit" name="page" value="<?php echo $nb_pages;?>"<?php if($_SESSION['page']==$nb_pages){?> disabled="disabled"<?php }?>><img alt="Dernière page" src="pic/admin/pagination/control-skip<?php if($_SESSION['page']==$nb_pages){?>-disabled<?php }?>.png"/></button></li>
					</ul>
				</td>
			</tr>
		</tfoot>
		<tbody>
<?php
				}
				
				// Exécution de la requête
				$results_words = $db->query($query_words);
				while($word=mysql_fetch_assoc($results_words)){
					// Détermine si le mot est en mode édition ou non
					$editable = ($action_current=='edit'&&$_GET[$action_current]==$word['id']);
					
					echo '<tr id="word'.$word['id'].'">';
						$pic_alt = "Validé";
						$pic_name = "status";
						if(is_null($word['date_validation'])){
							$pic_alt = "En attente de validation";
							$pic_name = "status-busy";
						}
						echo '<td class="status"><img alt="'.$pic_alt.'" src="pic/admin/status/'.$pic_name.'.png"/></td>';
						
						if($editable){
							echo '<td><input type="text" name="fr-edit" value="'.$word['fr'].'"/></td>';
							echo '<td><input type="text" name="en-edit" value="'.$word['en'].'"/></td>';
						}
						else{
							echo '<td>'.$word['fr'].'</td>';
							echo '<td>'.$word['en'].'</td>';
						}
						
						echo '<td class="operations">';
							if($editable){
								echo '<button type="submit" name="save" value="'.$word['id'].'"><img alt="Enregistrer" src="pic/admin/actions/disk-black.png"/></button>';
								echo '<button type="submit" name="cancel" value="'.$word['id'].'"><img alt="Annuler" src="pic/admin/actions/slash.png"/></button>';
							}
							else{
								if(is_null($word['date_validation']))
									echo '<button type="submit" name="validate" value="'.$word['id'].'"><img alt="Valider" src="pic/admin/actions/tick.png"/></button>';
								else
									echo '<button type="submit" name="undo" value="'.$word['id'].'"><img alt="Dévalider" src="pic/admin/actions/arrow-return-270.png"/></button>';
								echo '<button type="submit" name="edit" value="'.$word['id'].'"><img alt="Modifier" src="pic/admin/actions/pencil.png"/></button>';
								echo '<button type="submit" name="delete" value="'.$word['id'].'"><img alt="Supprimer" src="pic/admin/actions/cross-script.png"/></button>';
							}
						echo '</td>';
					echo '</tr>';
				}
?>
		</tbody>
<?php
			}else{
?>
		<tbody>
			<tr>
				<td colspan="4">Aucun mot n'est disponible</td>
			</tr>
		</tbody>
<?php 
			}
?>
	</table>
</form>
<?php
	}else{	// Aucun utilisateur n'est connecté à la page d'administration
?>
	<fieldset>
		<legend>Connexion à l'administration</legend>
		<form action="?" id="form-admin-connexion" method="post">
			<p>
				<input type="text" name="login" id="login" placeholder="Nom d'utilisateur"/>
				<input type="password" name="pass" id="pass" placeholder="Mot de passe"/>
				<input type="submit" value="Se connecter"/>
			</p>
		</form>
	</fieldset>
<?php
	}
?>