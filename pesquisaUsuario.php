<?php
session_start();
require 'config.php';
if (!empty($_POST['email']) && !empty($_POST['senha'])) {
    $nome = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $senha = md5(addslashes($_POST['senha']));
    $sql = $pdo->prepare("SELECT * FROM login WHERE nome = :nome AND email = :email AND senha = :senha");
    $sql->bindValue(":nome", $nome);
    $sql->bindValue(":email", $email);
    $sql->bindValue(":senha", $senha);
    $sql->execute();
    if ($sql->rowCount() == 0) {
        $sql = $pdo->prepare("INSERT INTO login (nome, email, senha, status) VALUES(:nome, :email, :senha, 0)");
        $sql->bindValue(":nome", $nome);
        $sql->bindValue(":email", $email);
        $sql->bindValue(":senha", $senha);
        $sql->execute();
        header("Location: cadastrousuario.php");
    } else {
        echo "<script>alert('Já existe este usuário cadastrado!'); window.location='index.php';</script>";
    }
}
?>

<?php require 'cabecalho.php'; ?>
<meta id="viewport" name="viewport" content="width=device-width, user-scalable=no">
<div class="container-fluid">
    <h3>Pesquisar Usuário</h3>
    <div class="container-fluid">
        <form method="POST" class="form-inline row">
            <input class="form-control mr-sm-4" name="filtro_pesquisar" required type="text">
            <button href="pesquisarUsuario.php" class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>
    </div>
    <?php
    $filtro_pesquisar = isset($_POST['filtro_pesquisar']) ? $_POST['filtro_pesquisar'] : "";
    print "<p>Resultados encontrados com a palavra <strong>' $filtro_pesquisar '.</strong></p><br>";
    ?>
    <div class="table-responsive table-sm">
        <table class="table table-bordered table-hover table-sm table">
            <tr>
                <th>Id:</th>
                <th>Nome:</th>
                <th>Email:</th>
                <th>Senha:</th>
                <th>Ações:</th>
            </tr>
            <?php
            $sql = "SELECT * FROM login WHERE status = 0";
            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0) {
                foreach ($sql->fetchAll() as $login) {
                    echo '<tr>';
                    echo '<td>' . $login['id'] . '</td>';
                    echo '<td>' . $login['nome'] . '</td>';
                    echo '<td>' . $login['email'] . '</td>';
                    echo '<td>' . $login['senha'] . '</td>';
                    echo '<td> <div class="container btn-group"><a class="btn btn-outline-primary pequeno" href="editarUsuario.php?id=' . $login['id'] . '">Editar</a><br> <a class="btn btn-outline-danger pequeno" href="excluirUsuario.php?id=' . $login['id'] . '">Excluir</a> </div></td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>
<?php require 'rodape.php'; ?>