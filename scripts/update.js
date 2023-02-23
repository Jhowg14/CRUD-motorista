
function getId(id){
    const form = new FormData();//criei um objeto FormData para enviar os dados do formulário
    form.append("id", id);//adicionei o id do usuário no form
    const url = 'http://localhost:80/GlobalDotCom/get_id.php';
    fetch(url, {// é a URL do servidor para onde a requisição será enviada. Este parâmetro é obrigatório
        method: "POST",//os dados do formulário serão enviados usando o método HTTP POST
        body: form //contém os dados do formulário que serão enviados para o servidor
    }).then(response =>{//o servidor retornará uma resposta. O método then() é usado para lidar com essa resposta.
        response.json().then(data => {//A resposta do servidor é convertida em um objeto JSON usando o método json()
            //console.log(data);
            $('#updateModal').modal('show')//abre o modal
            $("id").val(data[0].id);//pega o id do usuário e coloca no input
            $("#nome-1").val(data[0].nome);
            $("#cpf-1").val(data[0].cpf);
            $("#endereco-1").val(data[0].endereco);
            $("#veiculo-1").val(data[0].veiculo);
            $("#telefone-1").val(data[0].telefone);
        })
    })
}

function update(){
    const id = document.getElementById("id").value
    const nome = document.getElementById("nome-1").value
    const cpf = document.getElementById("cpf-1").value
    const endereco = document.getElementById("endereco-1").value
    const veiculo = document.getElementById("veiculo-1").value
    const telefone = document.getElementById("telefone-1").value

    const form = new FormData();//criei um objeto FormData para enviar os dados do formulário

    form.append("id", id);
    form.append("nome", nome);
    form.append("cpf", cpf);
    form.append("endereco", endereco);
    form.append("veiculo", veiculo);
    form.append("telefone", telefone);

    const url = 'http://localhost:80/GlobalDotCom/update.php';
    fetch(url, {
        method: "POST",
        body: form
    }).then(response =>{
        response.json().then(data => {
            Swal.fire(data.message).then(iscConfirmed => {
                if(iscConfirmed){
                    window.location.href = "index.html";
                    window.localStorage.removeItem("user");

                }
            })
        })
    })

}