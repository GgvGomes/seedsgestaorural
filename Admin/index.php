<?php
include './function/index.php';
$Resposta = VerificaLogin($Acesso);
if ($Resposta == "0") {
    header('Location: ./pages/login/');
} else if($Resposta == "2"){
    header('Location: ./pages/login/atualizaSenha/');
}else {
    header('Location: ./pages/main/');
}
