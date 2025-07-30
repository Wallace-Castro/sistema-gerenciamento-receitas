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

if (isset($_POST['id_receita_atualizar']) && isset($_POST['titulo_atualizar']) && isset($_POST['ingredientes_atualizar']) && isset($_POST['metodo_preparo_atualizar'])) {
    $id_receita = $_POST['id_receita_atualizar'];
    $titulo = $_POST['titulo_atualizar'];
    $ingredientes = $_POST['ingredientes_atualizar'];
    $metodo_preparo = $_POST['metodo_preparo_atualizar'];
    
    $sql = "UPDATE tb_receita SET nome = '$titulo', aprovado = FALSE WHERE id = $id_receita";
    if ($conecta->query($sql) === TRUE) {

        $sql_delete_ingredientes = "DELETE FROM tb_ingrediente WHERE receita_id = $id_receita";
        $conecta->query($sql_delete_ingredientes);

        $sql_delete_metodo_preparo = "DELETE FROM tb_etapa_preparo WHERE receita_id = $id_receita";
        $conecta->query($sql_delete_metodo_preparo);

        $ingredientes_array = explode(", ", $ingredientes);
        foreach ($ingredientes_array as $ingrediente) {
            $sql_insert_ingrediente = "INSERT INTO tb_ingrediente (nome, receita_id) VALUES ('$ingrediente', $id_receita)";
            $conecta->query($sql_insert_ingrediente);
        }

        $sql_insert_metodo_preparo = "INSERT INTO tb_etapa_preparo (descricao, ordem, receita_id) VALUES ('$metodo_preparo', 1 ,$id_receita)";
        $conecta->query($sql_insert_metodo_preparo);

        echo '<script>alert("Receita atualizada com sucesso!");';
        echo 'window.location.href = "../pesquisar.php";</script>';
        exit;
    } else {
        echo "Erro ao atualizar a receita: " . $conecta->error;
    }
}

$conecta->close();