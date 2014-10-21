<ul class="results_pager">

	<?php if ($totaux != 0): ?>
		<li class="total">
			<small>
				<?php echo $totaux; ?> <?php echo $listname; ?>
			</small>
		</li>
	<?php endif; ?>

	<?php if (preg_match('/(add|show\/guest|add|show\/user|host|users)/i', $PATTERN) && $totaux > 30): ?>
		<li class="search">
			<?php echo $this->render('search.htm',$this->mime,get_defined_vars()); ?>
		</li>
	<?php endif; ?>

	<?php if ($totaux > 30): ?>

		<?php if ($pg['firstPage']): ?>
			<li class="firstpage">
				<a href="<?php echo $BASE, $pg['route'], $pg['prefix'].$pg['firstPage']; ?>">
					<i class="glyphicon glyphicon glyphicon-fast-backward"></i>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($pg['prevPage']): ?>
			<li class="prevpage">
				<a href="<?php echo $BASE, $pg['route'], $pg['prefix'].$pg['prevPage']; ?>"><i class="glyphicon glyphicon glyphicon-step-backward"></i></a>
			</li>
		<?php endif; ?>

		<?php foreach (($pg['rangePages']?:array()) as $key=>$page): ?>
			<li class="page_<?php echo $key; ?><?php echo $page == $pg['currentPage'] ? ' active':''; ?>">
				<a href="<?php echo $BASE, $pg['route'], $pg['prefix'].$page; ?>"><?php echo $page; ?></a>
			</li>
		<?php endforeach; ?>

		<?php if ($pg['nextPage']): ?>
			<li>
				<a href="<?php echo $BASE, $pg['route'], $pg['prefix'].$pg['nextPage']; ?>">
					<i class="glyphicon glyphicon glyphicon-step-forward"></i>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($pg['lastPage']): ?>
			<li>
				<a href="<?php echo $BASE, $pg['route'], $pg['prefix'].$pg['lastPage']; ?>">
				<i class="glyphicon glyphicon glyphicon-fast-forward"></i></a>
			</li>
		<?php endif; ?>

	<?php endif; ?>

</ul>