<?php

session_start();

require_once "dataBase.php";
$userId = $_SESSION['userId'];
if(isset($_POST['amount']))
{
    $allRight = true;
    $amount = $_POST['amount'];
    if(!is_numeric($amount))
    {
        $ifComma = strpos($amount, ",");
        if(($ifComma==true) && (substr_count($amount, ",") == 1) && (!preg_match('/[a-z]/', $amount)))
        {
            $amount = str_replace(",",".",$amount);
            $correctAmount = round($amount, 2);
        }
        else
        {
            $allRight = false;
            $_SESSION['error_amount'] = "Podana wartość nie jest liczbą";
        }
    }
    else 
    {
        $correctAmount = round($amount, 2);
    }

    $dateIncome = $_POST['dateIncome'];
    if($dateIncome == "")
    {
        $dateIncome = date("Y-m-d");
    }
    else
    {
        $incomeYear = substr($dateIncome, 0, 4);
        $incomeMonth = substr($dateIncome, 5, 2);
        $incomeDay = substr($dateIncome, 8, 2);

        if(!checkdate($incomeMonth, $incomeDay, $incomeYear))
        {
            $allRight = false;
            $_SESSION['error_date'] = "Nie poprawna data lub format";
        }
        $todayDay = date('Y-m-d');
        if($dateIncome > $todayDay)
        {
            $allRight = false;
            $_SESSION['error_date'] = "Data nie może być późniejsza niż dziś";
        }
    }
    $category ="";
    
    if(isset($_POST['inlineRadioOptions']))
    {
    switch($_POST['inlineRadioOptions']){
        case "salary":
        $category = "Wynagrodzenie";
        break;
        case "bankInterest":
        $category = "Odsetki bankowe";
        break;
        case "allegro":
        $category = "Allegro";
        break;
        case "other":
        $category = "Inne";
        break;
        }
    }
    else{
        $allRight = false;
        $_SESSION['error_category'] = "Wybierz kategorię";
    }

    $comments = $_POST['comments'];
    $correctComment = htmlentities($comments, ENT_QUOTES, "UTF-8");

    if($allRight == true)
    {
        $incomeCat = $db->prepare("SELECT id FROM  incomes_category_assigned_to_users WHERE user_id = :userId AND name= :category");
        $incomeCat->bindValue(':userId', $userId, PDO::PARAM_STR);
        $incomeCat->bindValue(':category', $category, PDO::PARAM_STR);
        $incomeCat->execute();
        $incomeCatId = $incomeCat->fetch();

        $incomeQuery = $db->prepare("INSERT INTO incomes VALUES(NULL, :userId, :incomeCatId, :amount, :dateOfIncome, :comment)");

        $incomeQuery->bindValue(':userId', $userId, PDO::PARAM_STR);
        $incomeQuery->bindValue(':incomeCatId',  $incomeCatId['id'], PDO::PARAM_STR);
        $incomeQuery->bindValue(':amount',  $correctAmount, PDO::PARAM_STR);
        $incomeQuery->bindValue(':dateOfIncome',  $dateIncome, PDO::PARAM_STR);
        $incomeQuery->bindValue(':comment',  $correctComment, PDO::PARAM_STR);
        $incomeQuery->execute();

        $_SESSION['addedIncome'] = "Przychód został dodany";
        header('Location: mainMenu.php');
        exit();

    }
    else{
        header('Location: income.php');
    }






}


?>