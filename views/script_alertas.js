function mdAlert(type, title, msg) {
    Swal.fire({
        icon: type,
        title: title,
        text: msg,
        confirmButtonColor: '#2e7d32',
        confirmButtonText: 'Aceptar'
    });
}

function mdConfirm(msg, onConfirm, titulo, danger) {
    Swal.fire({
        icon: 'warning',
        title: titulo || (danger ? 'Confirmar eliminación' : 'Confirmar acción'),
        text: msg,
        showCancelButton: true,
        confirmButtonColor: danger ? '#c62828' : '#2e7d32',
        cancelButtonColor: '#757575',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) onConfirm();
    });
}