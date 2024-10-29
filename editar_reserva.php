<?php 
 	require 'cabecalho.php';
 ?>
 <?php
	session_start();
	require 'config.php';

	$id = 0;


	if (isset($_GET['id']) && empty($_GET['id']) == false) {
		$id = addslashes($_GET['id']);
}
	if (isset($_POST['nome']) && empty($_POST['nome']) == false) {
		$nome = addslashes($_POST['nome']);
 		$data = addslashes($_POST['data']);
 		$num_pessoas = addslashes($_POST['num_pessoas']);
 		$horario = addslashes($_POST['horario']);
 		$telefone = addslashes($_POST['telefone']);
		 $telefone2 = addslashes($_POST['telefone2']);
		 $tipo_evento = addslashes($_POST['tipo_evento']);
		 $forma_pagamento = addslashes($_POST['forma_pagamento']);
		 $valor_rodizio = addslashes($_POST['valor_rodizio']);
		 $numero_mesa = addslashes($_POST['numero_mesa']);
 		
 		$observacoes = addslashes($_POST['observacoes']);

 		$sql = "UPDATE clientes SET nome = '$nome' , data = '$data' , num_pessoas = '$num_pessoas' , horario = '$horario' , telefone = '$telefone' , telefone2 = '$telefone2' , tipo_evento = '$tipo_evento' ,forma_pagamento ='$forma_pagamento' ,valor_rodizio = '$valor_rodizio', num_mesa = '$numero_mesa'  , observacoes = '$observacoes' WHERE id = '$id'";
		 $sql = $pdo->query($sql);
		 

 		 echo "<script>alert('Atualizado com sucesso!'); window.location=index.php</script>";
 		header("Location: pesquisar_tudo.php");

	}

	
		$sql = "SELECT * FROM clientes WHERE id = '$id'";
		$sql = $pdo->query($sql);
		if($sql->rowCount() > 0){
			$dado = $sql->fetch();

		}	
?>
 
	<div class="container col-md-4">
	<h3>Editar <small>reserva</small></h3>

	<hr>
	
        <form method="POST">
	    <div class="form-group" >
		Nome:
		<input id="nome" class="form-control " type="text" name="nome" value="<?php echo $dado['nome']?>">
		Data: 
		<input class="form-control" id="data" type="date" name="data"value="<?php echo $dado['data']?>">
	
		Número de pessoas;
		<input class="form-control"type="number" name="num_pessoas"value="<?php echo $dado['num_pessoas']?>">
		Horário:
		<input class="form-control" type="time" name="horario"value="<?php echo $dado['horario']?>">
		Telefone:
		<input class="form-control"type="int" name="telefone"value="<?php echo $dado['telefone']?>">
		Telefone:
		<input class="form-control"type="int" name="telefone2"value="<?php echo 
		$dado['telefone2']?>">

Tipo de Evento:

				<select name="tipo_evento"class="form-control">

					 <option class="form-control" value="nao definido" ></option>	
			 		 <option class="form-control" value="Aniversario">Aniversário</option>
			 		 <option class="form-control"value="Formatura">Formatura</option>
			 		 <option class="form-control" value="casamento">Casamento</option>
			 		 <option class="form-control"value="Confraternizacao">Confraternização</option>
					  <option class="form-control"value="Conf. fim de ano">Confraternização Fim de Ano</option>
					  <option class="form-control"value="Bodas casamento">Bodas Casamento</option>

				 </select>
				 	
					 Forma de pagamento:
				<select name="forma_pagamento" class="form-control ">
					 <option class="form-control" value="nao definido"></option>
			 		 <option class="form-control"value="unica">Única</option>
			 		 <option class="form-control"value="individual">Individual</option>
			 		 <option class="form-control"value="unica(rod)individual(beb)">Única (rod) Individual (beb)</option>
			 		 <option class="form-control"value="outros">Outros</option>

				 </select>

				Valor do Rodizio:
				<select name="valor_rodizio" class="form-control ">
				<option class="form-control" value="Não definido" ></option>
			 		 <option class="form-control"value="R$ 69,80">R$ 69,80</option>
			 		 <option class="form-control"value="R$ 79,80">R$ 79,80</option>
			 		 <option class="form-control"value="R$ 89,80">R$ 89,80</option>
			 		 <option class="form-control"value="Valor do dia">Valor do dia</option>
				 </select>

				Número de mesa:
				<select name="numero_mesa" class="form-control ">
				      <option class="form-control" value="nao definido" ></option>
					   <option class="form-control"value="Salão 1">Salão 1</option>
					   <option class="form-control"value="Salão 2">Salão 2</option>
					   <option class="form-control"value="Salão 3">Salão 3</option>
					   <option class="form-control"value="Proximo a janela">Próximo a janela</option>
					   <option class="form-control"value="Próximo ao jardim">Próximo ao jardim</option>
					   <option class="form-control"value="Centro do salao">Centro do salão</option>
					   <option class="form-control"value="01">01</option>					
					  <option class="form-control"value="02">02</option>
					  <option class="form-control"value="03">03</option>
					  <option class="form-control"value="04">04</option>
					  <option class="form-control"value="05">05</option>
					  <option class="form-control"value="06">06</option>
					  <option class="form-control"value="07">07</option>
					  <option class="form-control"value="08">08</option>
					  <option class="form-control"value="09">09</option>
					  <option class="form-control"value="10">10</option>
					  <option class="form-control"value="11">11</option>
					  <option class="form-control"value="12">12</option>
					  <option class="form-control"value="13">13</option>
					  <option class="form-control"value="14">14</option>
					  <option class="form-control"value="15">15</option>
					  <option class="form-control"value="16">17</option>
					  <option class="form-control"value="18">18</option>
					  <option class="form-control"value="19">19</option>
					  <option class="form-control"value="20">20</option>
					  <option class="form-control"value="21">22</option>
					  <option class="form-control"value="23">23</option>
					  <option class="form-control"value="24">24</option>
					  <option class="form-control"value="25">25</option>
					  <option class="form-control"value="26">26</option>
					  <option class="form-control"value="27">28</option>
					  <option class="form-control"value="29">29</option>
					  <option class="form-control"value="30">30</option>
					  <option class="form-control"value="31">32</option>
					  <option class="form-control"value="33">33</option>
					  <option class="form-control"value="34">34</option>
					  <option class="form-control"value="35">35</option>
					  <option class="form-control"value="36">36</option>
					  <option class="form-control"value="37">37</option>
					  <option class="form-control"value="38">38</option>
					  <option class="form-control"value="39">39</option>
					  <option class="form-control"value="40">40</option>
					  <option class="form-control"value="41">41</option>
					  <option class="form-control"value="42">42</option>
					  <option class="form-control"value="43">43</option>
					  <option class="form-control"value="44">44</option>
					  <option class="form-control"value="45">45</option>
					  <option class="form-control"value="46">46</option>
					  <option class="form-control"value="47">47</option>
					  <option class="form-control"value="48">48</option>
					  <option class="form-control"value="49">49</option>
					  <option class="form-control"value="50">50</option>
					  <option class="form-control"value="51">51</option>
					  <option class="form-control"value="52">52</option>
					  <option class="form-control"value="53">53</option>
					  <option class="form-control"value="54">54</option>
					  <option class="form-control"value="55">55</option>
					  <option class="form-control"value="56">56</option>
					  <option class="form-control"value="57">57</option>
					  <option class="form-control"value="58">58</option>
					  <option class="form-control"value="59">59</option>
					  <option class="form-control"value="60">60</option>
					  <option class="form-control"value="61">61</option>
					  <option class="form-control"value="62">62</option>
					  <option class="form-control"value="63">63</option>
					  <option class="form-control"value="64">64</option>
					  <option class="form-control"value="65">65</option>
					  <option class="form-control"value="66">66</option>
					  <option class="form-control"value="67">67</option>
					  <option class="form-control"value="68">68</option>
					  <option class="form-control"value="69">69</option>
					  <option class="form-control"value="70">70</option>
					  <option class="form-control"value="71">71</option>
					  <option class="form-control"value="72">72</option>
					  <option class="form-control"value="73">73</option>
					  <option class="form-control"value="74">74</option>
					  <option class="form-control"value="75">75</option>
					  <option class="form-control"value="76">76</option>
					  <option class="form-control"value="77">77</option>
					  <option class="form-control"value="78">78</option>
					  <option class="form-control"value="79">79</option>
					  <option class="form-control"value="80">80</option>
					  <option class="form-control"value="81">82</option>
					  <option class="form-control"value="83">83</option>
					  <option class="form-control"value="84">84</option>
					  <option class="form-control"value="85">85</option>
					  <option class="form-control"value="86">86</option>
					  <option class="form-control"value="87">87</option>
					  <option class="form-control"value="88">88</option>
					  <option class="form-control"value="89">89</option>
					  <option class="form-control"value="90">90</option>
					  <option class="form-control"value="91">91</option>
					  <option class="form-control"value="92">92</option>
					  <option class="form-control"value="93">93</option>
					  <option class="form-control"value="94">94</option>
					  <option class="form-control"value="95">95</option>



				 </select>
		
		 Observações:<br>
		 <textarea class="form-control" name="observacoes" value=""><?php echo $dado['observacoes']?></textarea><br>

		 <input class="btn btn-primary "type="submit"  value="Atualizar">

	

	</form>
</div>

</div>
	


<script type="text/javascript" src="bootstrap.min.js"></script>
</body>
</html>