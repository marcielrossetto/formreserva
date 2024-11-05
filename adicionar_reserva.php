<?php
session_start();
require 'config.php';
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

if (!empty($_POST['nome']) && !empty($_POST['data'])) {
    $usuario_id = $_SESSION['mmnlogin'];  // Assumindo que o ID do usuário está armazenado na sessão
    $nome = addslashes($_POST['nome']);
    $data = addslashes($_POST['data']);
    $num_pessoas = addslashes($_POST['num_pessoas']);
    $horario = addslashes($_POST['horario']);
    $telefone = addslashes($_POST['telefone']);
    $telefone2 = addslashes($_POST['telefone2']);
    $tipo_evento = addslashes($_POST['tipo_evento']);
    $forma_pagamento = addslashes($_POST['forma_pagamento']);
    $valor_rodizio = addslashes($_POST['valor_rodizio']);
    $num_mesa = addslashes($_POST['num_mesa']);
    $observacoes = addslashes($_POST['observacoes']);

    $sql = $pdo->prepare("SELECT * FROM clientes WHERE nome = :nome AND data = :data");
    $sql->bindValue(":nome", $nome);
    $sql->bindValue(":data", $data);
    $sql->execute();

    if ($sql->rowCount() == 0) {
        $sql = $pdo->prepare("INSERT INTO clientes (usuario_id, nome, data, num_pessoas, horario, telefone, telefone2, tipo_evento, forma_pagamento, valor_rodizio, num_mesa, observacoes) VALUES(:usuario_id, :nome, :data, :num_pessoas, :horario, :telefone, :telefone2, :tipo_evento, :forma_pagamento, :valor_rodizio, :num_mesa, :observacoes)");
        $sql->bindValue(":usuario_id", $usuario_id);
        $sql->bindValue(":nome", $nome);
        $sql->bindValue(":data", $data);
        $sql->bindValue(":num_pessoas", $num_pessoas);
        $sql->bindValue(":horario", $horario);
        $sql->bindValue(":telefone", $telefone);
        $sql->bindValue(":telefone2", $telefone2);
        $sql->bindValue(":tipo_evento", $tipo_evento);
        $sql->bindValue(":forma_pagamento", $forma_pagamento);
        $sql->bindValue(":valor_rodizio", $valor_rodizio);
        $sql->bindValue(":num_mesa", $num_mesa);
        $sql->bindValue(":observacoes", $observacoes);
        $sql->execute();
        echo "<script>alert('Cadastrado com Sucesso!'); window.location='adicionar_reserva.php'</script>";
    } else {
        echo "<script>alert('Já existe este usuário cadastrado!'); window.location='adicionar_reserva.php'</script>";
    }
}

require 'cabecalho.php';

// Buscar o último preço do rodízio
$sql = $pdo->query("SELECT * FROM preco_rodizio ORDER BY id DESC LIMIT 1");
$ultimo_preco = $sql->fetch(PDO::FETCH_ASSOC);
?>

<div class="container col-md-6">
    <h5>Cadastro Nova <small>Reserva</small></h5>
    <hr>
    <form method="POST" onsubmit="return validarFormulario()">
        Nome: <input id="nome" class="form-control" type="text" name="nome" placeholder="Digite o nome">
        Data: <input class="form-control" id="data" required type="date" name="data">
        Número de pessoas: <input maxlength="2" required class="form-control" type="number" name="num_pessoas" placeholder="Digite quantidade de pessoas">
        Horário: <input class="form-control" type="time" required name="horario">
        Telefone: <input class="form-control" type="number" name="telefone" placeholder="Digite um número de telefone">
        Telefone: <input class="form-control" type="number" name="telefone2" placeholder="Digite um número de telefone">
        Forma de pagamento:
        <select name="forma_pagamento" class="form-control">
            <option class="form-control" value="Não definido"></option>
            <option class="form-control" value="unica">Única</option>
            <option class="form-control" value="individual">Individual</option>
            <option class="form-control" value="U (rod) I (beb)">Única (rod) Individual (beb)</option>
            <option class="form-control" value="outros">Outros</option>
        </select>
        Tipo de Evento:
        <select name="tipo_evento" class="form-control">
            <option class="form-control" value="Não definido"></option>
            <option class="form-control" value="Aniversario">Aniversário</option>
            <option class="form-control" value="Conf. fim de ano">Confraternização Fim de Ano</option>
            <option class="form-control" value="Formatura">Formatura</option>
            <option class="form-control" value="casamento">Casamento</option>
            <option class="form-control" value="Conf. Familia">Confraternização Família</option>
            <option class="form-control" value="Bodas casamento">Bodas Casamento</option>
        </select>
        Valor do Rodízio:
        <select name="valor_rodizio" class="form-control">
            <option class="form-control" value="">Selecione o valor</option>
            <?php
            if ($ultimo_preco) {
                echo "<option value='{$ultimo_preco['almoco']}'>Almoço - R$ {$ultimo_preco['almoco']}</option>";
                echo "<option value='{$ultimo_preco['jantar']}'>Jantar - R$ {$ultimo_preco['jantar']}</option>";
                echo "<option value='{$ultimo_preco['domingo_almoco']}'>Domingo Almoço - R$ {$ultimo_preco['domingo_almoco']}</option>";
                echo "<option value='{$ultimo_preco['outros']}'>Outros - R$ {$ultimo_preco['outros']}</option>";
            }
            ?>
        </select>
        Número de mesa:
        <select name="num_mesa" class="form-control">
            <option class="form-control" value=""></option>
            <option class="form-control" value="Salão 1">Salão 1</option>
            <option class="form-control" value="Salão 2">Salão 2</option>
            <option class="form-control" value="Salão 3">Salão 3</option>
            <option class="form-control" value="Próximo à janela">Próximo à janela</option>
            <option class="form-control" value="Próximo ao jardim">Próximo ao jardim</option>
            <option class="form-control" value="Centro do salão">Centro do salão</option>
            <?php
            for ($i = 1; $i <= 99; $i++) {
                echo "<option class='form-control' value='$i'>$i</option>";
            }
            ?>
        </select>
        Observações: <textarea name="observacoes" class="form-control" placeholder="Digite se houver alguma observação"></textarea><br>
        <input class="btn btn-primary" type="submit" name="enviar" value="Enviar">
    </form>
</div>

<script>
function validarFormulario() {
    var data = document.getElementById('data').value;
    var horario = document.getElementById('horario').value;
    
    if (data === '' || horario === '') {
        alert('Por favor, preencha a data e o horário.');
        return false;
    }
    return true;
}
</script>
