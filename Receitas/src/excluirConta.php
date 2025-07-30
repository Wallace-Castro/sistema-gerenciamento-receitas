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

$sql = "DELETE ep FROM tb_etapa_preparo AS ep INNER JOIN tb_receita AS rec ON ep.receita_id = rec.id WHERE rec.usuario_id = $idUsuario";

if ($conecta->query($sql) === TRUE) {
    $sql = "DELETE ing FROM tb_ingrediente AS ing INNER JOIN tb_receita AS rec ON ing.receita_id = rec.id WHERE rec.usuario_id = $idUsuario";

    if ($conecta->query($sql) === TRUE) {
        $sql = "DELETE FROM tb_receita WHERE usuario_id = $idUsuario";

        if ($conecta->query($sql) === TRUE) {
            $sql = "DELETE FROM tb_usuario WHERE id = $idUsuario";

            if ($conecta->query($sql) === TRUE) {
                session_destroy();
                echo "<script>alert('Perfil excluido com sucesso!'); window.location.href = '../index.php';</script>";
            } else {
                echo "Erro ao excluir usuário: " . $conecta->error . "<br>";
            }
        }
    }
}
$conecta->close();
