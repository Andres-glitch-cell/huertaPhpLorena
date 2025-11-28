-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Temps de generació: 26-11-2025 a les 00:54:27
-- Versió del servidor: 10.4.28-MariaDB
-- Versió de PHP: 8.2.4

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO'; -- Corregido: cambiadas " " por ' '
START TRANSACTION;
SET time_zone = '+00:00'; -- Corregido: cambiadas " " por ' '


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `huerta_db`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `cultivos`
--

CREATE TABLE `cultivos` (
  `nombre` varchar(100) NOT NULL,
  `dias_cosecha` mediumint(11) NOT NULL,
  `id` int(11) NOT NULL,
  `ciclo_cultivos` enum('Corto','Medio','Tardío') NOT NULL,
  `tipo` enum('Hortaliza','Fruto','Aromática','Legumbre','Tubérculo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `cultivos`
--

INSERT INTO `cultivos` (`nombre`, `dias_cosecha`, `id`, `ciclo_cultivos`, `tipo`) VALUES
('Tomate', 45, 1, 'Medio', 'Fruto'),
('Pimiento', 69, 2, 'Tardío', 'Fruto'),
('Lechuga', 11, 3, 'Corto', 'Hortaliza'),
('Guisantes', 43, 5, 'Medio', 'Legumbre'),
('Puerro', 32, 6, 'Medio', 'Hortaliza');

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `cultivos`
--
ALTER TABLE `cultivos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `cultivos`
--
ALTER TABLE `cultivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;