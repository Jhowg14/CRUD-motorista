<?php
header('Access-Control-Allow-Origin: *');
require_once ('config.php');

// Get the posted data.
$id = $_POST['id'];

if(empty($id)){
    echo json_encode(["message"=>"Sem id válido"]);
}else {
    $sql = "SELECT id, nome, cpf, endereco, veiculo,telefone FROM motoristas WHERE id = $id";
    $response = $connection -> query($sql);
    $rows = array();

    //Verifica se há motoristas com o id informado
    if($response -> num_rows > 0){
        foreach($response as $r){//Percorre o array de motoristas
            $rows[] = $r;
        }
        echo json_encode($rows);
    }else{
        echo json_encode(["message"=>"Nenhum motorista encontrado com esse id"]);
    }
}
?>