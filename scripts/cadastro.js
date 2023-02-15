
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
            Swal.fire(result.message);
            document.getElementById('nome').value = "";
            document.getElementById('cpf').value = "";
            document.getElementById('endereco').value = "";
            document.getElementById('veiculo').value = "";
            document.getElementById('telefone').value = "";
        }).catch(err => console.log(err))
    })
}
