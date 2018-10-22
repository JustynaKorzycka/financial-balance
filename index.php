<?php
session_start();
if(isset($_SESSION['userId']))
{
    header('Location: mainMenu.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Bilnas finansowy - strona główna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Merienda|Bai+Jamjuree" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <?php 
  if(isset($_SESSION['wrongLog'])||isset($_SESSION['wrongPass'])){
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
      <div class="navbar-header sideBarTitle">
        <h4>Finanse pod kontrolą</h4>
      </div>
    </div>
  </nav>

</header>       
<article>
  <div class="container">
    <div class = "row">
      <div class = "col-md-5 col-xs-12 col-md-offset-1">
      <?php
      if(isset($_SESSION['newRegister']))
      {
        echo "<div class='alert alert-success alert-dismissible'syle='margin-bottom:10px'>
        <a href'#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
        .$_SESSION['newRegister']."</div>";
        unset($_SESSION['newRegister']);
      } 
      ?>
        <div class = "blank img-rounded">
          <h2>Zaloguj się </h2>
            <form class="form-horizontal" method="post" action="logIn.php">
              <div class="form-group">
                <label class="control-label col-xs-3 formFormat" for="loginSubmit">Login</label>
                <div class="col-xs-8">
                  <input type="text" class="form-control formFormat" id="login" name = "login" autocomplete="off" placeholder="Wprowadź swój login">
                  <?php if(isset($_SESSION['wrongLog']))
                    echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['wrongLog']."</div>";
                    unset($_SESSION['wrongLog']);
                  ?>
                </div>
              </div>
              <div class="form-group" id="passwordBlank" >
                <label class="control-label col-xs-3 formFormat" for="passwordSubmit">Hasło</label>
                <div class="col-xs-8">
                  <input type="password" class="form-control formFormat" id="password" name = "password" placeholder="Twoje hasło">
                  <?php if(isset($_SESSION['wrongPass']))
                    echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['wrongPass']."</div>";
                    unset($_SESSION['wrongPass']);
                  ?>
                </div>
              </div>
              <button type="submit" class="btn btn-lg btn-primary btn-block" id = "logBtn">Zaloguj</button> 
            </form>

            <button type="button" class="btn btn-lg btn-primary btn-block" id = "wantReg" onclick="location.href='registration.php'" ><p>Nie masz jeszcze konta? </p> Zarejstruj się już dziś </button>  
        </div>
      </div>
      <div class = "col-md-5 col-xs-9 col-xs-offset-1">
        <div class="jumbotron" id = "description">
            <h2>Finanse pod kontrolą! </h2> 
            <p id = "description">Strona Finanse pod kontrolą jest aplikacją, która pomoże Ci, w prosty sposób, ogarnąć Twoje pieniądzę. Posiada funkcję dodawania wydatków i przychodów, jak i umożliwia przeglądanie bilansów z wybranych okresów. Załóż darmowe konto i zacznij już dziś oszczędzać na spełnienie swoich marzeń! </p> 
        </div>
      </div>
    </div>


  </div>
</article>



</body>
</html>
