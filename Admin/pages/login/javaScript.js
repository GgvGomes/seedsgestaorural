function Login() {
    var data = { funcao: "Logar", UserName: $("#UserName").val(), Senha: $("#Senha").val() };
    ChamaAjax(data, function (Resposta) { 
        var Respostan = JSON.parse(Resposta);
        if (Respostan.Status == "Ok") {
            if (Respostan.Resposta.PrimeiroAcesso == "0") {
                window.location.href="./atualizaSenha/";
            } else {
                window.location.href="../main/";
            }
        } else {
            $("#alert").html(Respostan.Resposta);
            $("#AlertModal").modal("show");
        }
    }, function (error) {
        console.log(error);
    });
}
function AtualizaSenha() {
    var data = { funcao: "AtualizaSenha", Senha: $("#Senha_One").val(), SenhaValida: $("#Senha_Two").val() };
  
    ChamaAjaxTrocaSenha(data, function (Resposta) {
        var Resposta = JSON.parse(Resposta);
        if (Resposta.Status == "Ok") {
            window.location.href="../../main/";
        } else {
            $("#alert").html(Resposta.Resposta);
            $("#AlertModal").modal("show");
        }
    }, function (error) {
        console.log(error);
    });
}