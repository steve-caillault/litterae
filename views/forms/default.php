<form <?php echo $attributes; ?>>
	<?php if($withTitle): ?>
	<h2><?php echo $title; ?></h2>
	<?php endif; ?><!-- 
	--><?php foreach($inputGroupKeys as $inputGroupKey): ?><!--
		--><?php foreach($inputs[$inputGroupKey] as $key => $inputWithLabel): ?>
		<div class="form-input">
			<?php echo $inputWithLabel; ?>
			<?php if(($error = getArray($errors, $key))): ?>
			<p class="error"><?php echo $error; ?></p>
			<?php endif; ?>
		</div><!--
		--><?php endforeach; ?><!--
	--><?php endforeach; ?><div class="form-input form-input-submit"><?php echo $inputs['submit']; ?></div>

	<?php foreach($inputs['hidden'] as $input): ?>
	<?php echo $input; ?>
	<?php endforeach; ?>
	<?php echo $inputs['name']; ?>
</form>