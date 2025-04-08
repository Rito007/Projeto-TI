<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="css/index.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>
    <?php
        require_once(__DIR__ ."\services\Auth.php");
        use Services\Auth;
    
        $Auth = new Auth();
        $Auth->checkLoginRedirect("dashboard.php",false);

        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $resultado = $Auth->login($_POST['username'], $_POST['password']);
        }

        if (isset($resultado->success) && $resultado->success) {
            header("Location: dashboard.php");
            exit;
        }
    ?>

    <body>
        <div style="height:100vh;" class="container align-content-center">
            <div class="row justify-content-center mb-5">

                <form class="formLogin shadow-sm rounded d-flex flex-column justify-content-center p-5" method="POST" action="index.php">
                    <div class="d-flex justify-content-center mb-4">
                        <img class="w-100" src="./img/estg_h.png" alt="">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" required placeholder="Insira o username" class="form-control" id="username" aria-describedby="username">
                        <label for="" id="labelUserErro" class="text-danger"><?php if(isset($resultado)){echo $resultado->unameErro;} ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" name="password" placeholder="Insira a password" required class="form-control" id="exampleInputPassword1">
                        <label for="" id="labelPassErro"  class="text-danger"><?php if(isset($resultado)){echo $resultado->passwordErro;}?></label>
                    </div>
            
                    <button type="submit" class="btn btn-primary">Login</button>
                    
                </form>
            
            </div>

        </div>
        <script src="main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>

    </html>