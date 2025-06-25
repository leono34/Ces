-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2025 a las 04:06:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `incidencias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casos_sin_cliente`
--

CREATE TABLE `casos_sin_cliente` (
  `id_caso` int(11) NOT NULL,
  `codigo_pedido` varchar(20) DEFAULT NULL,
  `comentarios` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos_sin_cliente`
--

INSERT INTO `casos_sin_cliente` (`id_caso`, `codigo_pedido`, `comentarios`, `fecha_registro`) VALUES
(1, 'PED-00123', 'Se registró el caso, pero aún no se tiene el DNI del cliente.', '2025-04-23 22:08:45'),
(2, 'PED-00456', 'Producto entregado, pero el cliente no figura en la base.', '2025-04-23 22:08:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casos_sin_pedido`
--

CREATE TABLE `casos_sin_pedido` (
  `id_caso` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_reporte` date NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casos_sin_pedido`
--

INSERT INTO `casos_sin_pedido` (`id_caso`, `descripcion`, `fecha_reporte`, `fecha_registro`) VALUES
(1, 'Cliente reporta problema técnico sin número de pedido.', '2025-04-22', '2025-04-23 22:08:45'),
(2, 'Consulta general sobre garantías, sin pedido específico.', '2025-04-23', '2025-04-23 22:08:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellido_paterno` varchar(100) DEFAULT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `dni` varchar(20) NOT NULL DEFAULT 'NOT NULL UNIQUE',
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombres`, `apellido_paterno`, `apellido_materno`, `dni`, `correo`, `telefono`, `direccion`, `id_empresa`) VALUES
(1, 'JUAN', 'PRUEB', 'PRUEB', '12345678', 'ASDF1@GMAIL.COM', '1234', 'asdf', 1),
(2, 'FELIPE', 'CASTILLO', 'CASTILLO', '48017110', 'ASDF2@GMAIL.COM', '940571894', 'CALLAO', 1),
(3, 'MARIA', 'TAVARA', 'ESCUDERO', '48017101', 'ASDF4@GMAIL.COM', '940571888', 'CALLAO', 1),
(12, 'JOSE', 'JOSE', 'JOSE', '12345', 'ASDF5@GMAIL.COM', '1234', 'asdf', 2),
(16, 'johan', 'ASDF', 'arestegui', '12345671', 'juan.perez@email.com', '1234', 'Callao Mi Peru AAHH 7 de Junio MZ N LT 9', 1),
(21, 'asasa', 'asas', 'arestegui', '76845217', 'leon@gmail.com', '968573420', 'asdf alado de la UPN', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombres`, `apellido_paterno`, `apellido_materno`, `cargo`, `email`, `telefono`) VALUES
(71444853, ' WILDHER CHRISTIAN', 'CURI ', 'QUISPE', 'OPERADOR', ' WILDHER CHRISTIAN@GMAIL.COM', '99999999999');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `nombre`) VALUES
(1, 'Plaza Vea'),
(3, 'Totus'),
(2, 'Vivanda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reclamos`
--

CREATE TABLE `estados_reclamos` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reclamos`
--

INSERT INTO `estados_reclamos` (`id_estado`, `nombre_estado`) VALUES
(1, 'INGRESADO'),
(2, 'EN REVISION'),
(3, 'ANULADO'),
(4, 'FINALIZADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias_reclamos`
--

CREATE TABLE `evidencias_reclamos` (
  `id_evidencia` int(11) NOT NULL,
  `id_reclamo` int(11) DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `tipo_archivo` varchar(50) DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencia`
--

CREATE TABLE `incidencia` (
  `id_incidencia` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `id_prioridad` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `comentarios` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `incidencia`
--

INSERT INTO `incidencia` (`id_incidencia`, `id_cliente`, `titulo`, `descripcion`, `id_prioridad`, `fecha_inicio`, `fecha_fin`, `archivo`, `id_estado`, `comentarios`) VALUES
(10, 1, 'Error de red', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-22', '1750206418_141-CARTA-CI-M2-CHOCA ROJAS.pdf', 4, ''),
(13, 2, 'prueba', 'prueba', 1, '2025-05-20', '2025-05-29', '1748573725_', 1, 'hola'),
(14, 1, 'Error de red', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-20', 'prueba.txt', 2, ''),
(15, 2, 'Error de red', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-20', 'prueba.txt', 3, ''),
(17, 2, 'Error de red', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-22', '1748274189_PLANILLA-DE-INSCRIPCION-NACIONAL-DE-RUTA-JUVENIL-2025.xlsx', 4, ''),
(18, 2, 'Error de red', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-22', '1748274170_HITSS-SST-DO-02_Reglamento Interno de SST_Rev 02.pdf', 1, ''),
(19, 1, 'Error de red', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-22', '1748274152_1748273431_1748108279_1747957684_Estructura (1) (1) (2) (1).pptx', 3, ''),
(20, 2, 'Error de red 2', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-22', '1748273465_REGLAMENTO-CN-RUTA-ELITE-2025.pdf', 2, ''),
(21, 1, 'Error de red', 'Pérdida de conectividad en la oficina.', 1, '2025-05-21', '2025-05-22', '1748274227_ListaWS.xlsx', 1, ''),
(23, 16, 'Prueba_Leo', 'Pérdida de red', 1, '2025-05-21', '2025-05-22', '1748545484_Estructura.pptx', 1, ''),
(33, 16, 'AYUDA', 'AYUDAaaaaaaaaaaa', 2, '2025-05-22', '2025-06-19', '1750207337_141_CARTA_CI_M2_CHOCA_ROJAS.pdf', 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo_reclamo`
--

CREATE TABLE `motivo_reclamo` (
  `id_motivo` int(11) NOT NULL,
  `nombre_motivo` varchar(100) NOT NULL,
  `detalle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motivo_reclamo`
--

INSERT INTO `motivo_reclamo` (`id_motivo`, `nombre_motivo`, `detalle`) VALUES
(1, 'DESPACHO', ' SIN NOVEDAD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `numero_pedido` varchar(50) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `fecha_pedido` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `numero_pedido`, `id_cliente`, `id_empresa`, `fecha_pedido`) VALUES
(1, '123456', 1, 1, '2025-04-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prioridad`
--

CREATE TABLE `prioridad` (
  `id_prioridad` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prioridad`
--

INSERT INTO `prioridad` (`id_prioridad`, `descripcion`) VALUES
(1, 'ALTA'),
(2, 'MEDIA'),
(3, 'BAJA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reclamos`
--

CREATE TABLE `reclamos` (
  `id_reclamo` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo_caso` enum('Con cliente y pedido','Sin cliente','Sin pedido') NOT NULL,
  `fecha_reclamo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reclamos`
--

INSERT INTO `reclamos` (`id_reclamo`, `id_cliente`, `id_pedido`, `descripcion`, `tipo_caso`, `fecha_reclamo`) VALUES
(1, 1, 1, 'Producto defectuoso', 'Con cliente y pedido', '2025-04-23 23:17:21'),
(2, NULL, 1, 'Producto defectuoso', 'Sin cliente', '2025-04-23 23:17:21'),
(3, 1, NULL, 'Producto defectuoso', 'Sin pedido', '2025-04-23 23:17:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id_respuesta` int(11) NOT NULL,
  `id_reclamo` int(11) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `respuesta` text NOT NULL,
  `fecha_respuesta` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id_respuesta`, `id_reclamo`, `id_empleado`, `respuesta`, `fecha_respuesta`, `fecha_cierre`) VALUES
(462341222, 71512410, 71444853, 'SIN NOVEDAD ', '2025-04-23 18:11:18', '2025-04-01 18:11:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_busqueda`
--

CREATE TABLE `tipo_busqueda` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_busqueda`
--

INSERT INTO `tipo_busqueda` (`id`, `nombre`) VALUES
(1, 'DNI'),
(2, 'Pedido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(5) NOT NULL,
  `cod_usuario` char(5) NOT NULL,
  `usuario` varchar(10) NOT NULL,
  `contraseña` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `cod_usuario`, `usuario`, `contraseña`) VALUES
(1, 'U0001', 'Cesia', '$2y$10$6QeV81Er/r6iNuhx0DIcLel2b7Md3lLRH0056bE/..339MHOwMn9K'),
(2, 'U0002', 'Bladimir', '$2y$10$UeG.ZQt2YkDJkXQtXS.9b.8McylNZfs6OveaP3wLXR/hmaFORoyxO'),
(3, 'U0003', '73364977', '$2y$10$HT1RysmUDkvBsBuacS9W0e83qjXpAAP5cAX9.KZs.7cSZU.NWbKUy');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `casos_sin_cliente`
--
ALTER TABLE `casos_sin_cliente`
  ADD PRIMARY KEY (`id_caso`);

--
-- Indices de la tabla `casos_sin_pedido`
--
ALTER TABLE `casos_sin_pedido`
  ADD PRIMARY KEY (`id_caso`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estados_reclamos`
--
ALTER TABLE `estados_reclamos`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `evidencias_reclamos`
--
ALTER TABLE `evidencias_reclamos`
  ADD PRIMARY KEY (`id_evidencia`),
  ADD KEY `id_reclamo` (`id_reclamo`);

--
-- Indices de la tabla `incidencia`
--
ALTER TABLE `incidencia`
  ADD PRIMARY KEY (`id_incidencia`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_prioridad` (`id_prioridad`),
  ADD KEY `FK_Incidencia_Estado` (`id_estado`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD UNIQUE KEY `numero_pedido` (`numero_pedido`),
  ADD KEY `id_empresa` (`id_empresa`),
  ADD KEY `fk_cliente` (`id_cliente`);

--
-- Indices de la tabla `prioridad`
--
ALTER TABLE `prioridad`
  ADD PRIMARY KEY (`id_prioridad`);

--
-- Indices de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD PRIMARY KEY (`id_reclamo`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `tipo_busqueda`
--
ALTER TABLE `tipo_busqueda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `casos_sin_cliente`
--
ALTER TABLE `casos_sin_cliente`
  MODIFY `id_caso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `casos_sin_pedido`
--
ALTER TABLE `casos_sin_pedido`
  MODIFY `id_caso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `evidencias_reclamos`
--
ALTER TABLE `evidencias_reclamos`
  MODIFY `id_evidencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `incidencia`
--
ALTER TABLE `incidencia`
  MODIFY `id_incidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prioridad`
--
ALTER TABLE `prioridad`
  MODIFY `id_prioridad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  MODIFY `id_reclamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_busqueda`
--
ALTER TABLE `tipo_busqueda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `evidencias_reclamos`
--
ALTER TABLE `evidencias_reclamos`
  ADD CONSTRAINT `evidencias_reclamos_ibfk_1` FOREIGN KEY (`id_reclamo`) REFERENCES `reclamos` (`id_reclamo`);

--
-- Filtros para la tabla `incidencia`
--
ALTER TABLE `incidencia`
  ADD CONSTRAINT `FK_Incidencia_Estado` FOREIGN KEY (`id_estado`) REFERENCES `estados_reclamos` (`id_estado`),
  ADD CONSTRAINT `incidencia_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `incidencia_ibfk_2` FOREIGN KEY (`id_prioridad`) REFERENCES `prioridad` (`id_prioridad`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id_empresa`);

--
-- Filtros para la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD CONSTRAINT `reclamos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `reclamos_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
