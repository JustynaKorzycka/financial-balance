<?php
session_start();
require_once "dataBase.php";
if(!isset($_SESSION['userId']))
{
    header('Location: index.php');
    exit();
}

$userId = $_SESSION['userId'];

if(isset($_POST['startDate']))
{
    $allRight = true;
    $firstDate= $_POST['startDate'];
    $lastDate = $_POST['endDate'];
    $todayDate = date("Y-m-d");

    if($firstDate == "")
    {
        $firstDate = date("Y-m-d");
    }
    if($lastDate == "")
    {
        $lastDate = date("Y-m-d");
    }
    $firstYear = substr($firstDate, 0, 4);
    $firstMonth = substr($firstDate, 5, 2);
    $firstDay = substr($firstDate, 8, 2);

    $lastYear = substr($lastDate, 0, 4);
    $lastMonth = substr($lastDate, 5, 2);
    $lastDay = substr($lastDate, 8, 2);

    if((!is_numeric($firstYear)) || (!is_numeric($firstMonth))|| (!is_numeric($firstYear))|| (!is_numeric($lastYear))|| (!is_numeric($lastMonth))|| (!is_numeric($lastDay)))
    {
        $allRight = false;
        $_SESSION['error_date'] = "Nie poprawna data lub format";
    }

    else if((!checkdate($firstMonth, $firstDay, $firstYear)) || (!checkdate($lastMonth, $lastDay, $lastYear)))
    {
        $allRight = false;
        $_SESSION['error_date'] = "Nie poprawna data lub format";
    }
    else
    {
        if($firstDate > $lastDate)
        {
            $allRight = false;
            $_SESSION['error_date'] = "Niepoprawny przedział";
        }
        else if($firstDate > $todayDate || $lastDate > $todayDate)
        {
            $allRight = false;
            $_SESSION['error_date'] = "Data nie może być późniejsza niż dziś";
        }
    }

    if($allRight == true)
    {   
        $_SESSION['createTable'] = true;

        $allIncome = $db->prepare("SELECT incomes.amount, incomes.date_of_income, incomes.income_comment, inc.name FROM incomes JOIN incomes_category_assigned_to_users AS inc ON incomes.date_of_income>='$firstDate' AND incomes.date_of_income<='$lastDate' AND incomes.user_id='$userId' AND incomes.income_category_assigned_to_user_id=inc.id");

        $allIncome->execute();
        $allIncomeBilans = $allIncome->fetchAll();
        
        $allExpense = $db->prepare("SELECT expenses.amount, expenses.date_of_expense, expenses.expense_comment, expen.name, met.name2 FROM expenses JOIN expenses_category_assigned_to_users AS expen ON expenses.date_of_expense>='$firstDate'
        JOIN payment_methods_assigned_to_users AS met ON expenses.payment_method_assigned_to_user_id=met.id
        AND expenses.date_of_expense<='$lastDate' AND expenses.user_id='$userId' AND expenses.expense_category_assigned_to_user_id=expen.id");
        
        $allExpense->execute();
        $allExpenseBilans = $allExpense->fetchAll();    


        $forDiagram = array('Transport'=> 0, 'Książki'=> 0, 'Jedzenie'=> 0, 'Mieszkanie'=> 0, 'Telekomunikacja'=> 0, 'Opieka zdrowotna'=> 0, 'Ubranie'=> 0, 'Higiena'=> 0, 'Dzieci'=> 0, 'Relaks'=> 0, 'Wycieczki'=> 0, 'Oszczędności'=> 0, 'Emerytura'=> 0, 'Spłata długu'=> 0, 'Prezent'=> 0, 'Inne'=> 0);

        foreach($allExpenseBilans as $expensCat)
        { 
            $forDiagram[$expensCat['name']] += $expensCat['amount'];    
        }
        asort($forDiagram);
        foreach($forDiagram as $x => $x_value){
            if($x_value == 0) unset($forDiagram[$x]);
        }
        foreach($forDiagram as $x => $x_value){
            $doWykresu[] = "['".$x."', ". $x_value."]";
            
        }
        $dataForChart = implode(",", $doWykresu);
            }
}


        
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <title>Bilnas finansowy na inny okres</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Merienda|Bai+Jamjuree" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <script>
          $(document).ready(function(){   
          $(".menu-trigger").click(function(){      
          $('nav').slideToggle();  
          });
          });
        </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel=”stylesheet” href=”https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css”>

    <script type=’text/javascript’ src=”https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js”></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     
    <?php 
    if(isset($_SESSION['error_date'])){
      echo "<style>
      .alert{
          height: 38px;
          align-items: center;
          display:flex;
          display: flex;
          width: 100%;
          margin-bottom: -30px;
        }
        </style>";
      }
    ?>


</head>

<body>
<header>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Rozwiń nawigację</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand active" href="mainMenu.php">Finanse pod kontrolą</a>
        </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="income.php"><span class="glyphicon glyphicon-piggy-bank"></span> Dodaj przychód</a></li>
                <li ><a href="expense.php"><span class="glyphicon glyphicon-credit-card"></span> Dodaj wydatek</a></li>
                <li class="dropdown active">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-list-alt"></span> Przeglądaj bilans
                <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="bilansCurrentMonth.php">Bieżący miesiąć</a></li>
                    <li><a href="bilansLastMonth.php">Poprzedni miesiąc</a></li>
                    <li><a href="bilansCurrentYear.php">Bieżący rok</a></li>
                    <li><a href="bilansDifferentDate.php">Inny przedział</a></li>
                </ul>
                </li>
                <li><a href="#"> <span class="glyphicon glyphicon-cog"></span> Ustawienia</a></li>
                <li><a href="logOut.php"><span class="glyphicon glyphicon-off"></span> Wyloguj się</a></li>
            </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
        </nav>
        </header>    
<article>

 <script>
$(function() {
$(".datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
});
</script>

</head>

<body>

<div class="container">
    <div class = "row">
        <div class = "col-md-8 col-xs-12 col-md-offset-2">
            <div class = 'bilansBlank img-rounded' style="margin-top: 10px;">
                <h4 style="text-align: center; font-size: 25px; margin-bottom:50px;">Wybierz zakres datowy </h4>        
                <form class="form-inline" role="form" method="post">
                    <div class="form-group">
                        <label style="font-size:20px;" class="control-label col-xs-4 col-md-3 col-xs-offset-1 formFormatDffBilans" for="startDate">Start</label>
                        <div class="col-xs-7 col-md-3">
                        <input type="text" class="form-control datepicker formFormatDffBilans " name = "startDate" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="font-size:20px;" class="control-label col-md-3 col-xs-4 col-xs-offset-1 formFormatDffBilans" for="startDate">Koniec</label>
                        <div class="col-xs-7 col-md-3">
                        <input type="text" class="form-control datepicker formFormatDffBilans " name = "endDate" autocomplete="off">
                        </div>
                        </div>
                        <div>
                        <?php if(isset($_SESSION['error_date'])) 
                            {
                                echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_date']."</div>";
                                unset($_SESSION['error_date']);
                            }
                        ?>
                        </div>
                    <button type="submit" class="btn btn-lg btn-primary btn-block formFormatDffBilans" id = "diffBilansBtn">Pokaż bilans!</button> 
                </form>  
            </div>
        </div>    
    </div>
    <?php
        if(isset($_SESSION['createTable']))
        {
            unset($_SESSION['createTable']);
            echo "<div class='container'>
            <div class = 'row'>
                <div class = 'col-md-10 col-xs-12 col-md-offset-1 mainBilans'>
                    <div class = 'bilansBlank'>";
                        echo "<h3>Przegląd bilansu od ". $firstDate." do ".$lastDate."</h3>
                        <div class='table-responsive col-xs-12 col-md-10 col-md-offset-1 col-xs-offset-0  bilansTable'>
                            <table class='table table-bordered table-condensed ' id='incomeTable'>
                                <thead>
                                    <tr>
                                        <th colspan='5' class= 'text-center'style=' background-color:#e28787;'>Przychody</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th class= 'text-center'>#</th>
                                        <th class= 'text-center'>Kategoria</th>
                                        <th class= 'text-center'>Kwota PLN</th>
                                        <th class= 'text-center'>Data</th>
                                        <th class= 'text-center'>Komentarz</th>
                                    </tr>
                                </thead>
                                <tbod>";
                                $number = 1; 
                                $amountOfIncome = 0;
                                foreach($allIncomeBilans as $bilans)
                                {
                                    echo "<tr>
                                        <td>".$number."</td>
                                        <td>".$bilans['name'].
                                        "</td><td>".$bilans['amount']."</td>
                                        <td>";
                                        print_r($bilans['date_of_income']);
                                        echo "</td>
                                        <td>".$bilans['income_comment']. "</td></tr>";
                                    $number++;
                                    $amountOfIncome +=$bilans['amount'];
                                }
                                echo "</tbod>
                            </table>
                        </div>
                        <div class='table-responsive col-xs-12 col-md-10 col-md-offset-1 col-xs-offset-0  bilansTable'>
                            <table class='table table-bordered table-condensed' id='expenseTable'>
                                <thead>
                                    <tr>
                                        <th colspan='6' class= 'text-center'style=' background-color:#e28787;'>Wydatki</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th class= 'text-center'>#</th>
                                        <th class= 'text-center'>Kategoria</th>
                                        <th class= 'text-center'>Kwota PLN</th>
                                        <th class= 'text-center'>Sposób płatności</th>
                                        <th class= 'text-center'>Data</th>
                                        <th class= 'text-center'>Komentarz</th>
                                    </tr>
                                </thead>
                                <tbod>";
                                $number = 1;
                                $amountOfExpenses = 0;
                                foreach($allExpenseBilans as $bilans)
                                {
                                    echo "<tr>
                                            <td>".$number."</td>
                                            <td>".$bilans['name'].
                                            "</td><td>".$bilans['amount']."</td>
                                            <td>".$bilans['name2'].
                                            "</td><td>";
                                            print_r($bilans['date_of_expense']);
                                            echo "</td>
                                            <td>".$bilans['expense_comment']. "</td></tr>";
                                    $number++;
                                    $amountOfExpenses+=$bilans['amount'];
                                }
                                echo "</tbod>
                            </table> 
                            </div>
                            <div row>
                            <div class='summary col-md-4 col-xs-9 col-xs-offset-1'>
                                <table class='table table-bordered table-condensed '>
                                    <thead>
                                        <tr>
                                            <th colspan='2' class= 'text-center'style=' background-color:#e28787;'>Podsumowanie</th>
                                        </tr>
                                    </thead>
                                    <tbod>
                                        <tr>
                                            <th class= 'text-center'>Przychody</th>
                                            <th class= 'text-center'>";
                                            echo  $amountOfIncome;
                                            echo "</th>
                                        </tr>
                                        <tr>
                                            <th class= 'text-center'>Wydatki</th>
                                            <th class= 'text-center'>";
                                            echo $amountOfExpenses;
                                            echo"</th>
                                        </tr>
                                    </tbody>
                                    </table>
                            </div>
                            <div class = 'col-md-5 col-xs-9 col-xs-offset-1 col-md-offset-1'>
                                <div class='jumbotron' id = 'sumJumbo'>";
                                $totalAmount = $amountOfIncome - $amountOfExpenses;
                                    if($totalAmount > 0)
                                    {
                                        echo "<h2>Brawo!</h2><p> W tym okresie oszczędziłeś ".$totalAmount." zł</p>";
                                    }
                                    else if($totalAmount == 0)
                                    {
                                        echo "<h2>Nieźle!</h2><p>W tym okresie wyszedłeś na czysto</p>";
                                    }
                                    else 
                                    {
                                        $totalAmount = $totalAmount * (-1);
                                        echo "<h2>Czas zacząć oszczędzać</h2><p>w tym okresie wydałeś za dużo o ".$totalAmount." zł</p>";
                                    }
                                echo "</div>
                            </div>";?>
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawChart);
                                function drawChart() {
                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Topping');
                                data.addColumn('number', 'Slices');
                                data.addRows([
                                    <?php echo $dataForChart; ?>
                                ]);

                                var options = {'title':'Ile pieniędzy przeznaczyłeś na poszczególne kategorie',
                                            'height':300,
                                            'is3D': true,
                                            
                                            };
                                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                                chart.draw(data, options);
                                }
                            </script>
                            <?php
                            
                            echo "<div class = 'col-md-8 col-xs-10 col-xs-offset-1 col-md-offset-2'>
                                <div id='chart_div'></div>  
                                </div>
                            </div>";                        
        }
                ?>
</div>
<script type="text/javascript" src="main.js"></script>
</body>
</html>



