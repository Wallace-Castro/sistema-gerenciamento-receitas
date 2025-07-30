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

$sql_delete_etapas = "DELETE FROM tb_etapa_preparo WHERE receita_id = $receita_id";
if ($conecta->query($sql_delete_etapas) === TRUE) {
    $sql_delete_ingredientes = "DELETE FROM tb_ingrediente WHERE receita_id = $receita_id";
    if ($conecta->query($sql_delete_ingredientes) === TRUE) {
        $sql_delete_receita = "DELETE FROM tb_receita WHERE id = $receita_id";
        if ($conecta->query($sql_delete_receita) === TRUE) {
            echo "<script>alert('Receita negada e excluída com sucesso.'); window.location.href = '../revisao.php';</script>";
        } else {
            echo "Erro ao excluir a receita: " . $conecta->error;
        }
    } else {
        echo "Erro ao excluir os ingredientes: " . $conecta->error;
    }
} else {
    echo "Erro ao excluir as etapas de preparo: " . $conecta->error;
}

$conecta->close();

