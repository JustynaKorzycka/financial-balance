<?php

session_start();
require_once "dataBase.php";


if(isset($_POST['login']))
{
    if($_POST['login'] == "")
    {
        $_SESSION['wrongLog'] = "Wprowadź swoje dane";
        header('Location: index.php');
        exit();

    }
    $login = filter_input(INPUT_POST, 'login');
    $password = filter_input(INPUT_POST, 'password');

    $logquery = $db->prepare('SELECT id,password FROM users WHERE login= :login');
    $logquery->bindValue(':login', $login, PDO::PARAM_STR);
    $logquery->execute();

    $user = $logquery->fetch();
    
    if(!$user)
    {   
        $_SESSION['wrongLog'] = "Nie ma takiego loginu";
        $_SESSION['LogValue'] = $login;
        header('Location: index.php');
        exit();
    }
    
    if($user &&password_verify($password, $user['password']))
    {
        $_SESSION['userId'] = $user['id'];
        unset($_SESSION['wrongPass']);
    }
    else 
    {
        $_SESSION['wrongPass'] = "Niepoprawne hasło";
        header('Location: index.php');
        exit();
    }
}
header('Location: index.php');






?>