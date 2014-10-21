<h2 class="page-heading">
    <?php echo $page_header; ?> : <?php echo $group['name']; ?>
    <a href="<?php echo $BASE.'/group/'.$group['gid'].'/show'; ?>" class="veralimid backto" title="<?php echo $back_to_groups[$l]; ?>">
        <i class="glyphicon glyphicon-list-alt"></i>
    </a>
</h2>

<h3 class="pull-left disblo widful page-subheading">
    <?php echo $add_group_member[$l]; ?><br>
    <small><?php echo $choose_in_list_user_to_add_to_group[$l]; ?></small>
</h3>

<table class="table table-striped responsive-utilities list grouplist">
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
                                <div class="flolef padtop10 padrig5 padbot10 padlef5">
                                    <?php echo $value=='nomcomplet'? $name[$l] : $value; ?>
                                </div>
                                <div class="sorterwrap" with="<?php $_value=$value=='nomcomplet'?'nom':$value;$k=$$_value; ?>">
                                    <a href="<?php echo $BASE.'/'.$listtype.'/'.$group['gid'].'/add/user/'.$_value.($_value==$filter && strlen($filtervalue)>0 ? '/'.$filtervalue:'').'/order/asc'; ?>"
                                        class="btn btn-link sorter up has-tip-up"
                                        title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> [A-Z]">
                                        <i class="sortby glyphicon glyphicon-chevron-up"></i>
                                    </a>
                                    <a href="<?php echo $BASE.'/'.$listtype.'/'.$group['gid'].'/add/user/'.$_value.(strlen($filtervalue)>0 && $_value==$filter ? '/'.$filtervalue:'').'/order/desc'; ?>"
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
                                        <span class="disinlblo widful<?php echo $key=='ville'?' textraupp':''; ?>"><?php echo $value; ?></span>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td class="actions texalirig">
                                <span class="btn btn-link curpoi" data-toggle="modal" data-target="#myModal-addtogroup-<?php echo $liste['uid']; ?>" title="<?php echo $add[$l]; ?>">
                                    <i class="glyphicon glyphicon-plus glyphicon-black"></i>
                                </span>
                                <!-- addtogroup.htm -->
                                <div class="modal fade bs-example-modal-sm" id="myModal-addtogroup-<?php echo $liste['uid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $liste['uid']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bornon texalilef">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel-<?php echo $liste['uid']; ?>">
                                                    <i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $add[$l]; ?> <b class="fonstyita"><?php echo $liste['nomcomplet']; ?></b><br><?php echo $togroup[$l]; ?> &laquo;&nbsp;<?php echo $group['name']; ?>&nbsp;&raquo;
                                                </h4>
                                            </div>
                                            <form action="<?php echo $BASE.'/group/'.$group['gid'].'/add/user'; ?>" method="post" class="form-delete" role="form" id="form-addtogroup-<?php echo $liste['uid']; ?>">
                                                <div class="modal-footer bornon padtopnon martopnon">
                                                    <input type="hidden" name="addtogroup" value="a" />
                                                    <input type="hidden" name="groupID" value="<?php echo $group['gid']; ?>">
                                                    <input type="hidden" name="userID" value="<?php echo $liste['uid']; ?>">
                                                    <input type="hidden" name="username" value="<?php echo $liste['nomcomplet']; ?>">
                                                    <input type="hidden" name="groupname" value="<?php echo $group['name']; ?>">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        <?php echo $cancel[$l]; ?>
                                                    </button>
                                                    <button class="btn btn-info" type="submit">
                                                        <?php echo $group_addto_confirm[$l]; ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
