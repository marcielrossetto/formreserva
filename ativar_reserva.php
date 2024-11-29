<?php
session_start();
require 'config.php';

// Certifique-se de que nada foi enviado antes do redirecionamento
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = addslashes($_GET['id']);
    
    try {
        $sql = "UPDATE clientes SET status = 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Mostrar alerta de sucesso
            echo "<script>
                    alert('Reserva ativada com sucesso!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            // Em caso de erro
            echo "<script>
                    alert('Erro ao atualizar o status.');
                    window.location.href = 'index.php';
                  </script>";
        }

        exit;
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
