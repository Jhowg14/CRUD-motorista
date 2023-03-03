var currentPage = 1;
$(document).ready(function(){
 
    showData(currentPage);
    //quando eu clico no botão de cadastrar, eu chamo a função openModal
    $("#botaoCadastrar").attr("onclick","openModal()");
});

function showData(pagina){
    var displaydata = "true";
    var paginaAdd = pagina;
    $.ajax({
        url: 'http://localhost:80/GlobalDotCom/read.php',
        type: 'POST',
        data:{
            pagina: paginaAdd,
            displaySend: displaydata,
        },
        dataType: 'json',
        success: function(data){
            $(".listarUsuarios").html(data);

            let table = new DataTable('#myTable');

            currentPage = paginaAdd;
        }
    });
}