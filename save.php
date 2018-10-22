<?php

session_start();

require_once "dataBase.php";

if(isset($_POST['userName']))
{
    $allRight = true;

    $userName = $_POST['userName'];
    if((strlen($userName)<2)||(strlen($userName)>40))
    {
        $allRight = false;
        $_SESSION['error_name'] = "Wprowadź swoje imię";
    }
    if(!preg_match('/[a-z]+$/', $userName))
    {
        $allRight = false;
        $_SESSION['error_name'] = "Tylko litery bez polskich znaków";
    }

    $email = $_POST['email'];
    $newEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($newEmail, FILTER_VALIDATE_EMAIL))== false || ($email != $newEmail))
    {
        $allRight = false;
        $_SESSION['error_email'] = "Podaj poprawny email";
    }
    else
    {
        $emailQuery = $db->prepare('SELECT id FROM users WHERE email=:email');
        $emailQuery->bindValue(':email', $email, PDO::PARAM_STR); 
        $emailQuery->execute();
        $emailExist = $emailQuery->fetch();
        if($emailExist)
        {
            $allRight = false;
            $_SESSION['error_email'] = "Taki mail już istnieje";
        }
    }
    
    
    $login = $_POST['login'];
    if((strlen($login)<4) || (strlen($login)>20))
    {
        $allRight = false;
        $_SESSION['error_login'] = "Wprowadź od 4 do 20 znaków";
    }
    if(ctype_alnum($login) == false)
    {
        $allRight = false;
        $_SESSION['error_login'] = "Litery i cyfry bez polskich znaków ";
    }
    else
    {
        $loginQuery = $db->prepare('SELECT id FROM users WHERE login=:login');
        $loginQuery->bindValue(':login', $login, PDO::PARAM_STR); 
        $loginQuery->execute();
        $loginExist = $loginQuery->fetch();

        if($loginExist)
        {
            $allRight = false;
            $_SESSION['error_login'] = "Taki login już istnieje";
        }
        
    }

    $password = $_POST['password'];
    if(strlen($password)<7 ||strlen($password)>20)
    {
        $allRight = false;
        $_SESSION['error_pass'] = "Wprowadź od 7 do 20 znaków";
    }
    else 
    {
        $hashpass = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($allRight == false)
    {
    $_SESSION['allRight'] = false;
    }

    if($allRight == true)
    {
        $regQuery = $db->prepare('INSERT INTO users VALUES(NULL, :userName, :login, :password, :email)');
        $regQuery->bindValue(':userName', $userName, PDO::PARAM_STR);
        $regQuery->bindValue(':login', $login, PDO::PARAM_STR);
        $regQuery->bindValue(':password', $hashpass, PDO::PARAM_STR);
        $regQuery->bindValue(':email', $email, PDO::PARAM_STR);
        $regQuery->execute();

        $incomeQuery = $db->prepare('INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT (SELECT id FROM users ORDER BY id DESC LIMIT 1), name FROM incomes_category_default');
        $incomeQuery->execute();

        $expenseQuery = $db->prepare('INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT (SELECT id FROM users ORDER BY id DESC LIMIT 1), name FROM expenses_category_default');
        $expenseQuery->execute();

        $paymentQuery = $db->prepare('INSERT INTO payment_methods_assigned_to_users (user_id, name2) SELECT (SELECT id FROM users ORDER BY id DESC LIMIT 1), name FROM payment_methods_default');
        $paymentQuery->execute();

        $_SESSION['newRegister'] = "Dziękujemy za rejestrację! Teraz możesz się zalogować :)";
        header('Location: index.php');
        exit();

    }



    
}

header('Location: registration.php');





?>

?>