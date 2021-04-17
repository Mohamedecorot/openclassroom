<?php
require 'inc/functions.php';
logged_only();
require 'inc/header.php';
?>

<h1>Bonjour <?= $_SESSION['auth']->username ?></h1>

<?php require 'inc/footer.php'; ?>