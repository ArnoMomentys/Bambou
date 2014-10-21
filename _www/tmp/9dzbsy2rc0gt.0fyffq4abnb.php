				<?php if ($PATTERN=='/login'): ?>
				    <?php else: ?>
			        	<div class="rightbar">
					        <a class="logout btn btn-link" role="button" href="/logout">
					            <i class="glyphicon glyphicon-off glyphicon-white"></i>
					        </a>
				        	<a class="btn btn-link" role="button" href="/lang/fr"><small>FR</small></a>
				        	<a class="btn btn-link" role="button" href="/lang/en"><small>EN</small></a>
				        	<?php if (isset($SESSION['switch']) && $SESSION['switch']!==true): ?>
				        		<span class="btn btn-link curpoi has-tip-left" data-toggle="modal" data-target="#myModal-switchback" title="<?php echo $switch_back[$l]; ?>">
                                    <i class="glyphicon glyphicon-transfer"></i>
                                </span>
				        	<?php endif; ?>
				        	<div class="spinner dark" id="page-loading"></div>
				        </div>
				    
				<?php endif; ?>
			</div>
        	<?php if (isset($SESSION['switch']) && $SESSION['switch']!==true): ?>
				<!-- removefromguest.htm -->
                <div class="modal fade bs-example-modal-sm" id="myModal-switchback" tabindex="-1" role="dialog" aria
                -labelledby="myModalLabel-<?php echo $SESSION['uid']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bornon texalilef">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                                <h4 class="modal-title" id="myModalLabel-<?php echo $SESSION['uid']; ?>">
                                    <i class="glyphicon glyphicon-transfer"></i> <?php echo $confirm_switch_back[$l]; ?>
                                </h4>
                            </div>
                            <form action="<?php echo $BASE.'/switchback'; ?>" method="post" class="
                            form-switchback" role="form" id="form-switchback-<?php echo $SESSION['uid']; ?>">
                                <div class="modal-footer bornon padtopnon martopnon">
                                    <input type="hidden" name="swb" value="y" />
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <?php echo $cancel[$l]; ?>
                                    </button>

                                    <button id="modal-switchback-<?php echo $SESSION['uid']; ?>" class="btn btn-info" type="submit">
                                        <?php echo $validate[$l]; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
		</div>

	    <script src="<?php echo $BASE.'/'.$JS; ?>jquery.js"></script>
	    <script src="<?php echo $BASE.'/'.$JS; ?>bootstrap.min.js"></script>
    	<script src="<?php echo $BASE.'/'.$JS; ?>underscore-min.js"></script>
	    <?php if ($PATTERN=='/'): ?>
	    	<script src="<?php echo $BASE.'/'.$JS; ?>raphael.js"></script>
	    	<script src="<?php echo $BASE.'/'.$JS; ?>donut-chart.js"></script>
		    <script type="text/javascript">
		    	var cpie1 = <?php echo intval( ($count_total_groups_users / ($count_total_groups_users + $count_total_out_of_groups_users)) * 100 ); ?>,
		    		cpie2 =  <?php echo 100 - intval( ($count_total_groups_users / ($count_total_groups_users + $count_total_out_of_groups_users)) * 100 ); ?>,
		    		cdesc1 = "<?php echo $count_total_groups_users; ?> <?php echo $group_members[$l]; ?>",
		    		cdesc2 = "<?php echo $count_total_out_of_groups_users; ?> <?php echo $out_of_group_users[$l]; ?>";
			</script>
			<script src="<?php echo $BASE.'/'.$JS; ?>chart.js"></script>
	    <?php endif; ?>
	    <script type="text/javascript">
	    	$(document).ready(function() {
	    		$("#page-loading").fadeOut()
	    	});
	    </script>
    	<script src="<?php echo $BASE.'/'.$JS; ?>frosty.min.js"></script>
    	<script type="text/javascript">
    		$(document).ready(function() {
    			$('.has-tip').frosty();
    			$('.has-tip-left').frosty({ position: 'left' });
    			$('.has-tip-right').frosty({ position: 'right' });
    			$('.has-tip-up').frosty({ position: 'top' });
	    		$('.has-tip-down').frosty({ position: 'bottom' });
	    		if ($(window).width() >= 767) {
    				$('.nav a.has-tip-right').frosty('hide');
	    		}
	    		$(window).resize(_.debounce(function(){
	    			if ($(window).width() >= 767) {
	    				$('.nav a.has-tip-right').frosty('hide');
				    }
				}, 0));
    		});
    	</script>
	    <?php if ($PATTERN=='/event/'.'@'.'eid/update'): ?>
	    	<script type="text/javascript">
	    		$(document).ready(function() {
	    			var uploader = $('input[name="xls"]'),
	    				trigger = $('#trigger'),
	    				reset = $('#reset'),
	    				triggerdeftxt = "<?php echo $event_choose_new_template[$l]; ?>";
	    			trigger.click(function() {
	    				uploader.click();
	    			});
	    			uploader.change(function() {
	    				var path = $(this).val();
	    				if(path != undefined && path != '') {
	    					trigger.removeClass('col-xs-12').addClass('col-xs-10 templated');
	    					reset.show();
	    					var startIndex = (path.indexOf('\\') >= 0 ? path.lastIndexOf('\\') : path.lastIndexOf('/')),
	    						filename = path.substring(startIndex);
    						if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
    							filename = filename.substring(1);
    						}
    						trigger.text(filename);
	    				} else {
	    					reset.hide();
	    					trigger.removeClass('col-xs-10 templated').addClass('col-xs-12');
	    					uploader.val('');
	    					trigger.text(triggerdeftxt);
	    				}
	    			});
	    			reset.click(function(){
    					trigger.removeClass('col-xs-10 templated').addClass('col-xs-12');
    					uploader.val('');
    					trigger.text(triggerdeftxt);
	    				reset.hide();
	    			});
	    		});
	    	</script>
	    <?php endif; ?>
	    <?php if (preg_match('/\/import\//i', $PATTERN)): ?>
	    	<script src="<?php echo $BASE.'/'.$JS; ?>file.min.js"></script>
	    	<script type="text/javascript">
	    		$(document).ready(function() {
	    			$(':file').change(function(){
	    				if($(this).val() != ''){
	    					$('#fsub').show().click(function(){
	    						$('#ug').submit();
	    					});
	    					return;
	    				} else {
	    					$('#fsub').hide();
	    					return false;
	    				}
	    			})
	    		});
	    	</script>
	    <?php endif; ?>
	    <?php if ($PATTERN=='/event/'.'@'.'eid/show' || $PATTERN=='/event/'.'@'.'eid/show/hosts' || $PATTERN=='/event/'.'@'.'eid/show/guests'): ?>
	    	<script src="<?php echo $BASE.'/'.$JS; ?>export.js"></script>
	    <?php endif; ?>
    </body>
</html>