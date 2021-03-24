<?php
function resgistraLog($link, $funcaoL, $acao, $status, $resposta, $user)
{
    $data = date("Y-m-d H:i:s");
    $sql = "INSERT INTO logs (funcao, acao,usuario,status,resposta, data)
VALUES ('$funcaoL', '$acao','$user','$status','$resposta', '$data')";
    if ($link->query($sql) === TRUE) {
    } else {
        $sql = "INSERT INTO logs (funcao, acao,usuario,status,resposta, data)
        VALUES ('Registra Log', 'Registra Log','$user','Error','$link->error', '$data')";
    }
    $link->close();
}
