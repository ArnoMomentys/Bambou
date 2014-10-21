<?php

// $dns = 'mysql:host=127.0.0.1;dbname=bambou';
// $utilisateur = 'root';
// $motDePasse = 'root';
// $options = array(
//     PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
//     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//   );
// $connection = new PDO( $dns, $utilisateur, $motDePasse, $options );
// try {
// 	$load = $connection->exec("LOAD DATA INFILE '/Applications/MAMP/www/_www/tmp/uploads/h1e100t1404260618.OK.csv' INTO TABLE `h1e100t1404260618` FIELDS TERMINATED BY ';'");
// 	echo "<pre>".var_dump($load)."<pre>";
// } catch(Exception $e) {
// 	echo "erreur [$e]";
// }

// var_dump(stristr('facture1408800724.xls.xls: Invalid mimetype. Must be one of: application/vnd.ms-excel, text/plain', 'invalid mimetype'));
$a = ['otot', 'titi', '  '];
$b = array_map('trim', $a);
$o = (object) array_map('trim', ['otot', 'titi', '  ']);

echo '<pre>';
var_dump($o);
echo '</pre>';