<?php
namespace Services;

class Logica{

    public static function logicaTemperatura($valor)
    { 
        if((float)$valor >=22)
            return 1;
        else
            return 0;

    }
  

}


?>