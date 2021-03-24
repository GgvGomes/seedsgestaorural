<?php
include "../conexoes/index.php";
include "../logs/index.php";
header('Access-Control-Allow-Origin: *');
$verificacao = filter_input(INPUT_POST, 'verificacao');
if ($verificacao == $chaveApi) {
    $funcao = filter_input(INPUT_POST, 'funcao');
    if ($funcao == null || $funcao == "") {
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma função encontrada");
        echo json_encode($retorno);
    } else {
        //==0 - WEB ====== 1 - APP ==========
        $Dispositivo = $_POST["Dispositivo"];
        $Usuario = $_POST["Usuario"];
        $Token = $_POST["Token"];
        if (($Usuario == null || $Usuario == "") && $funcao != "Logar") {
            $retorno = array("Status" => "Erro", "Resposta" => "Nenhum usuário detectado");
            echo json_encode($retorno);
            return;
        } else if ($funcao == "Logar") {
            $UserName = $_POST["UserName"];
            $Senha = $_POST["Senha"];
            $Dispositivo = $_POST["Dispositivo"];
            Logar($UserName, $Senha, $Dispositivo, $link, $verificacao);
        } else {
            $url = "https://seedgestaorural.com/API/Token/index.php";
            $obj = http_build_query(array(
                "verificacao" => $verificacao,
                "funcao" => "ValidaToken",
                "Token" => "$Token",
                "Usuario" => "$Usuario",
                "Dispositivo" => "$Dispositivo",
            ));
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $RetonaToken = curl_exec($curl);
            curl_close($curl);
            $RetonaToken = str_replace("Array", "", $RetonaToken);
            $RetonaToken = json_decode($RetonaToken);
            if ($RetonaToken->Status == "Ok") {
                if ($funcao == "CadastraAtualizaDeletaFazendas") {
                    $Nome = $_POST["Nome"];
                    $Id = $_POST["Id"];
                    $IdResponsavel = $_POST["IdResponsavel"];
                    $Acao = $_POST["Acao"];
                    CadastraAtualizaDeletaFazendas($Usuario, $IdResponsavel, $Nome, $Id, $Acao, $link);
                } else if ($funcao == "RetornaFazendas") {
                    $Id = $_POST["Id"];
                    RetornaFazendas($Usuario, $Id, $link);
                } else {
                    $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma função encontrada");
                    echo json_encode($retorno);
                }
            } else {
                if ($funcao == "DesconectUser") {
                    DesconectUser($Usuario, $Dispositivo, $link);
                } else {
                    $retorno = array("Status" => "erro", "Resposta" => "Token Inválido", "Token" => "999");
                    echo json_encode($retorno);
                }
            }
        }
    }
} else {
    $retorno = array("Status" => "Erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function CadastraAtualizaDeletaFazendas($Usuario, $IdResponsavel, $Nome, $Id, $Acao, $link)
{
    if ($Acao == "1") {
        $sql = "INSERT INTO Fazenda (Nome, Responsavel_Id, Status) VALUES
         ('$Nome', '$IdResponsavel', '0')";
        $StatusAcao = "Cadastrado";
    } else if ($Acao == "2") {
        $sql = "UPDATE Fazenda SET Nome='$Nome',Responsavel_Id='$IdResponsavel' WHERE Id='$Id'";
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE Fazenda SET Status='3' WHERE Id='$Id'";
        $StatusAcao = "Deletada";
    }
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "fazendas", "CadastraAtualizaDeleta", "Ok",  "Fazenda " . $StatusAcao, $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => "Fazenda " . $StatusAcao);
    } else {
        resgistraLog($link, "fazendas", "CadastraAtualizaDeleta", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
    }
    echo json_encode($retorno);
}
function RetornaFazendas($Usuario, $Id, $link)
{
    $sqlUsuario = "select Permissao,Responsavel_Id from Usuarios where Id='$Usuario'";
    $resultUsuario = $link->query($sqlUsuario);
    $rowUsuario = $resultUsuario->fetch_assoc();
    if ($rowUsuario["Permissao"] == "999") {
        if ($Id == null || $Id == "") {
            $sql = "select FZ.Id as Id,FZ.Safra,FZ.Status as Status,FZ.Nome as Nome, RS.Nome as Responsavel from Reponsavel RS join Fazenda FZ ON RS.Id = FZ.Responsavel_Id where FZ.Status='0'";
        } else {
            $sql = "select FZ.Id as Id,FZ.Safra,FZ.Status as Status,FZ.Nome as Nome, RS.Nome as Responsavel from Reponsavel RS join Fazenda FZ ON RS.Id = FZ.Responsavel_Id where FZ.Status='0' and FZ.Id='$Id'";
        }
    } else if ($rowUsuario["Permissao"] == "0") {
        $Responsavel_Id = $rowUsuario["Responsavel_Id"];
        if ($Id == null || $Id == "") {
            $sql = "select FZ.Id as Id,FZ.Safra,FZ.Status as Status,FZ.Nome as Nome, RS.Nome as Responsavel from Reponsavel RS join Fazenda FZ ON RS.Id = FZ.Responsavel_Id where FZ.Status='0' and  FZ.Responsavel_Id='$Responsavel_Id'";
        } else {
            $sql = "select FZ.Id as Id,FZ.Safra,FZ.Status as Status,FZ.Nome as Nome, RS.Nome as Responsavel from Reponsavel RS join Fazenda FZ ON RS.Id = FZ.Responsavel_Id where FZ.Status='0' and  FZ.Id='$Id' and  FZ.Responsavel_Id='$Responsavel_Id'";
        }
    }
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "Responsavel" => $row['Responsavel'],
                "Safra" => $row['Safra'],
                "Status" => $row["Status"]
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "licencas", "RetornaLicenca", "Ok",  "Retorna fazendas", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "licencas", "RetornaLicenca", "Erro",  "Retorna fazendas", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum fazenda encontrada");
    }
    echo json_encode($retorno);
}
