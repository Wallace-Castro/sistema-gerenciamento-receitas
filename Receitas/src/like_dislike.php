<?php
session_start();
$usuario_logado = isset($_SESSION['idUsuario']);

$nomeB_servidor = "****";
$nomeB_usuario = "****";
$senhaB = "****";
$nome_B = "****";
//Por ser informações pessoais do meu computador eu tirei as informações de acesso

$conecta = new mysqli($nomeB_servidor, $nomeB_usuario, $senhaB, $nome_B);

if ($conecta->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conecta->connect_error);
}

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não está logado']);
    exit;
}

$user_id = $_SESSION['idUsuario'];
$receita_id = $_POST['receita_id'];
$reacao = $_POST['reacao'];

$sql = "INSERT INTO tb_reacao_receita (usuario_id, receita_id, reacao) 
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE reacao = ?";

$stmt = $conecta->prepare($sql);
$stmt->bind_param("iiss", $user_id, $receita_id, $reacao, $reacao);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao registrar reação']);
}

$stmt->close();
$conecta->close();
