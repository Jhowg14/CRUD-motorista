<?php
    session_start();
    if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])){
        header('Location: http://localhost:80/GlobalDotCom/www.gaussfleet.com/index.html');
        exit; 
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motoristas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="https://img.icons8.com/ios/452/truck.png" type="image/x-icon"> 
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
    
  </head>
  <body>
    <!-- Modal -->
    <div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          
            <div class="card-header bg-secondary">
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 style="color:#fff" id="tituloModal">Cadastrar Motorista</h4>
            
          </div>
          
          <div class="modal-body">
            <div class="card p-2">
              <div class="row g-3">
                <div class="col-md-6">
                  <input id="id" type="text" hidden />
                    <label for="" class="form-label">Nome<span style="color:red">*</span></label>
                      <input class="form-control" id="nome" type="text" placeholder="Ex: Pedro Silva" required>
                </div>
                  <div class="col-md-6">
                      <label class="form-label">CPF<span style="color:red">*</span></label>
                      <input class="form-control" id="cpf" type="text" placeholder="Insira somente números" pattern="[0-9]" autocomplete="off" minlength="14" maxlength="14" required>
                  </div>
                  <div class="col-md-12">
                      <label class="form-label">Endereço<span style="color:red">*</span></label>
                      <input class="form-control" id="endereco" type="text" placeholder="Ex: Avenida Jose Bueno 680">   
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Veiculo<span style="color:red">*</span></label>
                      <!--fazer select de veiculos-->
                      <select class="form-control" id="veiculo" type="text">
                          <!--chama funcao para listar veiculos-->
                          <option value="">Selecione um veiculo</option>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Telefone<span style="color:red">*</span></label>
                      <input class="form-control" id="telefone" type="tel" maxlength="14" placeholder="Número com DDD">
                  </div>
              </div>
            </div><span style="color:rgb(24, 25, 26) ;font-size: 10px">campos obrigatórios <span style="color:red">*</span></span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            <button type="button" id="botaoModal" class="btn btn-primary">Cadastrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="progress" style="position: fixed; top: 0; height: 5px;">
      <div id="progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; height: 5px;"></div>  
    </div>
    <div  class="container my-3">
      <a href="http://localhost:80/GlobalDotCom/www.gaussfleet.com/index.html "><img  id="logo" class="text-center" src="http://localhost:80/GlobalDotCom//img/logo.jpg" style="display: block; margin: auto;"height="100"></a>
    </div>
    <div id="nomeEmitir ">
      <h3 class="text-center" style="font-size:19px;
    text-align:center ;">Bem vindo, <?php echo $_SESSION['nome']; ?></h3>
    </div>
    <div class='container p-3'>
      <!-- Botão para acionar modal -->
      <button type='button' id='botaoCadastrar' class='btn btn-primary'>
      Cadastrar
      </button>
      <!--botao saie-->
      <button class='btn btn-danger' onclick="logout()" id="myBtn" style="text-align: right; float: right;">Sair</button>
      <!-- Botao adicinoar veiculo -->
      <button class='btn btn-theme'id="addVeiculo" onclick="addVeiculo()" style="text-align: right; float: right; margin-right: 10px;">
      Adicionar <span class='fa fa-truck'></span>
      </button>
    </div>
    
    <div class="container" style="margin-bottom: 20px;">
      <table id='myTable' class='table table-bordered'>
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
        <tbody id="listarUsuario">
        </tbody>
      </table>
    </div>

    
    <!-- Bootstrap JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" ></script>
    <!-- Font Awesome JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>
        <?php
        // Verifica se o usuário está logado usando JavaScript
        if (isset($_SESSION['nome'])) {
            echo "console.log('Usuário logado');";
        } else {
            echo "window.location.href = 'http://localhost:80/GlobalDotCom/www.gaussfleet.com/';"; // Redireciona para a página de login
        }
        ?>
    </script>
    <script>
      var currentPage = 1;
      $(document).ready(function(){
      showData(currentPage);
      //quando eu clico no botão de cadastrar, eu chamo a função openModal
      $("#botaoCadastrar").attr("onclick","openModal()");
      
      $("#cpf,#telefone").keypress(function(event){
          if (event.which < 48 || event.which > 57){//se não for número
              event.preventDefault();//não deixa digitar
          }
      });
    });
    //funcao que adiciona veiculo
    function addVeiculo(){
      Swal.fire({
        title: 'Adicionar veiculo',
        html: '<input id="modelo" class="swal2-input" style="heigth:2em;" placeholder="Modelo">' +
              '<input id="ano" class="swal2-input" placeholder="Ano">' +
              '<input id="marca" class="swal2-input" placeholder="Marca">' +
              '<input id="placa" class="swal2-input" placeholder="Placa">',
        showCancelButton: true,
        confirmButtonText: 'Adicionar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        //diminuir o tamanho do modal
        width: 400,
        preConfirm: () => {
          const modelo = Swal.getPopup().querySelector('#modelo').value
          const ano = Swal.getPopup().querySelector('#ano').value
          const marca = Swal.getPopup().querySelector('#marca').value
          const placa = Swal.getPopup().querySelector('#placa').value
          if (!modelo || !ano || !marca || !placa) {
            Swal.showValidationMessage(`Por favor, preencha todos os campos`)
          }
          return {modelo: modelo, ano: ano, marca: marca, placa: placa}
        },
        allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            console.log(result);
            if (result.isConfirmed) {
                $.post({
                url: 'http://localhost:80/GlobalDotCom/index.php',
                data: {funcao: 'cadastrarVeiculo',
                        modelo: result.value.modelo,
                        ano: result.value.ano,
                        marca: result.value.marca,
                        placa: result.value.placa},
                async: true,
                dataType: 'json'
                }).done(function(response){
                    swal.fire(response.message);
                });
            }
        })
    }
    //funçao que pega veiculos do banco de dados
    function getVeiculos(){
      const url = 'http://localhost:80/GlobalDotCom/index.php';
      $.post({
          url: url,
          data: {funcao: 'getVeiculos'},
          async: true,
          dataType: 'json'
      }).done(function(response){
            //remover todos os options menos a 0
            $("#veiculo option").remove();
            $("#veiculo").append("<option value='0'>Selecione um veiculo</option>");
          for (var i = 0; i < response.length; i++) {
              $("#veiculo").append("<option value='"+response[i].id+"'>"+response[i].modelo+" "+response[i].placa+"</option>");
          }
      });
    }
    function read(start, end, batch_size) {
      //barrinha de progresso
      var progress = Math.floor((start / end) * 100);
      console.log(progress);
      $(".progress").css("width", progress + "%");

      //largura da barra de progresso
      
      if (start >= end) {
          // Todos os registros foram exibido
          $(".progress").css("display", "none");
          return;
      }
      var next = start + batch_size;
      if (next >= end) {
          next = end;
      }

      const url = 'http://localhost:80/GlobalDotCom/index.php';
      //executar de forma assíncrona
      $.post({
          url: url,
          data: {start: start, batch_size: batch_size, funcao: 'esca'},
          async: true,
          dataType: 'json'
      }).done(function(response){
          //adicinoar no datatable os registros
          for (var i = 0; i < response.length; i++) {
              //$("#listarUsuario").append("<tr id='line_"+response[i].id+"'><th>"+response[i].nome+"</th><td>"+response[i].cpf+"</td><td>"+response[i].endereco+"</td><td>"+response[i].veiculo+"</td><td>"+response[i].telefone+"</td><td><button class='btn btn-success' onclick='getId("+response[i].id+")'>Editar</button></td><td><button class='btn btn-danger' onclick='remove("+response[i].id+")'>Excluir</button></td></tr>");
              //colocar id em cada linha
              $("#myTable").DataTable().row.add([
                  `<b>${response[i].nome}</b>`,
                  response[i].cpf,
                  response[i].endereco,
                  //colocar botao para vizualizar veiculo
                  `${response[i].modelo}<span style=' font-size :80%; padding: 4px;text-align: right; float: right' class='fa fa-eye' onclick='getVeiculo(${response[i].veiculo_id})'</span>`,
                  response[i].telefone,
                  "<button class='btn btn-success' onclick='getId("+response[i].id+")'>Editar</button>",
                  "<button class='btn btn-danger' onclick='remove("+response[i].id+")'>Excluir</button></tr>"
              ]).draw().node().id = "line_"+response[i].id;//node pega o elemento html
              console.log(response[i].modelo);
          }
          
          // Chamar a função novamente para exibir os próximos registros
          read(next, end, batch_size);
      });
  }
  function getVeiculo(id){
      $.ajax({
          url: 'http://localhost:80/GlobalDotCom/index.php',
          type: 'POST',
          data:{
              id: id,
              funcao: 'getVeiculo'
          },
          dataType: 'json',
          success: function(data){
              Swal.fire({
                  title: 'Veiculo',
                  html: `<b>Modelo:</b> ${data.modelo}<br><b>Placa:</b> ${data.placa}<br><b>Marca:</b> ${data.marca}<br><b>Ano:</b> ${data.ano}`,
                  icon: '<img src="https://img.icons8.com/color/48/000000/car.png"/>',
                  confirmButtonText: 'Ok'
              });
          }
      });
  }
  function showData(pagina){
      var displaydata = "true";
      var paginaAdd = pagina;
      $.ajax({
          url: 'http://localhost:80/GlobalDotCom/index.php',
          type: 'POST',
          data:{
              pagina: paginaAdd,
              displaySend: displaydata,
              funcao: 'read'
          },
          dataType: 'json',
          success: function(data){
              
              currentPage = paginaAdd;
              read(0, data, 2);
            
          }
      });
  }
  var input = $("#cpf");
  input.on("keypress", function(){
      var inputlength = input.val().length;
      if (inputlength === 3){
          input.val(input.val() + ".");
      } else if (inputlength === 7){
          input.val(input.val() + ".");
      } else if (inputlength === 11){
          input.val(input.val() + "-");
      }
  });
  var input2 = $("#telefone");
  input2.on("keypress", function(){
      var inputlength = input2.val().length;
      if (inputlength === 0){
          input2.val(input2.val() + "(");
      } else if (inputlength === 3){
          input2.val(input2.val() + ")");
      } else if (inputlength === 4){
          input2.val(input2.val() + " ");
      } else if (inputlength === 9){
          input2.val(input2.val() + "-");
      }
  });
    //função para cadastrar usuário
    function createUser(){
        //pega os valores dos inputs
        var nomeAdd = $("#nome").val();
        var cpfAdd = $("#cpf").val();
        var enderecoAdd = $("#endereco").val();
        //pega o valor do select
        var veiculoAdd = $("#veiculo option:selected").val();
        //console.log(veiculoAdd);
        var telefoneAdd = $("#telefone").val();
        //se o cpf e o telefone não forem válidos, não adiciona
        if(!validarCPF(cpfAdd)){
            Swal.fire("CPF inválido");
            $("#cpf").css("border-color", "red");
            
            return;//return volta para a função que chamou a função atual
        }
        if(!validarTelefone(telefoneAdd)){
            Swal.fire("Telefone inválido");
            $("#telefone").css("border-color", "red");
            return;
        }
        const url = 'http://localhost:80/GlobalDotCom/index.php';

        //fetch é uma função que faz requisições para o servidor
        $.ajax({
            url: url,
            method : 'POST',
            data:{
                nome: nomeAdd,
                cpf: cpfAdd,
                endereco: enderecoAdd,
                veiculo: veiculoAdd,
                telefone: telefoneAdd,
                funcao: 'cadastro'
            },
            dataType: 'json'
        }).done(function(response){
                //adicona o novo usuário na tela sem precisar atualizar a página
                var $id = response.id;
                
                $("#nome").val("");
                $("#cpf").val("");
                $("#endereco").val("");
                $("#veiculo").val("");
                $("#telefone").val("");
                
                //tirar "nenhum registro encontrado, caso exista"
                $("#listarUsuario tr").filter("#nenhum").remove();
                
              
                Swal.fire(response.message);
                //se tiver algum campo vazio ou cpf já cadastrado, não adiciona se ja tiver cpf cadastrado, não adiciona
                //fechar modal
                if (nomeAdd != "" && cpfAdd != "" && enderecoAdd != "" && veiculoAdd != "" && telefoneAdd != "" && response.flag != true){
                    //adiciona o novo usuário na tela sem precisar atualizar a página
                    $("#myTable").DataTable().row.add([
                        `<b>${nomeAdd}</b>`,
                        cpfAdd,
                        enderecoAdd,
                        `${response.veiculo['modelo']}<span style=' font-size :80%; padding: 4px;text-align: right; float: right' class='fa fa-eye' onclick='getVeiculo(${response.veiculo['id']})'></span>`,
                        telefoneAdd,
                        "<button class='btn btn-success' onclick='getId("+$id+")'>Editar</button>",
                        "<button class='btn btn-danger' onclick='remove("+$id+")'>Excluir</button></tr>"
                    ]).draw().node().id = "line_"+$id;
                    $("#completeModal").modal("hide");
                }
            }).fail(function(response){
                Swal.fire(response.message);
        });
        
    }

    //funçao para abrir modal para cadastrar usuário
    function openModal(){
        //alterar titulo do modal
        $("#botaoModal").text("Cadastrar").attr("onclick", "createUser()");
        getVeiculos();
        $("#cpf").css("border-color", "#CCC");
        $("#telefone").css("border-color", "#CCC");
        //limpar campos do modal
            $("#nome").val("");
            $("#cpf").val("");
            $("#endereco").val("");
            //colocar o select na primeira opção
            $("#veiculo").val("");
            $("#telefone").val("");
        $("#completeModal").modal("show");
    }


    // funçao validar cpf
    function validarCPF(cpf) {
        $("#cpf").css("border-color", "#CCC");
        $("#telefone").css("border-color", "#CCC");
        cpf = cpf.replace(/[^\d]+/g,'');
        if (cpf.length != 11 && cpf.length != 14) {
            $("#cpf").css("border-color", "red");
            return false;
        } // o CPF deve ter 11 ou 14 dígitos
        /*if (cpf.length == 14) {// remove a máscara do CPF com 14 dígitos
            // remove a máscara do CPF com 14 dígitos
            cpf = cpf.replace(/\./g, '').replace('-', '');
          }
        
          // calcula o primeiro dígito verificador
          var soma = 0;
          for (var i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);//charAt retorna o caractere na posição especificada
          }
          var resto = 11 - (soma % 11);//resto da divisão
          var dv1 = (resto > 9) ? 0 : resto;//operador ternário
        
          // calcula o segundo dígito verificador
          soma = 0;
          for (var i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
          }
          resto = 11 - (soma % 11);
          var dv2 = (resto > 9) ? 0 : resto;
        
          // o CPF é válido se os dígitos verificadores calculados são iguais aos dígitos verificadores do CPF
          var cpfValido = (cpf.charAt(9) == dv1 && cpf.charAt(10) == dv2);
        
          // verifica se o CPF possui a máscara correta
          var cpfComMascara = cpf.substr(0, 3) + '.' + cpf.substr(3, 3) + '.' + cpf.substr(6, 3) + '-' + cpf.substr(9, 2);
          var cpfComMascaraValido = (cpfComMascara == cpf);*/
        
          return true;
    }
    //funçao para validar telefone
    function validarTelefone(telefone) {
        $("#cpf").css("border-color", "#CCC");
        $("#telefone").css("border-color", "#CCC");
        //telefone = telefone.replace(/\s/g, "").replace(/[-()]/g, ""); // remove todos os caracteres não numéricos
        if (telefone.length != 14){
            $("#telefone").css("border-color", "red");
            return false; 
        }
            
        /*// Verifica se o telefone contém apenas números
        if (!/^\d+$/.test(telefone)) {
            return false;
        }*/

        // Verifica se o telefone tem um comprimento válido para um número de telefone
        /*if (telefone.length !== 10 && telefone.length !== 11) {
            return false;
        }*/

        return true;
    }

    //funçao para editar usuário
    function remove(id){
        const form = {
            id: id,
            funcao: 'remover'
        }

        const url = 'http://localhost:80/GlobalDotCom/index.php';
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, apague isso!'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method : 'POST',
                    data: form,
                    dataType: 'json',
                }).done(function(response){
                    //remover a linha da datatable
                    $("#myTable").DataTable().row("#line_"+id).remove().draw();
                    Swal.fire(response.message);
                    if ($("#listarUsuario").children("tr").length == 0){
                        $("#listarUsuario").append("<tr id='nenhum'><td colspan='7' style='text-align: center'>Nenhum motorista encontrado</td></tr>");
                    }
                });
            }
        });

    }

    function getId(id){
        $("#cpf").css("border-color", "#CCC");
        $("#telefone").css("border-color", "#CCC");
        const url = 'http://localhost:80/GlobalDotCom/index.php';
        $.post(
            url,
            {id: id,
            funcao:'getId'
            },
            function(data,status){
                
                var userid=JSON.parse(data);
                getVeiculos();
                //console.log(userid);
                $("#id").val(id);
                $("#tituloModal").text("Editar Motorista");
                //mudar o botão de cadastrar para editar e mudar a função do botão
                $("#botaoModal").text("Editar").attr("onclick","update()");
                $("#nome").val(userid.nome);
                $("#cpf").val(userid.cpf);
                $("#endereco").val(userid.endereco);
                $("#veiculo").val(userid.veiculo_id);
                $("#telefone").val(userid.telefone);
                $("#completeModal").modal("show"); 
            });
            
    }

    function update(){

        var id = $("#id").val();
        var nome = $("#nome").val();
        var cpf = $("#cpf").val();
        var endereco = $("#endereco").val();
        var veiculo = $("#veiculo").val();
        var telefone = $("#telefone").val();

        //se o cpf e o telefone não forem válidos, não adiciona
        if (!validarCPF(cpf)){
            Swal.fire("CPF inválido");
            return;
        }
        if (!validarTelefone(telefone)){
            Swal.fire("Telefone inválido");
            return;
        }
        const url = 'http://localhost:80/GlobalDotCom/index.php';
        $.post(url,
              {
            id: id,
            nome: nome,
            cpf: cpf,
            endereco: endereco,
            veiculo: veiculo,
            telefone: telefone,
            funcao:'update'
              }
            ,function(data,status){
             
            var response = JSON.parse(data);
            console.log(response);
            Swal.fire(response.message);
            //se algum campo nao foi preenchiado, nao adiciona
            if (nome == "" || cpf == "" || endereco == "" || telefone == "" || veiculo == ""){
                return;
            }
            // Obtém a linha do DataTable pelo ID
            var row = $('#myTable').DataTable().row("#line_"+id);
            // Atualiza os dados da linha
            row.data([
                `<b>${nome}</b>`,
                cpf,
                endereco,
                `${response.Veiculo['modelo']}<span style=' font-size :80%; padding: 4px;text-align: right; float: right;' onclick='getVeiculo(${veiculo})' class="fa fa-eye"></span></button>`,
                telefone,
                `<button class='btn btn-success' onclick='getId(${id})'>Editar</button>`, 
                `<button class='btn btn-danger' onclick='remove(${id})'>Excluir</button>`
            ]).draw(false);
            // Redesenha a tabela para exibir as atualizações
            $('#myTable').DataTable().draw(false);
            //edita a linha do datatable
            $("#completeModal").modal("hide");
        });
    }
    </script>
    <script>
      //chama a funçao logout do index.php
      function logout(){
        const url = 'http://localhost:80/GlobalDotCom/index.php';
        $.post(url,
              {
            funcao:'logout'
              }
            ,function(data,status){
            var response = JSON.parse(data);
            console.log(response);
            setTimeout(function(){
                window.location.href = "http://localhost:80/GlobalDotCom/www.gaussfleet.com/index.html";
            }, 2000);
        });
      }
    </script>
  </body>
</html>