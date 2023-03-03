<?php
header("Ace-Access-Control-Allow-Origin: *");
require_once("config.php");
$sql = "SELECT * FROM motoristas WHERE excluido = false ORDER BY nome ASC ";

$resultado = $connection->query($sql);

//se o número de linhas for maior que 0, exibe os dados
if($resultado->num_rows > 0){
    $table ="";
    while($row = $resultado->fetch_assoc()){//enquanto houver dados a serem exibidos, Fetch_assoc() retorna uma matriz associativa de strings que corresponde à linha recuperada, onde cada chave na matriz representa o nome de uma das colunas do conjunto de resultados
        //exibe os dados
        $table.= '<tr id="line_'.$row['id'].'">
                        <th id="name_'.$row['id'].'">'.$row['nome'].'</th>
                        <td>'.$row['cpf'].'</td>
                        <td>'.$row['endereco'].'</td>
                        <td>'.$row['veiculo'].'</td>
                        <td>'.$row['telefone'].'</td>
                            <td><button class="btn btn-success" onclick="getId('.$row['id'].')">Editar</button></td>
                            <td><button class="btn btn-danger" onclick="remove('.$row['id'].')">Excluir</button></td>
                    </tr>';
    }
    echo json_encode($table);  
}else{//se não, exibe uma mensagem, nesse caso, nenhuma linha encontrada
    echo json_encode("<tr id='nenhum'><td colspan='7' style='text-align: center'>Nenhum motorista encontrado</td></tr>");
}
?>
