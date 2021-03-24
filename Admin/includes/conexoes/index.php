<?php
if (!isset($_SESSION)) {
    session_start();
}
$User_Id = $_COOKIE["User_Seeds"] ?? null;
$User_Token = $_COOKIE["Token_Seeds"] ?? null;
$Acesso = $_COOKIE["Acesso_Seeds"] ?? null;
$Nome_Seeds = $_COOKIE["Nome_Seeds"] ?? null;
$Permissao_Seeds = $_COOKIE["Permissao_Seeds"] ?? null;
date_default_timezone_set('America/Sao_Paulo');
// $urlApi = "https://seedgestaorural.com/API/";
$urlApi = "http://localhost:8580/siteseeds/API/";
$urlApiA6 = "https://a6technology.com.br/";
// $urlPrincipal = "https://seedgestaorural.com/admin/";
$urlPrincipal = "http://localhost:8580/siteseeds/Admin/";
$chaveApiA6 = md5("&&)#$#A6%(&*$#technology*(*$");
$chaveApi = md5("&&)#$#A6&&)#$#technology&&)Seed#$#");
