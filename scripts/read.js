$(document).ready(function(){
    showData(1);
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
        }
    });
}
