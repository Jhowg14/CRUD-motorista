$(document).ready(function(){
    showData();
    $("#botaoCadastrar").attr("onclick","openModal()");
});
function showData(){
    var displaydata = "true";
    
    $.ajax({
        url: 'http://localhost:80/GlobalDotCom/read.php',
        type: 'POST',
        data:{
            displaySend: displaydata
        },
        dataType: 'json',
        success: function(data){
            $("#results").html(data);
        }
    });
}
