<h2 class="page-heading marbotnon">
	<?php echo $page_header; ?>&nbsp;<i class="glyphicon glyphicon-chevron-right padlef10"></i>
	</a>
</h2>
<small class="dimgra">
	<span class="textraupp"><?php echo $date_start[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['debut'])); ?>
</small>
<br>
<small class="dimgra">
	<span class="textraupp"><?php echo $limit_displayed[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['limitB'])); ?>
</small>
<br>
<small class="dimgra">
	<span class="textraupp"><?php echo $date_end[$l]; ?></span> : <?php echo strftime("%A %d %B %G", strtotime($event['fin'])); ?>
</small>
<br>
<small class="dimgra">
	<span class="textraupp"><?php echo $event_place[$l]; ?></span> : <?php echo $event['lieu']; ?>
</small>
<br>
<small class="dimgra">
	<span class="textraupp"><?php echo $event_address[$l]; ?></span> : <?php echo $event['adresse']; ?>
</small>
<br>
<small class="dimgra">
	<span class="textraupp"><?php echo $event_town[$l]; ?></span> : <?php echo strtoupper($event['ville']); ?>
</small>
<br>
<small class="dimgra">
	<span class="textraupp"><?php echo $status[$l]; ?></span> : <?php echo [$$EVTSTATUS[$event['status']]]['0'][$l]; ?>
</small>
<br>

<div class="eventlinkslist padbot30 pull-left col-xs-12">
	<br>
	<?php if ($SESSION['lvl'] <= 2): ?>
		<div class="pull-left col-xs-12 row-fluid eventlinks">
			<?php if ($SESSION['c'] == 0): ?>
				
					<!-- 3 colonnes -->
					<!-- col 1 -->
					<?php if ($event['status']==3): ?>
						
							<span class="col-xs-3 has-check void deactivated">
				                <?php echo $update[$l]; ?>
				            </span>
						
						<?php else: ?>
							<a href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/update" class="col-xs-3 has-check void deactivated texdecnoni">
				                <?php echo $update[$l]; ?>
							</a>
						
					<?php endif; ?>

					<!-- col 2 -->
					<span class="col-xs-4 col-xs-offset-1 has-check void curpoi" data-toggle="modal" data-target="#myModal-delete-<?php echo $event['eid']; ?>">
						<?php echo $delete[$l]; ?>
					</span>
					<div class="modal fade bs-example-modal-sm" id="myModal-delete-<?php echo $event['eid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $event['eid']; ?>" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header bornon">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel-<?php echo $event['eid']; ?>">
										<i class="glyphicon glyphicon-remove-circle"></i> <?php echo $delete[$l]; ?> <b class="fonstyita"><?php echo $event['nom']; ?></b>
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

					<!-- col 3 -->
					<?php if ($event['status']==3): ?>
						
							<span class="col-xs-3 col-xs-offset-1 has-check void curpoi" data-toggle="modal" data-target="#myModal-activate-<?php echo $event['eid']; ?>">
								<?php echo $activate[$l]; ?>
							</span>
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
							<span class="col-xs-3 col-xs-offset-1 has-check void curpoi" data-toggle="modal" data-target="#myModal-deactivate-<?php echo $event['eid']; ?>">
								<?php echo $deactivate[$l]; ?>
							</span>
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
					<!-- 2 colonnes -->
					<!-- col 1 -->
					<?php if ($event['status']==3): ?>
						
							<span class="col-xs-5 has-check void deactivated">
				                <?php echo $update[$l]; ?>
				            </span>
						
						<?php else: ?>
							<a href="<?php echo $BASE; ?>/event/<?php echo $event['eid']; ?>/update" class="col-xs-5 has-check void deactivated texdecnoni">
				                <?php echo $update[$l]; ?>
							</a>
						
					<?php endif; ?>

					<!-- col 2 -->
					<?php if ($event['status']==3): ?>
						
							<span class="pull-right col-xs-5 has-check void curpoi" data-toggle="modal" data-target="#myModal-activate-<?php echo $event['eid']; ?>">
								<?php echo $activate[$l]; ?>
							</span>
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
							<span class="pull-right col-xs-5 has-check void curpoi" data-toggle="modal" data-target="#myModal-deactivate-<?php echo $event['eid']; ?>">
								<?php echo $deactivate[$l]; ?>
							</span>
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

				
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ($SESSION['lvl']==1): ?>
		<div class="pull-left col-xs-6 row-fluid eventlinks padtop20">
			<a href="<?php echo '/event/'.$event['eid'].'/show/hosts'; ?>" class="pull-left widful has-check void deactivated texdecnoni">
			    <?php echo $event_host_list[$l]; ?> <span class="borrad10 pad2-5 baccolgreye nocolr fonweinori"><?php echo $stats[$event['eid']]->nbHostsTotal; ?></span>
			</a>
			<?php if ($SESSION['c'] != 0): ?>
				<?php if ($isold===false): ?>
					
						<a href="<?php echo '/event/'.$event['eid'].'/add/host'; ?>" class="pull-left widful has-check void deactivated texdecnoni martop20">
				            <?php echo $event_host_add[$l]; ?>
						</a>
						<a href="<?php echo '/event/'.$event['eid'].'/add/new/host'; ?>" class="pull-left widful has-check void deactivated texdecnoni martop20">
				            <?php echo $event_host_add_new[$l]; ?>
						</a>
						<a href="<?php echo '/event/'.$event['eid'].'/import/hosts'; ?>" class="pull-left widful has-check void deactivated texdecnoni martop20">
				            <?php echo $event_hosts_import[$l]; ?>
						</a>
					
					<?php else: ?>
						<?php if ($stats[$event['eid']]->nbHostsTotal>0): ?>
							<span class="pull-left widful has-check void texdecnoni martop20 curpoi" data-toggle="modal" data-target="#myModal-exporthosts-<?php echo $event['eid']; ?>"><?php echo $event_hosts_export[$l]; ?></span>
							<?php echo $this->render('event/modal/exporthosts.htm',$this->mime,get_defined_vars()); ?>
						<?php endif; ?>
					
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ($SESSION['lvl'] <= 3): ?>
		<div class="pull-left <?php echo $SESSION['lvl']==3 ? 'col-xs-12' : 'col-xs-6'; ?> row-fluid eventlinks padtop20">
			<a href="<?php echo '/event/'.$event['eid'].'/show/guests'; ?>" class="pull-left widful has-check void deactivated texdecnoni">
			    <?php echo $event_guest_list[$l]; ?> <span class="borrad10 pad2-5 baccolgreye nocolr fonweinori"><?php echo $stats[$event['eid']]->nbGuestsTotal; ?></span>
			</a>
			<?php if ($SESSION['c'] != 0): ?>
				<?php if ($isold===false): ?>
					
						<a href="<?php echo '/event/'.$event['eid'].'/add/guest'; ?>" class="pull-left widful has-check void deactivated texdecnoni martop20">
				            <?php echo $event_guest_add[$l]; ?>
						</a>
						<a href="<?php echo '/event/'.$event['eid'].'/add/new/guest'; ?>" class="pull-left widful has-check void deactivated texdecnoni martop20">
				            <?php echo $event_guest_add_new[$l]; ?>
						</a>
						<a href="<?php echo '/event/'.$event['eid'].'/import/guests'; ?>" class="pull-left widful has-check void deactivated texdecnoni martop20">
				            <?php echo $event_guests_import[$l]; ?>
						</a>
					
					<?php else: ?>
						<?php if ($stats[$event['eid']]->nbGuestsTotal>0): ?>
							<span class="pull-left widful has-check void texdecnoni martop20 curpoi" data-toggle="modal" data-target="#myModal-exportguests-<?php echo $event['eid']; ?>"><?php echo $event_guests_export[$l]; ?></span>
							<?php echo $this->render('event/modal/exportguests.htm',$this->mime,get_defined_vars()); ?>
						<?php endif; ?>
					
				<?php endif; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>

<?php if ($SESSION['lvl'] <= 3): ?>
	<div class="pull-left padtop10 padrig10 padbot10 padlef10 col-xs-12 panel-event-home">
		<div class="panel panel-default pull-left widful">
			<div class="panel-body">
				<div class="body-heading stats texalicen">
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
			</div>
		</div>
	</div>
<?php endif; ?>


