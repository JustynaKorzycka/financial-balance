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
    if(isset($_SESSION['allRight'])){
        echo "<style>
        .alert{
            height: 38px;
            align-items: center;
            display:flex;
            display: flex;
            width: 100%;
            margin-bottom: -15px;
            font-size: 11px;
          }
        #regBtn
        {
            margin-top: 30px;
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
            <h4 id>Finanse pod kontrolą</h4>
        </div>
        </div>
    </nav>
</header>       
<article>
    <div class="container">
        <div class = "row">
            <div class = "col-md-8 col-xs-12 col-md-offset-2 mainReg">
                <div class = "Regblank img-rounded">
                    <h2>Rejestracja </h2>
                    <form class="form-horizontal"  role="form" method="post" action="save.php">
                    <div class="form-group" >
                        <label class="control-label col-xs-3 col-xs-offset-1 col-md-offset-0 formFormat" for="userName">Imię</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control formFormat" id="userName" name="userName" placeholder="Wprowadź swoje imię" autocomplete="off">
                            <?php if(isset($_SESSION['error_name']))
                                echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_name']."</div>";
                                unset($_SESSION['error_name']);
                                unset($_SESSION['allRight']);
                            ?>
                        </div>
                    </div>
                        
                    <div class="form-group" >
                        <label class="control-label col-xs-3 col-xs-offset-1 col-md-offset-0 formFormat" id="email" for="email">Email</label>
                        <div class="col-xs-7">
                            <input type="email" class="form-control formFormat" id="email" name="email" placeholder="Wprowadź swój adres email">
                            <?php if(isset($_SESSION['error_email']))
                                echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_email']."</div>";
                                unset($_SESSION['error_email']);
                                unset($_SESSION['allRight']);
                            ?>
                        </div>
                    </div>
                        <div class="form-group" >
                        <label class="control-label col-xs-3 col-xs-offset-1 col-md-offset-0 formFormat" for="login">Login</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control formFormat" id="logi"  name="login" placeholder="Wprowadź swój login">
                            <?php if(isset($_SESSION['error_login']))
                                echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_login']."</div>";
                                unset($_SESSION['error_login']);
                                unset($_SESSION['allRight']);
                            ?>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label class="control-label col-xs-3 col-xs-offset-1 col-md-offset-0 formFormat" for="password">Hasło</label>
                        <div class="col-xs-7">
                            <input type="password" class="form-control formFormat" id="password" name= "password" placeholder="Twoje hasło">
                            <?php if(isset($_SESSION['error_pass']))
                                echo "<div class='alert alert-danger alert-dismissible'>".$_SESSION['error_pass']."</div>";
                                unset($_SESSION['error_pass']);
                                unset($_SESSION['allRight']);
                            ?>
                        </div>
                    </div>
                    <div class = "col-xs-10 col-xs-offset-1 col-lg-4 col-md-10">
                        <button type="submit" class="btn btn-primary btn-block" id = "regBtn" >Zarejestruj</button> 
                    </div>
                    </form>
                    <div class = "col-xs-10 col-xs-offset-1 col-lg-offset-2 col-lg-4 col-md-10"
                        <button type="button" class="btn btn-primary btn-block" id = "returnBtn" onclick="location.href='index.php'" >Powrót</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>