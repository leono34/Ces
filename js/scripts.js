// // Función para redondear a dos decimales al perder el foco en el campo Precio
function dosDecimales(input) {
    if (input.value) {
        const value = parseFloat(input.value).toFixed(2);
        input.value = isNaN(value) ? '' : value; // Vacía el campo si el valor no es un número
    }
}

// Función para redondear 0 decimales al perder el foco
function sinDecimal(input) {
    if (input.value) {
        const value = parseFloat(input.value).toFixed(0);
        input.value = isNaN(value) ? '' : value; // Vacía el campo si el valor no es un número
    }
}