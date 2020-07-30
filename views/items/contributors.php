<div class="search-results persons contributors">
	<?php if($totalItems == 0): ?>
	<p class="no-item"><?php echo $noItemSentence; ?></p>
	<?php else: ?>
	<ul class="items persons contributors">
		<?php foreach($items as $item): ?>
		<li class="item person contributor"><!--
			--><?php echo $item['name']; ?><!--
			--><?php echo $item['followed']; ?><!--
		--></li>
		<?php endforeach; ?>
	</ul>
	<?php echo $pagination; ?>
	<?php endif; ?>
</div>