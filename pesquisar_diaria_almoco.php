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
            <button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Pesquisar</button>
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
  
    <style>
        /* Estilos gerais para a tabela */
        .table thead {
            background-color: #f8f9fa;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Estilos para a coluna de observação */
        .obs-column .container {
            width: 200px;
            height: 70px;
            overflow-y: auto; 
            word-wrap: break-word;
            border: 1px solid #ddd;
            padding: 5px;
            box-sizing: border-box;
            font-size: 10px;
            background-color: #f9f9f9;
        }

        /* Estilos para os botões */
        .btn-group .btn {
            margin: 0 5px;
        }

        .btn-group .btn:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        /* Estilos para impressão */
        @media print {
            table {
                width: 100%;
                table-layout: auto;
                border-collapse: collapse;
            }

            td, th {
                padding: 8px;
                border: 1px solid #ddd;
                word-wrap: break-word;
            }

            .obs-column {
                width: 200px !important;
                min-width: 200px !important;
                max-width: 200px !important;
            }

            .obs-column .container {
                width: 100%;
                height: auto;
                font-size: 10px;
                overflow-y: visible;
                white-space: normal;
            }
        }
    </style>

    <button class="btn btn-primary" onclick="printTable()">Imprimir Relatório</button><br><br>
    <div id="printableTable" class="table-responsive">
        <div class="table-responsive table-sm">
            <table class="table table-bordered table-hover table-sm table-warning">
                <tr>
                    <th>Id:</th>
                    <th>Nome:</th>
                    <th>N°:</th>
                    <th>Hs:</th>
                    <th>Tipo de Evento:</th>
                    <th>Pgto:</th>
                    <th>Obs</th>
                    <th>Mesa:</th>
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
    // Obtenção dos valores dinâmicos PHP
    var totalPessoas = "<?php echo isset($total) ? '<h4>Total de pessoas: ' . $total . '</h4><br>' : ''; ?>";
    var dataReserva = "<?php echo isset($dataFormatada) ? '<p>Data <strong>' . $dataFormatada . '</strong> das 11:00 às 17:59 hs</p>' : ''; ?>";

    // Obtenção do conteúdo da tabela para impressão
    var printContents = document.getElementById('printableTable').innerHTML;
    var originalContents = document.body.innerHTML;
    
    // Estilos de impressão específicos
    var printStyles = `
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                table-layout: auto;
                border-collapse: collapse;
            }
            td, th {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: center;
                word-wrap: break-word;
            }
            .obs-column .container {
                width: 100%;
                height: auto;
                font-size: 10px;
                overflow-y: visible;
                white-space: normal;
                word-wrap: break-word;
            }
            @media print {
                .obs-column {
                    width: 200px !important;
                    min-width: 200px !important;
                    max-width: 200px !important;
                }
                .btn {
                    display: none; /* Esconde o botão de impressão na página impressa */
                }
            }
        </style>
    `;
    
    // Substitui o conteúdo da página para impressão
    document.body.innerHTML = totalPessoas + dataReserva + printStyles + printContents;
    
    // Inicia a impressão
    window.print();
    
    // Restaura o conteúdo original da página
    document.body.innerHTML = originalContents;
}
</script>


</div>
<?php require 'rodape.php'; ?>