<?php
session_start();
require 'config.php';

// Processamento do formulário
if (!empty($_POST['senha'])) {
    $senha = md5(addslashes($_POST['senha'])); // Criptografa a senha usando MD5

    // Consulta no banco de dados
    $sql = $pdo->prepare("SELECT * FROM login WHERE senha = :senha");
    $sql->bindValue(":senha", $senha);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $usuario = $sql->fetch();
        $_SESSION['mmnlogin'] = $usuario['id'];
        
        header("Location: cadastrar_preco_rodizio.php");
        exit; // Garante que o script não continua
    } else {
        $erro = "Senha incorreta. Você não tem permissão para cadastrar Rodizio!";
    }
}

require 'cabecalho.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senha para Cadastro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Senha para cadastrar valor Rodizío</h5>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input 
                            class="form-control" 
                            type="password" 
                            id="senha" 
                            name="senha" 
                            placeholder="Digite a senha para cadastrar Rodizío." 
                            autofocus 
                            required 
                        />
                    </div>
                    <div class="form-group mt-3">
                        <input class="btn btn-primary btn-lg w-100" type="submit" value="Entrar" />
                    </div>
                </form>
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <?= htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
