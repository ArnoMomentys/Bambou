<div class="modal fade bs-example-modal-sm" id="myModal-exporthosts-<?php echo $event['eid']?$event['eid']:$PARAMS['eid']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $event['eid']?$event['eid']:$PARAMS['eid']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bornon texalilef">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel-<?php echo $event['eid']?$event['eid']:$PARAMS['eid']; ?>">
                    <i class="glyphicon glyphicon-info-sign"></i> <?php echo ucfirst($export[$l]); ?> <?php echo isset($stats) ? $stats[$event['eid']]->nbHostsTotal : $les[$l]; ?> <?php echo $hosts_at_event[$l]; ?> 
                </h4>
            </div>
            <form action="<?php echo $BASE.'/event/'.($event['eid']?$event['eid']:$PARAMS['eid']).'/export/hosts'; ?>" method="post" class="form-export" role="form" id="form-exporthosts-<?php echo $event['eid']?$event['eid']:$PARAMS['eid']; ?>">
                <div class="modal-footer bornon padtopnon martopnon">
                    <input type="hidden" name="ex" value="host" />
                    <input type="hidden" name="eid" value="<?php echo $event['eid']?$event['eid']:$PARAMS['eid']; ?>">
                    <input type="hidden" name="l" value="<?php echo $SESSION['crp']; ?>">
                    <div class="spinner dark" id="export-loading-hosts"></div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo $cancel[$l]; ?>
                    </button>
                    <button class="btn btn-info" type="submit">
                        <?php echo $event_host_export_confirm[$l]; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>