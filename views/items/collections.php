<div class="search-results collections">
	<?php if($totalItems == 0): ?>
	<p class="no-item"><?php echo $noItemSentence; ?></p>
	<?php else: ?>
	<ul class="items collections">
		<?php foreach($items as $item): ?>
		<li class="item collection">
			<?php echo $item['name']; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php echo $pagination; ?>
	<?php endif; ?>
</div>