<?php
class App{

    static $db = null;

    static function getDatabase(){
        if(!self::$db){
            self::$db = new Database('root', 'root', 'espacemembres');
        }
        return self::$db;
    }

    static function getAuth() {
        return new Auth(Session::getInstance(), ['restrinction_msg' => 'lol tu es bloqué']);
    }

    static function redirect($page) {
        header("Location: $page");
        exit();
    }

}