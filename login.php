<?php 
require_once "config.php";
session_start();
function estaIngresado() {
    return isset($_SESSION['username']);
  }
  if (estaIngresado()) {
    header("Location: ".ROOT_URL);
    exit;
  }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <style>
        .login {
        min-height: 100vh;
        }

        .bg-image {
        background-image: url('assets/img/login-bg.jpg');
        background-size: cover;
        background-position: 50% 70%;
        }

        .login-heading {
        font-weight: 300;
        }

        .btn-login {
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
    }
    </style>    
    <div class="container-fluid ps-md-0">
        <div class="row g-0">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
            <div class="col-md-8 col-lg-6">
            <div class="login d-flex align-items-center py-5">
                <div class="container">
                <div class="row">
                    <div class="col-md-9 col-lg-8 mx-auto">
                    <h3 class="login-heading mb-4"><?=APP_NAME?></h3>

                    <!-- Sign In Form -->
                    <form action="login-check.php" method="POST">
                        <?php
                        if (isset($_GET['password']) && $_GET['password'] === 'false') {
                            // Show an error message using Bootstrap alert
                            echo '
                            <div class="alert alert-danger" role="alert">
                                Error: usuario o contraseña no válida
                            </div>';
                        }
                        ?>
                        <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="username" id="username" placeholder="usuario">
                        <label for="username">Nombre de usuario:</label>
                        </div>
                        <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="contraseña">
                        <label for="password">Contraseña:</label>
                        </div>


                        <div class="d-grid">
                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" type="submit">Ingresar</button>
                        <div class="text-center">
                            <a class="small" href="password-forgot.php">Te olvidaste la contraseña?</a>
                        </div>
                        </div>

                    </form>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>