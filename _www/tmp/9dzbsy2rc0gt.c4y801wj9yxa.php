<form action="<?php echo $BASE.'/user/update/'.$ref; ?>" method="post" class="form-complete-profile" role="form">

    <h2 class="form-update-heading"><?php echo $user_profile_complete[$l]; ?></h2>

    <br>

    <?php if (isset($SESSION['errors']) && count($SESSION['errors'])>0): ?>
        <?php echo $this->render('globalerrors.htm',$this->mime,get_defined_vars()); ?>
    <?php endif; ?>

    <div class="input-group">
        <span class="input-group-addon short first hidden-md hidden-lg"><?php echo $gender[$l]; ?></span>
        <span class="input-group-addon first hidden-xs hidden-sm"><?php echo $gender[$l]; ?></span>
		<input name="civilite" type="text" class="form-control first" placeholder="Civilité" value="<?php echo strtolower($profile['civilite']); ?>" required>
	</div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $name[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $name[$l]; ?></span>
		<input name="nom" type="text" class="form-control middle" value="<?php echo $profile['nom']; ?>" required>
	</div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $firstname[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $firstname[$l]; ?></span>
		<input name="prenom" type="text" class="form-control middle" value="<?php echo $profile['prenom']; ?>" required>
	</div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $function[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $function[$l]; ?></span>
    	<input name="fonction" type="text" class="form-control middle" value="<?php echo $job['fonction']; ?>" >
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $company[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $company[$l]; ?></span>
        <input name="societe" type="text" class="form-control middle" value="<?php echo $job['societe']; ?>" >
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $branch[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $branch[$l]; ?></span>
        <input name="branche" type="text" class="form-control middle" value="<?php echo $job['branche']; ?>" >
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $address[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $address[$l]; ?></span>
        <input name="adresse" type="text" class="form-control middle" value="<?php echo $job['adresse']; ?>" >
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $zip_code[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $zip_code[$l]; ?></span>
        <input name="cp" type="text" class="form-control middle" value="<?php echo $job['cp']; ?>" />
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $town[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $town[$l]; ?></span>
        <input name="ville" type="text" class="form-control middle" value="<?php echo $job['ville']; ?>" />
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $country[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $country[$l]; ?></span>
        <input name="pays" type="text" class="form-control middle" value="<?php echo $job['pays']; ?>" />
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $user_phone[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $user_phone[$l]; ?></span>
		<input name="fixe" type="text" class="form-control middle" value="<?php echo $job['fixe']; ?>" />
	</div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $mobile_phone[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $mobile_phone[$l]; ?></span>
        <input name="portable" type="text" class="form-control middle" value="<?php echo $job['portable']; ?>" />
    </div>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $user_mail[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $user_mail[$l]; ?></span>
        <input name="email" type="email" class="form-control middle" value="<?php echo $account['email']; ?>" required>
    </div>

    <?php if ($SESSION['lvl']==1 || ($SESSION['uid']==$account['uid'] && $SESSION['lvl']<=3)): ?>
    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $user_pass[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $user_pass[$l]; ?></span>
        <input name="password" type="password" pattern=".{8,}" class="has-tip-up form-control<?php echo $SESSION['uid']==$account['uid'] && $SESSION['lvl']>1?' end':' middle'; ?>" value="<?php echo Encrypt::load()->invert($account['password']); ?>" title="<?php echo $minimum_pass[$l]; ?>" required>
    </div>
    <?php endif; ?>

    <?php if ($SESSION['lvl']==1 && $SESSION['uid']!=$account['uid']): ?>
    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $level[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $level[$l]; ?></span>
        <input name="level" type="number" min="<?php echo ($SESSION['lvl']>=3 ? 4 : ($SESSION['c']==0? '' : 3)); ?>" max="5" class="form-control middle" value="<?php echo $account['level']; ?>" required>
    </div>
    <?php endif; ?>

    <div class="input-group">
        <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $c_lastname[$l]; ?></span>
        <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $contact_lastname[$l]; ?></span>
        <input name="interlocuteurNom" type="text" class="form-control middle" value="<?php echo $job['interlocuteurNom']; ?>" >
    </div>

    <div class="input-group">
        <span class="input-group-addon short <?php echo $SESSION['uid']==$account['uid'] && $SESSION['lvl']>1?' end':' middle'; ?> hidden-md hidden-lg"><?php echo $c_firstname[$l]; ?></span>
        <span class="input-group-addon <?php echo $SESSION['uid']==$account['uid'] && $SESSION['lvl']>1?' end':' middle'; ?> hidden-xs hidden-sm"><?php echo $contact_firstname[$l]; ?></span>
        <input name="interlocuteurPrenom" type="text" class="form-control <?php echo $SESSION['uid']==$account['uid'] && $SESSION['lvl']>1?' end':' middle'; ?>" value="<?php echo $job['interlocuteurPrenom']; ?>" >
    </div>

    <?php if ($SESSION['lvl']==1): ?>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $short_siret[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_siret[$l]; ?></span>
            <input name="b_siret" type="text" class="form-control middle" value="<?php echo $billing['siret']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $tva[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $tva_intracomm[$l]; ?></span>
            <input name="b_tva" type="text" class="form-control middle" value="<?php echo $billing['numTva']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $organisation[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_organisation[$l]; ?></span>
            <input name="b_organisme" type="text" class="form-control middle" value="<?php echo $billing['organisme']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $b_address[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_address[$l]; ?></span>
            <input name="b_adresse" type="text" class="form-control middle" value="<?php echo $billing['adresse']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $b_postal_code[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_postal_code[$l]; ?></span>
            <input name="b_cp" type="text" class="form-control middle" value="<?php echo $billing['cp']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $b_town[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_town[$l]; ?></span>
            <input name="b_ville" type="text" class="form-control middle" value="<?php echo $billing['ville']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $b_country[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_country[$l]; ?></span>
            <input name="b_pays" type="text" class="form-control middle" value="<?php echo $billing['pays']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $b_imputation[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_imputation[$l]; ?></span>
            <input name="b_imputation" type="text" class="form-control middle" value="<?php echo $billing['imputation']; ?>" >
        </div>

        <div class="input-group">
            <span class="input-group-addon short middle hidden-md hidden-lg"><?php echo $b_smart[$l]; ?></span>
            <span class="input-group-addon middle hidden-xs hidden-sm"><?php echo $billing_smart[$l]; ?></span>
            <input name="b_smart" type="text" class="form-control end" value="<?php echo $billing['smart']; ?>" >
        </div>

    <?php endif; ?>

    <?php if (strlen($ref)>0): ?>
        
           <i class="disnon" with="<?php $url=$BASE.'/'.implode('/',explode('_',$ref)) ?>"></i>
        
        <?php else: ?>
            <i class="disnon" with="<?php $url=$BASE.'/events' ?>"></i>
        
    <?php endif; ?>

    <input type="hidden" name="uid" value="<?php echo $account['uid']; ?>" />
    <input type="hidden" name="complete-profile" value="complete-profile" />

    <a class="btn btn-default" href="<?php echo $url; ?>">
        <?php echo $cancel[$l]; ?>
    </a>
    <button class="btn btn-primary" type="submit">
        <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;<?php echo $validate[$l]; ?>
    </button>

    <br>
    <br>

</form>