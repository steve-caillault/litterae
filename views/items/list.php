<div <?php echo $collectionAttributes; ?>>
	<?php if($totalItems == 0): ?>
		<?php if($noItemSentence !== NULL): ?>
	<p class="no-item"><?php echo $noItemSentence; ?></p>
		<?php endif; ?>
	<?php else: ?>
	<table class="without-header">
		<?php foreach($items as $item): ?>
		<tr <?php echo $item['attributes']; ?>>
			<?php foreach($fields as $field): ?>
			<td <?php echo $fieldsAttributes[$field]; ?>><?php echo $item[$field]; ?></td> 
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php echo $pagination; ?>
	<?php endif;?>
</div>