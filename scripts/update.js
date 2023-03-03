
function getId(id){

    const url = 'http://localhost:80/GlobalDotCom/get_id.php';
    $.post(
        url,
        {id: id},
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
    const url = 'http://localhost:80/GlobalDotCom/update.php';
    $.post(url,
           {
        id: id,
        nome: nome,
        cpf: cpf,
        endereco: endereco,
        veiculo: veiculo,
        telefone: telefone

           }
        ,function(data,status){
        var response = JSON.parse(data);
        Swal.fire(response.message);
        // verifica qual campo foi alterado
        if (nome != "" && nome != $("#line_"+id+" th:first").text()){
            $("#line_"+id+" th:first").text(nome);
        }
        if (cpf != "" && cpf != $("#line_"+id+" td").eq(0).text()){
            $("#line_"+id+" td").eq(0).text(cpf);
        }
        if (endereco != "" && endereco != $("#line_"+id+" td").eq(2).text()){
            $("#line_"+id+" td").eq(1).text(endereco);
        }
        if (veiculo != "" && veiculo != $("#line_"+id+" td").eq(3).text()){
            $("#line_"+id+" td").eq(2).text(veiculo);
        }
        if (telefone != "" && telefone != $("#line_"+id+" td").eq(4).text()){
            $("#line_"+id+" td").eq(3).text(telefone);
        }
        $("#completeModal").modal("hide");
    });
}