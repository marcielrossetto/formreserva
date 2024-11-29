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
        print "<br><h6>Resultado: <strong>$data_formatada</strong></h6><br>";
    }
    $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE data = '$filtro' AND status != 0 ORDER BY data ASC";
    $sql = $pdo->query($sql);
    $total_pessoas = 0;
    if ($sql->rowCount() > 0) {
        $total_pessoas = $sql->fetch()['total_pessoas'];
        echo "<h6>Total de pessoas: $total_pessoas</h6><br>";
    }
    ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-sm">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Data</th>
                    <th>N° Pessoas</th>
                    <th>Horário</th>
                    <th>Telefone</th>
                    <th>Tipo de Evento</th>
                    <th>Comanda</th>
                    <th>Obs</th>
                    <th>Data Emissão</th>
                    <th>Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <style>
                    .obs-column .container {
                        width: 200px;
                        height: 70px;
                        overflow-y: auto;
                        white-space: normal;
                        word-wrap: break-word;
                        border: 1px solid #ddd;
                        padding: 5px;
                        box-sizing: border-box;
                        font-size: 12px;
                        background-color: #f9f9f9;
                    }

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

                    .btn-success {
                        background-color: #28a745;
                    }

                    /* Ajustes para impressão */
                    @media print {
                        table {
                            table-layout: auto;
                            width: 100%;
                        }

                        td, th {
                            min-width: 100px;
                        }

                        .obs-column .container {
                            width: 150px;
                            height: 50px;
                            font-size: 14px;
                        }
                    }
                </style>

                <?php
                $sql = "SELECT * FROM clientes WHERE data = '$filtro' AND status != 0 ORDER BY data ASC";
                $sql = $pdo->query($sql);
                if ($sql->rowCount() > 0) {
                    foreach ($sql->fetchAll() as $clientes) {
                        $nome = ucfirst(strtolower($clientes['nome']));
                        $data = htmlspecialchars(date("d/m/Y", strtotime($clientes['data'])), ENT_QUOTES, 'UTF-8');
                        $horario = htmlspecialchars(date("H:i", strtotime($clientes['horario'])), ENT_QUOTES, 'UTF-8');
                        $num_pessoas = $clientes['num_pessoas'];
                        $telefone = preg_replace('/[^0-9]/', '', $clientes['telefone']); // Remove caracteres não numéricos

                        $mensagem = "Olá $nome!, Tudo bem? Aqui é da Churrascaria Verdanna! Estamos passando para confirmar sua reserva para $num_pessoas pessoas no dia $data, às $horario hs. Se tudo estiver certo, me confirma com um OK. Agradecemos pela confiança e desejo um excelente dia! Qualquer dúvida, estamos à disposição!";

                        $link_whatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($clientes['nome'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>' . date("d/m/Y", strtotime($clientes['data'])) . '</td>';
                        echo '<td>' . $clientes['num_pessoas'] . '</td>';
                        echo '<td>' . $clientes['horario'] . '</td>';
                        echo '<td>' . $clientes['telefone'] . '</td>';
                        echo '<td>' . $clientes['tipo_evento'] . '</td>';
                        echo '<td>' . $clientes['forma_pagamento'] . '</td>';
                        echo '<td class="obs-column"><div class="container">' . nl2br(htmlspecialchars($clientes['observacoes'], ENT_QUOTES, 'UTF-8')) . '</div></td>';
                        echo '<td>' . date("d/m/Y H:i", strtotime($clientes['data_emissao'])) . '</td>';
                        echo '<td>' . htmlspecialchars($clientes['usuario_id'], ENT_QUOTES, 'UTF-8') . '</td>';
                        echo '<td>
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-sm" href="editar_reserva.php?id=' . $clientes['id'] . '">Editar</a>
                                    <a class="btn btn-danger btn-sm" href="excluir_reserva.php?id=' . $clientes['id'] . '">Excluir</a>
                                    <a class="btn btn-success btn-sm" href="' . $link_whatsapp . '" target="_blank">Confirmar</a>
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
