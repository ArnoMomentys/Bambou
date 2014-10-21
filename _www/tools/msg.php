<?php

$csv = array_map('str_getcsv', file('t.csv'));
$t = [];
foreach($csv as $v)
{
	$t[strtolower($v[0])][] = $v[1];
}
$s = '';
foreach($t as $l)
{
	$s .= "UPDATE users SET creatorUid=".array_shift($l)." WHERE creatorUid IN(".implode(',', $l).");\n";
}

echo '<pre>';
var_dump($s);
echo '</pre>';