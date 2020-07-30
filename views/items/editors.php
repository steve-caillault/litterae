<div class="search-results editors">
	<?php if($totalItems == 0): ?>
	<p class="no-item"><?php echo $noItemSentence; ?></p>
	<?php else: ?>
	<ul class="items editors">
		<?php foreach($items as $item): ?>
		<li class="item editor">
			<?php echo $item['name']; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php echo $pagination; ?>
	<?php endif; ?>
</div>