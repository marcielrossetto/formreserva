<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: pesquisar_data.php");
    exit;
}
require 'cabecalho_pesquisar_data.php';
?>

<meta id="viewport" name="viewport" content="width=device-width,user-scalable=no">
<div class="container-fluid">
    <h3>Pesquisar reserva</h3>
    <?php
    $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";
    if ($filtro != "") {
        $data_formatada = date("d/m/Y", strtotime($filtro));
        print "<h4>Resultados encontrados na data <strong>$data_formatada</strong></h4><br>";
    }
    ?>

    <?php
    $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE data = '$filtro' ORDER BY `data` ASC";
    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total_pessoas'];
        echo "<h4>Total de pessoas: $total_pessoas</h4><br>";
    }
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm table-success">
            <tr>
                <th>Nome:</th>
                <th>Data:</th>
                <th>N° pessoas:</th>
                <th>Horário:</th>
                <th>Telefone:</th>
                <th>Telefone 2:</th>
                <th>Tipo de Evento:</th>
                <th>Forma pagamento:</th>
                <th>Obs:</th>
                <th>Data Emissão:</th>
                <th>Ações:</th>
            </tr>
            <?php
            $sql = "SELECT * FROM clientes WHERE data = '$filtro' ORDER BY `data` ASC";
            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0) {
                foreach ($sql->fetchAll() as $clientes) {
                    echo '<tr>';
                    echo '<td>'.$clientes['nome'].'</td>';
                    echo '<td>'.date("d/m/Y", strtotime($clientes['data'])).'</td>';
                    echo '<td>'.$clientes['num_pessoas'].'</td>';
                    echo '<td>'.$clientes['horario'].'</td>';
                    echo '<td>'.$clientes['telefone'].'</td>';
                    echo '<td>'.$clientes['telefone2'].'</td>';
                    echo '<td>'.$clientes['tipo_evento'].'</td>';
                    echo '<td>'.$clientes['forma_pagamento'].'</td>';
                    echo '<td>'.$clientes['observacoes'].'</td>';
                    echo '<td>'.$clientes['data_emissao'].'</td>';
                    echo '<td><div class="btn-group"><a class="btn btn-primary pequeno" href="editar_reserva.php?id='.$clientes['id'].'">Editar</a><a class="btn btn-danger pequeno" href="excluir_reserva.php?id='.$clientes['id'].'">Excluir</a></div></td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>
