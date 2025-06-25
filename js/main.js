
document.addEventListener('DOMContentLoaded', function () {
    
        const toggleMenu = document.querySelector('.toggle');
        const contenedor = document.querySelector('.contenedor');
        const main = document.querySelector('.main');

        toggleMenu.addEventListener('click', () => {
        contenedor.classList.toggle('oculto');
        main.classList.toggle('expandido');
        });

    const toggles = document.querySelectorAll('.toggle-submenu');
    toggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = toggle.closest('.has-submenu');
            parent.classList.toggle('active');
        });
    });

    document.querySelectorAll('.cargar-contenido').forEach(link => {
        link.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const contenido = document.getElementById('contenido-dinamico');
                    if (contenido) contenido.innerHTML = html;
                })
                .catch(err => console.error('Error al cargar el contenido:', err));
        });
    });
});

document.querySelectorAll('.navegacion ul li > a').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.navegacion ul li > a').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
    });
});

document.querySelectorAll('.submenu li a').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.submenu li a').forEach(el => el.classList.remove('selected'));
        this.classList.add('selected');
    });
});

function cambiarTexto() {
    console.log("¡Hiciste clic en el botón!");
}

function abrirModal(id) {
    document.getElementById(id).style.display = 'block';
}
function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}
window.onclick = function(event) {
    const modales = document.querySelectorAll(".modal, .modaltablagrande"); 
    modales.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
};



//Login scripts

        function mostrarContrasena() {
            var input = document.getElementById("contraseña");
            input.type = document.getElementById("ver_contraseña").checked ? "text" : "password";
        }

//Buscar cliente
function buscarCliente() {
    console.log("Evento BUSCAR activado");
    const tipo = document.getElementById('tipo_busqueda').value;
    const valor = document.getElementById('valor_busqueda').value;
    console.log("Tipo de búsqueda seleccionado:", tipo); // Paso 2: Verificando el valor del combo
    console.log("Valor ingresado:", valor); // Paso 3: Verificando input del usuario
    if (tipo === "Tipo de búsqueda" || valor.trim() === "") {
        console.log("Falta seleccionar tipo o ingresar valor");
        alert("Selecciona un tipo de búsqueda y un valor válido.");
        return;
    }

    const url = '../login/buscarcliente.php?tipo=' + tipo + '&valor=' + valor;
    console.log("URL de fetch:", url); // Paso 4: URL generada

    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log("Respuesta:", data);
            if (data.existe) {
                document.getElementById('nombres').value = data.nombres;
                document.getElementById('apellido_paterno').value = data.apellido_paterno;
                document.getElementById('apellido_materno').value = data.apellido_materno;
                document.getElementById('dni').value = data.dni;
                document.getElementById('correo').value = data.correo;
                document.getElementById('telefono').value = data.telefono;
                document.getElementById('direccion').value = data.direccion;
                document.getElementById('id_empresa').value = data.id_empresa;

                setReadonlyState(true);
            } else {
                alert('Cliente no encontrado. Llenar manualmente.');
                setReadonlyState(false);
                limpiarCampos();
            }
        })
        .catch(error => console.error('Error:', error));
}

function setReadonlyState(state) {
    ['nombres', 'apellido_paterno', 'apellido_materno', 'dni', 'correo', 'telefono', 'direccion', 'id_empresa']
        .forEach(id => document.getElementById(id).readOnly = state);
}

function limpiarCampos() {
    ['nombres', 'apellido_paterno', 'apellido_materno', 'dni', 'correo', 'telefono', 'direccion', 'id_empresa']
        .forEach(id => document.getElementById(id).value = '');
}
// ✅ Este reemplaza al anterior DOMContentLoaded
document.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'btnBuscar') {
        buscarCliente();
    }
});

