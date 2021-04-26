<?php
class App{

    static $db = null;

    static function getDatabase(){
        if(!self::$db){
            self::$db = new Database('root', 'root', 'espacemembres');
        }
        return self::$db;
    }

}