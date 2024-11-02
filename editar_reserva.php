<?php
session_start();
require 'config.php';
require 'cabecalho.php';

$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $dado = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['nome', 'data', 'num_pessoas', 'horario', 'telefone', 'telefone2', 'tipo_evento', 'forma_pagamento', 'valor_rodizio', 'numero_mesa', 'observacoes'];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = $_POST[$field] ?? '';
    }
    $stmt = $pdo->prepare("UPDATE clientes SET nome = :nome, data = :data, num_pessoas = :num_pessoas, horario = :horario, telefone = :telefone, telefone2 = :telefone2, tipo_evento = :tipo_evento, forma_pagamento = :forma_pagamento, valor_rodizio = :valor_rodizio, num_mesa = :numero_mesa, observacoes = :observacoes WHERE id = :id");
    $data['id'] = $id;
    $stmt->execute($data);
    echo "<script>alert('Atualizado com sucesso!'); window.location='index.php'</script>";
    exit;
}

$optionsTipoEvento = ['Aniversario', 'Formatura', 'Casamento', 'Confraternizacao', 'Conf. fim de ano', 'Bodas casamento'];
$optionsFormaPagamento = ['unica', 'individual', 'unica(rod)individual(beb)', 'outros'];
$optionsValorRodizio = ['R$ 69,80', 'R$ 79,80', 'R$ 89,80', 'Valor do dia'];
$optionsNumeroMesa = array_merge(['Salão 1', 'Salão 2', 'Salão 3', 'Próximo a janela', 'Próximo ao jardim', 'Centro do salão'], range(1, 95));
?>

<div class="container col-md-4">
    <h3>Editar <small>reserva</small></h3>
    <hr>
    <form method="POST">
        <div class="form-group">
            Nome: <input id="nome" class="form-control" type="text" name="nome" value="<?= $dado['nome'] ?? '' ?>">
            Data: <input class="form-control" id="data" type="date" name="data" value="<?= $dado['data'] ?? '' ?>">
            Número de pessoas: <input class="form-control" type="number" name="num_pessoas" value="<?= $dado['num_pessoas'] ?? '' ?>">
            Horário: <input class="form-control" type="time" name="horario" value="<?= $dado['horario'] ?? '' ?>">
            Telefone: <input class="form-control" type="int" name="telefone" value="<?= $dado['telefone'] ?? '' ?>">
            Telefone 2: <input class="form-control" type="int" name="telefone2" value="<?= $dado['telefone2'] ?? '' ?>">
            Tipo de Evento:
            <select name="tipo_evento" class="form-control">
                <option value="">Não definido</option>
                <?php foreach ($optionsTipoEvento as $option) : ?>
                    <option value="<?= $option ?>" <?= $dado['tipo_evento'] == $option ? 'selected' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
            Forma de pagamento:
            <select name="forma_pagamento" class="form-control">
                <option value="">Não definido</option>
                <?php foreach ($optionsFormaPagamento as $option) : ?>
                    <option value="<?= $option ?>" <?= $dado['forma_pagamento'] == $option ? 'selected' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
            Valor do Rodízio:
            <select name="valor_rodizio" class="form-control">
                <option value="">Não definido</option>
                <?php foreach ($optionsValorRodizio as $option) : ?>
                    <option value="<?= $option ?>" <?= $dado['valor_rodizio'] == $option ? 'selected' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
            Número de mesa:
            <select name="numero_mesa" class="form-control">
                <option value="">Não definido</option>
                <?php foreach ($optionsNumeroMesa as $option) : ?>
                    <option value="<?= $option ?>" <?= $dado['num_mesa'] == $option ? 'selected' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
            Observações:<br>
            <textarea class="form-control" name="observacoes"><?= $dado['observacoes'] ?? '' ?></textarea><br>
            <input class="btn btn-primary" type="submit" value="Atualizar">
        </div>
    </form>
</div>

<script type="text/javascript" src="bootstrap.min.js"></script>
</body>
</html>
