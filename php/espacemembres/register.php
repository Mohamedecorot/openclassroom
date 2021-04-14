<?php require 'inc/header.php'; ?>

<?php
// si le formulaire d'inscription n'est pas vide
if (!empty($_POST)) {

    $errors = [];

    //connexion à la bdd
    require_once 'inc/connexionDb.php';

    // verification des données
    if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
        $errors['username'] = "votre pseudo n'est pas valide";
    } else {
        // vérification si le pseudo est disponible
        $req = $pdo->prepare('SELECT id FROM membres WHERE username = ?');
        $req->execute([$_POST['username']]);
        $user = $req->fetch();
        if($user) {
            $errors['username'] = 'Ce pseudo est deja utilisé';
        }
    }

    if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "votre email n'est pas valide";
    } else {
        // vérification si l'email est disponible
        $req = $pdo->prepare('SELECT id FROM membres WHERE email = ?');
        $req->execute([$_POST['email']]);
        $user = $req->fetch();
        if($user) {
            $errors['username'] = 'Cet email est deja utilisé pour un autre compte';
        }
    }
    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm'] ) {
        $errors['password'] = "vous devez rentrer un mot de passe valide";
    }


    if(empty($error)) {
        //ajoute des utilisateurs
        $req = $pdo->prepare("INSERT INTO membres SET username = ?, password = ?, email = ?");

        //cryptage du mot de passe de l'utilisateur
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $req->execute([$_POST['username'], $password, $_POST['email']]);
    }
}

?>

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