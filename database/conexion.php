<?php
$user=""; //Your username
$psw=""; //Your password
$dbname="wasap2";
$host="localhost";

////////// Database Structure //////////
//Table -> users
//			- name: username, type: varchar(32) [PRIMARY]
//			- name: password, type varchar(255)
//			- name: dateCreated, type: datetime
//
//Table -> config
//			- name: username, type: varchar(32) [PRIMARY]
//			- name: color, type varchar(7) [Predetermined: #FFFFFF]

try{
	$dsn="mysql:host=$host;dbname=$dbname";
	$conexion = new PDO($dsn, $user, $psw);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo $e->getMessage();
}
?>