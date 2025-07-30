<?php

session_start();

$nomeB_servidor = "****";
$nomeB_usuario = "****";
$senhaB = "****";
$nome_B = "****";
//Por ser informações pessoais do meu computador eu tirei as informações de acesso

$conecta = new mysqli($nomeB_servidor, $nomeB_usuario, $senhaB, $nome_B);

if ($conecta->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conecta->connect_error);
}

if (isset($_GET['id'])) {
    $id_receita = $_GET['id'];

    $sql_delete_etapas = "DELETE FROM tb_etapa_preparo WHERE receita_id = $id_receita";
    $conecta->query($sql_delete_etapas);

    $sql_delete_ingredientes = "DELETE FROM tb_ingrediente WHERE receita_id = $id_receita";
    $conecta->query($sql_delete_ingredientes);

    $sql_delete_receita = "DELETE FROM tb_receita WHERE id = $id_receita";
    if ($conecta->query($sql_delete_receita) === TRUE) {
        echo '<script>alert("Receita excluída com sucesso!");';
        echo 'window.location.href = "../pesquisar.php";</script>';
        exit;
    } else {
        echo "Erro ao excluir a receita: " . $conecta->error;
    }
}

$conecta->close();
