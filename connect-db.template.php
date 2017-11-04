<?php

$hosting = "local";

if ($hosting == 'freehostingeu')
{

$dbhost = 'fdb7.freehostingeu.com';
$dbuser = '2121730_14d0';
$dbpass = 'Tototutu5';
$mydb = '2121730_14d0';
} 
elseif ($hosting = 'aws ec2')
{
$dbhost = 'localhost';
$dbuser = 'toto';
$dbpass = 'Toto!';
$mydb = 'loki';
} 
elseif ($hosting = 'freemysql')
{
$dbhost = 'sql7.freemysqlhosting.net';
$dbuser = 'sql7118244';
$dbpass = '23iypFk4Au';
$mydb = 'sql7118244';
} 
elseif ($hosting = 'local')
{
$dbhost = 'localhost';
$dbuser = 'toto';
$dbpass = 'Toto!';
$mydb = 'loki';
}	

?>
