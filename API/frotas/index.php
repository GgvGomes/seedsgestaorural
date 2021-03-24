<?php
include "../conexoes/index.php";
include "../logs/index.php";
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$Headers = apache_request_headers();
// $verificacao = $Headers["verificacao"];
$verificacao = $_REQUEST["verificacao"];
// $verificacao = filter_input(INPUT_POST, 'verificacao');
if ($verificacao == $chaveApi) {
    // $funcao = filter_input(INPUT_POST, 'funcao');
    $funcao = $_REQUEST["funcao"];
    if ($funcao == null || $funcao == "") {
        $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
        echo json_encode($retorno);
    } else {
        //==0 - WEB ====== 1 - APP ==========
        $Dispositivo = $_REQUEST["Dispositivo"];
        $Usuario = $_REQUEST["Usuario"];
        $Token = $_REQUEST["Token"];
        // $Dispositivo = $Headers["Dispositivo"];
        // $Usuario = $Headers["Usuario"];
        // $Token = $Headers["Token"];
        if (($Usuario == null || $Usuario == "") && $funcao != "Logar") {
            $retorno = array("Status" => "erro", "Resposta" => "Nenhum usuário detectado");
            echo json_encode($retorno);
            return;
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
                if ($funcao == "CadastraAtualizaDeletaFrota") {
                    CadastraAtualizaDeletaFrota($Usuario, $link);
                } else if ($funcao == "RetornaFrotas") {
                    RetornaFrotas($Usuario, $link);
                } else if ($funcao == "PegaResponsavel") {
                    PegaResponsavel($Usuario, $link);
                }
            } else {
                $retorno = array("Status" => "erro", "Resposta" => "Token Inválido", "Token" => "999");
                echo json_encode($retorno);
            }
        }
    }
} else {
    $retorno = array("Status" => "erro", "Resposta" => "Chave de acesso");
    echo json_encode($retorno);
}
function CadastraAtualizaDeletaFrota($Usuario, $link)
{
    $Id = $_REQUEST["Id"];
    $Nome = $_REQUEST["Nome"];
    $Responsavel_Id = $_REQUEST["Responsavel"];
    $Marca = $_REQUEST["Marca"];
    $Modelo = $_REQUEST["Modelo"];
    $Placa = $_REQUEST["Placa"];
    $NumeroFrota = $_REQUEST["NumeroFrota"];
    $KmInicial = $_REQUEST["KmInicial"];
    $KmAtual = $_REQUEST["KmAtual"];
    $Ano = $_REQUEST["Ano"];
    $Acao = $_REQUEST["Acao"];
    $Status = $_REQUEST["Status"];
    if($Nome == "" || $Nome == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira o nome da frota");
        echo json_encode($retorno);
        return;
    }
    if($Responsavel_Id == "" || $Responsavel_Id == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira o responsável da frota");
        echo json_encode($retorno);
        return;
    }
    if($Marca == "" || $Marca == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira a marca da frota");
        echo json_encode($retorno);
        return;
    }
    if($Modelo == "" || $Modelo == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira o modelo da frota");
        echo json_encode($retorno);
        return;
    }
    if($Placa == "" || $Placa == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira a placa da frota");
        echo json_encode($retorno);
        return;
    }
    if($NumeroFrota == "" || $NumeroFrota == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira o numero da frota");
        echo json_encode($retorno);
        return;
    }
    if($KmInicial == "" || $KmInicial == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira o KmInicial da frota");
        echo json_encode($retorno);
        return;
    }
    if($KmAtual == "" || $KmAtual == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira o KmAtual da frota");
        echo json_encode($retorno);
        return;
    }
    if($Ano == "" || $Ano == null){
        $retorno = array("Status" => "Erro", "Resposta" => "Insira o ano da frota");
        echo json_encode($retorno);
        return;
    }

    // =============== Placa ========== //
    $Placa = explode("-",$Placa);
    $LetraPlaca = strtoupper($Placa[0]);
    $Placa = $LetraPlaca . "-" . $Placa[1];

    if ($Acao == "1") {
        $sql = "INSERT INTO Veiculos
        (Nome,Responsavel_Id,Marca,Modelo,Placa,NumeroFrota,KmInicial,KmAtual,Ano,Status)
        VALUES
        ('$Nome','$Responsavel_Id','$Marca','$Modelo','$Placa','$NumeroFrota',
        '$KmInicial','$KmAtual','$Ano','0')";
        $link->query($sql);
        $StatusAcao = "Cadastrada";
    } else if ($Acao == "2") {
        $sql = "UPDATE Veiculos SET
        Nome='$Nome',Responsavel_Id='$Responsavel_Id',Marca='$Marca',Modelo='$Modelo',
        Placa='$Placa',NumeroFrota='$NumeroFrota',KmInicial='$KmInicial',KmAtual='$KmAtual',
        Ano='$Ano',Status='$Status'
        WHERE Id='$Id'";
        $link->query($sql);
        $StatusAcao = "Atualizada";
    } else {
        $sql = "UPDATE Veiculos SET Status='3' WHERE Id='$Id'";
        $link->query($sql);
        $StatusAcao = "Deletada";
    }
    resgistraLog($link, "Frotas", "CadastraAtualizaDeleta", "Ok",  "Frota : " . $StatusAcao, $Usuario);
    $retorno = array("Status" => "Ok", "Resposta" => "Frota : " . $StatusAcao);
    echo json_encode($retorno);
}
function PegaResponsavel($Usuario, $link)
{
    $sql = "SELECT Responsavel_Id, Permissao from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $reponsavel = $row["Responsavel_Id"];
    $Permissao = $row["Permissao"];

    if ($Permissao == "999") {
        $sql = "SELECT * from Reponsavel";
    } else {
        $sql = "SELECT * from Reponsavel where Responsavel_Id='$reponsavel'";
    }
    $result = $link->query($sql);
    $Respostas = array();
    while ($row = $result->fetch_assoc()) {
        $Reposta = array(
            "Id" => $row["Id"],
            "Nome" => $row["Nome"],
        );
        array_push($Respostas, $Reposta);
    }
    $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    echo json_encode($retorno);
}
function RetornaFrotas($Usuario, $link)
{
    $Id = $_REQUEST["Id"];
    $sql = "SELECT Responsavel_Id, Permissao from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    $reponsavel = $row["Responsavel_Id"];
    $Permissao = $row["Permissao"];

    if ($Permissao == "999") {
        if ($Id == "" || $Id == null) {
            $sql = "SELECT * from Veiculos where Status='0'";
        } else {
            $sql = "SELECT * from Veiculos where Id = '$Id' and Status='0'";
        }
    } else {
        if ($Id == "" || $Id == null) {
            $sql = "SELECT * from Veiculos where Responsavel_Id='$reponsavel' and Status='0'";
        } else {
            $sql = "SELECT * from Veiculos where Id = '$Id' and Status='0'";
        }
    }
    $result = $link->query($sql);
    $Respostas = array();
    while ($row = $result->fetch_assoc()) {
        $Reposta = array(
            "Id" => $row["Id"],
            "Nome" => $row["Nome"],
            "Marca" => $row["Marca"],
            "Modelo" => $row["Modelo"],
            "Placa" => $row["Placa"],
            "NumeroFrota" => $row["NumeroFrota"],
            "KmInicial" => $row["KmInicial"],
            "KmAtual" => $row["KmAtual"],
            "Ano" => $row["Ano"],
            "Status" => $row["Status"],
            "Responsavel" => $row["Responsavel_Id"]
        );
        array_push($Respostas, $Reposta);
    }
    $retorno = array("Status" => "Ok", "Resposta" => $Respostas);
    echo json_encode($retorno);
}
