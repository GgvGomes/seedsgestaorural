<?php
function VerificaLogin($Acesso)
{
    if ($Acesso == null ||$Acesso == "") {
        $Status = "0";
    } else {
        if ($Acesso == "0") {
            $Status = "2";
        } else {
            $Status = "1";
        }
    }
    return $Status;
}
function ConnectUser($Token, $User, $Acesso, $Nome, $Permissao)
{
    setcookie("Acesso_Seeds",  $Acesso, time() + 3600 * 24 * 30 * 12 * 5, '/');
    setcookie("Token_Seeds", $Token, time() + 3600 * 24 * 30 * 12 * 5, '/');
    setcookie("User_Seeds",  $User, time() + 3600 * 24 * 30 * 12 * 5, '/');
    setcookie("Nome_Seeds",  $Nome, time() + 3600 * 24 * 30 * 12 * 5, '/');
    setcookie("Permissao_Seeds",  $Permissao, time() + 3600 * 24 * 30 * 12 * 5, '/');
}

function DesconectUser()
{
    setcookie('Acesso_Seeds', null, -1, '/');
    setcookie('Token_Seeds', null, -1, '/');
    setcookie('User_Seeds', null, -1, '/');
    setcookie('Nome_Seeds', null, -1, '/');
    setcookie('Permissao_Seeds', null, -1, '/');
}