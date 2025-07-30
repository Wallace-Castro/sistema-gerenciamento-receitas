<?php
session_start();
if (!isset($_SESSION['Adm'])) {
    echo "<script>alert('Somente pessoal autorizado'); window.location.href = 'index.php';</script>";
    exit();
}
$nomeB_servidor = "****";
$nomeB_usuario = "****";
$senhaB = "****";
$nome_B = "****";
//Por ser informações pessoais do meu computador eu tirei as informações de acesso

$conecta = new mysqli($nomeB_servidor, $nomeB_usuario, $senhaB, $nome_B);
if ($conecta->connect_error) {
    die("Conexão falhou: " . $conecta->connect_error . "<br>");
}

$sql = "SELECT r.id, r.nome AS nome_receita, r.tempo_preparo, u.nome AS nome_usuario, c.nome AS nome_categoria, e.descricao
        FROM tb_receita r
        INNER JOIN tb_usuario u ON r.usuario_id = u.id
        INNER JOIN tb_categoria c ON r.categoria_id = c.id
        LEFT JOIN tb_etapa_preparo e ON r.id = e.receita_id
        WHERE r.aprovado = FALSE
        ORDER BY r.id";
$result = $conecta->query($sql);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Receitas Pendentes de Aprovação</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/revisao.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Sistema de Receitas</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Página Inicial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="src/logout.php">Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    $receita_id_atual = null;

                    while ($row = $result->fetch_assoc()) {
                        $receita_id = $row['id'];

                        if ($receita_id != $receita_id_atual) {
                            $nome_receita = $row['nome_receita'];
                            $tempo_preparo = $row['tempo_preparo'];
                            $nome_usuario = $row['nome_usuario'];
                            $nome_categoria = $row['nome_categoria'];
                            ?>
                            <div class="col-md-6">
                                <div class="recipe">
                                    <div class="recipe-content">
                                        <h2><?php echo $nome_receita; ?></h2>
                                        <h3>Categoria: <?php echo $nome_categoria; ?></h3>
                                        <p><strong>Tempo de preparo:</strong> <?php echo $tempo_preparo; ?> minutos</p>
                                        <p><strong>Autor:</strong> <?php echo $nome_usuario; ?></p>
                                        <h4>Etapas de Preparo:</h4>
                                        <ul>
                                            <?php
                                            echo "<li>" . $row['descricao'] . "</li>";
                                            ?>
                                        </ul>
                                        <form action="src/aprovar_receita.php" method="post">
                                            <input type="hidden" name="receita_id" value="<?php echo $receita_id; ?>">
                                            <button type="submit" name="aprovar" class="btn btn-success">Aprovar</button>
                                        </form>
                                        <form action="src/negar_receita.php" method="post">
                                            <input type="hidden" name="receita_id" value="<?php echo $receita_id; ?>">
                                            <button type="submit" name="negar" class="btn btn-danger">Negar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $receita_id_atual = $receita_id;
                        } else {
                            ?>
                            <div class="col-md-6">
                                <div class="recipe">
                                    <div class="recipe-content">
                                        <ul>
                                            <?php
                                            echo "<li>" . $row['descricao'] . "</li>";
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo "<p class='col-md-12'>Não há receitas pendentes de aprovação.</p>";
                }
                ?>
            </div>
        </div>
    </body>
</html>
