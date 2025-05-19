<?php

    namespace Models;

    require_once(dirname(__FILE__) . "/../config/config.php");

    use Config\Config;
    //Classe sensor para melhor organização dos dados
    class Sensor
    {
        private $nome;
        private $valor;
        private $dataDeAtualizacao;
        private $unidade;
        private $log;
        private $imagem;
        private static $sensores = [];
        
        //Criação de um sensor
        public function __construct($nome, $valor, $unidade, $data, $log)
        {
            $this->nome = $nome;
            $this->valor = $valor;
            $this->dataDeAtualizacao = $data;
            $this->unidade = $unidade;
            $this->imagem = "img/" . strtolower(str_replace(' ', '_', trim($nome))) . ".png";
            $this->log = $log;

        }

        //Função estatica para receber um sensor por nome
        public static function getSensorByName($nome)
        {
            foreach (Sensor::getSensores() as $sensor) {
                if (str_replace(' ', '', trim($sensor->getNome())) === $nome) {
                  
                    return $sensor;
                }
            }
            return null;
        }
        //Função para salvar o valor em ficheiro
        private function escreveFicheiro($caminho, $valor, $log =false)
        {
            if (file_exists($caminho) && !$log) {
                unlink($caminho);
            }
            $ficheiro = fopen($caminho, 'a');
            fwrite($ficheiro, $valor);
            fclose($ficheiro);
        }
        //receber a lista estática dos sensores
        public static function getSensores()
        {
            return self::$sensores;
        }
        //mudar valores do sensor
        public function setValores($valor, $hora)
        {
            $this->valor = $valor;
            $this->dataDeAtualizacao = $hora;
        $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/valor.txt",$valor);
        $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/hora.txt", $this->dataDeAtualizacao);
        $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/log.txt",  $valor ."," . $this->dataDeAtualizacao."\n", true);
        
        }
        

        //Limpar logs
        public static function LimparLogs()
        {
            foreach(self::$sensores as $sensor)
            {
                $sensor->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $sensor->nome . "/log.txt", " ", false);
            }   
        }

        //atualizar valores do sensor pelo carregamento do arquivo
        public function atualizaValores()
        {
            $caminho = Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome;
            $valor = file_get_contents($caminho . '/valor.txt');
            $data = file_get_contents($caminho . '/hora.txt');
            $log = file_get_contents($caminho . '/log.txt');
            $this->valor = $valor;
            $this->dataDeAtualizacao = $data;
            $this->log = $log;
        }
        //Carregar o sensor de uma pasta
        public static function carregaSensorDaPasta($folderName)
        {
            $caminho = Config::get("rootPath") . Config::get("sensorPath") . '/' . $folderName;

            if (!is_dir($caminho)) {
                return null;
            }

            $nome = file_get_contents($caminho . '/nome.txt');
            $valor = file_get_contents($caminho . '/valor.txt');
            $data = file_get_contents($caminho . '/hora.txt');
            $log = file_get_contents($caminho . '/log.txt');
            $unidade = file_get_contents($caminho . '/unidade.txt');
            if (empty($nome) || (empty($valor) && $valor != 0) || empty($data)) {
                return null;
            }

            return new Sensor($nome, $valor, $unidade, $data,$log);
        }
        //Fazer loop das pastas dos sensores e carregar cada sensor individualmente
        public static function carregarSensoresDosFicheiros()
        {
            self::$sensores = [];
            $diretorioSensores = Config::get("rootPath") . Config::get("sensorPath");

            if (is_dir($diretorioSensores)) {
                $pastas = scandir($diretorioSensores);
                foreach ($pastas as $pasta) {
                    if ($pasta !== '.' && $pasta !== '..' && !strpos($pasta, ".")) {
                        $sensor = self::carregaSensorDaPasta($pasta);
                        if ($sensor !== null) {
                            self::$sensores[] = $sensor;
                        }
                    }
                }
                
            }
        }
        //Getters e setters
        public function getNome()
        {
            return $this->nome;
        }

        public function getValor()
        {
            return $this->valor;
        }

        public function getDataDeAtualizacao()
        {
            return $this->dataDeAtualizacao;
        }

        public function getUnidade()
        {
            return $this->unidade;
        }

        public function getImagem()
        {
            return $this->imagem;
        }

        public function getLogs()
        {
            return $this->log;
        }

        //Esta função é util para retornar varios valores do sensor organizados
        public function toArray()
        {
            return [
                'nome' => $this->nome,
                'valor' => $this->valor,
                'data_de_atualizacao' => $this->dataDeAtualizacao,
                'unidade' => $this->unidade,
                'imagem' => $this->imagem
            ];
        }

    }



    

    Sensor::carregarSensoresDosFicheiros();
    if(isset($_GET['limparLogs']))
    {
        Sensor::LimparLogs();
        echo "Logs apagadas";
    }
