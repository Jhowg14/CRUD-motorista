function remove(id){
    const form = {
        id: id
    }

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
            $.ajax({
                url: url,
                method : 'POST',
                data: form,
                dataType: 'json'
            }).done(function(response){
                //se for o último registro, mostra a mensagem de nenhum registro encontrado
                $("tr#line_"+id).remove();
                Swal.fire(response.message);
                if ($("#results").children("tr").length == 0){
                    $("#results").append("<tr id='nenhum'><td colspan='7' style='text-align: center'>Nenhum motorista encontrado</td></tr>");
                }
            });
        }
    });

}