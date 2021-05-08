<?php
class Auth{

    private $options = [
        'restrinction_msg' => "Vous n'avez pas le droit d'accéder à cette page"
    ];

    private $session;

    public function __construct($session, $options = []){
        $this->options = array_merge($this->options, $options);
        $this->session = $session;
    }

    public function register($db, $username, $password, $email) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $token = Str::random(60);
        $db->query("INSERT INTO membres SET username = ?, password = ?, email = ?, confirmation_token = ?", [$username, $password, $email, $token]);
        $user_id = $db->lastInsertId();
        mail($email, 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost:8000/confirm.php?id=$user_id&token=$token");
    }

    public function confirm($db, $user_id, $token) {
        $user = $db->query('SELECT * FROM membres WHERE id = ?', [$user_id])->fetch();

        if($user && $user->confirmation_token == $token) {
            $db->query('UPDATE membres SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
            $this->session->write('auth', $user);
            return true;
        } else {
            return false;
        }
    }

    public function restrict(){
        if(!$this->session->read('auth')){
            $this->session->setFlash('danger', $this->option['restrinction_msg']);
            header('Location: login.php');
            exit();
        }
    }

}