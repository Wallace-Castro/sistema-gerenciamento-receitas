<?php

session_start();

$receita_id = $_POST['receita_id'];

$nomeB_servidor = "****";
$nomeB_usuario = "****";
$senhaB = "****";
$nome_B = "****";
//Por ser informações pessoais do meu computador eu tirei as informações de acesso

$conecta = new mysqli($nomeB_servidor, $nomeB_usuario, $senhaB, $nome_B);
if ($conecta->connect_error) {
    die("Conexão falhou: " . $conecta->connect_error . "<br>");
}

$sql = "UPDATE tb_receita SET aprovado = TRUE WHERE id = $receita_id";
if ($conecta->query($sql) === TRUE) {
    echo "<script>alert('Receita aprovada com sucesso.'); window.location.href = '../revisao.php';</script>";
} else {
    echo "Erro ao aprovar a receita: " . $conecta->error;
}

$conecta->close();

