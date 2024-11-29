<?php
session_start();
require 'config.php';

// Certifique-se de que nada foi enviado antes do redirecionamento
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o ID foi passado na URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // Se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';

        if (!empty($motivo)) {
            try {
                // Atualiza o banco de dados com o motivo do cancelamento
                $sql = $pdo->prepare("UPDATE clientes SET status = 0, motivo_cancelamento = :motivo WHERE id = :id");
                $sql->bindValue(':motivo', $motivo);
                $sql->bindValue(':id', $id);
                $sql->execute();

                // Mensagem de sucesso
                $_SESSION['mensagem'] = "<div class='alert alert-success container' role='alert'>Reserva cancelada com sucesso!</div>";
                
                // Redireciona para a página inicial (index.php) com um atraso para mostrar o alerta
                echo "<script>
                        alert('Reserva cancelada com sucesso!');
                        window.location.href = 'index.php';
                      </script>";
                exit;
            } catch (PDOException $e) {
                // Captura qualquer erro e exibe uma mensagem
                $_SESSION['mensagem'] = "<div class='alert alert-danger container' role='alert'>Erro ao cancelar a reserva: " . $e->getMessage() . "</div>";
            }
        } else {
            $_SESSION['mensagem'] = "<div class='alert alert-danger container' role='alert'>Por favor, informe o motivo do cancelamento.</div>";
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!-- Formulário para capturar o motivo do cancelamento -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelar Reserva</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Cancelar Reserva</h2>
            </div>
            <div class="card-body">
                <!-- Exibe mensagens de erro ou sucesso -->
                <?php
                if (isset($_SESSION['mensagem'])) {
                    echo $_SESSION['mensagem'];
                    unset($_SESSION['mensagem']); // Limpa a mensagem após exibir
                }
                ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="motivo">Motivo do Cancelamento:</label>
                        <textarea name="motivo" id="motivo" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Confirmar Cancelamento</button>
                    <a href="index.php" class="btn btn-secondary">Voltar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
