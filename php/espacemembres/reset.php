<?php require 'inc/header.php'; ?>

<?php
require 'inc/bootstrap.php';
if(isset($_GET['id']) && isset($_GET['token'])){
    $auth = App::getAuth();
    $db = App::getDatabase();
    $user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);
    if($user){
        if(!empty($_POST)){
            $validator = new Validator($_POST);
            $validator->isConfirmed('password');
            if($validator->isValid()) {
                $password = $auth->hashPassword($_POST['password']);
                $db->query('UPDATE membres SET password = ?, reset_at = NULL, reset_token = NULL WHERE id = ?', [$password, $_GET['id']]);
                Session::getInstance()->setFlash('success', "Votre mot de passe a bien été modifié");
                $auth->connect($user);
                App::redirect('account.php');
            }
        }
    }else{
        Session::getInstance()->setFlash('danger', "Ce token n'est pas valide");
        App::redirect('login.php');
    }
}else{
    App::redirect('login.php');
}
?>

<h1>Réinitialisation du mot de passe</h1>

<form action="" method="POST">
    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="">Confirmation du mot de passe </label>
        <input type="password" name="password_confirm" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Réinitialiser votre mot de passe</button>
</form>

<?php require 'inc/footer.php'; ?>