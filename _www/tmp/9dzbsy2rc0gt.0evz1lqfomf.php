<h2 class="page-heading"><?php echo $page_header; ?></h2>

<div class="panel-events marlefrigaut">
    <div class="col-md-4 col-lg-4 panel-grid padrignon">
        <div class="panel panel-default events-all">
            <div class="panel-body events">
                <a href="<?php echo $BASE.'/events'; ?>">
                    <span class="count"><?php echo $count_total_events; ?></span>
                    <span class="text"><?php echo $total_events[$l]; ?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4 panel-grid padrignon">
        <div class="panel panel-default events events-active">
            <div class="panel-body">
                <span class="count"><?php echo $count_active_events; ?></span>
                <span class="text"><?php echo $active_events[$l]; ?></span class="">
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4 panel-grid events-archived">
        <div class="panel panel-default events events-archived">
            <div class="panel-body">
                <span class="count"><?php echo str_pad($count_archived_events,2,"0",STR_PAD_LEFT); ?></span>
                <span class="text"><?php echo $archived_events[$l]; ?></span>
            </div>
        </div>
    </div>
</div>

<div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><br><br></div>

<div class="panel-events-bottom widful clelef">
    <div class="col-md-4 col-lg-4 panel-grid panel-last-event padrignon">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>
                    <?php echo $last_event[$l]; ?>
                </h4>
                <div>
                    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>">
                        <?php echo $event['nom']; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10 fonsiznor"></i>
                    </a>
                    <?php if ($event['status']==2): ?>
                        <i class="pull-right glyphicon glyphicon-ban-circle" title="En maintenance"></i>
                    <?php endif; ?>
                </div>
                <small><?php echo $event['lieu']; ?></small>
                <br>
                <small><?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?></small>
            </div>
            <div class="panel-body">
                <div class="body-heading stats">
                    <?php echo $stats_last_active_event[0]->nbInvValidated; ?> <?php echo $invitations[$l]; ?> <?php echo $validated_fem_plur[$l]; ?> sur <?php echo $stats_last_active_event[0]->nbGuestsTotal; ?>
                </div>

                <div class="body-bars row-fluid stats pull-left widful l1">
                    <div class="stats_response col-xs-7 col-sm-7 col-md-7 col-lg-7 padnon">
                        <h4><?php echo $answers[$l]; ?> <i class="glyphicon glyphicon-bookmark"></i></h4>
                        <div class="record first _1">
                            <div class="bar _1" style="height:<?php echo $stats_last_active_event[0]->nbGuestsAnswerYes!=0 ? intval(( $stats_last_active_event[0]->nbGuestsAnswerYes/$stats_last_active_event[0]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                <span class="digits"><?php echo $stats_last_active_event[0]->nbGuestsAnswerYes; ?></span>
                                <span class="legend"><?php echo $presents[$l]; ?></span>
                            </div>
                        </div>
                        <div class="record _2">
                            <div class="bar _2" style="height:<?php echo $stats_last_active_event[0]->nbGuestsAnswerNo!=0 ? intval(($stats_last_active_event[0]->nbGuestsAnswerNo/$stats_last_active_event[0]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                <span class="digits"><?php echo $stats_last_active_event[0]->nbGuestsAnswerNo; ?></span>
                                <span class="legend"><?php echo $absents[$l]; ?></span>
                            </div>
                        </div>
                        <div class="record _3">
                            <div class="bar _3" style="height:<?php echo $stats_last_active_event[0]->nbGuestsAnswerNone!=0 ? intval(($stats_last_active_event[0]->nbGuestsAnswerNone/$stats_last_active_event[0]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                <span class="digits"><?php echo $stats_last_active_event[0]->nbGuestsAnswerNone; ?></span>
                                <span class="legend"><?php echo $no_answer[$l]; ?></span>
                            </div>
                        </div>
                        <div class="record _4">
                            <div class="bar _4" style="height:<?php echo $stats_last_active_event[0]->nbGuestsAcc!=0 ? intval(($stats_last_active_event[0]->nbGuestsAcc/$stats_last_active_event[0]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                <span class="digits"><?php echo $stats_last_active_event[0]->nbGuestsAcc; ?></span>
                                <span class="legend">Accomp.</span>
                            </div>
                        </div>
                    </div>
                    <div class="stats_presence col-xs-5 col-sm-5 col-md-5 col-lg-5">
                        <h4><?php echo $presences[$l]; ?> <i class="glyphicon glyphicon-flash"></i></h4>
                        <div class="record first _1">
                            <div class="bar _1" style="height:<?php echo $stats_last_active_event[0]->nbGuestsPresence!=0 ? intval(($stats_last_active_event[0]->nbGuestsPresence/$stats_last_active_event[0]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                <span class="digits"><?php echo $stats_last_active_event[0]->nbGuestsPresence; ?></span>
                                <span class="legend"><?php echo $presents[$l]; ?></span>
                            </div>
                        </div>
                        <div class="record _2">
                            <div class="bar _2" style="height:<?php echo intval($stats_last_active_event[0]->nbGuestsAnswerYes - $stats_last_active_event[0]->nbGuestsPresence)!=0 ? intval((($stats_last_active_event[0]->nbGuestsTotal - $stats_last_active_event[0]->nbGuestsPresence)/$stats_last_active_event[0]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                <span class="digits"><?php echo ( $event['fin'] > $date ? 0 : $stats_last_active_event[0]->nbGuestsAnswerYes - $stats_last_active_event[0]->nbGuestsPresence ); ?></span>
                                <span class="legend"><?php echo $absents[$l]; ?></span>
                            </div>
                        </div>
                        <div class="record _5">
                            <div class="bar _5" style="height:<?php echo $stats_last_active_event[0]->nbGuestsAccPresenceYes!=0 ? intval(($stats_last_active_event[0]->nbGuestsAccPresenceYes/$stats_last_active_event[0]->nbGuestsTotal)*100) : 1; ?>% !important;">
                                <span class="digits"><?php echo $stats_last_active_event[0]->nbGuestsAccPresenceYes; ?></span>
                                <span class="legend">Accomp.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer padnon">
                <div class="row marnon">
                    <a href="<?php echo $BASE.'/event/'.$event['eid'].'/show'; ?>" class="col-xs-12">
                        <?php echo $open_event_details[$l]; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-4 panel-grid panel-logs padrignon">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>
                    <?php echo $last_activities[$l]; ?>
                </h4>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span><?php echo $last_logged_user[$l]; ?></span>
                        <p>
                            <a href="<?php echo $BASE.'/user/'.$last_logged_user_uid.'/show'; ?>">
                                <?php echo $last_logged_user_nom_prenom; ?>
                            </a>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <span><?php echo $last_read_event[$l]; ?></span>
                        <p>
                            <a href="<?php echo $BASE.'/event/'.$last_read_event_eid.'/show'; ?>">
                                <?php echo $last_read_event_nom; ?>
                            </a>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <span><?php echo $last_added_guest[$l]; ?></span>
                        <p>
                            <a href="<?php echo $BASE.'/user/'.$last_guest_added_uid.'/show'; ?>">
                                <?php echo $last_guest_added_nom_prenom; ?>
                            </a>
                            <a href="<?php echo $BASE.'/event/'.$last_guest_added_event_eid.'/show'; ?>">
                                <?php echo $last_guest_added_event_nom; ?>
                            </a>

                        </p>
                    </li>
                    <li class="list-group-item">
                        <span><?php echo $last_added_user[$l]; ?></span>
                        <p>
                            <a href="<?php echo $BASE.'/user/'.$last_user_added_uid.'/show'; ?>">
                                <?php echo $last_user_added_nom_prenom; ?>
                            </a>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4 panel-grid panel-chart padrignon">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>
                    <?php echo $user_list[$l]; ?>
                </h4>
            </div>
            <div class="panel-body texalicen">
                <div class="chart-wrapper">
                    <div id="donutChart"></div>
                    <div id="donutDesc">
                        <span><?php echo $count_total_groups; ?></span>
                        <p><?php echo $group_list[$l]; ?></p>
                        <h3 class="title"></h3>
                        <div class="desc"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
