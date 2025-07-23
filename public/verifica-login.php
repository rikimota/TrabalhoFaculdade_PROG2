<!-- Verifica se há um usuario logado para acessar as páginas do sistema -->
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>