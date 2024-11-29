<?php
session_start();
require 'config.php';

// Verifica se os dados do formulário foram enviados
if (!empty($_POST['email'])) {
    $email = addslashes($_POST['email']);
    $senha = md5(addslashes($_POST['senha']));

    // Prepara e executa a consulta SQL
    $sql = $pdo->prepare("SELECT * FROM login WHERE email = :email AND senha = :senha");
    $sql->bindValue(":email", $email);
    $sql->bindValue(":senha", $senha);
    $sql->execute();

    // Verifica se a consulta retornou algum resultado
    if ($sql->rowCount() > 0) {
        $sql = $sql->fetch();
        $_SESSION['mmnlogin'] = $sql['id'];
        header("Location: index.php");
        exit;
    } else {
        echo "<br><div class='alert alert-danger container' role='alert'>Usuário ou senha incorretos.</div>";
    }
}
?>

<?php 
require 'cabecalho.php';
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Login</h5>
        </div>
        <div class="modal-body">
            <form method="POST">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input id="email" class="form-control" type="email" name="email" required />
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input id="senha" class="form-control" type="password" name="senha" required /><br>
                </div>
                <input class="btn btn-primary btn-lg" type="submit" value="Entrar" />
                <a class="btn btn-primary btn-lg" href="senhaentrar.php">Cadastrar</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
