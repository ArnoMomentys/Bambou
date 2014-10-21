<h2 class="page-heading">
    <?php echo $page_header; ?>
    <a href="<?php echo $BASE.'/group/create'; ?>" class="veralimid backto" title="<?php echo $create_new_group[$l]; ?>">
        <i class="glyphicon glyphicon-plus-sign"></i>
    </a>
</h2>

<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    
        <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
    
<?php endif; ?>

<table class="table table-striped responsive-utilities list grouplist">
    <thead>

        <tr>
            <td colspan="<?php echo count($lists_keys) + 2; ?>" class="paginate">
                <?php echo $this->raw($pagebrowser); ?>
            </td>
        </tr>
        <?php if (!empty($lists_keys)): ?>
        <tr>
            <?php $ctr=0; foreach (($lists_keys?:array()) as $key=>$value): $ctr++; ?>
                <?php if (in_array($value, array('name', 'nbUsers'))): ?>
                    <th class="<?php echo $value; ?>">
                        <div class="th-wrapper" with="<?php $k=$$value ?>">
                            <div class="flolef padtop10 padrig5 padbot10 padlef5"><?php echo $k[$l]; ?></div>
                            <div class="sorterwrap">
                                <a href="<?php echo $BASE.'/'.$listtype.'/'.$value.($value==$filter && strlen($filtervalue)>0 ? '/'.$filtervalue:'').'/order/asc'; ?>"
                                class="btn btn-link sorter up has-tip-up"
                                title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> <?php echo $value=='nbUsers' ? '[ASC]': '[A-Z]'; ?>">
                                    <i class="sortby glyphicon glyphicon-chevron-up"></i>
                                </a>
                                <a href="<?php echo $BASE.'/'.$listtype.'/'.$value.($value==$filter && strlen($filtervalue)>0 ? '/'.$filtervalue:'').'/order/desc'; ?>"
                                class="btn btn-link sorter down has-tip-down"
                                title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> <?php echo $value=='nbUsers' ? '[DESC]' : '[Z-A]'; ?>">
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
            
            <?php foreach (($lists?:array()) as $liste): ?>
                <tr>
                    <?php $ctr=0; foreach (($liste?:array()) as $key=>$value): $ctr++; ?>
                        <?php if (in_array($key, array('name', 'nbUsers'))): ?>
                            <td class="<?php echo $key; ?><?php echo $key=='nbUsers' ? ' texalicen' : ''; ?>">
                            <?php if ($key=='name'): ?>
                                
                                    <a href="<?php echo $BASE.'/group/'.$liste['gid'].'/show'; ?>" class="">
                                        <?php echo $value; ?>
                                    </a>
                                
                                <?php else: ?>
                                    <span class="disinlblo widful"><?php echo $value; ?></span>
                                
                            </td>
                        <?php endif; ?>
                    <?php endif; ?><?php endforeach; ?>

                    <td class="actions texalirig">
                        <a href="<?php echo $BASE.'/group/'.$liste['gid'].'/show'; ?>" class="btn btn-link texalicen has-tip" title="<?php echo $users_in_group[$l]; ?>">
                            <i class="glyphicon glyphicon-list"></i>
                        </a>
                        <a href="<?php echo $BASE.'/group/'.$liste['gid'].'/update'; ?>" class="btn btn-link texalicen has-tip" title="<?php echo $update[$l]; ?>">
                            <i class="glyphicon glyphicon-pencil glyphicon-black"></i>
                        </a>
                        <span class="btn btn-link curpoi has-tip" data-toggle="modal" data-target="#myModal-delete-<?php echo $liste['gid']; ?>" title="<?php echo $delete[$l]; ?>">
                            <i class="glyphicon glyphicon-remove glyphicon-black"></i>
                        </span>
                        <!-- delete.htm -->
                        <div class="modal fade bs-example-modal-sm" id="myModal-delete-<?php echo $liste['gid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $liste['gid']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bornon texalilef">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel-<?php echo $liste['gid']; ?>">
                                            <i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $group_delete[$l]; ?> <b class="fonstyita"><?php echo $liste['name']; ?></b>
                                        </h4>
                                    </div>
                                    <form action="<?php echo $BASE.'/group/'.$liste['gid'].'/delete'; ?>" method="post" class="form-delete" role="form" id="form-delete-<?php echo $liste['gid']; ?>">
                                        <div class="modal-footer bornon padtopnon martopnon">
                                            <input type="hidden" name="del" value="d" />
                                            <input type="hidden" name="gid" value="<?php echo $liste['gid']; ?>">
                                            <input type="hidden" name="groupname" value="<?php echo $liste['name']; ?>">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                <?php echo $cancel[$l]; ?>
                                            </button>
                                            <button class="btn btn-danger" type="submit">
                                                <?php echo $group_delete_confirm[$l]; ?>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if ($totaux > 30): ?>
                <tr>
                    <td colspan="<?php echo count($lists['subset'][0]->fields) + 2; ?>" class="paginate">
                        <?php echo $this->raw($pagebrowser); ?>
                    </td>
                </tr>
            <?php endif; ?>

            
            <?php else: ?>
                <tr>
                    <td colspan="4"><?php echo $no_group[$l]; ?></td>
                </tr>
            
        <?php endif; ?>
    </tbody>
</table>
