-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 11-12-2024 a las 22:04:14
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



DROP TABLE IF EXISTS `atencion_cliente`;
CREATE TABLE IF NOT EXISTS `atencion_cliente` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ID_usuario` int NOT NULL,
  `id_cliente` int NOT NULL,
  `ID_tipo_servicio` int NOT NULL,
  `Codigo_Operacion` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `ID_tipo_servicio` (`ID_tipo_servicio`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `atencion_cliente`
--

INSERT INTO `atencion_cliente` (`ID`, `ID_usuario`, `id_cliente`, `ID_tipo_servicio`, `Codigo_Operacion`, `fecha_creacion`, `estado`) VALUES
(49, 44, 31, 2, '41415', '2024-11-21 00:00:00', 1),
(50, 44, 30, 1, '41307', '2024-11-21 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

DROP TABLE IF EXISTS `cargo`;
CREATE TABLE IF NOT EXISTS `cargo` (
  `ID_cargo` int NOT NULL AUTO_INCREMENT,
  `Nom_cargo` varchar(10) NOT NULL,
  `Estado` int DEFAULT '1',
  PRIMARY KEY (`ID_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`ID_cargo`, `Nom_cargo`, `Estado`) VALUES
(1, 'Gerente', 1),
(24, 'Subjerente', 1),
(25, 'Secretaria', 1),
(26, 'Tesoreria', 1),
(27, 'Tecnico', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `ID_cliente` int NOT NULL AUTO_INCREMENT,
  `Dni` varchar(10) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido_paterno` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Apellido_materno` varchar(250) NOT NULL,
  `Dirección` varchar(100) NOT NULL,
  `Celular` varchar(11) NOT NULL,
  `Correo_Electronico` varchar(150) DEFAULT NULL,
  `Estado` int DEFAULT '1',
  PRIMARY KEY (`ID_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ID_cliente`, `Dni`, `Nombre`, `Apellido_paterno`, `Apellido_materno`, `Dirección`, `Celular`, `Correo_Electronico`, `Estado`) VALUES
(30, '98765432', 'uno', 'app', 'app cliente uno', 'hajskdf', '123456789', 'clienteuno@gmail.com', 1),
(31, '72183781', 'dos', 'cliente dos', 'cliente dos', 'CA INGENIERIA S/N MZ B LT 5 PIS 1', '987654321', '41@gmail.com', 1),
(32, '12345677', 'Dionel', 'Rodriguez', 'Fernandez', 'CA INGENIERIA S/N MZ B LT 5 PIS 1', '987654321', 'royner41@gmail.com', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cliente_tecnico`
--

DROP TABLE IF EXISTS `detalle_cliente_tecnico`;
CREATE TABLE IF NOT EXISTS `detalle_cliente_tecnico` (
  `Id_det_cliente_tecnico` int NOT NULL AUTO_INCREMENT,
  `ID_tecnico` int NOT NULL,
  `ID_tipo_servicio` int DEFAULT NULL,
  `ID_usuario` int NOT NULL,
  `Fecha_atencion` date NOT NULL,
  `Observacion` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Estado` int DEFAULT '0',
  `ID_atencion_cliente` int DEFAULT NULL,
  PRIMARY KEY (`Id_det_cliente_tecnico`),
  KEY `detalle_cliente_tecnico_ibfk_2` (`ID_tecnico`),
  KEY `fk_tipo_servicio_detalle_cliente_tecnico` (`ID_tipo_servicio`),
  KEY `ID_usuario` (`ID_usuario`),
  KEY `ID_antencion_cliente` (`ID_atencion_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_cliente_tecnico`
--

INSERT INTO `detalle_cliente_tecnico` (`Id_det_cliente_tecnico`, `ID_tecnico`, `ID_tipo_servicio`, `ID_usuario`, `Fecha_atencion`, `Observacion`, `Estado`, `ID_atencion_cliente`) VALUES
(22, 44, 2, 44, '2024-11-21', 'asdfasdf', 1, 49),
(23, 44, 1, 44, '2024-11-21', 'asdf', 2, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_producto_proveedor`
--

DROP TABLE IF EXISTS `detalle_producto_proveedor`;
CREATE TABLE IF NOT EXISTS `detalle_producto_proveedor` (
  `Id_det_producto_proveedor` int NOT NULL AUTO_INCREMENT,
  `ID_proveedor` int NOT NULL,
  `ID_producto` int DEFAULT NULL,
  `ID_usuario` int NOT NULL,
  `Fecha_abastecimiento` date NOT NULL,
  `cantidad` int DEFAULT NULL,
  `Observación` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_det_producto_proveedor`),
  KEY `ID_proveedor` (`ID_proveedor`),
  KEY `ID_producto` (`ID_producto`),
  KEY `ID_usuario` (`ID_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_producto_proveedor`
--

INSERT INTO `detalle_producto_proveedor` (`Id_det_producto_proveedor`, `ID_proveedor`, `ID_producto`, `ID_usuario`, `Fecha_abastecimiento`, `cantidad`, `Observación`, `Estado`) VALUES
(45, 9, 52, 44, '2024-11-20', 2, 'asdasd', 'Activo'),
(46, 10, 43, 44, '2024-11-20', 1, 'asd', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_tecnico_producto`
--

DROP TABLE IF EXISTS `detalle_tecnico_producto`;
CREATE TABLE IF NOT EXISTS `detalle_tecnico_producto` (
  `Id_det_tecnico_producto` int NOT NULL AUTO_INCREMENT,
  `ID_tecnico` int DEFAULT NULL,
  `ID_producto` int DEFAULT NULL,
  `ID_usuario` int NOT NULL,
  `Fecha_retiro` date NOT NULL,
  `cantidad` int NOT NULL,
  `Observación` varchar(250) DEFAULT NULL,
  `Estado` int DEFAULT '1',
  `id_detall_tecnico_cliente` int DEFAULT NULL,
  PRIMARY KEY (`Id_det_tecnico_producto`),
  KEY `ID_tecnico` (`ID_tecnico`),
  KEY `ID_producto` (`ID_producto`),
  KEY `ID_usuario` (`ID_usuario`),
  KEY `id_detall_tecnico_cliente` (`id_detall_tecnico_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_tecnico_producto`
--

INSERT INTO `detalle_tecnico_producto` (`Id_det_tecnico_producto`, `ID_tecnico`, `ID_producto`, `ID_usuario`, `Fecha_retiro`, `cantidad`, `Observación`, `Estado`, `id_detall_tecnico_cliente`) VALUES
(49, 44, 52, 44, '2024-11-21', 2, 'asdfasdf', 1, 22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_atencion_cliente`
--

DROP TABLE IF EXISTS `estado_atencion_cliente`;
CREATE TABLE IF NOT EXISTS `estado_atencion_cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estado_atencion_cliente`
--

INSERT INTO `estado_atencion_cliente` (`id`, `nombre`) VALUES
(1, 'Pedido Recibido'),
(2, 'Pedido en proceso'),
(3, 'Pedido completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_atencion_cliente`
--

DROP TABLE IF EXISTS `historial_atencion_cliente`;
CREATE TABLE IF NOT EXISTS `historial_atencion_cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_atencion_cliente` int DEFAULT NULL,
  `id_estado_atencion_cliente` int DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `accion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Registrado',
  `detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id`),
  KEY `id_atencion_cliente` (`id_atencion_cliente`),
  KEY `id_estado_atencion_cliente` (`id_estado_atencion_cliente`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `historial_atencion_cliente`
--

INSERT INTO `historial_atencion_cliente` (`id`, `id_usuario`, `id_atencion_cliente`, `id_estado_atencion_cliente`, `fecha`, `accion`, `detalle`) VALUES
(48, 44, 49, 1, '2024-11-21 05:04:27', 'Creación', 'Creación de la atención al cliente'),
(49, 44, 49, 1, '2024-11-21 05:04:00', 'asd', 'asdfs'),
(50, 44, 49, 1, '2024-11-21 05:06:00', 'cambio de tecnico', 'asdfsd'),
(51, 44, 50, 1, '2024-11-21 05:09:38', 'Creación', 'Creación de la atención al cliente'),
(52, 44, 49, 1, '2024-11-21 06:25:00', 'asdf', 'asdf'),
(53, 44, 49, 1, '2024-11-21 07:14:00', 'asd', 'fasdf'),
(54, 44, 49, 1, '2024-11-21 07:19:00', 'cambio de tecnico', 'aasdf'),
(58, 44, 50, 1, '2024-12-11 01:20:00', 'asdf', 'asdf'),
(59, 44, 50, 1, '2024-12-11 01:20:00', 'asdf', 'asdf'),
(60, 44, 50, 1, '2024-12-11 01:22:00', 'asdf', 'asdf'),
(61, 44, 50, 1, '2024-12-11 01:24:00', 'asdf', 'asdf'),
(62, 44, 50, 1, '2024-12-11 01:30:00', 'asd', 'asdf'),
(63, 44, 50, 1, '2024-12-11 01:40:00', 'asdf', 'asdf'),
(64, 44, 50, 1, '2024-12-11 01:45:00', 'asdf', 'asdf'),
(65, 44, 50, 1, '2024-12-11 02:04:00', 'asdf', 'asdf'),
(66, 44, 50, NULL, '2024-12-11 02:13:00', 'aa', 'asdf'),
(67, 44, 50, 1, '2024-12-11 02:17:00', 'asdf', 'asdf'),
(68, 44, 50, 1, '2024-12-11 02:19:00', 'asd', 'asdf'),
(69, 44, 50, 1, '2024-12-11 02:23:00', 'asdf', 'asdf'),
(70, 44, 50, 1, '2024-12-11 02:40:00', 'sdfg', 'sdfg'),
(71, 44, 50, 1, '2024-12-11 02:55:00', 'asdf', 'asdf'),
(72, 44, 50, 1, '2024-12-11 02:56:00', 'asdf', 'asdf'),
(73, 44, 50, 1, '2024-12-11 02:56:00', 'asdf', 'asdf'),
(74, 44, 50, 1, '2024-12-11 02:56:00', 'asdf', 'asdf'),
(75, 44, 50, 1, '2024-12-11 02:56:00', 'asdf', 'asdf'),
(76, 44, 50, 1, '2024-12-11 02:58:00', 'asdf', 'asdf'),
(77, 44, 50, 1, '2024-12-11 03:00:00', 'asdf', 'asdf'),
(78, 44, 50, 1, '2024-12-11 03:00:00', 'asdf', 'asdf'),
(79, 44, 50, 1, '2024-12-11 03:01:00', 'asdf', 'asdf'),
(80, 44, 49, 1, '2024-12-11 03:01:00', 'asdf', 'asdf'),
(81, 44, 49, 1, '2024-12-11 03:01:00', 'asdf', 'asdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

DROP TABLE IF EXISTS `personal`;
CREATE TABLE IF NOT EXISTS `personal` (
  `ID_personal` int NOT NULL AUTO_INCREMENT,
  `Dni` varchar(10) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido_paterno` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Apellido_materno` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Celular` varchar(11) NOT NULL,
  `Direccion` varchar(250) NOT NULL,
  `ID_cargo` int DEFAULT NULL,
  `Estado` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_personal`),
  KEY `fk_cargo_personal` (`ID_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`ID_personal`, `Dni`, `Nombre`, `Apellido_paterno`, `Apellido_materno`, `Celular`, `Direccion`, `ID_cargo`, `Estado`) VALUES
(9, '02970312', 'Daniel', 'Torres', 'Leon', '950131020', 'av l', 1, '1'),
(36, '72183781', 'Juan', 'Davila', 'Perez', '969547011', 'Urbnizacionlas Brisas', 1, '1'),
(37, '72193784', 'Lurdes', 'Secre', 'Secretaria', '987654321', 'urb las montañas', 25, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `id_tipo_producto` int DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` int DEFAULT '1',
  PRIMARY KEY (`id_producto`),
  KEY `id_tipo_producto` (`id_tipo_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `id_tipo_producto`, `precio`, `fecha_registro`, `estado`) VALUES
(43, 'Bobina', 'Cable 5km', 1, 1200.00, '2024-11-18 03:43:43', 1),
(52, 'Adatadores', '', 37, 100.00, '2024-11-19 05:46:41', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE IF NOT EXISTS `proveedor` (
  `ID_proveedor` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(10) NOT NULL,
  `Dirección` varchar(100) NOT NULL,
  `Teléfono` varchar(100) NOT NULL,
  `Estado` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT '1',
  PRIMARY KEY (`ID_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`ID_proveedor`, `Nombre`, `Dirección`, `Teléfono`, `Estado`) VALUES
(9, 'Dioxon', 'Centro de Lima', '920123147', '1'),
(10, 'Wurfel', 'Calle las Begonias 402', '952786354', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnico`
--
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `ID_tipousuario` int NOT NULL AUTO_INCREMENT,
  `Nombre_tipousuario` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1',
  PRIMARY KEY (`ID_tipousuario`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tecnico`;
CREATE TABLE IF NOT EXISTS `tecnico` (
  `ID_tecnico` int NOT NULL AUTO_INCREMENT,
  `id_personal` int NOT NULL,
  `ID_usuario` int NOT NULL,
  `codigo` varchar(250) DEFAULT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL,
  PRIMARY KEY (`ID_tecnico`),
  KEY `id_personal` (`id_personal`),
  KEY `ID_usuario` (`ID_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tecnico`
--

INSERT INTO `tecnico` (`ID_tecnico`, `id_personal`, `ID_usuario`, `codigo`, `estado`, `fecha_creacion`) VALUES
(43, 36, 44, '547218378144', 1, '2024-11-20 05:00:00'),
(44, 9, 44, '430297031232', 1, '2024-11-21 05:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

DROP TABLE IF EXISTS `tipo_producto`;
CREATE TABLE IF NOT EXISTS `tipo_producto` (
  `ID_tipo_producto` int NOT NULL AUTO_INCREMENT,
  `Nom_producto` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Estado` int DEFAULT '1',
  PRIMARY KEY (`ID_tipo_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`ID_tipo_producto`, `Nom_producto`, `Estado`) VALUES
(1, 'CATV', 1),
(37, 'Fibra optica', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_servicio`
--

DROP TABLE IF EXISTS `tipo_servicio`;
CREATE TABLE IF NOT EXISTS `tipo_servicio` (
  `ID_tipo_servicio` int NOT NULL AUTO_INCREMENT,
  `Nom_servicio` varchar(10) NOT NULL,
  `Estado` int DEFAULT '1',
  PRIMARY KEY (`ID_tipo_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_servicio`
--

INSERT INTO `tipo_servicio` (`ID_tipo_servicio`, `Nom_servicio`, `Estado`) VALUES
(1, 'Reparacoin', 1),
(2, 'Instalacio', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `ID_tipousuario` int NOT NULL AUTO_INCREMENT,
  `Nombre_tipousuario` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1',
  PRIMARY KEY (`ID_tipousuario`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`ID_tipousuario`, `Nombre_tipousuario`, `Estado`) VALUES
(5, 'superadministrador', '1'),
(13, 'Administrador', '1'),
(14, 'Secretaria', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `ID_usuario` int NOT NULL AUTO_INCREMENT,
  `id_personal` int DEFAULT NULL,
  `Nombre_usuario` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Correo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ID_tipousuario` int DEFAULT NULL,
  `Estado` int DEFAULT '1',
  `confirmado` int NOT NULL DEFAULT '0',
  `codigo` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `token` int DEFAULT NULL,
  `fecha` timestamp NOT NULL,
  PRIMARY KEY (`ID_usuario`),
  KEY `ID_tipousuario` (`ID_tipousuario`),
  KEY `id_Personal` (`id_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_usuario`, `id_personal`, `Nombre_usuario`, `Correo`, `password`, `ID_tipousuario`, `Estado`, `confirmado`, `codigo`, `token`, `fecha`) VALUES
(44, 9, 'superadministrador', 'royner41@gmail.com', '123123', 5, 1, 1, NULL, NULL, '2024-11-16 08:04:07'),
(48, 37, 'Lurdes', NULL, '12345678', 14, 1, 0, NULL, NULL, '0000-00-00 00:00:00');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `atencion_cliente`
--
ALTER TABLE `atencion_cliente`
  ADD CONSTRAINT `atencion_cliente_ibfk_2` FOREIGN KEY (`ID_tipo_servicio`) REFERENCES `tipo_servicio` (`ID_tipo_servicio`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `atencion_cliente_ibfk_4` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`ID_cliente`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `detalle_cliente_tecnico`
--
ALTER TABLE `detalle_cliente_tecnico`
  ADD CONSTRAINT `detalle_cliente_tecnico_ibfk_2` FOREIGN KEY (`ID_tecnico`) REFERENCES `tecnico` (`ID_tecnico`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_cliente_tecnico_ibfk_3` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_cliente_tecnico_ibfk_4` FOREIGN KEY (`ID_tipo_servicio`) REFERENCES `tipo_servicio` (`ID_tipo_servicio`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_cliente_tecnico_ibfk_7` FOREIGN KEY (`ID_atencion_cliente`) REFERENCES `atencion_cliente` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `detalle_producto_proveedor`
--
ALTER TABLE `detalle_producto_proveedor`
  ADD CONSTRAINT `detalle_producto_proveedor_ibfk_2` FOREIGN KEY (`ID_producto`) REFERENCES `productos` (`id_producto`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_producto_proveedor_ibfk_3` FOREIGN KEY (`ID_proveedor`) REFERENCES `proveedor` (`ID_proveedor`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_producto_proveedor_ibfk_4` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `detalle_tecnico_producto`
--
ALTER TABLE `detalle_tecnico_producto`
  ADD CONSTRAINT `detalle_tecnico_producto_ibfk_2` FOREIGN KEY (`ID_producto`) REFERENCES `productos` (`id_producto`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_tecnico_producto_ibfk_3` FOREIGN KEY (`ID_tecnico`) REFERENCES `tecnico` (`ID_tecnico`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_tecnico_producto_ibfk_4` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `detalle_tecnico_producto_ibfk_5` FOREIGN KEY (`id_detall_tecnico_cliente`) REFERENCES `detalle_cliente_tecnico` (`Id_det_cliente_tecnico`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `historial_atencion_cliente`
--
ALTER TABLE `historial_atencion_cliente`
  ADD CONSTRAINT `historial_atencion_cliente_ibfk_1` FOREIGN KEY (`id_atencion_cliente`) REFERENCES `atencion_cliente` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `historial_atencion_cliente_ibfk_2` FOREIGN KEY (`id_estado_atencion_cliente`) REFERENCES `estado_atencion_cliente` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `historial_atencion_cliente_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`ID_cargo`) REFERENCES `cargo` (`ID_cargo`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_tipo_producto`) REFERENCES `tipo_producto` (`ID_tipo_producto`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `tecnico`
--
ALTER TABLE `tecnico`
  ADD CONSTRAINT `tecnico_ibfk_2` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`ID_personal`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tecnico_ibfk_3` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`ID_tipousuario`) REFERENCES `tipo_usuario` (`ID_tipousuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`ID_personal`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
