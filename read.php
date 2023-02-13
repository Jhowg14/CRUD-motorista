<?php
header("Ace-Access-Control-Allow-Origin: *");
require_once("config.php");
$sql = "SELECT * FROM motoristas WHERE excluido = false ORDER BY id ASC ";

$resultado = $connection->query($sql);

//se o número de linhas for maior que 0, exibe os dados
if($resultado->num_rows > 0){
   foreach($resultado as $row){
        echo "<tr>";
            echo "<td>".$row['nome']."</td>";
            echo "<td>".$row['cpf']."</td>";
            echo "<td>".$row['endereco']."</td>";
            echo "<td>".$row['veiculo']."</td>";
            echo "<td>".$row['telefone']."</td>";
            #colocar espaço entre os botões
            echo "<td>
                <button type = 'button' class='btn btn-success 'onclick=getId('".$row['id']."')>Editar </button>
                
                <button type = 'button' class='btn btn-danger' onclick=remove('".$row['id']."')>Excluir</button>
            </td>";
        echo "</tr>";
    }
}else{//se não, exibe uma mensagem, nesse caso, nenhuma linha encontrada
    echo ("<tr><td colspan='6'>Nenhum registro encontrado</td></tr>");
}
?>
