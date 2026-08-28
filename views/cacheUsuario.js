const CACHE_KEY = 'viblio_usuario';

function guardarUsuarioEnCache(usuario) {
    const datos = {
        id: usuario.id,
        nombre: usuario.nombre,
        guardadoEn: Date.now()
    };
    localStorage.setItem(CACHE_KEY, JSON.stringify(datos));
}

function obtenerUsuarioDeCache() {
    const raw = localStorage.getItem(CACHE_KEY);
    if (!raw) return null;

    try {
        return JSON.parse(raw);
    } catch (e) {
        console.error('Cache de usuario corrupta, se limpia', e);
        localStorage.removeItem(CACHE_KEY);
        return null;
    }
}
    
function limpiarUsuarioDeCache() {
        localStorage.removeItem(CACHE_KEY);
}

function mostrarNombreUsuario(nombre) {
        const span = document.getElementById('user-name');
        if (span) span.textContent = nombre;
}

document.addEventListener('DOMContentLoaded', function () {
    const actual = window.usuarioLogueado;
    if (!actual) return;

    const cacheado = obtenerUsuarioDeCache();

    if (cacheado && cacheado.id === actual.id) {
        mostrarNombreUsuario(cacheado.nombre);
    } else {
        guardarUsuarioEnCache(actual);
        mostrarNombreUsuario(actual.nombre);
    }
});