<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/adm.css">
    
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-card">
                    <div class="card-header">
                        Login de Administrador
                    </div>
                    <form method="POST" action="src/loginAdm.php">
                        <div class="form-group">
                            <label for="username">Nome de Adm</label>
                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite seu nome de Adm">
                        </div>
                        <div class="form-group">
                            <label for="password">Chave</label>
                            <input type="password" class="form-control" name="chave" id="chave" placeholder="Chave">
                        </div>
                        <button type="submit" class="btn btn-custom btn-block">Entrar</button>
                    </form>
                </div>
                <a href="index.php" class="btn btn-secondary btn-block back-btn">Voltar para o Início</a>
            </div>
        </div>
    </div>

</body>

</html>
