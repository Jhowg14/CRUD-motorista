function getId(id){
    const form = new FormData();
    form.append("id", id);
    const url = 'http://localhost:80/GlobalDotCom/update.php';
    fetch(url, {
        method: "POST",
        body: form
    }).then(response =>{
        response.json().then(data => {
            window.localStorage.setItem("user", JSON.stringify(data));
            window.location.href = "update.html";
        })
    })
}
userData();
function userData() {
    const data = JSON.parse(window.localStorage.getItem("user"));
    const user = data[0];
    document.getElementById("id-1").value = user.id;
    document.getElementById("nome-1").value = user.nome;
    document.getElementById("cpf-1").value = user.cpf;
    document.getElementById("endereco-1").value = user.endereco;
    document.getElementById("veiculo-1").value = user.veiculo;
    document.getElementById("telefone-1").value = user.telefone;
}

function update(){
    const id = document.getElementById("id-1").value
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
            console.log(data.message)
        })
    })

}