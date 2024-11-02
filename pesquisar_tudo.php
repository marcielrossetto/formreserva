<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}
require 'cabecalho.php';
?>

<meta id="viewport" name="viewport" content="width=device-width,user-scalable=no">
<div class="container-fluid"><br>
    <div class="container-fluid">
        <form method="POST" class="form-inline row">
            <input class="form-control mr-sm-4" name="filtro_pesquisar" required type="text" placeholder="Pesquisar por nome ou telefone">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>
    </div>

    <?php
    $filtro_pesquisar = isset($_POST['filtro_pesquisar']) ? $_POST['filtro_pesquisar'] : "";
    if ($filtro_pesquisar != "") {
        echo "<h6>Resultado com <strong>'$filtro_pesquisar'</strong></h6><br>";
    }

    $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE (nome LIKE '%$filtro_pesquisar%' OR telefone LIKE '%$filtro_pesquisar%' OR telefone2 LIKE '%$filtro_pesquisar%') AND status != 0";

    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total_pessoas'];
        echo "<h6>Total: $total_pessoas</h6><br>";
    }
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm table-warning">
            <tr>
                <th>Nome:</th>
                <th>Data:</th>
                <th>N° pessoas:</th>
                <th>Horário:</th>
                <th>Telefone:</th>
                <th>Telefone 2:</th>
                <th>Observações</th>
                <th>Ações:</th>
            </tr>
            <?php
           $sql = "SELECT * FROM clientes WHERE (nome LIKE '%$filtro_pesquisar%' OR telefone LIKE '%$filtro_pesquisar%' OR telefone2 LIKE '%$filtro_pesquisar%') AND status != 0 ORDER BY `data` ASC";

            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0) {
                foreach ($sql->fetchAll() as $clientes) {
                    echo '<tr>';
                    echo '<td>' . $clientes['nome'] . '</td>';
                    echo '<td>' . date("d/m/Y", strtotime($clientes['data'])) . '</td>';
                    echo '<td>' . $clientes['num_pessoas'] . '</td>';
                    echo '<td>' . $clientes['horario'] . '</td>';
                    echo '<td>' . $clientes['telefone'] . '</td>';
                    echo '<td>' . $clientes['telefone2'] . '</td>';
                    echo '<td class="obs-column"><div class="container">'.$clientes['observacoes'].'</div></td>';

                    echo '<td><div class="btn-group"><a class="btn btn-primary pequeno" href="editar_reserva.php?id=' . $clientes['id'] . '">Editar</a><br><a class="btn btn-danger pequeno" href="excluir_reserva.php?id=' . $clientes['id'] . '">Excluir</a></div></td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>
