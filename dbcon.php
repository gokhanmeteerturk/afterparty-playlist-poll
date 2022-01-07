<?php
try {

    $dir = 'sqlite:/' . __DIR__ . '/playlist.sqlite';
    $dbh  = new PDO($dir) or die("cannot open the database");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

}
catch(PDOException $e)
{
    echo $e->getMessage();
    exit;
}
$home_url="http://46.101.75.155/lorennas/";
session_start();

?>