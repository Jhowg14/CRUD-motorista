<?php
header('Access-Control-Allow-Origin: *');
// Inicia a sessão
require_once('config.php');
// Verifica se o usuário está logado
session_start();
// Se o usuário não estiver logado, redireciona para a página de login
$nome=$_POST['nome'];//recebe o nome do usuário
$cpf=$_POST['cpf'];
$endereco=$_POST['endereco'];
$veiculo=$_POST['veiculo'];
$telefone=$_POST['telefone'];
$datadecriacao = date('Y-m-d H:i:s');
if(empty($nome) || empty($cpf) || empty($endereco) ||empty($veiculo) || empty($telefone)){
    echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
}else{
    // Verifica se o CPF já está cadastrado
    $str = "SELECT * FROM motoristas WHERE cpf = '$cpf' and excluido = false";
    $response = $connection -> query($str);

    //
    if ($response->num_rows > 0) {
        // Se o CPF já estiver cadastrado, exibe uma mensagem
        echo json_encode(["message"=>"CPF já cadastrado","flag"=>true]);
    } else {
        // Insere os dados no banco
        $sql = "INSERT INTO motoristas (nome, cpf, endereco, veiculo, telefone, excluido, datadecriacao) VALUES ('$nome', '$cpf', '$endereco', '$veiculo', '$telefone', false, '$datadecriacao')";
        // Se os dados forem inseridos com sucesso
        
        // Executa a query
        $result = $connection -> query($sql);
        //seleciona o id do motorista cadastrado
        $id = $connection->query("SELECT id FROM motoristas WHERE cpf = '$cpf'")->fetch_assoc()['id'];//fetch_assoc() retorna um array associativo

        if(!$result){
            echo json_encode(["message"=>"Erro ao cadastrar motorista"]);
        }else{
            // Exibe uma mensagem de sucesso e o id do usuário
            echo json_encode(["message"=>"Motorista cadastrado com sucesso", "id"=>$id]);
        }
        
    }
}
?>