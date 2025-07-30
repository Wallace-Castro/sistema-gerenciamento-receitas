function removerFavorito(id) {
    const favorito = document.getElementById('favorito-' + id);
    if (favorito) {
        favorito.remove();
    }
}
function adicionarFavorito(id, nome) {
    const favoritos = document.getElementById('favoritos');
    const favorito = document.createElement('div');
    favorito.innerText = nome;
    favorito.draggable = true;
    favorito.id = 'favorito-' + id;
    favorito.classList.add('favorito');
    favoritos.appendChild(favorito);

    const btnRemover = document.createElement('button');
    btnRemover.className = 'btn-remover';
    btnRemover.setAttribute('onclick', 'removerFavorito(' + id + ')');
    favorito.appendChild(btnRemover);

    favorito.addEventListener('click', () => {
        const receita = document.getElementById('receita-' + id);
        if (receita) {
            receita.scrollIntoView({behavior: 'smooth', block: 'start'});
        }
    });
}

function like(id) {
    enviarReacao(id, 'like');
}

function dislike(id) {
    enviarReacao(id, 'dislike');
}

function enviarReacao(receitaId, reacao) {
    $.ajax({
        type: 'POST',
        url: '../src/like_dislike.php',
        data: {
            receitaId: receitaId,
            reacao: reacao
        },
        success: function (response) {
        }
    });
}



