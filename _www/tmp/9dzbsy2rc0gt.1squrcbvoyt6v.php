<form action="<?php echo $BASE.'/event/create'; ?>" method="post" class="form-create" role="form">

    <h2 class="page-heading">
        <?php echo $event_create[$l]; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
    </h2>

    <?php if (isset($SESSION['errors']) && count($SESSION['errors'])>0): ?>
        <?php echo $this->render('globalerrors.htm',$this->mime,get_defined_vars()); ?>
    <?php endif; ?>

    <input name="nom" type="text" class="form-control first" placeholder="<?php echo $event_name[$l]; ?>" required autofocus value="<?php echo $post_has_data?$post_has_data['nom']:''; ?>" required />

    <input name="lieu" type="text" class="form-control middle" placeholder="<?php echo $event_place[$l]; ?>" required value="<?php echo $post_has_data?$post_has_data['lieu']:''; ?>" required />

    <input name="adresse" type="text" class="form-control middle" placeholder="<?php echo $address[$l]; ?>" required value="<?php echo $post_has_data?$post_has_data['adresse']:''; ?>" required />

    <input name="cp" type="text" class="form-control middle" placeholder="<?php echo $zip_code[$l]; ?>" required value="<?php echo $post_has_data?$post_has_data['cp']:''; ?>" />

    <input name="ville" type="text" class="form-control middle" placeholder="<?php echo $town[$l]; ?>" required value="<?php echo $post_has_data?$post_has_data['ville']:''; ?>" />

    <input name="debut" type="text" class="form-control middle" placeholder="<?php echo $date_start[$l]; ?> (<?php echo $date_format[$l]; ?>)" value="<?php echo $post_has_data?$post_has_data['debut']:''; ?>" required />

    <input name="fin" type="text" class="form-control middle" placeholder="<?php echo $date_end[$l]; ?> (<?php echo $date_format[$l]; ?>)" required value="<?php echo $post_has_data?$post_has_data['fin']:''; ?>" required />

    <input name="limitA" type="text" class="form-control middle" placeholder="<?php echo $date_limit_1[$l]; ?> (<?php echo $date_format[$l]; ?>)" required value="<?php echo $post_has_data?$post_has_data['limitA']:''; ?>" />

    <input name="limitB" type="text" class="form-control middle" placeholder="<?php echo $date_limit_2[$l]; ?> (<?php echo $date_format[$l]; ?>)" required value="<?php echo $post_has_data?$post_has_data['limitB']:''; ?>" />

    <input name="deadLine" type="text" class="form-control last" placeholder="<?php echo $dead_line[$l]; ?> (<?php echo $date_format[$l]; ?>)" value="<?php echo $post_has_data?$post_has_data['deadLine']:''; ?>" />

    <input type="hidden" name="create" value="create" />

    <a class="btn btn-default" href="<?php echo $BASE.'/events'; ?>">
        <?php echo $cancel[$l]; ?>
    </a>
    <button class="btn btn-default btn-primary" type="submit">
        <small class="glyphicon glyphicon-plus"></small> <?php echo $event_add[$l]; ?>
    </button>

</form>