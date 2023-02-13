showData();
function showData(){
    const url = 'http://localhost:80/GlobalDotCom/read.php';
    fetch(url,{
        method: 'GET',
    }).then(response => response.text())
    .then(response => results.innerHTML = response)//resultado é o id do elemento html que vai receber o resultado
    .catch(err => console.log(err));
}