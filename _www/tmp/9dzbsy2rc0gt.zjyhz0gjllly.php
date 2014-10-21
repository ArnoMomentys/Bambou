<h2 class="page-heading textracap">
    <?php echo $page_header; ?> : <?php echo $group_users[0]['name']; ?>
    <a href="<?php echo $BASE.'/groups'; ?>" class="veralimid backto" title="<?php echo $back_to_groups[$l]; ?>">
        <i class="glyphicon glyphicon-list-alt"></i>
    </a>
</h2>

<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    
        <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
    
<?php endif; ?>

<h3 class="pull-left disblo widful page-subheading marbotnon">
    <?php echo ucfirst($group_members[$l]); ?>
    <a href="<?php echo '/group/'.$group_users[0]['gid'].'/add/user'; ?>" class="pull-right veralimid texdecnon" title="<?php echo $add_group_member[$l]; ?>">
        <small class="hidden-xs hidden-sm"><?php echo $add_group_member[$l]; ?></small>
        <i class="glyphicon glyphicon-plus-sign"></i>
    </a>
</h3>

<table class="table table-striped usergrouplist">
    <thead>
        <tr>
            <td colspan="6" class="cptmembers">
                <?php echo $group_users[0]['nbUsers'] ? $group_users[0]['nbUsers'] : 0; ?> <?php echo $group_users[0]['nbUsers']>1 ? $users_list[$l] : $contact[$l]; ?>
            </td>
        </tr>

        <?php if ($group_users[0]['nbUsers'] > 0): ?>
            <tr>
                <?php $ctr=0; foreach (($group_users[1][0]?:array()) as $key=>$value): $ctr++; ?>
                    <?php if (in_array($key, ['nomcomplet','societe','fonction','ville','adresse'])): ?>
                        <th class="<?php echo $key; ?>">
                            <?php echo $key=='nomcomplet'? $name[$l] : $key; ?>
                        </th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th>&nbsp;</th>
            </tr>
        <?php endif; ?>
    </thead>

    <tbody>
        <?php if ($group_users[0]['nbUsers'] > 0): ?>
            
                <?php $ctr=0; foreach (($group_users[1]?:array()) as $group_user): $ctr++; ?>
                <tr>
                    <?php $ctr=0; foreach (($group_user?:array()) as $key=>$value): $ctr++; ?>
                        <?php if (in_array($key, ['nomcomplet','societe','fonction','ville','adresse'])): ?>
                            <td class="<?php echo $key; ?>">
                                <span class="disinlblo widful"><?php echo $value; ?></span>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td class="actions texalirig">
                        <span class="btn btn-link curpoi has-tip" data-toggle="modal" data-target="#myModal-removefromgroup-<?php echo $group_user['uid']; ?>" title="<?php echo $remove_from_group[$l]; ?>">
                            <i class="glyphicon glyphicon-remove glyphicon-black"></i>
                        </span>
                        <div class="modal fade bs-example-modal-sm" id="myModal-removefromgroup-<?php echo $group_user['uid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $group_user['uid']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bornon texalilef">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel-<?php echo $group_user['uid']; ?>">
                                            <i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo ucfirst($remove[$l]); ?> <b class="fonstyita"><?php echo $group_user['nomcomplet']; ?></b><br><?php echo $from_group[$l]; ?> &laquo;&nbsp;<?php echo $group_users[0]['name']; ?>&nbsp;&raquo;
                                        </h4>
                                    </div>
                                    <form action="<?php echo $BASE.'/group/'.$group_users['0']['gid'].'/delete/user/'.$group_user['uid']; ?>" method="post" class="form-delete" role="form" id="form-removefromgroup-<?php echo $group_user['uid']; ?>">
                                        <div class="modal-footer bornon padtopnon martopnon">
                                            <input type="hidden" name="del" value="d" />
                                            <input type="hidden" name="gid" value="<?php echo $group_users[0]['gid']; ?>">
                                            <input type="hidden" name="uid" value="<?php echo $group_user['uid']; ?>">
                                            <input type="hidden" name="username" value="<?php echo $group_user['nomcomplet']; ?>">
                                            <input type="hidden" name="groupname" value="<?php echo $group_users[0]['name']; ?>">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                <?php echo $cancel[$l]; ?>
                                            </button>
                                            <button id="modal-removefromgroup-<?php echo $group_user['uid']; ?>" class="btn btn-danger" type="submit">
                                                <?php echo $group_remove_confirm[$l]; ?>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            
            <?php else: ?>
                <tr>
                    <td class="name" colspan="6">
                        <span><?php echo $no_user_in_this_group[$l]; ?></span>
                    </td>
                </tr>
            
        <?php endif; ?>
    </tbody>
</table>