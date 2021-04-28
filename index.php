<?php
    session_start();
    //utilizador numero 1
    $user1 = "user1";
    $pass1 = "pass1";
    //utilizador numero 2
    $user2 = "user2";
    $pass2 = "pass2";
    //utilizador numero 3
    $user3 = "user3";
    $pass3 = "pass3";
    //admin
    $admin = "admin";
    $pass = "pass";

        
    if( (isset($_POST['username'])) && (isset($_POST['password'])) ){
    
        if (!empty($_POST['username']) && !empty($_POST['password'])) {     
            
            //confirmação das credenciais
            if ($_POST['username'] == $user1 && $_POST['password'] == $pass1) {
                $_SESSION['username'] = $_POST['username'];  
                header('Location: dashboard.php');

            }else{
                if($_POST['username'] == $user2 && $_POST['password'] == $pass2) {
                    $_SESSION['username'] = $_POST['username'];
                    header('Location: dashboard.php');

                }else{
                    if($_POST['username'] == $user3 && $_POST['password'] == $pass3) {

                    } else {
                
                        if($_POST['username'] == $admin && $_POST['password'] == $pass){
                            $_SESSION['username'] = $_POST['username'];
                            header('Location: dashboard.php');
                        } else {
                            //mensagem de erro
                            $error = "Dados de acesso inválidos!";
                        }
                    }
                }
            }
        } else {
            //mensagem de erro
            $error = "Campos Vazios";
        }
    }
?> 

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Smart Greenhouse</title>

    <!-- Bootstrap and CSS links -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/login.css?v=<?php echo time(); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="public/img/favicon.png"/>
    <!-- Font-Awesome (icons) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">

</head>
<body>

    <div class="container">        

        <div class="card card_form shadow-sm bg-light border-0 rounded-lg">
            <div class="card-body">
                <h3 class="card-title text-center text-uppercase text-success"> <b>Smart Greenhouse</b> </h3>
                    
                <div class="row justify-content-md-center">
                    <div class="media col-lg-5">
                            <img src="public/img/smart_greenhouse.svg"  alt="imagme de uma estufa ilustrada">
                    </div>
                    <div class="col-lg-7">

                        <!-- Alerta caso não tenha colocado as credenciais corretas -->
                        <?php
                            if (isset($error)) {
                                echo "<div class='alert alert-danger fade show' role='alert'> $error </div>";
                            }
                        ?>
                        
                        <form action="#" method="POST">
                            <div class="form-group">
                                <i class="icon fas fa-user text-success"></i>
                                <input type="text" class="form_input form-control" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">                             
                                <i class="icon fas fa-lock text-success"></i>                       
                                <input type="password" class="form_input form-control" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn bg-success text-white w-100">Entrar</button>
                        </form>
                     </div>

                </div>               
                   
            </div>
        </div>    
    </div>
        
   
    <!-- JavaScript Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    
</body>
</html>