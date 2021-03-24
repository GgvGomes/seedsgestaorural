<?php
include "../conexoes/index.php";
include "../logs/index.php";
header('Access-Control-Allow-Origin: *');
$verificacao = $_REQUEST['verificacao'];
if ($verificacao == $chaveApi) {
    $funcao = $_REQUEST['funcao'];
    if ($funcao == null || $funcao == "") {
        $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
        echo json_encode($retorno);
    } else {
        //==0 - WEB ====== 1 - APP ==========
        $Dispositivo = $_REQUEST["Dispositivo"];
        $Usuario = $_REQUEST["Usuario"];
        $Token = $_REQUEST["Token"];
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
                if ($funcao == "CadastraAtualizaDeletaLicenca") {
                    $Acao = $_REQUEST["Acao"];
                    $Id = $_REQUEST["Id"];
                    $Nome = $_REQUEST["Nome"];
                    $Usuarios = $_REQUEST["Usuarios"];
                    $Fazendas = $_REQUEST["Fazendas"];
                    CadastraAtualizaDeletaLicenca($Usuario, $Fazendas, $Usuarios, $Nome, $Id, $Acao, $link);
                } else if ($funcao == "CadastraAtualizaDeletaReponsavel") {
                    $UserName = $_REQUEST["UserName"];
                    $Nome = $_REQUEST["Nome"];
                    $Cpf = $_REQUEST["Cpf"];
                    $Email = $_REQUEST["Email"];
                    $Celular = $_REQUEST["Celular"];
                    $Cep = $_REQUEST["Cep"];
                    $Endereco = $_REQUEST["Endereco"];
                    $Numero = $_REQUEST["Numero"];
                    $Complemento = $_REQUEST["Complemento"];
                    $Bairro = $_REQUEST["Bairro"];
                    $Uf = $_REQUEST["Uf"];
                    $Cidade = $_REQUEST["Cidade"];
                    $Licenca = $_REQUEST["Licenca"];
                    $Status = $_REQUEST["Status"];
                    $Id = $_REQUEST["Id"];
                    $Acao = $_REQUEST["Acao"];
                    CadastraAtualizaDeletaReponsavel(
                        $Usuario,
                        $UserName,
                        $Nome,
                        $Cpf,
                        $Email,
                        $Celular,
                        $Cep,
                        $Endereco,
                        $Numero,
                        $Complemento,
                        $Bairro,
                        $Uf,
                        $Cidade,
                        $Licenca,
                        $Status,
                        $Id,
                        $Acao,
                        $link,
                        $verificacao,
                        $Token,
                        $Dispositivo
                    );
                } else if ($funcao == "ResetaSenha") {
                    $Usuario = $_REQUEST["Usuario"];
                    $Id = $_REQUEST["Id"];
                    ResetaSenha($Usuario, $Id, $link);
                } else if ($funcao == "RetornaResponsaveis") {
                    $Id = $_REQUEST["Id"];
                    RetornaResponsaveis($Usuario, $Id, $link);
                } else if ($funcao == "RetornaLicenca") {
                    $Id = $_REQUEST["Id"];
                    RetornaLicenca($Usuario, $Id, $link);
                } else if ($funcao == "RetornaResponsavel") {
                    $Id = $_REQUEST["Id"];
                    RetornaResponsavel($Usuario, $Id, $link);
                } else {
                    $retorno = array("Status" => "erro", "Resposta" => "Nenhuma função encontrada");
                    echo json_encode($retorno);
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
function RetornaResponsaveis($Usuario, $Id, $link)
{
    if ($Id == null || $Id == "") {
        $sql = "select Responsavel_Id,Permissao from Usuarios where Id='$Usuario'";
        $result = $link->query($sql);
        $row = $result->fetch_assoc();
        $Responsavel_Id = $row["Responsavel_Id"];
        if ($row["Permissao"] == "999") {
            $sql = "select * from Reponsavel";
        } else {
            $sql = "select * from Reponsavel where Id='$Responsavel_Id'";
        }
    } else {
        $sql = "select * from Reponsavel where Id='$Id'";
    }
    $result = $link->query($sql);
    $Repostas = array();
    while ($row = $result->fetch_assoc()) {
        $Reposta = array(
            "Id" => $row["Id"],
            "Nome" => $row["Nome"]
        );
        array_push($Repostas, $Reposta);
    }
    $retorno = array("Status" => "Ok", "Resposta" => $Repostas);

    echo json_encode($retorno);
}
function CadastraAtualizaDeletaReponsavel(
    $Usuario,
    $UserName,
    $Nome,
    $Cpf,
    $Email,
    $Celular,
    $Cep,
    $Endereco,
    $Numero,
    $Complemento,
    $Bairro,
    $Uf,
    $Cidade,
    $Licenca,
    $Status,
    $Id,
    $Acao,
    $link,
    $verificacao,
    $Token,
    $Dispositivo
) {
    // Status
    // 0-Ativo
    // 1-Inativo
    // 3-Cacelado
    $UserName = strtolower($UserName);
    if ($Acao == "1") {
        if ($UserName != null && $UserName != "") {
            $sql = "select * from Usuarios where Nome='$UserName'";
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
                $retorno = array("Status" => "Erro", "Resposta" => "Username já está em uso");
                echo json_encode($retorno);
                return;
            }
        } else {
            $retorno = array("Status" => "Erro", "Resposta" => "Informe o UserName");
            echo json_encode($retorno);
            return;
        }
        if ($Cpf != null && $Cpf != "") {
            $sql = "select * from Reponsavel where CPF='$Cpf'";
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
                $retorno = array("Status" => "Erro", "Resposta" => "Cpf já esta cadastrado");
                echo json_encode($retorno);
                return;
            }
        } else {
            $retorno = array("Status" => "Erro", "Resposta" => "Informe o cpf");
            echo json_encode($retorno);
            return;
        }
        $dateTime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO Reponsavel
        (Nome, CPF, Email, Celular, CEP, Endereco, Numero, Complemento, Bairro, UF, Cidade, Lincenca, Status, DataCadastro)
         VALUES
         ('$Nome', '$Cpf', '$Email', '$Celular', '$Cep', '$Endereco', '$Numero', '$Complemento', '$Bairro', '$Uf', '$Cidade', '$Licenca', '$Status','$dateTime')";
        $StatusAcao = "Cadastrado";
    } else if ($Acao == "2") {
        if ($UserName != null && $UserName != "") {
            $sql = "select * from Usuarios where Nome='$UserName' and Responsavel_Id<>'$Id'";
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
                $retorno = array("Status" => "Erro", "Resposta" => "Username já está em uso " . $sql);
                echo json_encode($retorno);
                return;
            }
        } else {
            $retorno = array("Status" => "Erro", "Resposta" => "Informe o UserName");
            echo json_encode($retorno);
            return;
        }
        if ($Cpf != null && $Cpf != "") {
            $sql = "select * from Reponsavel where CPF='$Cpf' and Id<>'$Id'";
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
                $retorno = array("Status" => "Erro", "Resposta" => "Cpf já esta cadastrado");
                echo json_encode($retorno);
                return;
            }
        } else {
            $retorno = array("Status" => "Erro", "Resposta" => "Informe o cpf");
            echo json_encode($retorno);
            return;
        }
        $sql = "UPDATE Reponsavel SET Nome='$Nome',CPF='$Cpf',Email='$Email',Celular='$Celular',CEP='$Cep',Endereco='$Endereco'
        ,Numero='$Numero',Complemento='$Complemento',Bairro='$Bairro',UF='$Uf',Cidade='$Cidade',Lincenca='$Licenca',Status='$Status' WHERE Id='$Id'";
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE Reponsavel SET Status='3' WHERE Id='$Id'";
        $StatusAcao = "Deletado";
    }
    if ($link->query($sql) === TRUE) {
        if ($Acao == "1") {
            $IdResponsavel = $link->insert_id;
            $url = "https://seedgestaorural.com/API/usuarios/index.php";
            $obj = http_build_query(array(
                "verificacao" => $verificacao,
                "UserName" => $UserName,
                "Pemissao" => '0',
                "Status" => '0',
                "Acao" => '1',
                "funcao" => "CadastraAtualizaDeleta",
                "Token" => "$Token",
                "Usuario" => "$Usuario",
                "IdResponsavel" => "$IdResponsavel",
                "Dispositivo" => "$Dispositivo",
            ));
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $Resposta = curl_exec($curl);
            curl_close($curl);
            $Resposta = json_decode($Resposta);
            $url = "https://seedgestaorural.com/API/Email/index.php";
            $obj = http_build_query(array(
                "verificacao" => $verificacao,
                "Destinatario" => "$Email",
                "Assunto" => "Cadastro Seeds",
                "logo" => "https://seedgestaorural.com/admin/images/logoFundo.jpeg",
                "funcao" => "EnviaEmail",
                "Tamplate" => "ConfirmacaoComUsuarioSenha",
                "UsuarioCadastro" => "$UserName",
                "SenhaCadastro" => "@trocarsenha123",
                "linkBotao" => "https://seedgestaorural.com/admin/",
            ));
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $RespostaEmail = curl_exec($curl);
            curl_close($curl);
            $RespostaEmails = json_decode($RespostaEmail);
            $data = date('d/m/Y', strtotime('+30 day'));
            $url = "https://seedgestaorural.com/API/safe2pay/index.php";
            $obj = http_build_query(array(
                "data" => $data,
                "verificacao" => $verificacao,
                "UserName" => $UserName,
                "Id" => "$IdResponsavel",
                "funcao" => "GeraBoleto",
                "Token" => "$Token",
                "Usuario" => "$Usuario",
                "Dispositivo" => "$Dispositivo",
            ));
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $Resposta = curl_exec($curl);
            curl_close($curl);
            $Resposta = json_decode($Resposta);
            if ($Resposta->Status != "Erro") {
                resgistraLog($link, "licencas", "CadastraAtualizaDeletaReponsavel", "Ok",  "Usuário " . $StatusAcao, $Usuario);
                $retorno = array("Status" => "Ok", "Resposta" => "Usuário " . $StatusAcao, "Boleto" => $Resposta, "Email" => $RespostaEmails);
            } else {
                resgistraLog($link, "licencas", "CadastraAtualizaDeletaReponsavel", "Erro",  "Usuário " . $StatusAcao . " - Erro ao criar UserName", $Usuario);
                $retorno = array("Status" => "Erro", "Resposta" => "Usuário " . $StatusAcao);
            }
        } else {
            resgistraLog($link, "licencas", "CadastraAtualizaDeletaReponsavel", "Ok",  "Usuário " . $StatusAcao, $Usuario);
            $retorno = array("Status" => "Ok", "Resposta" => "Usuário " . $StatusAcao);
        }
    } else {
        resgistraLog($link, "licencas", "CadastraAtualizaDeletaReponsavel", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
    }
    echo json_encode($retorno);
}
function CadastraAtualizaDeletaLicenca($Usuario, $Fazendas, $Usuarios, $Nome, $Id, $Acao, $link)
{
    if ($Acao == "1") {
        $sql = "INSERT INTO Licencas (Nome, Usuarios, Fazendas, Status) VALUES ('$Nome', '$Usuarios', '$Fazendas', '0')";
        $StatusAcao = "Cadastrado";
    } else if ($Acao == "2") {
        $sql = "UPDATE Licencas SET Nome='$Nome',Usuarios='$Usuarios',Fazendas='$Fazendas' WHERE Id='$Id'";
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE Licencas SET Status='3' WHERE Id='$Id'";
        $StatusAcao = "Deletado";
    }
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "licencas", "CadastraAtualizaDeletaLicenca", "Ok",  "Licença " . $StatusAcao, $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => "Licença " . $StatusAcao);
    } else {
        resgistraLog($link, "licencas", "CadastraAtualizaDeletaLicenca", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
    }
    echo json_encode($retorno);
}
function RetornaLicenca($Usuario, $Id, $link)
{
    if ($Id == null || $Id == "") {
        $sql = "select * from Licencas where Status='0'";
    } else {
        $sql = "select * from Licencas where Id='$Id' and Status='0'";
    }
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "Usuarios" => $row['Usuarios'],
                "Fazendas" => $row["Fazendas"],
                "Status" => $row["Status"]
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "licencas", "RetornaLicenca", "Ok",  "Retorna licenca", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "licencas", "RetornaLicenca", "Erro",  "Retorna licenca", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum licença encontrado");
    }
    echo json_encode($retorno);
}
function RetornaResponsavel($Usuario, $Id, $link)
{
    $sql = "select Permissao,Responsavel_Id from Usuarios where Id='$Usuario'";
    $result = $link->query($sql);
    $row = $result->fetch_assoc();
    if ($row["Permissao"] == "999") {
        if ($Id == null || $Id == "") {
            $sql = "select RS.*,US.Nome as UserName from Reponsavel RS join Usuarios US ON RS.Id = US.Responsavel_Id where RS.Status<>'3'";
        } else {
            $sql = "select RS.*,US.Nome as UserName from Reponsavel RS join Usuarios US ON RS.Id = US.Responsavel_Id where RS.Status<>'3' and RS.Id='$Id'";
        }
    } else  if ($row["Permissao"] == "0") {
        $Responsavel_Id = $row["Responsavel_Id"];
        if ($Id == null || $Id == "") {
            $sql = "select RS.*,US.Nome as UserName from Reponsavel RS join Usuarios US ON RS.Id = US.Responsavel_Id where RS.Status<>'3' and RS.Id='$Responsavel_Id'";
        } else {
            $sql = "select RS.*,US.Nome as UserName from Reponsavel RS join Usuarios US ON RS.Id = US.Responsavel_Id where RS.Status<>'3' and RS.Id='$Responsavel_Id'";
        }
    } else {
        resgistraLog($link, "licencas", "RetornaResponsavel", "Erro",  "Sem Permissão", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Sem Permissão");
        echo json_encode($retorno);
        return;
    }
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $idReponsavel = $row['Id'];
            $sqlPagamentos = "select * from HistoricoPagamentos where Resposavel_Id='$idReponsavel' ORDER BY Id DESC";
            $resultPagamentos = $link->query($sqlPagamentos);
            $Pagamentos = array();
            while ($rowPagamentos = $resultPagamentos->fetch_assoc()) {
                $StatusPagamento = $rowPagamentos["Status"];
                if ($StatusPagamento == "0") {
                    $StatusPagamento = "Pendente";
                } else {
                    $StatusPagamento = "Pago";
                }
                $arrayPagamentos = array(
                    "Id" => $rowPagamentos["Id"],
                    "Resposavel_Id" => $rowPagamentos["Resposavel_Id"],
                    "IdTransacional" => $rowPagamentos["IdTransacional"],
                    "UrlBoleto" => $rowPagamentos["UrlBoleto"],
                    "DataEmissao" => $rowPagamentos["DataEmissao"],
                    "Status" => $StatusPagamento
                );
                array_push($Pagamentos, $arrayPagamentos);
            }
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "UserName" => $row['UserName'],
                "CPF" => $row['CPF'],
                "Email" => $row["Email"],
                "Celular" => $row["Celular"],
                "CEP" => $row["CEP"],
                "Endereco" => $row["Endereco"],
                "Numero" => $row["Numero"],
                "Complemento" => $row["Complemento"],
                "Bairro" => $row["Bairro"],
                "UF" => $row["UF"],
                "Cidade" => $row["Cidade"],
                "Lincenca" => $row["Lincenca"],
                "Status" => $row["Status"],
                "Pagamentos" => $Pagamentos
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "licencas", "RetornaResponsavel", "Ok",  "Retorna responsavel", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "licencas", "RetornaResponsavel", "Erro",  "Retorna responsavel", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum licença encontrado");
    }
    echo json_encode($retorno);
}
