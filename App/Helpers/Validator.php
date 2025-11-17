<?php

namespace App\Helpers;

class Validator {
    private $errors;

    public function __construct() {
        $this->errors = array();
    }

    public function Requerido($campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            $this->errors[$campo] = "El campo $campo no puede estar vacío";
            return false;
        }
        return true;
    }

    public function EnteroRango($campo,$min=PHP_INT_MIN,$max=PHP_INT_MAX)
    {
        if(!filter_var($_POST[$campo],FILTER_VALIDATE_INT,
            ["options"=>["min_range"=>$min,"max_range"=>$max]]))
        {
            $this->errors[$campo]="Debe ser entero entre $min y $max";
            return false;
        }
        return true;
    }

    public function CadenaRango($campo,$max,$min=0)
    {
        if(!(strlen($_POST[$campo])>$min && strlen($_POST[$campo])<$max))
        {
            $this->errors[$campo]="Debe tener entre $min y $max caracteres";
            return false;
        }
        return true;

    }

    public function Email($campo)
    {
        if(!filter_var($_POST[$campo],FILTER_VALIDATE_EMAIL))
        {
            $this->errors[$campo]="Debe ser un email válido";
            return false; 
        }
        return true;
    }

    public function Dni($campo)
    {
        $letras="TRWAGMYFPDXBNJZSQVHLCKE";
        $mensaje="";
        if(preg_match("/^[0-9]{8}[a-zA-z]{1}$/",$_POST[$campo])==1)
        {
            $numero=substr($_POST[$campo],0,8);
            $letra=substr($_POST[$campo],8,1);
            if($letras[$numero%23]==strtoupper($letra))
            {
                return TRUE;
            }
            else
            {
                $mensaje="El campo $campo es un Dni con letra no válida";
            }
        }
        else
        {
            $mensaje="El campo $campo no es un Dni válido";
        }
        $this->errors[$campo]=$mensaje;
        return FALSE;
    }

    public function Patron($campo,$patron)
    {
        if(!preg_match($patron,$_POST[$campo]))
        {
            $this->errors[$campo]="No cumple el patrón $patron";
            return false;
        }
        return true;
    }

    public function ValidaConFuncion($campo,$funcion,$mensaje)
    {
        if(!call_user_func($funcion))
        {
            $this->errors[$campo]=$mensaje;
            return false;
        }
        return true;
    }

    public function ValidacionPasada()
    {
        if(count($this->errors)!=0)
        {
            return false;
        }
        return true;
    }

    public function ImprimirError($campo)
    {
        return
        isset($this->errors[$campo])?'<span class="error_mensaje">'.$this->errors[$campo].'</span>':'';
    }

    public function getValor($campo)
    {
        return
        isset($_POST[$campo])?$_POST[$campo]:'';
    }

    public function getSelected($campo,$valor)
    {
        return
        isset($_POST[$campo]) && $_POST[$campo]==$valor?'selected':'';
    }

    public function getChecked($campo,$valor)
    {
        return
        isset($_POST[$campo]) && $_POST[$campo]==$valor?'checked':'';
    }
}
?>