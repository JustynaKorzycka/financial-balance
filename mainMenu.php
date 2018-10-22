<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Bilnas finansowy - strona główna</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Merienda|Bai+Jamjuree" rel="stylesheet">
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
                    <li class="dropdown">
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
                <div class = "col-xs-12 col-md-10 col-md-offset-1">
                    <div id = "titleMainMenu">
                        <h1 >Witaj w menu głównym</h1>
                    </div>
                </div>
            </div>
            <div class = "row">
                <div class = "col-md-8 col-xs-12 col-xs-offset-0 col-md-offset-2">
                <?php
                if(isset($_SESSION['addedIncome']))
                {
                    echo "<div class='alert alert-success alert-dismissible'syle='margin-bottom:10px'>
                    <a href'#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
                    .$_SESSION['addedIncome']."</div>";
                    unset($_SESSION['addedIncome']);
                } 
                ?>
                    <div id = "mainMenu" class ="blank img-rounded">
                        <button type="button" class="btn btn-lg btn-primary btn-block" id = "mainMenuIncomeBtn" onclick="location.href='income.php'" ><p>Dodaj przychód </button>

                        <button type="button" class="btn btn-lg btn-primary btn-block" id = "mainMenuExpenseBtn" onclick="location.href='expense.php'" ><p>Dodaj wydatek </button>  

                        <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-info btn-lg btn-primary btn-block" data-toggle="modal" data-target="#myModal" id = "mainMenuBilansBtn">Przeglądaj bilans</button>

                        <!-- Modal -->
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-sm">
                   
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h3 class="modal-title">Wybierz okres bilansowy</h3>
                                    </div>
                                    <div class="modal-body">
                                        <ul style = "list-style-type: none;">
                                            <li> <button type="button" class="btn btn-primary" id='bilansBtn' onclick="location.href='bilansCurrentMonth.php'" >Bieżący miesiąc </button></li>
                                            <li> <button type="button" class="btn btn-primary" id='bilansBtn' onclick="location.href='bilansLastMonth.php'" >Poprzedni miesiąc </button></li>
                                            <li> <button type="button" class="btn btn-primary" id='bilansBtn' onclick="location.href='bilansCurrentYear.php'" >Bieżący rok</button></li>
                                            <li> <button type="button" class="btn btn-primary" id='bilansBtn' onclick="location.href='bilansDifferentDate.php'" >Inny przedział </button></li>
                                        </ul>
                                    </div>
                                </div>                       
                            </div>
                        </div>
                        <button type="button" class="btn btn-lg btn-primary btn-block" id = "mainMenuPropBtn" onclick="location.href='#'" ><p>Ustawienia </button> 
                    </div>           
                </div>
            </div>   
        </div>
</body>
</html>