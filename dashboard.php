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
    require_once(__DIR__ . "/services/Auth.php");
    require_once(__DIR__ . "/config/config.php");
    require_once(__DIR__ . "/services/Logica.php");
    use Services\Auth;
    use Services\Logica;
    use Config\Config;

    //Faz a autenticação de Login
    $Auth = new Auth();
    //Redireciona se não estiver com login
    $Auth->checkLoginRedirect(Config::get('relativePath'), true);

    require __DIR__ . "/components/navbar.php";


    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


    <div class="container d-flex justify-content-between align-items-center">
        <div id="title-header">
            <h1>Servidor IoT</h1>
            <!--Adiciona o nome do utilizador na página-->
            <h6>Utilizador: <?php echo $Auth->getUser(); ?></h6>
        </div>
        <div class="text-end"><img class="imagemEstg w-75" src="img/estgRecortado.png" alt="estg-imagem"></div>
    </div>
    <h6 class="container lotacaoBus" id="lotacaoBus">Quantidade Pessoas Autocarro: <?php echo Logica::getLotacao() ?></h6>
    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row justify-content-center">
            <?php
            require_once(__DIR__ . "/api/api.php");

            use Api\Api;
            //Recebe sensores do servidor
            $sensores = Api::getSensoresData();

            if (!empty($sensores)) {

                //Adiciona os cartões dos sensores
                foreach ($sensores as $sensor) {
                    $sensorValor = 0;
                    //Se o sensor tiver unidade VF(Verdadeiro/Falso) é substituido por Ativo ou Inativo
                    if($sensor['unidade'] == "VF")
                    {
                        $sensor['valor'] == "0" ? $sensorValor = "Ativo" : $sensorValor = "Inativo";
                    }
                    else
                    {
                        $sensorValor = $sensor['valor'] . $sensor['unidade'];
                    }
                    echo '
                        <div class="col col-sm-4 m-2 cartoesSensores">
                            <div class="card shadow-sm" data-sensor="' . htmlspecialchars($sensor['nome']) . '">
                                <div class="card-header sensor"><b>' . htmlspecialchars($sensor['nome']) . ': ' . htmlspecialchars($sensorValor) . '</b></div>
                                <div class="card-body text-center"><img  alt="Fotofrafia de sensor" src="' . $sensor['imagem'] . '"></div>
                                <div class="card-footer">
                                    <span><b>Atualização:</b> ' . htmlspecialchars($sensor['data_de_atualizacao']) . ' - <a href="historico.php?sensor='.htmlspecialchars(str_replace(' ', '_', trim($sensor['nome']))).'">Histórico</a></span>
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
    <div class="container mt-5 tabelaLogs">
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
                            //Recebe os sensores
                            $sensores = Api::getSensoresData();
                            //Adiciona os sensores como linhas de uma tabela
                            foreach ($sensores as $sensor) {
                                $estadoClasse = 'text-bg-primary';
                                if($sensor['unidade'] == "VF")
                                {
                                    $sensor['valor'] == "0" ? $sensorValor = "Ativo" : $sensorValor = "Inativo";
                                }
                                else
                                {
                                    $sensorValor = $sensor['valor'] . $sensor['unidade'];
                                }

                                echo '
                                    <tr data-sensor="' . htmlspecialchars($sensor['nome']) . 't">
                                        <th scope="row">' . htmlspecialchars($sensor['nome']) . '</th>
                                        <td>' . htmlspecialchars($sensorValor) . '</td>
                                        <td>' . htmlspecialchars($sensor['data_de_atualizacao']) . '</td>
                                        <td>
                                            <div class="badge rounded-pill ' . $estadoClasse . '">Normal</div>
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
    <?php
        require_once(__DIR__ . "/components/footer.php");
    ?>
</body>

</html>