<?php

try{
	$pdo = new PDO("mysql:dbname=bancoverdanna;host=localhost","root","");

}catch(PDOException $e){
	echo "ERRO:".$e->getMessage();
	exit;

}


?>