-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-07-2024 a las 17:42:53
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rutina`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(10) NOT NULL,
  `nombre_categoria` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'Matemática'),
(2, 'Lengua y Literatura'),
(3, 'Programación Web'),
(4, 'GYM'),
(5, 'Inglés'),
(6, 'Organización de las computadoras'),
(7, 'UBA'),
(8, '¡Abrulandia!');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id_pregunta` int(10) NOT NULL,
  `id_subcategoria` int(10) NOT NULL,
  `pregunta` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id_respuesta` int(10) NOT NULL,
  `id_pregunta` int(10) NOT NULL,
  `respuesta` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `rutinaview`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `rutinaview` (
`id_tarea` int(10)
,`nombre_usuario` varchar(20)
,`nombre_categoria` varchar(50)
,`nombre_subcategoria` varchar(30)
,`turno` varchar(20)
,`estado` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategorias`
--

CREATE TABLE `subcategorias` (
  `id_subcategoria` int(10) NOT NULL,
  `id_categoria` int(10) NOT NULL,
  `nombre_subcategoria` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategorias`
--

INSERT INTO `subcategorias` (`id_subcategoria`, `id_categoria`, `nombre_subcategoria`) VALUES
(1, 1, 'Funciones'),
(2, 1, 'Fracciones'),
(3, 1, 'Matrices'),
(4, 2, 'Género literario'),
(5, 2, 'Comprensión lectora'),
(6, 3, 'HTML'),
(7, 3, 'CSS'),
(8, 3, 'JavaScript'),
(9, 3, 'Bootstrap'),
(10, 4, 'Tren inferior'),
(11, 4, 'Tren superior'),
(12, 5, 'Verb To Be'),
(13, 5, 'Verbal Times'),
(14, 6, 'Componentes'),
(15, 6, 'Sistemas Operativos'),
(16, 6, 'Máquinas Virtuales'),
(17, 7, 'Psicología'),
(18, 7, 'Derecho'),
(19, 8, 'Estudiar Freud'),
(20, 8, 'Pasear a Lari'),
(21, 8, 'Llamar a Fran sin fijarme sush'),
(22, 8, 'Cuidar a Memilittt'),
(23, 8, 'Hacer tistos'),
(24, 8, 'Crear un mundo en Minecraft'),
(25, 8, 'Eliminar un mundo en Minecraft');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id_tarea` int(10) NOT NULL,
  `id_usuario` int(10) NOT NULL,
  `id_subcategoria` int(10) NOT NULL,
  `turno` varchar(20) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id_tarea`, `id_usuario`, `id_subcategoria`, `turno`, `estado`) VALUES
(36, 36, 1, 'Mañana', 'PENDIENTE'),
(59, 47, 8, 'Noche', 'COMPLETADA'),
(60, 47, 8, 'Noche', 'COMPLETADA'),
(61, 47, 11, 'Tarde', 'COMPLETADA'),
(62, 47, 11, 'Tarde', 'PENDIENTE'),
(63, 47, 11, 'Tarde', 'PENDIENTE'),
(77, 46, 1, 'Mañana', 'PENDIENTE'),
(78, 46, 4, 'Mañana', 'PENDIENTE'),
(79, 46, 6, 'Mañana', 'PENDIENTE'),
(80, 46, 11, 'Mañana', 'PENDIENTE'),
(81, 46, 12, 'Mañana', 'PENDIENTE'),
(84, 36, 6, 'Mediodía', 'PENDIENTE'),
(85, 36, 12, 'Mañana', 'PENDIENTE'),
(86, 36, 12, 'Noche', 'PENDIENTE'),
(87, 36, 2, 'Mañana', 'PENDIENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) NOT NULL,
  `nombre_usuario` varchar(20) NOT NULL,
  `correo_usuario` varchar(50) NOT NULL,
  `EXP` int(100) DEFAULT NULL,
  `NIVEL` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `correo_usuario`, `EXP`, `NIVEL`) VALUES
(36, 'asd', 'asd@gmail.com', 0, 0),
(38, 'asd2', 'asd2@gmail.com', 0, 0),
(39, 'lolete334', 'asd672@gmail.com', 0, 0),
(40, 'lolet2e334', 'asd6722@gmail.com', 0, 0),
(45, 'Martu', 'martucellifranco15@gmail.com', 0, 0),
(46, 'Lolete', 'lolete@gmail.com', 250, 2.5),
(47, 'lolete02', 'asd12@gmail.com', 150, 1.5),
(48, 'juja', 'juja@gmail.com', 100, 1);

-- --------------------------------------------------------

--
-- Estructura para la vista `rutinaview`
--
DROP TABLE IF EXISTS `rutinaview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rutinaview`  AS SELECT `tareas`.`id_tarea` AS `id_tarea`, `usuarios`.`nombre_usuario` AS `nombre_usuario`, `categorias`.`nombre_categoria` AS `nombre_categoria`, `subcategorias`.`nombre_subcategoria` AS `nombre_subcategoria`, `tareas`.`turno` AS `turno`, `tareas`.`estado` AS `estado` FROM (((`tareas` join `usuarios`) join `subcategorias`) join `categorias`) WHERE `tareas`.`id_usuario` = `usuarios`.`id_usuario` AND `tareas`.`id_subcategoria` = `subcategorias`.`id_subcategoria` AND `subcategorias`.`id_categoria` = `categorias`.`id_categoria` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `id_subcategoria` (`id_subcategoria`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD PRIMARY KEY (`id_subcategoria`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id_tarea`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_subcategoria` (`id_subcategoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id_pregunta` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id_respuesta` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  MODIFY `id_subcategoria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id_tarea` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`id_subcategoria`) REFERENCES `subcategorias` (`id_subcategoria`);

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas` (`id_pregunta`);

--
-- Filtros para la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD CONSTRAINT `subcategorias_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`id_subcategoria`) REFERENCES `subcategorias` (`id_subcategoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
