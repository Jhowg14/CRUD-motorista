<?php
header("Ace-Access-Control-Allow-Origin: *");
require_once("config.php");

$pagina = $_POST['pagina'];//recebe a página atual
//com limite de 10 linhas
if (!empty($pagina)) {

    //calcula o início da visualização
    $inicio = ($pagina * 10) - 10;


    $sql = "SELECT * FROM motoristas WHERE excluido = false ORDER BY nome ASC LIMIT $inicio, 10";

    $resultado = $connection->query($sql);

    //se o número de linhas for maior que 0, exibe os dados
    if($resultado->num_rows > 0){
        $table =
        "<div class='container p-3'>
        <!-- Botão para acionar modal -->
        <button type='button' id='botaoCadastrar' class='btn btn-primary'>
        Cadastrar
        </button>
    </div>
        <table class='table table-bordered'>
            <thead class='thead-dark'>
            <tr >
                <th scope='col' style='text-align: center'>Nome</th>
                <th scope='col' style='text-align: center'>CPF</th>
                <th scope='col' style='text-align: center'>Endereço</th>
                <th scope='col' style='text-align: center'>Veiculo</th>
                <th scope='col' style='text-align: center'>Telefone</th>
                <th scope='col' style='text-align: center'>Editar</th>
                <th scope='col' style='text-align: center'>Excluir</th>
            </tr>
            </thead>
            <tbody>";
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
        $table.= '</tbody>
        </table>
        </div>';
        //conta o número total de linhas
        $query_pg = "SELECT COUNT('id') AS num_linhas FROM motoristas WHERE excluido = false";
        $row_pg = $connection->query($query_pg)->fetch_assoc();//fetch_assoc() retorna uma matriz associativa de strings que corresponde à linha recuperada, onde cada chave na matriz representa o nome de uma das colunas do conjunto de resultados
        //quantidade de páginas
        $quantidade_pg = ceil($row_pg['num_linhas'] / 10);//ceil() arredonda frações para cima

        $table .= '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
        $table .= '<li class="page-ite disabled"><a class="page-link">Previous</a></li>';
        $table .= '<li class="page-item"><a class="page-link" href="#" onclick="getPagina(1)">1</a></li>';
        $table .= '<li class="page-item"><a class="page-link" href="#" onclick="getPagina(2)">2</a></li>';
        $table .= '<li class="page-item"><a class="page-link" href="#" onclick="getPagina(3)">3</a></li>';
        $table .= '<li class="page-item"><a class="page-link" href=".$quantidade_pg." onclick="getPagina(4)">Ultima</a></li>';
        $table .= '</ul></nav>';

        echo json_encode($table);  
    }else{//se não, exibe uma mensagem, nesse caso, nenhuma linha encontrada
        echo json_encode("<tr id='nenhum'><td colspan='7' style='text-align: center'>Nenhum motorista encontrado</td></tr>");
    }
}else{
    echo json_encode("<div class='alert alert-danger' role='alert'>Erro ao carregar a página</div>");
}
?>
