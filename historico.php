<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plataforma IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/historico.css">
</head>

<body>
    <?php
    require_once(dirname(__FILE__) ."/services/Auth.php");
    use Services\Auth;
    $Auth = new Auth();
    $Auth->checkLoginRedirect("/Projeto TI", true);
    require dirname(__FILE__) ."/components/navbar.php";

    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


        <div class="container d-flex justify-content-between align-items-center">
        <div id="title-header">
            <h1>Servidor IoT</h1>
            <h6>user: <?php echo $_SESSION['utilizador']; ?></h6>
        </div>
        <div class="text-end"><img class="imagemEstg w-75" src="img/estgRecortado.png" alt="estg-imagem"></div>
    </div>
    <div class="container">
       <div class="w-100 h-100 d-flex justify-content-center text-center border rounded">
            <h2>Histórico</h2>
       </div>
    </div>
    </div>
</body>

</html>