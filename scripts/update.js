
function getId(id){
    const form = new FormData();
    form.append("id", id);
    const url = 'http://localhost:80/GlobalDotCom/get_id.php';
    fetch(url, {
        method: "POST",
        body: form
    }).then(response =>{
        response.json().then(data => {
            console.log(data);
            $('#updateModal').modal('show')
            document.getElementById("id").value = data[0].id;
            document.getElementById("nome-1").value = data[0].nome;
            document.getElementById("cpf-1").value = data[0].cpf;
            document.getElementById("endereco-1").value = data[0].endereco;
            document.getElementById("veiculo-1").value = data[0].veiculo;
            document.getElementById("telefone-1").value = data[0].telefone;

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

    const form = new FormData();

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