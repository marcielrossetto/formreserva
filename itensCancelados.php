<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}
require 'cabecalho.php';
?>

<meta id="viewport" name="viewport" content="width=device-width, user-scalable=no">
<div class="container-fluid">
    <h3>Reservas canceladas</h3>
    <div class="container-fluid">
        <form method="POST" class="form-inline row">
            <label>Data</label>
            <input class="form-control" name="filtro" required placeholder="Data inicial" type="date">
            <label>Data</label>
            <input class="form-control" name="filtro2" required type="date">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>
    </div>

    <?php
    $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";
    $filtro2 = isset($_POST['filtro2']) ? $_POST['filtro2'] : "";
    if ($filtro != "" && $filtro2 != "") {
        $data_formatada_inicio = date("d/m/Y", strtotime($filtro));
        $data_formatada_fim = date("d/m/Y", strtotime($filtro2));
        print "<h4>Resultados encontrados entre as datas <strong>$data_formatada_inicio</strong> e <strong>$data_formatada_fim</strong></h4><br>";
    }

    $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE data BETWEEN '$filtro' AND '$filtro2' AND status = 0";
    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total_pessoas'];
        echo "<h4>Total de pessoas: $total_pessoas</h4><br>";
    }
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm table-warning">
            <tr>
                <th>Id:</th>
                <th>Nome:</th>
                <th>Data:</th>
                <th>N° pessoas:</th>
                <th>Horário:</th>
                <th>Mesa:</th>
                <th>R$ rodízio:</th>
                <th>Data Emissão:</th>
                <th>Horário Emissão:</th>
                <th>Ações:</th>
            </tr>
            <?php
            $sql = "SELECT * FROM clientes WHERE data BETWEEN '$filtro' AND '$filtro2' AND status = 0 ORDER BY `data` DESC, `horario` DESC";
            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0) {
                foreach ($sql->fetchAll() as $clientes) {
                    echo '<tr>';
                    echo '<td>'.$clientes['id'].'</td>';
                    echo '<td>'.$clientes['nome'].'</td>';
                    echo '<td>'.date("d/m/Y", strtotime($clientes['data'])).'</td>';
                    echo '<td>'.$clientes['num_pessoas'].'</td>';
                    echo '<td>'.$clientes['horario'].'</td>';
                    echo '<td>'.$clientes['num_mesa'].'</td>';
                    echo '<td>'.$clientes['valor_rodizio'].'</td>';
                    echo '<td>'.date("d/m/Y", strtotime($clientes['data_emissao'])).'</td>';
                    echo '<td>'.date("H:i:s", strtotime($clientes['data_emissao'])).'</td>';
                    echo '<td><div class="btn-group"><a class="btn btn-success" href="ativarReserva.php?id='.$clientes['id'].'">Ativar</a></div></td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>