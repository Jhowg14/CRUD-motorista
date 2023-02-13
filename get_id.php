<?php
header('Access-Control-Allow-Origin: *');
require_once ('config.php');

// Get the posted data.
$id = $_POST['id'];
if(empty($id)){
    echo json_encode(["message"=>"Sem id válido"]);
}else {
    $sql = "SELECT * FROM motoristas WHERE id = '$id'";
    $response = $connection -> query($sql);
    $rows = array();

    //Verifica se há motoristas com o id informado
    if($response -> num_rows > 0){
        foreach($response as $row){
            $rows[] = $row;
        }
        echo json_encode($rows);
    }else{
        echo json_encode(["message"=>"Nenhum motorista encontrado com esse id"]);
    }

}
?>