-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-11-2024 a las 06:02:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(255) NOT NULL,
  `id_usuario` int(255) NOT NULL,
  `id_producto` int(255) NOT NULL,
  `cantidad` int(255) NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `ID` int(255) NOT NULL,
  `nombre` varchar(40) DEFAULT NULL,
  `apellido` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) DEFAULT 'Default.png',
  `telefono` varchar(10) DEFAULT NULL,
  `direccion` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT 'default.png',
  `dni` varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `edad` date DEFAULT NULL,
  `sexo` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`ID`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `avatar`, `dni`, `edad`, `sexo`, `id_usuario`) VALUES
(2, 'Nico', 'Cardona', 'nicocardona11071@gmail.com', '3116426678', 'carrera 67 #45-2', '673ac585c3894-luca-bravo-ESkw2ayO2As-unsplash.jpg', '1568718624', '2020-06-28', 'Masculino', 12),
(3, 'Abelardo SA', 'Gaviria', 'vallecilla84@hotmail.com', '3103975253', 'carrera 75 #35-13', 'default.png', '9622362027', '1990-12-07', 'Masculino', 13),
(4, 'Juan Jo', 'Giraldo', 'soniatb31@hotmail.com', '2173247', 'carrera 68 #12-85', 'default.png', '5545345074', '1984-05-18', 'Masculino', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(255) NOT NULL,
  `id_usuario` int(255) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `estado` enum('PAGADO','PENDIENTE','RECHAZADO') DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `id_usuario`, `fecha`, `total`, `estado`) VALUES
(2, 11, '2024-11-18 17:15:02', 59000.00, 'PAGADO'),
(3, 14, '2024-11-18 17:20:48', 107000.00, 'RECHAZADO'),
(4, 11, '2024-11-18 18:07:50', 75000.00, 'PENDIENTE'),
(6, 11, '2024-11-18 18:45:55', 37000.00, 'PENDIENTE'),
(7, 12, '2024-11-19 21:45:35', 15000.00, 'PENDIENTE'),
(8, 11, '2024-11-20 18:23:02', 96000.00, 'PAGADO'),
(13, 11, '2024-11-20 18:43:11', 126900.00, 'PENDIENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id_detalle` int(255) NOT NULL,
  `id_compra` int(255) DEFAULT NULL,
  `id_producto` int(255) DEFAULT NULL,
  `cantidad` int(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `estado` enum('APROBADO','PENDIENTE','RECHAZADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_estonian_ci DEFAULT 'PENDIENTE',
  `id_proveedor` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id_detalle`, `id_compra`, `id_producto`, `cantidad`, `precio_unitario`, `estado`, `id_proveedor`) VALUES
(1, 2, 1, 1, 15000.00, 'APROBADO', 4),
(2, 2, 2, 2, 22000.00, 'APROBADO', 2),
(3, 3, 3, 1, 60000.00, 'RECHAZADO', 3),
(4, 3, 5, 1, 3000.00, 'PENDIENTE', 5),
(5, 3, 2, 2, 22000.00, 'RECHAZADO', 2),
(6, 4, 1, 1, 15000.00, 'PENDIENTE', 4),
(7, 4, 3, 1, 60000.00, 'APROBADO', 3),
(9, 6, 1, 1, 15000.00, 'PENDIENTE', 4),
(10, 6, 2, 1, 22000.00, 'APROBADO', 2),
(11, 7, 5, 1, 3000.00, 'PENDIENTE', 5),
(12, 7, 8, 1, 12000.00, 'APROBADO', 3),
(13, 8, 3, 2, 48000.00, 'APROBADO', 3),
(18, 13, 3, 1, 48000.00, 'PENDIENTE', 3),
(19, 13, 6, 1, 10900.00, 'APROBADO', 2),
(20, 13, 1, 1, 68000.00, 'PENDIENTE', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id_laboratorio` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `laboratorio`
--

INSERT INTO `laboratorio` (`id_laboratorio`, `nombre`) VALUES
(1, 'MK 2024'),
(2, 'Bayer'),
(3, 'Glackson Smi'),
(4, 'Roche'),
(11, 'American Fresly');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `id_presentacion` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`id_presentacion`, `nombre`) VALUES
(1, 'Cajas'),
(2, 'Unidades'),
(4, 'TABLETA'),
(5, 'Jarabe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `concentracion` varchar(255) DEFAULT NULL,
  `adicional` varchar(255) DEFAULT NULL,
  `precio` float NOT NULL,
  `cantidad` int(255) DEFAULT 0,
  `id_laboratorio` int(255) NOT NULL,
  `id_tip_prod` int(255) NOT NULL,
  `id_presentacion` int(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `id_proveedor` int(255) DEFAULT NULL,
  `precio_original` float DEFAULT 0,
  `tipo_descuento` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `concentracion`, `adicional`, `precio`, `cantidad`, `id_laboratorio`, `id_tip_prod`, `id_presentacion`, `avatar`, `id_proveedor`, `precio_original`, `tipo_descuento`) VALUES
(1, 'Dexametazona', '200ml', 'Caja x 12', 68000, 10, 2, 5, 5, '673941a89667a-descarga.png', 4, 80000, '15% de descuento por alto valor'),
(2, 'AMOXICILINA', '500mg', 'Caja Envase Blister Tabletas', 22000, 9, 1, 6, 4, '67104a2a1f52a-amoxicilina 500mg 50 cap.jpg', 2, 0, NULL),
(3, 'Noraver gripa', '500ml', 'Caja 50', 48000, 27, 1, 2, 1, '67104a36b9555-noraver gripa 60 cap.jpg', 3, 60000, '20% de descuento por inventario medio'),
(4, 'Aciclovir', '10ml', 'Caja Envase Blister Tabletas', 15800, 8, 4, 6, 4, '671050b6b40cd-aciclovir 800mg 10 tab.jpg', 4, 0, NULL),
(5, 'Loratadina', '10mg', 'Caja x10Tab', 3000, 9, 1, 7, 4, '671050c32f506-loratadina 10mg 10 tab.jpg', 5, 0, NULL),
(6, 'Dolex', '500mg', 'Caja x10', 10900, 18, 3, 2, 4, '671050cf4dd8d-dolex 24 tab.jpg', 2, 0, NULL),
(7, 'Acetaminofen', '150mg', 'Cajas x 12', 6400, 35, 2, 7, 2, '671050d7f391d-acetaminofen 100 tab 500mg.jpg', 4, 8000, '20% de descuento por inventario medio'),
(8, 'Advil Gripa', '800mg', 'Cajas x 20', 12000, 18, 1, 2, 2, '671050e2e4c8b-advil gripa 10 cap.jpg', 3, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `telefono` int(11) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default.png',
  `dni` varchar(45) NOT NULL,
  `edad` date DEFAULT NULL,
  `sexo` varchar(10) NOT NULL,
  `id_usuario` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre`, `apellido`, `telefono`, `correo`, `direccion`, `avatar`, `dni`, `edad`, `sexo`, `id_usuario`) VALUES
(2, 'Raul Farma distribuidora Medicamentos ', 'S.A.S', 2147483647, 'rgaviria74@gmail.com.co', 'Cra 86 No. 56-10 Apto 503 Villa de Leyva 1', '673ac5ff5fdb9-luca-bravo-ESkw2ayO2As-unsplash.jpg', '2481043223', '2004-04-12', 'Femenino', 18),
(3, 'Audifarma', 'S.A', 3453450, 'ventas@distridrogas.com', 'Cra 86 45-65', '5f069ab3c4e12-luis.jpg', '4728109085', '1997-09-21', 'Masculino', 19),
(4, 'Distridrogas', 'S.C', 3453450, 'ventas@distridrogas.com', 'Cra 86 45-65', '5f069ace64a06-raul.jpg', '6150401893', '1986-03-15', 'Masculino', 20),
(5, 'Dromayor', 'S.A', 345438986, 'ventas@distridrogas.com', 'Calle 24 Norte #13-33', '6183d8bdd870c-logo-colsubsidio.jpg', '7648048613', '2010-07-05', 'Masculino', 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_producto`
--

CREATE TABLE `tipo_producto` (
  `id_tip_prod` int(255) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_producto`
--

INSERT INTO `tipo_producto` (`id_tip_prod`, `nombre`) VALUES
(1, 'Antiviral'),
(2, 'Antigripal'),
(5, 'Antiestaminico'),
(6, 'Generico'),
(7, 'Antialergico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_us`
--

CREATE TABLE `tipo_us` (
  `id_tipo_us` int(11) NOT NULL,
  `nombre_tipo` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_us`
--

INSERT INTO `tipo_us` (`id_tipo_us`, `nombre_tipo`) VALUES
(1, 'Administrador'),
(2, 'Cliente'),
(3, 'Proveedor'),
(4, 'Invitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(255) NOT NULL,
  `nombre_us` varchar(45) NOT NULL,
  `apellidos_us` varchar(45) NOT NULL,
  `dni_us` varchar(45) NOT NULL,
  `edad_us` date DEFAULT NULL,
  `contrasena_us` varchar(45) NOT NULL,
  `telefono_us` varchar(10) DEFAULT NULL,
  `direccion_us` varchar(45) DEFAULT NULL,
  `correo_us` varchar(45) DEFAULT NULL,
  `sexo_us` varchar(10) DEFAULT NULL,
  `infoadicional_us` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default.png',
  `id_tipo_us` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_us`, `apellidos_us`, `dni_us`, `edad_us`, `contrasena_us`, `telefono_us`, `direccion_us`, `correo_us`, `sexo_us`, `infoadicional_us`, `avatar`, `id_tipo_us`) VALUES
(1, 'Raul', 'Gaviria', '18511995', '1980-05-19', '12345', '3007768933', 'Cra 86 No. 56-10 Apto 503 Villa de Leyva 1', 'raul.gaviriav@unilibre.edu.co', 'Masculino', 'Super Administrador general', '624dfabec8552-luis.jpg', 1),
(11, 'Hamilton', 'Londoño', '5528314349', '2001-01-19', '123456', '3043839561', 'carrera 44 #30-53', 'hamiltonjnata12@gmail.com', 'Masculino', 'Cliente', 'default.png', 2),
(12, 'Nico', 'Cardona', '1568718624', '2020-06-28', '123456', '3116426678', 'carrera 67 #45-2', 'nicocardona11071@gmail.com', 'Masculino', 'Cliente', 'default.png', 2),
(13, 'Abelardo SA', 'Gaviria', '9622362027', '1990-12-07', '123456', '3103975253', 'carrera 75 #35-13', 'vallecilla84@hotmail.com', 'Masculino', 'Cliente', 'default.png', 2),
(14, 'Juan Jose', 'Giraldo', '5545345074', '1984-05-18', '123456', '2173247', 'carrera 68 #12-85', 'soniatb31@hotmail.com', 'Masculino', 'Cliente', 'default.png', 2),
(18, 'Raul Farma distribuidora Medicamentos ', 'S.A.S', '2481043223', '2004-04-12', '987654321', '300776893', 'Cra 86 No. 56-10 Apto 503 Villa de Leyva 1', 'rgaviria74@gmail.com.co', 'Masculino', 'Proveedor', '667b73acbbc63-667a19ace1e74-default.png', 3),
(19, 'Audifarma', 'S.A', '4728109085', '1997-09-21', '987654321', '3453450', 'Cra 86 45-65', 'ventas@distridrogas.com', 'Masculino', 'Proveedor', '5f069ab3c4e12-luis.jpg', 3),
(20, 'Distridrogas', 'S.C', '6150401893', '1986-03-15', '987654321', '3453450', 'Cra 86 45-65', 'ventas@distridrogas.com', 'Masculino', 'Proveedor', '5f069ace64a06-raul.jpg', 3),
(21, 'Dromayor', 'S.A', '7648048613', '2010-07-05', '987654321', '345438986', 'Calle 24 Norte #13-33', 'ventas@distridrogas.com', 'Masculino', 'Proveedor', '6183d8bdd870c-logo-colsubsidio.jpg', 3),
(22, 'Maxidrogas', 'S.A.S', '3304283183', '1993-11-10', '987654321', '300776893', 'Montelibano', 'rgaviria74@gmail.com.co', 'Masculino', 'Proveedor', 'default.png', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id_laboratorio`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`id_presentacion`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_producto_laboratorio1_idx` (`id_laboratorio`),
  ADD KEY `fk_producto_tipo_producto1_idx` (`id_tip_prod`),
  ADD KEY `fk_producto_presentacion1_idx` (`id_presentacion`),
  ADD KEY `fk_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  ADD PRIMARY KEY (`id_tip_prod`);

--
-- Indices de la tabla `tipo_us`
--
ALTER TABLE `tipo_us`
  ADD PRIMARY KEY (`id_tipo_us`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_usuario_tipo_us_idx` (`id_tipo_us`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id_detalle` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id_laboratorio` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `id_presentacion` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tipo_producto`
--
ALTER TABLE `tipo_producto`
  MODIFY `id_tip_prod` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipo_us`
--
ALTER TABLE `tipo_us`
  MODIFY `id_tipo_us` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`),
  ADD CONSTRAINT `detalle_compras_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `detalle_compras_ibfk_3` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_tipo_us` FOREIGN KEY (`id_tipo_us`) REFERENCES `tipo_us` (`id_tipo_us`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
