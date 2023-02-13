<?php
header("Ace-Access-Control-Allow-Origin: *");
require_once("config.php");
$sql = "SELECT * FROM motoristas ORDER BY nome ASC";

$resultado = $connection->query($sql);

if($resultado->num_rows > 0){
   foreach($resultado as $row){
        echo "<tr>";
        echo "<td>".$row['nome']."</td>";
        echo "<td>".$row['cpf']."</td>";
        echo "<td>".$row['endereco']."</td>";
        echo "<td>".$row['veiculo']."</td>";
        echo "<td>".$row['telefone']."</td>";
        echo "<td>
            <button type = 'button' class='btn btn-success' onclick=getId('".$row['id']."')>Editar</button>
            <button type = 'button' class='btn btn-danger' onclick=remove('".$row['id']."')>Excluir</button>
        </td>";
        echo "</tr>";
    }
}else{
    echo ("<tr><td colspan='6'>Nenhum registro encontrado</td></tr>");
}
?>
