<h2 class="page-heading marbotnon">
    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>">
        <span class="textracap"><?php echo $eventname[$l]; ?></span> : <?php echo $event['nom']; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
    </a>
</h2>
<small class="dimgra"><span class="textraupp"><?php echo $starting_at[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?></small>
<br>
<small class="dimgra"><span class="textraupp"><?php echo $limit_displayed[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['limitB'])); ?></small><br><br>

<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    
        <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
    
<?php endif; ?>

<h3 class="pull-left disblo widful page-subheading marbotnon">
    <?php echo $event_hosts_already_added[$l]; ?>
    <?php if ($isold == false && $SESSION['c'] != 0): ?>
        <a href="<?php echo '/event/'.$event['eid'].'/add/host'; ?>" class="pull-right texdecnon postop-10 posrel" title="<?php echo $event_host_add[$l]; ?>">
            <small class="hidden-xs hidden-sm"><?php echo $event_host_add[$l]; ?></small>
            <i class="glyphicon glyphicon-plus-sign"></i>
        </a>
    <?php endif; ?>
</h3>

<table class="table table-striped responsive-utilities list grouplist hostslist martopnon">
    <thead>

        <tr>
            <td colspan="8" class="paginate">
                <?php echo $this->raw($pagebrowser); ?>
            </td>
        </tr>

        <?php if (!empty($lists_keys)): ?>
            <tr>
                <?php $ctr=0; foreach (($lists_keys?:array()) as $key=>$value): $ctr++; ?>
                    <?php if (in_array($value, ['hostname','hostbranch','hostbu','hostcompany','hostfunction','hosttown','hostaddress'])): ?>
                        <th class="<?php echo $value; ?>">
                            <div class="th-wrapper" with="<?php $k=$$value; ?>">
                                <div class="flolef padtop10 padrig5 padbot10 padlef5"><?php echo $k[$l]; ?></div>
                                <div class="sorterwrap">
                                    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show/hosts/'.$value.'/order/asc'; ?>"
                                    class="btn btn-link sorter up has-tip-up"
                                    title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> [A-Z]">
                                        <i class="sortby glyphicon glyphicon-chevron-up"></i>
                                    </a>
                                    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show/hosts/'.$value.'/order/desc'; ?>"
                                    class="btn btn-link sorter down has-tip-down"
                                    title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> [Z-A]">
                                        <i class="sortby glyphicon glyphicon-chevron-down"></i>
                                    </a>
                                </div>
                            </div>
                        </th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th>&nbsp;</th>
            </tr>
        <?php endif; ?>
    </thead>

    <tbody>
        <?php if ($totaux > 0): ?>
            
                <?php $lctr=0; foreach (($lists?:array()) as $liste): $lctr++; ?>
                    <tr>
                        <?php $ctr=0; foreach (($liste?:array()) as $key=>$value): $ctr++; ?>
                            <?php if (in_array($key, ['hostname','hostbranch','hostbu','hostcompany','hostfunction','hosttown','hostaddress'])): ?>
                                <td class="<?php echo $key; ?>">
                                    <?php if ($key=='hostname'): ?>
                                        
                                            <a href="/user/<?php echo $liste['hostid']; ?>/show" class="disinlblo widful"><?php echo $value; ?></a>
                                        
                                        <?php else: ?>
                                            <span class="disinlblo widful"><?php echo $value; ?></span>
                                        
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td class="actions texalirig">
                            <?php if ($isold == false): ?>
                                
                                    <span id="removefromhosts-<?php echo $liste['hostid']; ?>" class="btn btn-link curpoi has-tip" data-toggle="modal" data-target="#myModal-removefromhosts-<?php echo $liste['hostid']; ?>" title="<?php echo $remove_from_hosts[$l]; ?>">
                                        <i class="glyphicon glyphicon-remove glyphicon-black"></i>
                                    </span>
                                    <!-- removefromhost.htm -->
                                    <div class="modal fade bs-example-modal-sm" id="myModal-removefromhosts-<?php echo $liste['hostid']; ?>" tabindex="-1" role="dialog" aria
                                    -labelledby="myModalLabel-<?php echo $liste['hostid']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bornon texalilef">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                                    <h4 class="modal-title" id="myModalLabel-<?php echo $liste['hostid']; ?>">
                                                        <i class="glyphicon glyphicon-remove-circle"></i> <?php echo ucfirst($remove[$l]); ?> <b class="fonstyita"><?php echo $liste['hostname']; ?></b> <?php echo $from_hosts[$l]; ?> <?php echo isset($liststats[$liste['hostid']]) ? $and_all_invitations[$l].' ('. $liststats[$liste['hostid']].')':''; ?> ?
                                                    </h4>
                                                </div>
                                                <form action="<?php echo $BASE.'/event/'.$liste['eventid'].'/remove/host'; ?>" method="post" class="form-delete" role="form" id="form-removefromhosts-<?php echo $liste['hostid']; ?>">
                                                    <div class="modal-footer bornon padtopnon martopnon">
                                                        <input type="hidden" name="delh" value="d" />
                                                        <input type="hidden" name="eventID" value="<?php echo $liste['eventid']; ?>">
                                                        <input type="hidden" name="hostID" value="<?php echo $liste['hostid']; ?>">
                                                        <input type="hidden" name="hostname" value="<?php echo $liste['hostname']; ?>">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $cancel[$l]; ?></button>
                                                        <button id="modal-removefromhosts-<?php echo $liste['hostid']; ?>" class="btn btn-danger" type="submit"><?php echo $remove_from_hosts[$l]; ?></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                
                                <?php else: ?>
                                    <span id="bill-<?php echo $liste['hostid']; ?>" class="btn btn-link curpoi has-tip" title="Générer la facture">
                                        <i class="glyphicon glyphicon-euro glyphicon-black"></i>
                                    </span>
                                
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if ($totaux > 30): ?>
                    <tr>
                       <td colspan="8" class="paginate bottom_pager">
                            <?php echo $this->raw($pagebrowser); ?>
                        </td>
                    </tr>
                <?php endif; ?>

            
            <?php else: ?>
                <tr>
                    <td colspan="8"><?php echo $no_host[$l]; ?></td>
                </tr>
            
        <?php endif; ?>
    </tbody>
</table>

<?php if ($SESSION['lvl']<=3 && strstr($PATTERN,'eid') && (isset($isold) || $isold==true) && ((( strstr($PATTERN,'hosts') || (strstr($PATTERN,'status') && $PARAMS['status']=='hosts') )) || $SESSION['lvl']==3)): ?>
    <!-- attached to nav export -->
    <?php echo $this->render('event/modal/exporthosts.htm',$this->mime,get_defined_vars()); ?>
<?php endif; ?>