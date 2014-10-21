<form action="<?php echo $BASE.'/event/'.$event['eid'].'/add/new/host'; ?>" method="post" class="form-complete-profile" role="form">

    <h2 class="page-heading marbotnon">
        <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>">
            <span class="textracap"><?php echo $eventname[$l]; ?></span> : <?php echo $event['nom']; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
        </a>
    </h2>
    <small class="dimgra"><span class="textraupp"><?php echo $starting_at[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?></small>
    <br>
    <small class="dimgra"><span class="textraupp"><?php echo $limit_displayed[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['limitB'])); ?></small><br>

    <?php if (isset($SESSION['errors']) && count($SESSION['errors'])>0): ?>
        <br>
        <?php echo $this->render('globalerrors.htm',$this->mime,get_defined_vars()); ?>
    <?php endif; ?>

    <h3 class="pull-left disblo widful page-subheading padtop20 marbotnon">
        <?php echo $event_host_add_new[$l]; ?><br>
        <small><?php echo $all_user_host_fields_required[$l]; ?></small>
    </h3>

    <fieldset class="first padlefnon padrignon">

        <legend><?php echo $host_infos[$l]; ?></legend>
        <small class="col-xs-12 padlefnon"><?php echo $host_infos_required_mark[$l]; ?></small>
        <br>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 infos-block infos-block-left">
            <div class="input-group">
            	<span class="input-group-addon first"><?php echo $gender[$l]; ?> <span class="required">*</span></span>
        		<input name="civilite" type="text" class="form-control first" placeholder="<?php echo $gender[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['civilite']?$post_has_data['civilite']:''); ?>" required />
        	</div>
            <div class="input-group">
            	<span class="input-group-addon middle"><?php echo $name[$l]; ?> <span class="required">*</span></span>
        		<input name="nom" type="text" class="form-control middle" placeholder="<?php echo $name[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['nom']?$post_has_data['nom']:''); ?>" required />
        	</div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $firstname[$l]; ?> <span class="required">*</span></span>
                <input name="prenom" type="text" class="form-control middle" placeholder="<?php echo $firstname[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['prenom']?$post_has_data['prenom']:''); ?>" required />
            </div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $address[$l]; ?></span>
                <input name="adresse" type="text" class="form-control middle" placeholder="<?php echo $address[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['adresse']?$post_has_data['adresse']:''); ?>" />
            </div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $town[$l]; ?></span>
                <input name="ville" type="text" class="form-control middle" placeholder="<?php echo $town[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['ville']?$post_has_data['ville']:''); ?>" />
            </div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $zip_code[$l]; ?></span>
                <input name="cp" type="text" class="form-control middle" placeholder="<?php echo $zip_code[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['cp']?$post_has_data['cp']:''); ?>" />
            </div>
            <div class="input-group">
            	<span class="input-group-addon end"><?php echo $country[$l]; ?></span>
        		<input name="pays" type="text" class="form-control endrig" placeholder="<?php echo $country[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['pays']?$post_has_data['pays']:''); ?>" />
        	</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 infos-block infos-block-right">
            <div class="input-group">
                <span class="input-group-addon first"><?php echo $user_mail[$l]; ?> <span class="required">*</span></span>
                <input name="email" type="email" class="form-control first" placeholder="<?php echo $user_mail[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['email']?$post_has_data['email']:''); ?>" required />
            </div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $mobile_phone[$l]; ?></span>
                <input name="portable" type="number" pattern=".{10}" class="form-control middle" placeholder="<?php echo $mobile_phone_complete[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['portable']?$post_has_data['portable']:''); ?>">
            </div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $user_phone[$l]; ?></span>
                <input name="fixe" type="number" pattern=".{10}" class="form-control middle" placeholder="<?php echo $user_phone_complete[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['fixe']?$post_has_data['fixe']:''); ?>" />
            </div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $function[$l]; ?></span>
                <input name="fonction" type="text" class="form-control middle" placeholder="<?php echo $function[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['fonction']?$post_has_data['fonction']:''); ?>" />
            </div>
            <div class="input-group">
                <span class="input-group-addon middle"><?php echo $branch[$l]; ?></span>
                <input name="branche" type="text" class="form-control middle" placeholder="<?php echo $branch[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['branche']?$post_has_data['branche']:''); ?>" />
            </div>
            <div class="input-group">
            	<span class="input-group-addon middle">BU</span>
            	<input name="BU" type="text" class="form-control middle" placeholder="BU" value="<?php echo ($post_has_data && $post_has_data['BU']?$post_has_data['BU']:''); ?>" />
            </div>
            <div class="input-group">
            	<span class="input-group-addon end"><?php echo $company[$l]; ?> <span class="required">*</span></span>
        		<input name="societe" type="text" class="form-control endrig" placeholder="<?php echo $company[$l]; ?>" value="<?php echo ($post_has_data && $post_has_data['societe']?$post_has_data['societe']:''); ?>" required />
        	</div>
        </div>

    </fieldset>

    <br>

    <input type="hidden" name="reqprof">

    <div class="pull-left widful">
        <button class="btn btn-primary btn-block" type="submit">
            <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;<?php echo $save_host[$l]; ?>
        </button>
        <br>
        <br>
    </div>

</form>