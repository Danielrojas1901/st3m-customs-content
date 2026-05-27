document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('post');

    if (!form) {
        return;
    }

    function clearErrors() {
        document.querySelectorAll('.st3m-field-error').forEach(function (error) {
            error.remove();
        });

        document.querySelectorAll('.st3m-field-invalid').forEach(function (field) {
            field.classList.remove('st3m-field-invalid');
        });
    }

    function showError(field, message) {
        if (!field) {
            return;
        }

        field.classList.add('st3m-field-invalid');

        const error = document.createElement('p');
        error.className = 'st3m-field-error';
        error.textContent = message;

        field.insertAdjacentElement('afterend', error);
    }

    function isValidPhone(value) {
        return /^[0-9+()\s-]{7,25}$/.test(value);
    }

    function isValidUrl(value) {
        try {
            const url = new URL(value);
            return url.protocol === 'http:' || url.protocol === 'https:';
        } catch (e) {
            return false;
        }
    }

    function isValidLocationText(value, maxLength) {
        const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s.-]+$/;
        return value.length <= maxLength && regex.test(value);
    }

    function isValidGeneralText(value, maxLength) {
        const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü0-9\s.,;:()\-+#/@]+$/;
        return value.length <= maxLength && regex.test(value);
    }

    function validateSede() {
        let isValid = true;

        const ciudad = document.getElementById('st3m_ciudad');
        const estado = document.getElementById('st3m_estado');
        const direccion = document.getElementById('st3m_direccion');
        const telefono = document.getElementById('st3m_telefono');
        const email = document.getElementById('st3m_email');
        const horario = document.getElementById('st3m_horario');
        const mapaUrl = document.getElementById('st3m_mapa_url');

        if (ciudad && ciudad.value.trim() === '') {
            showError(ciudad, 'La ciudad es obligatoria.');
            isValid = false;
        } else if (ciudad && !isValidLocationText(ciudad.value.trim(), 50)) {
            showError(ciudad, 'La ciudad solo debe contener letras y máximo 50 caracteres.');
            isValid = false;
        }

        if (estado && estado.value.trim() === '') {
            showError(estado, 'El estado es obligatorio.');
            isValid = false;
        } else if (estado && !isValidLocationText(estado.value.trim(), 50)) {
            showError(estado, 'El estado solo debe contener letras y máximo 50 caracteres.');
            isValid = false;
        }

        if (direccion && direccion.value.trim() === '') {
            showError(direccion, 'La dirección es obligatoria.');
            isValid = false;
        } else if (direccion && !isValidGeneralText(direccion.value.trim(), 120)) {
            showError(direccion, 'La dirección tiene caracteres no permitidos o supera 120 caracteres.');
            isValid = false;
        }

        if (telefono && telefono.value.trim() !== '' && !isValidPhone(telefono.value.trim())) {
            showError(telefono, 'El teléfono tiene un formato inválido.');
            isValid = false;
        }

        if (email && email.value.trim() !== '' && !email.checkValidity()) {
            showError(email, 'El email tiene un formato inválido.');
            isValid = false;
        }

        if (horario && horario.value.trim() !== '' && !isValidGeneralText(horario.value.trim(), 120)) {
            showError(horario, 'El horario tiene caracteres no permitidos o supera 120 caracteres.');
            isValid = false;
        }

        if (mapaUrl && mapaUrl.value.trim() !== '' && !isValidUrl(mapaUrl.value.trim())) {
            showError(mapaUrl, 'La URL del mapa debe iniciar con http:// o https://.');
            isValid = false;
        }

        return isValid;
    }

    function validateAliado() {
        let isValid = true;

        const tipo = document.getElementById('st3m_aliado_tipo');
        const descripcion = document.getElementById('st3m_aliado_descripcion');
        const ubicacion = document.getElementById('st3m_aliado_ubicacion');
        const telefono = document.getElementById('st3m_aliado_telefono');
        const email = document.getElementById('st3m_aliado_email');
        const mostrarBoton = document.querySelector('input[name="st3m_aliado_mostrar_boton"]');
        const botonTexto = document.getElementById('st3m_aliado_boton_texto');
        const botonUrl = document.getElementById('st3m_aliado_boton_url');

        if (tipo && tipo.value.trim() === '') {
            showError(tipo, 'El tipo de aliado es obligatorio.');
            isValid = false;
        } else if (tipo && !isValidGeneralText(tipo.value.trim(), 40)) {
            showError(tipo, 'El tipo de aliado tiene caracteres no permitidos o supera 40 caracteres.');
            isValid = false;
        }

        if (descripcion && descripcion.value.trim() === '') {
            showError(descripcion, 'La descripción corta es obligatoria.');
            isValid = false;
        } else if (descripcion && !isValidGeneralText(descripcion.value.trim(), 180)) {
            showError(descripcion, 'La descripción tiene caracteres no permitidos o supera 180 caracteres.');
            isValid = false;
        }

        if (ubicacion && ubicacion.value.trim() !== '' && !isValidLocationText(ubicacion.value.trim(), 80)) {
            showError(ubicacion, 'La ubicación tiene caracteres no permitidos o supera 80 caracteres.');
            isValid = false;
        }

        if (telefono && telefono.value.trim() !== '' && !isValidPhone(telefono.value.trim())) {
            showError(telefono, 'El teléfono tiene un formato inválido.');
            isValid = false;
        }

        if (email && email.value.trim() !== '' && !email.checkValidity()) {
            showError(email, 'El email tiene un formato inválido.');
            isValid = false;
        }

        if (mostrarBoton && mostrarBoton.checked) {
            if (botonTexto && botonTexto.value.trim() === '') {
                showError(botonTexto, 'El texto del botón es obligatorio si el botón está activo.');
                isValid = false;
            } else if (botonTexto && !isValidGeneralText(botonTexto.value.trim(), 30)) {
                showError(botonTexto, 'El texto del botón supera 30 caracteres o tiene caracteres no permitidos.');
                isValid = false;
            }

            if (botonUrl && botonUrl.value.trim() === '') {
                showError(botonUrl, 'La URL del botón es obligatoria si el botón está activo.');
                isValid = false;
            } else if (botonUrl && !isValidUrl(botonUrl.value.trim())) {
                showError(botonUrl, 'La URL del botón debe iniciar con http:// o https://.');
                isValid = false;
            }
        }

        return isValid;
    }

    form.addEventListener('submit', function (event) {
        clearErrors();

        const isSede = document.getElementById('st3m_ciudad') !== null;
        const isAliado = document.getElementById('st3m_aliado_tipo') !== null;

        let isValid = true;

        if (isSede) {
            isValid = validateSede();
        }

        if (isAliado) {
            isValid = validateAliado();
        }

        if (!isValid) {
            event.preventDefault();

            const firstError = document.querySelector('.st3m-field-invalid');

            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                firstError.focus();
            }
        }
    });
});