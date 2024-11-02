<?php
require 'cabecalho.php';
session_start();
require 'config.php';

if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = addslashes($_GET['id']);
    $sql = "UPDATE clientes SET status = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    echo "<br><div class='alert alert-danger container' role='alert'>Usuário deletado com sucesso!</div>";
    header("Location: excluir_reserva.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
