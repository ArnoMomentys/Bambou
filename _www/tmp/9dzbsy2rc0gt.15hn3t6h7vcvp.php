<h2 class="page-heading">
    <?php echo $page_header; ?>
</h2>

<?php if (isset($SESSION['warnings']) && strlen($SESSION['warnings'])>1): ?>
    
        <?php echo $this->render('formwarnings.htm',$this->mime,get_defined_vars()); ?>
    
<?php endif; ?>

<table class="table table-bordered table-striped responsive-utilities list userslist">
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
                                <div class="flolef padtop10 padrig5 padbot10 padlef5"><?php echo $value=='nomcomplet'? $name[$l] : $value; ?></div>
                                <div class="sorterwrap" with="<?php $_value=$value=='nomcomplet'?'nom':$value;$k=$$_value; ?>">
                                    <a href="<?php echo $BASE.'/'.$listtype.'/list/'.$_value.($_value==$filter && strlen($filtervalue)>0 ? '/'.$filtervalue:'').'/order/asc'; ?>"
                                    class="btn btn-link sorter up has-tip-up"
                                    title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> [A-Z]">
                                        <i class="sortby glyphicon glyphicon-chevron-up"></i>
                                    </a>
                                    <a href="<?php echo $BASE.'/'.$listtype.'/list/'.$_value.(strlen($filtervalue)>0 && $_value==$filter ? '/'.$filtervalue:'').'/order/desc'; ?>"
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
                    <?php if (trim($liste['nom']) != ''): ?>
                        <tr>
                            <?php $ctr=0; foreach (($liste?:array()) as $key=>$value): $ctr++; ?>
                                <?php if (in_array($key, ['nomcomplet','societe','fonction','ville','adresse'])): ?>
                                    <td class="<?php echo $key; ?>">
                                        <?php if ($key=='nomcomplet' && $liste['uid']>1): ?>
                                            
                                                <a href="/user/<?php echo $liste['uid']; ?>/show" class="disinlblo widful"><?php echo $value; ?></a>
                                            
                                            <?php else: ?>
                                                <span class="disinlblo widful"><?php echo $value; ?></span>
                                            
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td class="actions texalirig">
                                <?php if ($liste['uid']>1): ?>
                                    <table class="florig bornoni padnon marnon disinlblo">
                                        <tr>
                                            <?php if (isset($SESSION['switch']) && $SESSION['switch']===true && $SESSION['uid'] != $liste['uid']): ?>
                                                <td>
                                                    <span class="btn btn-link curpoi has-tip-left" data-toggle="modal" data-target="#myModal-switch-<?php echo $liste['uid']; ?>" title="<?php echo $connect_as[$l]; ?> <?php echo $liste['nomcomplet']; ?>">
                                                        <i class="glyphicon glyphicon-transfer"></i>
                                                    </span>
                                                    <!-- switch.htm -->
                                                    <div class="modal fade bs-example-modal-sm" id="myModal-switch-<?php echo $liste['uid']; ?>" tabindex="-1" role="dialog" aria
                                                    -labelledby="myModalLabel-<?php echo $liste['uid']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bornon texalilef">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                                                    <h4 class="modal-title" id="myModalLabel-<?php echo $liste['uid']; ?>">
                                                                        <i class="glyphicon glyphicon-user"></i> <?php echo $confirm_connection_as[$l]; ?> <b class="fonstyita"><?php echo $liste['nomcomplet']; ?></b>
                                                                    </h4>
                                                                </div>
                                                                <form action="<?php echo $BASE.'/switchto'; ?>" method="post" class="
                                                                form-switch" role="form" id="form-switch-<?php echo $liste['uid']; ?>">
                                                                    <div class="modal-footer bornon padtopnon martopnon">
                                                                        <input type="hidden" name="sw" value="y" />
                                                                        <input type="hidden" name="u" value="<?php echo $liste['uid']; ?>" />
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                            <?php echo $cancel[$l]; ?>
                                                                        </button>

                                                                        <button id="modal-switch-<?php echo $liste['uid']; ?>" class="btn btn-info" type="submit">
                                                                            <?php echo $validate[$l]; ?>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <a href="<?php echo $BASE.'/user/'.$liste['uid'].'/show'; ?>" class="btn btn-link texalicen has-tip" title="<?php echo $watch_profile[$l]; ?>">
                                                    <i class="glyphicon glyphicon-list"></i>
                                                </a>
                                            </td>
                                            <td class="borrignoni">
                                                <a href="<?php echo $BASE.'/user/'.$liste['uid'].'/update/users_list'; ?>" class="btn btn-link texalicen has-tip" title="<?php echo $update_profile[$l]; ?>">
                                                    <i class="glyphicon glyphicon-pencil glyphicon-black"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
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
                    <td><?php echo $no_result[$l]; ?></td>
                </tr>
            
        <?php endif; ?>
    </tbody>
</table>
