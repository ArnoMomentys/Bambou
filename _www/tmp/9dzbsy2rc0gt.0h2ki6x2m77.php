<?php echo $this->render('header.htm',$this->mime,get_defined_vars()); ?>
	<?php echo $this->render($view,$this->mime,get_defined_vars()); ?>
	<?php if (!preg_match('/\/login\/u/i',$PATTERN)): ?>
	<div class="col-xs-12 col-sm-12 hotline texalicen well">
		<?php echo $hotline[$LANGUAGE]; ?> <a href="maito:bambou@momentys.fr"><?php echo $mail[$LANGUAGE]; ?></a>
	</div>
	<?php endif; ?>
<?php echo $this->render('footer.htm',$this->mime,get_defined_vars()); ?>
