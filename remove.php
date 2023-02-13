<?php   
header('Access-Control-Allow-Origin: *');
require_once("config.php");

$id=$_POST['id'];//recebe o id do motorista

if(empty($id)){
    echo json_encode(["message"=>"Não foi passado nenhum id"]);
}else{
    #$sql = "DELETE FROM motoristas WHERE id = '$id'";
    $sql = "UPDATE motoristas SET excluido = TRUE WHERE id = '$id'";
    
    $response = $connection->query($sql);
    
    if($response){
        echo json_encode(["message"=>"Motorista removido com sucesso"]);
    }else{
        echo json_encode(["message"=>"Erro ao remover motorista"]);
    }
}