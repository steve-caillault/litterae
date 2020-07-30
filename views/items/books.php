<div class="search-results books">
	<?php if($totalItems == 0): ?>
	<p class="no-item"><?php echo $noItemSentence; ?></p>
	<?php else: ?>
	<ul class="items books">
		<?php foreach($items as $item): ?>
		<li <?php echo $item['attributes']; ?>>
			<?php if(count($item['images']) > 0): ?>
			<div class="slideshow"><!--
				--><?php foreach($item['images'] as $image): ?><!--
				--><?php echo $image; ?><!--
				--><?php endforeach; ?><!--
			--></div>
			<?php endif; ?>
			<div class="content">
				<h2><?php echo $item['title']?></h2>
				<p class="publisher">
					<span class="tag"><?php echo $item['editor']; ?></span>
					<?php if($item['collection'] !== NULL): ?>
					<span class="tag"><?php echo $item['collection']; ?></span>
					<?php endif; ?>
				</p>
				<?php foreach($item['contributors'] as $key => $dataContributors): ?>
				<h3><?php echo $dataContributors['title']; ?></h3>
				<ul class="contributors <?php echo $key; ?>">
					<?php foreach($dataContributors['items'] as $contributor): ?>
					<li><?php echo $contributor; ?></li>
					<?php endforeach; ?>
				</ul>
				<?php endforeach; ?>
				<ul class="book-lists"><!--
					--><?php foreach($item['lists'] as $bookList): ?><!--
					--><li><?php echo $bookList; ?></li><!--
					--><?php endforeach; ?><!--
				--></ul>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php echo $pagination; ?>
	<?php endif; ?>
</div>