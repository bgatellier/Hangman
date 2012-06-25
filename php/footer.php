		</div>
	</div>
	<footer class="wrapper" id="footer">
		<ul>
			<?php
				$li_id = 'user-disconnected';
				$link_title = 'Se connecter';
				if(isset($_SESSION['user'])){
					$li_id = 'user-connected';
					$link_title = 'Vous êtes connecté en tant que '.$_SESSION['user']['prenom'].' '.$_SESSION['user']['nom'];
				}
			?>
			<li><a<?php if($_SESSION['content']=='admin')echo ' class="selected"'?> href="?content=admin" id="<?php echo $li_id;?>" title="<?php echo $link_title;?>">Administration</a></li>
			<?php if(isset($_SESSION['user'])){?>
			<li><a href="?disconnect">Se déconnecter</a></li>
			<?php }?>
		</ul>
	</footer>
</body>
</html>