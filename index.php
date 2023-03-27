<?php
use LDAP\Result;
header("Ace-Access-Control-Allow-Origin: *"); //permite que o script seja executado em qualquer domínio
$host = "localhost";
$user = "root";
$password = "";
$db = "globaldotcom";

$connection = mysqli_connect($host, $user, $password, $db); //mysqli_connect() abre uma nova conexão ao servidor MySQL

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error()); //die() é uma função que imprime uma mensagem e termina o script
}

switch ($_POST['funcao']) { //switch() é uma estrutura de controle de fluxo que permite a execução de diferentes trechos de código com base em uma condição
    case 'read':
        read();
        break;
    case 'cadastro':
        addMotorista();
        break;
    case 'update':
        editar();
        break;
    case 'remover':
        remove();
        break;
    case 'getId':
        pegarId();
        break;
    case 'esca':
        escalonamento();
        break;
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'cadastrar':
        cadastrar();
        break;
    case 'getVeiculo':
        getVeiculo();
        break;
    case 'getVeiculos':
        getVeiculos();
        break;
    case 'cadastrarVeiculo':
        cadastrarVeiculo();
        break;
    default:
        echo json_encode(['message' => 'Ação não definida.']);
        break;
}
//funcao para cadastrar veiculo
function cadastrarVeiculo()
{
    global $connection;
    $modelo = $_POST['modelo'];
    $placa = $_POST['placa'];
    $ano = $_POST['ano'];
    $marca = $_POST['marca'];

    if (empty($modelo) || empty($placa) || empty($ano) || empty($marca)) {
        echo json_encode(['message' => 'Preencha todos os campos.']);
        return;
    } else {
        $str = "SELECT * FROM veiculo WHERE placa = '$placa'";
        $response = $connection->query($str);

        if ($response->num_rows > 0) {
            echo json_encode(['message' => 'Placa já cadastrada.']);
        } else {
            $sql = "INSERT INTO veiculo (marca,modelo,ano,placa) VALUES ('$marca','$modelo','$ano','$placa')";
            $result = $connection->query($sql);
            if ($result) {
                echo json_encode(['message' => 'Veículo cadastrado com sucesso.']);
            } else {
                echo json_encode(['message' => 'Erro ao cadastrar veículo.']);
            }
        }
    }
}
//funçao para pegar veiculos do banco de dados
function getVeiculos()
{
    global $connection;
    $sql = "SELECT * FROM veiculo where id>0";
    $resultado = mysqli_query($connection, $sql);
    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $table[] = $row;
        }
        echo json_encode($table);
    } else {
        echo json_encode(['message' => 'Nenhum registro encontrado.']);
    }
}
//funçao para abrir informaçoes veiculo
function getVeiculo()
{
    global $connection;
    $id = $_POST['id'];
    $sql = "SELECT * FROM veiculo WHERE id = $id";
    $resultado = mysqli_query($connection, $sql);
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['message' => 'Nenhum registro encontrado.']);
    }
}
//funçao realiza escalonamento de dados
function escalonamento()
{
    global $connection;
    $start = $_POST['start'];
    $end = $_POST['batch_size'];

    //calcula o início da visualização
    //$inicio = ($pagina * 10) - 10;

    $sql = "SELECT * FROM motoristas WHERE excluido = false ORDER BY nome ASC LIMIT $start, $end";

    //executa a query
    $resultado = mysqli_query($connection, $sql);



    if ($resultado->num_rows > 0) {
        //$table ="";
        while ($row = $resultado->fetch_assoc()) { //enquanto houver dados a serem exibidos, Fetch_assoc() retorna uma matriz associativa de strings que corresponde à linha recuperada, onde cada chave na matriz representa o nome de uma das colunas do conjunto de resultados
            //busca o modelo do veiculo
            $sql = "SELECT veiculo.modelo FROM motoristas
                        INNER JOIN veiculo ON motoristas.veiculo_id = veiculo.id
                        WHERE motoristas.id = " . $row['id'];

            $modelo = mysqli_query($connection, $sql);
            $row['modelo'] = $modelo->fetch_assoc()['modelo'];
            //exibe os dados
            $table[] = $row;
            /* $table.= '<tr id="line_'.$row['id'].'">
            <th id="name_'.$row['id'].'">'.$row['nome'].'</th>
            <td>'.$row['cpf'].'</td>
            <td>'.$row['endereco'].'</td>
            <td>'.$row['veiculo'].'</td>
            <td>'.$row['telefone'].'</td>
            <td><button class="btn btn-success" onclick="getId('.$row['id'].')">Editar</button></td>
            <td><button class="btn btn-danger" onclick="remove('.$row['id'].')">Excluir</button></td>
            </tr>';*/
        }
        //retorna os dados
        echo json_encode($table);
    } else {
        echo json_encode(["message" => "Nenhum registro encontrado."]);
    }
}

//funçao para ler os dados do banco de dados
function read()
{
    global $connection;
    $pagina = $_POST['pagina']; //recebe a página atual
    //com limite de 10 linhas
    if (!empty($pagina)) {

        //calcula o início da visualização
        //$inicio = ($pagina * 10) - 10;

        $sql = "SELECT * FROM motoristas WHERE excluido = false ORDER BY nome ASC";

        //executa a query
        $resultado = mysqli_query($connection, $sql);


        //se o número de linhas for maior que 0, exibe os dados
        if ($resultado->num_rows > 0) {
            /*$query_pg = "SELECT COUNT('id') AS num_linhas FROM motoristas WHERE excluido = false";
            $row_pg = $connection->query($query_pg)->fetch_assoc();//fetch_assoc() retorna uma matriz associativa de strings que corresponde à linha recuperada, onde cada chave na matriz representa o nome de uma das colunas do conjunto de resultados
            //quantidade de páginas
            $quantidade_pg = ceil($row_pg['num_linhas'] / 10);//ceil() arredonda frações para cima
            //limitar os links antes e depois
            $max_links = 2;
            $table .= '<nav aria-label="Page navigation example"><ul class="pagination pagination-sm justify-content-center">';
            $table .= "<li class='page-item'><a class='page-link' onclick='showData(1)'>Primeira</a></li>";
            for($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++){
            if($pag_ant >= 1){
            $table .= "<li class='page-item'><a class='page-link' href='#' onclick='showData($pag_ant)'>$pag_ant</a></li>";
            }
            }
            $table .= "<li class='page-item active'><a class='page-link' href='#' onclick='getPagina(2)'>$pagina</a></li>";
            for($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++){
            if($pag_dep <= $quantidade_pg){
            $table .= "<li class='page-item'><a class='page-link' href='#' onclick='showData($pag_dep)'>$pag_dep</a></li>";
            }
            }
            $table .= "<li class='page-item'><a class='page-link' id='previous' href='#' onclick='showData($quantidade_pg)'>Ultima</a></li>";
            $table .= "</ul></nav>";*/

            echo json_encode($resultado->num_rows);
        } else { //se não, exibe uma mensagem, nesse caso, nenhuma linha encontrada
            echo json_encode("<tr id='nenhum'><td colspan='7' style='text-align: center'>Nenhum motorista encontrado</td></tr>");
        }
    } else {
        echo json_encode("<div class='alert alert-danger' role='alert'>Erro ao carregar a página</div>");
    }
}
//chama a função cadastrado
function addMotorista()
{
    global $connection;
    // Verifica se o usuário está logado
    session_start();
    // Se o usuário não estiver logado, redireciona para a página de login
    $nome = $_POST['nome']; //recebe o nome do usuário
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $veiculo = $_POST['veiculo'];
    $telefone = $_POST['telefone'];
    $datadecriacao = date('Y-m-d H:i:s');
    if (empty($nome) || empty($cpf) || empty($endereco) || empty($veiculo) || empty($telefone)) {
        echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
    } else {
        // Verifica se o CPF já está cadastrado
        $str = "SELECT * FROM motoristas WHERE cpf = '$cpf' and excluido = false";
        $response = $connection->query($str);

        //
        if ($response->num_rows > 0) {
            // Se o CPF já estiver cadastrado, exibe uma mensagem
            echo json_encode(["message" => "CPF já cadastrado", "flag" => true]);
        } else {
            //pega o modelo do veiculo
            $sql = "SELECT * FROM veiculo WHERE id = '$veiculo'";
            $result = $connection->query($sql);
            //pega a linha do veiculo
            $idVeiculo = $result->fetch_assoc();
            // Insere os dados no banco
            $sql = "INSERT INTO motoristas (nome, cpf, endereco, veiculo_id, telefone, excluido, datadecriacao) VALUES ('$nome', '$cpf', '$endereco', '$veiculo', '$telefone', false, '$datadecriacao')";
            // Se os dados forem inseridos com sucesso

            // Executa a query
            $result = $connection->query($sql);
            //select o id do motorista cadastrado
            $id = $connection->query("SELECT id FROM motoristas WHERE cpf = '$cpf'")->fetch_assoc()['id']; //fetch_assoc() retorna um array associativo

            if (!$result) {
                echo json_encode(["message" => "Erro ao cadastrar motorista", "flag" => true]);
            } else {
                // Exibe uma mensagem de sucesso e o id do usuário
                echo json_encode(["message" => "Motorista cadastrado com sucesso", "id" => $id, "veiculo" => $idVeiculo, "flag" => false]);
            }

        }
    }
}
//chama a função remove
function remove()
{
    global $connection;
    $id = $_POST['id']; //recebe o id do motorista
    $datadeexclusao = date('Y-m-d H:i:s'); //recebe a data e hora atual

    if (empty($id)) {
        echo json_encode(["message" => "Não foi passado nenhum id"]);
    } else {
        #$sql = "DELETE FROM motoristas WHERE id = '$id'";
        $sql = "UPDATE motoristas SET excluido = TRUE, datadeexclusao = '$datadeexclusao' WHERE id = '$id'";

        $response = $connection->query($sql);

        //sleciona o cpf do motorista removido
        $cpf = $connection->query("SELECT cpf FROM motoristas WHERE id = '$id'")->fetch_assoc()['cpf']; //fetch_assoc() retorna um array associativo

        if ($response) {
            echo json_encode(["message" => "Motorista removido com sucesso", "cpf" => $cpf]);
        } else {
            echo json_encode(["message" => "Erro ao remover motorista"]);
        }
    }
}
function pegarId()
{
    global $connection;
    $id = $_POST['id']; //Recebe o id do motorista

    if (empty($id)) {
        echo json_encode(["message" => "Sem id válido"]);
    } else {
        $sql = "SELECT * FROM motoristas WHERE id = $id";
        $response = $connection->query($sql);
        $rows = array();
        //pega o modelo do veiculo conforme o veiculo_id do motorista
        $sql = "SELECT veiculo.modelo FROM veiculo WHERE id = (SELECT veiculo_id FROM motoristas WHERE id = $id)";
        $result = $connection->query($sql)->fetch_assoc()['modelo'];

        //Verifica se há motoristas com o id informado
        if ($response->num_rows > 0) {
            foreach ($response as $r) { //Percorre o array de motoristas
                $rows = $r;
            }
            echo json_encode(["rows" => $rows, "veiculo" => $result]);
        } else {
            echo json_encode(["message" => "Nenhum motorista encontrado com esse id"]);
        }
    }
}
//chama a função edita
function editar()
{
    global $connection;
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $veiculo = $_POST['veiculo'];
    $telefone = $_POST['telefone'];
    $datadeatualizacao = date('Y-m-d H:i:s');
    //pegar data atual

    if (empty($nome) || empty($cpf) || empty($endereco) || empty($veiculo) || empty($telefone)) {
        echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
    } else {
        $str = "SELECT * FROM motoristas WHERE cpf = '$cpf' and excluido = false";
        $response = $connection->query($str);

        if ($response->num_rows > 0 and $response->fetch_assoc()['id'] != $id) {
            echo json_encode(["message" => "CPF já cadastrado"]);
        } else {
            $sql = "UPDATE motoristas SET nome = '$nome', cpf = '$cpf', endereco = '$endereco', telefone = '$telefone', datadeatualizacao = '$datadeatualizacao' WHERE id = $id";
            $result = $connection->query($sql);
            $sql = "SELECT * FROM veiculo WHERE id = '$veiculo'";
            $result = $connection->query($sql);
            $veiculoID = $result->fetch_assoc();
            $sql = "UPDATE motoristas SET veiculo_id='$veiculoID[id]' WHERE id = $id";
            $result = $connection->query($sql);
            if ($result) {
                echo json_encode(["message" => "Cliente atualizado com sucesso.", 'Veiculo' => $veiculoID]);
            } else {
                echo json_encode(["message" => "Erro ao atualizar cliente."]);
            }
        }
    }
}
function login()
{
    global $connection;
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        echo json_encode(["message" => "Todos os campos são obrigatórios"]);
    } else {
        $sql = "SELECT * FROM login_motorista WHERE email = '$email'";

        $result = $connection->query("$sql");

        if ($result->num_rows == 0) {
            echo json_encode(["message" => "Usuário ou senha inválidos", "flag" => true]);
        } else {
            $row_usuario = $result->fetch_assoc();
            if (!password_verify($senha, $row_usuario['senha'])) {

                echo json_encode(["message" => "Usuário ou senha inválidos", "flag" => true]);
            } else {
                // Inicia a sessão
                session_start();
                $_SESSION['id'] = $row_usuario['id'];
                $_SESSION['nome'] = $row_usuario['nome'];
                $_SESSION['email'] = $row_usuario['email'];

                echo json_encode(["message" => "Login efetuado com sucesso", "dados" => $row_usuario, "flag" => false]);
            }
        }
    }
}
function logout()
{
    session_start();
    session_destroy();
    echo json_encode(["message" => "Logout efetuado com sucesso"]);
}
function cadastrar()
{
    global $connection;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $senha = password_hash($senha, PASSWORD_DEFAULT);

    if (empty($email) || empty($senha) || empty($nome)) {
        echo json_encode(["message" => "Todos os campos são obrigatórios"]);
    } else {
        $sql = "SELECT * FROM login_motorista WHERE email = '$email'";

        $result = $connection->query("$sql");

        if ($result->num_rows > 0) {
            echo json_encode(["message" => "Email já cadastrado", "flag" => true]);
        } else {
            $sql = "INSERT INTO login_motorista (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
            $result = $connection->query("$sql");
            if ($result) {
                echo json_encode(["message" => "Usuário cadastrado com sucesso", "flag" => false]);
            } else {
                echo json_encode(["message" => "Erro ao cadastrar usuário", "flag" => true]);
            }
        }
    }
}
#emitir dados quem está logado
?>