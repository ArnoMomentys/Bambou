<form action="<?php echo $BASE.'/login'; ?>" method="post" class="form-signin" role="form">

    <h2 class="form-signin-heading texalicen"><?php echo $connect_title[$LANGUAGE]; ?></h2>

    <?php if ($msg): ?>
        
            <div class="alert alert-<?php echo $msgtype; ?> texalicen"><?php echo $msg; ?></div>
        
    <?php endif; ?>

    <input name="ide" type="email" class="form-control first" placeholder="Adresse mail" required autofocus>

    <input name="pw" type="password" class="form-control last" placeholder="Mot de passe" required>

    <input type="hidden" name="login" value="login" />

    <button class="btn btn-lg btn-primary btn-block" type="submit">
        <span class="glyphicon glyphicon-user"></span> Connexion
    </button>

    <div class="martop50 hotline texalicen well"><?php echo $hotline[$LANGUAGE]; ?> <a href="maito:bambou@momentys.fr"><?php echo $mail[$LANGUAGE]; ?></a></div>

</form>