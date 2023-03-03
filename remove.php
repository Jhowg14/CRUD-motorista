<?php   
header('Access-Control-Allow-Origin: *');
require_once("config.php");

$id=$_POST['id'];//recebe o id do motorista
$datadeexclusao = date('Y-m-d H:i:s');//recebe a data e hora atual

if(empty($id)){
    echo json_encode(["message"=>"Não foi passado nenhum id"]);
}else{
    #$sql = "DELETE FROM motoristas WHERE id = '$id'";
    $sql = "UPDATE motoristas SET excluido = TRUE, datadeexclusao = '$datadeexclusao' WHERE id = '$id'";
    
    $response = $connection->query($sql);

    //sleciona o cpf do motorista removido
    $cpf = $connection->query("SELECT cpf FROM motoristas WHERE id = '$id'")->fetch_assoc()['cpf'];//fetch_assoc() retorna um array associativo
    
    if($response){
        echo json_encode(["message"=>"Motorista removido com sucesso", "cpf"=>$cpf]);
    }else{
        echo json_encode(["message"=>"Erro ao remover motorista"]);
    }
}