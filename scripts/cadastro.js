const input = document.getElementById('cpf');
input.addEventListener('keypress', () => {
    let inputlength = input.value.length;

    if (inputlength === 3 || inputlength === 7) {
        input.value += '.';
    } else if (inputlength === 11) {
        input.value += '-';
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
    const nome = document.getElementById("nome").value;
    const cpf = document.getElementById("cpf").value;
    const endereco = document.getElementById("endereco").value;
    const veiculo = document.getElementById("veiculo").value;
    const telefone = document.getElementById("telefone").value;
    
    const form = new FormData();

    form.append("nome", nome);
    form.append("cpf", cpf);
    form.append("endereco", endereco);
    form.append("veiculo", veiculo);
    form.append("telefone", telefone);

    const url = 'http://localhost:80/GlobalDotCom/cadastro.php';

    //fetch é uma função que faz requisições para o servidor
    fetch(url, {
        method: 'POST',
        body: form
    }).then(response => {
        response.json().then(result => {
            //console.log(result)
            showData();
            Swal.fire(result.message);
            $("#nome").val("");
            $("#cpf").val("");
            $("#endereco").val("");
            $("#veiculo").val("");
            $("#telefone").val("");
            $("#completeModal").modal('hide');
        }).catch(err => console.log(err))
    })
}
