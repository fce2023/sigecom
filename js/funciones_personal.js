function openModal(modalId) {
    var modal = document.getElementById(modalId);
    var form = modal.querySelector('form');
    
    // Obtener los campos de entrada del formulario
    var nombreInput = form.querySelector('input[name="nombre-reg"]');
    var apellidoInput = form.querySelector('input[name="apellido-reg"]');
    var celularInput = form.querySelector('input[name="celular-reg"]');
    var dniInput = form.querySelector('input[name="dni-reg"]');
    var cargoInput = form.querySelector('select[name="cargo-reg"]');
    var estadoInput = form.querySelector('select[name="estado-reg"]');
    
    // Asignar los valores a los campos de formulario usando los atributos data-* del modal
    nombreInput.value = modal.dataset.nombrePersonal || '';
    apellidoInput.value = modal.dataset.apellidoPersonal || '';
    celularInput.value = modal.dataset.celularPersonal || '';
    dniInput.value = modal.dataset.dniPersonal || '';
    cargoInput.value = modal.dataset.cargoPersonal || '';
    estadoInput.value = modal.dataset.estadoPersonal || '';
    
    // Mostrar el modal
    modal.style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Cierra el modal si el usuario hace clic fuera de él
window.onclick = function(event) {
    var modals = document.getElementsByClassName('custom-modal');
    for (var i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
            modals[i].style.display = "none";
        }
    }
}

function openErrorModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeErrorModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Si hay un mensaje de error en la URL, abre el modal automáticamente
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        openErrorModal('errorModal'); // Usamos el nombre único 'errorModal'
    }
};

