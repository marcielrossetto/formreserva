<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: pesquisar.php");
    exit;
}
require 'cabecalho.php';
?>

<meta id="viewport" name="viewport" content="width=device-width, user-scalable=no">
<div class="container-fluid">
    <h3>Pesquisar reserva entre datas</h3>
    <div class="container-fluid">
        <form method="POST" class="form-inline row">
            <label>Data</label>
            <input class="form-control" name="filtro" required placeholder="Data inicial" type="date">
            <label>Data</label>
            <input class="form-control" name="filtro2" required type="date">
            <label>Horário inicial:</label>
            <input class="form-control" name="hora1" required type="time">
            <label>Horário final:</label>
            <input class="form-control" name="hora2" required type="time">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>
    </div>

    <?php
    $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";
    $filtro2 = isset($_POST['filtro2']) ? $_POST['filtro2'] : "";
    $horario1 = isset($_POST['hora1']) ? $_POST['hora1'] : "";
    $horario2 = isset($_POST['hora2']) ? $_POST['hora2'] : "";
    if ($filtro != "" && $filtro2 != "" && $horario1 != "" && $horario2 != "") {
        $data_formatada_inicio = date("d/m/Y", strtotime($filtro));
        $data_formatada_fim = date("d/m/Y", strtotime($filtro2));
        print "<p>Resultados encontrados entre as datas <strong>$data_formatada_inicio</strong> e <strong>$data_formatada_fim</strong> e horários <strong>$horario1</strong> e <strong>$horario2</strong> hs.</p><br>";
    }

    $sql = "SELECT SUM(num_pessoas) as total FROM clientes WHERE data BETWEEN '$filtro' AND '$filtro2' AND horario BETWEEN '$horario1' AND '$horario2' AND status = 1";
    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total'];
        echo "<h4>Total de pessoas: $total_pessoas</h4><br>";
    }
    ?>

    <div class="table-responsive table-sm">
        <table class="table table-bordered table-hover table-sm table">
            <tr>
                <th>Id:</th>
                <th>Nome:</th>
                <th>Data:</th>
                <th>Quant:</th>
                <th>Horário:</th>
                <th>Telefone:</th>
                <th>Telefone 2:</th>
                <th>Evento:</th>
                <th>Conta:</th>
                <th>R$:</th>
                <th>Mesa:</th>
                <th>Obs:</th>
                <th>Data Emissão:</th>
                <th>Horário Emissão:</th>
                <th>Ações:</th>
            </tr>
            <?php
            $sql = "SELECT * FROM clientes WHERE data BETWEEN '$filtro' AND '$filtro2' AND horario BETWEEN '$horario1' AND '$horario2' AND status = 1 ORDER BY `data` DESC, `horario` DESC";
            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0) {
                foreach ($sql->fetchAll() as $clientes) {
                    echo '<tr>';
                    echo '<td>'.$clientes['id'].'</td>';
                    echo '<td>'.$clientes['nome'].'</td>';
                    echo '<td>'.date('d/m/Y', strtotime($clientes['data'])).'</td>';
                    echo '<td>'.$clientes['num_pessoas'].'</td>';
                    echo '<td>'.$clientes['horario'].'</td>';
                    echo '<td>'.$clientes['telefone'].'</td>';
                    echo '<td>'.$clientes['telefone2'].'</td>';
                    echo '<td>'.$clientes['tipo_evento'].'</td>';
                    echo '<td>'.$clientes['forma_pagamento'].'</td>';
                    echo '<td>'.$clientes['valor_rodizio'].'</td>';
                    echo '<td>'.$clientes['num_mesa'].'</td>';
                    echo '<td>'.$clientes['observacoes'].'</td>';
                    echo '<td>'.date('d/m/Y', strtotime($clientes['data_emissao'])).'</td>';
                    echo '<td>'.date('H:i:s', strtotime($clientes['data_emissao'])).'</td>';
                    echo '<td><div class="btn-group"><a class="btn btn-outline-primary pequeno" href="editar_reserva.php?id='.$clientes['id'].'">Editar</a><br><a class="btn btn-outline-danger pequeno" href="excluir_reserva.php?id='.$clientes['id'].'">Excluir</a></div></td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>
