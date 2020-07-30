<header>
	<?php echo $homeAnchor; ?>
	
	<?php if($userMenu = $menus['user']): ?>
	<nav class="menu menu-user">
		<a class="deploy" href="#">Menus</a>
		<div class="content">
			<?php echo $userMenu; ?>
		</div>
	</nav>
	<?php endif; ?>
	
	<?php if($mainMenu = $menus['main']): ?>
	<nav class="menu menu-main"><?php echo $mainMenu; ?></nav>
	<?php endif; ?>
</header>