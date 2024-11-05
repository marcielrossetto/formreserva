<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}
require 'cabecalho_pesquisar_data.php';
?>
<meta id="viewport" name="viewport" content="width=device-width, user-scalable=no">
<div class="container-fluid">
    <?php
    $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";
    if ($filtro != "") {
        $data_formatada = date("d/m/Y", strtotime($filtro));
        print "<br><h6>Resultado:<strong>$data_formatada</strong></h6><br>";
    }
    $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE data = '$filtro' AND status != 0 ORDER BY `data` ASC";
    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total_pessoas'];
        echo "<h6>Total de pessoas: $total_pessoas</h6><br>";
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
                <th>Tipo de Evento:</th>
                <th>Forma pagamento:</th>
                <th class="obs-column">Obs:</th>
                <th>Data Emissão:</th>
                <th>Responsavel</th>

                <th>Ações:</th>
            </tr>
            <?php
            $sql = "SELECT * FROM clientes WHERE data = '$filtro' AND status != 0 ORDER BY `data` ASC";
            $sql = $pdo->query($sql);
            if ($sql->rowCount() > 0) {
                foreach ($sql->fetchAll() as $clientes) {
                    echo '<tr>';
                    echo '<td>'.$clientes['nome'].'</td>';
                    echo '<td>'.date("d/m/Y", strtotime($clientes['data'])).'</td>';
                    echo '<td>'.$clientes['num_pessoas'].'</td>';
                    echo '<td>'.$clientes['horario'].'</td>';
                    echo '<td>'.$clientes['telefone'].'</td>';
                    echo '<td>'.$clientes['tipo_evento'].'</td>';
                    echo '<td>'.$clientes['forma_pagamento'].'</td>';
                    echo '<td class="obs-column"><div class="container">'.$clientes['observacoes'].'</div></td>';
                    echo '<td>'.$clientes['data_emissao'].'</td>';
                    echo '<td>'.$clientes['usuario_id'].'</td>';
                    echo '<td><div class="btn-group"><a class="btn btn-primary pequeno" href="editar_reserva.php?id='.$clientes['id'].'">Editar</a><a class="btn btn-danger pequeno" href="excluir_reserva.php?id='.$clientes['id'].'">Excluir</a></div></td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>
