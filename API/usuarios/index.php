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
   
        $Dispositivo = $_REQUEST["Dispositivo"];
        $Usuario = $_REQUEST["Usuario"] ?? null;
        $Token = $_REQUEST["Token"] ?? null;
        if (($Usuario == null || $Usuario == "") && $funcao != "Logar") {
            $retorno = array("Status" => "Erro", "Resposta" => "Nenhum usuário detectado");
            echo json_encode($retorno);
            return;
        } else if ($funcao == "Logar") {
            $UserName = $_REQUEST["UserName"];
            $Senha = $_REQUEST["Senha"];
            $Dispositivo = $_REQUEST["Dispositivo"];
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
                if ($funcao == "CadastraAtualizaDeletaUsuarios") {
                    $Id = $_REQUEST["Id"];
                    $Acao = $_REQUEST["Acao"];
                    $Fazenda = $_REQUEST["Fazenda"];
                    $Permissao = $_REQUEST["Permissao"];
                    $IdResponsavel = $_REQUEST["IdResponsavel"];
                    $Nome = $_REQUEST["Nome"];
                    CadastraAtualizaDeletaUsuarios($Usuario, $IdResponsavel, $Nome, $Permissao, $Id, $Fazenda, $Acao, $link);
                } else if ($funcao == "AtualizaSenha") {
                    $Senha = $_REQUEST["Senha"];
                    $SenhaValida = $_REQUEST["SenhaValida"];
                    AtualizaSenha($Usuario, $Senha, $SenhaValida, $link);
                } else if ($funcao == "DesconectUser") {
                    DesconectUser($Usuario, $Dispositivo, $link);
                } else if ($funcao == "CarregaUsuarios") {
                    $Id = $_REQUEST["Id"];
                    CarregaUsuarios($Usuario, $Id, $link);
                } else if ($funcao == "CarregaUsuariosFazendas") {
                    CarregaUsuariosFazendas($Usuario, $link);
                } else if ($funcao == "ResetaSenha") {
                    $Usuario = $_REQUEST["Usuario"];
                    $Id = $_REQUEST["Id"];
                    ResetaSenha($Usuario, $Id, $link);
                } else if ($funcao == "UsuarioFazenda") {
                    $Usuario = $_REQUEST["Usuario"];
                    $FazendaId = $_REQUEST["FazendaId"];
                    $UserId = $_REQUEST["UserId"];
                    $Id = $_REQUEST["Id"];
                    $Acao = $_REQUEST["Acao"];
                    UsuarioFazenda($UserId, $FazendaId, $Usuario, $Acao, $Id, $link);
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
function DesconectUser($Usuario, $Dispositivo, $link)
{
    $sql = "DELETE FROM Token_Usuarios WHERE Usuario_Id='$Usuario'";
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "usuarios", "DesconectUser", "Ok",  "Usuário deslogado - " . $Dispositivo, $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => "Usuário deslogado");
    } else {
        resgistraLog($link, "usuarios", "DesconectUser", "Erro", "Erro ao deslogar", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao deslogar");
    }
    echo json_encode($retorno);
}
function UsuarioFazenda($UserId, $FazendaId, $Usuario, $Acao, $Id, $link)
{
    if ($Acao == "1") {
        $sql = "select Id from Rl_Fazenda_Usuario where Usuario_Id='$UserId' and Fazenda_Id='$FazendaId'";
        $result = $link->query($sql);
        if ($result->num_rows > 0) {
            resgistraLog($link, "usuarios", "VinculaUsuarioFazenda", "Erro",  "Usuário ja está vinculado a essa fazenda" . $sql, $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => "Usuário ja está vinculado a essa fazenda");
        } else {
            $sql = "INSERT INTO Rl_Fazenda_Usuario (Usuario_Id, Fazenda_Id) VALUES ('$UserId', '$FazendaId')";
            if ($link->query($sql) === TRUE) {
                resgistraLog($link, "usuarios", "VinculaUsuarioFazenda", "Ok",  "Usuário Vinculado", $Usuario);
                $retorno = array("Status" => "Ok", "Resposta" => "Usuário Vinculado");
            } else {
                resgistraLog($link, "usuarios", "VinculaUsuarioFazenda", "Erro", "Erro ao vincular usuário na fazenda" . $sql, $Usuario);
                $retorno = array("Status" => "Erro", "Resposta" => "Erro ao vincular usuário na fazenda");
            }
        }
    } else  if ($Acao == "3") {
        $sql = "DELETE FROM Rl_Fazenda_Usuario WHERE Id='$Id'";
        if ($link->query($sql) === TRUE) {
            resgistraLog($link, "usuarios", "VinculaUsuarioFazenda", "Ok",  "Usuário Desvinculado", $Usuario);
            $retorno = array("Status" => "Ok", "Resposta" => "Usuário Desvinculado");
        } else {
            resgistraLog($link, "usuarios", "VinculaUsuarioFazenda", "Erro",  "Erro ao desvincular usuário : " . $sql, $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => "Erro ao desvincular usuário");
        }
    } else {
        resgistraLog($link, "usuarios", "VinculaUsuarioFazenda", "Erro", "Nenhuma ação detectada", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhuma ação detectada - " . $Acao);
    }
    echo json_encode($retorno);
}
function Logar($UserName, $Senha, $Dispositivo, $link, $verificacao)
{   
    
    if ($UserName == null || $UserName == "") {
        $retorno = array("Status" => "Erro", "Resposta" => "Infome o usuário");
        echo json_encode($retorno);
        return;
    }
    if ($Senha == null || $Senha == "") {
        $retorno = array("Status" => "Erro", "Resposta" => "Infome a senha");
        echo json_encode($retorno);
        return;
    }
    $sql = "select * from Usuarios where Nome='$UserName' and Senha='$Senha'";
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Usuario = $row["Id"];
            if ($Senha == "@trocarsenha123") {
                $Respostas = array(
                    "Id" => $row["Id"],
                    "Nome" => $row["Nome"],
                    "Status" => $row["Status"],
                    "Permissao" => $row["Permissao"],
                    "PrimeiroAcesso" => "0"
                );
            } else {
                $Respostas = array(
                    "Id" => $row["Id"],
                    "Nome" => $row["Nome"],
                    "Status" => $row["Status"],
                    "Permissao" => $row["Permissao"],
                    "PrimeiroAcesso" => "1"
                );
            }
            if ($row["Status"] == "0") {
                $url = "https://seedgestaorural.com/API/Token/index.php";
                $obj = http_build_query(array(
                    "verificacao" => $verificacao,
                    "funcao" => "GeraToken",
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
                $RetornaTokenR =  json_decode($RetonaToken);
                if ($RetornaTokenR->Status == "Ok") {
                    resgistraLog($link, "usuarios", "Logar", "Ok",  "Usuário Logado", $Usuario);
                    $retorno = array("Status" => "Ok", "Resposta" => $Respostas, "Token" => $RetornaTokenR->Token);
                } else {
                    resgistraLog($link, "usuarios", "Logar", "Erro",  $RetornaTokenR->Resposta, $Usuario);
                    $retorno = array("Status" => "Erro", "Resposta" => $RetornaTokenR->Resposta);
                }
            } else {
                resgistraLog($link, "usuarios", "Logar", "Erro",  "Usuário tentou logar porém estava bloqueado", $Usuario);
                $retorno = array("Status" => "Erro", "Resposta" => "Usuário bloqueado");
            }
        }
    } else {
        resgistraLog($link, "usuarios", "Logar", "Erro",  "Senha incorreta nenhum usuário encotrado: " . $sql, "");
        $retorno = array("Status" => "Erro", "Resposta" => "Senha incorreta nenhum usuário encotrado");
    }
    echo json_encode($retorno);
}
function CadastraAtualizaDeletaUsuarios($Usuario, $IdResponsavel, $Nome, $Permissao, $Id, $Fazenda, $Acao, $link)
{
    if ($Acao == "1") {
        $sql = "INSERT INTO Usuarios (Nome, Senha, Permissao, Status,Responsavel_Id) VALUES
         ('$Nome', '@trocarsenha123', '$Permissao', '0','$IdResponsavel')";
        $StatusAcao = "Cadastrado";
    } else if ($Acao == "2") {
        $sql = "UPDATE Usuarios SET Nome='$Nome',Permissao='$Permissao',Responsavel_Id='$IdResponsavel' WHERE Id='$Id'";
        $StatusAcao = "Atualizados";
    } else {
        $sql = "UPDATE Usuarios SET Status='3' WHERE Id='$Id'";
        $StatusAcao = "Deletado";
    }
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "usuarios", "CadastraAtualizaDeleta", "Ok",  "Usuário " . $StatusAcao, $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => "Usuário " . $StatusAcao);
    } else {
        resgistraLog($link, "usuarios", "CadastraAtualizaDeleta", "Erro", "Erro ao cadastrar : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao cadastrar");
    }
    echo json_encode($retorno);
}
function AtualizaSenha($Usuario, $Senha, $SenhaValida, $link)
{
    if ($Senha == $SenhaValida) {
        if (strlen($Senha) >= 5) {
            $sql = "UPDATE Usuarios SET Senha='$Senha' WHERE Id='$Usuario'";
            if ($link->query($sql) === TRUE) {
                resgistraLog($link, "usuarios", "AtualizaSenha", "Ok",  "Senha alterada", $Usuario);
                $retorno = array("Status" => "Ok", "Resposta" => "Senha alterada ");
            } else {
                resgistraLog($link, "usuarios", "AtualizaSenha", "Erro", "Erro ao atualizar senha : " . $sql, $Usuario);
                $retorno = array("Status" => "Erro", "Resposta" => "Erro ao atualizar senha");
            }
        } else {
            resgistraLog($link, "usuarios", "AtualizaSenha", "Erro", "Senha com menos de 5 caracteres", $Usuario);
            $retorno = array("Status" => "Erro", "Resposta" => "Senha deve conter pelo menos 5 caracteres- " . strlen($Senha));
        }
    } else {
        resgistraLog($link, "usuarios", "AtualizaSenha", "Erro", "Senhas diferente", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Senhas diferentes");
    }
    echo json_encode($retorno);
}
function CarregaUsuariosFazendas($Usuario, $link)
{
    $sql = "select RV.Id as Id,US.Nome as Username,FZ.Nome as Fazenda from Rl_Fazenda_Usuario RV join Usuarios US on RV.Usuario_Id = US.Id join Fazenda FZ ON RV.Fazenda_Id = FZ.Id where US.Status<>'3' and FZ.Status<>'3'";
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Username" => $row['Username'],
                "Fazenda" => $row['Fazenda']
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "usuarios", "CarregaUsuariosFazendas", "Ok",  "Retorna Usuários", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "usuarios", "CarregaUsuariosFazendas", "Erro",  "Retorna Usuários", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum usuários encontrado");
    }
    echo json_encode($retorno);
}
function CarregaUsuarios($Usuario, $Id, $link)
{
    $sqlUsuario = "select Permissao,Responsavel_Id from Usuarios where Id='$Usuario'";
    $resultUsuario = $link->query($sqlUsuario);
    $rowUsuario = $resultUsuario->fetch_assoc();
    if ($rowUsuario["Permissao"] == "999") {
        if ($Id == null || $Id == "") {
            $sql = "select * from Usuarios where Status='0'";
        } else {
            $sql = "select * from Usuarios where Id='$Id' and Status='0'";
        }
    } else {
        $Responsavel_Id = $rowUsuario["Responsavel_Id"];
        if ($Id == null || $Id == "") {
            $sql = "select * from Usuarios where Status='0' and Responsavel_Id='$Responsavel_Id'";
        } else {
            $sql = "select * from Usuarios where Id='$Id' and Status='0' and Responsavel_Id='$Responsavel_Id'";
        }
    }
    $result = $link->query($sql);
    $respostas = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resposta = array(
                "Id" => $row['Id'],
                "Nome" => $row['Nome'],
                "Permissao" => $row['Permissao'],
                "Responsavel_Id" => $row["Responsavel_Id"]
            );
            array_push($respostas, $resposta);
        }
        resgistraLog($link, "usuarios", "CarregaUsuarios", "Ok",  "Retorna Usuários", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => $respostas);
    } else {
        resgistraLog($link, "usuarios", "CarregaUsuarios", "Erro",  "Retorna Usuários", $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Nenhum usuários encontrado");
    }
    echo json_encode($retorno);
}
function ResetaSenha($Usuario, $Id, $link)
{
    $sql = "UPDATE Usuarios SET Senha='@trocarsenha123' WHERE Id='$Id'";
    if ($link->query($sql) === TRUE) {
        resgistraLog($link, "usuarios", "ResetaSenha", "Ok",  "Senha Resetada", $Usuario);
        $retorno = array("Status" => "Ok", "Resposta" => "Senha Resetada");
    } else {
        resgistraLog($link, "usuarios", "ResetaSenha", "Erro", "Erro ao resetar senha : " . $sql, $Usuario);
        $retorno = array("Status" => "Erro", "Resposta" => "Erro ao resetar senha");
    }
    echo json_encode($retorno);
}
