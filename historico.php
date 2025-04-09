<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Histórico - Plataforma IoT</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="css/historico.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="js/historico.js"></script>
</head>

<body>
    <?php
    require_once(__DIR__ . "/services/Auth.php");
    require_once(__DIR__ . "/config/config.php");

    use Services\Auth;
    use Config\Config;
    //Verifica se o utilizador está com login
    $Auth = new Auth();
    $Auth->checkLoginRedirect(Config::get('relativePath'), true);
    //Importa Navbar
    require __DIR__ . "/components/navbar.php";

    ?>

    <div class="container d-flex justify-content-between align-items-center my-4">
        <div id="title-header">
            <h1>Servidor IoT</h1>
            <h6>Utilizador: <?php echo $Auth->getUser(); ?></h6>
        </div>
        <div class="text-end">
            <img class="imagemEstg w-75" src="img/estgRecortado.png" alt="ESTG Logo">
        </div>
    </div>

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header bg-dark text-white text-center">
                <h2>Histórico de Valores</h2>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <div class="w-100">
                        <label class="d-flex text-center mb-2 labelFiltro">Filtro:
                            <select id="filtroEstado" class="ms-2 form-control form-control-sm" style="width: 177px;">
                                <option value="">Todos</option>
                                <?php
                                require_once __DIR__ . "/models/sensor.php";
                                use Models\Sensor;
                                //Cria uma opção filtro para cada sensor
                                foreach (Sensor::getSensores() as $sensor) {
                                    $selected = false;
                                    if(isset($_GET['sensor']) && $_GET['sensor'] == $sensor->getNome())
                                        $selected = true;
                                    echo '<option ' . ($selected ? "selected" : "") . ' value="' . $sensor->getNome() . '">' . $sensor->getNome() . '</option>';

                                }

                                ?>
                            </select>
                        </label>
                    </div>
                        <!--Esta tabela usa bibliotecas datatables.js e jquery-->
                    <table id="tabelaHistorico" class="table table-striped table-bordered" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Sensor</th>
                                <th>Valor</th>
                                <th>Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody id="historico-body">
                            <!-- Dados do histórico serão inseridos aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   <?php require_once(__DIR__ . "/components/footer.php"); ?>
</body>

</html>