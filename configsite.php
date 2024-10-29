<?php

try{
	$pdo = new PDO("mysql:dbname=id19452698_bancoverdanna;host=localhost","id19452698_marciel","XZsawq21**XZsawq21**");

}catch(PDOException $e){
	echo "ERRO:".$e->getMessage();
	exit;

}


?>