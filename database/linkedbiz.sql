-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 21:31:42
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `linkedbiz`
--
CREATE DATABASE IF NOT EXISTS `linkedbiz` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `linkedbiz`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Apellidos` varchar(255) NOT NULL,
  `Usuario` varchar(255) NOT NULL COMMENT 'Nombre de usuario para acceder a la aplicación',
  `Contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`ID`, `Nombre`, `Apellidos`, `Usuario`, `Contraseña`) VALUES
(1, 'David', 'Redondo Lara', 'adminDavid', 'password123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `amigo`
--

CREATE TABLE `amigo` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UsuarioEmisor` int(10) UNSIGNED NOT NULL,
  `UsuarioReceptor` int(10) UNSIGNED NOT NULL,
  `Solicitud` varchar(255) NOT NULL COMMENT 'La solicitud de amistad puede ser de 3 tipos:\n- Aceptada\n- Pendiente\n- Rechazada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Publicacion` int(10) UNSIGNED NOT NULL,
  `Usuario` int(10) UNSIGNED NOT NULL,
  `Contenido` text NOT NULL,
  `Fecha` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `CIF` varchar(255) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Localidad` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Telefono` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`CIF`, `Nombre`, `Direccion`, `Localidad`, `Email`, `Telefono`) VALUES
('B02586451', 'Yotta Desarrollos Tecnologicos S.L.', 'Avenida de España, 28', 'Albacete', 'info@yottadesarrollos.com', 967257053),
('B02590859', 'Soluciones Informaticas', NULL, NULL, NULL, NULL),
('B82387770', 'NTT Data Spain', 'Camino Fuente de la Mora, 1', 'Madrid', 'infoweb@nttdata.com', 917490000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Tipo` varchar(255) NOT NULL,
  `UsuarioEmisor` int(10) UNSIGNED NOT NULL,
  `UsuarioReceptor` int(10) UNSIGNED NOT NULL,
  `Mensaje` text NOT NULL,
  `Fecha` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicacion`
--

CREATE TABLE `publicacion` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Usuario` int(10) UNSIGNED NOT NULL,
  `Contenido` text NOT NULL,
  `Imagen` varchar(255) DEFAULT NULL,
  `Fecha` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reaccion`
--

CREATE TABLE `reaccion` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Publicacion` int(10) UNSIGNED NOT NULL,
  `Usuario` int(10) UNSIGNED NOT NULL,
  `Tipo` varchar(255) NOT NULL,
  `Fecha` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Apellidos` varchar(255) NOT NULL,
  `Biografia` varchar(255) NOT NULL,
  `Usuario` varchar(255) NOT NULL COMMENT 'Nombre de usuario',
  `Contraseña` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FechaNacimiento` date NOT NULL,
  `Fotografia` varchar(255) DEFAULT NULL,
  `Empresa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID`, `Nombre`, `Apellidos`, `Biografia`, `Usuario`, `Contraseña`, `Email`, `FechaNacimiento`, `Fotografia`, `Empresa`) VALUES
(1, 'Carlos', 'Martínez', 'Ingeniero de software especializado en IA', 'carlosmartinez', 'password123', 'carlos.martinez@solucionesinformaticas.com', '1996-03-10', 'fotoEjemploChico.jpg', 'B02590859'),
(2, 'Jose ', 'García', 'Project manager en desarrollo de software.', 'josegarcia', 'password123', 'jose.garcia@yottadesarrollos.com', '1999-04-28', NULL, 'B02586451'),
(3, 'Marta', 'Jiménez', 'Diseñadora UX/UI con pasión por la innovación.', 'martajimenez', 'password123', 'marta.jimenez@yottadesarrollos.com', '1994-02-02', NULL, 'B02586451'),
(4, 'Pedro', 'Ramírez', 'Consultor en transformación digital y big data.', 'pedroramirez', 'password123', 'pedro.ramirez@nttdata.com', '1984-06-08', NULL, 'B82387770'),
(5, 'Elena', 'Torres', 'Soy otro usuario de prueba', 'elenatorres', 'password123', 'elena.torres@nttdata.com', '1998-01-12', 'fotoEjemploChica.jpg', 'B82387770');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `amigo`
--
ALTER TABLE `amigo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `amigo_usuarioreceptor_foreign` (`UsuarioReceptor`),
  ADD KEY `amigo_usuarioemisor_foreign` (`UsuarioEmisor`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `comentario_publicacion_foreign` (`Publicacion`),
  ADD KEY `comentario_usuario_foreign` (`Usuario`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`CIF`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `notificacion_usuarioreceptor_foreign` (`UsuarioReceptor`),
  ADD KEY `notificacion_usuarioemisor_foreign` (`UsuarioEmisor`);

--
-- Indices de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `publicacion_usuario_foreign` (`Usuario`);

--
-- Indices de la tabla `reaccion`
--
ALTER TABLE `reaccion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `reaccion_usuario_foreign` (`Usuario`),
  ADD KEY `reaccion_publicacion_foreign` (`Publicacion`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `usuario_empresa_foreign` (`Empresa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `amigo`
--
ALTER TABLE `amigo`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reaccion`
--
ALTER TABLE `reaccion`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `amigo`
--
ALTER TABLE `amigo`
  ADD CONSTRAINT `amigo_usuarioemisor_foreign` FOREIGN KEY (`UsuarioEmisor`) REFERENCES `usuario` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `amigo_usuarioreceptor_foreign` FOREIGN KEY (`UsuarioReceptor`) REFERENCES `usuario` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_publicacion_foreign` FOREIGN KEY (`Publicacion`) REFERENCES `publicacion` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentario_usuario_foreign` FOREIGN KEY (`Usuario`) REFERENCES `usuario` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_usuarioemisor_foreign` FOREIGN KEY (`UsuarioEmisor`) REFERENCES `usuario` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notificacion_usuarioreceptor_foreign` FOREIGN KEY (`UsuarioReceptor`) REFERENCES `usuario` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD CONSTRAINT `publicacion_usuario_foreign` FOREIGN KEY (`Usuario`) REFERENCES `usuario` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reaccion`
--
ALTER TABLE `reaccion`
  ADD CONSTRAINT `reaccion_publicacion_foreign` FOREIGN KEY (`Publicacion`) REFERENCES `publicacion` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reaccion_usuario_foreign` FOREIGN KEY (`Usuario`) REFERENCES `usuario` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_empresa_foreign` FOREIGN KEY (`Empresa`) REFERENCES `empresa` (`CIF`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
