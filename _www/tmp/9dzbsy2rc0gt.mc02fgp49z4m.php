<h2 class="page-heading marbotnon">
    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>">
        <span class="textracap"><?php echo $eventname[$l]; ?></span> : <?php echo $event['nom']; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
    </a>
</h2>
<small class="dimgra"><span class="textraupp"><?php echo $starting_at[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?></small>
<br>
<small class="dimgra"><span class="textraupp"><?php echo $limit_displayed[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['limitB'])); ?></small><br>

<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    <br>
    
        <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
    
<?php endif; ?>

<h3 class="pull-left disblo widful page-subheading padtop20 marbotnon">
    <?php echo $event_host_add[$l]; ?><br>
    <small class="fonweilig"><?php echo $choose_in_list_host_to_add[$l]; ?></small><br>
    <small class="required"><?php echo $host_not_listed[$l]; ?></small>
    <?php if ($isold == false): ?>
        <a href="<?php echo '/event/'.$event['eid'].'/add/new/host'; ?>" class="pull-right texdecnon postop-10 posrel" title="<?php echo $event_host_add_new[$l]; ?>">
            <small class="hidden-xs hidden-sm"><?php echo $event_host_add_new[$l]; ?></small>
            <i class="glyphicon glyphicon-star pad5"></i>
        </a>
    <?php endif; ?>
</h3>

<table class="table table-striped responsive-utilities list grouplist hostslist martopnon">
    <thead>

        <tr>
            <td colspan="6" class="paginate">
                <?php echo $this->raw($pagebrowser); ?>
            </td>
        </tr>

        <?php if (!empty($lists_keys)): ?>
            <tr>
                <?php $ctr=0; foreach (($lists_keys?:array()) as $key=>$value): $ctr++; ?>
                    <?php if (in_array($value, ['nomcomplet','societe','fonction','ville','adresse'])): ?>
                        <th class="<?php echo $value; ?>">
                            <div class="th-wrapper">
                                <div class="flolef padtop10 padrig5 padbot10 padlef5<?php echo $key=='ville'?' textraupp':''; ?>">
                                    <?php echo $value=='nomcomplet'? $name[$l] : $value; ?>
                                </div>
                                <div class="sorterwrap" with="<?php $_value=$value=='nomcomplet'?'nom':$value;$k=$$_value; ?>">
                                    <a href="<?php echo $BASE.'/event/'.$eid.'/add/host/'.$_value.($_value==$filter && strlen($filtervalue)>0 ? '/'.$filtervalue:'').'/order/asc'; ?>"
                                    class="btn btn-link sorter up has-tip-up"
                                    title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> [A-Z]">
                                        <i class="sortby glyphicon glyphicon-chevron-up"></i>
                                    </a>
                                    <a href="<?php echo $BASE.'/event/'.$eid.'/add/host/'.$_value.($_value==$filter && strlen($filtervalue)>0 ? '/'.$filtervalue:'').'/order/desc'; ?>"
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
        <?php if ($totaux>0): ?>
            
                <?php foreach (($lists?:array()) as $liste): ?>
                    <?php if (strlen(trim($liste['nom'])) > 0): ?>
                        <tr>
                            <?php $ctr=0; foreach (($liste?:array()) as $key=>$value): $ctr++; ?>
                                <?php if (in_array($key, ['nomcomplet','societe','fonction','ville','adresse'])): ?>
                                    <td class="<?php echo $key; ?>">
                                        <?php if ($key=='nomcomplet' && $liste['uid']>1): ?>
                                            
                                                <a href="/user/<?php echo $liste['uid']; ?>/show" class="disinlblo widful">
                                                    <?php echo $value; ?>
                                                </a>
                                            
                                            <?php else: ?>
                                                <span class="disinlblo widful<?php echo $key=='ville'?' textraupp':''; ?>"><?php echo $value; ?></span>
                                            
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td class="actions texalirig">
                                <?php if ($liste['uid']>1): ?>
                                    <span class="btn btn-link curpoi has-tip" data-toggle="modal" data-target="#myModal-addhost-<?php echo $liste['uid']; ?>" title="<?php echo $add[$l]; ?>">
                                        <i class="glyphicon glyphicon-plus glyphicon-black"></i>
                                    </span>
                                    <div class="modal fade bs-example-modal-sm" id="myModal-addhost-<?php echo $liste['uid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $liste['uid']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bornon texalilef">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel-<?php echo $liste['uid']; ?>">
                                                        <i class="glyphicon glyphicon-info-sign"></i> <?php echo ucfirst($add[$l]); ?> <b class="fonstyita"><?php echo $liste['nomcomplet']; ?></b> <?php echo $to_event_hosts[$l]; ?>
                                                    </h4>
                                                </div>
                                                <form action="<?php echo $BASE.'/event/'.$eid.'/add/host'; ?>" method="post" class="form-delete" role="form" id="form-addhost-<?php echo $liste['uid']; ?>">
                                                    <div class="modal-footer bornon padtopnon martopnon">
                                                        <input type="hidden" name="addHost" value="a" />
                                                        <input type="hidden" name="eventID" value="<?php echo $eid; ?>">
                                                        <input type="hidden" name="hostID" value="<?php echo $liste['uid']; ?>">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                            <?php echo $cancel[$l]; ?>
                                                        </button>
                                                        <button class="btn btn-info" type="submit">
                                                            <?php echo $event_host_addto_confirm[$l]; ?>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if ($totaux > 30): ?>
                    <tr>
                        <td colspan="6" class="paginate bottom_pager">
                            <?php echo $this->raw($pagebrowser); ?>
                        </td>
                    </tr>
                <?php endif; ?>

            
            <?php else: ?>
                <tr>
                    <td><?php echo $no_host[$l]; ?></td>
                </tr>
            
        <?php endif; ?>
    </tbody>
</table>
