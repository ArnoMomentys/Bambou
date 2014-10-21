<h2 class="page-heading marbotnon">
    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>">
        <span class="textracap"><?php echo $eventname[$l]; ?></span> : <?php echo $event['nom']; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
    </a>
</h2>
<small class="dimgra"><span class="textraupp"><?php echo $starting_at[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?></small>
<br>
<small class="dimgra"><span class="textraupp"><?php echo $limit_displayed[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['limitB'])); ?></small><br><br>

<?php if (isset($SESSION['errors']) && count($SESSION['errors'])>0): ?>
    <?php echo $this->render('globalerrors.htm',$this->mime,get_defined_vars()); ?>
<?php endif; ?>

<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
<?php endif; ?>

<h3 class="pull-left disblo widful page-subheading">
    <?php echo $page_subheader; ?>
    <br>
    <small class="fonweilig">
        <?php echo $follow_csv_reco[$l]; ?>
    </small>
</h3>

<h3 class="pull-left disblo widful page-subheading">
    <small class="fonweilig">
	<span class="badge">1</span>&nbsp;&nbsp;<?php echo $follow_csv_model[$l]; ?>
        <br>
        <span class="padlef25 fonweibola nocolr">. La première ligne doit contenir l'entête des champs ci-dessous (csv uniquement)</span>
        <br>
        <span class="padlef25 fonweibola nocolr">. <?php echo $follow_csv_required_columns[$l]; ?></span>
    </small>
</h3>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 marlefnon padlefnon widful"  with="<?php $ar=['Civilité','Nom','Prénom','Fonction','Branche','BU','Société/Organisme/Collectivité','Adresse','Code Postal','Ville','Pays','Telephone Fixe','Telephone portable','email'] ?>">
    <?php $ctr=0; foreach (($ar?:array()) as $col): $ctr++; ?>
        <div class="marnon padnon pull-left">
            <table class="table marnon cell-bordered-important model">
                <tr>
                    <th class="<?php echo $ctr==1?'first':''; ?>"><?php echo $col; ?></th>
                </tr>
                <tr>
                    <td class="texalicen<?php echo $ctr==1?' first':''; ?>">...</td>
                </tr>
            </table>
        </div>
    <?php endforeach; ?>
</div>

<div class="pull-left martop30 widful"><br></div>

<h3 class="pull-left disblo widful page-subheading">
    <small class="fonweilig">
        <span class="badge">2</span>&nbsp;&nbsp;<?php echo ucfirst($can_download_model[$l]); ?> csv <a href="<?php echo $BASE.'/download/contacts/'.$event['eid']; ?>" class="nocolr veralimid btn-link baccolnoni texdecundi"> <?php echo $here[$l]; ?> </a> - xls <a href="<?php echo $BASE.'/tmp/downloads/contacts.xls'; ?>" class="nocolr veralimid btn-link baccolnoni texdecundi"> <?php echo $here[$l]; ?> </a>
    </small>
</h3>

<div class="pull-left martop30 widful"></div>

<h3 class="pull-left disblo widful page-subheading">
    <small class="fonweilig">
        <span class="badge">3</span>&nbsp;&nbsp;<?php echo $choose_csv_file_to_upload[$l]; ?>
    </small>
</h3>

<div class="col-xs-12 marlefnon padlefnon">
	<form method="POST" enctype="multipart/form-data" id="ug" role="form">
	    <input type="file" class="filestyle" name="csv" data-buttonText="&nbsp;&nbsp;<?php echo $event_choose_file[$l]; ?>" data-iconName="glyphicon-inbox" data-buttonName="btn-primary custom" data-buttonName="btn-primary">
	    <div id="fsub" class="btn btn-success disnon">
	    	<i class="glyphicon glyphicon-open"></i> <?php echo $event_import_file[$l]; ?>
	    </div>
	</form>
    <br>
    <br>
</div>
