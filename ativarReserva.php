<?php 
require 'cabecalho.php';
session_start();
require 'config.php';

if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && empty($_GET['id']) == false) {
    $id = addslashes($_GET['id']);
    $sql = "UPDATE clientes SET status = 1 WHERE id = '$id'";
    $pdo->query($sql);

    // Redirecionar antes de enviar qualquer saída
    header("Location: itensCancelados.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
