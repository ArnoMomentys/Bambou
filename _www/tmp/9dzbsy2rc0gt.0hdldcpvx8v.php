<form action="<?php echo $BASE.'/group/create'; ?>" method="post" class="form-create" role="form">

    <h2 class="form-create-heading"><?php echo $create_new_group[$l]; ?></h2>

    <br>

    <?php if (isset($SESSION['errors']) && count($SESSION['errors'])>0): ?>
        <?php echo $this->render('globalerrors.htm',$this->mime,get_defined_vars()); ?>
    <?php endif; ?>

    <input name="name" type="text" class="form-control" placeholder="<?php echo $group_name[$l]; ?>" required autofocus value="<?php echo $post_has_data?$post_has_data['name']:''; ?>" />

    <input type="hidden" name="create" value="create" />

    <a class="btn btn-default" href="<?php echo $BASE.'/groups'; ?>">
        <?php echo $cancel[$l]; ?>
    </a>
    <button class="btn btn-primary" type="submit">
        <small class="glyphicon glyphicon-plus"></small> <?php echo $group_add[$l]; ?>
    </button>

</form>