<?php 
	use App\HTML\Menu\MenuHTML as Menu; 
?>

<ul class="<?php echo $type; ?>-menu"><!--
	--><?php foreach($items[Menu::TYPE_PRIMARY] as $key_primary => $item_primary): ?><!--
	--><li class="<?php echo $item_primary['item']['class']; ?>"><!--
		--><?php echo $item_primary['item']['anchor']; ?><!--
		--><?php if($items_secondary = getArray($item_primary, Menu::TYPE_SECONDARY)): ?><!--
		--><ul class="<?php echo Menu::TYPE_SECONDARY; ?>"><!--
			--><?php foreach($items_secondary as $key_secondary => $item_secondary): ?><!--
			--><li class="<?php echo $item_secondary['item']['class']; ?>"><!--
				--><?php echo $item_secondary['item']['anchor']; ?><!--
				--><?php if($items_tertiary = getArray($item_secondary, Menu::TYPE_TERTIARY)): ?><!--
				--><ul class="<?php echo Menu::TYPE_TERTIARY; ?>"><!--
					--><?php foreach($items_tertiary as $key_tertiary => $item_tertiary): ?><!--
					--><li class="<?php echo $item_tertiary['item']['class']; ?>"><?php echo $item_tertiary['item']['anchor']; ?></li><!--
					--><?php endforeach; ?><!--
				--></ul><!--
				--><?php endif; ?><!--
			--></li><!--
			--><?php endforeach; ?><!--
		--></ul><!--
		--><?php endif; ?><!--
	--></li><!--
	--><?php endforeach; ?><!--
--></ul>