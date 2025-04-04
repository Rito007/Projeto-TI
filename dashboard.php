<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plataforma IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="js/dashboardRealTime.js" defer></script>
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
        <div class="text-end"><img class="imagemEstg w-75" src="img/estgRecortado.png" alt="estg-imagem"></div>
    </div>
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row">
            <?php
            require_once(dirname(__FILE__) . "/api/api.php");

            use Api\Api;

            $sensores = Api::getSensoresData();

            if (!empty($sensores)) {
                foreach ($sensores as $sensor) {
                    echo '
        <div class="col col-sm-* m-2">
            <div class="card shadow-sm" data-sensor="' . htmlspecialchars($sensor['nome']) . '">
                <div class="card-header sensor"><b>' . htmlspecialchars($sensor['nome']) . ': ' . htmlspecialchars($sensor['valor']) . '</b></div>
                <div class="card-body text-center"><img src="' . $sensor['imagem'] . '"></div>
                <div class="card-footer">
                    <span><b>Atualização:</b> ' . htmlspecialchars($sensor['data_de_atualizacao']) . ' - <a href="#">Histórico</a></span>
                </div>
            </div>
        </div>';
                }
            } else {
                echo '<p>Nenhum sensor disponível.</p>';
            }
            ?>

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
                            <?php
                            $sensores = Api::getSensoresData();

                            foreach ($sensores as $sensor) {
                                $estadoClasse = 'text-bg-primary';
                                $estadoTexto = 'Normal';

                                if ($sensor['estado'] == 'Elevada') {
                                    $estadoClasse = 'text-bg-danger';
                                    $estadoTexto = 'Elevada';
                                } elseif ($sensor['estado'] == 'Ativo') {
                                    $estadoClasse = 'text-bg-success';
                                    $estadoTexto = 'Ativo';
                                }

                                echo '
                                    <tr data-sensor="' . htmlspecialchars($sensor['nome']) . 't">
                                        <th scope="row">' . htmlspecialchars($sensor['nome']) . '</th>
                                        <td>' . htmlspecialchars($sensor['valor']) . '</td>
                                        <td>' . htmlspecialchars($sensor['data_de_atualizacao']) . '</td>
                                        <td>
                                            <div class="badge rounded-pill ' . $estadoClasse . '">' . $estadoTexto . '</div>
                                        </td>
                                    </tr>';
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>