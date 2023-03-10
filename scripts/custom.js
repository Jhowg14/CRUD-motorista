var currentPage = 1;
$(document).ready(function(){
 
    showData(currentPage);
    
    //quando eu clico no botão de cadastrar, eu chamo a função openModal
    $("#botaoCadastrar").attr("onclick","openModal()");
    
    $("#cpf,#telefone").keypress(function(event){
        if (event.which < 48 || event.which > 57){
            event.preventDefault();
        }
    });
});
function display_records_in_batches(start, end, batch_size) {
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
                response[i].veiculo,
                response[i].telefone,
                "<button class='btn btn-success' onclick='getId("+response[i].id+")'>Editar</button>",
                "<button class='btn btn-danger' onclick='remove("+response[i].id+")'>Excluir</button></tr>"
            ]).draw().node().id = "line_"+response[i].id;//node pega o elemento html
        }
        
        // Chamar a função novamente para exibir os próximos registros
        display_records_in_batches(next, end, batch_size);
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
            display_records_in_batches(0, data, 2);
           
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
                    veiculoAdd,
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
            $("#id").val(id);
            $("#tituloModal").text("Editar Motorista");
            //mudar o botão de cadastrar para editar e mudar a função do botão
            $("#botaoModal").text("Editar").attr("onclick","update()");
            $("#nome").val(userid.nome);
            $("#cpf").val(userid.cpf);
            $("#endereco").val(userid.endereco);
            $("#veiculo").val(userid.veiculo);
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
        Swal.fire(response.message);
        // Obtém a linha do DataTable pelo ID
        var row = $('#myTable').DataTable().row("#line_"+id);
        // Atualiza os dados da linha
        row.data([
            `<b>${nome}</b>`,
            cpf,
            endereco,
            veiculo,
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
    