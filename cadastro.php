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
    echo json_encode(["message"=>"Preencha todos os campos"]);
}else{
    // Verifica se o CPF já está cadastrado
    $str = "SELECT * FROM motoristas WHERE cpf = '$cpf' and excluido = false";
    $response = $connection -> query($str);

    //
    if ($response->num_rows > 0) {
        echo json_encode(["message"=>"CPF já cadastrado"]);
    } else {
        // Insere os dados no banco
        $sql = "INSERT INTO motoristas (nome, cpf, endereco, veiculo, telefone, excluido, datadecriacao) VALUES ('$nome', '$cpf', '$endereco', '$veiculo', '$telefone', false, '$datadecriacao')";
        // Se os dados forem inseridos com sucesso
        $result = $connection -> query($sql);

        if(!$result){
            http_response_code(500);
            echo json_encode(["message"=>"Erro ao cadastrar"]);
        }else{
            http_response_code(200);
            echo json_encode(["message"=>"Cadastro realizado com sucesso"]);
        }
    }
}
?>