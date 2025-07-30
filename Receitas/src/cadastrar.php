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
$telefone = $_POST["telefone"];
$senha = $_POST["senha"];

$senha_hash = password_hash($senha, PASSWORD_BCRYPT);

$sql_verificar = "SELECT * FROM tb_usuario WHERE telefone='$telefone'";
$resultado = $conecta->query($sql_verificar);

if ($resultado->num_rows > 0) {
    echo '<script>alert("Já existe uma conta com esse telefone.");';
    echo 'window.location.href = "../index.php";</script>';
} else {
    $sql = "INSERT INTO tb_usuario(nome,senha,telefone) VALUES ('$nome','$senha_hash','$telefone')";

    if ($conecta->query($sql) === TRUE) {
        echo '<script>alert("Registro inserido com sucesso!");';
        echo 'window.location.href = "../index.php";</script>';
        $row = $resultado->fetch_assoc();
        $_SESSION['idUsuario'] = $row['id'];
    } else {
        echo '<script>alert("Erro em fazer o cadastro");';
        echo "Erro ao inserir registro: " . $conecta->error;
        echo 'window.location.href = "../index.php";</script>';
    }
}

$conecta->close();

