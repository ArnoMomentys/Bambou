<?php 

// TIMER
function ums($init)
{
	$secs = floor($init);
	$milli = (int) (($init - $secs) * 1000);

	$hours = ($secs / 3600);
	$minutes = (($secs / 60) % 60);
	$seconds = $secs % 60;
	if($minutes < 10)
	{
		$minutes = "0".$minutes;
	}

	if($seconds < 10)
	{
		$seconds = "0".$seconds;
	}

	$milli = /* code to ret the remaining milliseconds */   
	$stageTime = "$minutes minutes $seconds secondes $milli millisecondes";
	return $stageTime;
}

// PDO PARAMS
$PARAM_hote='localhost'; // le chemin vers le serveur
$PARAM_port='3306';
$PARAM_nom_bd='bambou'; // le nom de votre base de données
$PARAM_utilisateur='bambou'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe='momentys'; // mot de passe de l'utilisateur pour se connecter

// INIT CONNECTION
try
{
	$connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
}
catch(Exception $e)
{
	echo 'Une erreur est survenue !';
    die();
}

// REQUESTS
$r = "_events_eventguests";
$request1 = "
select 
	`i`.`eventID` AS `eid`,
	`i`.`guestID` AS `guestid`,
	`i`.`hostID` AS `hostid`,
	`un`.`nom` AS `guestname`,
	`un`.`societe` AS `guestcompany`,
	`un2`.`nom` AS `hostname`,
	`i`.`validated` AS `validated`,
	`ig`.`guestAnswer` AS `answer`,
	`ig`.`guestPresence` AS `presence`,
	`i`.`iid` AS `invitationid` 
from 
	`invitations` `i` 
left join 
	`_user_nomsociete` `un` 
on 
	`i`.`guestID` = `un`.`uid` 
left join 
	`_user_nomsociete` `un2` 
on 
	`i`.`hostID` = `un2`.`uid` 
left join 
	`invitationguests` `ig` 
on 
	`i`.`iid` = `ig`.`invitationID` 
order by 
	`i`.`hostID`,`i`.`eventID`"; 


$request2 = "
select 
	`i`.`eventID` AS `eid`,
	`i`.`guestID` AS `guestid`,
	`i`.`hostID` AS `hostid`,
	`un`.`nom` AS `guestname`,
	`un`.`societe` AS `guestcompany`,
	`un`.`nom` AS `hostname`,
	`i`.`validated` AS `validated`,
	`ig`.`guestAnswer` AS `answer`,
	`ig`.`guestPresence` AS `presence`,
	`i`.`iid` AS `invitationid` 
from 
	`invitations` `i`,
	`_user_nomsociete` `un`, 
	`invitationguests` `ig` 
where 
	`i`.`guestID` = `un`.`uid` 
and 
	`i`.`hostID` = `un`.`uid` 
and 
	`i`.`iid` = `ig`.`invitationID`";

/*
$r = "_user_nomsociete";
$request1 = "
select 
	`u`.`uid` AS `uid`,
	concat_ws(' ',`up`.`nom`,`up`.`prenom`) AS `nom`,
	`uj`.`societe` AS `societe`,
	(char_length(trim(`u`.`email`)) <> 0) AS `isComplete` 
from 
	`users` `u` 
left join 
	`userprofile` `up` 
on 
	`u`.`uid` = `up`.`userID` 
join 
	`userjobinfos` `uj` 
on 
	`u`.`uid` = `uj`.`userID` 
order by `up`.`nom`,`uj`.`societe`"; 

$request2 = "
select 
	up.userID AS uid,
	concat_ws(' ',up.nom,up.prenom) AS nom,
	societe
from userprofile up 
join userjobinfos uj 
on up.userID = uj.userID";
*/
        

// TIME AND EXEC 1
$ts = microtime(true);
$exec1 = $connexion->query($request1);
$results1 = $exec1->fetchAll();
$te = microtime(true);
$t = $te - $ts;

// TIME AND EXEC 2
$ts2 = microtime(true);
$exec2 = $connexion->query($request2);
$results2 = $exec2->fetchAll();
$te2 = microtime(true);
$t2 = $te2 - $ts2;

// SHOW EXEC TIME
echo '<br>';
echo '<h2>DELAI REQUETE 1</h2><b>'.ums($t).'</b><br><pre>'.$r;
// var_dump($results1);
echo '</pre><br><hr><br>';
echo '<h2>DELAI REQUETE 2</h2><b>'.ums($t2).'</b><br><pre>'.$r.' OPTIM';
// var_dump($results2);
echo '</pre><br><hr><br>';

