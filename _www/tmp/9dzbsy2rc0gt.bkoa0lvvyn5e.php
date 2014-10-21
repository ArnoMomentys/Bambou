<h2 class="page-heading">
    <?php echo $page_header; ?>
</h2>

<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    
        <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
    
<?php endif; ?>

<div class="col-sm-12 profile_infos">
    <div class="group col-sm-6 padlefnon">
        <h1 class="martopnon">
            <small><?php echo ucfirst($user['civilite']); ?></small><br><?php echo strtoupper($user['nom']); ?><br><?php echo ucfirst($user['prenom']); ?>
            <?php if (isset($SESSION['switch']) && $SESSION['switch']===true && $SESSION['uid'] != $user['uid'] && $user['level'] <= 3 && $user['level'] > 1): ?>
                <span class="btn btn-link curpoi has-tip-up" data-toggle="modal" data-target="#myModal-switch-<?php echo $user['uid']; ?>" title="<?php echo $connect_as[$l]; ?> <?php echo strtoupper($user['nom']); ?> <?php echo ucfirst($user['prenom']); ?>">
                    <i class="glyphicon glyphicon-transfer"></i>
                </span>
            <?php endif; ?>
        </h1>
    </div>
    <div class="col-sm-6">
        <div class="group">
            <small><?php echo ucfirst($function[$l]); ?> :</small>
            <b><?php echo $user['fonction']; ?></b>
        </div>
        <div class="group">
            <small><?php echo ucfirst($company[$l]); ?> :</small>
            <b><?php echo $user['societe']; ?></b>
        </div>
        <div class="group">
            <small><?php echo ucfirst($branch[$l]); ?> :</small>
            <b><?php echo $user['branche']; ?></b>
        </div>
        <?php if (!preg_match('/nielsy/i', $user['email'])): ?>
            <div class="group">
                <small><?php echo ucfirst($mail[$l]); ?> :</small>
                <b><?php echo $user['email']; ?></b>
            </div>
       <?php endif; ?>
    </div>
</div>

<div class="col-sm-6 col-md-6 col-lg-6 profile_details">

    <?php if ($SESSION['lvl']<=2): ?>
    <div class="group">
        <h4 class="textraupp"><?php echo ucfirst($access_conditions[$l]); ?></h4>
        <small><?php echo ucfirst($grant_type[$l]); ?> :</small>
        <b><?php echo $access[$user['level']][$l]; ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($can_host[$l]); ?> :</small>
        <b><?php echo $user['level']<=3?$yes[$l]:$no[$l]; ?></b>
    </div>
    <br>
    <?php endif; ?>

    <div class="group">
        <h4 class="textraupp"><?php echo $contact_details[$l]; ?></h4>
        <small><?php echo ucfirst($address[$l]); ?> :</small>
        <b><?php echo $user['adresse']; ?></b>
        <b><?php echo $user['cp']; ?> <?php echo strtoupper($user['ville']); ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($department[$l]); ?> :</small>
        <b><?php echo $user['dept']; ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($region[$l]); ?> :</small>
        <b><?php echo $user['region']; ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($country[$l]); ?> :</small>
        <b><?php echo $user['pays']; ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($phone[$l]); ?> :</small>
        <b><?php echo $user['prefixeFixe']?'(+'.$user['prefixeFixe'].') ' :''; ?><?php echo $user['fixe']; ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($mobile_phone[$l]); ?> :</small>
        <b><?php echo $user['prefixePortable']?'(+'.$user['prefixePortable'].') ' :''; ?><?php echo $user['portable']; ?></b>
    </div>
    <br>

    <?php if ($SESSION['lvl']<=2): ?>
    <div class="group">
        <h4 class="textraupp"><?php echo $group_list[$l]; ?></h4>
        <?php $ctr=0; foreach (($groups['fields']?:array()) as $key=>$group): $ctr++; ?>
            <b><?php echo $key=='name'?$group['value']:''; ?></b>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<div class="col-sm-6 col-md-6 col-lg-6 profile_details">

    <div class="group">
        <h4 class="textraupp"><?php echo $account_history[$l]; ?></h4>
        <small><?php echo ucfirst($created_at[$l]); ?> :</small>
        <b><?php echo strftime("%A %d %B %G", strtotime($user['createdAt'])); ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($account_creator[$l]); ?> :</small>
        <a href="<?php echo $BASE.'/user/'.$creator['uid'].'/show'; ?>"><b class="textracap"><?php echo $creator['nom']; ?> <?php echo $creator['prenom']; ?></b></a>
    </div>

    <div class="group">
        <small><?php echo ucfirst($updated_at[$l]); ?> :</small>
        <b><?php echo strftime("%A %d %B %G", strtotime($user['updatedAt'])); ?></b>
    </div>

    <?php if (!empty($updator)): ?>
    <div class="group">
        <small><?php echo ucfirst($updated_by[$l]); ?> :</small>
        <a href="<?php echo $BASE.'/user/'.$user['updatedBy'].'/show'; ?>"><b class="textracap"><?php echo strtoupper($updator['nom']); ?> <?php echo $updator['prenom']; ?></b></a>
    </div>
    <?php endif; ?>

    <br>

    <div class="group">
        <h4 class="textraupp"><?php echo $billing_infos[$l]; ?></h4>
        <small><?php echo ucfirst($billing_contact[$l]); ?> :</small>
        <b><?php echo strtoupper($user['interlocuteurNom']); ?> <?php echo $user['interlocuteurPrenom']; ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($organisation[$l]); ?> :</small>
        <b><?php echo $user['organisme']; ?></b>
    </div>
    <div class="group">
        <small><?php echo ucfirst($address[$l]); ?> :</small>
        <b><?php echo $user['adresseF']; ?></b> <b><?php echo $user['cpF']; ?> <?php echo strtoupper($user['villeF']); ?></b>
    </div>
    <div class="group">
        <small><?php echo $siret[$l]; ?> :</small>
        <b><?php echo $user['siret']; ?></b>
    </div>
    <div class="group">
        <small><?php echo strtoupper($tva_intracomm[$l]); ?> :</small>
        <b><?php echo $user['numTva']; ?></b>
    </div>
    <div class="group">
        <small><?php echo strtoupper($imputation[$l]); ?> :</small>
        <b><?php echo $user['imputation']; ?></b>
    </div>
    <div class="group">
        <small><?php echo strtoupper($billing_smart[$l]); ?> :</small>
        <b><?php echo $user['smart']; ?></b>
    </div>
</div>

<?php if ($SESSION['lvl'] == 1 || ($SESSION['uid']==$user['uid'] && $SESSION['lvl']<=3)): ?>
    <a href="<?php echo $BASE.'/user/'.$user['uid'].'/update/user_'.$user['uid'].'_show'; ?>" class="btn btn-success widful martop30 marbot30">
        <?php echo $user_update_profile[$l]; ?>
    </a>
<?php endif; ?>

<?php if (isset($SESSION['switch']) && $SESSION['switch']===true && $SESSION['uid'] != $user['uid'] && $user['level'] <= 3 && $user['level'] > 1): ?>
    <!-- switch.htm -->
    <div class="modal fade bs-example-modal-sm" id="myModal-switch-<?php echo $user['uid']; ?>" tabindex="-1" role="dialog" aria
    -labelledby="myModalLabel-<?php echo $user['uid']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bornon texalilef">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    <h4 class="modal-title" id="myModalLabel-<?php echo $user['uid']; ?>">
                        <i class="glyphicon glyphicon-user"></i> <?php echo $confirm_connection_as[$l]; ?> <b class="fonstyita"><?php echo strtoupper($user['nom']); ?> <?php echo ucfirst($user['prenom']); ?></b>
                    </h4>
                </div>
                <form action="<?php echo $BASE.'/switchto'; ?>" method="post" class="
                form-switch" role="form" id="form-switch-<?php echo $user['uid']; ?>">
                    <div class="modal-footer bornon padtopnon martopnon">
                        <input type="hidden" name="sw" value="y" />
                        <input type="hidden" name="u" value="<?php echo $user['uid']; ?>" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <?php echo $cancel[$l]; ?>
                        </button>

                        <button id="modal-switch-<?php echo $user['uid']; ?>" class="btn btn-info" type="submit">
                            <?php echo $validate[$l]; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
