<?php
session_start();
require_once "dataBase.php";
if(!isset($_SESSION['userId']))
{
    header('Location: index.php');
    exit();  
}

$userId = $_SESSION['userId'];

$lastMonth = date('m', strtotime('-1 month')) ;
$thisYear = date('Y', strtotime('-1 month'));
$firstDay = $thisYear."-".$lastMonth."-01";
$lastDay = date('Y-m-t', strtotime('-1 month'));

$lastMonthIncome = $db->prepare("SELECT incomes.amount, incomes.date_of_income, incomes.income_comment, inc.name FROM incomes JOIN incomes_category_assigned_to_users AS inc ON incomes.date_of_income>='$firstDay' AND incomes.date_of_income<='$lastDay' AND incomes.user_id='$userId' AND incomes.income_category_assigned_to_user_id=inc.id");

$lastMonthIncome->execute();
$lastMonthBilnasIncome = $lastMonthIncome->fetchAll();

$lastMonthExpenses = $db->prepare("SELECT expenses.amount, expenses.date_of_expense, expenses.expense_comment, expen.name, met.name2 FROM expenses JOIN expenses_category_assigned_to_users AS expen ON expenses.date_of_expense>='$firstDay' AND expenses.date_of_expense<='$lastDay'
JOIN payment_methods_assigned_to_users AS met ON expenses.payment_method_assigned_to_user_id=met.id
AND expenses.date_of_expense<='$lastDay' AND expenses.user_id='$userId' AND expenses.expense_category_assigned_to_user_id=expen.id");

$lastMonthExpenses->execute();
$lastMonthBilnasExpense = $lastMonthExpenses->fetchAll();

$forDiagram = array('Transport'=> 0, 'Książki'=> 0, 'Jedzenie'=> 0, 'Mieszkanie'=> 0, 'Telekomunikacja'=> 0, 'Opieka zdrowotna'=> 0, 'Ubranie'=> 0, 'Higiena'=> 0, 'Dzieci'=> 0, 'Relaks'=> 0, 'Wycieczki'=> 0, 'Oszczędności'=> 0, 'Emerytura'=> 0, 'Spłata długu'=> 0, 'Prezent'=> 0, 'Inne'=> 0);

foreach($lastMonthBilnasExpense as $expensCat)
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

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Bilnas finansowy bieżący miesiąc</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Merienda|Bai+Jamjuree" rel="stylesheet">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
            <div class="container">
                <div class = "row">
                    <div class = "col-md-10 col-xs-12 col-md-offset-1 mainBilans">
                        <div class = "bilansBlank">
                            <h3>Przegląd bilansu z poprzedniego miesiąca</h3>
                            <div class="table-responsive col-xs-12 col-md-10 col-md-offset-1 col-xs-offset-0  bilansTable">
                                <table class="table table-bordered table-condensed " id="incomeTable">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class= "text-center"style=" background-color:#e28787;">Przychody</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class= "text-center">#</th>
                                            <th class= "text-center">Kategoria</th>
                                            <th class= "text-center">Kwota PLN</th>
                                            <th class= "text-center">Data</th>
                                            <th class= "text-center">Komentarz</th>
                                        </tr>
                                    </thead>
                                    <tbod>
                                    <?php
                                        $number = 1;
                                        $amountOfIncome = 0;
                                        foreach($lastMonthBilnasIncome as $bilans)
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
                                        ?>                                
                                    </tbod>
                                </table>
                        
                             </div>
                             <div class="table-responsive col-xs-12 col-md-10 col-md-offset-1 col-xs-offset-0  bilansTable">
                                <table class="table table-bordered table-condensed " id="expenseTable">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class= "text-center"style=" background-color:#e28787;">Wydatki</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class= "text-center">#</th>
                                            <th class= "text-center">Kategoria</th>
                                            <th class= "text-center">Kwota PLN</th>
                                            <th class= "text-center">Sposób płatności</th>
                                            <th class= "text-center">Data</th>
                                            <th class= "text-center">Komentarz</th>
                                        </tr>
                                    </thead>
                                    <tbod>
                                    <?php
                                        $number = 1;
                                        $amountOfExpenses = 0;
                                        foreach($lastMonthBilnasExpense as $bilans)
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
                                        ?> 
                                        
                                    </tbod>
                                </table> 
                             </div>
                             <div row>
                                <div class="summary col-md-4 col-xs-9 col-xs-offset-1">
                                    <table class="table table-bordered table-condensed ">
                                        <thead>
                                            <tr>
                                                <th colspan="2" class= "text-center"style=" background-color:#e28787;">Podsumowanie</th>
                                            </tr>
                                        </thead>
                                        <tbod>
                                            <tr>
                                                <th class= "text-center">Przychody</th>
                                                <th class= "text-center"><?php echo $amountOfIncome ?></th>
                                            </tr>
                                            <tr>
                                                <th class= "text-center">Wydatki</th>
                                                <th class= "text-center"><?php echo $amountOfExpenses ?></th>
                                            </tr>
                                        </tbody>
                                        </table>
                                </div>
                                <div class = "col-md-5 col-xs-9 col-xs-offset-1 col-md-offset-1">
                                    <div class="jumbotron" id = "sumJumbo">
                                    <?php
                                        $totalAmount = $amountOfIncome - $amountOfExpenses;
                                        if($totalAmount > 0)
                                        {
                                            echo "<h2>Brawo!</h2><p> W poprzednim miesiącu oszczędziłeś ".$totalAmount." zł</p>";
                                        }
                                        else if($totalAmount == 0)
                                        {
                                            echo "<h2>Nieźle!</h2><p>W poprzednim miesiącu wyszedłeś na czysto</p>";
                                        }
                                        else 
                                        {
                                            $totalAmount = $totalAmount * (-1);
                                            echo "<h2>Czas zacząć oszczędzać</h2><p>w poprzednim miesiącu wydałeś za dużo o ".$totalAmount." zł</p>";
                                        }
                                    ?> 
                                    </div>
                                </div>
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
                                                'is3D': true
                                                };
                                    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                                    chart.draw(data, options);
                                }
                                </script>
                                <div class = "col-md-8 col-xs-10 col-xs-offset-1 col-md-offset-2">
                                    <div id="chart_div"></div>  
                                </div>
                            </div>
                        </div>
                    </div>         
                </div>
            </div>



        </article>
        </html>