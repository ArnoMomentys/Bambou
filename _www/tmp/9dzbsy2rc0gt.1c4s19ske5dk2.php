<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 sidebar">
    <div class="logo logo-md hidden-xs pull-left">
        <a href="/" class="texalicen widful">
            <img src="<?php echo $BASE.'/'.$IMG; ?>b-md.png" class="img-responsive disinl" />
        </a>
    </div>
    <div class="logo logo-sm visible-xs pull-left">
        <a href="/" class="disblo pull-left texalicen widful">
            <img src="<?php echo $BASE.'/'.$IMG; ?>b-sm.png" class="img-responsive disinl" />
        </a>
    </div>
    <ul class="nav nav-sidebar pull-left">

        <!-- user inputs -->
        <li class="welcome texalicen<?php echo (preg_match('/(profil)|(user)/i', $PATTERN) && isset($PARAMS['uid']) ? ($SESSION['uid']==$PARAMS['uid'] ? ' active' : '') : ''); ?>">
            <a class="disinl" href="/user/<?php echo $SESSION['uid']; ?>/show">
                <i class="glyphicon glyphicon-user"></i>  <span class="hidden-xs nom textraupp"><?php echo $SESSION['profile']['nom']; ?></span> <span class="hidden-xs prenom textracap"><?php echo $SESSION['profile']['prenom']; ?></span>
                <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $user_profile[$l]; ?> de <?php echo $SESSION['profile']['nom']; ?> <?php echo $SESSION['profile']['prenom']; ?>">&nbsp;</small><br>
                <small class="hidden-xs"><?php echo $goto_profile[$l]; ?></small>
            </a>
        </li>

        <!-- admin home -->
        <?php if ($SESSION['lvl']<=2): ?>
            
                <?php if ($PATTERN!='/'): ?>
                    <li class="texalicen cat">
                        <a href="<?php echo $BASE.'/'; ?>" class="texalicen">
                            <i class="glyphicon glyphicon-home glyphicon-black"></i>
                            <span class="texte disblo hidden-xs">
                                <?php echo $home_page[$l]; ?>
                            </span>
                            <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $home_page[$l]; ?>">&nbsp;</small>
                        </a>
                    </li>
                <?php endif; ?>
            
        <?php endif; ?>

        <!-- events list -->
        <li class="texalicen cat<?php echo preg_match('/event/i', $PATTERN) ? ' active': ''; ?>">
            <a href="<?php echo $BASE.'/events'; ?>" class="texalicen">
                <i class="glyphicon glyphicon-time glyphicon-black"></i>
                <span class="texte disblo hidden-xs">
                    <?php echo $events_list[$l]; ?>
                </span>
                <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $events_list[$l]; ?>">&nbsp;</small>
            </a>
        </li>

        <!-- event create | update -->
        <?php if ($SESSION['lvl']<=2): ?>
        

            <!-- event create -->
            <?php if (preg_match('/\/events/i',$PATTERN)): ?>
                <li class="texalicen inner<?php echo $PATTERN=='/event/create' ?' subactive':''; ?>">
                    <a href="<?php echo $PATTERN=='/event/create' ? 'javascript:void()' : $BASE.'/event/create'; ?>" class="texalicen">
                        <i class="glyphicon glyphicon-record glyphicon-black"></i>
                        <span class="texte disblo hidden-xs">
                            <?php echo $event_create[$l]; ?>
                        </span>
                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_create[$l]; ?>">&nbsp;</small>
                    </a>
                </li>
            <?php endif; ?>

            <!-- event update -->
            <?php if (preg_match('/\/event\/u/i',$PATTERN)): ?>
                <li class="texalicen inner subactive">
                    <a href="javascript:void(0);" class="texalicen">
                        <i class="glyphicon glyphicon-refresh glyphicon-black"></i>
                        <span class="texte disblo hidden-xs">
                            <?php echo $event_update[$l]; ?>
                        </span>
                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_update[$l]; ?>">&nbsp;</small>
                    </a>
                </li>
            <?php endif; ?>
        
        <?php endif; ?>

        <!-- event guest,host add | import | export -->
        <?php if ($SESSION['lvl']<=3): ?>
            <?php if (strstr($PATTERN,'eid') || strstr($PATTERN,'eventid')): ?>
                <?php if (( strstr($PATTERN,'guest') || (strstr($PATTERN,'status') && $PARAMS['status']=='guests') ) || $SESSION['lvl']==3): ?>
                    <?php if (!isset($isold) || $isold==false): ?>
                        

                            <!-- event guests list -->
                            <li class="texalicen inner<?php echo preg_match('/(show\/guests)|(add\/acc)|(add\/repr)/',$PATTERN) ? ' subactive': ''; ?>">
                                <a href="<?php echo '/event/'.$PARAMS['eid'].'/show/guests'; ?>" class="veralimid texdecnon">
                                    <i class="glyphicon glyphicon-align-left glyphicon-black"></i>
                                    <span class="texte disblo hidden-xs"><?php echo $event_guest_list[$l]; ?></span>
                                    <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_guest_list[$l]; ?>">&nbsp;</small>
                                </a>
                            </li>

                            <?php if ($SESSION['c'] != 0): ?>
                                <!-- event guest add -->
                                <li class="texalicen inner<?php echo strstr($PATTERN, 'add/guest')==true ? ' subactive': ''; ?>">
                                    <a href="<?php echo '/event/'.$PARAMS['eid'].'/add/guest'; ?>" class="veralimid texdecnon">
                                        <i class="glyphicon glyphicon-plus-sign glyphicon-black"></i>
                                        <span class="texte disblo hidden-xs"><?php echo $event_guest_add[$l]; ?></span>
                                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_host_add[$l]; ?>">&nbsp;</small>
                                    </a>
                                </li>

                                <!-- event guest add new -->
                                <li class="texalicen inner<?php echo strstr($PATTERN, 'add/new/guest')==true ? ' subactive': ''; ?>">
                                    <a href="<?php echo '/event/'.$PARAMS['eid'].'/add/new/guest'; ?>" class="veralimid texdecnon">
                                        <i class="glyphicon glyphicon-star glyphicon-black"></i>
                                        <span class="texte disblo hidden-xs"><?php echo $event_guest_add_new[$l]; ?></span>
                                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_guest_add[$l]; ?>">&nbsp;</small>
                                    </a>
                                </li>

                                <!-- event guest import -->
                                <li class="texalicen inner<?php echo strstr($PARAMS['0'], 'import/guests') ? ' subactive': ''; ?>">
                                    <a href="<?php echo '/event/'.$PARAMS['eid'].'/import/guests'; ?>" class="veralimid texdecnon">
                                        <i class="glyphicon glyphicon-import glyphicon-black"></i>
                                        <span class="texte disblo hidden-xs"><?php echo $event_guests_import[$l]; ?></span>
                                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_guests_import[$l]; ?>">&nbsp;</small>
                                    </a>
                                </li>
                            <?php endif; ?>
                        
                    <?php endif; ?>
                    <?php if ($SESSION['c'] > 0 && $SESSION['lvl'] <= 3): ?>
                        <!-- event guest export -->
                        <li class="texalicen inner<?php echo strstr($PARAMS['0'], 'export/guests') ? ' subactive': ''; ?>">
                            <span class="export veralimid texalicen curpoi  disinlblo" data-toggle="modal" data-target="#myModal-exportguests-<?php echo $PARAMS['eid']; ?>">
                                <i class="glyphicon glyphicon-export glyphicon-black"></i>
                                <span class="texte disblo hidden-xs"><?php echo $event_guests_export[$l]; ?></span>
                                <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_guests_export[$l]; ?>">&nbsp;</small>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (strstr($PATTERN,'host') || (strstr($PATTERN,'status') && $PARAMS['status']=='hosts')): ?>
                    <?php if (!isset($isold) || $isold==false): ?>
                        

                            <!-- event hosts list -->
                            <li class="texalicen inner<?php echo strstr($PATTERN, '/show/hosts')==true ? ' subactive': ''; ?>">
                                <a href="<?php echo '/event/'.$PARAMS['eid'].'/show/hosts'; ?>" class="veralimid texdecnon">
                                    <i class="glyphicon glyphicon-align-left glyphicon-black"></i>
                                    <span class="texte disblo hidden-xs"><?php echo $event_host_list[$l]; ?></span>
                                    <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_host_list[$l]; ?>">&nbsp;</small>
                                </a>
                            </li>

                            <?php if ($SESSION['c'] != 0): ?>
                                <!-- event host add -->
                                <li class="texalicen inner<?php echo strstr($PATTERN, 'add/host')==true ? ' subactive': ''; ?>">
                                    <a href="<?php echo '/event/'.$PARAMS['eid'].'/add/host'; ?>" class="veralimid texdecnon">
                                        <i class="glyphicon glyphicon-plus-sign glyphicon-black"></i>
                                        <span class="texte disblo hidden-xs"><?php echo $event_host_add[$l]; ?></span>
                                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_host_add[$l]; ?>">&nbsp;</small>
                                    </a>
                                </li>

                                <!-- event host add new -->
                                <li class="texalicen inner<?php echo strstr($PATTERN, 'add/new/host')==true ? ' subactive': ''; ?>">
                                    <a href="<?php echo '/event/'.$PARAMS['eid'].'/add/new/host'; ?>" class="veralimid texdecnon">
                                        <i class="glyphicon glyphicon-star glyphicon-black"></i>
                                        <span class="texte disblo hidden-xs"><?php echo $event_host_add_new[$l]; ?></span>
                                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_host_add_new[$l]; ?>">&nbsp;</small>
                                    </a>
                                </li>

                                <!-- event host import -->
                                <li class="texalicen inner<?php echo strstr($PARAMS['0'], 'import/hosts') ? ' subactive': ''; ?>">
                                    <a href="<?php echo '/event/'.$PARAMS['eid'].'/import/hosts'; ?>" class="veralimid texdecnon">
                                        <i class="glyphicon glyphicon-import glyphicon-black"></i>
                                        <span class="texte disblo hidden-xs"><?php echo $event_hosts_import[$l]; ?></span>
                                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_hosts_import[$l]; ?>">&nbsp;</small>
                                    </a>
                                </li>
                            <?php endif; ?>
                        
                        <?php else: ?>

                        
                    <?php endif; ?>
                    <?php if ($SESSION['c'] > 0 && $SESSION['lvl'] == 1): ?>
                        <!-- event host export -->
                        <li class="texalicen inner<?php echo strstr($PARAMS['0'], 'export/hosts') ? ' subactive': ''; ?>">
                            <span class="export veralimid texalicen curpoi disinlblo" data-toggle="modal" data-target="#myModal-exporthosts-<?php echo $PARAMS['eid']; ?>">
                                <i class="glyphicon glyphicon-export glyphicon-black"></i>
                                <span class="texte disblo hidden-xs"><?php echo $event_hosts_export[$l]; ?></span>
                                <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $event_hosts_export[$l]; ?>">&nbsp;</small>
                            </span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>

        <!-- user list -->
        <?php if ($SESSION['lvl']<=2): ?>
            <li class="texalicen cat<?php echo (preg_match('/(users\/list)|(uid\/show)|(uid\/update)/i', $PATTERN) && ((isset($PARAMS['uid']) && $SESSION['uid']!=$PARAMS['uid']) || !isset($PARAMS['uid'])) ? ' active' : ''); ?>">
                <a href="<?php echo $BASE.'/users/list'; ?>" class="texalicen">
                    <i class="glyphicon glyphicon-th-list glyphicon-black"></i>
                    <span class="texte disblo hidden-xs">
                        <?php echo $user_list[$l]; ?>
                    </span>
                    <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $user_list[$l]; ?>">&nbsp;</small>
                </a>
            </li>

            <!-- groups -->
            <li class="texalicen cat<?php echo preg_match('/group/i',$PATTERN) ?' active':''; ?>">
                <a href="<?php echo $BASE.'/groups'; ?>" class="texalicen">
                    <i class="glyphicon glyphicon-list-alt glyphicon-black"></i>
                    <span class="texte disblo hidden-xs">
                        <?php echo ucfirst($group_list[$l]); ?>
                    </span>
                    <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $group_list[$l]; ?>">&nbsp;</small>
                </a>
            </li>

            <!-- group create -->
            <?php if (preg_match('/\/group/i',$PATTERN)): ?>
                <li class="texalicen inner<?php echo $PATTERN=='/group/create' ? ' subactive': ''; ?>">
                    <a href="<?php echo $PATTERN=='/group/create' ? 'javascript:void()' : $BASE.'/group/create'; ?>" class="texalicen">
                        <i class="glyphicon glyphicon-plus-sign glyphicon-black"></i>
                        <span class="texte disblo hidden-xs">
                            <?php echo $group_create[$l]; ?>
                        </span>
                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $group_create[$l]; ?>">&nbsp;</small>
                    </a>
                </li>
            <?php endif; ?>

            <!-- group update -->
            <?php if (preg_match('/\/group\/(.*)\/update/i',$PATTERN)): ?>
                <li class="texalicen inner subactive">
                    <a href="javascript:void(0);" class="texalicen">
                        <i class="glyphicon glyphicon-refresh glyphicon-black"></i>
                        <span class="texte disblo hidden-xs">
                            <?php echo $group_update[$l]; ?>
                        </span>
                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $group_update[$l]; ?>">&nbsp;</small>
                    </a>
                </li>
            <?php endif; ?>

            <!-- group show -->
            <?php if (preg_match('/group\/(.*)\/(add|show)/i',$PATTERN)): ?>
                <li class="texalicen inner<?php echo preg_match('/group\/(.*)\/add/i',$PATTERN) ? ' subactive' : ''; ?>">
                    <a href="<?php echo preg_match('/group\/(.*)\/show/i',$PARAMS['0']) ? preg_replace('/(show.*)/i','add/user', $PARAMS['0']) : $PARAMS['0']; ?>" class="texalicen">
                        <i class="glyphicon glyphicon-list glyphicon-black"></i>
                        <span class="texte disblo hidden-xs">
                            <?php echo $add_group_member[$l]; ?>
                        </span>
                        <small class="visible-xs heiful widful has-tip-right posabs postop0 poslef0" title="<?php echo $add_group_member[$l]; ?>">&nbsp;</small>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</div>