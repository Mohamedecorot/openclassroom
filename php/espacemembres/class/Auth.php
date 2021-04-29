<?php
class Auth{

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function register($username, $password, $email) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $token = Str::random(60);
        $this->db->query("INSERT INTO membres SET username = ?, password = ?, email = ?, confirmation_token = ?", [$username, $password, $email, $token]);
        $user_id = $this->db->lastInsertId();
        mail($email, 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost:8000/confirm.php?id=$user_id&token=$token");

    }

}