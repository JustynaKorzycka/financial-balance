<?php

$config = require_once 'config.php';

try{
    $firsConfig = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8";
    $db = new PDO($firsConfig, $config['user'], $config['password'], [PDO::ATTR_EMULATE_PREPARES=>false, PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);


} catch(PDOException $error){
    
    exit('Database error');
    
}



?>