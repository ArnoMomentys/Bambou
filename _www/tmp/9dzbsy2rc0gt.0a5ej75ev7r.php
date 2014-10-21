<form action="<?php echo $BASE.'/group/update'; ?>" method="post" class="form-update" role="form">

    <h2 class="form-update-heading"><?php echo $group_update[$l]; ?></h2>

    <br>

    <input name="name" type="text" class="form-control" placeholder="<?php echo $group_name[$l]; ?>" required value="<?php echo $group['name']; ?>" />

    <input type="hidden" name="gid" value="<?php echo $group['gid']; ?>" />
    <input type="hidden" name="update" value="update" />

    <a class="btn btn-default" href="<?php echo $BASE.'/groups'; ?>">
    	<?php echo $cancel[$l]; ?>
    </a>
    <button class="btn btn-primary" type="submit">
        <span class="glyphicon glyphicon-ok"></span> <?php echo $group_update[$l]; ?>
    </button>
    
</form>