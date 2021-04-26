<?php
// fichier qui se charge de l'initialisation de l'application
spl_autoload_register('app_autoload');

function app_autoload($class){
    require "class/$class.php";
}
