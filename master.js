// Agregar funcionalidad al formulario de publicación
document.querySelector('.create-post form').addEventListener('submit', (e) => {
    e.preventDefault();
    const textarea = document.querySelector('.create-post textarea');
    const post = textarea.value;
    textarea.value = '';
    // Agregar la publicación a la lista de publicaciones
    const postHTML = `
        <div class="post">
            <img src="./../pages/resources/imag/perfil.png" alt="Perfil">
            <h3>Usuario</h3>
            <p>${post}</p>
            <button>Me gusta</button>
            <button>Comentar</button>
        </div>
    `;
    document.querySelector('.posts').innerHTML += postHTML;
});
