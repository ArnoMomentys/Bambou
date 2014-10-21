<?php

require_once("../../../_lib/forceutf8-master/src/ForceUTF8/Encoding.php");
$data = "";

$fileToRead = scandir(".");

foreach ($fileToRead as $index => $currentFileToRead)
{
	if (in_array($currentFileToRead, array(".", "..", "utf8.php")))
	{
		unset($fileToRead[$index]);
	}
}

foreach ($fileToRead as $index => $currentFileToRead)
{
	// 1. READ INPUT DATA
	$fd = fopen($currentFileToRead, "r");
	$currentData = "";
	while (!feof($fd))
	{
		$currentData .= fread($fd,  8192);
	}
	fclose($fd);
	
	// Title
	$data .= "<center><h1>".$currentFileToRead."</h1></center>";
	
	// 2. CONVERT TO UTF8 IF NEEDED
	$data .= \ForceUTF8\Encoding::toUTF8($currentData);
	
	$data .= "<br><hr>";
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
    <body><?php echo $data; ?>
	</body>
</html>