<?php
session_start();
require 'config.php';

if (!empty($_POST['almoco']) && !empty($_POST['jantar'])) {
    $almoco = addslashes($_POST['almoco']);
    $jantar = addslashes($_POST['jantar']);
    $domingo_almoco = addslashes($_POST['domingo_almoco']);
    $outros = addslashes($_POST['outros']);

    $sql = $pdo->prepare("SELECT * FROM preco_rodizio WHERE almoco = :almoco AND jantar = :jantar AND domingo_almoco = :domingo_almoco AND outros = :outros");
    $sql->bindValue(":almoco", $almoco);
    $sql->bindValue(":jantar", $jantar);
    $sql->bindValue(":domingo_almoco", $domingo_almoco);
    $sql->bindValue(":outros", $outros);
    $sql->execute();

    if ($sql->rowCount() == 0) {
        $sql = $pdo->prepare("INSERT INTO preco_rodizio (almoco, jantar, domingo_almoco, outros) VALUES (:almoco, :jantar, :domingo_almoco, :outros)");
        $sql->bindValue(":almoco", $almoco);
        $sql->bindValue(":jantar", $jantar);
        $sql->bindValue(":domingo_almoco", $domingo_almoco);
        $sql->bindValue(":outros", $outros);
        $sql->execute();
        header("Location: cadastrar_preco_rodizio.php");
    } else {
        echo "<script>alert('Já existe este preço cadastrado!!'); window.location='index.php';</script>";
    }
}
?>

<?php require 'cabecalho.php'; ?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Cadastrar Rodízio</h5>
        </div>
        <div class="modal-body">
            <form method="POST">
                <div class="form-group">
                    Almoço: <input class="form-control" type="text" name="almoco">
                    Jantar: <input class="form-control" type="text" name="jantar">
                    Domingo Almoço: <input class="form-control" type="text" name="domingo_almoco"><br>
                    Desconto: <input class="form-control" type="text" name="outros"><br>
                    <input class="btn btn-primary btn-lg" type="submit" value="Cadastrar">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
