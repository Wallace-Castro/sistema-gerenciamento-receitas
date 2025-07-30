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

$telefone = $_POST["telefone"];
$senha_digitada = $_POST["senha"];

$sql = "SELECT * FROM tb_usuario WHERE telefone = '$telefone'";
$resultado = $conecta->query($sql);

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $senha_hash = $row['senha'];

    if (password_verify($senha_digitada, $senha_hash)) {
        $_SESSION['idUsuario'] = $row['id'];
        echo '<script>alert("Login efetuado com sucesso!");';
        echo 'window.location.href = "../index.php";</script>';
    } else {
        echo '<script>alert("Senha incorreta.");';
        echo 'window.location.href = "../index.php";</script>';
    }
} else {
    echo '<script>alert("Nenhuma conta encontrada com esse telefone.");';
    echo 'window.location.href = "../index.php";</script>';
}

$conecta->close();

