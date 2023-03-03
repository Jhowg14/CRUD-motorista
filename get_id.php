<?php
header('Access-Control-Allow-Origin: *');//Permite que o arquivo seja acessado de qualquer origem
require_once ('config.php');//Requer o arquivo de configuração

// Recebe o id do motorista,$_POST recupera valores enviados via método POST
$id = $_POST['id'];

if(empty($id)){
    echo json_encode(["message"=>"Sem id válido"]);
}else {
    $sql = "SELECT id, nome, cpf, endereco, veiculo,telefone FROM motoristas WHERE id = $id";
    $response = $connection -> query($sql);
    $rows = array();//Cria um array para armazenar os motoristas

    //Verifica se há motoristas com o id informado
    if($response -> num_rows > 0){
        foreach($response as $r){//Percorre o array de motoristas
            $rows= $r;//$rows conterá todos os elementos em $response, na mesma ordem
        }
        echo json_encode($rows);//Retorna o motorista em formato JSON
    }else{
        echo json_encode(["message"=>"Nenhum motorista encontrado com esse id"]);
    }
}
?>