<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: pesquisar_diaria_almoco.php");
    exit;
}
require 'cabecalho.php';
?>

<meta id="viewport" name="viewport" content="width=device-width, user-scalable=no">
<div class="container-fluid">
    <br>
    <div class="justify-content-center align-items-center row">
        <h3 class="text-center w-100">Relatório Almoço</h3>
        <form method="POST" class="form-inline row d-flex justify-content-center">
            <input class="form-control mr-sm-4" name="filtro" required type="date">
            <button href="pesquisar.php" class="btn btn-outline-dark my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>
    </div>
    
    <?php
    $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";
    if ($filtro != "") {
        $dataFormatada = date('d/m/Y', strtotime($filtro));
        echo "<p>Resultados com a data <strong>$dataFormatada</strong> <br>das 11:00 às 17:59 hs</p>";
    }

    $stmt = $pdo->prepare("SELECT SUM(num_pessoas) AS total FROM clientes WHERE data = :filtro AND horario BETWEEN '11:00:00' AND '17:59:00' AND status = 1");
    $stmt->execute(['filtro' => $filtro]);
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $row['total'];
        echo "<h4>Total de pessoas: $total</h4><br>";
    }
    ?>
    <button class="btn btn-primary" onclick="printTable()">Imprimir Relatório</button></br>
    <div id="printableTable" class="table-responsive">
        
        <div class="table-responsive table-sm">
            <table class="table table-bordered table-hover table-sm table-warning">
                <tr>
                    <th>Id:</th>
                    <th>Nome:</th>
                    <th>N° pessoas:</th>
                    <th>Horário:</th>
                    <th>Tipo de Evento:</th>
                    <th>Forma pagamento:</th>
                    <th>Telefone:</th>
                    <th>Observações</th>
                    <th>N° mesa:</th>
                </tr>
                <?php
                $sql = "SELECT * FROM clientes WHERE data = '$filtro' AND horario BETWEEN '11:00:00' AND '17:59:00' AND status = 1 ORDER BY `data` ASC";
                $sql = $pdo->query($sql);
                if ($sql->rowCount() > 0) {
                    foreach ($sql->fetchAll() as $clientes) {
                        echo '<tr>';
                        echo '<td>'.$clientes['id'].'</td>';
                        echo '<td>'.$clientes['nome'].'</td>';
                        echo '<td>'.$clientes['num_pessoas'].'</td>';
                        echo '<td>'.$clientes['horario'].'</td>';
                        echo '<td>'.$clientes['tipo_evento'].'</td>';
                        echo '<td>'.$clientes['forma_pagamento'].'</td>';
                        echo '<td>'.$clientes['telefone'].'</td>';
                        echo '<td class="obs-column"><div class="container">'.$clientes['observacoes'].'</div></td>';
                        echo '<td>'.$clientes['num_mesa'].'</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>
        </div>
    </div>

    <script>
    function printTable() {
        var printContents = document.getElementById('printableTable').innerHTML;
        var totalPessoas = "<?php echo '<h4>Total de pessoas: ' . $total . '</h4><br>'; ?>";
        var dataReserva = "<?php echo '<p>Resultados com a data <strong>' . $dataFormatada . '</strong> das 11:00 às 17:59 hs</p>'; ?>";
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = totalPessoas + dataReserva + printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

</div>
