<?php 
include "../conexoes/index.php";
include "../logs/index.php";
header('Access-Control-Allow-Origin: *');
$verificacao = filter_input(INPUT_POST, 'verificacao');
if ($verificacao == $chaveApi) {
    $funcao = filter_input(INPUT_POST, 'funcao');
    if ($funcao == null || $funcao == "") {
        $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
        echo json_encode($retorno);
    } else {
        if ($funcao == "GeraToken") {
            $Dispositivo = $_POST["Dispositivo"];
            $Usuario = $_POST["Usuario"];
            GeraToken($Usuario, $Dispositivo, $link);
        } else if ($funcao == "ValidaToken") {
            $Token = $_POST["Token"];
            $Dispositivo = $_POST["Dispositivo"];
            $Usuario = $_POST["Usuario"];
            $link2=$link;
            ValidaToken($Token, $Dispositivo, $Usuario, $link);
        } else if ($funcao == "DeletaToken") {
            $Token = $_POST["Token"];
            $Dispositivo = $_POST["Dispositivo"];
            $Usuario = $_POST["Usuario"];
            DeletaToken($Usuario, $Token, $Dispositivo, $link);
        } else {
            $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
            echo json_encode($retorno);
        }
    }
} else {
    $retorno = array("Status" => "erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function GeraToken($Usuario, $Dispositivo, $link)
{
    $sql = "select Id from Token_Usuarios where Usuario_Id='$Usuario' and Dispositivo='$Dispositivo'";
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        $retorno = array("Status" => "erro", "Resposta" => "Usuário ja esta conectado em outro dispositivo");
    } else {
        $Token = uniqid();
        $sql = "INSERT INTO Token_Usuarios (Usuario_Id, Token, Dispositivo) VALUES ('$Usuario', '$Token', '$Dispositivo')";
        if ($link->query($sql) === TRUE) {
            resgistraLog($link, "token", "GeraToken", "ok", "Token Gerado " . $Token, $Usuario);
            $retorno = array("Status" => "Ok", "Resposta" => "Token criado", "Token" => $Token);
        } else {
            resgistraLog($link, "token", "GeraToken", "ok", "Erro ao gerar token", $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => "Erro ao criar token");
        }
    }
    echo json_encode($retorno);
    // return $retorno;
}
function ValidaToken($Token, $Dispositivo, $Usuario, $link)
{
        $RepostaValidacao = ValidaLicenca($Usuario, $link);
        if ($RepostaValidacao["Status"] == "Ok") {
           $link= connect();
        $sql = "select Id from Token_Usuarios where Token='$Token' and Dispositivo='$Dispositivo'";
           $result = $link->query($sql);
    if ($result->num_rows > 0) {
            resgistraLog($link, "token", "ValidaToken", "ok", "Token validado", $Usuario);
            $retorno = array("Status" => "Ok", "Resposta" => "Token válido");
        } else {
            resgistraLog($link, "token", "ValidaToken", "Erro", "Token inválido", $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => $link);
        }
    } else {
        resgistraLog($link, "licencas", "RetornaResponsavel", "Erro",  "Licença inválida", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Licença inválida");
    }


    echo json_encode($retorno);
}
function DeletaToken($Usuario, $Token, $Dispositivo, $link)
{
    $sql = "select Id from Token_Usuarios where Token='$Token' and Dispositivo='$Dispositivo' and Usuario_Id='$Usuario'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idToken = $row["Id"];
        $sql = "DELETE FROM Token_Usuarios WHERE Id='$idToken'";
        if ($link->query($sql) === TRUE) {
            resgistraLog($link, "token", "DeletaToken", "Ok", "Token deletado", $Usuario);
            $retorno = array("Status" => "Ok", "Resposta" => "Token deletado");
        } else {
            resgistraLog($link, "token", "DeletaToken", "Ok", "Erro ao deletar token : " . $sql, $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => "Erro ao deletar token : " . $sql);
        }
    } else {
        resgistraLog($link, "token", "DeletaToken", "Erro", "Erro ao deslogar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Token inválida");
    }
}
function ValidaLicenca($Usuario, $link)
{
    $sql = "select RS.Status from Reponsavel RS join Usuarios US ON RS.Id = US.Responsavel_Id where RS.Status<>'0' and US.Id='$Usuario'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        resgistraLog($link, "licencas", "RetornaResponsavel", "Erro",  "Licença inválida", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => $sql);
    } else {
           resgistraLog($link, "licencas", "RetornaResponsavel", "Ok",  "Licença válida", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => "Licença válida");
    }
    return $retorno;
}
