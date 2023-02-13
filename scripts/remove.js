function remove(id){
    const form = new FormData();
    form.append('id', id);

    const url = 'http://localhost:80/GlobalDotCom/remove.php';
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
            fetch(url, {
                method: 'POST',
                body: form
            }).then(response =>{
                response.json().then(data => {
                    swal.fire(data.message)
                    showData();
                })
            }).catch(error => console.log(err))
        }
      })
}