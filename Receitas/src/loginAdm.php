<?php

session_start();

$nomeB_servidor = "****";
$nomeB_usuario = "****";
$senhaB = "****";
$nome_B = "****";
//Por ser informações pessoais do meu computador eu tirei as informações de acesso

$conecta = new mysqli($nomeB_servidor, $nomeB_usuario, $senhaB, $nome_B);
if ($conecta->connect_error) {
    die("Conexão falhou: " . $conecta->connect_error . "<br>");
}

$nome = $_POST["nome"];
$chave = $_POST["chave"];

$sql = "SELECT * FROM tb_usuario_adm WHERE nome = '$nome' AND chave = '$chave'";

$resultado = $conecta->query($sql);
if ($resultado->num_rows > 0) {
    echo '<script>alert("login efetuado com sucesso!");';
    echo 'window.location.href = "../revisao.php";</script>';
    $row = $resultado->fetch_assoc();
    $_SESSION['Adm'] = $row['id'];
}
$conecta->close();
