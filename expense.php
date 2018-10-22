<?php
session_start();

if(!isset($_SESSION['userId']))
{
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Bilnas finansowy - wydatek</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Merienda|Bai+Jamjuree" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel=”stylesheet” href=”https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker3.min.css”>
    <script type=’text/javascript’ src=”https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.min.js”></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
     <?php 
    if(isset($_SESSION['error_amount'])){
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
          <li  class="active"><a href="expense.php"><span class="glyphicon glyphicon-credit-card"></span> Dodaj wydatek</a></li>
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
      <div class = "col-md-8 col-xs-12 col-md-offset-2">
        <div class = "ExpenseBlank img-rounded">
          <h2 style='margin-top:-10px;'>Dodaj wydatek </h2>
          <form class="form-horizontal" role="form" method="post" action="addExpense.php">
            <div class="form-group">
              <label class="control-label col-xs-3 col-md-offset-0 formFormatExpense" for="expenseAmount">Kwota</label>
              <div class="col-xs-7">
                <input type="text" class="form-control formFormatExpense" id="expenseAmount"  name = "amount" placeholder="...zł" autocomplete="off">
                <?php if(isset($_SESSION['error_amount'])) 
                {
                  echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_amount']."</div>";
                  unset($_SESSION['error_amount']);
                }
                ?>
              </div>
            </div>
            <div class="form-group" >
              <label class="control-label col-xs-3 col-md-offset-0 formFormatExpense" for="expenseDate">Data</label>
              <div class="col-xs-7">
                <input type="text" id="date_ex" class="form-control formFormatExpense"  name = "dateExpense" autocomplete="off" placeholder="Domyślnie dzisiejsza data"> 
                <?php if(isset($_SESSION['error_date'])) 
              {
                echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_date']."</div>";
                unset($_SESSION['error_date']);
              }
              ?>
              </div>
            </div>
            <div class="form-group" >
              <label class="control-label col-md-3 col-xs-4 col-md-offset-0   formFormatExpense" for="paymentMethod">Sposób płatności</label>
              <div class="col-xs-7 col-md-5 ">
                <div class = "radio">
                  <label class="radio-inline formFormatExpense" style='margin-left: 11px;'>
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="cash"> Gotówka
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="debitCard"> Karta debetowa
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="creditCard"> Karta kredytowa
                  </label>
                </div>
                <?php if(isset($_SESSION['error_paymentMethod'])) 
                {
                  echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_paymentMethod']."</div>";
                  unset($_SESSION['error_paymentMethod']);
                }
                ?>
              </div>
            </div>
            <div class="form-group">
              <label for="category" class="control-label col-xs-3 col-md-offset-0   formFormatExpense">Rodzaj</label>
              <div class="col-xs-7">
                <select class="form-control formFormatExpense" name="category">
                  <option value="transport">Transport</option>
                  <option value="books">Książki</option>
                  <option value="food">Jedzienie</option>
                  <option value="apartments">Mieszkanie</option>
                  <option value="telecommunication">Telekomunikacja</option>
                  <option value="health">Opieka zdrowotna</option>
                  <option value="clothes">Ubranie</option>
                  <option value="hygiene">Higiena</option>
                  <option value="kids">Dzieci</option>
                  <option value="recreation">Relaks</option>
                  <option value="trip">Wycieczki</option>
                  <option value = "savings">Oszczędności</option>
                  <option value="retirement">Na złotą jesień - emerytura</option>         
                  <option value="debtRepayment">Spłata długu</option>
                  <option value="gift">Prezenty</option>
                  <option value = "another">Inne</option>
                </select>                      
              </div>
              <?php if(isset($_SESSION['error_category'])) 
              {
                echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_category']."</div>";
                unset($_SESSION['error_category']);
              }
              ?>
            </div>
            <div class="form-group" >
              <label class="control-label col-xs-3 col-md-offset-0  formFormatExpense" for="comment">Opis</label>
              <div class="col-xs-7  ">
                <input type="text" class="form-control formFormatExpense" id="incomeComments"  name = "comments" placeholder="Opcjonalnie" autocomplete="off">
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" id = "incomeBtn" >Dodaj</button> 
          </form>
        </div>
      </div>
    </div>
  </div>
</article>
<script src="main.js"> </script>   
</body>
</html>