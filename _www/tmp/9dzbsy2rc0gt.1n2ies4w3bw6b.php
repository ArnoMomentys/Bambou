<!DOCTYPE html>
<html dir="ltr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo $BASE.'/'.$IMG; ?>b-sm.png" type="image/png">
        <base href="<?php echo $BASE.'/'.$UI; ?>" />
        <title><?php echo $site; ?></title>
        <link href="<?php echo $BASE.'/'.$CSS; ?>bootstrap.css" rel="stylesheet" media="screen">
        <link href="<?php echo $BASE.'/'.$CSS; ?>layout.min.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
	            <div class="main">
	                <div class="logo logo-md pull-left">
	                	<a href="<?php echo $BASE.'/'; ?>">
	                    	<img src="<?php echo $BASE.'/'.$IMG; ?>b-md.png" class="" />
	                	</a>
	                </div>
	            </div>
			</div>

	        <div class="pull-left widful texalicen">
				<div class="alert alert-danger">
					<h1><span class="glyphicon glyphicon-fire"></span>  Page non trouvée</h1>
					<small>[ <?php echo $PATH; ?> ]</small>
				</div>
	        </div>
		</div> 
	    <script src="<?php echo $BASE.'/'.$JS; ?>jquery.js"></script>
	    <script src="<?php echo $BASE.'/'.$JS; ?>bootstrap.min.js"></script>
    </body>
</html>            