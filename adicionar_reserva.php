<?php
session_start();
require 'config.php';

// Verifica se a sessão está ativa
if (empty($_SESSION['mmnlogin'])) {
    header("Location: login.php");
    exit;
}

// Verifica se os dados do formulário foram enviados
if (!empty($_POST['nome']) && !empty($_POST['data'])) {
    $usuario_id = $_SESSION['mmnlogin'];
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

    // Verifica se já existe um cliente com o mesmo nome e data
    $sql = $pdo->prepare("SELECT * FROM clientes WHERE nome = :nome AND data = :data");
    $sql->bindValue(":nome", $nome);
    $sql->bindValue(":data", $data);
    $sql->execute();

    if ($sql->rowCount() == 0) {
        // Insere os dados no banco de dados
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

// Obtém o último preço do rodízio
$sql = $pdo->query("SELECT * FROM preco_rodizio ORDER BY id DESC LIMIT 1");
$ultimo_preco = $sql->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-5 col-lg-8 col-md-10">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h5>Cadastro de Nova Reserva</h5>
        </div>
        <div class="card-body">
            <form method="POST" onsubmit="return validarFormulario()">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input id="nome" class="form-control" type="text" name="nome" placeholder="Digite o nome">
                </div>
                <div class="form-group">
                    <label for="data">Data:</label>
                    <input class="form-control" id="data" required type="date" name="data">
                </div>
                <div class="form-group">
                    <label for="num_pessoas">Número de Pessoas:</label>
                    <input id="num_pessoas" maxlength="2" required class="form-control" type="number" name="num_pessoas" placeholder="Digite a quantidade de pessoas">
                </div>
                <div class="form-group">
                    <label for="horario">Horário:</label>
                    <input class="form-control" id="horario" required type="time" name="horario">
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input id="telefone" class="form-control" type="number" required name="telefone" placeholder="Digite o número de telefone">
                </div>
                <div class="form-group">
                    <label for="telefone2">Telefone Alternativo:</label>
                    <input id="telefone2" class="form-control" type="number" name="telefone2" placeholder="Digite um telefone alternativo (opcional)">
                </div>
                <div class="form-group">
                    <label for="forma_pagamento">Forma de Pagamento:</label>
                    <select id="forma_pagamento" name="forma_pagamento" class="form-control">
                        <option value="Não definido">Selecione</option>
                        <option value="unica">Única</option>
                        <option value="individual">Individual</option>
                        <option value="U (rod) I (beb)">Única (rod) Individual (beb)</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo_evento">Tipo de Evento:</label>
                    <select id="tipo_evento" name="tipo_evento" class="form-control">
                        <option value="Não definido">Selecione</option>
                        <option value="Aniversario">Aniversário</option>
                        <option value="Conf. fim de ano">Confraternização Fim de Ano</option>
                        <option value="Formatura">Formatura</option>
                        <option value="casamento">Casamento</option>
                        <option value="Conf. Familia">Confraternização Família</option>
                        <option value="Bodas casamento">Bodas de Casamento</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="valor_rodizio">Valor do Rodízio:</label>
                    <select id="valor_rodizio" name="valor_rodizio" class="form-control">
                        <option value="">Selecione o valor</option>
                        <?php
                        if ($ultimo_preco) {
                            echo "<option value='{$ultimo_preco['almoco']}'>Almoço - R$ {$ultimo_preco['almoco']}</option>";
                            echo "<option value='{$ultimo_preco['jantar']}'>Jantar - R$ {$ultimo_preco['jantar']}</option>";                           
                            echo "<option value='{$ultimo_preco['outros']}'>Sàbado - R$ {$ultimo_preco['outros']}</option>"; 
                            echo "<option value='{$ultimo_preco['domingo_almoco']}'>Domingo Almoço - R$ {$ultimo_preco['domingo_almoco']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="num_mesa">Número da Mesa:</label>
                    <select id="num_mesa" name="num_mesa" class="form-control">
                        <option value="">Selecione</option>
                        <option value="Salão 1">Salão 1</option>
                        <option value="Salão 2">Salão 2</option>
                        <option value="Salão 3">Salão 3</option>
                        <option value="Próximo à janela">Próximo à janela</option>
                        <option value="Próximo ao jardim">Próximo ao jardim</option>
                        <option value="Centro do salão">Centro do salão</option>
                        <?php
                        for ($i = 1; $i <= 99; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes" class="form-control" placeholder="Digite alguma observação (opcional)"></textarea>
                </div>
                <button class="btn btn-primary btn-block" type="submit" name="enviar">Enviar</button>
            </form>
        </div>
    </div>
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
<?php require 'rodape.php'; ?>