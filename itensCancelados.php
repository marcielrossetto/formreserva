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
<div class="container-fluid my-4">
    <div class="bg-light p-4 rounded shadow-sm">
        <h2 class="text-center mb-4">Gerenciar Reservas</h2>
        <form method="POST" class="form-inline row justify-content-center">
            <div class="input-group col-md-6 col-sm-12 mb-3">
                <input class="form-control" name="filtro_pesquisar" required type="text" placeholder="Pesquisar por nome ou telefone">
                <div class="input-group-append">
                    <button class="btn btn-success" type="submit">Pesquisar</button>
                </div>
            </div>
        </form>

        <?php
        $filtro_pesquisar = isset($_POST['filtro_pesquisar']) ? $_POST['filtro_pesquisar'] : "";
        if ($filtro_pesquisar != "") {
            echo "<h6 class='text-center'>Resultado para <strong>'$filtro_pesquisar'</strong></h6><br>";
        }

        // Busca registros com status = 0
        $sql = "SELECT SUM(num_pessoas) AS total_pessoas FROM clientes WHERE (nome LIKE '%$filtro_pesquisar%' OR telefone LIKE '%$filtro_pesquisar%' OR telefone2 LIKE '%$filtro_pesquisar%') AND status = 0";
        $stmt = $pdo->query($sql);
        $total_pessoas = 0;
        if ($stmt->rowCount() > 0) {
            $total_pessoas = $stmt->fetch()['total_pessoas'];
            echo "<h6 class='text-center'>Total de Pessoas: <span class='badge badge-primary'>$total_pessoas</span></h6><br>";
        } else {
            echo "<h6 class='text-center'>Nenhuma reserva encontrada com status ativo.</h6><br>";
        }
        ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Data</th>
                        <th>N° Pessoas</th>
                        <th>Horário</th>
                        <th>Telefone</th>
                        <th>Observações</th>
                        <th>Motivo Cancelamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Busca registros com status = 0
                $sql = "SELECT * FROM clientes WHERE (nome LIKE '%$filtro_pesquisar%' OR telefone LIKE '%$filtro_pesquisar%' OR telefone2 LIKE '%$filtro_pesquisar%') AND status = 0 ORDER BY `data` ASC";
                $stmt = $pdo->query($sql);
                if ($stmt->rowCount() > 0) {
                    foreach ($stmt->fetchAll() as $clientes) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($clientes['nome']) . '</td>';
                        echo '<td>' . date("d/m/Y", strtotime($clientes['data'])) . '</td>';
                        echo '<td>' . htmlspecialchars($clientes['num_pessoas']) . '</td>';
                        echo '<td>' . htmlspecialchars($clientes['horario']) . '</td>';
                        echo '<td>' . htmlspecialchars($clientes['telefone']) . '</td>';
                        echo '<td class="obs-column"><div class="obs-content">' . nl2br(htmlspecialchars($clientes['observacoes'])) . '</div></td>';
                        echo '<td>' . htmlspecialchars($clientes['motivo_cancelamento']) . '</td>';

                        // Link para WhatsApp
                        $telefone = preg_replace('/[^0-9]/', '', $clientes['telefone']); 
                        $mensagem = "Olá " . ucfirst(strtolower($clientes['nome'])) . "! Estamos entrando em contato para remarcar, caso tenha interesse da sua reserva feita para " . $clientes['num_pessoas'] . " pessoas no dia " . date("d/m/Y", strtotime($clientes['data'])) . " às " . $clientes['horario'] . " hs. Se tiver interesse em remarcar me confirme com um OK para prosseguir para reagendamento.";
                        $link_whatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);

                        echo '<td>
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-primary" href="ativar_reserva.php?id=' . $clientes['id'] . '">Ativar Reserva</a>
                                    <a class="btn btn-sm btn-success" href="' . $link_whatsapp . '" target="_blank">Confirmar</a>
                                </div>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8" class="text-center">Nenhuma reserva encontrada com status ativo.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Estilo geral */
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    /* Botões */
    .btn-group .btn {
        margin: 0 3px;
    }
    .btn-group .btn:hover {
        transform: scale(1.05);
        transition: all 0.3s ease;
    }

    /* Tabela */
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
    .table thead th {
        background-color: #343a40;
        color: #fff;
    }

    /* Observações */
    .obs-content {
        max-width: 250px;
        max-height: 100px;
        overflow-y: auto;
        white-space: pre-wrap;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 5px;
        border-radius: 4px;
    }

    /* Input de pesquisa */
    .input-group input {
        border-radius: 0.25rem 0 0 0.25rem;
    }
    .input-group .btn {
        border-radius: 0 0.25rem 0.25rem 0;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .btn-group .btn {
            width: 100%;
            margin: 5px 0;
        }

        .obs-content {
            max-width: 100%;
        }
    }
</style>

