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
if (isset($_SESSION['idUsuario'])) {
    $id = $_SESSION['idUsuario'];
} else {
    die("ID de usuário não encontrado na sessão.");
}
if (isset($_POST['nome']) && isset($_POST['telefone']) && isset($_POST['senha'])) {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];

    $sql = "UPDATE tb_usuario SET nome='$nome', senha='$senha', telefone='$telefone' WHERE id='$id'";

    if ($conecta->query($sql) === TRUE) {
        echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href = '../perfil.php';</script>";
    } else {
        echo "Erro ao atualizar o perfil: " . $conecta->error;
    }
} else {
    echo "Dados do formulário não recebidos.";
}

$conecta->close();

