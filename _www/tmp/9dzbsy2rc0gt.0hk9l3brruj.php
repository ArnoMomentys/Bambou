<h2 class="page-heading marbotnon">
    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>">
        <span class="textracap"><?php echo $eventname[$l]; ?></span> : <?php echo $event['nom']; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
    </a>
</h2>
<small class="dimgra"><span class="textraupp"><?php echo $starting_at[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?></small>
<br>
<small class="dimgra"><span class="textraupp"><?php echo $limit_displayed[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['limitB'])); ?></small>
<br>
<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    
        <br>
        <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
    
<?php endif; ?>

<h3 class="pull-left disblo widful page-subheading padtop20 marbotnon">
    <?php echo $page_header; ?>
    <?php if ($isold == false && $SESSION['c'] != 0): ?>
        <a href="<?php echo '/event/'.$event['eid'].'/add/guest'; ?>" class="pull-right texdecnon postop-10 posrel" title="<?php echo $event_guest_add[$l]; ?>">
            <small class="hidden-xs hidden-sm"><?php echo $event_guest_add[$l]; ?></small>
            <i class="glyphicon glyphicon-plus-sign"></i>
        </a>
    <?php endif; ?>
</h3>

<table class="table table-striped responsive-utilities list grouplist guestslist martopnon">
    <thead>

        <tr>
            <td colspan="5" class="paginate">
                <?php echo $this->raw($pagebrowser); ?>
            </td>
        </tr>

        <?php if (!empty($lists_keys)): ?>
            <tr>
                <?php $ctr=0; foreach (($lists_keys?:array()) as $key=>$value): $ctr++; ?>
                    <?php if (in_array($value, ['guestname','guestfunction','guestcompany','hostname'])): ?>
                        <?php if (($value=='hostname' && $SESSION['lvl']==1) || $value!='hostname'): ?>
                            <th class="<?php echo $value; ?>">
                                <div class="th-wrapper" with="<?php $k=$$value; ?>">
                                    <div class="flolef padtop10 padrig5 padbot10 padlef5"><?php echo $k[$l]; ?></div>
                                    <div class="sorterwrap">
                                        <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show/guests/'.$value.'/order/asc'; ?>"
                                        class="btn btn-link sorter up has-tip-up"
                                        title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> [A-Z]">
                                            <i class="sortby glyphicon glyphicon-chevron-up"></i>
                                        </a>
                                        <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show/guests/'.$value.'/order/desc'; ?>"
                                        class="btn btn-link sorter down has-tip-down"
                                        title="<?php echo $sortby[$l]; ?> <?php echo $k[$l]; ?> [Z-A]">
                                            <i class="sortby glyphicon glyphicon-chevron-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th>&nbsp;</th>
            </tr>
        <?php endif; ?>
    </thead>

    <tbody>
        <?php if ($totaux>0): ?>
            
                <?php $lctr=0; foreach (($lists?:array()) as $liste): $lctr++; ?>
                    <tr>
                        <?php $ctr=0; foreach (($liste?:array()) as $key=>$value): $ctr++; ?>
                            <?php if (in_array($key, ['guestname','guestfunction','guestcompany','hostname'])): ?>
                                <?php if (($key=='hostname' && $SESSION['lvl']==1) || $key!='hostname'): ?>
                                    <td class="<?php echo $key; ?>">
                                        <?php if ($key=='reprname'): ?>
                                            
                                                <table class="flolef bornoni padnon posrel marnon widful">
                                                    <tr>
                                                        <td>
                                                            <span class="pull-left">
                                                                <a href="/user/<?php echo $liste['reprid']; ?>/show" class="disinlblo">
                                                                    <?php echo $value; ?>
                                                                </a>
                                                            </span>
                                                        </td>
                                                        <?php if (!$isold): ?>
                                                            <?php if (strlen($value)==0): ?>
                                                                
                                                                    <td class="posabs">
                                                                        <!-- /event/@eid/guest/@guestid/invitation/@invitationid/add/repr -->
                                                                        <a href="<?php echo '/event/'.$liste['eid'].'/guest/'.$liste['guestid'].'/invitation/'.$liste['invitationid'].'/add/repr'; ?>" title="<?php echo $add_representative[$l]; ?>" class="pull-right has-tip-up">
                                                                            <i class="glyphicon glyphicon-plus"></i>
                                                                        </a>
                                                                    </td>
                                                                
                                                                <?php else: ?>
                                                                    <td>
                                                                        <!-- /event/@eid/guest/@guestid/invitation/@invitationid/remove/repr/@reprid -->
                                                                        <a href="<?php echo '/event/'.$liste['eid'].'/guest/'.$liste['guestid'].'/invitation/'.$liste['invitationid'].'/remove/repr/'.$liste['reprid']; ?>" title="<?php echo $representative_remove[$l]; ?>" class="pull-right has-tip-up">
                                                                            <i class="glyphicon glyphicon-remove"></i>
                                                                        </a>
                                                                    </td>
                                                                
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </tr>
                                                </table>
                                            
                                            <?php else: ?>
                                                <?php if ($key=='accnames'): ?>
                                                    
                                                        <table class="flolef bornoni padnon posrel marnon widful">
                                                            <tr>
                                                                <td
                                                                    class="names"
                                                                    with="<?php
                                                                        $ids = explode(',', $liste['accids']);
                                                                        $names = explode(',', $value);
                                                                        $val = trim($value);
                                                                    ?>">
                                                                        <?php if (!empty($val)): ?>
                                                                        <?php $namesctr=0; foreach (($names?:array()) as $name): $namesctr++; ?>
                                                                            <span class="pull-left disblo widful">
                                                                                <a href="/user/<?php echo $ids[$namesctr-1]; ?>/show" class="disinlblo">
                                                                                    <?php echo $name; ?>
                                                                                </a>
                                                                                <?php if (!$isold): ?>
                                                                                    <a href="<?php echo '/event/'.$liste['eid'].'/invitation/'.$liste['invitationid'].'/remove/acc/'.$ids[$namesctr-1]; ?>" title="<?php echo $accompanying_remove[$l]; ?>" class="has-tip-up">
                                                                                        <i class="glyphicon glyphicon-remove"></i>
                                                                                    </a>
                                                                                <?php endif; ?>
                                                                            </span>
                                                                        <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                </td>
                                                                <?php if (!$isold): ?>
                                                                    <td class="posabs">
                                                                        <!-- /event/100/guest/34407/igid/33590/add/acc -->
                                                                        <a href="<?php echo '/event/'.$liste['eid'].'/guest/'.$liste['guestid'].'/invitation/'.$liste['invitationid'].'/add/acc'; ?>" title="<?php echo $add_accompanying[$l]; ?>" class="pull-right has-tip-up">
                                                                            <i class="glyphicon glyphicon-plus"></i>
                                                                        </a>
                                                                    </td>
                                                                <?php endif; ?>
                                                            </tr>
                                                        </table>
                                                    
                                                    <?php else: ?>
                                                        <?php if ($key=='guestname'): ?>
                                                            
                                                                <a href="/user/<?php echo $liste['guestid']; ?>/show" class="disinlblo widful">
                                                                    <?php echo $value; ?>
                                                                </a>
                                                            
                                                            <?php else: ?>
                                                                <?php if ($key=='hostname'): ?>
                                                                    
                                                                        <a href="/user/<?php echo $liste['hostid']; ?>/show" class="disinlblo widful">
                                                                            <?php echo $value; ?>
                                                                        </a>
                                                                    
                                                                    <?php else: ?>
                                                                        <span class="disinlblo widful"><?php echo $value; ?></span>
                                                                    
                                                                <?php endif; ?>
                                                            
                                                        <?php endif; ?>
                                                    
                                                <?php endif; ?>
                                            
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td class="actions texalirig borrignoni">
                            <table class="florig bornoni padnon marnon disinlblo">
                                <tr>
                                    <?php if ($isold == false): ?>
									    <td class="answ">
                                            <div class="dropdown disinl">
                                                <button class="btn btn-link curpoi has-tip-left dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" title="<?php echo $answer_invitation[$l]; ?>">
                                                    <?php echo $liste['answer'] == 0 ? '<i class="glyphicon glyphicon-question-sign bluetxt"></i>' : ''; ?>
                                                    <?php echo $liste['answer'] == 1 ? '<i class="glyphicon glyphicon-ok-sign greentxt"></i>' : ''; ?>
                                                    <?php echo $liste['answer'] == 2 ? '<i class="glyphicon glyphicon-minus-sign redtxt"></i>' : ''; ?>
                                                </button>
												<!-- ONLY Admin can change the status -->
												<?php if ($SESSION['lvl'] <= 2): ?>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
														<li role="presentation">
															<a class="texalilef" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $liste['invitationid']; ?>/0/event_<?php echo $event['eid']; ?>_show_guests"><?php echo $no_answer[$l]; ?> <?php echo $liste['answer'] == 0 ? '<i class="glyphicon glyphicon-ok marlef5"></i>' : ''; ?></a>
														</li>
														<li role="presentation">
															<a class="texalilef" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $liste['invitationid']; ?>/1/event_<?php echo $event['eid']; ?>_show_guests"><?php echo $guest_answer_yes[$l]; ?> <?php echo $liste['answer'] == 1 ? '<i class="glyphicon glyphicon-ok marlef5"></i>' : ''; ?></a>
														</li>
														<li role="presentation">
															<a class="texalilef" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $liste['invitationid']; ?>/2/event_<?php echo $event['eid']; ?>_show_guests"><?php echo $guest_answer_no[$l]; ?> <?php echo $liste['answer'] == 2 ? '<i class="glyphicon glyphicon-ok marlef5"></i>' : ''; ?></a>
														</li>
													</ul>
												<?php endif; ?>
                                            </div>
                                        </td>
									<?php endif; ?>

                                    <?php if ($isold == false && $isdone == false): ?>
                                        <?php if ($liste['validated'] == 0): ?>
                                            
												<!-- ONLY Admin can send invitation -->
												<?php if ($SESSION['lvl'] <= 2): ?>
													<td class="valid">
														<span class="btn btn-link curpoi has-tip-left tobevalidated" data-toggle="modal" data-target="#myModal-validateinvitations-<?php echo $liste['invitationid']; ?>" title="<?php echo $send_invitation[$l]; ?>">
															<i class="glyphicon glyphicon-envelope"></i>
														</span>
														<!-- validateinvitation.htm -->
														<div class="modal fade bs-example-modal-sm" id="myModal-validateinvitations-<?php echo $liste['invitationid']; ?>" tabindex="-1" role="dialog" aria
														-labelledby="myModalLabel-<?php echo $liste['invitationid']; ?>" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header bornon texalilef">
																		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

																		<h4 class="modal-title" id="myModalLabel-<?php echo $liste['invitationid']; ?>">
																			<i class="glyphicon glyphicon-ok-circle"></i> <?php echo ucfirst($validate[$l]); ?> <?php echo $invitation_for[$l]; ?> <b class="fonstyita"><?php echo $liste['guestname']; ?></b>
																		</h4>
																	</div>
																	<form action="<?php echo $BASE.'/event/'.$event['eid'].'/invitation/validate'; ?>" method="post" class="
																	form-delete" role="form" id="form-validateinvitations-<?php echo $liste['invitationid']; ?>">
																		<div class="modal-footer bornon padtopnon martopnon">
																			<input type="hidden" name="validate-guest" value="v" />
																			<input type="hidden" name="invitationID" value="<?php echo $liste['invitationid']; ?>">
																			<input type="hidden" name="eventID" value="<?php echo $event['eid']; ?>">
																			<input type="hidden" name="guestname" value="<?php echo $liste['guestname']; ?>">
																			<button type="button" class="btn btn-default" data-dismiss="modal">
																				<?php echo $cancel[$l]; ?>
																			</button>

																			<button id="modal-validateinvitations-<?php echo $liste['invitationid']; ?>" class="btn btn-danger" type="submit">
																				<?php echo $send_invitation[$l]; ?>
																			</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</td>
												<?php endif; ?>
                                                <td class="remo borrignoni">
                                                    <span class="btn btn-link curpoi has-tip-left" data-toggle="modal" data-target="#myModal-removefromguests-<?php echo $liste['invitationid']; ?>" title="<?php echo $remove_from_guests[$l]; ?>">
                                                        <i class="glyphicon glyphicon-remove glyphicon-black"></i>
                                                    </span>
                                                    <!-- removefromguest.htm -->
                                                    <div class="modal fade bs-example-modal-sm" id="myModal-removefromguests-<?php echo $liste['invitationid']; ?>" tabindex="-1" role="dialog" aria
                                                    -labelledby="myModalLabel-<?php echo $liste['invitationid']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bornon texalilef">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                                                    <h4 class="modal-title" id="myModalLabel-<?php echo $liste['invitationid']; ?>">
                                                                        <i class="glyphicon glyphicon-remove-circle"></i> <?php echo ucfirst($remove[$l]); ?> <b class="fonstyita"><?php echo $liste['guestname']; ?></b> <?php echo $from_guests[$l]; ?>
                                                                    </h4>
                                                                </div>
                                                                <form action="<?php echo $BASE.'/event/'.$event['eid'].'/remove/guest'; ?>" method="post" class="
                                                                form-delete" role="form" id="form-removefromguests-<?php echo $liste['invitationid']; ?>">
                                                                    <div class="modal-footer bornon padtopnon martopnon">
                                                                        <input type="hidden" name="delg" value="d" />
                                                                        <input type="hidden" name="invitationID" value="<?php echo $liste['invitationid']; ?>">
                                                                        <input type="hidden" name="eventID" value="<?php echo $event['eid']; ?>">
                                                                        <input type="hidden" name="guestname" value="<?php echo $liste['guestname']; ?>">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                            <?php echo $cancel[$l]; ?>
                                                                        </button>

                                                                        <button id="modal-removefromguests-<?php echo $liste['invitationid']; ?>" class="btn btn-danger" type="submit">
                                                                            <?php echo $remove_from_guests[$l]; ?>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            
                                            <?php else: ?>
                                                <td class="valid borrignoni">
                                                    <span class="btn btn-link curnot has-tip-left validated" title="<?php echo $invitation_already_validated[$l]; ?>">
                                                        <i class="glyphicon glyphicon-saved glyphicon-black"></i>
                                                    </span>
                                                </td>
                                            
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if ($isold == true && $isdone == false): ?>
                                        <?php if ($liste['validated'] == 0): ?>
                                            
                                                <td class="valid">
                                                    <span class="btn btn-link curnot has-tip-left validated" title="<?php echo $invitation_not_validated[$l]; ?>">
                                                        <i class="glyphicon glyphicon-save"></i>
                                                    </span>
                                                </td>
                                                <td class="remo borrignoni">
                                                    <span class="btn btn-link curpoi has-tip-left" data-toggle="modal" data-target="#myModal-removefromguests-<?php echo $liste['invitationid']; ?>" title="<?php echo $remove_from_guests[$l]; ?>">
                                                        <i class="glyphicon glyphicon-remove glyphicon-black"></i>
                                                    </span>
                                                    <!-- removefromguest.htm -->
                                                    <div class="modal fade bs-example-modal-sm" id="myModal-removefromguests-<?php echo $liste['invitationid']; ?>" tabindex="-1" role="dialog" aria
                                                    -labelledby="myModalLabel-<?php echo $liste['invitationid']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bornon texalilef">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                                                    <h4 class="modal-title" id="myModalLabel-<?php echo $liste['invitationid']; ?>">
                                                                        <i class="glyphicon glyphicon-remove-circle"></i> <?php echo ucfirst($remove[$l]); ?> <b class="fonstyita"><?php echo $liste['guestname']; ?></b> <?php echo $from_guests[$l]; ?>
                                                                    </h4>
                                                                </div>
                                                                <form action="<?php echo $BASE.'/event/'.$event['eid'].'/remove/guest'; ?>" method="post" class="
                                                                form-delete" role="form" id="form-removefromguests-<?php echo $liste['invitationid']; ?>">
                                                                    <div class="modal-footer bornon padtopnon martopnon">
                                                                        <input type="hidden" name="delg" value="d" />
                                                                        <input type="hidden" name="invitationID" value="<?php echo $liste['invitationid']; ?>">
                                                                        <input type="hidden" name="eventID" value="<?php echo $event['eid']; ?>">
                                                                        <input type="hidden" name="guestname" value="<?php echo $liste['guestname']; ?>">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                            <?php echo $cancel[$l]; ?>
                                                                        </button>

                                                                        <button id="modal-removefromguests-<?php echo $liste['invitationid']; ?>" class="btn btn-danger" type="submit">
                                                                            <?php echo $remove_from_guests[$l]; ?>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            
                                            <?php else: ?>
                                                <td class="valid borrignoni">
                                                    <span class="btn btn-link curnot has-tip-left validated" title="<?php echo $invitation_already_validated[$l]; ?>">
                                                        <i class="glyphicon glyphicon-saved glyphicon-black"></i>
                                                    </span>
                                                </td>
                                            
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if ($isdone == true && $isold == true): ?>
                                        <td class="pres">
                                            <div class="dropdown disinl">
                                                <button class="btn btn-link curpoi has-tip-left dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" title="<?php echo ucfirst($event_presence[$l]); ?>">
                                                    <i class="glyphicon glyphicon-flash glyphicon-black"></i>
                                                </button>
                                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
                                                    <li role="presentation">
                                                        <a class="texalilef" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $liste['invitationid']; ?>/presence/0/event_<?php echo $event['eid']; ?>_show_guests"><?php echo ucfirst($answered_no[$l]); ?> <?php echo $liste['presence'] == 0 ? '<i class="glyphicon glyphicon-ok marlef5"></i>' : ''; ?></a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a class="texalilef" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $liste['invitationid']; ?>/presence/1/event_<?php echo $event['eid']; ?>_show_guests"><?php echo ucfirst($answered_yes[$l]); ?> <?php echo $liste['presence'] == 1 ? '<i class="glyphicon glyphicon-ok marlef5"></i>' : ''; ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <?php if ($liste['validated'] == 0): ?>
                                            
                                                <td class="valid">
                                                    <span class="btn btn-link curnot has-tip-left validated" title="<?php echo $invitation_not_validated[$l]; ?>">
                                                        <i class="glyphicon glyphicon-save"></i>
                                                    </span>
                                                </td>
                                                <?php if ($liste['presence']==0): ?>
                                                    <td class="remo borrignoni">
                                                        <span class="btn btn-link curpoi has-tip-left" data-toggle="modal" data-target="#myModal-removefromguests-<?php echo $liste['invitationid']; ?>" title="<?php echo $remove_from_guests[$l]; ?>">
                                                            <i class="glyphicon glyphicon-remove glyphicon-black"></i>
                                                        </span>
                                                        <!-- removefromguest.htm -->
                                                        <div class="modal fade bs-example-modal-sm" id="myModal-removefromguests-<?php echo $liste['invitationid']; ?>" tabindex="-1" role="dialog" aria
                                                        -labelledby="myModalLabel-<?php echo $liste['invitationid']; ?>" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bornon texalilef">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                                                        <h4 class="modal-title" id="myModalLabel-<?php echo $liste['invitationid']; ?>">
                                                                            <i class="glyphicon glyphicon-remove-circle"></i> <?php echo ucfirst($remove[$l]); ?> <b class="fonstyita"><?php echo $liste['guestname']; ?></b> <?php echo $from_guests[$l]; ?>
                                                                        </h4>
                                                                    </div>
                                                                    <form action="<?php echo $BASE.'/event/'.$event['eid'].'/remove/guest'; ?>" method="post" class="
                                                                    form-delete" role="form" id="form-removefromguests-<?php echo $liste['invitationid']; ?>">
                                                                        <div class="modal-footer bornon padtopnon martopnon">
                                                                            <input type="hidden" name="delg" value="d" />
                                                                            <input type="hidden" name="invitationID" value="<?php echo $liste['invitationid']; ?>">
                                                                            <input type="hidden" name="eventID" value="<?php echo $event['eid']; ?>">
                                                                            <input type="hidden" name="guestname" value="<?php echo $liste['guestname']; ?>">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                                <?php echo $cancel[$l]; ?>
                                                                            </button>

                                                                            <button id="modal-removefromguests-<?php echo $liste['invitationid']; ?>" class="btn btn-danger" type="submit">
                                                                                <?php echo $remove_from_guests[$l]; ?>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                <?php endif; ?>
                                            
                                            <?php else: ?>
                                                <td class="valid borrignoni">
                                                    <span class="btn btn-link curnot has-tip-left validated" title="<?php echo $invitation_already_validated[$l]; ?>">
                                                        <i class="glyphicon glyphicon-saved glyphicon-black"></i>
                                                    </span>
                                                </td>
                                            
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if ($totaux > 30): ?>
                    <tr>
                        <td colspan="7" class="paginate bottom_pager">
                            <?php echo $this->raw($pagebrowser); ?>
                        </td>
                    </tr>
                <?php endif; ?>

            
            <?php else: ?>
                <tr>
                    <td colspan="5"><?php echo $no_guest[$l]; ?></td>
                </tr>
            
        <?php endif; ?>
    </tbody>
</table>

<?php if ($SESSION['lvl']<=3 && strstr($PATTERN,'eid') && (isset($isold) || $isold==true) && ((( strstr($PATTERN,'guest') || (strstr($PATTERN,'status') && $PARAMS['status']=='guests') )) || $SESSION['lvl']==3)): ?>
    <!-- attached to nav export -->
    <?php echo $this->render('event/modal/exportguests.htm',$this->mime,get_defined_vars()); ?>
<?php endif; ?>