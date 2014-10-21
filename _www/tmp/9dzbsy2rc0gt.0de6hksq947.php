<h2 class="page-heading">
    <?php echo $page_header; ?>
    <?php if ($SESSION['lvl'] <= 2): ?>
        <a href="<?php echo $BASE.'/event/create'; ?>" class="veralimid backto" title="<?php echo $event_create[$l]; ?>">
            <i class="glyphicon glyphicon-record glyphicon-black"></i>
        </a>
    <?php endif; ?>
</h2>

<?php if (isset($SESSION['errors']) && count($SESSION['errors'])>0): ?>
    <?php echo $this->render('globalerrors.htm',$this->mime,get_defined_vars()); ?>
<?php endif; ?>

<?php if (isset($SESSION['msg']) && strlen($SESSION['msg'])>1): ?>
    <?php echo $this->render('forminfos.htm',$this->mime,get_defined_vars()); ?>
<?php endif; ?>

<?php $ctr=0; foreach (($lists?:array()) as $annee=>$events): $ctr++; ?>
    <h3 class="pull-left disblo widful<?php echo $ctr > 1 ? ' grid-group-heading':''; ?>"><?php echo $annee; ?></h3>
    <?php $ct=0; foreach (($events?:array()) as $key=>$event): $ct++; ?>
        <div class="pull-left col-md-4 col-lg-4 panel-grid<?php echo $event['status']==3?' deactivated':($event['status']==2?' maintenance':''); ?><?php echo ' grid'.$ct%3; ?><?php echo $ct%2==0?' even':' odd'; ?>">
            <div class="panel panel-default panel-<?php echo $event['eid']; ?> lv<?php echo $SESSION['lvl']; ?> <?php echo isset($allMyEvents['hosted']) && !in_array($event['eid'], $allMyEvents['hosted']) ? 'invited' : ''; ?>">
                <div class="head-block<?php echo $SESSION['c']==0?' sadm':''; ?>">
                    <div class="panel-heading">
                        <?php if ($SESSION['lvl'] <= 3 && ((isset($allMyEvents['hosted']) && in_array($event['eid'], $allMyEvents['hosted'])) || $SESSION['lvl']==1)): ?>
                            
                                <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>"><?php echo $event['nom']; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i></a>
                            
                            <?php else: ?>
                                <?php echo $event['nom']; ?>
                            
                        <?php endif; ?>
                        <?php if ($event['status']==2): ?>
                            
                                <div class="maintenance-mode">
                                    <i class="glyphicon glyphicon-ban-circle has-tip" title="En maintenance"></i>
                                </div>
                            
                        <?php endif; ?>
                        <?php if ($event['status']==3): ?>
                            
                                <div class="deactivated-mode">
                                    <i class="glyphicon glyphicon-minus-sign has-tip" title="Archivé"></i>
                                </div>
                            
                        <?php endif; ?>
                    </div>

                    <div class="panel-body">
                        <small><?php echo $event['lieu']; ?></small>
                        <br>
                        <small><?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?></small>

                        <?php if ((isset($allMyEvents['hosted']) && in_array($event['eid'], $allMyEvents['hosted'])) || $SESSION['lvl']==1): ?>

                            <div class="body-heading stats">
                                <?php if (isset($stats[$event['eid']]->nbInvValidated)): ?>
                                    <?php echo $stats[$event['eid']]->nbInvValidated; ?>
                                    <?php if ($stats[$event['eid']]->nbInvValidated > 1): ?>
                                        
                                            <span class="hidden-xs"><?php echo $invitations[$l]; ?> <?php echo $validated_fem_plur[$l]; ?></span><span class="hidden-sm hidden-md hidden-lg">inv. val. / </span>
                                        
                                        <?php else: ?>
                                            <span class="hidden-xs"><?php echo $invitation[$l]; ?> <?php echo $validated_fem[$l]; ?></span><span class="hidden-sm hidden-md hidden-lg">inv. val. / </span>
                                        
                                    <?php endif; ?>
                                     <span class="hidden-xs">sur</span> <?php echo $stats[$event['eid']]->nbGuestsTotal; ?>
                                <?php endif; ?>
                            </div>
                            <div class="body-bars stats row-fluid pull-left widful l1">
                                <div class="stats_response col-xs-7 col-sm-7 col-md-7 col-lg-7 padnon">
                                    <h4><?php echo $answers[$l]; ?> <i class="glyphicon glyphicon-bookmark"></i></h4>
                                    <div class="record first _1">
                                        <div class="bar _1" style="height:<?php echo $stats[$event['eid']]->nbGuestsAnswerYes!=0 ? intval(($stats[$event['eid']]->nbGuestsAnswerYes/$stats[$event['eid']]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                            <?php if ($stats[$event['eid']]->nbGuestsAnswerYes>0): ?>
                                                
                                                    <a href="<?php echo $BASE . '/event/' . $event['eid'] . '/show/guests/answer/1'; ?>">
                                                        <span class="digits"><?php echo $stats[$event['eid']]->nbGuestsAnswerYes; ?></span>
                                                        <span class="legend"><?php echo $presents[$l]; ?></span>
                                                    </a>
                                                
                                                <?php else: ?>
                                                    <span class="digits">0</span>
                                                    <span class="legend"><?php echo $presents[$l]; ?></span>
                                                
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="record _2">
                                        <div class="bar _2" style="height:<?php echo $stats[$event['eid']]->nbGuestsAnswerNo!=0 ? intval(($stats[$event['eid']]->nbGuestsAnswerNo/$stats[$event['eid']]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                            <?php if ($stats[$event['eid']]->nbGuestsAnswerNo > 0): ?>
                                                
                                                    <a href="<?php echo $BASE . '/event/' . $event['eid'] . '/show/guests/answer/2'; ?>">
                                                        <span class="digits"><?php echo $stats[$event['eid']]->nbGuestsAnswerNo; ?></span>
                                                        <span class="legend"><?php echo $absents[$l]; ?></span>
                                                    </a>
                                                
                                                <?php else: ?>
                                                    <span class="digits">0</span>
                                                    <span class="legend"><?php echo $absents[$l]; ?></span>
                                                
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="record _3">
                                        <div class="bar _3" style="height:<?php echo $stats[$event['eid']]->nbGuestsAnswerNone!=0 ? intval(($stats[$event['eid']]->nbGuestsAnswerNone/$stats[$event['eid']]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                            <?php if ($stats[$event['eid']]->nbGuestsAnswerNone > 0): ?>
                                                
                                                    <a href="<?php echo $BASE . '/event/' . $event['eid'] . '/show/guests/answer/0'; ?>">
                                                        <span class="digits"><?php echo $stats[$event['eid']]->nbGuestsAnswerNone; ?></span>
                                                        <span class="legend"><?php echo $no_answer[$l]; ?></span>
                                                    </a>
                                                
                                                <?php else: ?>
                                                    <span class="digits">0</span>
                                                    <span class="legend"><?php echo $no_answer[$l]; ?></span>
                                                
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="record _4">
                                        <div class="bar _4" style="height:<?php echo $stats[$event['eid']]->nbGuestsAcc!=0 ? intval(($stats[$event['eid']]->nbGuestsAcc/$stats[$event['eid']]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                            <span class="digits"><?php echo $stats[$event['eid']]->nbGuestsAcc; ?></span>
                                            <span class="legend">Accomp.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="stats_presence col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                    <h4><?php echo $presence[$l]; ?> <i class="glyphicon glyphicon-flash"></i></h4>
                                    <div class="record first _1">
                                        <div class="bar _1" style="height:<?php echo $stats[$event['eid']]->nbGuestsPresence!=0 ? intval(($stats[$event['eid']]->nbGuestsPresence/$stats[$event['eid']]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                            <?php if ($event['fin'] < $date): ?>
                                                
                                                    <a href="<?php echo $BASE . '/event/' . $event['eid'] . '/show/guests/presence/1'; ?>">
                                                        <span class="digits"><?php echo $stats[$event['eid']]->nbGuestsPresence; ?></span>
                                                        <span class="legend"><?php echo $presents[$l]; ?></span>
                                                    </a>
                                                
                                                <?php else: ?>
                                                    <span class="digits">0</span>
                                                    <span class="legend"><?php echo $presents[$l]; ?></span>
                                                
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="record _2">
                                        <div class="bar _2" style="height:<?php echo intval($stats[$event['eid']]->nbGuestsAnswerYes - $stats[$event['eid']]->nbGuestsPresence)!=0 ? intval((($stats[$event['eid']]->nbGuestsTotal - $stats[$event['eid']]->nbGuestsPresence)/$stats[$event['eid']]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                            <?php if ($event['fin'] < $date): ?>
                                                
                                                    <a href="<?php echo $BASE . '/event/' . $event['eid'] . '/show/guests/presence/0'; ?>">
                                                        <span class="digits"><?php echo $stats[$event['eid']]->nbGuestsAnswerYes - $stats[$event['eid']]->nbGuestsPresence; ?></span>
                                                        <span class="legend"><?php echo $absents[$l]; ?></span>
                                                    </a>
                                                
                                                <?php else: ?>
                                                    <span class="digits">0</span>
                                                    <span class="legend"><?php echo $absents[$l]; ?></span>
                                                
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="record _5">
                                        <div class="bar _5" style="height:<?php echo $stats[$event['eid']]->nbGuestsAccPresenceYes!=0 ? intval(($stats[$event['eid']]->nbGuestsAccPresenceYes/$stats[$event['eid']]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                            <span class="digits"><?php echo $stats[$event['eid']]->nbGuestsAccPresenceYes; ?></span>
                                            <span class="legend">Accomp.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <?php if ((isset($allMyEvents['invited']) && in_array($event['eid'], $allMyEvents['invited']))): ?>

                            <div class="body-invites">
                                <small><?php echo $allMyEvents['invitations'][$event['eid']]['hostid'] == $SESSION['uid']  ? '<em>'.$invited_myself[$l].'</em>' : $invited_by[$l]. ' <em>'.$allMyEvents['invitations'][$event['eid']]['hostname'].'</em>'; ?></small>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

                <div class="panel-footer padnon">

                    <?php if ((isset($allMyEvents['hosted']) && in_array($event['eid'], $allMyEvents['hosted'])) || $SESSION['lvl']==1): ?>
                        <?php if ($SESSION['c']==0): ?>
                            <?php else: ?>
                                <div class="row marnon invits<?php echo $SESSION['lvl']==3?' botlinks':''; ?>">

                                    <?php if ($SESSION['lvl']<=2): ?>
                                        
                                            <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show/hosts'; ?>" class="col-xs-6 borderedrig borderedbot">
                                                <?php if ($event['debut'] > $date): ?>
                                                    
                                                        <?php echo '<span class="hidden-xs">'.$manage[$l].'</span> '.$_hosts[$l]; ?> <small class="baccolgreyd borrad10 pad2-5 nocolr"><?php echo $stats[$event['eid']]->nbHostsTotal; ?></small>
                                                    
                                                    <?php else: ?>
                                                        <?php echo '<span class="hidden-xs">'.$watch[$l].'</span> '.$_hosts[$l]; ?> <small class="baccolgreyd borrad10 pad2-5 nocolr"><?php echo $stats[$event['eid']]->nbHostsTotal; ?></small>
                                                    
                                                <?php endif; ?>
                                            </a>
                                        
                                    <?php endif; ?>

                                    <?php if (($event['status']==1 && $SESSION['lvl']<=3) || (( $event['status']==2 || $event['status']==3 ) && $SESSION['lvl'] <= 2 )): ?>
                                        
                                            <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show/guests'; ?>" class="<?php echo $SESSION['lvl']==3 ? 'col-xs-12'.(isset($allMyEvents['invited']) && in_array($event['eid'], $allMyEvents['invited']) && $allMyEvents['invitations'][$event['eid']]['hostid'] != $SESSION['uid'] ?' borderedbot':' botlefriglink') : 'col-xs-6 borderedbot'; ?>">
                                                <?php if ($event['debut'] > $date): ?>
                                                    
                                                        <?php echo $SESSION['lvl']==1 ? '<span class="hidden-xs">'.$manage[$l].'</span> '.$_guests[$l] : '<span class="hidden-xs">'.$manage[$l].'</span> <span>'.$_my_guests[$l].'</span>';; ?>
                                                        <small class="baccolgreyd borrad10 pad2-5 nocolr"><?php echo $stats[$event['eid']]->nbGuestsTotal; ?></small>
                                                    
                                                    <?php else: ?>
                                                        <?php echo $SESSION['lvl']==1 ? '<span class="hidden-xs">'.$watch[$l].'</span> '.$_guests[$l] : '<span class="hidden-xs">'.$watch[$l].'</span> <span>'.$_my_guests[$l].'</span>';; ?>
                                                        <small class="baccolgreyd borrad10 pad2-5 nocolr"><?php echo $stats[$event['eid']]->nbGuestsTotal; ?></small>
                                                    
                                                <?php endif; ?>
                                            </a>
                                        
                                        <?php else: ?>
                                            <span class="<?php echo $SESSION['lvl']==3 ? 'col-xs-12'.(isset($allMyEvents['invited']) && in_array($event['eid'], $allMyEvents['invited']) && $allMyEvents['invitations'][$event['eid']]['hostid'] != $SESSION['uid'] ?' borderedbot':' botlefriglink') : 'col-xs-6 borderedrig borderedbot'; ?> void deactivated">
                                                <?php if ($event['debut'] > $date): ?>
                                                    
                                                        <?php echo $SESSION['lvl']==1 ? '<span class="hidden-xs">'.$manage[$l].'</span> '.$_guests[$l] : '<span class="hidden-xs">'.$manage[$l].'</span> <span>'.$_my_guests[$l].'</span>';; ?>
                                                    
                                                    <?php else: ?>
                                                        <?php echo $SESSION['lvl']==1 ? '<span class="hidden-xs">'.$watch[$l].'</span> '.$_guests[$l] : '<span class="hidden-xs">'.$watch[$l].'</span> <span>'.$_my_guests[$l].'</span>';; ?>
                                                    
                                                <?php endif; ?>
                                            </span>
                                        
                                    <?php endif; ?>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($SESSION['lvl']<=2): ?>
                            
                                <div class="row marnon padnon botlinks">
                                    <?php if (($event['status']==2 && $SESSION['lvl']==1) || ($event['status']!=2 && ($SESSION['lvl']==1 || $SESSION['lvl']==2))): ?>
                                        
                                            <?php if ($event['status']==3): ?>
                                                
                                                    <span class="<?php echo $SESSION['lvl']==1 && $SESSION['c']==0 ? 'col-xs-4' : 'col-xs-6'; ?> borderedrig void deactivated botleflink">
                                                        <?php echo $update[$l]; ?>
                                                    </span>
                                                
                                                <?php else: ?>
                                                    <a href="<?php echo $BASE.'/'; ?>event/<?php echo $event['eid']; ?>/update" class="<?php echo $SESSION['lvl']==1 && $SESSION['c']==0 ? 'col-xs-4' : 'col-xs-6'; ?> borderedrig botleflink">
                                                        <?php echo $update[$l]; ?>
                                                    </a>
                                                
                                            <?php endif; ?>

                                            <?php if ($SESSION['lvl']==1 && $SESSION['c']==0): ?>
                                                <span class="col-xs-4 borderedrig void curpoi" data-toggle="modal" data-target="#myModal-delete-<?php echo $event['eid']; ?>"><?php echo $delete[$l]; ?></span>
                                                <div class="modal fade bs-example-modal-sm" id="myModal-delete-<?php echo $event['eid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $event['eid']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bornon">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title" id="myModalLabel-<?php echo $event['eid']; ?>">
                                                                    <i class="glyphicon glyphicon-remove-circle"></i> <?php echo ucfirst($delete[$l]); ?> <?php echo strtolower($the_event[$l]); ?> <b class="fonstyita"><?php echo $event['nom']; ?></b>
                                                                </h4>
                                                            </div>
                                                            <form action="<?php echo $BASE.'/event/delete'; ?>" method="post" class="form-delete" role="form" id="form-delete-<?php echo $event['eid']; ?>">
                                                                <div class="modal-footer bornon padtopnon martopnon">
                                                                    <input type="hidden" name="del" value="d" />
                                                                    <input type="hidden" name="eid" value="<?php echo $event['eid']; ?>">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                        <?php echo $cancel[$l]; ?>
                                                                    </button>
                                                                    <button class="btn btn-danger" type="submit">
                                                                        <?php echo $event_delete_confirm[$l]; ?>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($event['status']==3): ?>
                                                
                                                    <span class="<?php echo $SESSION['lvl']==1 && $SESSION['c']==0 ? 'col-xs-4' : 'col-xs-6'; ?> void curpoi botriglink" data-toggle="modal" data-target="#myModal-activate-<?php echo $event['eid']; ?>"><?php echo $activate[$l]; ?></span>
                                                    <div class="modal fade bs-example-modal-sm" id="myModal-activate-<?php echo $event['eid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $event['eid']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bornon">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title" id="myModalLabel-<?php echo $event['eid']; ?>">
                                                                        <i class="glyphicon glyphicon-ok-circle"></i> <?php echo $activate[$l]; ?> <b class="fonstyita"><?php echo $event['nom']; ?></b>
                                                                    </h4>
                                                                </div>
                                                                <form action="<?php echo $BASE.'/event/activate'; ?>" method="post" class="form-activate" role="form" id="form-activate-<?php echo $event['eid']; ?>">
                                                                    <div class="modal-footer bornon padtopnon martopnon">
                                                                        <input type="hidden" name="act" value="act" />
                                                                        <input type="hidden" name="eid" value="<?php echo $event['eid']; ?>">
                                                                        <input type="hidden" name="status" value="1" />
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                            <?php echo $cancel[$l]; ?>
                                                                        </button>
                                                                        <button class="btn btn-success" type="submit">
                                                                            <?php echo $event_activate_confirm[$l]; ?>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                <?php else: ?>
                                                    <span class="<?php echo $SESSION['lvl']==1 && $SESSION['c']==0 ? 'col-xs-4' : 'col-xs-6'; ?> void curpoi botriglink" data-toggle="modal" data-target="#myModal-deactivate-<?php echo $event['eid']; ?>"><?php echo $deactivate[$l]; ?></span>
                                                    <div class="modal fade bs-example-modal-sm" id="myModal-deactivate-<?php echo $event['eid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $event['eid']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bornon">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title" id="myModalLabel-<?php echo $event['eid']; ?>">
                                                                        <i class="glyphicon glyphicon-ban-circle"></i> <?php echo $deactivate[$l]; ?> <b class="fonstyita"><?php echo $event['nom']; ?></b>
                                                                    </h4>
                                                                </div>
                                                                <form action="<?php echo $BASE.'/event/deactivate'; ?>" method="post" class="form-deactivate" role="form" id="form-deactivate-<?php echo $event['eid']; ?>">
                                                                    <div class="modal-footer bornon padtopnon martopnon">
                                                                        <input type="hidden" name="dea" value="dea" />
                                                                        <input type="hidden" name="eid" value="<?php echo $event['eid']; ?>">
                                                                        <input type="hidden" name="status" value="3" />
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                                                            <?php echo $cancel[$l]; ?>
                                                                        </button>
                                                                        <button class="btn btn-warning" type="submit">
                                                                            <?php echo $event_deactivate_confirm[$l]; ?>
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                            <?php endif; ?>
                                        
                                        <?php else: ?>
                                            <span class="<?php echo $SESSION['lvl']==1 && $SESSION['c']==0 ? 'col-xs-4' : 'col-xs-6'; ?> borderedrig deactivated void botleflink" title="<?php echo $nocredential_rights[$l]; ?>">
                                                <?php echo $update[$l]; ?>
                                            </span>

                                            <?php if ($SESSION['lvl']==1 && $SESSION['c']==0): ?>
                                                <span class="col-xs-4 borderedrig deactivated void" title="<?php echo $nocredential_rights[$l]; ?>">
                                                    <?php echo $delete[$l]; ?>
                                                </span>
                                            <?php endif; ?>

                                            <span class="<?php echo $SESSION['lvl']==1 && $SESSION['c']==0 ? 'col-xs-4' : 'col-xs-6'; ?> deactivated void botriglink" title="<?php echo $nocredential_rights[$l]; ?>">
                                                <?php echo $deactivate[$l]; ?>
                                            </span>
                                        
                                    <?php endif; ?>
                                </div>
                            
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if ((isset($allMyEvents['invited']) && in_array($event['eid'], $allMyEvents['invited']) && $allMyEvents['invitations'][$event['eid']]['hostid'] != $SESSION['uid'])): ?>

                        <div class="row marnon padnon botlinks">
                            <?php if ($event['limitB'] > $date): ?>

                                
                                    <a class="col-xs-4 void default botleflink borderedrig" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $allMyEvents['invitations'][$event['eid']]['invitationid']; ?>/0/events">
                                        <span class="verb<?php echo ($allMyEvents['invitations'][$event['eid']]['answer']==0 ? ' glyphicon glyphicon-ok':''); ?>">
                                            <span class="legend1 textracap">
                                                <?php echo $no_answer[$l]; ?>
                                            </span>
                                            <span class="legend2 textracap">
                                                <?php echo $waiting[$l]; ?>
                                            </span>
                                        </span>
                                    </a>

                                    <a class="col-xs-4 void default borderedrig" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $allMyEvents['invitations'][$event['eid']]['invitationid']; ?>/1/events">
                                        <span class="verb<?php echo ($allMyEvents['invitations'][$event['eid']]['answer']==1 ? ' glyphicon glyphicon-ok':''); ?>">
                                            <span class="legend1 textracap">
                                                <?php echo $guest_answer_yes[$l]; ?>
                                            </span>
                                            <span class="legend2 textracap">
                                                <?php echo $attending[$l]; ?>
                                            </span>
                                        </span>
                                    </a>

                                    <a class="col-xs-4 void default botriglink" role="menuitem" tabindex="-1" href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/guest/<?php echo $allMyEvents['invitations'][$event['eid']]['invitationid']; ?>/2/events">
                                        <span class="verb<?php echo ($allMyEvents['invitations'][$event['eid']]['answer']==2 ? ' glyphicon glyphicon-ok':''); ?>">
                                            <span class="legend1 textracap">
                                                <?php echo $guest_answer_no[$l]; ?>
                                            </span>
                                            <span class="legend2 textracap">
                                                <?php echo $missing[$l]; ?>
                                            </span>
                                        </span>
                                    </a>

                                
                                <?php else: ?>
                                    <span class="botlefriglink disblo deactivated void" title="<?php echo $event_over[$l]; ?>">
                                         <?php echo $allMyEvents['invitations'][$event['eid']]['answer']==0 ? [$$INVANSWER['0']]['0'][$l] : [$$INVANSWERED[$allMyEvents['invitations'][$event['eid']]['answer']]]['0'][$l]; ?>
                                    </span>
                                
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>