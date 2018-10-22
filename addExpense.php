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

    $dateExpense = $_POST['dateExpense'];
    if($dateExpense == "")
    {
        $dateExpense = date("Y-m-d");
    }
    else
    {
        $expenseYear = substr($dateExpense, 0, 4);
        $expenseMonth = substr($dateExpense, 5, 2);
        $expenseDay = substr($dateExpense, 8, 2);

        if(!checkdate($expenseMonth, $expenseDay, $expenseYear))
        {
            $allRight = false;
            $_SESSION['error_date'] = "Nie poprawna data lub format";
        }
        $todayDay = date('Y-m-d');
        if($dateExpense > $todayDay)
        {
            $allRight = false;
            $_SESSION['error_date'] = "Data nie może być późniejsza niż dzisiaj";
        }
    }

    $paymentMethod ="";
    
    if($_POST['inlineRadioOptions'])
    {
    switch($_POST['inlineRadioOptions']){
        case "cash":
        $paymentMethod = "Gotówka";
        break;
        case "debitCard":
        $paymentMethod = "Karta debetowa";
        break;
        case "creditCard":
        $paymentMethod = "Karta kredytowa";
        break;
        }
    }
    else{
        $allRight = false;
        $_SESSION['error_paymentMethod'] = "Wybierz sposób płatności";
    }

    $category = "";
    if ($_POST['category'])
    {
    switch($_POST['category']){
        case "transport":
        $category = "Transport";
        break;
        case "books":
        $category = "Książki";
        break;
        case "food":
        $category = "Jedzenie";
        break;
        case "apartments":
        $category = "Mieszkanie";
        break;
        case "telecommunication":
        $category = "Telekomunikacja";
        break;
        case "health":
        $category = "Opieka zdrowotna";
        break;
        case "clothes":
        $category = "Ubranie";
        break;
        case "hygiene":
        $category = "Higiena";
        break;
        case "kids":
        $category = "Dzieci";
        break;
        case "recreation":
        $category = "Relaks";
        break;
        case "trip":
        $category = "Wycieczki";
        break;
        case "savings":
        $category = "Oszczędności";
        break;
        case "retirement":
        $category = "Emerytura";
        break;
        case "debtRepayment":
        $category = "Spłata długu";
        break;
        case "gift":
        $category = "Prezent";
        break;
        case "another":
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
    }
    if($allRight == true)
    {
        $expensesCat = $db->prepare("SELECT id FROM  expenses_category_assigned_to_users WHERE user_id = :userId AND name= :category");
        $expensesCat->bindValue(':userId', $userId, PDO::PARAM_STR);
        $expensesCat->bindValue(':category', $category, PDO::PARAM_STR);
        $expensesCat->execute();
        $expensesCatId = $expensesCat->fetch();

        $expensesPayment = $db->prepare("SELECT id FROM  payment_methods_assigned_to_users WHERE user_id = :userId AND name2= :paymentMethod");
        $expensesPayment->bindValue(':userId', $userId, PDO::PARAM_STR);
        $expensesPayment->bindValue(':paymentMethod', $paymentMethod, PDO::PARAM_STR);
        $expensesPayment->execute();
        $expensesPaymentId = $expensesPayment->fetch();


        $expensesQuery = $db->prepare("INSERT INTO expenses VALUES(NULL, :userId, :expensesCatId,:expensesPaymentId, :amount, :dateOfExpense, :comment)");

        $expensesQuery->bindValue(':userId', $userId, PDO::PARAM_STR);
        $expensesQuery->bindValue(':expensesCatId',  $expensesCatId['id'], PDO::PARAM_STR);
        $expensesQuery->bindValue(':expensesPaymentId',  $expensesPaymentId['id'], PDO::PARAM_STR);
        $expensesQuery->bindValue(':amount',  $correctAmount, PDO::PARAM_STR);
        $expensesQuery->bindValue(':dateOfExpense',  $dateExpense, PDO::PARAM_STR);
        $expensesQuery->bindValue(':comment',  $correctComment, PDO::PARAM_STR);
        $expensesQuery->execute();


        $_SESSION['addedIncome'] = "Wydatek został dodany";
        header('Location: mainMenu.php');
        exit();
    }
else{
    header('Location: expense.php');
}


?>