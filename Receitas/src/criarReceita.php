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

$idUsuario = $_SESSION['idUsuario'];
$titulo = $_POST["titulo"];
$tempo = $_POST["tempo_preparo"];
$categoria = $_POST["categoria"];
$ingredientes = $_POST["ingredientes"];
$preparo = $_POST["modo_preparo"];

$sqlreceita = "INSERT INTO tb_receita(nome, tempo_preparo, usuario_id, categoria_id) VALUES ('$titulo', '$tempo', '$idUsuario', '$categoria')";

if ($conecta->query($sqlreceita) === TRUE) {
    $id_receita = $conecta->insert_id;

    $sqlingredientes = "INSERT INTO tb_ingrediente(nome, quantidade, receita_id) VALUES ('$ingredientes', NULL, $id_receita)";
    if ($conecta->query($sqlingredientes) === TRUE) {

        $sql_etapa = "INSERT INTO tb_etapa_preparo(descricao, ordem, receita_id) VALUES ('$preparo', 1, $id_receita)";
        if ($conecta->query($sql_etapa)) {
            echo '<script>alert("Receita criada com sucesso agora espere ser aprovada para apareçer no site!");';
            echo 'window.location.href = "../receitas.php";</script>';
            exit;
        } else {
            echo "Erro ao cadastrar etapa de preparo: " . $conecta->error;
        }
    } else {
        echo "Erro ao cadastrar ingredientes: " . $conecta->error;
    }
} else {
    echo "Erro ao cadastrar receita: " . $conecta->error;
}

$conecta->close();
