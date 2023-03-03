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
const input2 = document.getElementById('telefone');
input2.addEventListener('keypress', () => {
    let inputlength = input2.value.length;

    if (inputlength === 0) {
        input2.value += '(';
    } else if (inputlength === 3) {
        input2.value += ')';
    } else if (inputlength === 4) {
        input2.value += ' ';
    } else if (inputlength === 9) {
        input2.value += '-';
    }
});
//função para cadastrar usuário
function createUser(){
    var nomeAdd = $("#nome").val();
    var cpfAdd = $("#cpf").val();
    var enderecoAdd = $("#endereco").val();
    //pega o valor do select
    var veiculoAdd = $("#veiculo option:selected").val();
    console.log(veiculoAdd);
    var telefoneAdd = $("#telefone").val();

    //se o cpf e o telefone não forem válidos, não adiciona
    if(!validarCPF(cpfAdd)){
        Swal.fire("CPF inválido");
        return;
    }
    if(!validarTelefone(telefoneAdd)){
        Swal.fire("Telefone inválido");
        return;
    }
    const url = 'http://localhost:80/GlobalDotCom/cadastro.php';

    //fetch é uma função que faz requisições para o servidor
    $.ajax({
        url: url,
        method : 'POST',
        data:{
            nome: nomeAdd,
            cpf: cpfAdd,
            endereco: enderecoAdd,
            veiculo: veiculoAdd,
            telefone: telefoneAdd
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
            $("#results tr").filter("#nenhum").remove();

            Swal.fire(response.message);
            //se tiver algum campo vazio ou cpf já cadastrado, não adiciona se ja tiver cpf cadastrado, não adiciona
            
            if (nomeAdd != "" && cpfAdd != "" && enderecoAdd != "" && veiculoAdd != "" && telefoneAdd != "" && response.flag != true){
                var inserted = false;
                $("#results tr").each(function(){
                    var nome = $(this).find("th:first").text();
                    if (nome > nomeAdd && !inserted){
                        $(this).before(`<tr id='line_${$id}'><th>${nomeAdd}</th><td>${cpfAdd}</td><td>${enderecoAdd}</td><td>${veiculoAdd}</td><td>${telefoneAdd}</td><td><button class='btn btn-success' onclick='getId(${$id})'>Editar</button></td><td><button class='btn btn-danger' onclick='remove(${$id})'>Excluir</button></td></tr>`);
                        inserted = true;
                    }
                });
                if (!inserted)
                $("#results").append(`<tr id='line_${$id}'><th>${nomeAdd}</th><td>${cpfAdd}</td><td>${enderecoAdd}</td><td>${veiculoAdd}</td><td>${telefoneAdd}</td><td><button class='btn btn-success' onclick='getId(${$id})'>Editar</button></td><td><button class='btn btn-danger' onclick='remove(${$id})'>Excluir</button></td></tr>`); 
            }
        }).fail(function(response){
            Swal.fire(response.message);
    });
}

//funçao para abrir modal para cadastrar usuário
function openModal(){
    //alterar titulo do modal
    $("#botaoModal").text("Cadastrar").attr("onclick", "createUser()");

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
    cpf = cpf.replace(/[^\d]+/g,''); // remove todos os caracteres não numéricos
    if (cpf.length != 11 && cpf.length != 14) return false; // o CPF deve ter 11 ou 14 dígitos
    
    if (cpf.length == 14) {// remove a máscara do CPF com 14 dígitos
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
      var cpfComMascaraValido = (cpfComMascara == cpf);
    
      return true;
    }
    //funçao para validar telefone
    function validarTelefone(telefone) {
        telefone = telefone.replace(/\s/g, "").replace(/[-()]/g, ""); // remove todos os caracteres não numéricos
        if (telefone.length != 11 && telefone.length != 14) return false; 
        
            // Verifica se o telefone contém apenas números
    if (!/^\d+$/.test(telefone)) {
        return false;
    }

    // Verifica se o telefone tem um comprimento válido para um número de telefone
    if (telefone.length !== 10 && telefone.length !== 11) {
        return false;
    }

    return true;
    }

    