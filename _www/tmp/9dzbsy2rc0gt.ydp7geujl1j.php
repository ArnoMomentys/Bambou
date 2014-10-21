<!DOCTYPE html>
<html dir="ltr" lang="<?php echo !$SESSION ? $LANGUAGE : (!$l ? $LANGUAGE : $l); ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" href="<?php echo $BASE.'/'.$IMG; ?>b-sm.png" type="image/png" />
        <base href="<?php echo $BASE.'/'.$UI; ?>" />
        <title><?php echo $site; ?></title>
        <link href="<?php echo $BASE.'/'.$CSS; ?>bootstrap.css" rel="stylesheet" media="screen" />
        <link href="<?php echo $BASE.'/'.$CSS; ?>layout.min.css" rel="stylesheet" media="screen" />
        <link href="<?php echo $BASE.'/'.$CSS; ?>frosty.css" rel="stylesheet" media="screen" />
    </head>
    <body <?php echo !$loggedin ? 'class="signin"':''; ?>>
        <div class="container-fluid big-wrapper">
            <div class="row inner-wrapper">
                <?php if ($loggedin): ?>
                    
                        <?php echo $this->render('nav.htm',$this->mime,get_defined_vars()); ?>
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2 main content-wrapper">
                    
                    <?php else: ?>
                        <div class="logo logo-md pull-left padbot30">
                            <img src="<?php echo $BASE.'/'.$IMG; ?>b-sm.png" />
                        </div>
                    
                <?php endif; ?>