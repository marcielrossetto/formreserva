<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}
require 'cabecalho_pesquisar_data.php';
?>
<meta id="viewport" name="viewport" content="width=device-width,user-scalable=no">
<div class="container-fluid">
    <?php
    $filtro = isset($_POST['filtro']) ? $_POST['filtro'] : "";
    if ($filtro != "") {
        $data_formatada = date("d/m/Y", strtotime($filtro));
        print "<br><h6>Data <strong>$data_formatada</strong> das 12:00 às 17:59 hs</h6><br>";
    }
    ?>

    <?php
    $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE data = '$filtro' AND horario BETWEEN '12:00:00' AND '17:59:00' AND status !=0 ORDER BY `data` ASC";
    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total_pessoas'];
        echo "<h4>Total de pessoas: $total_pessoas</h4><br>";
    }
    ?>
    
    <style>
        /* Estilos gerais da tabela */
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

        /* Estilos específicos para impressão */
        @media print {
            table {
                width: 100%;
                table-layout: auto;
            }

            td, th {
                padding: 8px;
                border: 1px solid #ddd;
            }

            .obs-column .container {
                min-width: 200px;
                height: auto;
                font-size: 10px;
                overflow-y: visible;
            }
        }
    </style>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>Quant</th>
                    <th>Hs</th>
                    <th>Tel</th>
                    <th>Evento</th>
                    <th>Pgnto</th>
                    <th>Obs</th>
                    <th>Emis</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM clientes WHERE data = '$filtro' AND horario BETWEEN '12:00:00' AND '17:59:00' AND status != 0 ORDER BY `data` ASC";
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
                        echo '<td>
                                <div class="btn-group">
                                    <a class="btn btn-primary pequeno" href="editar_reserva.php?id='.$clientes['id'].'">Editar</a>
                                    <a class="btn btn-danger pequeno" href="excluir_reserva.php?id='.$clientes['id'].'">Excluir</a>
                                </div>
                              </td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
