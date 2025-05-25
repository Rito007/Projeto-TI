<!doctype html>
<html lang="pt">

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
    // Importa os serviços e configurações
    require_once(__DIR__ . "/services/Auth.php");
    require_once(__DIR__ . "/config/config.php");
    require_once(__DIR__ . "/services/Logica.php");
    require_once(__DIR__ . "/api/api.php");

    use Services\Auth;
    use Services\Logica;
    use Config\Config;
    use Api\Api;

    // Autenticação e redirecionamento
    $Auth = new Auth();
    $Auth->checkLoginRedirect(Config::get('relativePath'), true);

    // Inclui a navbar
    require __DIR__ . "/components/navbar.php";

    // Obtém os dados dos sensores
    $sensores = Api::getSensoresData();

    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <div class="container d-flex justify-content-between align-items-center my-3">
        <div id="title-header">
            <h1>Servidor IoT</h1>
            <h6>Utilizador: <?= htmlspecialchars($Auth->getUser()) ?></h6>
        </div>
        <div class="text-end">
            <img class="imagemEstg w-75" src="img/estgRecortado.png" alt="Logótipo ESTG">
        </div>
    </div>

    <h6 class="container lotacaoBus mb-4" id="lotacaoBus">
        Quantidade Pessoas Autocarro: <?= htmlspecialchars(Logica::getLotacao()) ?>
    </h6>

    <div class="container h-100 d-flex flex-column justify-content-center">
        <div class="row justify-content-center">
            <?php
            if (!empty($sensores)) :
                foreach ($sensores as $sensor) :

                    // Tratamento do valor do sensor
                    if ($sensor['unidade'] === "VF") {
                        // VF significa Verdadeiro/Falso - Corrigido operador de atribuição (== -> =)
                        $sensorValor = ($sensor['valor'] == "0") ? "Ativo" : "Inativo";
                    } else {
                        $sensorValor = $sensor['valor'] . $sensor['unidade'];
                    }

                    // Escapa os valores para evitar XSS
                    $nomeSensor = htmlspecialchars($sensor['nome']);
                    $valorSensor = htmlspecialchars($sensorValor);
                    $dataAtualizacao = htmlspecialchars($sensor['data_de_atualizacao']);
                    $imagemSensor = htmlspecialchars($sensor['imagem']);
                    $linkHistorico = "historico.php?sensor=" . urlencode(str_replace(' ', '_', trim($sensor['nome'])));

                    // Botão para sensores VF (Ativar/Desativar)
                    $botaoAtivacao = '';
                    if ($sensor['unidade'] === "VF") {
                        if ($sensor['valor'] == "0") {
                            $botaoAtivacao = '<input class="btn btn-success" id="BotaoAtivacao" type="button" value="Ativar">';
                        } else {
                            $botaoAtivacao = '<input class="btn btn-danger" id="BotaoAtivacao" type="button" value="Desativar">';
                        }
                    } else {
                        // Controlo de + e - para sensores com valores numéricos
                        $botaoAtivacao = '
                            <div class="d-flex bg-light displayTemp align-items-center justify-content-center gap-3 w-100">
                                <button class="btn btn-primary btn-decrease">−</button>
                                <div class="temp-display">' . htmlspecialchars($sensor['valor']) . '</div>
                                <button class="btn btn-primary btn-increase">+</button>
                            </div>';
                    }
            ?>
                    <div class="col col-sm-4 m-2 cartoesSensores">
                        <div class="card shadow-sm" data-sensor="<?= $nomeSensor ?>">
                            <div class="card-header sensor"><b><?= $nomeSensor ?>: <?= $valorSensor ?></b></div>
                            <div class="card-body text-center">
                                <img alt="Fotografia do sensor <?= $nomeSensor ?>" src="<?= $imagemSensor ?>">
                            </div>
                            <div class="card-footer">
                                <span><b>Atualização:</b> <?= $dataAtualizacao ?> - <a href="<?= $linkHistorico ?>">Histórico</a></span>
                            </div>
                            <?= $botaoAtivacao ?>
                        </div>
                    </div>
            <?php
                endforeach;
            else :
                echo '<p class="text-center">Nenhum sensor disponível.</p>';
            endif;
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
                            if (!empty($sensores)) :
                                foreach ($sensores as $sensor) :

                                    // Estado de alerta (pode ser expandido para lógica real)
                                    $estadoClasse = 'text-bg-primary';
                                    $sensorValor = ($sensor['unidade'] === "VF")
                                        ? (($sensor['valor'] == "0") ? "Ativo" : "Inativo")
                                        : $sensor['valor'] . $sensor['unidade'];

                                    $nomeSensor = htmlspecialchars($sensor['nome']);
                                    $valorSensor = htmlspecialchars($sensorValor);
                                    $dataAtualizacao = htmlspecialchars($sensor['data_de_atualizacao']);
                            ?>
                                    <tr data-sensor="<?= $nomeSensor ?>t">
                                        <th scope="row"><?= $nomeSensor ?></th>
                                        <td><?= $valorSensor ?></td>
                                        <td><?= $dataAtualizacao ?></td>
                                        <td>
                                            <div class="badge rounded-pill <?= $estadoClasse ?>">Normal</div>
                                        </td>
                                    </tr>
                            <?php
                                endforeach;
                            else :
                                echo '<tr><td colspan="4" class="text-center">Nenhum sensor disponível.</td></tr>';
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . "/components/footer.php"); ?>
</body>

</html>
