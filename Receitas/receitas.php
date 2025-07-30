<?php
session_start();
$usuario_logado = isset($_SESSION['idUsuario']);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Receitas</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/receitas.css">
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
                        <a class="nav-link" href="#">Receitas</a>
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
                            <form action="pesquisar.php" method="POST">
                                <div class="form-group">
                                    <label for="titulo">Título da Receita</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Digite o título da receita">
                                </div>
                                <div class="form-group">
                                    <label for="ingrediente">Ingrediente</label>
                                    <input type="text" class="form-control" id="ingrediente" name="ingrediente" placeholder="Digite o ingrediente">
                                </div>
                                <button type="submit" class="btn btn-custom">Pesquisar</button>
                            </form>
                        </div>
                        <?php if ($usuario_logado): ?>
                            <div class="card-footer text-right">
                                <button class="btn btn-custom" id="criarReceitaBtn">Criar Receita</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5 criar-receita-card">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            Criar Receita
                        </div>
                        <div class="card-body">
                            <form action="src/criarReceita.php" method="POST">
                                <div class="form-group">
                                    <label for="titulo">Título da Receita</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Digite o título da receita" required>
                                </div>
                                <div class="form-group">
                                    <label for="tempo_preparo">Tempo de Preparo (minutos)</label>
                                    <input type="number" class="form-control" id="tempo_preparo" name="tempo_preparo" placeholder="Digite o tempo de preparo em minutos" required>
                                </div>
                                <div class="form-group">
                                    <label for="categoria">Categoria</label>
                                    <select class="form-control" id="categoria" name="categoria" required>
                                        <option value="">Selecione uma categoria</option>
                                        <option value="1">Janta</option>
                                        <option value="2">Almoço</option>
                                        <option value="3">Lanche</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ingredientes">Ingredientes</label>
                                    <textarea class="form-control" id="ingredientes" name="ingredientes" rows="3" placeholder="Digite os ingredientes" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="modo_preparo">Modo de Preparo</label>
                                    <textarea class="form-control" id="modo_preparo" name="modo_preparo" rows="3" placeholder="Digite o modo de preparo" required></textarea>
                                </div>
                                <button id="formbotao" type="submit" class="btn btn-custom">Criar</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#criarReceitaBtn").click(function () {
                $(".criar-receita-card").toggle();
            });
        });
    </script>
</body>
</html>
