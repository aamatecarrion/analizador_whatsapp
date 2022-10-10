-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2022 at 09:44 PM
-- Server version: 8.0.29-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whatsapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id_chat` int NOT NULL,
  `id_usuario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nombre` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_subida` datetime DEFAULT NULL,
  `numero_mensajes` int DEFAULT NULL,
  `numero_participantes` int DEFAULT NULL,
  `mensajes_dia` int DEFAULT NULL,
  `primer_mensaje` datetime DEFAULT NULL,
  `ultimo_mensaje` datetime DEFAULT NULL,
  `m_lunes` int DEFAULT NULL,
  `m_martes` int DEFAULT NULL,
  `m_miercoles` int DEFAULT NULL,
  `m_jueves` int DEFAULT NULL,
  `m_viernes` int DEFAULT NULL,
  `m_sabado` int DEFAULT NULL,
  `m_domingo` int DEFAULT NULL,
  `m0` int DEFAULT NULL,
  `m1` int DEFAULT NULL,
  `m2` int DEFAULT NULL,
  `m3` int DEFAULT NULL,
  `m4` int DEFAULT NULL,
  `m5` int DEFAULT NULL,
  `m6` int DEFAULT NULL,
  `m7` int DEFAULT NULL,
  `m8` int DEFAULT NULL,
  `m9` int DEFAULT NULL,
  `m10` int DEFAULT NULL,
  `m11` int DEFAULT NULL,
  `m12` int DEFAULT NULL,
  `m13` int DEFAULT NULL,
  `m14` int DEFAULT NULL,
  `m15` int DEFAULT NULL,
  `m16` int DEFAULT NULL,
  `m17` int DEFAULT NULL,
  `m18` int DEFAULT NULL,
  `m19` int DEFAULT NULL,
  `m20` int DEFAULT NULL,
  `m21` int DEFAULT NULL,
  `m22` int DEFAULT NULL,
  `m23` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int NOT NULL,
  `id_chat` int DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_participante` int DEFAULT NULL,
  `contenido` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meses`
--

CREATE TABLE `meses` (
  `id_mes` int NOT NULL,
  `id_chat` int DEFAULT NULL,
  `mes` varchar(13) DEFAULT NULL,
  `numero_mensajes_mes` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participantes`
--

CREATE TABLE `participantes` (
  `id_participante` int NOT NULL,
  `nombre` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_chat` int DEFAULT NULL,
  `participacion` int DEFAULT NULL,
  `numero_mensajes` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` varchar(20) NOT NULL,
  `pass` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `nombre` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `total_mensajes` int DEFAULT NULL,
  `ultimo_chat` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id_chat`),
  ADD UNIQUE KEY `id_usuario_3` (`id_usuario`,`nombre`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_chat` (`id_chat`),
  ADD KEY `participante` (`id_participante`);

--
-- Indexes for table `meses`
--
ALTER TABLE `meses`
  ADD PRIMARY KEY (`id_mes`),
  ADD UNIQUE KEY `id_chat_2` (`id_chat`,`mes`),
  ADD KEY `id_chat` (`id_chat`);

--
-- Indexes for table `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id_participante`),
  ADD UNIQUE KEY `nombre` (`nombre`,`id_chat`),
  ADD KEY `id_chat` (`id_chat`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `ultimo_chat` (`ultimo_chat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id_chat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meses`
--
ALTER TABLE `meses`
  MODIFY `id_mes` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id_participante` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_chat`) REFERENCES `chats` (`id_chat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_participante`) REFERENCES `participantes` (`id_participante`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meses`
--
ALTER TABLE `meses`
  ADD CONSTRAINT `meses_ibfk_1` FOREIGN KEY (`id_chat`) REFERENCES `chats` (`id_chat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `participantes`
--
ALTER TABLE `participantes`
  ADD CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`id_chat`) REFERENCES `chats` (`id_chat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`ultimo_chat`) REFERENCES `chats` (`id_chat`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
