<?php
header("Access-Control-Allow-Origin: *");
require_once("config.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$veiculo = $_POST['veiculo'];
$telefone = $_POST['telefone'];
$datadeatualizacao = date('Y-m-d H:i:s');
//pegar data atual

if( empty($nome) || empty($cpf) || empty($endereco) || empty($veiculo) || empty($telefone)){
    echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
    }else{
        $sql = "UPDATE motoristas SET nome = '$nome', cpf = '$cpf', endereco = '$endereco', veiculo = '$veiculo', telefone = '$telefone', datadeatualizacao = '$datadeatualizacao' WHERE id = $id";

        $response = $connection->query($sql);

        if($response){
            echo json_encode(["message" => "Cliente atualizado com sucesso."]);
        }else{
            echo json_encode(["message" => "Erro ao atualizar cliente."]);
        }
}

?>