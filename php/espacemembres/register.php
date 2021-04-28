<?php
require_once 'inc/bootstrap.php';

// si le formulaire d'inscription n'est pas vide
if (!empty($_POST)) {

    $errors = [];
    $db = App::getDatabase();
    $validator = new Validator($_POST);
    $validator->isAlpha('username', "Votre pseudo n'est pas valide (alphanumérique)");
    if($validator->isValid()) {
        $validator->isUniq('username', $db, 'membres', 'ce pseudo est déjà pris');
    }
    $validator->isEmail('email', "votre email n'est pas valide");
    if($validator->isValid()) {
        $validator->isUniq('email', $db, 'membres', 'Cet email est deja utilisé pour un autre compte');
    }
    $validator->isConfirmed('password',"vous devez rentrer un mot de passe valide");

    if($validator->isValid()) {
        //cryptage du mot de passe de l'utilisateur
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Pour la validation de compte par email
        $token = str_random(60);
        $db->query("INSERT INTO membres SET username = ?, password = ?, email = ?, confirmation_token = ?", [$_POST['username'], $password, $_POST['email'], $token]);

        $user_id = $db->lastInsertId();
        mail($_POST['email'], 'Confirmation de votre compte', "Afin de valider votre compte, merci de cliquer sur ce lien\n\nhttp://localhost:8000/confirm.php?id=$user_id&token=$token");
        $_SESSION['flash']['success'] = "un email de confirmation vous a été envoyé pour valider pour compte";
        header('Location: login.php');
        exit();
    } else {
        $errors = $validator->getErrors();
    }
}

?>

<?php require 'inc/header.php'; ?>


<h1>S'inscrire</h1>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
    <p>Vous n'avez pas remplit le formulaire correctement</p>
    <ul>
        <?php foreach($errors as $error): ?>
            <li><?= $error; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="" method="POST">
    <div class="form-group">
        <label for="">Pseudo</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="">Email</label>
        <input type="text" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="">Confirmez votre mot de passe</label>
        <input type="password" name="password_confirm" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">M'inscrire</button>
</form>

<?php require 'inc/footer.php'; ?>