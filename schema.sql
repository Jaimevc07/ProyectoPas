-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 16-01-2023 a las 00:51:35
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registroinnovar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `employees`
--

INSERT INTO `employees` (`id`, `name`) VALUES
(1, 'Jose David Quezedo Hernandez'),
(6, 'Eder Mauricio Barrero Paternina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee_attendance`
--

DROP TABLE IF EXISTS `employee_attendance`;
CREATE TABLE IF NOT EXISTS `employee_attendance` (
  `employee_id` bigint UNSIGNED NOT NULL,
  `date` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `hour` time NOT NULL,
  `hour_out` time NOT NULL,
  `status` enum('presence','absence') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `employee_attendance`
--

INSERT INTO `employee_attendance` (`employee_id`, `date`, `hour`, `hour_out`, `status`) VALUES
(1, '2023-01-14', '00:00:00', '00:00:00', 'presence'),
(1, '2023-01-15', '00:00:00', '00:00:00', 'presence'),
(6, '2023-01-15', '00:00:00', '00:00:00', 'absence'),
(1, '2023-01-15', '00:00:00', '00:00:00', 'presence'),
(1, '2023-01-15', '00:00:00', '00:00:00', 'presence'),
(1, '2023-01-15', '23:55:37', '00:00:00', 'presence'),
(1, '2023-01-16', '00:34:49', '00:00:00', 'presence'),
(1, '2023-01-16', '00:00:00', '00:35:13', 'presence'),
(1, '2023-01-16', '00:34:49', '00:45:56', 'presence');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee_rfid`
--

DROP TABLE IF EXISTS `employee_rfid`;
CREATE TABLE IF NOT EXISTS `employee_rfid` (
  `employee_id` bigint UNSIGNED NOT NULL,
  `rfid_serial` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `employee_rfid`
--

INSERT INTO `employee_rfid` (`employee_id`, `rfid_serial`) VALUES
(1, '0A-FA-B7-25');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD CONSTRAINT `employee_attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `employee_rfid`
--
ALTER TABLE `employee_rfid`
  ADD CONSTRAINT `employee_rfid_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
