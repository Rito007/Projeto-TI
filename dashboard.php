<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plataforma IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <?php
    require_once(dirname(__FILE__) . "/services/Auth.php");

    use Services\Auth;

    $Auth = new Auth();
    $Auth->checkLoginRedirect("/Projeto TI", true);
    require dirname(__FILE__) . "/components/navbar.php";

    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


    <div class="container d-flex justify-content-between align-items-center">
        <div id="title-header">
            <h1>Servidor IoT</h1>
            <h6>user: <?php echo $_SESSION['utilizador']; ?></h6>
        </div>
        <div class="text-end"><img class="imagemEstg w-75" src="img/estgRecortado.png"  alt="estg-imagem"></div>
        
    </div>
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col col-sm-* m-2">
                <div class="card shadow-sm">
                    <div class="card-header sensor"><b>Temperatura 40º</b></div>
                    <div class="card-body text-center"><img src="img/temperature-high.png"></div>
                    <div class="card-footer"></img><span><b>Atualização</b> 2024/03/10 14:31 - <a
                                href="#">Histórico</a></span></div>
                </div>
            </div>
            <div class="col col-sm-* m-2">
                <div class="card shadow-sm">
                    <div class="card-header sensor"><b>Humidade:70%</b></div>
                    <div class="card-body text-center"><img src="img/humidity-high.png"></div>
                    <div class="card-footer"></img><span><b>Atualização</b> 2024/03/10 14:31 - <a
                                href="#">Histórico</a></span></div>
                </div>
            </div>
            <div class="col col-sm-* m-2">
                <div class="card col col-sm-* shadow-sm">
                    <div class="card-header atuador"><b>Led Arduino: Ligado</b></div>
                    <div class="card-body text-center"><img src="img/light-on.png"></div>
                    <div class="card-footer"></img><span><b>Atualização</b> 2024/03/10 14:31 - <a
                                href="#">Histórico</a></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="col">
            <div class="card shadow-sm m-2">
                <div class="card-header"><b>Tabela Sensores</b></div>
                <div class="card-body table-responsive p-0">
                    <table class="table m-0">
                        <thead class="table-dark rounded rounded-top">
                            <tr>
                                <th scope="col">Tipo de Dispositivo IoT</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Data de Atualização</th>
                                <th scope="col">Estados de Alerta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Temperatura</th>
                                <td>40º</td>
                                <td>Data abc</td>                               
                                <td>
                                    <div class="badge rounded-pill text-bg-danger">Elevada</div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Humidade</th>
                                <td>70%</td>
                                <td>Data abc</td>
                                <td>
                                    <div class="badge rounded-pill text-bg-primary">Normal</div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Led Arduino</th>
                                <td>Ligado</td>
                                <td>Data abc</td>
                                <td>
                                    <div class="badge rounded-pill text-bg-success">Ativo</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>