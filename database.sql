-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-02-2026 a las 13:08:58
-- Versión del servidor: 10.11.15-MariaDB-cll-lve
-- Versión de PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zqgikadc_administracionphp`
--
CREATE DATABASE IF NOT EXISTS `zqgikadc_administracionphp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `zqgikadc_administracionphp`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `dominio` varchar(255) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `api_secret` varchar(128) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `ultimo_uso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campos_dinamicos`
--

DROP TABLE IF EXISTS `campos_dinamicos`;
CREATE TABLE `campos_dinamicos` (
  `id` int(11) NOT NULL,
  `nombre_campo` varchar(80) NOT NULL,
  `nombre_mostrar` varchar(100) NOT NULL,
  `tipo_dato` enum('texto','numero','lista','fecha') NOT NULL DEFAULT 'texto',
  `mostrar_lista` tinyint(1) NOT NULL DEFAULT 1,
  `mostrar_filtro` tinyint(1) NOT NULL DEFAULT 1,
  `mostrar_estadisticas` tinyint(1) NOT NULL DEFAULT 0,
  `mostrar_excel` tinyint(1) NOT NULL DEFAULT 1,
  `orden` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fuente` enum('manual','wordpress') DEFAULT 'wordpress',
  `es_obligatorio` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `usuario_nombre` varchar(100) NOT NULL,
  `tipo_usuario` varchar(20) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `detalle` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `usuario_id`, `usuario_nombre`, `tipo_usuario`, `accion`, `detalle`, `ip`, `fecha`, `hora`) VALUES
(1, 1, 'Administrador Sistema', 'administrador', 'Resetear BD', 'Sistema reseteado. Tablas limpiadas: registros, logs, api_keys, campos_dinamicos, opciones_sistema', '190.236.156.23', '2026-02-24 18:23:15', '18:23:15'),
(2, 1, 'Administrador Sistema', 'administrador', 'Creó consultor', 'Consultor: Miriam Amezquita (miriam)', '190.236.156.23', '2026-02-24 18:27:36', '18:27:36'),
(3, 2, 'Miriam Amézquita Llacsa', 'consultor', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-24 18:27:46', '18:27:46'),
(4, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-24 18:27:53', '18:27:53'),
(5, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 18:28:08', '18:28:08'),
(6, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 18:28:16', '18:28:16'),
(7, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitross | Login: Habilitado', '190.236.156.23', '2026-02-24 18:28:29', '18:28:29'),
(8, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-24 18:28:36', '18:28:36'),
(9, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 18:28:51', '18:28:51'),
(10, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 18:29:00', '18:29:00'),
(11, 1, 'Administrador Sistema', 'administrador', 'Suspendió consultor', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 19:16:38', '19:16:38'),
(12, 2, 'Miriam Amezquita', 'consultor', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-24 19:16:39', '19:16:39'),
(13, 1, 'Administrador Sistema', 'administrador', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-24 19:17:23', '19:17:23'),
(14, 1, 'Administrador Sistema', 'administrador', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-24 19:17:29', '19:17:29'),
(15, 2, 'Miriam Amezquita', 'consultor', 'Login bloqueado', 'Cuenta suspendida', '190.236.156.23', '2026-02-24 19:17:34', '19:17:34'),
(16, 1, 'Administrador Sistema', 'administrador', 'Activó consultor', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 19:20:13', '19:20:13'),
(17, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-24 19:20:16', '19:20:16'),
(18, 1, 'Administrador Sistema', 'administrador', 'Suspendió consultor', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 20:04:32', '20:04:32'),
(19, 2, 'Miriam Amezquita', 'consultor', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-24 20:04:35', '20:04:35'),
(20, 1, 'Administrador Sistema', 'administrador', 'Activó consultor', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 20:05:34', '20:05:34'),
(21, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-24 20:05:42', '20:05:42'),
(22, 1, 'Administrador Sistema', 'administrador', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-24 20:49:35', '20:49:35'),
(23, 2, 'Miriam Amezquita', 'consultor', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-24 20:50:08', '20:50:08'),
(24, 1, 'Administrador Sistema', 'administrador', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-24 20:50:37', '20:50:37'),
(25, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-24 22:09:15', '22:09:15'),
(26, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:09:49', '22:09:49'),
(27, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:10:22', '22:10:22'),
(28, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:10:50', '22:10:50'),
(29, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:11:05', '22:11:05'),
(30, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:11:24', '22:11:24'),
(31, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:11:48', '22:11:48'),
(32, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:11:53', '22:11:53'),
(33, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:12:04', '22:12:04'),
(34, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:12:12', '22:12:12'),
(35, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:13:01', '22:13:01'),
(36, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:13:17', '22:13:17'),
(37, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:13:46', '22:13:46'),
(38, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:14:03', '22:14:03'),
(39, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-24 22:14:47', '22:14:47'),
(40, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Deshabilitado', '190.236.156.23', '2026-02-24 22:16:03', '22:16:03'),
(41, 2, 'Miriam Amezquita', 'consultor', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-24 22:16:05', '22:16:05'),
(42, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-24 22:16:17', '22:16:17'),
(43, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-24 22:16:19', '22:16:19'),
(44, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitrossss | Login: Habilitado', '190.236.156.23', '2026-02-24 23:00:57', '23:00:57'),
(45, 1, 'Administrador Sistema', 'administrador', 'Suspendió consultor', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 23:02:12', '23:02:12'),
(46, 1, 'Administrador Sistema', 'administrador', 'Activó consultor #2', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 23:02:31', '23:02:31'),
(47, 1, 'Administrador Sistema', 'administrador', 'Suspendió consultor #2', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 23:02:37', '23:02:37'),
(48, 1, 'Administrador Sistema', 'administrador', 'Activó consultor', 'Consultor: Miriam Amezquita', '190.236.156.23', '2026-02-24 23:02:50', '23:02:50'),
(49, 1, 'Administrador Sistema', 'administrador', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-25 10:20:30', '10:20:30'),
(50, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-25 10:21:29', '10:21:29'),
(51, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros1 | Login: Habilitado', '190.236.156.23', '2026-02-25 10:21:36', '10:21:36'),
(52, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-25 10:21:47', '10:21:47'),
(53, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros1 | Login: Deshabilitado', '190.236.156.23', '2026-02-25 10:50:13', '10:50:13'),
(54, 2, 'Miriam Amezquita', 'consultor', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-25 10:50:16', '10:50:16'),
(55, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros1 | Login: Deshabilitado', '190.236.156.23', '2026-02-25 10:50:31', '10:50:31'),
(56, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros1 | Login: Habilitado', '190.236.156.23', '2026-02-25 10:52:55', '10:52:55'),
(57, 2, 'Miriam Amezquita', 'consultor', 'Login fallido', 'Contraseña incorrecta', '190.236.156.23', '2026-02-25 10:54:15', '10:54:15'),
(58, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-25 10:54:32', '10:54:32'),
(59, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 10:56:39', '10:56:39'),
(60, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 10:56:47', '10:56:47'),
(61, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 10:57:26', '10:57:26'),
(62, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 10:57:38', '10:57:38'),
(63, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 10:58:29', '10:58:29'),
(64, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 10:59:36', '10:59:36'),
(65, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:00:25', '11:00:25'),
(66, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:00:54', '11:00:54'),
(67, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:02:48', '11:02:48'),
(68, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:03:15', '11:03:15'),
(69, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:04:01', '11:04:01'),
(70, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:04:44', '11:04:44'),
(71, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:04:58', '11:04:58'),
(72, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:05:41', '11:05:41'),
(73, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:05:54', '11:05:54'),
(74, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:06:36', '11:06:36'),
(75, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 11:15:32', '11:15:32'),
(76, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros14 | Login: Habilitado', '190.236.156.23', '2026-02-25 12:17:57', '12:17:57'),
(77, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros14 | Login: Habilitado', '190.236.156.23', '2026-02-25 12:18:42', '12:18:42'),
(78, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-25 12:19:17', '12:19:17'),
(79, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitrosss | Login: Habilitado', '190.236.156.23', '2026-02-25 12:28:23', '12:28:23'),
(80, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-25 12:29:10', '12:29:10'),
(81, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitrosssss | Login: Habilitado', '190.236.156.23', '2026-02-25 12:30:05', '12:30:05'),
(82, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-25 12:31:20', '12:31:20'),
(83, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitrosssssss | Login: Habilitado', '190.236.156.23', '2026-02-25 12:33:47', '12:33:47'),
(84, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-25 12:33:59', '12:33:59'),
(85, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Deshabilitado', '190.236.156.23', '2026-02-25 12:34:04', '12:34:04'),
(86, 2, 'Miriam Amezquita', 'consultor', 'Cierre de sesión', 'Salió del sistema', '190.236.156.23', '2026-02-25 12:34:07', '12:34:07'),
(87, 1, 'Administrador Sistema', 'administrador', 'Editó opciones globales', 'Nombre: Escuela Internacional de Psicología | Sistema de Regitros | Login: Habilitado', '190.236.156.23', '2026-02-25 12:34:22', '12:34:22'),
(88, 2, 'Miriam Amezquita', 'consultor', 'Inicio de sesión', 'Acceso exitoso al sistema', '190.236.156.23', '2026-02-25 12:34:24', '12:34:24'),
(89, 1, 'Administrador Sistema', 'administrador', 'Editó permisos de usuario', 'Usuario: Miriam Amezquita', '190.236.156.23', '2026-02-25 12:38:10', '12:38:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapeo_campos_formulario`
--

DROP TABLE IF EXISTS `mapeo_campos_formulario`;
CREATE TABLE `mapeo_campos_formulario` (
  `id` int(11) NOT NULL,
  `formulario_id` varchar(200) NOT NULL,
  `campos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`campos`)),
  `web` varchar(255) DEFAULT NULL,
  `total_registros` int(11) NOT NULL DEFAULT 0,
  `primera_recepcion` datetime NOT NULL DEFAULT current_timestamp(),
  `ultima_recepcion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mapeo_campos_formulario`
--

INSERT INTO `mapeo_campos_formulario` (`id`, `formulario_id`, `campos`, `web`, `total_registros`, `primera_recepcion`, `ultima_recepcion`) VALUES
(1, 'Formulario Consulta General', '[\"nombre\",\"apellidos\",\"telefono\",\"correo\",\"asesor\",\"delegado\",\"curso\",\"pais\",\"ciudad\",\"moneda\",\"metodo_pago\",\"ip\",\"fecha\",\"hora\",\"categoria\",\"file_url\"]', 'https://www.psicologiaenvivo.com', 21, '2025-10-06 00:07:22', '2026-02-10 21:46:26'),
(2, 'Formulario Inscripción 2026', '[\"nombre\",\"apellidos\",\"telefono\",\"correo\",\"asesor\",\"delegado\",\"curso\",\"pais\",\"ciudad\",\"moneda\",\"metodo_pago\",\"ip\",\"fecha\",\"hora\",\"categoria\",\"file_url\"]', 'https://www.psicologiaenvivo.com', 23, '2025-08-27 23:52:17', '2026-02-18 21:00:51'),
(3, 'Formulario Matrícula 2026', '[\"nombre\",\"apellidos\",\"telefono\",\"correo\",\"asesor\",\"delegado\",\"curso\",\"pais\",\"ciudad\",\"moneda\",\"metodo_pago\",\"ip\",\"fecha\",\"hora\",\"categoria\",\"file_url\"]', 'https://www.psicologiaenvivo.com', 18, '2025-09-13 23:29:07', '2026-02-01 18:54:50'),
(4, 'Formulario Preinscripción Diplomado', '[\"nombre\",\"apellidos\",\"telefono\",\"correo\",\"asesor\",\"delegado\",\"curso\",\"pais\",\"ciudad\",\"moneda\",\"metodo_pago\",\"ip\",\"fecha\",\"hora\",\"categoria\",\"file_url\"]', 'https://cursos.psicologiaenvivo.com', 12, '2025-08-30 23:50:20', '2026-02-10 21:19:37'),
(5, 'Formulario Reserva de Vacante', '[\"nombre\",\"apellidos\",\"telefono\",\"correo\",\"asesor\",\"delegado\",\"curso\",\"pais\",\"ciudad\",\"moneda\",\"metodo_pago\",\"ip\",\"fecha\",\"hora\",\"categoria\",\"file_url\"]', 'https://diplomados.psicologiaenvivo.com', 26, '2025-10-04 23:33:27', '2026-02-18 19:14:54'),
(6, 'FORM-001', '[\"nombre\",\"apellidos\",\"telefono\",\"correo\",\"asesor\",\"delegado\",\"curso\",\"pais\",\"ciudad\",\"moneda\",\"metodo_pago\",\"ip\",\"fecha\",\"hora\",\"categoria\",\"file_url\"]', 'www.psicologiaenvivo.com', 2, '2026-02-24 18:19:30', '2026-02-24 18:19:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_globales`
--

DROP TABLE IF EXISTS `opciones_globales`;
CREATE TABLE `opciones_globales` (
  `id` int(11) NOT NULL,
  `opcion` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `opciones_globales`
--

INSERT INTO `opciones_globales` (`id`, `opcion`, `valor`, `fecha_actualizacion`) VALUES
(12, 'sistema_nombre', 'Escuela Internacional de Psicología | Sistema de Regitros', '2026-02-25 12:34:22'),
(13, 'login_habilitado', '1', '2026-02-25 12:34:22'),
(14, 'login_mensaje', 'El sistema se encuentra en mantenimiento. Por favor, intente más tarde.', '2026-02-25 12:34:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_sistema`
--

DROP TABLE IF EXISTS `opciones_sistema`;
CREATE TABLE `opciones_sistema` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `seccion` varchar(50) NOT NULL,
  `opcion` varchar(100) NOT NULL,
  `valor` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`valor`)),
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `opciones_sistema`
--

INSERT INTO `opciones_sistema` (`id`, `usuario_id`, `seccion`, `opcion`, `valor`, `fecha_actualizacion`) VALUES
(1, 2, 'permisos', 'permisos_usuario', '{\"dashboard\":{\"col_nombre\":true,\"col_apellidos\":true,\"col_telefono\":true,\"col_correo\":true,\"col_asesor\":true,\"col_delegado\":true,\"col_curso\":true,\"col_pais\":true,\"col_ciudad\":true,\"col_moneda\":true,\"col_metodo_pago\":true,\"col_ip\":true,\"col_fecha\":true,\"col_hora\":true,\"col_categoria\":true,\"col_file_url\":true,\"col_formulario_id\":true,\"col_web\":true,\"filtro_asesor\":true,\"filtro_delegado\":true,\"filtro_curso\":true,\"filtro_pais\":true,\"filtro_ciudad\":true,\"filtro_moneda\":true,\"filtro_metodo_pago\":true,\"filtro_web\":true,\"reordenar_columnas\":true,\"descargar_excel\":true,\"edicion_inline\":true},\"asesores_delegados\":{\"col_nombre\":true,\"col_apellidos\":true,\"col_telefono\":true,\"col_correo\":true,\"col_asesor\":true,\"col_delegado\":true,\"col_curso\":true,\"col_pais\":true,\"col_ciudad\":true,\"col_moneda\":true,\"col_metodo_pago\":true,\"col_ip\":true,\"col_fecha\":true,\"col_hora\":true,\"col_categoria\":true,\"col_file_url\":true,\"col_formulario_id\":true,\"col_web\":true,\"filtro_curso\":true,\"filtro_pais\":true,\"filtro_ciudad\":true,\"filtro_moneda\":true,\"filtro_metodo_pago\":true,\"filtro_web\":true,\"reordenar_columnas\":true,\"descargar_excel\":true,\"edicion_inline\":true},\"estadisticas\":{\"acceso_estadisticas\":true,\"filtro_fecha\":true,\"filtro_asesor\":false,\"filtro_delegado\":true,\"filtro_curso\":true,\"filtro_pais\":true,\"filtro_ciudad\":true,\"filtro_moneda\":true,\"filtro_metodo_pago\":true,\"filtro_categoria\":true,\"filtro_id\":true}}', '2026-02-25 12:38:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

DROP TABLE IF EXISTS `registros`;
CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `asesor` varchar(150) DEFAULT NULL,
  `delegado` varchar(150) DEFAULT NULL,
  `curso` varchar(200) DEFAULT NULL,
  `pais` varchar(80) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `moneda` varchar(30) DEFAULT NULL,
  `metodo_pago` varchar(80) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `file_url` text DEFAULT NULL,
  `formulario_id` varchar(200) DEFAULT NULL,
  `web` varchar(255) DEFAULT NULL,
  `campos_extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`campos_extra`)),
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registros`
--

INSERT INTO `registros` (`id`, `nombre`, `apellidos`, `telefono`, `correo`, `asesor`, `delegado`, `curso`, `pais`, `ciudad`, `moneda`, `metodo_pago`, `ip`, `fecha`, `hora`, `categoria`, `file_url`, `formulario_id`, `web`, `campos_extra`, `fecha_registro`) VALUES
(1, 'Juan Carlos', 'García López', '+54950857730', 'juancarlos.garcia@outlook.com', NULL, NULL, 'Psicología Organizacional', 'Argentina', 'Mendoza', 'CLP', 'Western Union', '135.136.130.219', '2025-12-16', '11:09:09', 'Consulta', 'https://drive.google.com/file/d/8edeff788aea630ffb2e4513d84f9b90/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-12-16 19:21:42'),
(2, 'María Elena', 'Martínez Rodríguez', '+593945939812', 'mariaelena.martinez@gmail.com', NULL, NULL, 'Terapia de Pareja y Familia', 'Ecuador', 'Guayaquil', 'ARS', 'Efectivo', '38.51.44.235', '2025-12-16', '20:37:54', 'Reserva', 'https://drive.google.com/file/d/b5fc6bc536f2b8a93c778e67983b19eb/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-12-16 18:33:57'),
(3, 'Carlos Alberto', 'Hernández Torres', '+593926745654', 'carlosalberto.hernandez@outlook.com', NULL, NULL, 'Coaching Psicológico', 'Ecuador', 'Ambato', 'CLP', 'Tarjeta de Crédito', '114.13.231.70', '2025-12-19', '14:40:44', 'Reserva', 'https://drive.google.com/file/d/48c4946c6bc6ae925c5cad86b867039a/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-12-19 18:45:22'),
(4, 'Ana Patricia', 'López Pérez', '+58978082676', 'anapatricia.lopez@hotmail.com', NULL, 'Diego Morales', 'Psicología Organizacional', 'Venezuela', 'Barquisimeto', 'COP', 'Efectivo', '231.19.125.208', '2025-11-15', '13:14:02', 'Consulta', 'https://drive.google.com/file/d/13c837d46db926f9b84073bcd46bf664/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-11-15 19:14:34'),
(5, 'Roberto', 'González Ramírez', '+58969769980', 'roberto.gonzalez@outlook.com', 'Roberto García', NULL, 'Terapia de Pareja y Familia', 'Venezuela', 'Maracay', 'PEN', 'PayPal', '66.168.81.14', '2025-10-04', '07:51:43', 'Matrícula', 'https://drive.google.com/file/d/a579d0ed98b8ca7b27e090e23fae57fd/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-04 18:54:56'),
(6, 'Lucía del Carmen', 'Pérez Flores', '+56953418613', 'luciadelcarmen.perez@gmail.com', NULL, NULL, 'Psicología Forense', 'Chile', 'Valparaíso', 'CLP', 'PayPal', '97.156.0.84', '2025-09-12', '07:41:01', 'Reserva', 'https://drive.google.com/file/d/4445646b965e96e144bd410579650898/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-09-12 18:58:25'),
(7, 'Fernando de Jesús', 'Sánchez Rivera', '+52954360996', 'fernandodejesus.sanchez@outlook.com', NULL, 'Pedro Ramírez', 'Psicología Clínica Avanzada', 'México', 'Guadalajara', 'USD', 'Western Union', '233.129.17.243', '2025-12-24', '14:23:53', 'Inscripción', 'https://drive.google.com/file/d/6b419a56ecc16c27598d0489d0cd0768/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-12-24 18:30:21'),
(8, 'Gabriela', 'Ramírez Cruz', '+56995382722', 'gabriela.ramirez@yahoo.com', NULL, 'Diego Morales', 'Terapia Cognitivo Conductual', 'Chile', 'Valparaíso', 'MXN', 'Tarjeta de Débito', '166.1.150.225', '2025-11-13', '09:32:35', 'Reserva', NULL, 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-13 18:59:21'),
(9, 'Pedro Pablo', 'Torres Morales', '+34909616537', 'pedropablo.torres@hotmail.com', NULL, NULL, 'Psicología Clínica Avanzada', 'España', 'Barcelona', 'MXN', 'Tarjeta de Crédito', '244.26.205.69', '2025-11-02', '15:19:47', 'Reserva', 'https://drive.google.com/file/d/4a94dcd2c28455b41bf7188e395e93dc/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-02 19:22:01'),
(10, 'Carmen Rosa', 'Flores Ortiz', '+593950538269', 'carmenrosa.flores@yahoo.com', NULL, 'Sofía Cruz', 'Terapia Cognitivo Conductual', 'Ecuador', 'Cuenca', 'PEN', 'Western Union', '171.199.110.57', '2026-01-17', '21:36:26', 'Reserva', 'https://drive.google.com/file/d/f6fdeb6f4c618c7971dd4e917e6dc6ec/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-01-17 19:21:02'),
(11, 'Miguel Ángel', 'Rivera Gutiérrez', '+54945190429', 'miguelangel.rivera@yahoo.com', 'Ana Torres', NULL, 'Psicología Infantil', 'Argentina', 'Córdoba', 'PEN', 'Western Union', '112.50.52.162', '2026-02-02', '22:19:40', 'Matrícula', 'https://drive.google.com/file/d/b46a189fee907239a96c31d9d1687521/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-02-02 18:36:29'),
(12, 'Sofía', 'Gómez Castillo', '+595911222529', 'sofia.gomez@yahoo.com', NULL, NULL, 'Psicología Clínica Básica', 'Paraguay', 'Ciudad del Este', 'USD', 'Plin', '117.137.147.167', '2025-11-28', '16:10:11', 'Consulta', 'https://drive.google.com/file/d/fb669bf02cf520e312dd0a0c3177f5b6/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-11-28 18:58:28'),
(13, 'Diego Armando', 'Díaz Mendoza', '+593991728808', 'diegoarmando.diaz@gmail.com', NULL, NULL, 'Psicología Forense', 'Ecuador', 'Guayaquil', 'EUR', 'Efectivo', '118.118.197.71', '2025-11-18', '10:05:57', 'Consulta', 'https://drive.google.com/file/d/8f167163a33feabaf9b531b677b0ef63/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-18 19:22:16'),
(14, 'Valentina', 'Cruz Vargas', '+591931070924', 'valentina.cruz@yahoo.com', NULL, 'Sofía Cruz', 'Psicología Forense', 'Bolivia', 'Oruro', 'EUR', 'Western Union', '85.102.166.7', '2025-11-14', '18:40:58', 'Consulta', 'https://drive.google.com/file/d/9a4bf2032497e4605109a4b94a515c0e/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-14 18:52:06'),
(15, 'José Luis', 'Morales Reyes', '+58963928551', 'joseluis.morales@gmail.com', NULL, NULL, 'Psicología Organizacional', 'Venezuela', 'Maracay', 'EUR', 'Plin', '44.208.36.14', '2025-12-08', '18:33:20', 'Matrícula', NULL, 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-12-08 19:13:07'),
(16, 'Camila', 'Ortiz Jiménez', '+57980847733', 'camila.ortiz@yahoo.com', 'Carlos Pérez', NULL, 'Neuropsicología', 'Colombia', 'Barranquilla', 'MXN', 'Western Union', '59.223.107.231', '2025-12-11', '12:55:16', 'Preinscripción', NULL, 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-12-11 19:01:49'),
(17, 'Andrés Felipe', 'Gutiérrez Ruiz', '+51975751945', 'andresfelipe.gutierrez@yahoo.com', 'María López', NULL, 'Terapia Cognitivo Conductual', 'Perú', 'Arequipa', 'COP', 'Tarjeta de Crédito', '137.255.214.255', '2026-01-02', '17:53:14', 'Reserva', 'https://drive.google.com/file/d/b98a5c1d0feb37589af3a6ad0988ed1b/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2026-01-02 19:04:54'),
(18, 'Isabella', 'Mendoza Álvarez', '+51903029234', 'isabella.mendoza@hotmail.com', NULL, NULL, 'Evaluación Psicológica', 'Perú', 'Chiclayo', 'USD', 'Yape', '232.153.101.84', '2026-02-18', '13:16:37', 'Matrícula', 'https://drive.google.com/file/d/7074b44c7a718036df1b92b1869d9862/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2026-02-18 19:14:54'),
(19, 'Ricardo', 'Castillo Romero', '+595924263660', 'ricardo.castillo@outlook.com', 'Lucía Mendoza', NULL, 'Psicología Forense', 'Paraguay', 'Encarnación', 'EUR', 'Tarjeta de Crédito', '198.164.217.33', '2025-09-17', '09:23:01', 'Reserva', 'https://drive.google.com/file/d/bdb0354aa9fa2605da2be98d6b5dfe9d/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-09-17 19:00:40'),
(20, 'Daniela', 'Vargas Herrera', '+593957936416', 'daniela.vargas@gmail.com', NULL, NULL, 'Neuropsicología', 'Ecuador', 'Loja', 'CLP', 'Tarjeta de Débito', '250.111.227.36', '2025-10-14', '20:34:02', 'Preinscripción', 'https://drive.google.com/file/d/33d6c3ea42b55e4b363b818b875ef701/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-14 19:18:14'),
(21, 'Alejandro', 'Reyes Medina', '+51923632801', 'alejandro.reyes@yahoo.com', 'Lucía Mendoza', NULL, 'Psicología Clínica Básica', 'Perú', 'Cusco', 'EUR', 'Tarjeta de Crédito', '47.89.197.196', '2025-09-18', '12:41:35', 'Reserva', 'https://drive.google.com/file/d/49b9247013bb4932cc8f61fb52e515c8/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-09-18 19:07:37'),
(22, 'Paula Andrea', 'Jiménez Aguilar', '+57984149053', 'paulaandrea.jimenez@gmail.com', NULL, NULL, 'Terapia de Pareja y Familia', 'Colombia', 'Cali', 'EUR', 'Efectivo', '245.106.177.247', '2026-01-20', '12:32:29', 'Matrícula', 'https://drive.google.com/file/d/bde9ebf648fdb637a33cfeae5ce84a99/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2026-01-20 18:39:57'),
(23, 'Sebastián', 'Ruiz Garza', '+591910586590', 'sebastian.ruiz@hotmail.com', 'Carlos Pérez', NULL, 'Terapia de Pareja y Familia', 'Bolivia', 'La Paz', 'COP', 'PayPal', '94.202.208.169', '2026-01-30', '19:48:54', 'Consulta', 'https://drive.google.com/file/d/82b8af55c4a94dceb1e70cd07b8285dc/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-01-30 18:36:34'),
(24, 'Mariana', 'Álvarez Silva', '+506993158739', 'mariana.alvarez@outlook.com', 'Roberto García', NULL, 'Psicología Infantil', 'Costa Rica', 'Heredia', 'MXN', 'Tarjeta de Crédito', '145.134.57.199', '2025-12-24', '20:49:31', 'Matrícula', 'https://drive.google.com/file/d/f410e7faade1eed3eee5461e22aa48fe/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-12-24 19:03:48'),
(25, 'Javier', 'Romero Castro', '+57995414268', 'javier.romero@outlook.com', NULL, NULL, 'Neuropsicología', 'Colombia', 'Cartagena', 'COP', 'Yape', '114.72.242.108', '2026-01-26', '13:49:37', 'Inscripción', NULL, 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-01-26 19:08:25'),
(26, 'Natalia', 'Herrera Vázquez', '+593915476814', 'natalia.herrera@outlook.com', NULL, 'Sofía Cruz', 'Psicología Infantil', 'Ecuador', 'Guayaquil', 'CLP', 'Transferencia Bancaria', '33.208.247.37', '2025-08-28', '08:18:17', 'Matrícula', 'https://drive.google.com/file/d/062f37a2d730a7931341ab46566f9625/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-08-28 18:54:53'),
(27, 'Óscar', 'Medina Ramos', '+58937102608', 'oscar.medina@yahoo.com', 'Lucía Mendoza', NULL, 'Terapia Cognitivo Conductual', 'Venezuela', 'Maracay', 'COP', 'Plin', '190.195.205.77', '2025-12-25', '19:04:26', 'Preinscripción', 'https://drive.google.com/file/d/a0efa4b5997b9ea2ece68a5063cb35e8/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-12-25 18:41:32'),
(28, 'Lorena', 'Aguilar Santos', '+51950034942', 'lorena.aguilar@gmail.com', 'Roberto García', NULL, 'Psicología Forense', 'Perú', 'Trujillo', 'PEN', 'PayPal', '56.150.185.26', '2026-02-03', '07:55:33', 'Inscripción', 'https://drive.google.com/file/d/51d5c1c71a0fd595f33e8563c7b2eda8/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2026-02-03 18:34:56'),
(29, 'Emilio', 'Garza Guerrero', '+595947897609', 'emilio.garza@outlook.com', NULL, 'Sofía Cruz', 'Psicología Organizacional', 'Paraguay', 'Asunción', 'ARS', 'PayPal', '130.149.206.144', '2025-11-26', '20:01:10', 'Inscripción', 'https://drive.google.com/file/d/4b176f8080278e9f7de3d6cc87641c38/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-26 19:02:26'),
(30, 'Claudia', 'Silva Peña', '+34972378160', 'claudia.silva@gmail.com', 'Ana Torres', NULL, 'Neuropsicología', 'España', 'Madrid', 'COP', 'Tarjeta de Crédito', '81.141.78.48', '2025-10-11', '10:34:31', 'Consulta', 'https://drive.google.com/file/d/47a8ef46bb6c1e67e4fb8aaafb276d88/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-10-11 19:27:31'),
(31, 'Héctor', 'Castro Ríos', '+506911186149', 'hector.castro@yahoo.com', NULL, 'Diego Morales', 'Psicología Forense', 'Costa Rica', 'Heredia', 'CLP', 'Tarjeta de Débito', '125.75.229.160', '2025-11-24', '20:25:16', 'Consulta', 'https://drive.google.com/file/d/c6eb0dec396078fe19e30a7d5bb93caf/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-24 19:16:33'),
(32, 'Patricia', 'Vázquez Campos', '+54960334429', 'patricia.vazquez@yahoo.com', 'Carlos Pérez', NULL, 'Psicología Organizacional', 'Argentina', 'Rosario', 'ARS', 'Plin', '159.142.30.166', '2025-09-07', '22:24:16', 'Consulta', 'https://drive.google.com/file/d/e85b58410dd45b8c92ffd4cf7f81bea3/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-09-07 18:47:29'),
(33, 'Gustavo', 'Ramos Delgado', '+502965047986', 'gustavo.ramos@outlook.com', NULL, 'Diego Morales', 'Coaching Psicológico', 'Guatemala', 'Petén', 'EUR', 'Plin', '127.60.30.98', '2026-01-06', '20:54:12', 'Matrícula', 'https://drive.google.com/file/d/f95f467e9ce4851bc671a62457ddd7cb/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-01-06 19:22:28'),
(34, 'Adriana', 'Santos Navarro', '+502946520812', 'adriana.santos@outlook.com', NULL, 'Sofía Cruz', 'Terapia Cognitivo Conductual', 'Guatemala', 'Ciudad de Guatemala', 'CLP', 'Tarjeta de Débito', '188.222.186.11', '2025-09-21', '22:20:27', 'Inscripción', 'https://drive.google.com/file/d/8ddfb2a0780941558322d6cc21d662e0/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-09-21 19:07:12'),
(35, 'Raúl', 'Guerrero Molina', '+57944628815', 'raul.guerrero@yahoo.com', NULL, NULL, 'Psicología Forense', 'Colombia', 'Bogotá', 'COP', 'Transferencia Bancaria', '255.131.197.87', '2025-12-22', '21:05:18', 'Preinscripción', 'https://drive.google.com/file/d/a839c71cbdec06f8d320328dde372e61/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-12-22 19:18:46'),
(36, 'Verónica', 'Peña Domínguez', '+506921948467', 'veronica.pena@outlook.com', 'Roberto García', NULL, 'Terapia de Pareja y Familia', 'Costa Rica', 'Alajuela', 'MXN', 'Yape', '62.209.54.190', '2025-10-24', '18:31:05', 'Consulta', 'https://drive.google.com/file/d/19bb2905bec7578c01fae7d1c7e65a2e/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-24 18:37:56'),
(37, 'Martín', 'Ríos Suárez', '+57908848443', 'martin.rios@gmail.com', NULL, 'Pedro Ramírez', 'Psicología Clínica Básica', 'Colombia', 'Bogotá', 'CLP', 'Tarjeta de Débito', '240.225.174.196', '2025-11-26', '22:26:07', 'Inscripción', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-26 18:42:12'),
(38, 'Carolina', 'Campos Ibarra', '+58945592535', 'carolina.campos@outlook.com', NULL, 'Sofía Cruz', 'Psicología Clínica Básica', 'Venezuela', 'Maracay', 'COP', 'PayPal', '197.112.126.141', '2025-10-01', '16:52:11', 'Consulta', NULL, 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-10-01 18:42:22'),
(39, 'Francisco', 'Delgado Mejía', '+593948304492', 'francisco.delgado@hotmail.com', NULL, NULL, 'Evaluación Psicológica', 'Ecuador', 'Quito', 'CLP', 'Tarjeta de Crédito', '190.108.163.221', '2026-02-05', '09:30:06', 'Reserva', 'https://drive.google.com/file/d/994487cc935b45c3441f802c9b6aafad/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-02-05 18:40:30'),
(40, 'Mónica', 'Navarro Osorio', '+56944680338', 'monica.navarro@yahoo.com', NULL, 'Pedro Ramírez', 'Psicología Clínica Básica', 'Chile', 'Antofagasta', 'EUR', 'Tarjeta de Crédito', '165.148.194.169', '2026-01-19', '15:13:57', 'Consulta', 'https://drive.google.com/file/d/c152f9a42f88801384c68e773d83eec6/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2026-01-19 18:37:43'),
(41, 'Eduardo', 'Molina Contreras', '+52947893048', 'eduardo.molina@outlook.com', 'Ana Torres', NULL, 'Psicología Clínica Básica', 'México', 'Cancún', 'EUR', 'Plin', '197.114.17.17', '2026-01-15', '10:06:36', 'Inscripción', 'https://drive.google.com/file/d/9ebb74371f867c9639079593991e045a/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-01-15 19:11:25'),
(42, 'Silvia', 'Domínguez Salazar', '+593993003556', 'silvia.dominguez@yahoo.com', NULL, 'Sofía Cruz', 'Neuropsicología', 'Ecuador', 'Loja', 'PEN', 'Western Union', '190.255.126.129', '2026-02-03', '11:43:45', 'Matrícula', 'https://drive.google.com/file/d/0af907cd0dfb40fc1f250b3a8e3ec2f4/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-02-03 19:06:48'),
(43, 'Gonzalo', 'Suárez Espinoza', '+57945576806', 'gonzalo.suarez@hotmail.com', 'Ana Torres', NULL, 'Terapia de Pareja y Familia', 'Colombia', 'Cartagena', 'COP', 'Plin', '186.42.209.183', '2025-12-31', '18:57:46', 'Consulta', 'https://drive.google.com/file/d/3218bcce0a97d3ec80d9e45fe5440ef5/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-12-31 19:24:20'),
(44, 'Teresa', 'Ibarra León', '+52993044055', 'teresa.ibarra@gmail.com', NULL, NULL, 'Evaluación Psicológica', 'México', 'Puebla', 'COP', 'Transferencia Bancaria', '6.98.46.170', '2025-11-14', '08:01:39', 'Inscripción', 'https://drive.google.com/file/d/f09c786d779b9840bdbe041b1aac8124/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-11-14 18:52:49'),
(45, 'Ramón', 'Mejía Acosta', '+52955225067', 'ramon.mejia@hotmail.com', 'María López', NULL, 'Psicología Infantil', 'México', 'Monterrey', 'MXN', 'Transferencia Bancaria', '68.100.31.45', '2026-01-30', '14:13:54', 'Matrícula', NULL, 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-01-30 18:57:55'),
(46, 'Elena', 'Osorio Palacios', '+506952885886', 'elena.osorio@hotmail.com', NULL, NULL, 'Terapia de Pareja y Familia', 'Costa Rica', 'Alajuela', 'ARS', 'Plin', '6.128.72.113', '2025-10-24', '12:10:52', 'Reserva', NULL, 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-24 18:55:41'),
(47, 'Pablo', 'Contreras Bautista', '+56948132621', 'pablo.contreras@outlook.com', NULL, NULL, 'Psicología Forense', 'Chile', 'Temuco', 'CLP', 'PayPal', '88.202.168.170', '2026-01-09', '18:18:34', 'Consulta', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2026-01-09 19:04:04'),
(48, 'Diana', 'Salazar Valencia', '+595982221721', 'diana.salazar@outlook.com', 'María López', NULL, 'Evaluación Psicológica', 'Paraguay', 'Luque', 'USD', 'Western Union', '61.104.89.165', '2026-02-01', '18:57:19', 'Consulta', 'https://drive.google.com/file/d/f679a78e03561f8d5afa899e5b81bf3e/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-02-01 18:54:50'),
(49, 'Tomás', 'Espinoza Cordero', '+54929960376', 'tomas.espinoza@outlook.com', NULL, NULL, 'Terapia de Pareja y Familia', 'Argentina', 'Rosario', 'USD', 'Western Union', '43.23.56.172', '2026-01-15', '20:50:21', 'Matrícula', 'https://drive.google.com/file/d/363a013c60e6cb8b536886a2b09cdf52/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-01-15 18:34:34'),
(50, 'Beatriz', 'León Miranda', '+51990752842', 'beatriz.leon@outlook.com', NULL, NULL, 'Psicología Clínica Avanzada', 'Perú', 'Chiclayo', 'COP', 'Plin', '138.53.11.127', '2025-12-22', '21:16:14', 'Consulta', 'https://drive.google.com/file/d/db588566481bf5bebe99d6a124280ed2/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-12-22 18:52:10'),
(51, 'Juan Carlos', 'García López', '+57901622576', 'juancarlos.garcia@gmail.com', NULL, 'Diego Morales', 'Evaluación Psicológica', 'Colombia', 'Medellín', 'PEN', 'Efectivo', '65.42.84.190', '2025-11-05', '18:49:51', 'Matrícula', 'https://drive.google.com/file/d/08d47420410b63b864c2a3ae64a853be/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-05 21:30:37'),
(52, 'María Elena', 'Martínez Rodríguez', '+51920717253', 'mariaelena.martinez@yahoo.com', NULL, NULL, 'Psicología Clínica Básica', 'Perú', 'Chiclayo', 'EUR', 'Yape', '75.79.118.8', '2026-01-04', '11:28:26', 'Inscripción', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2026-01-04 21:08:10'),
(53, 'Carlos Alberto', 'Hernández Torres', '+54995652771', 'carlosalberto.hernandez@gmail.com', NULL, 'Diego Morales', 'Psicología Clínica Básica', 'Argentina', 'Mendoza', 'ARS', 'Transferencia Bancaria', '69.191.140.143', '2025-11-18', '19:45:33', 'Inscripción', 'https://drive.google.com/file/d/c9f116ce3d8826314453555a32cb3d53/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-18 21:13:14'),
(54, 'Ana Patricia', 'López Pérez', '+502904528544', 'anapatricia.lopez@yahoo.com', NULL, 'Diego Morales', 'Psicología Clínica Avanzada', 'Guatemala', 'Petén', 'ARS', 'Plin', '133.15.127.151', '2025-11-09', '16:19:26', 'Reserva', NULL, 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-11-09 21:12:44'),
(55, 'Roberto', 'González Ramírez', '+506969156936', 'roberto.gonzalez@yahoo.com', 'Roberto García', NULL, 'Coaching Psicológico', 'Costa Rica', 'Liberia', 'PEN', 'Plin', '216.157.90.136', '2026-01-27', '20:20:18', 'Consulta', 'https://drive.google.com/file/d/92217d057113821fab19438875bfa3cd/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2026-01-27 20:55:52'),
(56, 'Lucía del Carmen', 'Pérez Flores', '+593968315947', 'luciadelcarmen.perez@hotmail.com', NULL, 'Diego Morales', 'Psicología Infantil', 'Ecuador', 'Loja', 'MXN', 'Tarjeta de Crédito', '46.120.161.102', '2025-10-21', '17:09:00', 'Preinscripción', 'https://drive.google.com/file/d/6d4ab023883d48053986c943163df185/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-21 21:36:55'),
(57, 'Fernando de Jesús', 'Sánchez Rivera', '+54927550544', 'fernandodejesus.sanchez@outlook.com', 'Ana Torres', NULL, 'Terapia Cognitivo Conductual', 'Argentina', 'La Plata', 'COP', 'Transferencia Bancaria', '23.147.208.131', '2026-01-28', '20:05:37', 'Consulta', 'https://drive.google.com/file/d/1ce724139469612e260927396993107c/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-01-28 21:45:50'),
(58, 'Gabriela', 'Ramírez Cruz', '+502922739519', 'gabriela.ramirez@hotmail.com', NULL, NULL, 'Psicología Clínica Avanzada', 'Guatemala', 'Ciudad de Guatemala', 'USD', 'Yape', '253.8.28.52', '2025-10-24', '14:36:04', 'Preinscripción', 'https://drive.google.com/file/d/d07b69f584e5c1482b26018377f8dddc/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-24 21:48:59'),
(59, 'Pedro Pablo', 'Torres Morales', '+51939519659', 'pedropablo.torres@yahoo.com', NULL, 'Pedro Ramírez', 'Coaching Psicológico', 'Perú', 'Arequipa', 'COP', 'PayPal', '32.176.139.133', '2025-11-16', '15:53:13', 'Inscripción', NULL, 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-16 21:05:18'),
(60, 'Carmen Rosa', 'Flores Ortiz', '+506971996120', 'carmenrosa.flores@hotmail.com', 'Ana Torres', NULL, 'Evaluación Psicológica', 'Costa Rica', 'Liberia', 'COP', 'Western Union', '23.49.224.126', '2025-12-02', '13:49:13', 'Reserva', NULL, 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-12-02 21:23:01'),
(61, 'Miguel Ángel', 'Rivera Gutiérrez', '+593996181598', 'miguelangel.rivera@yahoo.com', 'Roberto García', NULL, 'Psicología Clínica Básica', 'Ecuador', 'Loja', 'ARS', 'PayPal', '13.176.147.190', '2025-09-06', '07:45:26', 'Matrícula', 'https://drive.google.com/file/d/a73e092244577008ead0f1848eac6f04/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2025-09-06 21:13:05'),
(62, 'Sofía', 'Gómez Castillo', '+56919354750', 'sofia.gomez@yahoo.com', NULL, NULL, 'Terapia de Pareja y Familia', 'Chile', 'Santiago', 'PEN', 'Tarjeta de Débito', '122.247.106.33', '2025-11-30', '07:17:21', 'Consulta', 'https://drive.google.com/file/d/268c0fd4a1437a7d64474296522c8485/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-30 21:38:20'),
(63, 'Diego Armando', 'Díaz Mendoza', '+595903604315', 'diegoarmando.diaz@yahoo.com', NULL, NULL, 'Psicología Clínica Avanzada', 'Paraguay', 'Asunción', 'CLP', 'Yape', '121.249.106.184', '2026-01-17', '08:51:24', 'Preinscripción', NULL, 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-01-17 21:10:52'),
(64, 'Valentina', 'Cruz Vargas', '+34966675183', 'valentina.cruz@hotmail.com', 'Ana Torres', NULL, 'Psicología Infantil', 'España', 'Barcelona', 'MXN', 'PayPal', '153.14.225.27', '2025-11-23', '17:44:33', 'Consulta', NULL, 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-23 21:35:41'),
(65, 'José Luis', 'Morales Reyes', '+593923202776', 'joseluis.morales@gmail.com', NULL, NULL, 'Psicología Infantil', 'Ecuador', 'Quito', 'PEN', 'Tarjeta de Débito', '139.21.37.23', '2025-08-28', '13:14:41', 'Preinscripción', 'https://drive.google.com/file/d/cb5174d686b111bd502e249e15fa5348/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-08-28 21:13:33'),
(66, 'Camila', 'Ortiz Jiménez', '+57958997095', 'camila.ortiz@outlook.com', 'Ana Torres', NULL, 'Psicología Clínica Básica', 'Colombia', 'Cartagena', 'COP', 'Transferencia Bancaria', '136.32.229.93', '2025-11-25', '12:52:25', 'Reserva', NULL, 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2025-11-25 21:23:30'),
(67, 'Andrés Felipe', 'Gutiérrez Ruiz', '+56965946588', 'andresfelipe.gutierrez@yahoo.com', 'Lucía Mendoza', NULL, 'Psicología Infantil', 'Chile', 'Valparaíso', 'ARS', 'Western Union', '132.76.105.41', '2025-09-24', '22:13:02', 'Matrícula', NULL, 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-09-24 20:57:55'),
(68, 'Isabella', 'Mendoza Álvarez', '+591976825592', 'isabella.mendoza@gmail.com', 'Carlos Pérez', NULL, 'Neuropsicología', 'Bolivia', 'Cochabamba', 'CLP', 'Yape', '142.190.151.57', '2025-12-21', '17:08:32', 'Preinscripción', 'https://drive.google.com/file/d/347714b06f7a0495af15de83f5db61bc/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-12-21 21:37:02'),
(69, 'Ricardo', 'Castillo Romero', '+58937254314', 'ricardo.castillo@hotmail.com', NULL, NULL, 'Evaluación Psicológica', 'Venezuela', 'Caracas', 'USD', 'Western Union', '39.101.147.18', '2025-12-24', '22:40:55', 'Consulta', NULL, 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-12-24 21:04:53'),
(70, 'Daniela', 'Vargas Herrera', '+54940373053', 'daniela.vargas@yahoo.com', 'Carlos Pérez', NULL, 'Terapia Cognitivo Conductual', 'Argentina', 'Buenos Aires', 'MXN', 'Yape', '62.52.213.29', '2025-09-12', '21:02:56', 'Matrícula', 'https://drive.google.com/file/d/d31cb91073581f2307f5974ef554c292/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-09-12 21:22:45'),
(71, 'Alejandro', 'Reyes Medina', '+506954260838', 'alejandro.reyes@outlook.com', NULL, 'Pedro Ramírez', 'Terapia de Pareja y Familia', 'Costa Rica', 'Cartago', 'PEN', 'Yape', '242.51.28.135', '2025-11-02', '17:17:03', 'Preinscripción', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-02 21:00:50'),
(72, 'Paula Andrea', 'Jiménez Aguilar', '+51964995116', 'paulaandrea.jimenez@yahoo.com', NULL, NULL, 'Neuropsicología', 'Perú', 'Trujillo', 'ARS', 'Tarjeta de Débito', '186.211.71.203', '2026-02-15', '14:51:31', 'Consulta', 'https://drive.google.com/file/d/5a6a57507e003c40dfd340592ae4a31e/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-02-15 20:58:26'),
(73, 'Sebastián', 'Ruiz Garza', '+52945757456', 'sebastian.ruiz@hotmail.com', NULL, 'Diego Morales', 'Coaching Psicológico', 'México', 'Ciudad de México', 'PEN', 'Western Union', '29.150.92.195', '2025-09-18', '20:12:56', 'Reserva', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-09-18 21:31:55'),
(74, 'Mariana', 'Álvarez Silva', '+595919086820', 'mariana.alvarez@gmail.com', NULL, NULL, 'Psicología Infantil', 'Paraguay', 'Asunción', 'COP', 'Yape', '169.217.118.42', '2025-09-12', '19:06:58', 'Consulta', 'https://drive.google.com/file/d/f6d573637b1d2253669bb89e39951026/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2025-09-12 21:45:31'),
(75, 'Javier', 'Romero Castro', '+54957205364', 'javier.romero@yahoo.com', NULL, 'Pedro Ramírez', 'Evaluación Psicológica', 'Argentina', 'Rosario', 'MXN', 'Transferencia Bancaria', '52.186.178.204', '2025-11-19', '22:52:49', 'Preinscripción', 'https://drive.google.com/file/d/e1cec933141dfc61c90a5c3deb565f87/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-19 21:32:34'),
(76, 'Natalia', 'Herrera Vázquez', '+51966841317', 'natalia.herrera@gmail.com', NULL, NULL, 'Psicología Clínica Avanzada', 'Perú', 'Chiclayo', 'MXN', 'Tarjeta de Débito', '233.195.203.62', '2026-02-10', '16:05:20', 'Inscripción', 'https://drive.google.com/file/d/e09b46c3ef7a618de14e9ab40c3aead6/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-02-10 21:19:37'),
(77, 'Óscar', 'Medina Ramos', '+56906146566', 'oscar.medina@outlook.com', NULL, 'Sofía Cruz', 'Psicología Infantil', 'Chile', 'Antofagasta', 'CLP', 'Tarjeta de Crédito', '153.110.238.113', '2025-10-17', '08:21:34', 'Reserva', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-10-17 21:22:51'),
(78, 'Lorena', 'Aguilar Santos', '+506905777630', 'lorena.aguilar@hotmail.com', 'Lucía Mendoza', NULL, 'Evaluación Psicológica', 'Costa Rica', 'San José', 'PEN', 'Transferencia Bancaria', '14.64.13.214', '2026-01-07', '09:53:10', 'Preinscripción', 'https://drive.google.com/file/d/cba4ec7e80b0b510ad6d21740f9b53c4/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2026-01-07 21:42:12'),
(79, 'Emilio', 'Garza Guerrero', '+595933430315', 'emilio.garza@outlook.com', NULL, 'Sofía Cruz', 'Psicología Clínica Avanzada', 'Paraguay', 'San Lorenzo', 'COP', 'Transferencia Bancaria', '249.5.91.34', '2025-11-18', '15:26:35', 'Reserva', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-18 21:44:32'),
(80, 'Claudia', 'Silva Peña', '+506956659887', 'claudia.silva@outlook.com', NULL, NULL, 'Psicología Clínica Avanzada', 'Costa Rica', 'San José', 'ARS', 'Tarjeta de Crédito', '68.160.77.18', '2026-02-10', '15:49:47', 'Matrícula', NULL, 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2026-02-10 21:46:26'),
(81, 'Héctor', 'Castro Ríos', '+34979177637', 'hector.castro@outlook.com', 'María López', NULL, 'Terapia Cognitivo Conductual', 'España', 'Sevilla', 'CLP', 'Tarjeta de Débito', '57.45.73.46', '2026-01-01', '12:28:10', 'Matrícula', 'https://drive.google.com/file/d/db44c43399fc0a361be2b9402dd8e31f/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-01-01 21:24:22'),
(82, 'Patricia', 'Vázquez Campos', '+58920276704', 'patricia.vazquez@gmail.com', NULL, 'Diego Morales', 'Psicología Infantil', 'Venezuela', 'Maracaibo', 'EUR', 'Tarjeta de Crédito', '67.96.169.132', '2026-02-08', '15:01:21', 'Consulta', 'https://drive.google.com/file/d/f8b849b36308ee6257676ff711eeb7d8/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2026-02-08 21:46:36'),
(83, 'Gustavo', 'Ramos Delgado', '+591976404609', 'gustavo.ramos@gmail.com', NULL, 'Sofía Cruz', 'Psicología Infantil', 'Bolivia', 'Cochabamba', 'MXN', 'Plin', '165.77.8.93', '2025-09-15', '08:18:23', 'Reserva', 'https://drive.google.com/file/d/b7a171c091c14e7506f1533f78c20015/view', 'Formulario Preinscripción Diplomado', 'https://cursos.psicologiaenvivo.com', NULL, '2025-09-15 21:42:37'),
(84, 'Adriana', 'Santos Navarro', '+506952248671', 'adriana.santos@gmail.com', NULL, 'Pedro Ramírez', 'Psicología Organizacional', 'Costa Rica', 'Liberia', 'CLP', 'Transferencia Bancaria', '242.184.59.136', '2025-11-21', '15:36:00', 'Reserva', NULL, 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-21 21:17:31'),
(85, 'Raúl', 'Guerrero Molina', '+506947109660', 'raul.guerrero@outlook.com', NULL, NULL, 'Psicología Clínica Básica', 'Costa Rica', 'Cartago', 'MXN', 'Tarjeta de Crédito', '97.238.175.82', '2026-01-18', '16:29:13', 'Inscripción', 'https://drive.google.com/file/d/dfced991c59db174d9fea3fcdc01de90/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2026-01-18 21:21:27'),
(86, 'Verónica', 'Peña Domínguez', '+56923962489', 'veronica.pena@hotmail.com', NULL, 'Pedro Ramírez', 'Neuropsicología', 'Chile', 'Temuco', 'PEN', 'Western Union', '115.93.79.251', '2026-02-18', '22:04:08', 'Inscripción', 'https://drive.google.com/file/d/23f25505c4b607a9ac370341cce8ce60/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-02-18 21:00:51'),
(87, 'Martín', 'Ríos Suárez', '+591940054606', 'martin.rios@hotmail.com', 'Carlos Pérez', NULL, 'Psicología Clínica Avanzada', 'Bolivia', 'Santa Cruz', 'USD', 'PayPal', '145.41.98.166', '2025-11-17', '16:14:01', 'Reserva', 'https://drive.google.com/file/d/13128895dbaa2eb0d5c68edc28ce2361/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-11-17 20:55:54'),
(88, 'Carolina', 'Campos Ibarra', '+591918978454', 'carolina.campos@outlook.com', 'María López', NULL, 'Psicología Infantil', 'Bolivia', 'Sucre', 'USD', 'PayPal', '196.149.39.133', '2025-10-31', '22:25:56', 'Inscripción', 'https://drive.google.com/file/d/53b769d0f19f1140c1261c815ba28cc7/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-10-31 21:27:39'),
(89, 'Francisco', 'Delgado Mejía', '+52928419524', 'francisco.delgado@yahoo.com', 'María López', NULL, 'Psicología Forense', 'México', 'Cancún', 'ARS', 'Yape', '145.201.245.184', '2025-12-31', '20:35:58', 'Preinscripción', 'https://drive.google.com/file/d/8c990938eb4e9aa3a80a8b89f1b042aa/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-12-31 20:57:25'),
(90, 'Mónica', 'Navarro Osorio', '+595934005850', 'monica.navarro@hotmail.com', 'Ana Torres', NULL, 'Psicología Clínica Avanzada', 'Paraguay', 'Asunción', 'EUR', 'Efectivo', '137.145.164.182', '2025-11-03', '08:19:33', 'Inscripción', 'https://drive.google.com/file/d/c5dd177560ef2a791e4f289ab1d2c6a4/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-11-03 20:57:31'),
(91, 'Eduardo', 'Molina Contreras', '+502974665298', 'eduardo.molina@outlook.com', NULL, NULL, 'Neuropsicología', 'Guatemala', 'Antigua', 'EUR', 'PayPal', '255.78.87.170', '2025-12-24', '19:42:05', 'Consulta', NULL, 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-12-24 21:08:46'),
(92, 'Silvia', 'Domínguez Salazar', '+591952851143', 'silvia.dominguez@gmail.com', 'Lucía Mendoza', NULL, 'Psicología Clínica Básica', 'Bolivia', 'La Paz', 'PEN', 'PayPal', '215.202.152.80', '2025-09-23', '17:34:53', 'Matrícula', 'https://drive.google.com/file/d/fb20f6e7e9248dfcd2ea11a0dcba653e/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-09-23 21:09:53'),
(93, 'Gonzalo', 'Suárez Espinoza', '+34911695455', 'gonzalo.suarez@hotmail.com', 'María López', NULL, 'Evaluación Psicológica', 'España', 'Valencia', 'ARS', 'Tarjeta de Débito', '245.141.89.59', '2025-10-10', '09:53:51', 'Preinscripción', 'https://drive.google.com/file/d/2048934424329704b0b565c3630d8395/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-10-10 21:10:29'),
(94, 'Teresa', 'Ibarra León', '+58982304411', 'teresa.ibarra@hotmail.com', 'Lucía Mendoza', NULL, 'Psicología Clínica Básica', 'Venezuela', 'Caracas', 'CLP', 'Yape', '153.107.221.165', '2025-11-13', '12:09:49', 'Reserva', 'https://drive.google.com/file/d/6af82895658343b979c0ae8f5a542359/view', 'Formulario Reserva de Vacante', 'https://diplomados.psicologiaenvivo.com', NULL, '2025-11-13 21:49:52'),
(95, 'Ramón', 'Mejía Acosta', '+506910560208', 'ramon.mejia@gmail.com', NULL, 'Diego Morales', 'Terapia Cognitivo Conductual', 'Costa Rica', 'Heredia', 'EUR', 'Yape', '71.26.125.123', '2026-01-13', '20:39:06', 'Preinscripción', 'https://drive.google.com/file/d/bdf42e1dd76df50d850d4d5b6bd0f9b1/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2026-01-13 21:29:18'),
(96, 'Elena', 'Osorio Palacios', '+506904399430', 'elena.osorio@yahoo.com', NULL, NULL, 'Psicología Infantil', 'Costa Rica', 'San José', 'MXN', 'Western Union', '91.240.234.139', '2025-09-13', '17:10:00', 'Preinscripción', 'https://drive.google.com/file/d/7cc1052ffae8de5a96ae1a045be42120/view', 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-09-13 21:11:02'),
(97, 'Pablo', 'Contreras Bautista', '+57949605811', 'pablo.contreras@yahoo.com', 'María López', NULL, 'Psicología Clínica Avanzada', 'Colombia', 'Medellín', 'USD', 'Efectivo', '223.168.85.228', '2025-12-09', '09:00:45', 'Reserva', 'https://drive.google.com/file/d/c103e8128a56d40873aebd766d8cf95d/view', 'Formulario Consulta General', 'https://www.psicologiaenvivo.com', NULL, '2025-12-09 21:41:41'),
(98, 'Diana', 'Salazar Valencia', '+57923906021', 'diana.salazar@gmail.com', NULL, 'Sofía Cruz', 'Evaluación Psicológica', 'Colombia', 'Medellín', 'COP', 'Western Union', '93.219.191.217', '2025-10-07', '12:37:55', 'Matrícula', NULL, 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-07 21:19:04'),
(99, 'Tomás', 'Espinoza Cordero', '+57938235461', 'tomas.espinoza@outlook.com', 'María López', NULL, 'Terapia Cognitivo Conductual', 'Colombia', 'Bogotá', 'PEN', 'Transferencia Bancaria', '218.92.5.77', '2025-08-31', '20:11:36', 'Inscripción', 'https://drive.google.com/file/d/08e09a2a0d99e04b5da616423a033402/view', 'Formulario Inscripción 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-08-31 21:15:30'),
(100, 'Beatriz', 'León Miranda', '+58953144879', 'beatriz.leon@gmail.com', 'Roberto García', NULL, 'Psicología Forense', 'Venezuela', 'Barquisimeto', 'PEN', 'Efectivo', '32.115.229.99', '2025-10-25', '11:54:21', 'Preinscripción', NULL, 'Formulario Matrícula 2026', 'https://www.psicologiaenvivo.com', NULL, '2025-10-25 21:24:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` enum('administrador','consultor') NOT NULL DEFAULT 'consultor',
  `estado` enum('activo','suspendido') NOT NULL DEFAULT 'activo',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `pais`, `telefono`, `usuario`, `password`, `tipo`, `estado`, `fecha_creacion`, `ultimo_acceso`) VALUES
(1, 'Administrador', 'Sistema', 'Perú', '+51000000000', 'admin', '$2y$10$02p1scbMTgfAO/HyjL8lhu1bisghQ6YbwST40C53eXAi18xKmghFS', 'administrador', 'activo', '2026-02-22 23:22:08', '2026-02-25 10:20:30'),
(2, 'Miriam', 'Amezquita', 'Perú', '+51123456789', 'miriam', '$2y$10$97mhUxqL4cqn9kCoVTxFvO5NWANaMBE1UfjTIeFsYvL6SCYJjzeNG', 'consultor', 'activo', '2026-02-24 18:27:36', '2026-02-25 12:34:24');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_dominio` (`dominio`),
  ADD UNIQUE KEY `uk_api_key` (`api_key`);

--
-- Indices de la tabla `campos_dinamicos`
--
ALTER TABLE `campos_dinamicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nombre_campo` (`nombre_campo`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_fecha` (`fecha`);

--
-- Indices de la tabla `mapeo_campos_formulario`
--
ALTER TABLE `mapeo_campos_formulario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_formulario_web` (`formulario_id`,`web`);

--
-- Indices de la tabla `opciones_globales`
--
ALTER TABLE `opciones_globales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_opcion` (`opcion`);

--
-- Indices de la tabla `opciones_sistema`
--
ALTER TABLE `opciones_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuario_seccion_opcion` (`usuario_id`,`seccion`,`opcion`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fecha` (`fecha`),
  ADD KEY `idx_formulario` (`formulario_id`),
  ADD KEY `idx_asesor` (`asesor`),
  ADD KEY `idx_delegado` (`delegado`),
  ADD KEY `idx_pais` (`pais`),
  ADD KEY `idx_curso` (`curso`),
  ADD KEY `idx_categoria` (`categoria`),
  ADD KEY `idx_web` (`web`),
  ADD KEY `idx_fecha_registro` (`fecha_registro`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuario` (`usuario`),
  ADD UNIQUE KEY `uk_telefono` (`telefono`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `campos_dinamicos`
--
ALTER TABLE `campos_dinamicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `mapeo_campos_formulario`
--
ALTER TABLE `mapeo_campos_formulario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `opciones_globales`
--
ALTER TABLE `opciones_globales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `opciones_sistema`
--
ALTER TABLE `opciones_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `opciones_sistema`
--
ALTER TABLE `opciones_sistema`
  ADD CONSTRAINT `fk_opciones_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
