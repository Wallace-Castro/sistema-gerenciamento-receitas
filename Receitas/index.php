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

$sql = "SELECT r.id, r.nome, c.nome AS categoria, 
        GROUP_CONCAT(DISTINCT i.nome SEPARATOR ', ') AS ingredientes, 
        GROUP_CONCAT(DISTINCT e.descricao ORDER BY e.ordem ASC SEPARATOR ' ') AS metodo_preparo, 
        SUM(CASE WHEN rr.reacao = 'like' THEN 1 ELSE 0 END) AS likes,
        SUM(CASE WHEN rr.reacao = 'dislike' THEN 1 ELSE 0 END) AS dislikes
        FROM tb_receita r
        LEFT JOIN tb_categoria c ON r.categoria_id = c.id
        LEFT JOIN tb_ingrediente i ON r.id = i.receita_id
        LEFT JOIN tb_etapa_preparo e ON r.id = e.receita_id
        LEFT JOIN tb_reacao_receita rr ON r.id = rr.receita_id
        WHERE r.aprovado = TRUE
        GROUP BY r.id
        ORDER BY likes DESC";

$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Receitas</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="css/index.css">
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
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <?php if (!$usuario_logado) { ?>
                            <button class="btn btn-outline-light mx-2" data-toggle="modal" data-target="#loginModal">Login</button>
                            <button class="btn btn-outline-light mx-2" data-toggle="modal" data-target="#signupModal">Cadastrar</button>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="favoritos" droppable="true">
        </div>
        <div class="container mt-5" style="margin-top: 100px;">
            <div class="row">
                <div class="col-md-8">
                    <?php
                    if ($resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) {
                            echo '<div id="receita-' . $row["id"] . '" class="recipe">';
                            echo '<div class="recipe-content">';
                            echo '<h2>' . $row["nome"] . ' (' . $row["categoria"] . ')</h2>';
                            echo '<h3>Ingredientes</h3>';
                            echo '<ul><li>' . $row["ingredientes"] . '</li></ul>';
                            echo '<h3>Modo de Preparo</h3>';
                            echo '<p>' . $row["metodo_preparo"] . '</p>';
                            if ($usuario_logado) {
                                echo '<button class="btn-like" onclick="like(' . $row["id"] . ')">Like</button>';
                                echo '<button class="btn-dislike" onclick="dislike(' . $row["id"] . ')">Dislike</button>';
                            }
                            echo '<div class="likes">Likes: ' . $row["likes"] . '</div>';
                            echo '<div class="dislikes">Dislikes: ' . $row["dislikes"] . '</div>';
                            echo '<button class="btn-favorito" onclick="adicionarFavorito(' . $row["id"] . ', \'' . $row["nome"] . '\')">Favorito</button>';

                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "Nenhuma receita encontrada.";
                    }
                    $conexao->close();
                    ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-dark text-light rounded-4 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <h1 class="fw-bold mb-0 fs-2">Entrar na conta</h1>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 pt-0">
                        <form action="src/login.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="tel" name="telefone" class="form-control rounded-3 bg-dark text-light" id="floatingPhone" placeholder="Telefone">
                                <label for="floatingPhone">Telefone</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="senha" class="form-control rounded-3 bg-dark text-light" id="floatingPassword" placeholder="Senha">
                                <label for="floatingPassword">Senha</label>
                            </div>
                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-dark text-light rounded-4 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <h1 class="fw-bold mb-0 fs-2">Cadastre-se</h1>
                        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 pt-0">
                        <form method="POST" action="src/cadastrar.php">
                            <div class="form-floating mb-3">
                                <input type="text" name="nome" class="form-control rounded-3 bg-dark text-light" id="floatingName" placeholder="Nome">
                                <label for="floatingName">Nome</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="tel" name="telefone" class="form-control rounded-3 bg-dark text-light" id="floatingPhone" placeholder="Telefone">
                                <label for="floatingPhone">Telefone</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="senha" class="form-control rounded-3 bg-dark text-light" id="floatingPassword" placeholder="Senha">
                                <label for="floatingPassword">Senha</label>
                            </div>
                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Sign up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="js/index.js"></script>
    </body>
</html>
