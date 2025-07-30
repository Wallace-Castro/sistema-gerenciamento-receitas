<?php
session_start();
$usuario_logado = isset($_SESSION['idUsuario']);

$nomeB_servidor = "****";
$nomeB_usuario = "****";
$senhaB = "****";
$nome_B = "****";
//Por ser informações pessoais do meu computador eu tirei as informações de acesso

$conexao = new mysqli($nomeB_servidor, $nomeB_usuario, $senhaB, $nome_B);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

if (isset($_POST["submit"])) {
    $titulo = $_POST["titulo"];
    $ingrediente = isset($_POST["ingrediente"]) ? $_POST["ingrediente"] : "";

    $sql = "SELECT r.id, r.nome, r.usuario_id, 
                   GROUP_CONCAT(DISTINCT i.nome ORDER BY i.nome SEPARATOR ', ') AS ingredientes, 
                   GROUP_CONCAT(DISTINCT e.descricao ORDER BY e.ordem SEPARATOR ' ') AS metodo_preparo 
            FROM tb_receita r
            LEFT JOIN tb_ingrediente i ON r.id = i.receita_id
            LEFT JOIN tb_etapa_preparo e ON r.id = e.receita_id
            WHERE r.nome LIKE '%$titulo%' AND r.aprovado = TRUE";

    if (!empty($ingrediente)) {
        $sql .= " AND i.nome LIKE '%$ingrediente%'";
    }

    $sql .= " GROUP BY r.id";

    $resultado = $conexao->query($sql);
} else {
    $sql = "SELECT r.id, r.nome, r.usuario_id, 
                   GROUP_CONCAT(DISTINCT i.nome ORDER BY i.nome SEPARATOR ', ') AS ingredientes, 
                   GROUP_CONCAT(DISTINCT e.descricao ORDER BY e.ordem SEPARATOR ' ') AS metodo_preparo 
            FROM tb_receita r
            LEFT JOIN tb_ingrediente i ON r.id = i.receita_id
            LEFT JOIN tb_etapa_preparo e ON r.id = e.receita_id
            WHERE r.aprovado = TRUE
            GROUP BY r.id";

    $resultado = $conexao->query($sql);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisar Receitas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/pesquisar.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Receitas</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="receitas.php">Receitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="perfil.php">Perfil</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card pesquisa-card">
                    <div class="card-header">
                        Pesquisar Receitas
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="titulo">Título da Receita</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Digite o título da receita">
                            </div>
                            <div class="form-group">
                                <label for="ingrediente">Ingrediente</label>
                                <input type="text" class="form-control" id="ingrediente" name="ingrediente" placeholder="Digite o ingrediente">
                            </div>
                            <button type="submit" class="btn btn-custom" name="submit">Pesquisar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php
                if (isset($resultado) && $resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo '<div id="receita-' . $row["id"] . '" class="recipe">';
                        echo '<div class="recipe-content">';
                        echo '<h2>' . $row["nome"] . '</h2>';
                        echo '<h3>Ingredientes</h3>';
                        echo '<ul><li>' . str_replace(", ", "</li><li>", $row["ingredientes"]) . '</li></ul>';
                        echo '<h3>Modo de Preparo</h3>';
                        echo '<p>' . $row["metodo_preparo"] . '</p>';
                        if ($usuario_logado && $_SESSION['idUsuario'] == $row['usuario_id']) {
                            echo '<button class="btn btn-primary" onclick="editarReceita(' . $row["id"] . ', \'' . $row["nome"] . '\', \'' . $row["ingredientes"] . '\', \'' . $row["metodo_preparo"] . '\')">Atualizar</button>';
                            echo '<button class="btn btn-danger" onclick="excluirReceita(' . $row["id"] . ', \'' . $row["nome"] . '\')">Excluir</button>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "Nenhuma receita encontrada";
                }
                $conexao->close();
                ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="atualizarReceitaModal" tabindex="-1" role="dialog" aria-labelledby="atualizarReceitaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="atualizarReceitaModalLabel">Atualizar Receita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAtualizarReceita" action="src/atualizarReceita.php" method="POST">
                        <div class="form-group">
                            <label for="titulo_atualizar">Título da Receita</label>
                            <input type="text" class="form-control" id="titulo_atualizar" name="titulo_atualizar">
                        </div>
                        <div class="form-group">
                            <label for="ingredientes_atualizar">Ingredientes</label>
                            <textarea class="form-control" id="ingredientes_atualizar" name="ingredientes_atualizar" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="metodo_preparo_atualizar">Método de Preparo</label>
                            <textarea class="form-control" id="metodo_preparo_atualizar" name="metodo_preparo_atualizar" rows="5"></textarea>
                        </div>
                        <input type="hidden" id="id_receita_atualizar" name="id_receita_atualizar">
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function editarReceita(id, nome, ingredientes, metodo_preparo) {
            $('#id_receita_atualizar').val(id);
            $('#titulo_atualizar').val(nome);
            $('#ingredientes_atualizar').val(ingredientes);
            $('#metodo_preparo_atualizar').val(metodo_preparo);
            $('#atualizarReceitaModal').modal('show');
        }

        function excluirReceita(id, nome) {
            if (confirm("Tem certeza que deseja excluir a receita '" + nome + "'?")) {
                window.location.href = 'src/excluirReceita.php?id=' + id;
            }
        }
    </script>
</body>
</html>
