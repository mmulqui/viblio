CREATE DATABASE  IF NOT EXISTS `viblio_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `viblio_db`;
-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: viblio_db
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alumno`
--

DROP TABLE IF EXISTS `alumno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alumno` (
  `id_alumno` int NOT NULL AUTO_INCREMENT,
  `numero_prestamos` int unsigned DEFAULT NULL,
  `numero_multas` int unsigned DEFAULT NULL,
  `usuario_id_usuario` int NOT NULL,
  PRIMARY KEY (`id_alumno`,`usuario_id_usuario`),
  KEY `fk_alumno_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_alumno_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumno`
--

LOCK TABLES `alumno` WRITE;
/*!40000 ALTER TABLE `alumno` DISABLE KEYS */;
INSERT INTO `alumno` VALUES (16,0,0,18),(17,0,0,20),(18,0,0,24),(19,0,0,25),(20,0,0,26),(21,0,0,27);
/*!40000 ALTER TABLE `alumno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id_auditoria` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `modulo` varchar(50) NOT NULL DEFAULT 'Sistema',
  `accion` varchar(255) NOT NULL,
  `detalle` text,
  `ip` varchar(45) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id_auditoria`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,9,'Sistema','login_exitoso','','::1','2026-09-04 06:39:05'),(2,9,'Sistema','login_exitoso','','::1','2026-09-04 20:20:20'),(3,NULL,'Sistema','registro_usuario','email: a@gmail.com','::1','2026-09-04 20:21:35'),(4,9,'Sistema','baja_usuario','dni: 40400400','::1','2026-09-04 20:22:03'),(5,9,'Sistema','modificar_usuario','id editado: 20','::1','2026-09-04 20:23:39'),(6,20,'Sistema','login_exitoso','','::1','2026-09-04 20:31:46'),(7,20,'Sistema','login_exitoso','','::1','2026-09-04 21:17:54'),(8,9,'Sistema','login_exitoso','','::1','2026-09-04 23:15:28'),(9,9,'Sistema','login_exitoso','','::1','2026-09-12 02:07:10'),(10,9,'Sistema','login_exitoso','','::1','2026-09-14 02:15:24'),(11,9,'Sistema','login_exitoso','','::1','2026-09-14 03:09:06'),(12,9,'Sistema','login_exitoso','','::1','2026-09-15 23:03:31'),(13,9,'Sistema','login_exitoso','','::1','2026-09-16 00:28:05'),(14,9,'Sistema','login_exitoso','','::1','2026-09-16 00:46:32'),(15,NULL,'Sistema','registro_usuario','email: prueba@gmail.com','::1','2026-09-16 01:30:07'),(16,9,'Sistema','modificar_usuario','id editado: 26','::1','2026-09-16 01:30:23'),(17,26,'Sistema','login_exitoso','','::1','2026-09-16 01:30:56'),(18,9,'Sistema','login_exitoso','','::1','2026-09-16 01:32:46'),(19,9,'Sistema','modificar_usuario','id editado: 26','::1','2026-09-16 01:33:08'),(20,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-17 06:45:15'),(21,9,'Usuarios','modificar_usuario','rol: \"profesor\" -> \"alumno\" (usuario id: 19)','::1','2026-09-17 06:45:44'),(22,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-17 22:33:51'),(23,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-18 01:51:38'),(24,9,'Usuarios','modificar_usuario','rol: \"alumno\" -> \"bibliotecario\" (usuario id: 19, email: jonasalejandrovera123@hotmail.com)','::1','2026-09-18 01:51:46'),(25,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-18 07:05:35'),(26,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-18 22:29:34'),(27,9,'Usuarios','modificar_usuario','rol: \"bibliotecario\" -> \"alumno\" (usuario id: 20, email: salmasanchez@hotmail.com)','::1','2026-09-18 22:45:03'),(28,9,'Usuarios','modificar_usuario','rol: \"bibliotecario\" -> \"alumno\" (usuario id: 19, email: jonasalejandrovera123@hotmail.com)','::1','2026-09-18 22:45:17'),(29,9,'Usuarios','modificar_usuario','nombre: \"prueba\" -> \"Juan\" (usuario id: 26, email: prueba@gmail.com)','::1','2026-09-18 22:45:33'),(30,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-19 00:28:23'),(31,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-19 01:12:35'),(32,9,'Usuarios','modificar_usuario','apellido: \"asd\" -> \"Perez\" (usuario id: 26, email: prueba@gmail.com)','::1','2026-09-19 01:24:56'),(33,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-19 03:34:50'),(34,NULL,'Usuarios','registro_usuario','email: script@gmail.com','::1','2026-09-19 03:40:15'),(35,9,'Usuarios','modificar_usuario','Sin cambios detectados (usuario id: 27, email: script@gmail.com)','::1','2026-09-19 03:43:38'),(36,9,'AutenticaciÃ³n','login_fallido','contraseÃ±a incorrecta','::1','2026-09-19 03:55:38'),(37,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-19 03:55:46'),(38,9,'AutenticaciÃ³n','login_fallido','contraseÃ±a incorrecta','::1','2026-09-29 04:32:22'),(39,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-29 04:32:30'),(40,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-29 23:24:03'),(41,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-30 00:27:17'),(42,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-09-30 02:33:48'),(43,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-01 03:56:15'),(44,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-01 04:08:48'),(45,9,'Usuarios','modificar_usuario','rol: \"alumno\" -> \"bibliotecario\" (usuario id: 20, email: salmasanchez@hotmail.com)','::1','2026-10-01 04:11:11'),(46,20,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-01 04:11:36'),(47,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-02 23:42:08'),(48,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-13 06:21:50'),(49,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-13 22:39:08'),(50,9,'PrÃ©stamos','registrar_prestamo','libro: Harry Potter y la piedra filosofal (ISBN 978074753) | usuario: Denis Gomez (profesor, DNI 33333333)','::1','2026-10-13 22:56:16'),(51,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-14 00:25:58'),(52,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-14 01:13:11'),(53,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-14 02:23:29'),(54,9,'AutenticaciÃ³n','logout','','::1','2026-10-14 02:32:58'),(55,19,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-14 02:33:04'),(56,19,'AutenticaciÃ³n','logout','','::1','2026-10-14 02:35:52'),(57,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-14 02:35:58'),(58,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-16 04:04:52'),(59,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-16 05:42:29'),(60,9,'PrÃ©stamos','devolver_prestamo','prÃ©stamo id: 5 | libro: Harry Potter y la piedra filosofal (ISBN 978074753) | usuario: Denis Gomez (profesor, DNI 33333333) | estado del libro: bueno','::1','2026-10-16 05:43:11'),(61,9,'Reservas','cancelar_reserva','reserva id: 3','::1','2026-10-16 06:06:53'),(62,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-16 20:29:19'),(63,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-16 21:16:16'),(64,9,'Libros','agregar_stock','1984 (ISBN 978045) | +10 ejemplar(es) (stock 1 â†’ 11) | procedencia: Donacion de un alumno','::1','2026-10-16 21:24:29'),(65,9,'PrÃ©stamos','registrar_prestamo','libro: 1984 (ISBN 978045) | usuario: Jonas Vera (alumno, DNI 44224952)','::1','2026-10-16 21:25:08'),(66,9,'PrÃ©stamos','devolver_prestamo','prÃ©stamo id: 6 | libro: 1984 (ISBN 978045) | usuario: Jonas Vera (alumno, DNI 44224952) | estado del libro: rayado','::1','2026-10-16 21:25:30'),(67,9,'Multas','generar_multa','multa id: 1 | usuario: Jonas Vera (alumno, DNI 44224952) | motivo: rayado | 7 dÃ­a(s) | prÃ©stamo id: 6','::1','2026-10-16 21:25:30'),(68,9,'PrÃ©stamos','prestamo_rechazado','libro: 1984 (ISBN 978045) | usuario: Jonas Vera (alumno, DNI 44224952) | motivo: El usuario tiene una sanciÃ³n vigente hasta el 23/10/2026 16:25 y no puede pedir libros.','::1','2026-10-16 21:25:55'),(69,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-20 00:41:51'),(70,9,'AutenticaciÃ³n','logout','','::1','2026-10-20 00:42:05'),(71,19,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-20 00:42:11'),(72,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-10-27 23:28:31'),(73,9,'PrÃ©stamos','registrar_prestamo','libro: The Pragmatic Programmer (ISBN 9780135957059) | usuario: Jonas Vera (alumno, DNI 44224952)','::1','2026-10-27 23:33:18'),(74,9,'PrÃ©stamos','devolver_prestamo','prÃ©stamo id: 7 | libro: The Pragmatic Programmer (ISBN 9780135957059) | usuario: Jonas Vera (alumno, DNI 44224952) | estado del libro: bueno','::1','2026-10-27 23:51:18'),(75,9,'PrÃ©stamos','registrar_prestamo','libro: Code Complete (ISBN 9780735619678) | usuario: Daiara Mulqui (alumno, DNI 40626704)','::1','2026-10-27 23:51:51'),(76,9,'PrÃ©stamos','devolver_prestamo','prÃ©stamo id: 8 | libro: Code Complete (ISBN 9780735619678) | usuario: Daiara Mulqui (alumno, DNI 40626704) | estado del libro: rayado','::1','2026-10-27 23:52:09'),(77,9,'Multas','generar_multa','multa id: 2 | usuario: Daiara Mulqui (alumno, DNI 40626704) | motivo: rayado | 7 dÃ­a(s) | prÃ©stamo id: 8','::1','2026-10-27 23:52:09'),(78,9,'PrÃ©stamos','prestamo_rechazado','libro: Clean Code (ISBN 9780132350884) | usuario: Daiara Mulqui (alumno, DNI 40626704) | motivo: El usuario tiene una sanciÃ³n vigente hasta el 03/11/2026 19:52 y no puede pedir libros.','::1','2026-10-27 23:52:35'),(79,9,'Multas','anular_multa','multa id: 2 | usuario: Daiara Mulqui (alumno, DNI 40626704) | motivo: rayado | 7 dÃ­a(s)','::1','2026-10-27 23:52:59'),(80,9,'Libros','agregar_stock','Code Complete (ISBN 9780735619678) | +10 ejemplar(es) (stock 1 â†’ 11) | procedencia: Por donacion de un alumno','::1','2026-10-27 23:54:05'),(81,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-04 07:02:22'),(82,9,'AutenticaciÃ³n','logout','','::1','2026-11-04 07:13:35'),(83,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-04 07:13:40'),(84,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-06 21:01:25'),(85,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-10 21:46:38'),(86,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-10 23:45:30'),(87,9,'Libros','agregar_stock','The Pragmatic Programmer (ISBN 9780135957059) | +10 ejemplar(es) (stock 1 â†’ 11) | motivo: Compra | con boleta | ingreso id: 1','::1','2026-11-10 23:45:57'),(88,9,'Libros','agregar_stock','Fahrenheit 451 (ISBN 9781451673319) | +10 ejemplar(es) (stock 1 â†’ 11) | motivo: Compra | con boleta | procedencia: se hizo una compra nueva | ingreso id: 2','::1','2026-11-10 23:52:00'),(89,9,'AutenticaciÃ³n','logout','','::1','2026-11-11 01:30:38'),(90,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-11 01:30:46'),(91,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-11 01:41:26'),(92,24,'AutenticaciÃ³n','logout','','::1','2026-11-11 01:49:45'),(93,24,'AutenticaciÃ³n','login_exitoso','','192.168.0.11','2026-11-11 01:50:29'),(94,24,'PrÃ©stamos','solicitar_prestamo','libro: Harry Potter y la piedra filosofal (ISBN 978074753) | usuario: Daiara Mulqui (alumno, DNI 40626704)','192.168.0.11','2026-11-11 02:07:52'),(95,24,'AutenticaciÃ³n','logout','','192.168.0.11','2026-11-11 02:10:39'),(96,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-11 02:13:00'),(97,24,'AutenticaciÃ³n','logout','','::1','2026-11-11 02:16:57'),(98,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-13 22:04:19'),(99,9,'AutenticaciÃ³n','logout','','::1','2026-11-13 22:20:07'),(100,24,'AutenticaciÃ³n','login_exitoso','','192.168.0.11','2026-11-13 22:38:16'),(101,24,'AutenticaciÃ³n','logout','','192.168.0.11','2026-11-13 22:38:39'),(102,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-13 22:42:39'),(103,9,'AutenticaciÃ³n','logout','','::1','2026-11-13 22:43:07'),(104,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-13 22:43:41'),(105,9,'AutenticaciÃ³n','logout','','::1','2026-11-13 22:44:33'),(106,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-13 22:44:43'),(107,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-13 22:45:40'),(108,24,'AutenticaciÃ³n','logout','','::1','2026-11-13 22:47:41'),(109,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-13 23:03:11'),(110,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-13 23:42:42'),(111,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 03:13:27'),(112,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 03:34:03'),(113,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 03:41:31'),(114,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 03:42:13'),(115,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 03:46:22'),(116,24,'AutenticaciÃ³n','logout','','::1','2026-11-14 03:46:55'),(117,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 05:55:03'),(118,9,'AutenticaciÃ³n','logout','','::1','2026-11-14 05:55:23'),(119,21,'AutenticaciÃ³n','login_fallido','contraseÃ±a incorrecta','::1','2026-11-14 05:55:29'),(120,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 05:55:38'),(121,24,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 05:56:33'),(122,24,'AutenticaciÃ³n','logout','','::1','2026-11-14 05:57:21'),(123,9,'AutenticaciÃ³n','login_exitoso','','::1','2026-11-14 05:57:30');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autor`
--

DROP TABLE IF EXISTS `autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autor` (
  `id_autor` int NOT NULL AUTO_INCREMENT,
  `foto` blob,
  `nombre` varchar(50) NOT NULL,
  `nacionalidad` varchar(50) DEFAULT NULL,
  `biografia` varchar(1000) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_autor`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autor`
--

LOCK TABLES `autor` WRITE;
/*!40000 ALTER TABLE `autor` DISABLE KEYS */;
INSERT INTO `autor` VALUES (1,NULL,'J. R. R. Tolkien',NULL,NULL,1),(2,NULL,'J. R. R. Tolkien',NULL,NULL,1),(3,NULL,'J. R. R. Tolkien',NULL,NULL,1),(4,NULL,'J. R. R. Tolkien',NULL,NULL,1),(5,NULL,'yo',NULL,NULL,1),(6,NULL,'George Orwell',NULL,NULL,1),(7,NULL,'undefined',NULL,NULL,1),(8,NULL,'Gabriel GarcÃ­a MÃ¡rquez',NULL,NULL,1),(9,NULL,'qsy',NULL,NULL,1),(10,NULL,'asdasd',NULL,NULL,1),(11,NULL,'Robert C. Martin',NULL,NULL,1),(12,NULL,'Andrew Hunt, David Thomas',NULL,NULL,1),(13,NULL,'Steve McConnell',NULL,NULL,1),(14,NULL,'Ray Bradbury',NULL,NULL,1),(15,NULL,'Antoine de Saint-ExupÃ©ry',NULL,NULL,1),(16,NULL,'Yuval Noah Harari',NULL,NULL,1);
/*!40000 ALTER TABLE `autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autor_tmp`
--

DROP TABLE IF EXISTS `autor_tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autor_tmp` (
  `id_autor` int NOT NULL AUTO_INCREMENT,
  `foto` blob,
  `nombre` varchar(50) NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `biografia` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autor_tmp`
--

LOCK TABLES `autor_tmp` WRITE;
/*!40000 ALTER TABLE `autor_tmp` DISABLE KEYS */;
/*!40000 ALTER TABLE `autor_tmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bibliotecario`
--

DROP TABLE IF EXISTS `bibliotecario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bibliotecario` (
  `id_biblitecario` int NOT NULL AUTO_INCREMENT,
  `turno` varchar(25) DEFAULT NULL,
  `usuario_id_usuario` int NOT NULL,
  PRIMARY KEY (`id_biblitecario`,`usuario_id_usuario`),
  KEY `fk_bibliotecario_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_bibliotecario_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bibliotecario`
--

LOCK TABLES `bibliotecario` WRITE;
/*!40000 ALTER TABLE `bibliotecario` DISABLE KEYS */;
INSERT INTO `bibliotecario` VALUES (1,NULL,9),(2,'MaÃ±ana',19),(3,'tarde',21),(4,'MaÃ±ana',22),(5,'tarde',23);
/*!40000 ALTER TABLE `bibliotecario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'FantasÃ­a',1),(2,'FantasÃ­a',1),(3,'FantasÃ­a',1),(4,'FantasÃ­a',1),(5,'FantasÃ­a',1),(6,'Novela',1),(7,'terro',1),(8,'FicciÃ³n literaria',1),(9,'asdasd',1),(10,'asdasdasd',1),(11,'Libro tÃ©cnico',1),(12,'Libro tÃ©cnico',1),(13,'Libro tÃ©cnico',1),(14,'Novela',1),(15,'Novela corta',1),(16,'Ensayo',1);
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `editorial`
--

DROP TABLE IF EXISTS `editorial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `editorial` (
  `id_editorial` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `sitioweb` varchar(250) DEFAULT NULL,
  `id_pais` int DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_editorial`),
  KEY `id_pais` (`id_pais`),
  CONSTRAINT `editorial_ibfk_1` FOREIGN KEY (`id_pais`) REFERENCES `pais` (`id_pais`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editorial`
--

LOCK TABLES `editorial` WRITE;
/*!40000 ALTER TABLE `editorial` DISABLE KEYS */;
INSERT INTO `editorial` VALUES (1,'Minotauro',NULL,NULL,1),(2,'Minotauro',NULL,NULL,1),(3,'Minotauro',NULL,NULL,1),(4,'Minotauro',NULL,NULL,1),(5,'Bloomsbury',NULL,NULL,1),(6,'Secker & Warburg',NULL,NULL,1),(7,'undefined',NULL,NULL,1),(8,' Editorial Sudamericana',NULL,NULL,1),(9,'sada',NULL,NULL,1),(10,'asdasdasd',NULL,NULL,1),(11,'Prentice Hall',NULL,NULL,1),(12,'Addison-Wesley',NULL,NULL,1),(13,'Microsoft Press',NULL,NULL,1),(14,'Simon & Schuster',NULL,NULL,1),(15,'Harcourt',NULL,NULL,1),(16,'Harper',NULL,NULL,1);
/*!40000 ALTER TABLE `editorial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `descrpcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado`
--

LOCK TABLES `estado` WRITE;
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_prestamo`
--

DROP TABLE IF EXISTS `estado_prestamo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_prestamo` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_prestamo`
--

LOCK TABLES `estado_prestamo` WRITE;
/*!40000 ALTER TABLE `estado_prestamo` DISABLE KEYS */;
INSERT INTO `estado_prestamo` VALUES (1,'activo'),(2,'devuelto'),(3,'vencido');
/*!40000 ALTER TABLE `estado_prestamo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_reserva`
--

DROP TABLE IF EXISTS `estado_reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_reserva` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_reserva`
--

LOCK TABLES `estado_reserva` WRITE;
/*!40000 ALTER TABLE `estado_reserva` DISABLE KEYS */;
INSERT INTO `estado_reserva` VALUES (1,'pendiente'),(2,'cumplida'),(3,'cancelada');
/*!40000 ALTER TABLE `estado_reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero`
--

DROP TABLE IF EXISTS `genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero` (
  `id_genero` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_genero`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'Ã‰pico',NULL,1),(2,'Ã‰pico',NULL,1),(3,'Ã‰pico',NULL,1),(4,'Ã‰pico',NULL,1),(5,'Aventura',NULL,1),(6,'DistopÃ­a',NULL,1),(7,'undefined',NULL,1),(8,'Realismo mÃ¡gico',NULL,1),(9,'asdasd',NULL,1),(10,'asdasdasd',NULL,1),(11,'ProgramaciÃ³n',NULL,1),(12,'ProgramaciÃ³n',NULL,1),(13,'IngenierÃ­a de software',NULL,1),(14,'Ciencia ficciÃ³n',NULL,1),(15,'FÃ¡bula',NULL,1),(16,'Historia',NULL,1);
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingreso_stock`
--

DROP TABLE IF EXISTS `ingreso_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingreso_stock` (
  `id_ingreso` int NOT NULL AUTO_INCREMENT,
  `id_libro` int DEFAULT NULL,
  `titulo_libro` varchar(100) NOT NULL,
  `isbn` varchar(17) NOT NULL,
  `cantidad` int unsigned NOT NULL,
  `stock_anterior` int unsigned NOT NULL,
  `stock_nuevo` int unsigned NOT NULL,
  `motivo` enum('compra','donacion','otro') NOT NULL DEFAULT 'otro',
  `procedencia` varchar(255) DEFAULT NULL,
  `boleta` mediumblob,
  `boleta_tipo` varchar(50) DEFAULT NULL,
  `id_registrador` int DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ingreso`),
  KEY `idx_ingreso_libro` (`id_libro`),
  KEY `idx_ingreso_fecha` (`fecha`),
  KEY `fk_ingreso_registrador` (`id_registrador`),
  CONSTRAINT `fk_ingreso_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE SET NULL,
  CONSTRAINT `fk_ingreso_registrador` FOREIGN KEY (`id_registrador`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingreso_stock`
--

LOCK TABLES `ingreso_stock` WRITE;
/*!40000 ALTER TABLE `ingreso_stock` DISABLE KEYS */;
INSERT INTO `ingreso_stock` VALUES (1,9,'The Pragmatic Programmer','9780135957059',10,1,11,'compra',NULL,_binary 'ÿ\Øÿ\à\0JFIF\0\0`\0`\0\0ÿş\0;CREATOR: gd-jpeg v1.0 (using IJG JPEG v80), quality = 82\nÿ\Û\0C\0				\r\r\n\Z!\'\"#%%%),($+!$%$ÿ\Û\0C				$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ÿÀ\0(\0\ä\"\0ÿ\Ä\0\0\0\0\0\0\0\0\0\0\0	\nÿ\Ä\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁR\Ñğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹º\Â\Ã\Ä\Å\Æ\Ç\È\É\Ê\Ò\Ó\Ô\Õ\Ö\×\Ø\Ù\Ú\á\â\ã\ä\å\æ\ç\è\é\êñòóôõö÷øùúÿ\Ä\0\0\0\0\0\0\0\0	\nÿ\Ä\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3Rğbr\Ñ\n$4\á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹º\Â\Ã\Ä\Å\Æ\Ç\È\É\Ê\Ò\Ó\Ô\Õ\Ö\×\Ø\Ù\Ú\â\ã\ä\å\æ\ç\è\é\êòóôõö÷øùúÿ\Ú\0\0\0?\0÷oxV\ÚO\ÜøcBÿ\0\ØÓ¡Oı’¶,\ímm·¥­½¼	»\î\Å§şƒDq?™÷*x\âŸ\äş*\È\Ğ™şı7\Í\ï¿ıõHÑ½\"\Äò\Ò ]\ïı÷ÿ\0¾¨\Şÿ\0\ßû\ê\äRıš¬o\ï¿ıõF÷şûÿ\0\ßU/•G‘ş\İ\0Cÿ\0}ÿ\0\ßTmÿ\0n¦ò?Û§yu˜Yi¬µs\ÉJ<” ÿ\0#ıº>\ÍZJQ\ä¥”¼¯¹I\ä·W¼º<ºd|\îR5²U¼%&\ê°*4}™*\ÓH”y‰PQhŸ\ÇK\ä[ÜŸ~9?Ø«·\Ù(X?Û©üÄ©(Š¥U§Qº€\r\ÔÖ—\îS%o¿P\ÊßºJ\Ê\\\r\Å\È\Õ-Eûõ$M÷ÿ\0Ş¦Eşª–/\ãÿ\0z\rR­@#ı\ëÔ´&\ê7U6›\æ¿÷(\Ö\ê*·šô\İ\ïòPº7UO5\ékıú\0½ESûK\Ğ%z\0¹ES\Şÿ\0#Õ•’€Ê”¦Tù*Uÿ\0ƒø©¦?¸ôedJ\äó(j\rßºtş:\0‘™)\ì•ûô¿?—ş\Ş\Ú\0U\ÙG\É÷)¢Dÿ\0\Ñ\Ê\0“\ËH¾z_7ıªŠo\àÿ\0z˜g\Üù(Ç›ş\Õ\"Ê•ÿ\0cû´E¿øşú\Ö`K\'ú§¨\Ïú´©OñTGıZUL½(¡zQP@±R\Åü\ïREI±\é–7\ï}857\Ëz\Ï@¢°u_\é:>¯i¢\Ş\Ş\Å¡w\åıaºmÏ·\å\ìÿ\0û5{Z\ÖaĞ´\Ùu\Ùv\ÚCµ¥m»¶.\í»¨BŠ¡¥jğ\ë–I{e*5»3¢7÷ö¾\İ\ßø\åPµñf™®Ë¢Á>\ëø<\Ï6\ã‰o\Ì\ß\İİ½vÿ\0{\æş\ëP\î\Ú6\Ö>¡¯Ie©[\é\ég=\İ\Ä\ĞOr‘\ÆÈ»\ÖŠ\Ëóº|\Ìò¢¯ğÿ\0x­ajŸ\ítxæ¹½\Ñuˆ­\áóGŸ²-¯*@Ó´?v\íªË»n\Í\Ë÷¾\í\Ú6\×;ÿ\0	”?ğ’\Â?ö+¶\íVş\rŸ2³}ı\ß\ìwûµ…\ã›/\é·z•\Â\Û\Úwgdù·B“|»Y·|®¿ğ/û\ês#«\ÛI\\\Úø©>Ã£\İ+†ş\ÕtŠ\ÙU¢\ÊnF›\ç\Ûòª?\İfÿ\0cuCl®­¯fû%\Úı‹b\Ëlİ¹¦x¿·\ï#ÿ\0û-\Ş\çO$”\ß7ıš\ç´_Ã®É§\Ã\r•\Ä\Ú6O¨@\Î\ĞüÈŠ\Ù\n\ìÛ¿x¿\ìÿ\0µ]ıñL÷şJg™ü{k¼PƒH»Õ“Mº{Kf˜>Æˆ³ù.\Èì‹¿ı‡o\ïmÿ\0kåªŸğ\ÙKs,6ºm\í\âF—.³ÀĞˆ] 1«°,\ëüO³œrü&À\éšD‹e5eşıq÷tÈ´Ÿ\íi´ıKû?t£\Íı\Ï\Î\É\ÎË³~ï¸ÿ\0ÿ\0¾«b\ï\ÄsZ\Ü\Ú[M¢\êh½gHcFƒ{\íM\Íü{hg\Íÿ\0b…dûû+Ÿ²ñc\ßJğÃ¡j»\áš(f\ÊCşŠ\îˆÿ\0:¬›¾Utİ·ul^_C¦Fu*@,q+?\Ü\ŞÏµWşÎ«RYdHŸse1¤O¸‰QZ]¥õ²]Zº4S.ôû\é½?½U¬5›-NW‚\Ê\ê\Şw_¿\Z7ÎŸ3/\Íı\ÖŞ»\Øj/,‰÷6Ô»“\Íû•™©kšf™\"&¡{olò¦öûÿ\02§\Ëı\æ\Ü\è»\ÛZ\Óò\ß\ï\ìOû\ê‚õt\èÿ\0Õ¥&\×ÿ\0b¿À”%QA\ÅR\ÔQT~k\ÅòUKKMù\èù\ê@\çu¿\é>!\Õ\í5kØ®ì¼¯³²²ş\çk\ïù>O\âû­ş\Í]\×ôo\é\Ze\ë»\Ú\Ü\íIUvüÉ¿vß˜\İÿ\0\ĞvÖ–\ç®s\Ç\ï4Z$H—l“_Y[\Í:>\Ã\å=\Â+®\ï\á\Üoü€44é©§\é\é\åZ¬²2GµWb3³mM©÷Wv\Õÿ\0f²t\ß\0i:G‰%ñ”O¡w\ç5Ä›3o\Ù÷¾M\Ümùvÿ\0}·n\ßYÚ•\Ïú5¿…¼9­\\Oq{s2Kr·t–0£)›\çûÛ—rB»·2³¯÷+šğö·¬\ßx¢\ËBÕ®®\'´½\Ô5g´–)\Ş=ğ¬\Ó#\ÂÎ­÷‘•ı•u\Ù÷)\Ş\ëú­}«iú¶“{kcwioqf\ÍqnfM“l,\Û—\æV…?‹oŞ®_WøAk©\Û]£ß§ö\ì\×É¨=®\çt–\Ù\àÛµ]Wø÷|»wlûŸ\ÅU4(ûMğ½–§ª\êv\Ö÷º]\î¡q*\ê3#´\ê\Ğ*¿›¿Ê®\í·v\ß\âş\n\Ç\×õMB-U\ÕôıU¹¸[‹\Ë1¯‡šcº7’>A¹\Ñzª·Ï»ø¨\æ¦»ø?¤]Kwe4VK¢Iq-\ÊYEjc\Í\Âÿ\07ğüË½º\Ç\ÑŸcğ\ïV±Ô®.¦´ğ½\İ\Å\ìP\Äú‚Y²\\\Û\â\Ù u‹®b+ş³[ÿ\0nuo3G\Òl¿µv]M\'ı‘4P]2¢nfUQœù¿ƒoñ\×9\á­CP¾ş\Í\×5kû»Ï´=Š<¶š“!²\Ñ?s=ª?’\ê\íó\î_›÷¿uS\rLŒxûD\Ñ\áĞ­ô«mCL¸‚fe·òb»‘cd}û>l²;\í?6k\×\áÿ\0ˆ\ìb¸µIt«˜µ6†]BW’Xš\'[™¦›\É@­½[\ÍÚ»™[\åù‹WCñûR¶²\Ómtô\Ôõå¶•lR\á\Óc»*;ºª3lÛ»r²®vó¶²şëº¶¹s©ÿ\0i\Ü\\Nöˆ‘yW›\Ñ\Ò{”;\Ñ>Mÿ\0\"n\ÛüIL	üğıü|“Z¦Ÿş—f‘_4Pli§Fù]\ŞG}ÿ\0\î\'ûu\Ú7Ÿ^_m>§/†ô{\×ñ%\ï\ÚõÏ³ı¦\Û\íCqk¸U\Ş/“t;MŸ.İ»\Óø\ÕZ²®õŸ\İcL†÷S-Ÿ\ìvw?oØ¶.ó:Bó7\ŞuÈ¯»smO›w\ÍRO§ø+Z\Ó,u‹k+/\Ú]\Ş\ËvË«D\ï\çº\Í3:³¯“÷•÷›\æE£LøWmm}kk¨X\éZ‡‡ôä½‹O¶¸ƒ\ÍxRv\ÕY]6ü®—ş\ë§ûUµ¨\ê\ZÏŠ§²¸¸•\í’Ù­ \éRI•\Ù\ßo÷v#|»“\äûõ³\àk\íNO\í3P¸¾ºş\Î\Ô>\É÷{<÷\ìğºù›>Fo\Ş›û»wüôD{\\øM5ôº\îúb\Ş\ê1j6\ï#£&Ô¹ƒj7r£ş\0\Ïş\íi\É\á\íL\é\ß\ÙñøGÁğZùşsÙ­\Ã\ìv\Ù÷\Ñ\Ò\Ø\ê\Û>m­òó¹MwI4Q»¤^{ª»,h\ß;ÿ\0³óU]/P½¿‰\Ş\ëH¸\Ó9\Â-\Ó\Â\îÿ\0\í~\å\Ùj€\ãm|\'\âx¯t‡™´Æ–\Ã\ì©.¬—S©£Aó\Â\é·\çV}ûw6\Õß»fõ\çw\Åş‡ÆºGöN¡·\ìŸhŠYp¿|+nd\İü;—\åÜ¿\Â\í\\®«¨\ëV¿\á}Cû>=F\Ò\Ş]—§\ÉD–]Ÿgo”\ï}Ÿ2ıß½ıüÏ¦Ik}e\Û5	V\Ïb\Ëqtò\î·K{–Uù·q\ŞØ»\é\ØøkEOh–šJ?–‘$K&Íû‰\ÑkÂŸl<!«\ê\Zµ´»§\Ôr÷8M››\ÌwV_Ÿ\åUGÛ·ı•o½Y.\Öõ\İ3\ÆÈ–_kûH32\Ü\"\Ã\nMw433£}\íÈ¡~_¹òµ`\Øx\ë\Äw\×:z}«PŠ-y!—÷\Ö^RZn¹…-\ßn\çWI~ónù“wñ\í \Û\Æl¼k©Y^\Ü\İ<\Ø\Û\áw¨5q]ü¯\È\ë´ÿ\0ı…®À-s^¸½—M¾†ö\á\î^\ËP¸¶Iem\Î\è¯\Æ\æ\Ûó2¯Ë»ıš\é·?÷(\Ô\ÆZgšÿ\0Ü©Ÿº\ßZ%,?\êÖŠ\Ì\n±ÿ\0­ªš®§k£Ø½\íïš¶û\Ñd+üÍµ~UVo½W\"ÿ\0[Y>/\Ó.u\İ\ïOµò¼\Ù=¾k²/\Ê\ê\Íó¯û+Z=†¹k}sqm\Ú[yR)·@\êˆYU\×\çe\Ú\ß+\'İ¥\Õuˆt+\Ïy\Ò\åX¡X\áy]\ßk6İˆ­üÿ\07\İùkKğ›\Úø§W\×f¸¹ÿ\0HºIm\â‚öd‰‘`Dmñ+ª;nS÷•¾]¼ÿ\0v-r\ÏP\Ö4ÛŸ¶ønin-/\Ğ\"·\Õ<—u_•góQ•¡ûÏ¸|Í·w\Ş\Î\Ê\0³\'4o7MG½\Ø\×ó=½´RÁ2J\î\ê\ë±\×r\ídûÍµi\×Zÿ\0‡5‰u]\ê[KŸ°D©\ÛL›\á…Y—{\Û÷U¾Z\ä¡øG=ŒZ=\Òj—­ª\Ãs·ì—\å:}¥\ç}›÷3|\î~ón*ƒøªkOƒöÖº–¡\Ûoÿ\0±ç¶·H—\íŸ\ÎIæ™¿‡\îü\ã\ïn\Î÷ßº³+C_Nñƒ\ít\Ô\Ô4È’\r>?5eµ\Ò\æ@–\Ê\íı\ÔùWz7\Ëÿ\0R\ßx«Á_‘{[\íA|\í6m´\íŸfS\æùşö>ö\ï\â\ÍrºŸƒ¼Ai¡g\Ş\ß]Cr÷–š¢j…´¹ûCº<±±\Ó[o\âù\à\ÓüK\áø›W\Ô\ïl´[‹‰´ù\ì\îe¸½†hu(•\Ó\ÉH¾vhY“{nÚ»Yÿ\0‹\ïP3¦´—Á‘i¿fƒJ´‚+K—‰mMtt¸d\ÜÊ\ìİ¹‘÷nUû§û•¤«\á›\í^\â\Éôû[A¡ÿ\0Iÿ\0Bó>FV]²¾Í¿2\î]¬\ßv¼ûşZ\Ş\Üjö\ÚVº»¾\Ü-­ \Õ!ûl/2Z*<³<¸~`v\åŸEù‘ko\Ã\Z‰´q>Ô·ójwr.‘\íg…­ö*¢}ôm\êŸÂ¿*7Ï·\ä¤I5·…¼l—I¥Yiˆ¬Š­g§}\×f\Ûü	ò\ïß·şU­Áò\ê\èöº,K¨Xyq+&–\ë\ä\å¢\ïÙµ~G_÷ÿ\0\rhx\ÇHº\Ôô	l¬“Í–Im\İU™\äY‘›ÿ\0Vªº/†§¶ñ.»«]Oz©uy¶ğ$ÿ\0¹t[Hs§÷·‡ÿ\0¾ıš`o\\C\ÑI\'·F{gó\"ß†\ØûYw/÷[c?ı÷\\\İı§ƒ4\ÏZ%Í¾Ÿmª\ß\îXv~\é\æùÙ›vß½¹ş÷\ßbÕ¹a5\ÕÑ¸{­2\æÅ£šH“\Ït:5û®»mVş\é\ÚÙ®+\Ä\Ş\×u=KXº¶¸x­\î³v@©%Ç“>÷gsó\r¿Áó-\0j\Åk\à\Ïøù:E¾\Æg‹Sÿ\0Cu™·²7Ë»ø÷ü¿\Ş\İZ\Z~‘\áû7É³\Óm\Z\Òx\Ú¯ß™\Ûk\îù™w3¿üuS‡E\Ô?\â°O)ÿ\0\âcw\çZ.ôù\ÇØ ‡şó£¯\Íı\Ê\ØğõŒ\Ö:&Ÿk2m–h\Ñ\×r¶\ÇU]\Ô\Ôü9s%\Â<º{»L¶ò\ïTıë£ªcwñ2»¢ÿ\0²\ÏW\Ø\éú<3ùV\Ï{p»\Ûó¦Ú¨»¿¼\ÛQş\\\Ö:\ìZ#\è©á‹‰e¶»š\ç\íªğ¨\Ú×»ÿ\0uónfdf\İ÷~\ïûk]‹¯.®lt§‡M\Ô\Ùÿ\0´m¥uH7:\":³3ÿ\0ÀV¤\r\ç\Ö4ø­®§{\Ût·´ó>\Ñ;¸T‡g\Ş\Üÿ\0u6ÿ\0=õ2-\î÷öŸ.Ì·Ÿ&ÿ\0¹ÿ\0}÷«‚½\Ğu\Ùt\ß\é\éqªµ\ÅüWÿ\0c´h#û#\ïF\Ùó\ìİ»\æû¬\ÛØ¨\ìü#\âüG§¼Osoö‹´\é÷®÷µI·|Û›\ï¢6\İ\Í÷¶S\æ`uK¡øfû\Ä\ë^RÏª\ÛÊ«/úD°\Í\ä\áw»h)ÿ\0»»\ïU¦ğ_…¬bŠ\ÒX{±4lú¥Ê¿\Úv·Ì¿r»}\Û[‘œîªšR^\Úø\ë[y¥\×`·»¼I­\à\Õ$²?²B›\Ş]…«£|›\×\î¯\Ëós©\ã(%±´½ÿ\0‰\ÇÚ­n<ûo\ìÈ„³ù›6\ìud\ÚÁ\İw?È»÷n_½F¤jQ‹Hğ7\ÛtÛ—½·–\í¶­“Oª<\ÆmŒÌŸy\Û\Î\Ú\Åö\îİ±·m§ø@Í„V¿m\Ñ\Äpˆb\Ùv©\å\ÆfP»\\6\åıôhª\ß{r|œ\×+a\áŸi‘E§\Ş\Ú_\\\êz«Y\\K}ÇŠ\Õ\Òñ\ç™Y”\"¦\Íÿ\0\'÷\Ù\Ûb/\ÍN·ñF§,W~ “\Ã:\ß\Ú\í\×\ìšnˆúE\Ì+>jşõ\ßcofÚQ7mTÚ¨\ÇsY\ÚiZ†t=6\Æ\ÏO\Õtø-\îf‘m3z²4óy˜}®]‹¾÷\Ú\ßx\îj¹ÿ\0	N…/œ‘\ëZc=°œÍ‹¤ıÊ§\ß\İ\ÏË³\ä\İı\Şõ\æ×º~¯-\ÊC±ªË¯iòY­\ËYMf-n\Úgg™\âd\İ|\ê\È\ÇşxubW7õ/¶x‚\çTò\ì5;O°—K;§\Ïİ¢º´\ÎÒºlo;aT@\Û~}Í¹›\ä\0\í\Û\Å\ZVPŞ¾»¥-¤\ï\åC9¼M“Iı\Å}\ÛY¾Vùj\Ú\êú|·Ï¦&¥hÚ„)æ½¢Î¾r\'\Ëó:}\å_™?5¯4»‹V¹ş\ÓÕ“J\Ö/-5xµ[;/³º\Ëº@‰½U\áW6ò>\æÚ‹½Ù­-L\Õ-¼Ac¦L·S\Ëe©\Üj\ZƒÀşLğ¼Q\Ù6\îÌ‰ò«\Ëÿ\0f˜—ú±Eÿ\0«U@Ÿ\ëk”ø\â\ÍOÁúm½Ö“¥¶«q5\ËEöeeûawùy\İò\ì\İò«|ª\ß\'ñWVŸ\ëh_â¬™fO‡u)õ=\ÇS}-Í¬w°\ìÃ³.~]²2ü\ß\Ãó·ûÕğ\ß\â¡ãŸµÿ\0hh\ï¦y6ğÊŸ:™~&L9ùg\Ëü?+|\í]\\z…—\Û³>\Ûoöİo\Ù7¯\å\ïÙ¿g\ŞÛ»\å\İW©\Äx\Çw^ñF™¡A¥\Í}o{\äù·hñ¨·\Şû9uûı\îü\İ7}\Ú\×ñÆ¿?†|7q«Z\Úı¦hY6Áò\å÷:®\Å\ÜG\ÌÛ¶¯ûL¿+}\ÚØšú\Ö\Ú\æ(&¸‰n&ÿ\0U¶\×}¿\ÜOâ¥»¼†\Æ4š\êX Ft‰w¶\Ğ\ÎÏµGÕ\0¤-ø‚\ë\Ä\Ş‡V½°{dyC[3Éµ\Ùv¬Ü®İ÷[r·È¿v±ü=ñ.\ë\\ñµ\ï‡E–{/´ªŞ³Ç²mƒ\äùÿ\0‡~\×Û»W;~\íu-¬\é2\íO·\Úşòcn›%4\Ãs2¿ò·\Ë÷¾V¢-OI–Kxa\Ô,™\æiü˜\Òdgr¶mŸ\Ş\Ú\ßû­÷©ŒÀñWˆõ«n\×L\Òl®.^\æ\Â\î\á`‡\È\İ\æ£B©¹¦u]¿;}\ßökXø‰®\é‘\ë\Z|\ÉhºÆ™is©\Ì\Û3\Ù\Ò\İY\n|ùÿ\0\\\è¿\íy/]õúi–·\Öú\ë\ÛÁq·\ì\Ğ\É+\ìû\ì¿\"ÿ\0¾ÈŸ÷\ÍPº´ğ®§sv÷R\é—7¨t‹Ò‚Ò®\Ú]\ß{\í·\ïm øP\Öo\î.\ßP\Óf±´WQo\ãB\Ó7\Ëó—ò]—n\îı\Óş\Í2\ïU\Ô?\á)·\Ó!x–\Şk«•Ş›ştxU?‹\îşõ¿ñß»üVO‰th£Šg\ÕôÅ†gx¢fºM’²\îÜªû¹u\Øww\rY\ëc\á[­~\ÇSMK\Ï\Ô\Z\Õ\Ú\Û\Ê\ÕcÛ¶\İÌ±+\ì)÷>m»~\ïû4¬M\ÎV\ßâ§söw[$‰\Z\æ\ßO¢²\êH®ü™§¹DŠ\"]¶ªF«ı\İ\ìÇ¶»	\ëskš¾©4N¯!’#¾‹v\É7l?2nÛ»k}Ú©¤XøZú\ç~“.Ÿrğİ¶©º\Ò\ã~\Ù\æW_5ö·ñ£¿û5ugĞ´/+IK\İ>\ÆY-¥¸Twwvo•Y·|\Î\íEŠ9½s\Ç\Z¶™m­½Ÿoyqa«\Û\ØCÿ\0\Ëexmœ®ü¬|÷Uo\áù~Vªşñ\î§\ãK\İA4\ÉtÕµ‚/6\Şw‰\Û\ÎG™\Õw|\ß/ÈŸ7ûMş\Æ\Ú\İo	hV2Kuz\Òï½¼\å\Ú\âñ\İZ\älTe\Ü\ß+|ˆ¨«ı\Õ‹w x\n\Â\çg\Ûtı=\çšq2¦ªğùÛ¦\Ş\è\êw.öû‡\å]\ì?ˆ« \Ñ>1½ÿ\0„Kûi\"·i´Ù—kª\ìûo‘ıÿ\0¿·ÿ\0¨¯¼_©\Ås{g\Ùo®\Ûiƒ11Ü\Í\×\ï\ï”ÿ\0ñ\rüVS\Âú¶O¦Cz\íiw,Ò¬_mgù÷³»\'\Í÷•şo—\îµIyğûLº¶H~Õ©Àÿ\0lKÇ–+\ÇGy•U™ÿ\0‹j¢\ß09\ßxûZ\Ğô\İN\ëÊ²‘mo®\í6ş\êX½\Ê3|üüéµ¿¼­ü5\Ö\é\Z\Å\íÎµ«i—n\ßbò] hşG_›~\ç|ü\Èÿ\0\İÿ\0Ùª\Ç\Ã\í\æK‰¯!¸¾K·–W{§1oš%\İS~\İ\Û7\r\ßÂ¥¶õ5§£xr|‰uws-ÁC5\Í\Û\ï’]ªª¿?û*¿ú}\æf¦Y‹sñ\"o\ÛøKû\"öYdd´ù2ù*ŒÛ·lûû\Óo÷~÷Ï½Yjÿ\0¼em\àm#ûf\ê\Ê\îò\İgHd[Tbé¹¶\åW~şß¼\Ëşö\íªt›A\Ó>\İö×°´û_š’ù\í\ïŞ«±[\ŞİµŠ\îş\ëm«\İló­\Ò%\ÒTŞ»ö:ı\Ö_ö¨\Ô\n\×\á!\Ğ4ı[\ì¯mö¸V_!\ÕÁM\ß\Ã\Ê+À¶\×?\áˆ\Ğø\ÓS\Ô4\Èl.\í¥°R&i£p»÷²mBÈ‹ü¾}­÷—g\Ê\Ûz«\r:\ÛL¶Š\Ê\Ê\Ş+kHQ(pˆîª¯ğ­G§hzN™\'e¦\ÙY¿•\å~\æFÙ½›o\Ëü;\Ûo÷‰ {\Åÿ\0-|!­\éúL\Ú}\Üò\ê8[vDr7\ïT\Ú\ÛWı½\ß.\æÿ\0c\çL\ïx‡Xÿ\0„{H½\Ô\ä‚\â\ám\"yš8T³º¯÷\Î\ß]¢Ÿ¨\èZf¥(š÷M´¹}Qi¢F}›•ö\ïş\îôVÿ\0x-Y¾³µ\Ô\í¦²½·Š\æ\Şeò¥\×xuş\ë\'÷hÁş/‡ÆšS\ê¶v\×0ZùòA£Jª\Ûwr‹ÿ\0\î_ö²­· oõT\Ë}>\Ö\Â)~\Ëo	$\Ï3ª.\İ\Î\í¹›ı\æj{ÿ\0\Ãş¬QQ\n(8¿\Ö\Ò\îı\Ûÿ\0½I/ü³z\Ğ—±º—Åº~ Šÿ\0b†\Â\î)[È’¼\Ö\ìŸ/ñ|¨ÿ\07ÿ\0[2ÿ\0±XgU½‹Å¶úc½¿\Ù&\Ó\æ™qoGG…~wß·kyŸwoğı\ê¥ñ\Åø?\Ão«\ÛY=\ãù\ĞÄ«¹v|Î‹\İ\×ÿ\0ş-½³Y–Z\Ô\"º—Äš=\Ò[î·´[5‘—bnU\ÛU<Cxú\Ü3h\Z\Ó%¥\Ü)mg\åÏ‹\ç\èŒü\ç\ĞğkO\Ã\ZŒ\Ú\ç†ôıNh¼‡»…%1ü­†eÿ\0e›ÿ\0B®RûÅº\î™ı¦ó>Ÿ<VZ¤\Zo\î4\é\ŞFŞ;>\Õvfÿ\0[³j¯ğÀh?\í?K/‡S\Ò5Vxu;‹—eµ\r\'Ù„¢Q>UvgO•\Şõ\ÛSÂ¾	¼\Ò<I§\ê\ŞE\ï\ï®oå¼Šk­ğÁ¹\ßc¢n\ãz”İöw\×Y£k—·Z´º{\ìh–\ÎÅŸ\ì¯n\ï½\æ]¬Û—\î/ı÷\\ô~=½—D»\Ôÿ\0\Ñ\Õ\á¹û?”\ÚtèˆŸhò7og\Úÿ\0\'\ÍòÑ ><¼ºŠ\ÛOû-–¡x\ë¨Á+ı—b#«6ı¿\ì­r¶\Ú‰¬|[o«¤W\r¥\\j\×W7o\ŞÈ«\ëÆŸywù›]W\æù\åù\Íh\é&\Ôõt²ƒ\Êk)¾\Ñö{Ø¬§t™bòW\îÿ\0\ïwM\ÍüQ·]“©jzœ’ş\ê\ß\ì\Ş\Íl\êw‡ØŸ.õõ\Ü\ß\Ãı\×ûÿ\0\'Ì€\åô«\íCM\ÓtMNm/Zhl®o\ÖhÉŒÊ“;´/³nı›vıß»¹w}\Æ\Û=\Û\Ş\ÜøƒO…4]COvĞ®m¶¥»<6“»\ÂÈ*&Á·aû­·\åÿ\0swU­\ß\Ü\Û_YiöQE\ç]\ï>l»ö&\Ä\İ÷ı\ê¬|I³%\ê,\Z”6\Ï,°\"»B©¹•%Ø›—ıª`ex6{Ë­m\ïf\Óu[d‡K²\Óİ¯¢h‹\Ü+\Ì_¾ö\İ\éóôo\á-E\İ\ã\é¾)Ô’÷C\Õu;}DY}•b·ó¡Ş®\Ù\Üÿ\0rŒ\èÿ\06\ß\ï\r\ÍTôoŠëš–Ÿ§Ã¡\\X\ËvaFû^\äD/\îû>OW\ì\åU¾\\ı\ï¹Vl~\"}¦/%ôİ·\r,Â©.ô™\á\àm¿/Ê©\å»»@x\Ï\ÂÚ†¯¤}‹û_U¾K\İB\Ï;R${DY”³\Æ\èŠ\ß/\ß\ÜÛ¾\åq°x{SĞ¬¼G¤¥¯ˆ._U¶š\Î\Î\å yi¼\çù\åu_“š­½¶¯\Şû»+\Ğeñl–\×Wp½‡\Ém«\Û\éK±şÿ\0œ¾ÿ\0»ò\íóv\íÿ\0g\ï|\ß/SH+\ÃZ.§k\â\İO\í)*\é–\r3\Øn_‘ş\Ö\ë3ÿ\0\ß®¿\î½v\Í\\­ÿ\0Œf\Ó?¶7\é©/\Ø¢Xg\Ë\Ìf*¨‡r®Ï\×Ö´t-sûOûN H.,.\Úm‘ˆ\ê\ÊûW\ï#¥06*6•)´›Sø\è~yjUJoš”y©ZIE3\ÍJ]ÿ\0\ì=fš¼\ß\Ã\Z”:gˆ\"\Ólµ«‹\ëUût×–\×VKØ¶Í¹[v\Åe]\Îÿ\0\ë·ıå®³T\×.´\Í[G²ûMo©\ÜIlò´»^X^eùqónH\ßø—oûU¢ø\ÎMcS[g²ò-\î\Í\ÏØ¦i]ü‡û\Ób\ìİ»r|Í•ş\íœÇ5?X²\Óõ««+\ì\Ëı\"\î+(\å·\ß\"]¾\Ï\'b®\í®\é¿c-E\áø&‹\Æ6V·I\æø–\ßQ\çQ¹XŸ÷¶Mi±~Ï™wµº\íşô-ıÆ®\Ë\Å+ŸC“e•ƒ\ßJ¶“^Ê¾k.\È!Û¿g\ÊÛ¤m\ëµ~]\ß\ßZ«a\ã\Ä\Ôõ(¡†şÌ¹¹{;½\íş‘:E\ç“gÊ»O›wğ7r˜¥¿ú¿\Ëù\n*EYq»Ô±û\Õ\ë¤‹øÿ\0Ş é´¨¶\âÕ¾´C\Û›\ä\Ø\ìŒ\ß/ûÑ§ş=U¼Q\ám#\ÅúOöf­o\æ[´©.\ÕT\ÜYv\ç\é·ı\Öe\ï[lµ‹û•™emF²\Ğô\ÛM3O‹È·µ‰\"Š%\Ú8_÷Š³¯¼guö½—·Vr\İ\ê	¨™\àdwI‘nôeÛ¶4ùvÿ\0z¶¼ÇŠ7ıŠ\0\ç\ãğ„ñ\ßh\'‰5µ5²[K8[_Ş¢;²ü¾F\İÿ\0;ı\ÕZ†?$V¯§¾½­Ë§ı£\íd“\ì\Û¼\ï;\äu‰_\ïy¾\íu>jQ\æ¥\Êxµ/\í;=cU³—}\Ë\íC¦É\Ó\çG*¬\é¿\ïuv\íµVÅŸ…¾Ã«=\ì\Z¾ªñ4\ÏpmDò|\×M¬\Õ\ïşûl\Î\İ\Äü1¿\æ¥\'š”ek~Mb[+¨\ïn¬ol™ü«›]›ö:me\Ú\èË¸\ßwø§]\Z\ìOì—–\á¢û7\Ù\ÚI_|Ì»6\îgo¼\ß\íU\Ï=?¹HÒ¿ğ-\0s—>²¹¾²\Ô÷P¶»²H\"†ttı×’³/\İ(U·,ò+}~]§š,ü¦Z\Ü\è—[\î\Ú\ãGY–)Áóšo¾ó\00\çv[ª¶+¢\ËĞ±ÿ\0}\è–“Á“\ÄÁ\ß]•¥»\Õ#\ÔÕ–\İ\ÉtTU]¿Ä»\"Eÿ\0Ç«~\ÏÎŠ\Ûe\Õ\×\Úe\Üÿ\0½Ø©ÿ\0­[òª_’€9\Û\ïZ\ê\Û\î%W\Ô\Ñ›j?’È¿#¦W\ï+mo›wÌ‹V|9\áÿ\0\ì;k„šõ\ï®\î\æ{‹›™Q¥vÚ¿u~UÚ¨‰ÿ\0\0­ª(\0¨$©Yª)(:)wSkB©·¥U\ÛRl\îTST\ÓS½\Ñ\î¾\Ğ\ëır÷+±w\ï\İo4;ò.\ïø\rci›LÔ¥ºûm¬ñBÓ¾Û°6\ï;\ï}\í¿\ç\\¦\ÔÚ«…f_šºM¯÷(U\îT–rÚ¯‚õ\rN\Æ\Ó~¹şš¶si—wokó\İC6\İû]v?È¬­ómş\ëT–>’\ÃWI’ö\ßû2—¼´°[wF‚\á\â\Ø\Í\æ\ïù—ç™¶\ãø\Û\æùkªÿ\0–_ğ\ZCü) ¢EY\"gÿ\0€Vˆô}kWŠ$\Ò<D\ÚC\Æ\Òo‘m|\İû¾\ï\ÊÌ«òÿ\0µº·¢¥‹øÿ\0Ş¬À\åŸ@ñO\ÚbøK\Ó\ÊØŠñfü®ÊŠ¬Û·ü»Ÿsm÷\ÛPE\á_\Åm,/\ã\ï6\á\'}-w\".\íË·ñn_û\â»\Z(bxš)-ü^’¤>_›öwú\íóË¹~Z†\ßÃ0Š\Ú\í\Æ\ÑNò&Ø™ô…\İıw?ğ*—\Ä\Ş\"Õ´=J\ÆdM)´©\î`³XÙœ\İ]H\ï´\ì\å\Ï\ß*¯÷qš\éüÔ £›\Ã3’+Dƒ\Ç)Â…ea¤£y§~\åm¬ÿ\0/Ëµ)\Ğø_Å±\İ]¼4\İ\Ûü˜MTHw:\íù÷\î}ª¥\àu\Öùõ\æ\Z\Æ¬bi\ÇO³µÈ°¹š\å[b%\Â@\ïp‹·büû—k7\Ë÷±@\ŞñŸ–‰vÊ®\îò>——\r³\ä\ê\å\Øÿ\0÷\ßû¾/\rø²;ë‰ŸÆ›­Y$H`m6\ÂÌ¿#o\İómoŸ\æûÕ•k\ã½fú\Æ)­_B»F\Õ\ÓL[¸7¼7hÊ\çE‡şî¬›Ÿ\æFùë»\æò“{¦ı¿6\Åù7\ĞAÊ¯†|gö\'…üs\\o\İç¾œGı\ÕE½\ïş\×Ü©\Û@ñoÛ•\Ó\Æ;-6\"³bß¼*«6üÿ\0\îm»jß‹uCC\ÓRö\Ö(¥ÿ\0I··e•™?\×L‰»\åş\îüÿ\0À+x³]W0\Ïed-â¾‹L‘•\ßÌ’w…fŞ‹»œ™·}\æùvm`²\Óøs\Æ\ßÙ¿eÿ\0„\å\í¥Cö¿\ì˜~Tù÷\r›¶üÛ£ş÷\Üÿ\0o\å³\ã8¥²Gñ=¤ñC³\Îİ§*¼\ß\Şş=«ÿ\0§øOUÕµˆ\î\ç\Ô\ÕR™­‘mw²3;·û•^o\ë2ø’\ãL\Ó4U»´²0­\Ì\Í{\å\Éû\ï\âDÛµ‚®·2·\ŞÀõ\0u¶‘\ã£•&ñ»;}\Íö©µ>öUw|Ÿ/ù\İR¶\â¯)6kV[ÿ\06û‘şfûŸ/\Ëò\í_\â®V\ã\âû\é–VZ­¥$šŒQ\Ş[y7E\Ù-“|’\îE\Ã\"È­µw\ÏòSl~/%õµ\İ\ìVğY[ªJ>\Õu¶i•§šØ\ço)>]\ßÆ«I‹S­¹±ñT·7~F¥§\ÇÇ·î·º|\Ë÷ş_\î\ïÿ\0¾ÿ\0Š ¸\Óüm-²y:ÖŸÂ³\îı\×\È\éò\íş÷¿\ïº\Äÿ\0…©\åÆ®ú|S¤\Âd‰l¯–Y·¥\ÂB\×bª\ï3\'Í»\å\ß[–0‚Kf}N\İôÛ¸\ïSL–õûn_n\ÕWDù•÷£nù~ÿ\0Í¶˜jHú‹D—\Ç`\Öò#\ìfù$öa\×\ïüÍ»wü\nª_Xø\Û\ì\Ï\ä\êºo\Ú\ZW\Ûü*‰·ş¸·\Íş~o»]V^“\ÌzQ‰g-ö_\Å{û\İ5­6.\å\ŞT\ïÙµ¿\å÷÷¿\Ş_¸¿\í\Õh\àøö„\ì/µù©\ä³]>\ÔM­»ş]\×\æİ³g\Ê\ß\Åøö^c\Óüÿ\0ö(Ğ‹œŒ1ø\Û\í6\é¡}Ÿ÷iÿ\0H‘_\ï|\Û\Èş\îı¹aü?vø¸f•?\âœûG\É\ä\Éö‰¿½óoO#ûµ\×oO\îQ½)\è\Z‹7\Ä?ôtOøG7\íıöû‰~O¾\ï\î>“g\Ş\ÛóT³/e–\ïÉ—Ã¾W\ÏöeieŞ¿2\í\Şû?»»øZ´5\ry\ìumOû\ÒE¨\Í$Mr’¢¬.°¼\Ûv}\æf\Øÿ\0uZ^ñœ>+¹¸KW\Ó6BóDU/Ä·(RMŸ<[>M\ß{\ïvÂŸøO~ÄŸ\'†š\íwoo>uG_—oğ¿ÿ\0|/÷ş[6­\â\ß\ítûLZ\'öf÷\İ\åK1›nß—ø6\î\ß÷¹¦xŸ\Åÿ\0ğÊÁ¦\ÜjrıškË…\Ñ^dÛ¾O›\ï·Î»ø¾o\î\Ô64†û[M1,&û$Ò½½¶ \ïòM2\"»\"¯\Şû›şo\â\Ø\Ôs\ë\áÿ\0V(¨…Gúß¿G™\åoDşõ:*|HŸ?ûÕ \åÿ\0Û¤\Øÿ\0\ÇVh¬\Ë8ûÿ\0jrø\Ê\ß\ÄpkÄ°\Ãä¥¥Õ—šˆ„\î—co]ŒûWsm=º\Ï)*JkP|¤®_„\Ú,_bx%ki­¦’Yd†U\î]®oö¢/ûµ\İ\ïşúSw$¿~€9\Ë	,Vñ[y\ì–ğ\ê\Ú6±F›v|û\Ù>ÿ\0\İ\Ş\ïıß•\Õ6ü¼ô¾û\ÆdŠJ2•@R\×t¤\×4Ô¶y|„ûM½\Æ\í»¿\ÔÌ“ì•ˆşOK{ıªŸ\Ùó\ê	©\Ëh\Öù“\ÎHB|’\îùS\äC·oğ›š\ê[d»7\ÑòTCDÓ“G¶¸O5\åó.f¹\Ü\ë÷7»¶\ßø\íµ•s j\Û÷\Z†Ÿ®-­\ë@\×1]ò³\Ä\ßò\Í\Ùö¢°Ú­¹[¾6µt‹²ƒ²/¿@%—\Ãi¡¹\Ñ\ä›Zó\ÓGŠ{8’\×o\îb“w\Ïóü\ÌÊ‘.\ï•~Vm¿7Z„“C\×5-M\î\Şw¿?u\á\Ù\äşúy¾öÿ\0úxuÿ\0€U‰qyº&Ÿj–ö÷>~§m‘q.Ä›\çû®û\äÿ\0€µb\ÜxM,o¼9 \ïv\×\Z¥\ÔOÚ¥t\í\Ü\Éno™T\ìş6ü´mş\Ûyi	¸‹\ìóŠñM˜\Ûv‰v·(¬Ÿ\ìşõ?\Ú\ß÷kb\Ã\Â	£\Åq“qı™o6£ñ‚\Ş\İ<¿uC\ì}\ì¿\ß5\æğ]\ê\Z\Å\ÊoÔµ;\Ö<ö–{ıE!¶ºòoC[š\Ú|—*;ÿ\0‰\ë´\Ğu=Iû–\Í¡\Õ\"³š\ÛP§Ew\ØÌ–\ïÏ¹_\ä\İüO÷?†\0\îw¥?zS0”ß’¨oJ_.‘vS\ÙÒ¤ùII\åTÁ¿¹M ][A}OR\Ñ\ïRTT\Ó.\Şå·¦\í\è\ĞK\ßö\Ö\îÿ\0€\ÖŸ‡<Gı·i¨_Ë¤OönÅ·\Ù\Ã\Ä\Ó<Î¬»şVT]¨»¶\î\Ü\ß5Wñ\ÜrK«\éöZ~¥­ÿ\0\ÂA0D±¶´Ÿd»_s\İ\Ü*\íVEû¸}\Ê\ßus5tş$ºÕ­´K¹ü?e¡©Ç³É¶w\Ù\×\æm\ì¿u~m»—vİ¹Z\0\äµ?xšûM°ß«\ÚK©ÿ\0fË¦jw/\È\æm»¾®¿/Êª\Ùl\Õ\Í+Á7Zf¿\ßh·]\Â\âk\Û+eÏœ“Ê›_øv®ùY~ó~÷ıŸ›‹½¾ñ^	¸Õµ\×g·Ó—T…\ä—RKY\Òu¹e…Ù¡}®©µ“g\Íó/Èò\Ö\ç„õ\íN\ç\ÄM®\Û\êß¾½ŸM±d¸EµV…~è••‹·’ÿ\03+cWm=EzQP\ÑA˜\è¿\Ö\Ğ\×v¶¿\ë®\"ƒs>\İî©¾ˆ\Û÷µCWğöŸ\â‘5»\Ù;¯‘{4÷\Ö\Ç]\ßğ*\Ğ\r\ï­eù\Ò\êİ‘—v\äuo’•n\íeŞ‰uõûË½~M\ßv°ÀZš“$W~j\íO›Q¹?uw\Ïó|ª\Ş\ëP‡û3\Ù=½ô¶û£}³\êWR|É»n\Í\Ï\Ç\ß?w\Û\Ñk2Î§Í‡\äù\Ó\æû¿7ß¦´°\Ëÿ\0567ñnù+ü¡}ª\Ş\é\Ò÷Í´dx·j7;cn_“~\Öùÿ\0½U&ø}\á\É~\Ñö÷s¤Ê•\ï\ç»÷v|ÿ\0&\Ïöh‡I\æA÷>Ğ›\Ûıµù\é=¬»\ß\í»~ónû•\Ë\Íğ§\ÂW1\Ú%Ö›q*Y.\Øw\ßÏ½>}\ß~\æùÿ\0½SK\à/Kö¿/Ou{°\é+,\ï³\æm\ßs~ß¾”\ÃC§X\Ò_?\Øj\Z$ûˆõÍ¯\Ã\ï\Ël\Ï \Éó\íW\Ôgş-¿+üÿ\07\ÜO½Dşğ\äZŒº‚YJ—w*\ë+%\ÌË¿r\ímË»ûŸ÷\ÍXht¾Z|”¾RW2<£}›\ì\Ïo+[³ù­\Ü;ÿ\0½÷›\î7ñ¯ñUdøs\á\Ï\í(naµ•e¶‰a_ŸrmTò¾eş&Ø¨››\æù€\Ğ\ëüª$Dù÷\×)iğ\ÓÃ–\Ö\Ïl–O3¤¿#\í\ØÊ«ÿ\0»\ßtù¼\r§\Çsi43\İÁöwVH\Ó\ÉòşVw\Ùó\'\Üù¿‡\rÿ\0P\Z©ku±d»~uW]û‰m!–Ty¢Mğ¶ø™†íµ—zÿ\0w\åf®b_Y\\\Û\\ZÍ©j¬“²?\Î\éû­­»jüŸú\æù\êµ\Ï\Ã->[kHf\Õu6†Ñ¿t»m¶}\æe\ßû¿\áİ¶³U\Ü\é&Ñ´›\ëo±M¦\ÙOoƒ‚[u(\Ê\ßyv\í\ÛóT\Ö\ZŸ£\ÙC§\éú}½µ¤-¹ ·Q·\îÜª«µ~oš¹y~\éò\É{¿R\Ô\Ù\ïú\Ê\ëm¾›slı\Ïû÷R\Íğ\Æ\Â[d†MSRo/w“\'•jY7•\éº\r£fÀÿ\0\ÙÂ°r®\çe\ås}fÿ\0~¹i<Ÿn»t\Õõ5K˜\Ûb+¯o\İ\îÿ\0k\æ-H<Ÿfònu­Vx–W•|Õµ¼»vÿ\0¨û´j#ªX“\ïüôyI-rrx%½ş\Ğ}cRóT\"}\Ûo›\ä\Ùÿ\0<¿\à_\ï±ò\Ó\ÛÁ)ı›,/\âMm¼\çFi\ì\Ûş]\ß/ú½¿\Çı\ß\à©÷€\ëB\Ò~\î¹c\á7Š\çOº}sUÿ\0DDO• \Û.\İ\ß÷?Ç¿øvıÕ¡¼Ÿf¸‡ş-wı\'c4ùûÿ\0,?ú\ËU©\í_Á~\×/b\Ô5m\ÊòöH’\å\â\Ş\èª\ÌËµ¿‡\æfü\êü\Z=•­õ\î¡kkwş_\Ú\'D\Úól]«½ÿ\0‹jı\Ú\ç&øzòı“g‹üKÙ—fÔ–\r“|ùù\Ó\Éù½>ü\Ó\ãğy·s\ÂI\â\r—;• k„\Âneo“\äÜ¿sû\İÚ™§\ÌÕºğÎu-¼\ÓiR5•\Ã\Ü\Û\ï_É•›sLŸ\í\ç\ß{u$>Ñ­µ&Õ¡\Ò\íWP‘G¹H“yfUVmÿ\0\ŞdT¬©<ÿ\0Ù±[\ÂU\â\rğ\î\Û?ö\İı\í©µ¾\ï÷jk/	Í¦j_\Úğ‘ks£;³ZM:<\r»øvlùU‡m\0tbŠŒÅ‹ımr\Ş,Õµ;oG\Óôÿ\0\í=—pİ³®¶\Í&\äÙµ¿ò\íùº˜\×÷µ‰®\êz‘}i{¨\Ç\ê¬\ÑZlG–m‡nı¨›oÈ››o\Ëò\Ó,óû¿ø›H²™õ;\Ö[û#\r\Ì?\Ùù³\ßöE™\æóö*ù‰.\ï—~İ¨©³{o­\Ï\ê76úc\ê¾ [‹Ö´\İ?ölW¼W.\ÏÂŸ6ÿ\0³²üÑ¶İ™\Ù]\ä¾¾¹›Lºº\ÓZ[\Ôş\Ùx‘\ãÙ³\Î\ë·Wı\Ş¿½XúŸ¬<\İv\×Rÿ\0rÁ4·:•\íË¼(RX¢?¿l„eEÃ—=\èW•—µ«\ïø˜}¾eŠ\ì´{e…\Ü\İ\Él\ìÛ“xû¹Û¹vÿ\0ãµ±\ámsP¾¹\Ó>\Õ{qsöıM•\à†?%\Ù\×\åMŸ\Ãóı\Ö\İ÷\çû\Õ!Ñ¼c­Û¼—E\ÛM•—³¬.\ê\Îğü„\ìc—gOö¾\í[ğ¼ŠY\á½·¹•bD+\ëO\äB¬\Û;\ìMùû¿/\Éş\Í\0Pñ6µ\âøH/tı\íR\ÜG§\ÚKpÁC\Í4\È\Ï+?Í·j/\İş\ãSlş!M&“ı¢ş\Õb²i’\Ş\ÚwÙ¼ùdWfıË—û\åOO•ky\î´]2ûP½š\ê\Òå¶„Ü»¿)n¯7’\ì¿Â»O›\ëY¦ûÁò\é#I‚\â&\Ó\ãm\á`g;\n\Í÷\Ñÿ\0¼“&\íÛ¾]´“ñŠ\ÛG\İ\r\ï‡u†¸‚Y\"¸Šm›\ÉuEo½½7nG\İò\ÖÍ§-µ;\ÛtK+´´ºº\Ò\Ú\å\Ùy,(\Ìÿ\0*¾\å_’O™¿»ş\Ú\Õ+k?\0}•.^ö\Şu\ÕwY­\Í\İÛ–»i•[h‘›—eD\Ú\Ëÿ\0\0­-?\Ã\Şµ‘õ\r=Ÿe¤³Iµ/\á†_™&dMûC}ıÿ\0\í3ÿ\0z\0›S»\Ô?\á \Ó4›[¤¶Š\î\Ş\âieDW“\äòUvnù\å§÷[\îW­x÷Y\Ò/µ=÷ºxk)–\Ì\Ä4ù•]¾\É\ç4\Êû\Ù~ÿ\0ğnùU6\î\Ş\êÕ½®j\×l­5\İÈ‹s\Åmwaxğ»\å~uGNHÀ\Şÿ\0\İÙ»ø7Qı\à/ù]?÷\ì“.—¹ÿ\0s±v®\í\ß\ê“\î¯ğ®\ê\0\Ç_ŠS\Ås\Óÿ\0hby©oqrúkù\é7‘4Ò§”«¿jªÛ¶\í¿q\Ï\Şû\Ë\Ã^6\Ô5‹Ÿ±jq\\Aw¶aò4Ù¼›\×{®\åFÿ\0e™k¢ÂºLz’jon\íp†6OŞ¶À\êü}\İ\Û¯ü=ªi¾²\Ò%G\Ó5\rB\Ò‘#À®’¢.\Õİ¼3}Ï—\åeù}ù¤\Ğ\rğO‰¿\á+ğı•\ïú_\Ú\Z\Ú	Ÿ\ÍÓ¤¶FgLü›\×k¯û¬\ß\ï\×9ñÇ¾#ğÎ­qe¦isi\rµ\Ã\\ùL\â\Õ\Şi•şqòùp¶\ßö¶õß¶½H\Ól´-6\ÓL²‹Ê´²…-á˜¶\ÄEÚ«¹¾fùVªj\Òu9/fº‰›\íöğ\Û\\~ñ—r#;*ü¿w\æ™\é\Í‰zd“[»\éZ\Ä\Ó\ïò.^(™$T gÚÌª\êß¼Uùwwmn]x¡<\Ût¶ß±µU\ÓY`Ü\Û[vß™‹\å\İó|\Ê\ß%Vµğ“›\×\×vSC=ºZ]J­0\Ì\êÎ‹µU™~_\âf\ÛR\ÙG§húd:†¡\éwp’ùª\ï;®\ïfem\ÛÙ›\î\îf 	\ÓVy|S¨iŸ\"­­•¥\Êl_šg[¿ı2O\áÿ\0\ìu·<±º:|õN\ßD†\×\Äz\Ò^\Ü4\×v\ĞÛ¼g’ˆŒ\ì»>M\ß}\ßø¿ı\Ú\Öù?½L\n¾gú6ÍŸ=)Wû2UŸ1)gÜ« ¬cyv&ÊœG\åF•\Z\Çüé²¬nJ\0«’ğÏŠ®µ;\ï±jmµ\ë,¬>‘qj®ˆûw#»\í¼¿wûÛºW`dH¶#ÊŠ\í÷Wwß®^\Ã@†-JY\àñ6¡}wh“Ew\Å7Ø¼\æWû¨«#}\ÅÙ¹›\åZÌ²øS\Ñ\î=-öZi÷\ZÏŸrñ£/È›]v»\ïœ\îÛ³\î|õ\âû«\íJ\Ó\å‰t›û»›;=‘6øT\îwvm»d\ß\Ãü+\Ëo©\ï¼¦\\\éº~Ÿu«\ê\å\Û>˜n^ti¯¡}»\âvpÛ·\ìş§\ïl\ÛSYøOO±ñ$º„·²—•4\Ö0ù66\Ğ\Ò\"„\Ş\ì=[o\Ìş´ÓŠ*X~à¢‚\n‘¬®s\Å\Z¡¬x“Dš\Ê÷P\Ó\íà´»I®lü­\é»\ÉÚŸ:?\Ş\Ø\ßu‚ºx«V×¯mµ½3I²†\ß}\ì72ù³\å•<Ÿ.\Õşó?÷¿‚“,ó_á–¯m¦\êz~“­º\êpıÙ‘t)bó[\æù9_›\ÓŞ¦³³ñ‰µ½*hÿ\0¶\í´û;‹GMCMŠ7¶u´½WØ®Š]>xsnù\ß\åj\ÒÕ¾/>u{ks‹ö‹\'û<–I¨3O\æ-¿½Wf\æ‡v\Ôİµ½ş\ÍA¦|gšú\çeÏ†.ô\Ï\Ş\ÚDûüË‡s2\Ü\ïdDM\Î\íŠ)\î9ùEX\Ì\í\ÃÚµŒo¤}ƒRŠ\âg\Ó)\×d*¶×¯;¶õVEù6\ìÿ\0yW\ï\×I\à;+Ø®tÇº\Ón\í?³484Éšhš0\Ó+|Ê›¾ú®\ÍÛ¾\ïÎ¿\ím\Ğ\Òül÷\Ñ\èS%¬[5[¯\Ù\Ùü–MŸ\'\Üù¾ÿ\0û;vıÚ³\á\ß¾¹s§Á4Gö\İ&-I^)‰\Æÿ\0ùgÊ®ï¼¿7\Ëş\ê\Ô!\Ä¿‰u­WVòµo6\r.\ÖE´vò\ç6\åü\ê­\ä¾\Î\Ø^¹e­_‡²MccıŸu7ˆü\İ\Ó/Ù®\ì¶[Ã¶Cş®_%wo\İò\îf\Ï;~\í_ñ\áĞµ»½#\ìñKv¶ö\Ò\ÛG½ów,\Ï:ù+µk(ƒwñg8&™cñ\n\ÊûDMB­%¸k‹{f¶I\Ùü“5\ÂÀ»\ßg÷Ÿvİ´	œ¬\ŞÖ®¼7£Ú¥–ß²xC\ìoÕŸ\Şv\ÈwB©½6»l\Ûÿ\0\0®§Á7\ß\Ù\ZlºN¡e©Áqö»»l§duiÑ•ö\íûŸ\ínû¿\Æõ±¨\ë×ºg\Ù\ê\Â\Ó}Ş¢–QyWLÿ\0»em®\ß\'\Şù~\çş?Y\Ö><K\ï\Â?%ªÁ7t¼\Öù’¹>M¬\çÎŠÛ–‹\ÂĞ¯\'\Ó4\İS›H\Ö%Š\Ê\Ë) M.O?sº:|›7ldO½÷w2\îo½O±¶ºşÒ´\Ó?².\ìİµx5‚+wû5¤h«³\Í\Ù\äüŒŒ›Tÿ\0v¶¬üiu}m¥Nšm§›©­\Æ#{\ÆÚ\n·ñ\ìù·mû\ß\Ã\ïP\èÿ\0&\Ö,´û\ß\ìT¶Šòò\Ş\Û\ç¸;\×Î¶Y·l\ÙómwEÿ\0Ç¿Ù¦i½(Ş”ÿ\01(óıŠĞ‚/1)\ÛÒ\İÿ\0±G\îÿ\0Ø \ïÿ\0b“zENÜŸ\ìR4‰ş\Å\0\r\"Q\æ%.\äÿ\0b“\ÌOö(\0ó)|Ä¤óıŠ<\Äÿ\0b€$VO\à§mªŸiO\ï¥ñ?¾•˜ùLOi©/‰<)z–»\Ú\îUi\Ö-\Ï\n5¬\ß}ÿ\0…7lÿ\0m®C\Â&¼[ó\ì]N\ÂE5«’›w³]+Á\æ3/;\ÑEş\ê{W£µ\Êÿ\0®oHñ¥–¯{oe\ÅsÄ¶Ó¾İ“$.ˆ\Íò·Ë»z2±ıß»AG%\ã{\ë?X\éúœ\Öó\Éiªh³.)`}\é|û\î\Ê\Ûiş-¶½?Ã©\åx\ê(_cx‚\ÚòúmR\å2u£ òw\ãOš\İU\é‹lû]oˆ|c‡¥•>\É{xğ\ÙM¨MöP¿$(Ë»\ï2\îo›\å_ö\Z¢±ñÅ®¥©Ce\r•ß•5\ÍÅµ½ÛªysK\ß	´\çûûxÿ\0–o\í´]QQE÷ˆ%¬xnw\ì—_Úš†“wo\çEİŒ\éş[®>t#klW\é• 0#mn\Ãÿ\0®K\Åz­©ø“GşÌ½[?ô+è¦¹–ÀÜ¢#½¿\Ë÷•Q¾_—w÷\äni–K7\Ã\Í\Z]&]\'ı-m$¸ûA_<»nXV¾û›\îªÿ\0À©ú¯\ìï¯­\ïSP¿±º¶HQ$€\Â~HRuO•\Ñ\×ş^%ÿ\0\Ç»\\ö“hşğ·Š´->\ßX[¸\Úh´ö[)¼\Ç\ÛhŠŒ®©µŸ\äû\ËüU‰oc©øM\Ô4ÿ\0°E§¦«m§[\Â\ÚV›;[Z\Î\ï:M6À2¤J›\Ïñ2¦v\æ€;«O\ÛX\Ş\éSÁ©^¬:e»\Û%¦\ÈLR£m\ŞÏ½7\îm‹¿\æ\íü9j·¡ø>\×\Ã\×>r^\Ş\Üùv\Ñ\ÚC\Ã&\ËXv\ÄMˆŸ\ŞûÍ¹¾U®)ô­>\×V}3LÒ®v££Ë¦l²}‹k&ÿ\0\Ş\í\ÚQn8f\İ\Ëÿ\0»\â°Å¦\é¯4[¢‡T´•ÿ\0tÎ¨‹2³;|¿*¯÷ÿ\0†€\'\Ö|3g©\Ü\Ü^Mq<},¨\ê<¯³LîŒ»—\ïnw\İı\åùj¬^	H´ì‘­j­d¬²\Ã‚\ÚÈ“y\È\è\Û>ò:§\ŞÜ¼|\Ë\\o‚¡º\Ó<Gu3\ÜZ\éš}Ò¤°\Çw§»´ğµõ\ë¢\Äàª§\É4)V\Ú~U¨¯¼={mı¡¡§\Ëı\áû›h­·Å¹&¶}B™ŸfÏ™R$Eo÷‚l\Îò\ë\Âo}ö\'}sX\ßmx÷‹\'\î_{vs\åE*ª\íÿ\0kq¦E\àÄ¶Õ¥\Ô?¶/¥ıô\×0\Æ\É\nˆf•vy‹„\Ü\Ø]Ê»·}öİº¸ÿ\0k\Z~™\ãCPº¸K*÷\Î{9\İ<¤Ÿ÷‰»gÈ¿Å½¿\Úûÿ\0v³uË™.´Ÿ\ì\Ë/5®\á\Õ5‹™\àHº[4\×8}ˆ»Š0tÿ\0;~j\\\Â\Ô\î\íüö\Ö:|)\â-A^Ánc‚ù&]¿sfß—\ï/ş?º;À\Z~™}nö×·Ë§\ÚK\Âiû•\ã3$K\n»¹ùş\ê¯Ë»n\ä\İO\Ñõ\ÛGuu¤¯‘os\Z<ğBşU\Üîˆ ¦Ş¿À®WÁ·t5\Ó\íø÷i†£¼\×ù÷ÿ\0\rû•\æ\'I\Ó,t+û^\é4{-wTx|\ÛTòJ´÷{·.\Ô]‡r·ñ~5\×ø6\êk\ï	i—·ª‹,\Ö\áİ–)]?½³øw¯\ÍK˜«¿Mó\Ò/\ï·û‹^	£\Üøş›·†\ãJò¿\á^lòv-\ß\Ï\ä«ÿ\0›¿v\ÅûÕ»\â\æùµ-\Ú\Öú\ßN\Ò\ì%³º‚ñƒZ;O.ö·DFWvò\Õs/È»~\ãµ»ö·û\î›Rµÿ\0À·W›xM\'şG²\Ó\ÛJ\Õ|»k–MB\İZ+¨W\ÎMö÷IüN\Ä\îWm¬\Ş[ü«÷¿¾…k\â„\Ë\Z{iry>~õıöõ\ß\å?\Şó¾\æÍ¿?\İ\ÛLguö—ş£\í/÷\Ñ>õx¯Š\ï!–\Ùá²º–-m¯5D½\Ùò\İ%¶\æ\Ù\æ.\İÊ›~Ïµ\Ù¹ıút:—•©j\Óxªÿ\0Jûm•\Ã\Û\Ém«\æ[\äğ+º9ÿ\0Rîˆ¿{ıc®\ß\âUrg2}\Íÿ\0\ÅK\æ~\ï~\Êñ\Ø5{?øGüN\æ\ïJ¶²\Îm£º¸ÿ\0‰œ_\èHÈ¼–feÿ\0y¿\Şj¹q¨jz?öŸˆ&†(%\Ğ\îmQô»Fy\áhYVoº·½\Âÿ\0\Ëöls²f©ùüh”nykÏ¼,\Úf¿{¢\êwº<÷½¢\Ëö«\Ü\\\ïkd\İ\å#nùYş\ï\Íüo]u\àvd}¯r\é\"6\ÇGTm­»øj}\âM@ßº\ßòW+£xB\ëG½–\êMJ\Ö¹‹Nƒ\ì\î¿d>öW}ÿ\0?Ìˆª\ÛW\åOö©\Ú\Åİ­·ˆ<%Ú¢ƒÎ•ó›³z}\Û\în\åwlÿ\0W+{®ÿ\0\Å7núN´²ø M4ù¯I³j£}¡Cn½¶\ÕvM»~J­J:-WÁ\Z§eiö\ê}£û9ô\ÍN\å\à-%\ì/³{&Ò»r¾\Æ\Ú\ß}ªÎ—\àŸ\ì\Ï%\×\ÛÑ´\Èfš\æ\Î\Ç\Èu0O>\İ\í¿ÌŸë¶®\ßùj\ßÜ¬^½ûmŠ%\í½çŸ§ù·Ë©^}\á™fe›\äUm¬¹v|ª»w\×Yg,\ßğ™j¶¯.\ëxl¬eH·}\×g¹\Ü\ßğ=‰ÿ\0|Q}H\Ô\è$_pQL‘‘\ÉR\Äÿ\0úE\îªX“ÿ\0B ±»\é\Şg•÷)6Ñ¶€\r\Ô\İ\Ïü-IMWów\Ğe–oŸø¿İ©DoòoŸ\ÃÀ_§\æGÒœ\"û‰·u-8Øf†,~Wüµ~ûºsœr}ğ\0ôö¦´oü·§¯\Ëô\çı=jZ)ò¡]1¿T\Ï\æ-œıIÏ­sù\è99\ÏNİ½\é\ÔQÊ‡r6Gş\r‹ò…oU\ç<qü8\È÷&Û¾s\æ\î\ç?6\0ø\ÆsŒ¾•-r \æŠÿ\0\ßÿ\0¾x9\ã¿\Ğcòô©1\Îoº™\ç\ßzôô¤¢«”Bsıÿ\0\â\Éùˆ#±\Ç\Ôsõ¥QıÏ—ø›iû§Œ`{\0Tò\áÿ\0\×ô\ç®;{ÿ\0?Zn?ø®\Ø\'’O \ãvH\ã³S¨£”.D\Ñ\ä¡O¿\Ï\ŞÀ\Û\Î=8 z\n<¹;>\ÎÁ†3\İGA\Ó™õ©h£•n\"¹Š5\äg_\âuMõ7\Ï\ßÿ\0¾’›*ù¿\Üÿ\0T^c\ÅRÀŠm>\Ö\ê\å&š\Ş\ŞyW\î\Ê\è¬\éş\ãU‹x“\ç\Ù+ıöØ¿~\Ëı\Äù\ébo+ÿ\0f \n÷\ÚVŸuıª\Ê\Ò}»›÷°+üÿ\0ğ/÷jÑ\îlD§\Ëş­ÿ\0İ¦¿ú¤ ²AEKú¥¢‚\"©#¨\â§\ÅTX\ê)Œ\Õ÷û\ï³eHUc\ßù\ïT†GóQ?½H²ÿ\0\ß\Ã@	\Z¿ñ§ğÔ”Å“ı´dş´ú¨‰…#³ÿ\0n\ëüXÔ´U!\ßş\Âõ\İò“ôô¥¤iS\ÍDwEv\Î˜e±\×½-\0QE\0QE\0QE\0QE\0&\ÚZ( Y?{ò>\Úsoû›ª9?\ÖSƒ\'ğK·ı\ê\0o\Éş\İ.\Äù)>õt\èU\åûô3/\îŸı\Ú]©ò#\Ò\Éş­\é¯ü%“/J(‡\î\n**\Ç\\\ïŒõM[D\Ó[P²¿\Ò4ûKtyn.uwWû¨ˆˆÉ½™›o]\ßuB³?\Ë\ÑCşµ\ë^ğ‚x÷M¾ŸR\Ôl\ßL™\å…-ü¶\åo—yI‘\×r|\ÛXme\Ü\Ô\Ë1´?\ëWZ¼6º\å£\éi¹òa¶}*|?ú:¾Ï´nÙ»vÿ\0\áù¶m÷§\ëZ\Å\ë\Ûi	e¬Úƒ\éö\×7<›¼˜\İç˜\é¹w¯’¡‰Y·2ÖÇ¾\Ót·Sø‡]•#®\"\å‹dSy,›Õ¶nş&m»¶no»RM\àK/\ìM+F\Ó\ç»\ÓSG\Ùö;›FO:¨É»\çFVÜ®\ÊÛ—»P%¦üKÕµ=[Iµû[}§\ìhğ<L\Ã÷\È\ì\ç\Ï\r±vl}¨\ß3q\ë[\Ş*\Ô\îµ{?H‚\Ño!\Ó!¹¸\ènv¼\Óü¨\ß2¢&İ¿.\çÜ›—mZ´øg¦X\Ën-¯u[Kck/\Ù\Ëòeš\Ù·É¿r…OºÈ­±xûÙ»ÿ\0.Ÿ‡\ì´(n®\àû±\Ü[\ß+#\\¬\ÊÛ¼\âÌ¥Yœ\ïİ•ù··­\Ğ\á\ß\ân­\rÍ¼>V›±y1\\Àğ;y\Ó5\Û[:#«\ì¶M\ÈÌ&ı\ê\ëı\Ê\è|M\à}F\çD\Ô-´}nö\Úö\î\ínQ®¯Ë€¬\í#‘µwl*\ì6ú*®7U—øk§ùvğ&¥©¥º\Ão\Ìh\Éş\ä\È\Ó+\Ê\Û7ngg,Ë·vö­¯h	\â½\ïEšö\â\Ú+µDy\íñ¼¦ÿ\0˜sÙ—\åoö^-ö\ÍfûE\Ö.mµ«‹”{\İj\çP†WX.m£vM¶\Ãw\îÔ6\İ\Å\×tkóz\ï|ii9\Óo_O›_ûE„K•’C7\Ú\Ù\Ô\íÚ»¢\Ü\Ì~e\Âı\êÒ»øo\rõµ¤7º\Å\ï\îm\Ú\Æo\"\"[«f\Ø|™Sn\İ\É\Æİ¿+²÷­›ıK\ïµ=¶©¨Ø½\ÜQÄ ¬%\Ù]•\Æ\ï›\æşÚ´…s\É<o\â84=\"\ßí„Ÿ]õ6²W´Åˆ¼a¤YRv.]D\ë\å¨\Ú\Î];|\Õ\êrj3\Êe‡L{‹ù\ç¶k«i\Ù\0µ;‡Èj£\r¿u¿‰¶’~lª•“\Â\İi\Z\îŸuu+>¼¤^\Éªš\İ`ù>öß‘û\ÕsS\Ñ_TûD\é¨jr\Ïfö\É\ä\ÏòÃ»ş[*ı\İ\ëü-L.yÖ«\â\Ï\Ú\é¾ óµMSGaöCOÓ¤ŸahraŠ\ä–B¿36\Ü8,J\í¼59|7¥]j\×ÿ\0iº’\ÙVWGRÈŸ2•E\Îq»2\í\Ôm5›e\àmOH\Òmôı?\Å7+dt\Ée\ÖÜ¬­½ÙŸ~\ï\ï{š\é4h–ZM®æ·²…\"F¼ûW\ïQ¨h^¢‘\Î=şb7`‘ÁÀ“\Üúrh9ÿ\08À>\á°zf¯˜‘h¦™G\Î?ºp{~y\í\Üö£wôúóŒq\ÔuÀu\Ó\"G÷ı¾\ê\ç¯NŸÊ11Ÿı—\îı}=óÓ½\0:ŠEoı/\Ë\ÏZ\\ÿ\0\\c}ıº\Ñpş~\\÷ú\nB©ı\Ïö~\ïoO§Š]¿\çüõ\ëN¿ş;ü]}\Z£<´ş\ïş;KK¶›Ÿ©\ç\äu\çÿ\0¯Ò§™¢Ÿı—kµ\Æ9¨\äó]<¤H•S-\Ëdm\Úp ûÔ’J©<p½9 u>™\ÉqÚª-œ\ÑjW¯p\í\Ğ\Å@\Ëò¦\Ò\ä·üpÿ\0¾h•€\Ô^”TbŠ!¹-\\\Ó4/hSkZ•Ÿo&™¨Æ²]Î±!:\Ï\n>\Ü\í\Ï\Ë\í]t54û5o\à\Û\í2\ë\Å\Z«¾«\á©\å“S™­¢h“\í®»•’Mÿ\02\ìù¾Uûµw\â4wZÎ…¥\Ãao}oss/iqpa†}¹]\îˆ\íò·\Í÷~ò­wM¿ø>ı@Ñ¿\Üù>j\0ò­+f§‡/ux§»“\ÃzŒ\Ò\Î\é\ætkDVwû\ÌÊ¬~o½Éª³\ßx_Â°\èZ¿‡ÿ\0´ä¸¶’)4ø7:Z\Í2¥\Ç\Î\íµQ_\îıöşõ{FÇ—\Ï÷–“cÿ\0\Ïøñ\Ğ‚i!²ºûD÷¯o§\ëwBl­¦\ÙöÄ™µ[\ÍÈ‰÷™¶„F\Û\éóüµ\Ô_jz\Ú|V‰.˜\É»§Gv\Şbü–Ì¶»÷\íû©¿\Ì\İü?{wñW©,OÏ¹¿ûÔK½Q¾ò¢ıúV\ÎÃºŒ1Xÿ\0¡jIŸ6¿-½·“k\çC4{›0£¯\İMû¾º»6\×{ò|ÿ\0\ì\Ñ~V\ÄMŠŠ»v§\ÉF×—~ÿ\0\â¦1ğÿ\0~¥¨aWù\êj\0+ñ‹m6|¿ñ%Ô¥û\ßzD{M§\ì\ï~{e½k¯ª\×\nj>\ÄŞ«µ[oÜ¥k\â‡\Ä>&¿¹ğ¾Ÿs½m\ï\ì\ì—O\Ô[Ì¾M®\ï¹\ÙSc6\ÅÜ«»û»şz\é<3¬\\ŸøEc\Õ.®\ãíš•£¤÷[\ßl2Êˆÿ\0\Æ\Ë\åª\îş&]\ß~½%O->DØ¿w\åû•R\ëA\Òn®b½›K\Óå¸‡ıT\ïo:|Û¾VÛ¹~Ÿı\ê,+œ·ƒµ+R´\Ò\çÔ¢\Õ_\ì2\Ëi¨[\Ş;­\Ü*\è®ò£»mugO›sg\çÛ·\æZ\í\ZJÏ²\Ñô2Yf\Óô\İ>\Ò\â\í³4–ö\éLÿ\0\ŞfUù¿\àUl~\ëı§ş\Zc%ÿ\0Uó¿ß©cO7\çz#ø\ß\ïÔ•@4->’–¤m£m¨\İZ-S+2Å“ı[\ÔOü\é?Õ½G+U ¢QRO0±ª¢*( ¡dş:«$»\İşıP³¼Q\ìzi<­ÿ\0\ÇE(“÷U\ß\ßE@,Ñ§ñ¾Úûé¾Š*@³;uU\0n¦µPY¿u÷)şo›÷(¢€\å§\Üş:–$ò¾ıP»¨\rE\0\í\Ôn¢Š\r\Ôn¢Š \r\Ôn¢Š\0l­û§¨¥¹E 8/rŠ(©9Oÿ\Ù','image/jpeg',9,'2026-11-10 19:45:57'),(2,11,'Fahrenheit 451','9781451673319',10,1,11,'compra','se hizo una compra nueva',_binary 'ÿ\Øÿ\à\0JFIF\0\0`\0`\0\0ÿş\0;CREATOR: gd-jpeg v1.0 (using IJG JPEG v80), quality = 82\nÿ\Û\0C\0				\r\r\n\Z!\'\"#%%%),($+!$%$ÿ\Û\0C				$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ÿÀ\0ô\"\0ÿ\Ä\0\0\0\0\0\0\0\0\0\0\0	\nÿ\Ä\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁR\Ñğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹º\Â\Ã\Ä\Å\Æ\Ç\È\É\Ê\Ò\Ó\Ô\Õ\Ö\×\Ø\Ù\Ú\á\â\ã\ä\å\æ\ç\è\é\êñòóôõö÷øùúÿ\Ä\0\0\0\0\0\0\0\0	\nÿ\Ä\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3Rğbr\Ñ\n$4\á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹º\Â\Ã\Ä\Å\Æ\Ç\È\É\Ê\Ò\Ó\Ô\Õ\Ö\×\Ø\Ù\Ú\â\ã\ä\å\æ\ç\è\é\êòóôõö÷øùúÿ\Ú\0\0\0?\0ó[–û=»\Éş\ÅcxY|\Ë7‘ÿ\0\ç«V²\Ş^—/û•W\Âkÿ\0´ÿ\0}ª(¿r‹û\Ï2•›2\Íå¿“³\Íÿ\0o\î\Ğ2UZ+KS“P–\Å--7ÀŠ\ïû\Óü_İ«Z\Å\å\îŸn÷pÁ‘\"om\íÿ\0 \Ğ‡—OX\ë:\Îúo\ìùn\ïcŠ\Ù\æü»\åÛ»æª­®jq\Ùı½\ìS\ìŸ\äo›o÷¶\Ğ\ã/ı÷FÚ¡®—\Z\ÂX§\Ü{´oÿ\0}\Úv›©hIw²4ò )ûÌ¿z¨{i6\Ö5««j\é<66W\Í÷\å?\Ãş\ÍZ¿ŸS·\'H-6$[\åù\Ş_\îÿ\0³R#AWË¥Uı\ågh÷Wº„q]¼q}t\ßò}\ê\ÒûÏŠ–²5K\Ë\Øõ‹KY\"\ÏF»N±Ô®£\Ô?³u+\ÍÙ¾\'O»-\0j\Òö\ëNŸV\Ö$»‘/­\àH.\Z/õ¾\ío[DñÆ|‰$¿\Æûv\Ğ\é)\ß\êÿ\0ß®{\\ñ\éz¥”	¿\Êû÷\î·Ê¿\î\Ğ\ÛRn¬z\æ\æ94\Ø-gò>\Õq±Ÿn\ïá¨õ\rP´k¿µ\Û\İ?•÷vº·\ŞZnRm¬\ë“iw‘\Ã˜Ÿ\ëe\Ùÿ\0<—\åù¿»Sx†\êh\í\ì¾\Ë;\Ç\ç\Ü,[\Óû­ı\Ú\0\Ù\ÛKş³ûõ\Ï\İı·C¸´‘ï¥»Šy~\Î\è\ê?‹\î²\Ñ\âº\Ó\ãŠtÔ®6Op±l\ãj«v€7Ú’¡µ\ì\ã}÷r\Ïş\Ü\ß\Ãÿ\0\Ø\Ö\'†õÉµË¸\î·\ìŸuÅ¿\Íü;¶\í_ûæ\ÚzFôª¾eI\08/\îŞ«º½\\O\âª\ï\ï(´Š©\æT»|\ÊM¿¼ù*„F\Û)~zq_\ŞQ\å\Ğ3\Äò–™\á5ÿ\0‰:¾\Õ\'‰ş$òıúOÿ\0\È?\ßoı\n¥¶‹O\ÛNZZ\0\ÄÓ¿\äd\Ôÿ\0¹²*›\Ä\ëÿ\0ş¡ÿ\0\\¿öjn\Ç\âMNGöl‹eA\âÛ©£\Óå´†\ÆY<ôÿ\0\\Ÿq~o\âªmWgü\"rÿ\0s\ì«ÿ\0 ­\\±¶K\Í\Ş	£ı\ÓÛª?ıóT iµ\Ï\ÜZ}’[I|¯)<\ï\âm¿\ÃY¯­^É£¥Ši7±\Ü<_gß·\åVû»·P!ô½aã²÷ıƒ\ÊMŸÂ»¾õtXc\Ğ\í>\Å÷<­ÿ\0ğ/âª¶š|\Ñø‘7\ï‘Ábó¿¼Û¿­.‹k6¨]Ø¤oöOøø·\á]\ßy(•\áıOS·\Ó\Ò8t—»‹\ÍoŸv\Ú\éo—ş%÷ÿ\0\ç“\è5\Ï\è:\Ä\Ú~>“{#\ïoıªè¥•\ï4w“\Ëx\İ\í\Û\ä¼¿-\0Qğ\Çü€ôÿ\0ú\å[!k3\ÃQ=¾‡eÑ¼o\åV¦\ß.¤;ÿ\0ù4¯\îyR\ÓõòüI¥Gÿ\0]_ÿ\0¨5\ï¶\Û\ë\Z}İ­Œ·h‰*:\'ûTº|\Z†¡¬&¥{\ÚEjŒ‘Cş÷ñP3;Hş\Öó5\ìÏ³ù_j—ıw÷¿\Î\Ú\ë-–o³§³\Í\Ùóÿ\0½\\½“\ëZ\\š„p\é/:=\ÓJ¯»oŞ®—N–k‹4’ö\"_\în\İ@µpú…İ”š†»\ì\éº-¼_\ímş*\î¶\Ö¡¥½¼¿m´O5\î%ûÿ\07Ë»Š\0\È\Õ.\Ş\ãOĞ®\à\Ùö5v§û[v\í«\Éc¬\ê\Z…”š„vğ[Ú»Kû–û\Íü5§‡\ïmã²\ËMjM/\Şÿ\0–_Ş®´/—%\0pú¦¡kı¹ª\Ç{?–Ÿgû,[w\Şù‹Tw3\Íy\á=*D‘#—\íÿ\0÷w*\×C¢\è¿g“P’\ê·\ÏpÎŸ\Åòÿ\0\ê\Ï\r]}X\áÙ³ûInş¹-P\Û\Ù\Í\'ˆ\ÇV¾y\Òô‹—j\Ëı\ïÊ¯øÁ\âWı}Eÿ\0¡U\ÍcL}BK+¸vGqkq¿\çş\ïñSµ\Í9õM?È‡do\æ«ÿ\0\ß-@ˆ\î~Ï£\ê¦\ÍşS\ã\ß-qöz¥µ¼úƒ\Í\İmû‰ZeÚ›_­v!\ÓX\Ó\Ş\Ñ6oŞ¿Ù©5½#ûSK{H|¨\ß\åÿ\0\ÇZ¤\r$Zz+\Ó|¸\ÓeXhğşó}B\ëûÏ’¬Â¿¼z…Ö„´\ÜT›i¿TX¨÷ÿ\0\Ó7©ö¥&\Ïöÿ\0ñ\Ú\0\Ãñÿ\0Ä\ãıÊÂ‘ÿ\0Ä-ÿ\0\í\èUkXO3K»ÿ\0qª\nÿ\0\È\ßûŸ7ş…RÀ\Ù_\ŞT›¨\ÛK·Ë m.\Ú]©J‹@­_÷>\å<l£ûô\0Ğ´›iûi»h›h\Ûûº—o™L\Û@\r§m¥Ne w\ÄR\êÚš}†™wöG%\İò\îû´\Í6ûS\ÓõD\ÓugI\Ò\Şm¿\İû\ËS\ê«\åø“Gÿ\0¶¿ú\r7[_/\\\Ñ?ß—ÿ\0Aª:µ\İr;‹\èu\'´‹{$Iş\ï÷«k\ÃÚƒ\êš<S\Íş·\î?û\ËUüû\Í\r?ë¬¿ú/ƒ•?±\ßş¾%ÿ\0ĞªDlªşò•WÌ’±Òõt\0\ÅZ\ZŸ¶—m\0C¶ƒş\ßÜ©~O†T öÿ\0r“m;Ë¥ dmû\É(\ÛO\ÚôPV¬Dµ\ß\ŞT±ªT,IûÊ†Uz²÷•ªşcÿ\0r©ƒşZR2Ô©Œµ@&\ÚO.Ÿı\Ê\\§ûË¼_2\Î_÷ÿ\0Aª>ÿ\0=¿üÿ\0B­öò\ì\å\ßı\Æÿ\0\Ğk;\Ã?ò·ÿ\0?\ÅR\êµ.\êYüº3@ü~œµ\ZÔ‰@_\à§5#-9–€¶¾fús/—÷\è\Å\01\èÿ\0–\í\Ó\Êşò”/ı÷@ˆöı\ÊP´Î—‘¤È’%Kûºs\"ƒPT\Ó\îôøwO5û¿7÷©º~Ÿ©\ê\Z\Â_jq¤	ú¨Sû\ßŞ®‘¶TpÊ—¤‰÷?øšrVšˆô´X\ÙAo=¾öx\ÛûÕ»\á\í1ô½.(&ÿ\0[ó;ÿ\0¼\ßz´¤t7‘ş\ânzlr¤‘¤‰÷ÿ\0\à-@\ÇRâ…ª÷ºŒ:|i¿\Í\Şû¶\".\æù~õ\0YZ]´\Ø%K‹t>\ãü\éÿ\0§m öÓ±O\ÛIş²€\Z´İ¾fúsP\Ë@jj\Óö\Ò5\0jhöT+SEş²¤	\Óıbr¢uò÷Õ„_\Ş%A2şò©/ıñMo\İÿ\0~¥¤e D{\Ü\ßIòR®Ê“÷\ìU\ç|A/—£\Ë\'ûú/‡¢òô{O÷j§Œ\'òô½Ÿ\ßz½¡¯—¥\Úlÿ\0KRÀ\ĞU§şı5ñ\ÊK‹”·ı[\ïß±ıª\0”-JŸ¼¬¿\í”ò\Óe¤²Kóo‡\Ñmm­Zp\É\æF’&ú\0“\ê¤Úœ1\Ş$	\"ÿ\0›\î\íşõ]·•.#ó ‘$J\0VZkU-CWM>G\Èy^WOùd­S›¸#‘\ãózµ@\îû•OX\íô{¹\Ó\ï¤M³ı\ï\á¦\Ü\êö¶vò\İù‰\"\'û_ø\í>{\èc-û$I\İQ>o½ºÍ¬©o\åX\Şù¶‘%\ÃoM\ß\Â\Ñnû\Ëş\Ö\ê†\çPš;{\ßùo\n+Å½ùU¥e]«ş\ï\\\×Qq%”—‘o’/´\'ÎŸ÷\Ïÿ\0U®õ}28\ÒD’\Ş»\éò«6\İ\Ô[K4¸\Õ5	&’]‰q±qÛµ‘wVMŸ\Ùmô7?\ç\ãÊ—\æ?ºù\Ûk5ti©\ÙGo\çù\é±\ßfÿ\0ö¿»şõ7ûCOù\ÌM\ï·õû¿\0s·\ÉıŸ\Ûg}Ÿe¹T~Ö«\ãÿ\0AÛŠ—Odó-÷\ïûo\Ú \Ù\×ıS\"ÿ\0\ã¿zº–Xm\ãydq\'\ÏUmµ«).%\ß\"GWÏ´\î\ÜÛ¾]¿ğ\Z\0’É’K\ÍCd’\ïóW~ÿ\0º¿/ğ\Ó5™a4\ŞX<ôeûJ\Ë&û\Û\Z}õó\ÇıŸ=¯•%½Ô«¿û\ßwm^“÷v\ï\'–òP=ö=–øü¿ôuÿ\0\Ğj\èZ\Æ]f\ê9	­\"ûC¤^R#}¶ª·÷qŞ¬O¬\ÍŸ\çı“\çK…·•7}ß™Wwû_z€4\é»k6ma\ã¼xü´û<7n\ï»\æ\Ü\Ûv\í_øjH\Ïo³\ï\Ğ6¥#-eM­]\Â>ú’Z\'›óo‡w\İ\Ú\Û[ı\ï»ZOrE\É\å¼ÿ\0\"µò\î \íû”ÿ\0\0¬;\Ír\ëO‘ü\è\âû’ºCüJ©÷Y¾¿û5ZÒµ\'¸·–I§· Mşt+ş\Î\æùhMªHÿ\0wQ\Ã\"\\F’&ıŠÿ\0÷\ÕL«@Sıbÿ\0r¡™~ıL‹û\Ä\Ù÷)’P€­Gû•#Rmò\ê„G¶›Š•Ø¦\í\îP3†ñ\Ìÿ\0»Š?÷·ô¨ÿ\0\â_iÿ\0\\—ÿ\0A®SÆ²yš‚\×*\ì\ìË·‹\ï\ìò—ÿ\0A©aRªjO\å\Û\Ïo\'‘p®\èŸ{o\İùk@-J©@\ÎNm=\ä‘çº°¸‘\'Ø‰ü,\Íòî®–\Ò)£³Š9¿\ÖùK¿ı\íµiWûÿ\0r´\ÅOk4–öñ¥\ÄrÁo*Kû¯¼Û—v\Öş,ü\Õ\Ğx~\ír?›±\Ù\×D#o»·\î­i\í§ö([GûcÈ\\}£\Ê_*\æº\ß7\İ\áü\ë&ö\Öo2\ï|rüğü¿u¶ü\ßy³]§•\åıú¬\ê’}ÿ\0¹@û\ÛMq§\Şÿ\0 ¼{\í\Õ8ù¶\Õ\ëÈóO‹d¿\í>\Ïîªº\Õ\ßõu2-9¹´Ë©5\İ\İ\ì{5>a\åm\Ûòÿ\0µU\"\Ğuö¢|\é·\ÓøZÿ\0\ÙZ»H£ª:¤·6÷\Z“³\Ê{…Š_\ï|\ßİ e\Ón¤¸I\Ş/ı=n6uV-»ª=GFº¸\Ô%\ÙÈ“¼OòKµWo÷—ø«U\ëûq\ày\ìÿ\0gŞ‰·ø·mjŸR»}>4‘ y\İ\åX‘7mùš€U³{\Í.\îvy¯l¬K\Í3S\Ô5¾{`Dx¾He\ÛjÈ­µ¿‡–ºM6\ëûB\İ\İ\àò%IZ\'MÛ¶²ÿ\0µT.<P–ú\Ã\Ø=Œ¿\ëV/;¿6\ßş-h\Ó&G\Ó\àH7K¥•\Óv\æU\Ş\Ì\Ì\ÍZ\ï¾8\ßfù?\ØşõZhşı7oıñ@•¥¡%¿\Úæ±—\í¿hŠ\á÷\ã\æ\Ú\ßq~‹W%Ó®¤\Ò\ïqûÛ«¯´y?\ì\ï_ş\'ut;i»¨˜¼Ñ¦“X–t°yî¢•.w\r±*ª«|¿÷\Õt{v•%h4ù\äğ\İÅ§—û\×óö\'û\ÎÌµzh\æKME\"oı\îÿ\0\á]¿\ÃW±F(–Ô´­CP“\Ëx\âù<ÿ\0ô\ßyYYQvÿ\0À¿ñÚ·cg¨y—wo¼r½º\ÛÄ›·|Ê­ó7ıõ[›?¿E\0V¶Wò\Ó\Î\Ù\æ\ì]ÿ\0\İÿ\0j¬%5–œ¿¼ 	\â_\Ş%6Vı\åKğTr¬zq*o÷)\íM\ÛL7ú\Ên\ßó\ÍH\ßøıØ©\Ëü_û\Íaÿ\0\ÜZ\ï\ì•ş\Ïû‹ÿ\0 \×\âÿ\0ùÀV½\n\Ò?.4ÿ\0qj@°«ûº‘V‘?‚”¬ª\0\ÛCÓ©¬´\0\Åj]´m§a\è›|Ê†UO’“U¹ûŸ,ÿ\0\ÜOüz°¿´/n4ı>H|­ÿ\0jû<¿1ûÁ™\ï“@jµ2-c6®ñ\êh›?pê›N\æİ·\æVö\İMş\Ú\Ô-\í\Ò\îx\"ò§·•\×f~V_»»û\Ùı(}WËª\×\Ölû&Í‘ù/ıó÷«.U½ûwÎ’\ÒIR\Ş\'û§k*£7\İ\İK¬^]\\i÷q\ÚùQ¢X-\Ã\ïş-\ê\ß*ÿ\0w\î\Ğ¿\Øÿ\0\âh—\É\'ü»´_÷\Ó+\ìµ»§Í¨[\Å®ø\İ.\"—÷v·\Şÿ\0j©\Új÷¾e¼\å}‘\î>Ë³o\Í÷~ö\ï÷–´µk™­\í\âòvy¯q_:\îû\í¶€\ÛHš\Ş\ŞX\Ò\ïı\"{´<\Û¼Ë¹vÿ\0º»j•\ï†&¸\Ô%»IcÊ¯óÿ\0½ÿ\0\Z§&§©\Ü\\=Š}Ÿ\íò\ï™\×\åeEV_—wËÿ\0ø\íX³\Õ\ïu‹x\ícŠ8\Ö+§\ßş\Ó2²­\0h\"Mö‹‰DòŸo”Ÿ\İşö\ï­Hô\'ö‰cx\Ó\Êùv>ï™¿½ş\íg\ëÚ›\évi\"ló]ö\"m-»ı\Õ_½@Z˜\Õ\Ìj:\ÕÖ©£\Ë%¬qFŸÙ¿h—{\ß>\åÚ»}6µMe\â9®5´x\"ò¼\ß+~\ã»\ï2¯ş@\ï)UkûNö\Ş9vA›ı¤¶¿x\íù•[wÿ\0cZšU\Ô×‘\Ë\çlX.\Z\İö}ß—ø–€\'e¦\íşıNWû”\Ï.€#ÿ\0–oH\ËRSvş\î€\"\ÛJ‹N£oß 	\á_\Ş%6e©m÷ù‰÷öTs/\ï„*´İ´õÿ\0b–¨\Ûÿ\0£şû§¹²›º€<\ÃÅŸòø\rz,?\ê\Óı\Êó\Èsşù¯J~\åf‰Oÿ\0YL§?ú·ª¾j|ÿ\0¼O“ı¯»Iğ\É’$‘w\Ëÿ\0}V^‡gevSüŸk{ûÿ\0»\æm\ßŞ¨¡\ãğ\İß‘\'–\èó¿\İ\r÷]›n\Ú\0\İ@Z\çµNö;.\×\ÍıÅ¬W¹TÛ¹¿½»\î¯\ËÚ§¶¾½’H¤yşG½k}›‡\æ\Ûóz€4\ï\ìa\Õ-\Òû›\Õÿ\0\ï–û­Tÿ\0\á†;w‚ûG\Úf>Vÿ\0f¨Z¼\Ú~,‰w,÷­|£\åı\ë.\åÿ\0h\Õ\ë9\ïd\Ó\å\ß\'—*\\l\ßs»¹~ö\ßû\çşù MÌ¸ó\Ò\î\ãg\Ê\îœ~õ—û\Ôõ\Ól®#Š\Ñ\çI<„•6#›w_ÊµBşó\ä®f\Î{«=.ö\í ·ù}“ú\ß\â \rˆtˆcû\\~|²=\Ô[%\Ş\ß6İ¬µ\r\ï‡á¸\Ëy\î#O³ı•öK·r¯÷ª¼½¾¸=\Û\Éş©>\âùdoš³5]j÷\Ëy ß¾\×Í•ş`«µeÚ¿/ñ}\Ú7!ğı¬w	:<»_5!\İò\îÛ·v\ß\ïUZ\Å5H\â±y~ø®?\à(\ß5R‰¦“T\Ô\äó\åò­R\'H‡\înm\ßZ\É{—M>\í/¥\ßue÷\İş«|\Ñ\î\Ûıß½AF|5e$i²K¸<\ß\ß$¿7Ï·;›ø¾\êÕ‹{m3O¼Š4#—\ì\ën»º¬Ì´º#?™¨@ó¼\ékq±şf\ÚÈ­µ›ø¾õT’->MCXûn\Ïõ¶\ß>\ï™~UÛ·ş@\Z‰g\å\ŞK?˜û\çEù?‡\åş\íU\Õ4\Ä\Ô#‹÷òÁ,½½÷vµfY\ëW\Z„»<ß³\Ïos,[\Ø7\Üm¿*¯\İş.¿\ì\Ò@×²Y\é[\ï¥ß¨<[\ßhÜ¿ºfeO®\Ú\0C\à\è~ÆA}w\Z}Ÿ\ì\ïóŞ®\æe\İÿ\0}RÁ¡\éñ\ê‰şş\æù;†\ï¼\Í÷\àu«¢J÷\Z™7\ßIe‹÷¶;*µfZ.Ÿr\ß]lX5)ö?ñn]\ÛWı¯—w\ËAeó¡\Ã$\'˜ÿ\0ñô·¿ğ%]»ñÚ±if–~n\Ïùo+Kÿ\0}û5\È\rkSû<±ùòÇ¿\ì\Ò\Ä\î\È\Ò\ívo\îü«ş\ïjbøƒP“Gù\'–K´\İqü¯”±+|\ßMÿ\0v‚\Ùÿ\0Ø¨ZtGß³z}ÿ\0öŞ¬¨%º\Ô5Dÿ\0Kx\"KX.6\"™™›wşƒSYo\\\Ô yD\Ù¦ÿ\0\áİ»v\ßû\æ€$:½”vnûZ}“ş{÷j\Ú\ì“g÷+ÿ\0Š_Sş\çú_ş„\Õ©u4q\Ë\"]\Ë\Ú\ØEqnÚ¬\Íı\ï\ï}İ´\ÒQ·÷”¡|Í›\é[ıŠ\0’İ¼½”\ÉW÷•5²şóıºl«û\Ê~ò‡ı\İ?m2¨Bm¨¾z™¿\ÖQ¶€<¯\ÅKÿ\0\Çÿ\0€×¤Æ¿r¼\ëÅ«\å\ë‰ÿ\0¯GZ$£ä’„T©\Ğ2†‘e§\É\æC–ÿ\0\ï÷¾_\î\Ô\Égk¼°yº}\Ûÿ\0\à_z¬Ğ‹û\Ïö(Œú-•Ä‰$\Ğ|èŠŸxı\ÕşşõMı™–Ÿ»O’_´À¿½VB\Ó\è‡öŸ$r\Æö‰²w\Şÿ\0\í6\í\Ôô\Ñl£·{O!<§}\ïş\Ó}ïš¯mû”»h¿¼ª‰¢\ÙG$» Mó\î\ßÿ\0ûÕ¡¶†Z\0\Ìş\Ã\Ó\ã\ËKH¶|¿ø\ïİ¦¶¦\\G\å\ÍcÏ›ÿ\0ùšµ6~•c [\Ú\Ã\"F›\ßnÿ\0øÊ´\Äğö“ı–6ÿ\0:yOòı\åİ»m\\‹ÿ\0«*´¥œ6qùv\Ğ$	ş\Âÿ\0Gq£\é÷	w5ŒRKòü\î¿7\Ë÷*¸ªõ.\ß\İ\Ğ8ğş™op÷icr¾\ïŸıÿ\0½ù\Ó\ä\Ó,®-\â´xH Û±?»·\î\í«²\Ôgı]ZZ\ÃgnC\ZGUo\ì}>;‡»û$^küûöÿ\0yv·ş;Z\rP\ËAD^\Òm\ä&\Æ/\áõşÜµ#øsI’4\ìbØŸüN\ßıV´† \nX\Ú\Û\ìòcH\ß\ÊX¿\à+÷V¥X\Ìyü´\Şÿ\0&ÿ\0÷~\íJ\Õ\0B–\Ğ\ÇÆ‘¦\Çİ¿ş÷©·\Ze•\Æ\Ï:\Ò)>DŞ¿v¬S\ÂşóıŠ\0M´\ÖZ‘©6\Ğ­\×÷”L¿¼§Â¿¼Jl«\æHô !\İI¶¥¤ª?“şM\İR\í¦\í©\Ìüg—¬EıÍ‹ÿ\0¡Wp\Ş<Wûe§÷?û*\í­¿\ÕÔf¿\ê\é©OZ «ÿ\0}\ÑU®¯’\ŞDà¸‘\ßşx©m¿\ïQk¨¥Ä\Ù.#ö\×m\0\\\ÛB¯\îÿ\0Ûª\ÛIişƒ{ÿ\0~\r=µ_.\İ\'û%\ß\Ïòlò¾ø\ĞõZk*\Û_{‹„û7P\Õ7¼_-M.±\å\Èû,ow¤»?\Õ}\ïö—ıš\0\Ñ\Ûû\ÊFªCUó,\Ş°\İü›~O+\æù¿\Ùö¨GˆMŸñ)\Ôÿ\0\ï\Õ\0jm¥\ÛT\îµ?³\Èñı’\îO÷\"Ü¿÷\Õ>\ÛQ{6G±»û\ëó7û´r%«q­b¦´ş_ü‚u=ÿ\0õÊ®\Ü\êOo\Z \İÉ½ö\"ı\ßö[ıª\0\ÑUÿ\0¾(fªÚ¿\Ú$xş\Ã{É¿÷\Ñmÿ\0€\Õ9¼Bşbl\Òu\rÿ\0õÊ‚\Í&oûâ¢ª·\Z»\Çn’}†\îG}\ß&\İ\Íò¶ß›ûµ\rª÷$ocwıv‹\åZ47T2ÿ\0Rş×šI%ÿ\0‰M\ï\Éş\Ï\Şÿ\0v”\ŞMötŸ\ì7{÷ÿ\0©\ãu\0Ye¥ù\ê„:\ÕÄ[\è\×p&ÿ\0¿¹?½K6¥{\Zi2ÈŸ7Ï¹u\0\\e¥\İûÊ¤nu³¼Ÿaÿ\0Hùs\æø\ÍPÿ\0hj\Şgü‚Sşÿ\0Š\0Ô¥ZÏ»¹\Ô\ã¸&\Å\'O\ïù¡jk	õ	7ı¶\Ò(?¹²]\Ôm—ûô\Ús±H«@AL›ıcS\áZm\ÇúÇ¡ú\Êu8--Pµ÷\Å9©›\é\0y÷<\ËO\î|\Õ\×[lòÒ¹_¯™¤Ÿ\íWQmş­?Ü¬À¼´õdò\ê%¦\Ü\ŞC§\Ûùóo\Ùş\î\ïı¨^6¡‰ö(\â‘?{m¥³—S’Oô¨-\ãOö%\İS\ÛÎ—¤‰¿gû¥i\Ö\×)q\æ\ìûğ?”ÿ\0\ï-\0Qó5\ß1öA§\ìÿ\0x\Ô\Ò6­ö4\Ù§\Úÿ\0\æ;6ÿ\0½V-o\â\â\âówÀûz\íû\ßu—û\Õ%\å\Ê[\Ç\æO÷>Tÿ\0¾›j\Ğu¤º\ì’E\çÁe\å\ÉOİ¥¸mw\Ì&;/+\æÙ½üµ¶ÿ\0\ßt¯\"G\Èÿ\0q>ø\n\Ğ|-«}ü\è->Ñ½v|\ß.\ß\âÿ\0Ud“\Ä\ÇŸÿ\0}\Z¿s¬\Ù[ÆtŸ#¢ÊŸ)m\ÊÍµv¯ü\n¦°¼‡PÌ†G‘>dû¥[wûJ\ßv€*Ü¶§\æ~\æ;O+ı¶;ÿ\0Ú§\Ûÿ\0k}_:;/7g\î¶1Û»ı¯üv´ivùtš\â8ölƒO\ßÿ\0]Oÿ\0VoŸV\Êû,Ÿs÷¾s½ş\ÍO¥\êP\ê\ï<>li½¢t™v²²ü¬»j\ÛÀ\è,È¶“Z’\áş\Û”qlm»³nş\Z§»\Ä\ÒIş¯O\ÙşñjÜ¸t7\ß÷+&\×\Ä6Z‡›ö_´H\é›\äùEY—øYU¾öh e\â\ë¾]¿\Ù~\Å\æ\ío´o\Î\İ\ßÃ¶›dº\ï\Û\í¿\Ùÿ\0gş?\';«B\Æñ58®\á\ß\å?Ü£\íP\Çx–Ÿò\×cK³ı•m´Ÿ2xƒ\Ì&M?föÙ½_\îÔ·+©\Éoû™-#¸\ß÷\ß;v\Õı\ßûô\"À(,Æ\rw\ÌO:\î\ËgË¿dG\æşõIw§$\ä_\Û\Çır\Ü\Õf\ÛP†\â7‘>\çš\ÑÀ‘¶ÿ\0\ìµ2­š\ÛP’\ŞXşÜoË±ü¯»ÿ\0ş,\Õ#¦k_&ıe?‡şXöU·ÿ\0¡\Òr€3/´ıB\â\ãÌ‡Rx\"\Ù÷<­\ßğ*ŸO±º·\ßö«\ç»ÿ\0€…\ÛW)V€\r©t\ïùf”*Ó•¿\ïº\0’¤¸Wóœ‹N¸_\ŞP€¬¿\ê\èe©6ùtÆª¿\îS~J}\é\0p>?_ô;ú\ëÿ\0²\×Gkû\È\ÓıÅ®\Ç\ëÿ\0ø¿\ë¯ş\Ë[ö?\ê\Óû›¤\ËUµŸù\ŞııŸgoı­¯úºq$÷\ì\Ù@¯Šµ4\'Š„²ûB;\ÊWwğ\íOö¿\Î)öó\Ãö\ÉdIÿ\0\Ó´¢Mo\Ì\Ñ2®\ï—ø”\×M-­\Äi\çÁ‰÷>u\İR‹8|\Ï1 ‹÷öô\Ï\êó½¿ü$C÷ü«oş%¿İ¬û˜MP\ß=¼‘Auş\æw’(¾e\İó7¯\é]«Ä[\ïıÿ\0—\ïmÿ\0Ğª•-–±§ù\Úyv\î\íû—ˆ/\İm¿2ÿ\0Àh,\æ|O¨Z\Û\ÜEö[»x>\Ëo¶\ï\æ¿\ïUŸ\î§Íµ¾\ï-óWaªlşÏ»ş\ç\Ù\åÿ\0\ĞZ³µ}B\Ê\ŞD‚k»ò\"óeØ©ûˆ·}\æ\İı?»V\\†KÏ²y\å<¿gûOSK·v\Ïó\Çj99ã½M}\ÚN\ïom\å~\ëj¯\ïcû\Û~õvZ>Ÿ5œw]N’\\]JÒ¾\ÅÚ¿uUv¯ü¬µ´?&ø\âØ›v|£û\ß.Ú™Y$ß²€9k\éì¿¶/~\Û},=·\Ùö1İµ¿ºŸÅ–ùMG¢N’j\Z|‰;ı®ynRõ<\Ò\Ûv\îÛ¹‡.+WS×´?X´±½\Ùö‰ş\ã\íÿ\0Uı\İ\Íü9«Iwe¹ö>\×ö´;\íwvß™½\è,\ÈfH\ãx\æ“Ë´}jT¸}\Û~]¬Ê¬\ß\Ãóm¨¬ÿ\0\âi&Ÿ\Ï,–÷\Û6J{º\ìù¾óWG©\\Ã§Û§ú\'ó\Ü*%²c÷²·\Íü_÷\×?İª’kVVú\\Z”Öoû:C´+«3mÚ¿÷\Ïj\0»¨ş\ïO»\ßÿ\0>òÿ\0\è-X\Ş±ºòôû\ë\Ù\â“ı(“d[~V\Ú\ß7÷¿†¯Í¬$z‡\Ù\Æ_+\Íû+\Üñ·\Íeû»~÷û55\å\Úhöo?—ò&\ÔDO\âoºh \á5\Óş}*\Ñ\äŠ{y\ß\í/¿\ï.\ï‘vÿ\0\íŸ\îÖ•›iòkš%õ×•\æ\İi«±\ßşZÊ¬»\àU¨\Úòyii¯%\Şö‰\í7\'\î¶mgm\ßu¾V\\z\î­K)!¼·Š\î\Ó\Êx•\âù…¿ô\Z\0óû	f·\â\ï÷RJ‘\\ı¢Y<\Öù¿\å«,S\ìn­c‘\àšDŸLKõ\ßû¢±2´-·\åş\îÿ\0\Ì\×¹$ûVÿ\0Â³5ı]4==\î\İGşSø›n\ê\0\ã!k(ôùcš7\Øöóı‹÷G\å—Î“\îÿ\0u¾\åz;\ã7ııŸ?û\ß\ÅY7%µ·\Ò\íï¼·ÿ\0J•bH_\åm\Ì\ÛY[\é[²8\ß\ÜJMÿ\0\Ğ\ë+J\×P\Ì³\ìû?\Ú\á”7•ı\Õ\î¶\ßıšª\Úx‚\ëT\Óâ¾²(\å—|\×9ò¢TùU›ı£A@¿\ê\é\ÛïŠ­¥]¾¡§\Ûİ¼<\éÿ\0\Øü¿\ÓÚ²_š=÷\Ï\Zgù²Û¢\'ú\İÈ­ó3{\ìoüv€:§¥c\éZ…Ô—Eï•½\í\Ö\é<Ÿ\áVùvÿ\0µ\ïw­€¿ß 	Ö:\Ñÿ\0,\éó-\n¿úF\ËR\í¤ş\åPˆö\ÑN¦\î g\ã\ïù\Åÿ\0]öZ\Ü\Óÿ\0Õ§û‹X>?o/OŠ?úk[ºj¿\Ù\Óı\Ê\Ì\r©‚\Ô)S\"\Õ*Ô›i¡jEZ\0Z\È\Ò\äş\Ï\Ò\î\çx\ŞO\"\â\æ_“\ï7\Î\Íò\Ö\Ï\ÉF\Ú9a¦\âõ\Ò\Ò\âD½\ÓV(¶)o\Ş\îo•¿»÷\é¶\ĞM‘i¯¾jjKq¿iòš%]Û·\ãµ\Õ\ÑAˆ\Ì\Ñ\î\ãHwtØˆŸ{s|«ş\î\æ\İPx^	­ôù`½şÚ—\rö‰¿\ç»}\í\ê\ß\İ?¥k­=hñtsG\â\Ë/\"\Ò_6}»tS\í_™Y}»ûV—¦\ê~4–y£‹e\ÔS¿œ™û»\×bÿ\0³Š\ëv\'˜›şı?o\ï(–ñÍ®±gi#Áqö{]KÊ¸Ù\ß*²\î]Ÿ7\Şe¬k\Í3P¸\Ñ\âü\Í>\ÖY~Î—;ü\ÕV•v6\ß\âù~^{n¯B†$?’4?\İ\Û÷©ûh,\äõ{\Íb\Ê7´¸\î\Öõ~tWò\Z-»™›ø~™\äUny¯,\î\ã†\Ò]ú}\Ô÷Öª2»lş÷\Ëú\×F\ÖTN´q±ù\Öú\Ã\ë?a»’\ŞygØ‰\ï~dU¶ÿ\0v5oxz\ÕôıO´›ıj[®ÿ\0öZ´Y|\É)Z€0tı\ìõÿ\0³ö|\ß\êlü¶ù¿\Ú\İùú\Õ?\Ú\Íq\áùvA\ç¢|\ïı\åù[\ç_§ò®¿yH\ë\æPs\á\ÍZóC²»ó\åó`M‰m2ùµ¥Vû\ß\Ş·ğZ\ë\'¹y5\Ó|¿1ç·•ü\ï\î\íùWw\×uhm¡h±ğ\Ó\Ü[\Åhö2é¨–e¸™6~ı·¯÷~÷\İn¿Ş«šNŸ©\éz|±¼	©o½\Úò\ã\İteş~ñÿ\0zº]´\í´‘¤A{§\ézU¢@›ÿ\0\å\ã\æÿ\0P¿3|¿\Ş\ÇÊµBmö\ã}‹Æ‘\Ú%\Ä÷	s\æı\í\ê\ÛWo¶ö\ë]J­\n¿» -O\Ô#¼K»\Ø\âƒÈµ[Tò[v\æİ¹›ıŸ\á­İ‰\æS¿Ü¤ÿ\0–•%’\íı\İ>\à¤Oõt³R «QµJ\ËM\Û÷*„GF\êwú\Íô›¨\ç^?\İ\ÚGş\ÛWMb¿»O÷¸ÿ\0I\æj‘üÿ\0wÿ\0f®\Ö\Ñ|¸Ò³\Ò-N•\ZÔ«T‰OE¦­H?Ø \Ò\ã¤Vò\éC~ò€ûô»|ºZ@¿÷\İ\09Vœ´Ó³\ç¡h\ËJŸ\ÇM§P\×ıbP´\ÚUıÜ”\0”Ö©\Z¡fÿ\0~€\Ôı´û\ÊR¿r€F\ß/ıúw—÷)v\Ğıeµ3-7m\0GŠv\Úu;oÜ UJM¿»z};\ä v¥\n´úıe\0JŸ\ê\é³59\Z›p\ÏB³QN¦ÿ\0¿Tût\ß1\é\ßò\Ó\ç£	@&ñ‹yš\âÀkĞ­ÿ\0w^u\â\İÿ\0ğ\ß5\èğ-f2Ê­=[şø¦/\î\êEª\ËUn[VGû,v^Wğy\Ìû¿ñÚ·T\æÒ\â\á\äûu\ìÇ±\å Dú·\ïw\Çe÷?u±Ÿ\ïµò\Ô{µ\ßú†lÿ\0¶”¶úC\Ûù¿\é×²oşû—ı¥ùiŸğ<ŸV\Ôÿ\0\ï\èÿ\0\âh,še\×|¸¾\ËıŸ\æ\ìù÷\ïÛ»øvÿ\0³O´]ZI\Îû\Ïö7ÿ\0\èUKˆ\í\ãûv¡²\Ùş·\ï|Û·7÷ªİ”–ò&Ë»¹>}û¾_û\çm\ßş?\àş\Ìÿ\0È•4¿\Ú\ßg}Ÿ\Ùşnÿ\0öö\í\Ûş\ï÷ªá¤’D“ûKSÿ\0¿¿ıO.Š’[¤n½ûû÷ù¿7\İÛ·\î\ĞX‘.µ\æ&ÿ\0\ìÿ\0+øşş\ê.ZóÉ“OÙ¹¶oWû¿\íSAH\äI?´µûÿ\0÷¿ñ\Ú}ÆŠ—<Ÿn\Ô#ù\Ùö$ûW\æÿ\0\Ùh ™Sò\åı\å¦ÿ\0—g\ß\Ûş\Ö\êŠ$\×|Ï}?şı=:-!#·x\î÷cÿ\0Ÿó\ßUxr$}÷z‡şŸüv€\'ºMZI?s=¤iòıõ,\ßw\æ¨\"ƒZ9w\İ\Ùoù|¯\Ü•¿‹w÷ªk\Ï7|—{û’\íşµ>µ\ŞX<Ë¹<ÿ\0¿¾r\Íı\ï½Ac\Û]ù?Ó¬¿\ïÁÿ\0\âªi\à\Ô\äò¼›\è£ù>}ğn\Ü\Û~÷Ş¨\Û\ÂöR}ù/ğ%\ê{\ÊòDó¼\İ\é·\îN\ë÷~\ëPm-µ8\ä:ú)cl\Ùİ­ü-÷¾jjY\ê\ŞZoÕ¢ÿ\0\ì\ßı•:\ß\Ã\Ú}¾ın\Çİ¿|\ï\ß\ï»H¾\Ó$Ù¾9‹ş[\Éü_+=®§%¼[5$\ßüo\ä›\ï\ï—øi--5\îIµo=?¹\ä\êIô\r>Kx xDƒ\î|\Çü·\ãIƒ§\Çp“¤o\æ¦íŸ½\âû\ß\ÅŞ€\"k;\Ø÷ÿ\0\Ä\Ùÿ\0\ï\Ğİ·uH¶\×?gò\ßR}û÷ù\ŞPû¿İ¨\ÂúOüú?ıı\âÿ\0½V@\Ó#·û\nZ\'\Ù÷\ïÙ¸ÿ\0À¿Š€\"K¯3şC²ÿ\0\É\å%$¶“Ipÿ\0ñ=—\ï\ïò~O—ıš±‡´˜\äM–)½zuù[şúÿ\0f£ŸÃšMÄ;\Ø\Å\æ»\ïw\çø¿\àT\Z\\o¿~¥-ÿ\0ûøùïš¾ÿ\0»ªºv•e¥\ïû¤Poş\åZj\0X\Û÷¾‰ÿ\0\ÖSSıe>\çıcĞˆ+µ0¯™OjLU\0\Æ_2%)Ô¿\'ûò/ªG\âÿ\0€×£À\É\å¦\Êó\Ï\'—¬E\"qô*ô;õiYŒ´¿ø\å=iˆ\ßr¤_\âª\Ûiÿ\0\ê\é•%€ZrR-*şò‚¢\Óö\Ò-9hô\n6Ò­\n\Ô\0\Ãş\İ5–\Ë÷)­@	ºó\Òmÿ\0¾)\Í@\Ü\ê\ë‘X$öÿ\0d)n?\Õ\ÛU•v\î\İş\×Ş¥ÿ\0„–\ÖH\åò#¸‘ü¦–/\İ¯Uùw\'÷ªi\ì\æ“\\´»ÿ\0–Io,Nÿ\0\í;G·ÿ\0A¬\İ+F\Ô\ã’\Ê¨\"++Y\í\Òo7sJÍµW\å\Ûòü«º‚\ËV!I4;}I\ìnş}¿\"Eı\åİ¹~o¹ş\Õ:÷PšI4K»+¿ôK«…‰\Ó\ÊeWFeo\ï/İ¬¹4=N\ãC\Ó š\Â\ÊG²ù%¶y\Î\ÙWf\Õm\Û{7Í¶´ltk\ØôÀşTr\éò\Äòü\ß\'È¬¿+À¨U/¡“P{\ßö„‰n>\ïğ³m_›şU|M«¾‡£½\ÚF›÷\ìMÿ\0uY¿‰ª\â-\ïöƒ\ïò¾\Åöu\Ùÿ\0=|\İ\ß7ümQñnŸ6¡¡\ÜGe³\Íûû6ÿ\0­Ú­ò~=½ö\Ğm\ç‹&\Âñ_<	öÛ¯‘>dù~fuÿ\0goøWNw¿}q)\à\'“\ÃqFó\Ë ˆ\ÛÙ¼¸—{+2ã¿uOö\ØõKHw\Éiöy|ÙŸ\æ}ËµS\æ÷ù¨ğ÷‰\ŞòG\êú)\"û;\\:y/#k\Zõ%†³¨j\Z_ÛO°D—Sı¡ü \ÍK÷Wgñ52\Û\Â÷·\Û\Øj\ßgû%­¬¶©öiN\éw2ü\Íıß¹Ú§Ó¼?©\èñ\ËıŸv›ş\Û-\ÆË™^E–&]ª­ı\Öş*\0\Ôğı\ã\ê\Z=¥\Ü\Ò$\é÷\Óø¾fUm¿ÃŸ½Y7Zõíœ©<öO´Ooöm£\åTVÃ«}\îYşú«úV›¨iz^Ÿh“\ÚoIw\Ü|¿.\ÖffTÿ\0¾—No]\\I\äOwö\Ú%¸û§\Í\Ü\êË·w\İ\Ú7\Ğš=\å\ï\Û>\Éu?“Ú­\ê>Ğ¾V\æ\Ú\Éòı\åû¾õ´[\îVN¤^\Û\Ş$÷·qH\éj¶±y*Uv«n\ÜÛ¿ˆÖ«/÷\è\0O\İ\ÉROş±\êo¹R\Ïş²„ASi\å~\å5ª€aÿ\0WL©)v½<³\Ç\Ñyz…¤Ÿ\ç\ïWwf\Şdi\\g\Äÿ\0\ã\ÒO÷«°°ÿ\0uÿ\0uk3Búµ=\ÖSµD§«S( «$”©H«JŸ\ìP\ãı_û\ê)ô85*\Ó÷”\åo¹A\í¦Ó¿\å6€•W\ï\Ò\íı\İ*¯ß n\æòö?<nx?{\Ù\í ±O*³üû~gûÿ\0(o\á­;-i/#\Ó6Fÿ\0\é±K*³·k\ì\ÕVóE\Ô//ı./\ì÷¸Š\ãc©óWf\İÊ÷UN\Õÿ\0¾š¡\Ñü=©\é÷\ZÚ®\â’+(¥‰\"*\Û_\î³7ñ5šz¦§6Ÿqiw-Ó²\"nòş\ê\îm\ÍY—>8†\Î=2G6]|ûôóbmû6ªø½*î·§\Ş\Şj\ZT–Ry²»Í´IµY6ı\ß\â\Íb]x÷\ì\ï•õ¾÷Š$—\í0|\Ì\É+K¹[øsº€5&ñc\Û\Èò=‹ÿ\0gı¢[´¤£\ïD¬\Íòmÿ\0a”»W4\İjk6;«\"T·[¤D—\Ìóboø\Ê\ß\Ã\\\åÇ†µmCX{I£¸LûT÷\ÒXü­®Œ¿*ı\í\ß?~+ Ó´mB\ŞIgº¾ŠI~Ê¶±y0y{v\îm\Íó|\Ô©gr÷\Z}¼\ïÁç¢¿’ÿ\0yw/\İj\ä/<C©\Ûøò\Ş\Ç\ÏH->TH]~YÕ¶ü\Ûÿ\0¿ó6?\İ\ÇñW[f“Ggo\Ôş|©¤³y¿‰«›¿ğƒ\Üx¢-I.\åû\'ü|Jœ­Vj¯ñm;?\î\ĞU¹5¾Ÿw\çI©ıµüÔ‰\î[÷_\ë¶3\"ÿ\0—­\\\İ4z\Ãøm\ï®ş\Ïö…ı÷›ûı¾S;.ÿ\0÷—úU\Öğ­\Õ\Æøõ\rI\çDŠX­ÿ\0uµ¢\Ş\ÊÛ™¿‰†Õ©\Ã3Ip÷Ï©?ö¯š²¥Ï•ò®\ÔÙ·g÷JµP<;s5\æŸ\'™*<±;ÿ\0{c²\îÿ\0{\å®~\ïPº³ß¬¥Ü»ş\Û=¯’\íû¯)U¶ü¿\ŞÜ›·½[\Ö\Ú;\éövV\ßK\ZA/›+\í¿İ¹[û¹fªo\áŸ2\á\ä{·’\Ë\í\İ}›h\Ù\æº\ío›ø—\æo–¤‚\ßPŠY\Ò\êÁnŸ\Îmß½İµ™»\ßtq]\ÖN‹¡¾Ÿq\ç\Í}-û¥º\ÛÅ½BùQ+n\Ûòı\æÿ\0jµ^‚\È:µ\'úÆª²¯\Ü\ÙS¾ú?yM§Sj€?\å7/KQ\î G\ã\Èÿ\0\â_ŸÜ–ºm7ş=\âÿ\0uk\Æ\Ë\æhoşÃ­li[ş\Çû‹ÿ\0 \Öc5Qi\ê\ÕT›ª€v\êv\êb·Ü§\ĞÒ…¦¥;u\0H”ú•w\ĞÙ©\ËLÿ\0–•*ÿ\0« \Ó^…j?ô:\0ÿ\0~¹ÿ\0\ê³iöq}•ü¹^_7ş\ÙD»\İ»\àU\Ğö\ë+Vğå®±q—¿¼Š¥‹\É»¹öüß†\Ú\0£/‰î£¸¸‘-û>ˆ\"y·Ì²ª\íe_mü\æ¦\Ğ\îõ;CXo³ùP\\2\'\Ìû·l]¿ğëº–\ÓÂ¿g\Ò\å±{·“\Ïx~ß›÷K\Zş»*í‹\Ûêš„\é}\æEtşoÙ¶§\î›j«6\ï½ü4f\Ùk¿\ÙöB–ÿ\0k\ê{Y\Z%Ù¹™¿½ü<U«CPòôy.­\"ƒ}\Ò\Åq\ãò³nUdeş\â\çû\ËJ\Şx\ã‹È¾–	aº–\é&E\r÷Ù·.\Öşš¥M>\Ïiiö¹d{[…º\Şÿ\033nfù¾»š€)¿‰\ïc‘\î\Ş\Ò\ß\ì_h\×\ï7r+|\ß\İ\çc\ãµgM\Õõ	.\Òö5\íVö/\';v³m\ØÛ¿\à?5U´ğªIq/\Ûo¥‘\â{„¶óuµ÷.\í¿\Ş\Ú\ßJ»¦øy4ùG¿»»³­ª<\Øı\ÔKü+µhı”³\\iñIu\Ù.%yaİ»\Ên\ë»ø«‹Öµ\rB\ß\â–û\éc´û‰±wE´\íÜ¯ı\Ö\İ\ë\Ó\ä®\Ò\Â\Û\ìvqZy?‹÷û\íş\ÓV¡\á[\ÏE©<\ïıùa\İşµ“nß—û¿*\çşA5\ä_\Øò]ıŠ\î\âK¸-\åû}\Î\ãóJìªŸ/\İV™¸\éS<	o®\'‡\Óöc\ÜDşK±oùe#2\îû\ÛK\"µh\Ùøz\Ê8õÔ¥»ûV\ç¸MÉ»smù¿»ŒĞš™%»İ¾­q$©*¿Û¼ô\İ\"\íû\ßuq¹”­—ü9+É£¦ùG‚Ym\Ñ\ß\ïmGe]\ß\ím®ròGIu”‘şÖš¤¶»ÿ\0\é–İ»6ÿ\0wø¾¼\×C‹eŸi3\Ë\åAp·\é.æ•·3|\ÍüY\İP>‡¦j|ó¿šòı·\ìxÛ»n\İû( ¡á˜’\ÎóOòwÿ\0¦\é¿h¸ÿ\0¦²\î_¿\Úù\Ûÿ\0®›mc\èšF™§\ÈòY]¼\ï}Ÿ\çŸ\Ìòv\í‹ı\Ú\Ù_õtÔ¯QJ\Õ+Ğ‹Mfû”»¿wş\Å75D\r\ÛLù?Û§\ËJg—@¯Š\Ì\Ğ\îÿ\0Ü«úC¡\Ûÿ\0\×%ÿ\0\Ğj¦º¾fwÿ\0\\š¬\è\å\éö›?\ç\İô\Z–ªT•\Óÿ\0\Õıú\0zÒ¯ñSY©wP\×şOZŠœ­@­Hµ\ê~\ï\Ş=Z”5@R-+}Ï¹I·÷”\İÔ¾g—\ïû”\0´µR\ÓS²\Ô7ıŠ\î)ö}ÿ\0%ƒ\ßUj€şZ%r\ZdVZ~o®¼‰\ß\Ù\çy]\æŸsn\Ü\ËüXùkªi\Ò;„\äO7fôO\âÚ¿\ÅX\é\á-28\åË—\Êt–/%\ç,ª®Û›bÿ\0\rœõ×Šµ\Û}>]ÿ\0¸»‚\á‘ü\è“sEä´«¹W\å_Ãµ>\ÛPÕ®.5ä¾Š9ml·¾\È\ÙöK2¯ûªvöşõj\Í\á=&\Ş\Ş\âI¤—c¶÷w\ÛoÉ³ø¿\Ùj™ü¤\É#\ïûGñ#§UYYÙ™YW\ï}\ê 5ó\Û\ë‰wû©\"}6[PÜ»6¶\İÿ\0y”\î¤\Ñu\rBKˆ º»Iş\Õaö\ÕıĞ\ÊmÊ»~_¼¿7~kQôød¼Šwû\éEş\Î\ÖÛ»\åÿ\0€\Õ=?\Ãúf‡#\Ïÿ\0õ^W\ï¥2m‹\ïm]\ßujH.\éms&Ÿoö\ß+\í{\Íò~\î\ï\â\Ûş\Íp~)Ÿ\ìş8´»y\åØ›\ÔËµ o•¶\íş%ş#\ë»ıš\ì´ùô\Í?G‹\ìS\ÅıŸ_#\îÜ»Wıª¡\á½3X\Ö-5—D’X?ò\'÷7}>ğ s^³‡K\Ù>Ÿ\å}{[½—pÿ\0­V]Ï½¿‰~ö*õ\ÌP\Ûø¢+#²[g{m£\Êİ²m¿/ü>»k_ûEPù\ãO´O¯\ä»]¯ò»*}\Õûİ¿½P>Ÿ\á\Ë=>\î<¤–$¸ùe_¹ó}\í\Ãw ²ß†\åHôù`MT³\Å÷UUşU¬k\ÕHä½¾MŸmƒZTI¿‰Wj®\ßûåš¶lmt›\Í\Ş;(\ÒK$uxºı\åmÛ¿½»w­Cw…ı¹\åiü®½~öß—ıŞ™\æ‚ÿ\0\r*[É¡l\ÙÚ´\Ù^_új\Ê\ÊÛ›şºº¶¬-t(õˆô\Ï+\íò\×\ïı\İ\ß6\İ\ß.\İ\İqŞ¶¨\0f©x•úÊšOõ‰B­H\Ô55›÷uBª=\ß\îS¿\Õ\ÇK—ÿ\0bœæ¦©ıŸw¿şy7şƒI ÿ\0\È>\×üò_ı¨¯™gq¿şy7şƒM\Ñ\Ì\Ó\í?\ë’ÿ\0\è5,³bœ¿\ë)‹LšT?2i4 ‚Uo¿RnOøVT“~\Éz}ÿ\0öi\ŞzG\"A\æ&÷\Ü\èŸ\î\Ğªô\å¦f´%=wù•\ê•h,“ûô\åj‹u?rPA&\ï¹Uu6ÿ\0‰}\ß\ßÿ\0yô«¿wL’$’7ş\ã£#ÿ\0º\ß-\0p6·Ú…½¿™\äEip–¶–\è–\ÍóK¬»_s|ª\ß\Ã\ÏCWşÙ­I\ZZ?\Û~K‰~O¶D·[UU—sı\Ö\Æ\æÿ\0\Çk¦“C\Ó.-ü‰­Dò–\ßş\Ù/Ì«ÿ\0\Ô-\á]Kt´}6/)Ÿ¼\ßy¨.\İ?´5\Í\í/®\äG°i\Öùj\ÛZ5û¾ı\×Ö²/5­O\Ì\Ô\'†\îXâºµ¹{ß‰YWr®ß“ø«²}O“\ì›\í\"\ßkÿ\0ÿ\0/ú¯÷jğÎ“ö‰gû¿šû·>ß½¿\ï~t\ÏkqM¾±c6¥w\"Aµ\Â;Ëµ·32¿Ì¿\Ãò\î\ÛÚŸ\âyoc¸û%•Ü» °ób·y_6ö]\î\Í÷ñ·\î\×Qq§\Ú\Üy¾t	\"N‹¿\í*ı\Õÿ\0Çª	ü=¤\Ü[\Å\Ö6’Eú¤x¿\Õ»@2¼š…¦û\äù\í\ã\Ù1û\Öù~uÿ\0d\ì\Õ‹•$ğ\İß\"FŸ/\Ïü?y~òÿ\0wÖµ>\Í\Ú\ï\ËO5•ø•[\ï/ş;R\Í\\Fñ\Í\ZHò:P”[\Ë4–\ÒI|§•¾\Õ5Ÿ\ï\å‹wü\æÛŸøz•}š^™\çI\ä=\ÔQ$Hÿ\0+3lû¿û5Oi¥Yiz:i°ÆŸgÙ³ı\í\ß{w÷©&\Ó\á’KO\îZ¾ø“ø>\î\Õü¨‘†úö?\\O5\ï›öy\â·M£kD›Yvü\İÛ¯û\ËMğ¼©o&¡ı§\Äı¶÷ùguÿ\0{\å]\İ=¶\×lğC$‰;Æjn\Øÿ\0\ï}\ê‰\ìmdó|\è\"“\Ï\Û\æ\ï]Û¶ı\İ\ßJ\0\Æğ\Í\â[\é÷²^\İ\Åò_Ês÷b•™—k/÷~öß­S\Õ%H\ä\Ö#y\Íû}³¢nù›ı^İ«ÿ\0}WHš}¬vşBZE\åo\ß\ä\íù~ö\ï»O–\Ö\ÖK„\àŠIS\î>Ñ¹\àT\É\è0\É\'‡\ãI\"ó`Š\å%Dû\Ëşòÿ\0\Í]k5F¶°\Ç#ÎE¯÷\ßh\Ü\ß\ï7ñSÚ€²I*ÜŸÁ²ª\Çş²­Iş­*bL´\Öo\Ş·J\Ô\ÖıÜ• …¤ó\ßı¿û\æšÿ\0\íÑ¶˜\Ë\æY\Ëÿ\0\\›ÿ\0A¦h_ò´ÿ\0®Kÿ\0 \Ó\ïı\ã\îªoı«h\rÿ\0»Oú\äµ m¯\ï+Åªòx~\î4ûû?ñ\å­Uı\İVÔš³¿\Ûvy_.ÿ\0û\ê€9mò÷P¼{G[I~\Ğ\ßhò~ó4Q*ÿ\0\ß?\ÅWlc›P\Ö4{·»¸\Şö²¾\Äo—\åe_»\ïŞ¯¾‘¤jK¾4‘şÑ½şb¿3/û?\ìÕ££\éòIiû„ÿ\0Eÿ\0öh#LÔ¯n<HŸ¼—\ìSùû<\éC}Ï—\å]¿-t:k?—.û´»ÿ\0Hoûeş\ÇûÂ©Ã i–÷}\r¢Gqó|üÿ\0Ş«\Öv\Ğ\Ù\ïòcû\îÒ¿û\Ç\ïP¥o\ŞT\Õ\Zÿ\0¬¨\à»K\É.#Mû\à)ÿ\0\ŞÚ­ÿ\0³P¥¡Zš\Z“ry\ìP´ª\Ô\Äd¡_÷ŸÁ@,\'û• oû\â¢\İ÷öS™¨\íM2}Ê‰§O1>\æÿ\0÷©¾zy˜›ÿ\0\Şù¨_ùiş\Å9Z«}ª-\äób}ÿ\0˜\èT5\Ü1Æ’y\é±ÿ\0\Úğ\Z\0¶­ıú<Ê«ç§˜‘ù‰¿û›¾j­¨jğ\Ù\Ù\Ë?\É\'‘µ‡ñ2¯ş\Í@İ’˜[÷•CS\Ô~Ç§\Ë}ivn}‰(ù•~öÖ©b¼†H\â“\ÌHüô]›\Û\ïn]\ßğ*\0²\ÒQº ó\Ó\Ìòü\Äó¹»\æÿ\0¾i\Î\É>Í”5&\ÚÏ¶\Õ\ín$¸\ÌM–®©¿p\ÚÛ—w\ËW|\Äş\r›(Å¼Ê³Æ£7ö\ÃØ¼	\Z}Ÿ\ÍI·}\ï›k|¿\ÃW(X›÷•u—÷iT#Zº?Õ¥LK!f¦S›ı[\Óp•¡Q»şš%6›ûº\0À\Ô\Û\Ë\Ò\îÿ\0\ë“\è5„\ßñ/´ÿ\0®Kÿ\0 Ñ¯7—£\İ\Éÿ\0Lš“FTO´ûÿ\0\ê—ÿ\0A©e›¬+\Ühwqÿ\0oş„µ¨´É£Kˆü·6ñ4rºd\ïqy-¥\ìy²ù¿7—ó\"*¯\Íÿ\0WK¡\Î÷\Z]¤÷\Ş%¤{)#}ö‘lw\Şû\Ô}ï»º®\"¤q¦Ï¸”\É=\Ì\Öz|W\É<¿hŸ\í)÷‹|«\æ2ü¾\ÛjI®R9%‚\Êú/+d\ë§;Y›v\ågş\×I\äC\å¤~Z|›¾O\î\î¨\'‹L\Óôùd{H¼¯¾\é´|\ßüU\0I\á«Ä¸\Ò\ÒDş	e‹\ïy‹ò·ğ·ñ/¥f\\¢G&§}óù©©@‘|\ßww–§ó\İ\Ímùğ\Ù\Û\Å\äÆ›7ª\'“\âş/÷j\Î\Ø|Çü­ÿ\0gş\Í@2j¼½\Öt\íV²\Êğ\Â\Çt[eUù÷\Öj¿¨\\\éñ\Ézdöÿ\0dûRù¾vZ\×\î6\Õùï¯®\Ú\ê¶Z\Ç÷#·\Ş\è\Û:|\ß\Ä\ÕV\ÇS†\ãO{»¨\â´D•¢tv•¶ı\ê\0\âo\ÖhöoŸ\Ì³\ìGM\ê»\Òv\îÿ\0u\ïšÖ·¼I$O¿ı¦÷ÿ\0>\Ïõ¾F\ÏımuOwe\Íó\Û\ìŸ\î|\Ã\æÿ\0wû\Õ0TO’¨oÁ÷P\ÉpC\åNÿ\0eWy¡S|­µVUşÿ\0ÿ\0e[\Z\êù’i‘ÿ\0Şª?û»\Z˜šÕ”š‡‘–ò~é¥•Ñ‡Ëµ¾\ë\ßUgûV\Ê;t\î\íü­ÿ\0wË¹j@\äõ‹o/X–\Ñ$Š\Ùl–¿\è\ÆVû\ßòÉ¿‡\æ\ëUo.R\ŞMB4ıä®—{÷Àc\Ú\Íó?\İuôÿ\0€\×m&«e‰\İ\Û\ï}»pù·}İ´M%­Å»\ï\Ù$S\î‹ı\ï\ï-\0rO$hI\á´xô\Ïôo´o€ª·\Şù¶ÿ\0>Ju…œ7š…\ŞøKO³\Üı~]¾oËµ‡ø±]z¾Ÿ%»\Ï\rô^Tw\ìo»V!¹†\â4D\ãJ\0\âl\î^\Ş\â\Ë\Ï\ß$¯öGû4\Ğ7s\"«<Rÿ\0\n\ê{«U	/O¶\ï“\çÕ\â\Ù ò\Ö&Y•¶ÿ\0µü_1®\ÛUÖ¡\Ó\ãO\ŞE%\Æø¶C»\æù\ßn\å«Q^Y\\\\<	<RJŸ}7\r\ËAf$o\r\Ä~ ’\Ëş=>Î»>R«»\Êm\ÛWşù¬½rGı×:@¥Å³}™—\æş%O\î·\İÿ\0\Çk¯\Ôgš\ßO¸\ÒIQö;|­¶¢M^\×\ìö’M\"@÷H®ˆ\íı\åVÚ´svò¥¾¹ü·—\íK½&€ù«½>gY¹ş\Éÿ\0jº«ÿ\0cË³~\Ï\İ;\ì]\ß.õ\İòÿ\0»º¯m¬7\Ä0\ê\Z†¡\ZIoö{_\ã\İó7÷›ı\Ú\0\ç.\çI5‰g²ıÅ§Ú—\çû‘w4;~\çüº¿~\ïC´ûû66\Íñym·smù†¦‹U\Ó\ä·{´»‹g\Üß»ø¿»U\Ä6Q\Ş<o<Q\Å\å,±M\æÿ\0­\ÜÌ¿û-\0>}ÿ\0ğ’ZH›ö}Š_ı\rjúşò«h\Úı³\ì|_hÙ»\Éş-µeV€\'Š®§ü{\ÕH[ûõeışS \ã¦3yt\æjMÕ \rjMC7\Üşı.\ê\0\ãüU/—\áû¿÷*m\'şAöÿ\0õ\Éô\Z\Ïñ´^‡/ü¯\é_»\Ó\íÿ\0\ë’ÿ\0\è5 j«Vn¸\Éšo“Ë‹\íy¿\İÛ»\æ\İWÖå¤‘üû6P!4°\ÉÆ“\Ûı‘%—\ÊKœù[UWî·³n®¯L—\í\Z}¤Ÿ<hñ/\ßo›\îÿ\0y6ñşûÊ?\Û\Å2\ÇP†\â\Ş\ÒG‘#y\Ñ]w\ÍóP5¦]yš\ãÈ’E½\â¹\ßlŠU—k|»\Ûø¿¥^}>\×şÿ\01\àMÿ\0eWg›\æ_›ut^\\?<\Z¿K$¶¶ñ¾ù\"?\åZ\0\ÍÔ–4{y,£‹\Êóbò¶/ûk÷»P—²P\Ô#¹‚Y.ş\Û•±N\í»Wn\Öş6kT\êVQÆ’=\Ü[>WO˜l©~\İkö„\ç‹\Íş\æïš€0ô=A$“D´O7Í‚\Ş_7\å*ª\ÛUv\Ò+%½½¤—Q¿\Ùÿ\0´®|\ß\İûÛ¶³/ñV\Òj0\É%\Æù\"\Ùkµüß»»û\ßİ¦É¬\Ã\Äğ\ÏGû[Swğ\îo\á Vg±ù\ï¿<Rı­¼Õ|\ÖØŸ\ŞFÛ·ğj\í^K7şÿ\0•ÿ\0mûµ\êv¶şW<Q»§\ÉóSN«eö#\íqy¿2lÿ\0i~ò\Ğs²\â\Î\Óì¶—¼J—¸?\Ã,{—oñÖ­$Ÿkó5?øø—e\Ï\Ø\Çñ\"«~\ëû§o\Ş\Û\Õkv\Ã\Ä6W–÷¼‰\Z@\íóó·j¶\İÕ¡iy\äo$2$‰@]³]\\[\Ş\Ú&šşlö¶‰ò.\ß+û»¿»ı\ê~¥ö\Ù,\â\ÓR\Æ\ï\íh¹ù\İ~_™fÛµ½÷WUu©iú|\çH‘\Êÿ\0;ü»¾Uû»©\íykå¤blwh¿\àK»wş‚\Ô\ÉXY½\Är\Ï4š®ôŠŸ\È²²³6\Ô]¿6+\ÃM4š{ùÑ¿ü|K³|^[J»¾Weş5wO\Ô,µ\ròZ\É\æ\'û¥ô*[ıB\×O>\Õ#Æş\Éoû\ëo\İZ\0çµ¤x\ä\Ô#{K‰\ê\â	m\İ\"Ü«·\Ë_½ü8\Ú\Ô\Ï\Ç4š…¾ÿ\0¶\ï\îw£À%\Ü\Í÷_oÍš\è_W²\ã\Èyÿ\0{òÿ\0	\Ûó}\İ\Í÷W4G«\ÙIq-¢Oû\Ô\İòm+÷~V\Ûı\ìw\Å\0Mv¾e\Çıroı¸\ís\í_\Øv–ß•ı–»<˜·~õvü­ı\Ú\ê^\Ó\ä·ytØ›~\â–û\İ6ÿ\0{ğ¤}L8§{¿‘÷lùOğõÜ¿Ãz\0¹e¼_\î¯şƒ\\ı\åœ\Òi~ \ß{\Ü3§\Ëşµv\Æ\ß/÷¿Šµ¿¶l¾Ğy\é½ö\ìş\ï\Íó/\Í÷y«r?—\Èÿ\0q(–†9£¼K\ï#P/´/ß€+mò™~\âún¢“\í³ÿ\0f¼ö¤Işó\Éÿ\0|±û\ÛkJ\ï\Äp\ÇöI!“÷Oqöytw/\ÈÌ¿/\Şş\íLu\Í>;x§yÿ\0u?ñ\í?\Ãò¶\ï\î\ãŞ‚\Îj(\ï¶4ù&ƒPŞ—\î–/+oıõº»e¬ù¯¼½b+1??\İ?\Ãı\Öûµ Ÿ» ‚\ÌMSÿ\0\Ë7ÿ\0~ ¿¹S#~\í\è,…ÿ\0wLÿ\0Y÷ş\å+ÿ\0·Q³U8\Éû\Ê]\Ô\Ï\ïÓ¶\Ğ#„ñ§ü€\ßıõ­]+şAöÿ\0õ\Éô\Z\ÎñjyšµJo3O·\Ùÿ\0<—ÿ\0A©¤»\ê?ùgM’\å-\ãy\ætøŞ‚\Ì\Ía\áP‹\í±ù‘}]Ÿº\İó|µb³I\åo‘\ãwû6\ÄKo1¶ª¯\İo\áZì­®`¼Ï†D‘?¿S%	y\Ú-\åƒ\çùÑ“şú®RO¶\ÜY\Å}7›ıª$\İy›U—v\Ï\â\Ë|\Õ\Ø+}úhÿ\0b€9m6\Íÿ\0{¾9dO±N\é\çE·\ï;}\Õş\Z›G‘#Èš\ÆY%{ˆ?t~\î\Å\ÚÛ½¶\×Q»÷t\Õş\r”ƒª\Û<’k Ÿ\ì\ß\Ã÷¾o›ıê­¢\Ù\ÍıŸ¬Fğ>÷·Ø‰·ø¶\É÷k©\İûÊ‘?¨KW³ºûB\ÈB4ŸMŠ-–\Ğ	72\îÜ­»\îı\êµ\rŒ\ÑÛ§\îöº\Ë÷~mª\Ûw\ß5\Ó/ğPµ s“e\ê%¤»\ÓRûG\İÿ\0Z¾j·\Ëı\ê\Ø\Ñdy.59\Ş	`I\îgœ»[\åEVmµu©ÿ\0÷\İ\0szüWQÿ\0jÈ–dWVñ|û¾\î\ÍÛ·z©\Í§qoŠX\Ëÿ\0³\Ëö\Ãn\×Y6²ÿ\0\ßuÖº¤‘¾ÿ\0¹NUz\0\ç¼-i4w<\Ñ\ê¿\Ù\â‹ı.P\ß\í6\Õ_º´ÿ\0\Ù\Ş\ŞGpÁw=»\Å*<6\Òùm\æ\íù¿¼¿z·\ÂÔŸ\ë(Š¶‹P¸·»±KO¿öO7{mò¶¤{¾_\âû´\í:\ÏSş\ÜIæ´»ß²\æ)fv­¹¾M«ü5Ø²$r¢e b}>\ëş½4\ãÍµx\Ò\Û/µ¶·µ%œº}\Â]½…\Ü\èñJ›Ti~gV]\Í\ï]+\Ñ@§öEìšƒï´½\'–	Q!œ,µW\ï/¶\Ú\éµUº“K»ûÿ\0´<M\å\ÍS­9›ûô\ÆZ\Ø\Ş\Ù\ê<:m\îÄ½Š](f\Û\å2·Í»µE¨\é\Z…Å\Äocw$Sı¦T…%\Û\åJÏ¹w/ñ|µ\Ûÿ\0¿E\0a´\é&±¥Aå¿›R¼©·\î\îEş/÷«yiŸú<¬ 1²T°ÿ\0«z‚*š/õoR·\ï)‡ıŠsÿ\0¬¨\ÛıbUˆZ_-é´¾oı4Jc9G\æh÷\îT\Ú$Ÿñ/´ÿ\0®Kÿ\0 \Ó\ï\Ó\Ì\Ó\î?¹\å7şƒU¼:ÿ\0ñ\'´ÿ\0®KR\Äm/úº¡®\ïû<R$o\"$±J\èŸ\İV«Š\Ôı\ÉA¡£oÿ\0K‘\àx\Ò{†tGù~]«ü5¨µ·÷\é\ß\îUJ¿ø\å*\Ö^¥w{üz\Ç\Ügy¦û«·øi\êóIq¦F\'\Ù\ïQŸ~\ï™[n\í»h°»\é\ËPM:[\Æó¼Z\'\Ïÿ\0¬¸<G—\ë|¤H¶~\è\î\Üû¿‡ş@\ÍÄ§­U¶ºK\Ët¹Y\ß\Úw²j\ZGo\å$«§üµù—\ïÿ\0»ÿ\0\ÄÔ–n«Rî¬ŸøHtÿ\0.Y<\ÇØŸ6ÿ\0(ü\ß6\İ\Ëı\ï›Ò™\â\Í\ç†9dt•btòË¹—\ï/û½( \ØZ\Z³®µ4\ì[7Æ—Rª|ñûå¿º\ßZtº\å”w\ïŞŸ&ı¿.\å]\Ûwz€4S‡úº\æ\çñ•Å¿™e$»÷\Åÿ\0,\İgUm¿\Şû\ßøõhYGnò?›úB\Û\ì\Ûó+7Ì¿/½\0j+TX\Óx†\Ö\ßfø\î6yK+ü¿\ê›o\Ïı\ÚÕšT7‘ş\â|ÿ\0÷\Í\0+7\î\éŒ\ß\îW=ÿ\0	/—xò<q\Åöu\Ù¨\İ+3\í]´jš«\Ühwv²Ki-«ü\É\Æ\í\Ê\ßu¿\Ùù¨u›÷”+\ß\Öo\îW;y«\ê\Z]Ç™3¤ˆ\é;ı›oú¥EÜ­»ø¿û*\0\éÖŠÃ¶¼\Ôÿ\0{hòE%\Ç\Ù\Ö\â\'Ûµ~fe\Ú\Ë\íT¥\×&û>™\ç_%‡Ú­\Ú\á\æu4«·\åù¨ªZEªºm\Ó\Şiöó¿\î\İ\âWÿ\0¾ª\Å\0;ıe\ÍÔ«Ae˜ª\Â~\î7ª\Ñ5Y\İû¿ö\è ®\íL¥j‰›÷•@*Ó°ô\Åj<\Ï÷\è_\Ş[¿û••\á\Æÿ\0‰\\U©/ú·¬\r·üK\âÿ\0\èU,\r\äo÷)\ê\Õ7\î\éû¨\ß\ë(\İıúg\É\æR¯ú\Ê\0\Ì×–ö\ãÊ‚\Ö\İ\ßı#cfUû«ó	\ïV&\ä¸\Ó$Hü´\Ûû?&Ú»O\İ@j\Ö\Ïy¥\İÀŸ}\âjç¯´\İOP¼{·±x\Ó÷¹Iö³mVVù—\îı\ê\ê\Õ\ÉN\İûÄªƒlöúz@ñ¼½Ÿf\ï3\ï7ñ7ñUû;ÛA6XÅ³\í\n\éy¸nX¿‰vÿ\0÷kii\å¿wR&‘u§\éo¾\Æ_6–/9\ç2+~õv\í_\á­\Û\Í>ö\â\ßS‘\àO6w\Ñ¿¹·ø«k\äù)¹AeB)µ{O\İùoöˆ¥t\İ÷U[\æù«.Mi5‰>\Èò#\Ü5\ÂM\çü¿2ÿ\0rºJpj\0\ã\ÃÚœq¦\Èzmù7w\Éoı‘«]4\ÛÛ‹‡»š\Şş+›·m‰n\í\ßŞ­\Ú]\ÔsšŞ5\æ©,\éiov“\ÅI\çJW\Êef\İò¯Ş®†\î7’\ÎXf÷‰“şú]µ&\êk5œ¥ö‡«j§§\î-\âDD”í•‘Õ¾o\îü«VcÑ¦ş\Ã\Ô-<‹x\é\ÛbC÷W\îÿ\0üºjš‚wß®y4­N\â\ãPûlq¥$¶\é7šw,_Âª¿Î··S·Pn—§\Ş\Çp÷w¾Vÿ\0³­ºy?\ìü\Ì\ÕIô[\Ø\íôÍ‘\ÚO-­¼¶\î“}ß›ø–·Ù¿\ß\ß@o\ï\Ğ:U³\éú}¥£\É\æ<*oÿ\0v­nò\äùé›¿wK@f¥_õ•\nµ?4f*ıêª·\ï*x[÷o@»}\Êe>Oã¦ªùuB\Z­NÚ”Ÿò\Í\é(‰#yq½cøm¿\â_ùş*Ò¼oôy~\æıÿ\0 \ÖO†ÿ\0\äi³û•,)\Õ7\î\é\Ê\Ô(gó)U˜´-\0H»\é\Ô\Åj~h\ß\ï\Ònş\å&k/\ÄwOg§¼‰#ÁûØ“z}ı¬\ß56)\ìÕƒ-\Û\Û\è\é<2\\Nj\ïwm­µ™Wûµj\çSš=C\ì0\Úy±ewİ·j³m f\çÿ\0€R\î®n\ÏÅ‰q\'–ö\Zlg\ßæ›j\î­#XMR9vlŞ³\ïnş\Ôª­O\Íbjúœ\ÖşlÀò?\Ù\ÚWı\îİ«÷V«Yø?\Ñ\à}Ÿò\Ê\'}\Ãv\æUş\â “44•¦«Çªjqù’\ìı\Óü\í»n\åm\Ûºµ5ıó\Û\Éo(’Kt\ì‰óm_•w7\Í@\Z\r%.\ï\Ş=s÷·/q™vñ\Ë¿j‰7y¶·û\Õb\âù\íõÄ÷ù_egû\ß\İe\İò\ĞY¬\í\å\ÇQ3W7Œ\ÒHÿ\0\ÔE#\ìWD†]ÍµWku¾j]KY½û;ù0$r\ÃuRü\ß\Şee\Ú\Şû¨ è™¨VòöV&¡\âT\Ó\äò\Ş8·¢,²ş÷k|\ß\Üşõlnş\å\0M\æQ¹\ê\ßÜ§ùŸ~fı\İ*7\ï*’•\Z’«~ò²y•o¿OF 	Ñª\Ô-û·ªIV\í¿€#\ß7™%,\Í÷\é‹T!Û©Ÿ=;u?jP3“Ö¥òô»½ÿ\0óÉª†ş$öŸ\îS|Y;Ç£\Ëş\ŞÔ¤ğ\çü\í?Ü©`n£SÙª\'\Ü\ÙN\Üô&ß§­D\Zš\0™\Z—w—Q«S\è»ª¶¡fú…ºGÿ\0Mbû\å·U\Éüt¡¨N\Õõ7?\Ùÿ\0\ÇYZ”[yz„·Àñ,[?\İfoıš§\İM g8şšHü·6ldû¿\ì2Ö®§\Ígqw%Ô‘I\çmû‹·\î®Ú¾­û\ÊUj\0\Î\ÕtÛ«‹‡\Öt\Şİ­\ßz\îù†ª\Ûxrk{„\Ù=¿•\æ\Å+ş\ë÷¼*«mo\áûµ¶ûôı\å\02gP»\äO\ßù_ø\î\ê‡T³š\âKy-gH\åµ}\é½w/Ì»[\å«;©CP?\ì×’\Î\Ş	®\ŞIR\án7ÿ\0{kn\ÛR\\iÿ\0h\Ô\"ş\âE,_÷\Ş\ßş&­f@\Ô>š8\Ò»M‰\å\"~\ëoÊ­ó{\î\Õ\ëı\"kµ\ì#y\åŠTù~\îÍµ«ş®˜\Ô‰}¡\Íqq\çùñox–)]\à\İ÷[\ï/÷~õnõtŠ\ÔõZ\0hu¢•h:z¥-¨\0Uşı<-F\Ôú\0*hv|õ\n7Ü©¡o¿@vJÿ\0‚–ZkUTj?\ïŠn\ï\İÑ¶s\ãkŸôx£şûÿ\0\è5¡\á\ïù\Ú¹\\ÿ\0Œ\åó/\"ıŠ\è´ù\Û\ìÿ\0KR\Äj®\ÊZhjUjH´ñüt\Ê}\0<7—²º˜­\åĞ¿Á÷\è\ãøi\È\Õ:nÿ\0~šÔ¿òÎ‘¨›¼ºP\ÕS·P›¨¦S÷y”\0»©\ëÿ\0Tj´»hÛ©\íH´Ÿú\0;ûôİ¿~\ÔÖ’€¶\Ô\Ø\Ù\ä\ç§P¿ß£4\ÍÔ´\0ı\Ş].\êu¨M\Ôôjˆ7ß¥\rÿ\0}\Ğ¥©mÿ\0ª£U˜[÷oıú\0Fşÿ\0Ü¨÷S\äjoş‡TJş\î—\çÿ\0f‘Ú™ûºy?‹›\Ì\ÕıÅ®¯Gÿ\0}¯\ßÿ\0Tµ\Èx©|½aÿ\0\ÜZ\ì4ù\Úÿ\0\×%ÿ\0\Ğj@\ÒF©wT+O\Û@\Ç\Ğ\ÌşgÉ²š@Y&ÿ\0i³ı\êz\Èÿ\0ó\Í?\ïª\Z¤ \î¿\çœ[?Ş¥\ßu\åÿ\0«‹şú©¹NZ\0j<\ß&ø\Ógû\Ô\Í×¾_ú¸·ÿ\0¼j\Ê51Ú€!\İ4ŸgıõH­7üóJ]\Ôõ Zoö*E\ß\æ«JŸº€\Í7\Éû´ÿ\0¾©<É¼\Ïõqlÿ\0zŸº•h3ö¯Ÿg•K\æMóÿ\0ª\ÙøÔ¥¿\ïŠ(%ó¿\Êıi<\Çÿ\0b?\Ô\0¿¿òÿ\0ƒ}\'\ï¾}şW\ëR¤v ™¦ş=›)­\ç\ÅR²¥1Š€šoŸg•³ñ§\Æ\Ó|ûöÀ)7R†z\0zµH\Z ]õ*/™@ı¿¹V-ÿ\0«§úº–\ãûõ 6Jc7÷(‘¼¿÷\éŸß«\æ¤\Ûş\å+5&\ï÷)\å~/ş&	ş\åuZ+Ä®\Óş¹-sş3‹ş=\äÿ\0y+sBoø“\Ú\×%©ª´\êbÒ³}\Ê\0{5ü”n ­*\Ó?\ÕÓ¶\Ğ”µj(X\Û÷L‘¼\É)´­û\Ê\0UZ]´´´\0¡¾\å:˜›)Z€ş²J)?¹²@/÷\é)?\Õ\ĞW˜ô\æı\å¿yB­\0*²P\ß\ÃKıúFş\Z\0FjM´¬¿»¦ªıú\0Jx_\îRRªùt\0«üU2-FJ­\æP”©¡ş:€3üÿ\0ß©m\Û\Ì\ß@\Õ7—Of¨^Oû\î¨\ß»ÿ\0Ü¨·~\í\é~z8ö8½ŸŠ¿\á\ïù\Ú\×*(¡¬?\ÕÓ¨¢€œ¿\ÅEr\ÓÖŠ(--P8‚ÿ\0«JH¿Š(2ÿ\0«¡¿‚Š(/úº(¢¨bÿ\0\Ë4§¥TˆAüt¯EcT´QG@ş:b\ÑE\05ÿ\0…ş*( ±\Ë@ş:( €?ÁO¢ŠMúºš.:QE\01¿¢oã¢Š\0\êlš( ÿ\Ù','image/jpeg',9,'2026-11-10 19:52:00');
/*!40000 ALTER TABLE `ingreso_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insumos`
--

DROP TABLE IF EXISTS `insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insumos` (
  `id_insumos` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `cantidad_total` int unsigned NOT NULL,
  `cantidad_disponible` int unsigned NOT NULL,
  `id_usuarios` int DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_insumos`),
  KEY `id_usuarios` (`id_usuarios`),
  CONSTRAINT `insumos_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insumos`
--

LOCK TABLES `insumos` WRITE;
/*!40000 ALTER TABLE `insumos` DISABLE KEYS */;
/*!40000 ALTER TABLE `insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libro`
--

DROP TABLE IF EXISTS `libro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `libro` (
  `id_libro` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `edicion` varchar(100) NOT NULL,
  `anio_publicacion` smallint NOT NULL,
  `stock` int unsigned NOT NULL DEFAULT '1',
  `isbn` varchar(17) NOT NULL,
  `estado` tinyint NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_libro`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libro`
--

LOCK TABLES `libro` WRITE;
/*!40000 ALTER TABLE `libro` DISABLE KEYS */;
INSERT INTO `libro` VALUES (2,'Harry Potter y la piedra filosofal','1ra ediciÃ³n',1997,2,'978074753',1,1),(3,'1984','1ra ediciÃ³n',1949,11,'978045',1,1),(8,'Clean Code','1ra ediciÃ³n',2008,1,'9780132350884',1,1),(9,'The Pragmatic Programmer','2da edicion',2019,11,'9780135957059',1,1),(10,'Code Complete','2da edicion',2004,11,'9780735619678',1,1),(11,'Fahrenheit 451','EdiciÃ³n 60.Âº aniversario',1953,11,'9781451673319',1,1),(12,'The Little Prince','EdiciÃ³n de bolsillo',1943,1,'9780156012195',1,1),(13,'Sapiens','1ra ediciÃ³n',2015,1,'9780062316097',1,1);
/*!40000 ALTER TABLE `libro` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `libro_bi` BEFORE INSERT ON `libro` FOR EACH ROW BEGIN
    SET NEW.estado = IF(NEW.stock > 0, 1, 0);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `libro_bu` BEFORE UPDATE ON `libro` FOR EACH ROW BEGIN
    SET NEW.estado = IF(NEW.stock > 0, 1, 0);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `modulos_config`
--

DROP TABLE IF EXISTS `modulos_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulos_config` (
  `id_modulo` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `clave` varchar(50) NOT NULL,
  PRIMARY KEY (`id_modulo`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulos_config`
--

LOCK TABLES `modulos_config` WRITE;
/*!40000 ALTER TABLE `modulos_config` DISABLE KEYS */;
INSERT INTO `modulos_config` VALUES (1,'CatÃ¡logo de Libros','catalogo'),(2,'Mis PrÃ©stamos','prestamos'),(3,'Mis Reservas','reservas');
/*!40000 ALTER TABLE `modulos_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_insumos`
--

DROP TABLE IF EXISTS `movimientos_insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos_insumos` (
  `id_movimientos_insumos` int NOT NULL AUTO_INCREMENT,
  `tipo_insumo` varchar(100) NOT NULL,
  `cantidad` int unsigned NOT NULL,
  `fecha_prestamo` datetime NOT NULL,
  `fecha_devolucion` datetime NOT NULL,
  `motivo` varchar(100) DEFAULT NULL,
  `id_insumos` int DEFAULT NULL,
  PRIMARY KEY (`id_movimientos_insumos`),
  KEY `id_insumos` (`id_insumos`),
  CONSTRAINT `movimientos_insumos_ibfk_1` FOREIGN KEY (`id_insumos`) REFERENCES `insumos` (`id_insumos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_insumos`
--

LOCK TABLES `movimientos_insumos` WRITE;
/*!40000 ALTER TABLE `movimientos_insumos` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos_insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multa`
--

DROP TABLE IF EXISTS `multa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `multa` (
  `id_multa` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_prestamo` int DEFAULT NULL,
  `motivo` enum('retraso','rayado','maltratado','roto') NOT NULL,
  `dias_sancion` int unsigned NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `estado` enum('activa','anulada') NOT NULL DEFAULT 'activa',
  `observaciones` varchar(500) DEFAULT NULL,
  `id_registrador` int DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_multa`),
  KEY `idx_multa_usuario_vigencia` (`id_usuario`,`estado`,`fecha_fin`),
  KEY `idx_multa_prestamo` (`id_prestamo`),
  KEY `fk_multa_registrador` (`id_registrador`),
  CONSTRAINT `fk_multa_prestamo` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamo` (`id_prestamo`) ON DELETE SET NULL,
  CONSTRAINT `fk_multa_registrador` FOREIGN KEY (`id_registrador`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `fk_multa_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multa`
--

LOCK TABLES `multa` WRITE;
/*!40000 ALTER TABLE `multa` DISABLE KEYS */;
INSERT INTO `multa` VALUES (1,19,6,'rayado',7,'2026-10-16 16:25:30','2026-10-23 16:25:30','activa','rayado con lapiz',9,'2026-10-16 16:25:30'),(2,24,8,'rayado',7,'2026-10-27 19:52:09','2026-11-03 19:52:09','anulada','con fibron',9,'2026-10-27 19:52:09');
/*!40000 ALTER TABLE `multa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion` (
  `id_notificacion` int NOT NULL AUTO_INCREMENT,
  `mensaje` varchar(500) NOT NULL,
  `fecha_envio` date NOT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pais`
--

DROP TABLE IF EXISTS `pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pais` (
  `id_pais` int NOT NULL AUTO_INCREMENT,
  `nombre_pais` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pais`
--

LOCK TABLES `pais` WRITE;
/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `fk_usuario` (`id_usuario`),
  CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `tipo_perfil` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_perfil`),
  UNIQUE KEY `unique_tipo_perfil` (`tipo_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'alumno',1),(2,'bibliotecario',1),(3,'profesor',1),(4,'invitado',1);
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_insert_perfil` AFTER INSERT ON `perfil` FOR EACH ROW BEGIN
    INSERT INTO perfil_modulos (id_perfil, id_modulo, activo)
    SELECT NEW.id_perfil, id_modulo, 1
    FROM modulos_config;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `perfil_modulos`
--

DROP TABLE IF EXISTS `perfil_modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil_modulos` (
  `id_perfil` int NOT NULL,
  `id_modulo` int NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_perfil`,`id_modulo`),
  KEY `perfil_modulos_ibfk_2` (`id_modulo`),
  CONSTRAINT `perfil_modulos_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`),
  CONSTRAINT `perfil_modulos_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos_config` (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_modulos`
--

LOCK TABLES `perfil_modulos` WRITE;
/*!40000 ALTER TABLE `perfil_modulos` DISABLE KEYS */;
INSERT INTO `perfil_modulos` VALUES (1,1,1),(1,2,1),(1,3,1),(2,1,1),(2,2,1),(2,3,1),(3,1,1),(3,2,1),(3,3,1),(4,1,1),(4,2,1),(4,3,1);
/*!40000 ALTER TABLE `perfil_modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `dni` varchar(20) NOT NULL,
  PRIMARY KEY (`id_persona`),
  UNIQUE KEY `unique_dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (10,'Miguel','Mulqui','2003-10-11','44344934'),(20,'asdf','asfasd','2029-11-11','434443434'),(21,'Jonas','Vera','2002-07-12','44224952'),(22,'Salma','Sanchez','2002-10-02','44256056'),(23,'Denis','Gomez','2000-08-09','33333333'),(24,'Rodrigo','Gaona','2002-08-31','22222222'),(25,'Maria','Valdez','1971-09-21','11111111'),(26,'Daiara','Mulqui','1997-12-02','40626704'),(27,'aaaaaaaa','aaaaaa','2000-12-12','40400400'),(28,'Juan','Perez','2000-11-11','12333444'),(29,'<script>alert(\"hola\");</script>','asd','2000-11-11','11122345');
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestamo`
--

DROP TABLE IF EXISTS `prestamo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prestamo` (
  `id_prestamo` int NOT NULL AUTO_INCREMENT,
  `codigo_prestamo` int NOT NULL,
  `fecha_prestamo` datetime NOT NULL,
  `fecha_vencimieto` datetime NOT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `id_libro` int NOT NULL,
  `id_estado` int NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_prestamo`),
  KEY `id_libro_idx` (`id_libro`),
  KEY `id_usuario_idx` (`id_usuario`),
  KEY `fk_prestamo_estado` (`id_estado`),
  CONSTRAINT `fk_prestamo_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado_prestamo` (`id_estado`),
  CONSTRAINT `fk_prestamo_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  CONSTRAINT `fk_prestamo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestamo`
--

LOCK TABLES `prestamo` WRITE;
/*!40000 ALTER TABLE `prestamo` DISABLE KEYS */;
INSERT INTO `prestamo` VALUES (1,507233,'2026-08-30 21:26:36','2026-09-06 21:26:36','2026-08-30 21:26:44',2,2,24),(2,615022,'2026-09-18 17:46:33','2026-09-25 17:46:33','2026-10-02 18:42:49',11,2,24),(3,360971,'2026-09-18 17:46:48','2026-09-25 17:46:48','2026-10-02 18:42:43',13,2,19),(4,691428,'2026-09-18 17:47:25','2026-09-25 17:47:25','2026-10-02 18:42:24',2,2,20),(5,604515,'2026-10-13 17:56:16','2026-10-20 17:56:16','2026-10-16 00:43:11',2,2,21),(6,186229,'2026-10-16 16:25:08','2026-10-23 16:25:08','2026-10-16 16:25:30',3,2,19),(7,600480,'2026-10-27 19:33:18','2026-11-03 19:33:18','2026-10-27 19:51:18',9,2,19),(8,800908,'2026-10-27 19:51:51','2026-11-03 19:51:51','2026-10-27 19:52:09',10,2,24),(9,424489,'2026-11-10 22:07:52','2026-11-17 22:07:52',NULL,2,1,24);
/*!40000 ALTER TABLE `prestamo` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `prestamo_bi` BEFORE INSERT ON `prestamo` FOR EACH ROW BEGIN
    DECLARE v_stock INT;
    SELECT stock INTO v_stock FROM libro WHERE id_libro = NEW.id_libro FOR UPDATE;
    IF v_stock IS NULL OR v_stock < 1 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El libro no tiene stock disponible';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `prestamo_ai` AFTER INSERT ON `prestamo` FOR EACH ROW BEGIN
    UPDATE libro SET stock = stock - 1 WHERE id_libro = NEW.id_libro;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `prestamo_au` AFTER UPDATE ON `prestamo` FOR EACH ROW BEGIN
    -- activo (1) o vencido (3) -> devuelto (2): vuelve el ejemplar
    IF OLD.id_estado IN (1, 3) AND NEW.id_estado = 2 THEN
        UPDATE libro SET stock = stock + 1 WHERE id_libro = NEW.id_libro;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `profesor`
--

DROP TABLE IF EXISTS `profesor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profesor` (
  `id_profesor` int NOT NULL AUTO_INCREMENT,
  `numero_prestamos` int unsigned DEFAULT NULL,
  `usuario_id_usuario` int NOT NULL,
  PRIMARY KEY (`id_profesor`,`usuario_id_usuario`),
  KEY `fk_profesor_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_profesor_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profesor`
--

LOCK TABLES `profesor` WRITE;
/*!40000 ALTER TABLE `profesor` DISABLE KEYS */;
/*!40000 ALTER TABLE `profesor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registros_pendientes`
--

DROP TABLE IF EXISTS `registros_pendientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registros_pendientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `dni` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira_en` datetime NOT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registros_pendientes`
--

LOCK TABLES `registros_pendientes` WRITE;
/*!40000 ALTER TABLE `registros_pendientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `registros_pendientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rela_aut_lib`
--

DROP TABLE IF EXISTS `rela_aut_lib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rela_aut_lib` (
  `id_rela_aut_lib` int NOT NULL AUTO_INCREMENT,
  `id_libro` int DEFAULT NULL,
  `id_autor` int DEFAULT NULL,
  PRIMARY KEY (`id_rela_aut_lib`),
  KEY `id_libro` (`id_libro`),
  KEY `rela_aut_lib_ibfk_2` (`id_autor`),
  CONSTRAINT `rela_aut_lib_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  CONSTRAINT `rela_aut_lib_ibfk_2` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id_autor`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_aut_lib`
--

LOCK TABLES `rela_aut_lib` WRITE;
/*!40000 ALTER TABLE `rela_aut_lib` DISABLE KEYS */;
INSERT INTO `rela_aut_lib` VALUES (2,2,5),(3,3,6),(8,8,11),(9,9,12),(10,10,13),(11,11,14),(12,12,15),(13,13,16);
/*!40000 ALTER TABLE `rela_aut_lib` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rela_cat_lib_gen`
--

DROP TABLE IF EXISTS `rela_cat_lib_gen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rela_cat_lib_gen` (
  `id_rela_cat_gen` int NOT NULL AUTO_INCREMENT,
  `id_libro` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL,
  `id_genero` int DEFAULT NULL,
  PRIMARY KEY (`id_rela_cat_gen`),
  KEY `id_libro` (`id_libro`),
  KEY `id_categoria` (`id_categoria`),
  KEY `id_genero` (`id_genero`),
  CONSTRAINT `rela_cat_lib_gen_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  CONSTRAINT `rela_cat_lib_gen_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `rela_cat_lib_gen_ibfk_3` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_cat_lib_gen`
--

LOCK TABLES `rela_cat_lib_gen` WRITE;
/*!40000 ALTER TABLE `rela_cat_lib_gen` DISABLE KEYS */;
INSERT INTO `rela_cat_lib_gen` VALUES (2,2,5,5),(3,3,6,6),(8,8,11,11),(9,9,12,12),(10,10,13,13),(11,11,14,14),(12,12,15,15),(13,13,16,16);
/*!40000 ALTER TABLE `rela_cat_lib_gen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rela_edit_lib`
--

DROP TABLE IF EXISTS `rela_edit_lib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rela_edit_lib` (
  `id_rela_edit_lib` int NOT NULL AUTO_INCREMENT,
  `id_libro` int DEFAULT NULL,
  `id_editorial` int DEFAULT NULL,
  PRIMARY KEY (`id_rela_edit_lib`),
  KEY `id_libro` (`id_libro`),
  KEY `id_editorial` (`id_editorial`),
  CONSTRAINT `rela_edit_lib_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  CONSTRAINT `rela_edit_lib_ibfk_2` FOREIGN KEY (`id_editorial`) REFERENCES `editorial` (`id_editorial`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_edit_lib`
--

LOCK TABLES `rela_edit_lib` WRITE;
/*!40000 ALTER TABLE `rela_edit_lib` DISABLE KEYS */;
INSERT INTO `rela_edit_lib` VALUES (2,2,5),(3,3,6),(8,8,11),(9,9,12),(10,10,13),(11,11,14),(12,12,15),(13,13,16);
/*!40000 ALTER TABLE `rela_edit_lib` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resenia`
--

DROP TABLE IF EXISTS `resenia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resenia` (
  `id_resenia` int NOT NULL AUTO_INCREMENT,
  `fecha_resenia` date NOT NULL,
  `comentario_resenia` varchar(500) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_libro` int DEFAULT NULL,
  PRIMARY KEY (`id_resenia`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_libro` (`id_libro`),
  CONSTRAINT `resenia_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `resenia_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resenia`
--

LOCK TABLES `resenia` WRITE;
/*!40000 ALTER TABLE `resenia` DISABLE KEYS */;
/*!40000 ALTER TABLE `resenia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserva`
--

DROP TABLE IF EXISTS `reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reserva` (
  `id_reserva` int NOT NULL AUTO_INCREMENT,
  `fecha_solicitud` datetime NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `codigo_reserva` int NOT NULL,
  `id_libro` int NOT NULL,
  `id_estado` int NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `id_libro_idx` (`id_libro`),
  KEY `id_usuario_idx` (`id_usuario`),
  KEY `fk_reserva_estado` (`id_estado`),
  CONSTRAINT `fk_reserva_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado_reserva` (`id_estado`),
  CONSTRAINT `fk_reserva_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
INSERT INTO `reserva` VALUES (1,'2026-08-30 21:27:12','2026-08-30 21:27:12',847114,3,3,20),(2,'2026-09-18 17:47:45','2026-09-18 17:47:45',470181,10,3,19),(3,'2026-09-18 17:48:01','2026-09-18 17:48:01',250139,12,3,24);
/*!40000 ALTER TABLE `reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_notificacion`
--

DROP TABLE IF EXISTS `tipo_notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_notificacion` (
  `id_tipo_notificacion` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) DEFAULT NULL,
  `id_notificacion` int DEFAULT NULL,
  PRIMARY KEY (`id_tipo_notificacion`),
  KEY `id_notificacion` (`id_notificacion`),
  CONSTRAINT `tipo_notificacion_ibfk_1` FOREIGN KEY (`id_notificacion`) REFERENCES `notificacion` (`id_notificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_notificacion`
--

LOCK TABLES `tipo_notificacion` WRITE;
/*!40000 ALTER TABLE `tipo_notificacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `contraseÃ±a` varchar(255) DEFAULT NULL,
  `avatar` blob,
  `id_perfil` int DEFAULT NULL,
  `persona_id_persona` int NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT (1) COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_usuario`,`persona_id_persona`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `contraseÃ±a` (`contraseÃ±a`),
  KEY `id_perfil` (`id_perfil`),
  KEY `fk_usuario_persona1_idx` (`persona_id_persona`),
  CONSTRAINT `fk_usuario_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (9,'miguelmulqui@hotmail.com','$2y$12$OhR4MySC5aEsVcj6papj0.6Mkg8DsvDfYixQnL4UCufVAok696bzG',NULL,2,10,1),(18,'bbb@hotmail.com','$2y$12$kjdxkX7Xv7OH5hQGEHYsNOHmvsKEHuVmuuKfdWu2u12JGIJiGCZVu',NULL,1,20,0),(19,'jonasalejandrovera123@hotmail.com','$2y$12$pam4oMm145EDrBWcLzSsQeFgNbAMyfIwPmvXhClBYXiVkOzZTQfgW',NULL,1,21,1),(20,'salmasanchez@hotmail.com','$2y$12$rUOtKp2uzSXSJsnex5osWOzN9ijmKj0V2svUGkvgGMT0elH6Qokqu',NULL,2,22,1),(21,'denisgomez@hotmail.com','$2y$12$hHZQfwT3nWpbPrE8zFfAd.7WG2AY.l39.Tm4JzzicQL6S2z1g7nW.',NULL,3,23,1),(22,'rodrigogaona@hotmail.com','$2y$12$pvc0MIqVkw.oc5viAMpE7.wzmdtpubBfDwXu7c.87CSzntv78vFTi',NULL,1,24,0),(23,'mariavaldez@gmail.com','$2y$12$RphA3KsBXO4fp1diArKAuOQ2XW1.zox7DDwQuWYBkVQCuZMXKavIa',NULL,2,25,0),(24,'mulquidaiara@gmail.com','$2y$12$HqmU1rJjP2Xt3PFiC49X6e9A8Ve1NwFpeA40IybdTwR4qLRUYUzlC',NULL,1,26,1),(25,'a@gmail.com','$2y$12$flVf5IgMXAL/3Ni/PDj5W.Ylparia3dTY92N48ly5LwL1MmZP7qvG',NULL,1,27,0),(26,'prueba@gmail.com','$2y$12$haDsEm0m3HQ4vg2yiC3rquwxFWK35YdKeKtNr5lPCYrTYVtNR3uz2',NULL,2,28,1),(27,'script@gmail.com','$2y$12$MXhm69O2FR0HQ0ME0cDsMuxzLD2sWMY80Ya9lLvBBCMPC/4oflrDe',NULL,1,29,1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_insert_usuario` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
    INSERT INTO usuario_modulos (id_usuario, id_modulo, activo)
    SELECT NEW.id_usuario, id_modulo, 1
    FROM modulos_config;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `usuario_modulos`
--

DROP TABLE IF EXISTS `usuario_modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_modulos` (
  `id_usuario` int NOT NULL,
  `id_modulo` int NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_usuario`,`id_modulo`),
  KEY `id_modulo` (`id_modulo`),
  CONSTRAINT `usuario_modulos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `usuario_modulos_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos_config` (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_modulos`
--

LOCK TABLES `usuario_modulos` WRITE;
/*!40000 ALTER TABLE `usuario_modulos` DISABLE KEYS */;
INSERT INTO `usuario_modulos` VALUES (9,1,1),(9,2,1),(9,3,1),(18,1,1),(18,2,1),(18,3,1),(19,1,1),(19,2,1),(19,3,1),(20,1,1),(20,2,1),(20,3,1),(21,1,1),(21,2,1),(21,3,1),(22,1,1),(22,2,1),(22,3,1),(23,1,1),(23,2,1),(23,3,1),(24,1,1),(24,2,1),(24,3,1),(25,1,1),(25,2,1),(25,3,1),(26,1,1),(26,2,1),(26,3,1),(27,1,1),(27,2,1),(27,3,1);
/*!40000 ALTER TABLE `usuario_modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'viblio_db'
--
/*!50003 DROP PROCEDURE IF EXISTS `modificar_libro` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `modificar_libro`(
    IN p_isbn VARCHAR(20),
    IN p_titulo VARCHAR(100),
    IN p_edicion VARCHAR(100),
    IN p_anio_publicacion SMALLINT,
    IN p_stock INT,
    IN p_autor VARCHAR(50),
    IN p_editorial VARCHAR(50),
    IN p_categoria VARCHAR(50),
    IN p_genero VARCHAR(50)
)
BEGIN
    DECLARE v_id_libro INT;
    DECLARE v_id_autor INT;
    DECLARE v_id_editorial INT;
    DECLARE v_id_categoria INT;
    DECLARE v_id_genero INT;

    SELECT id_libro INTO v_id_libro FROM libro WHERE isbn = p_isbn;

    SELECT id_autor INTO v_id_autor
    FROM rela_aut_lib WHERE id_libro = v_id_libro;

    SELECT id_editorial INTO v_id_editorial
    FROM rela_edit_lib WHERE id_libro = v_id_libro;

    SELECT id_categoria, id_genero INTO v_id_categoria, v_id_genero
    FROM rela_cat_lib_gen WHERE id_libro = v_id_libro;

    UPDATE autor SET nombre = p_autor WHERE id_autor = v_id_autor;
    UPDATE editorial SET nombre = p_editorial WHERE id_editorial = v_id_editorial;
    UPDATE categoria SET nombre = p_categoria WHERE id_categoria = v_id_categoria;
    UPDATE genero SET nombre = p_genero WHERE id_genero = v_id_genero;

    -- el trigger libro_bu recalcula "estado" a partir del stock
    UPDATE libro
    SET titulo = p_titulo,
        edicion = p_edicion,
        anio_publicacion = p_anio_publicacion,
        stock = p_stock
    WHERE id_libro = v_id_libro;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `registrar_alumno` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_alumno`(
    IN p_nombre VARCHAR(50),
    IN p_apellido VARCHAR(50),
    IN p_fecha_nacimiento DATE,
    IN p_dni VARCHAR(20),
    IN p_email VARCHAR(100),
    IN p_contraseÃ±a CHAR(60)
)
BEGIN
    DECLARE v_id_persona INT;
    DECLARE v_id_perfil INT;
    DECLARE v_id_usuario INT;
 
    START TRANSACTION;
 
    -- 1) Insertar persona
    INSERT INTO persona(nombre, apellido, fecha_nacimiento, dni)
    VALUES (p_nombre, p_apellido, p_fecha_nacimiento, p_dni);
 
    SET v_id_persona = LAST_INSERT_ID();
 
    -- 2) Obtener id_perfil del rol 'alumno' (siempre existe)
    SELECT id_perfil INTO v_id_perfil
    FROM perfil
    WHERE tipo_perfil = 'alumno';
 
    -- 3) Insertar usuario
    INSERT INTO usuario(email, contraseÃ±a, id_perfil, persona_id_persona)
    VALUES (p_email, p_contraseÃ±a, v_id_perfil, v_id_persona);
 
    SET v_id_usuario = LAST_INSERT_ID();
 
    -- 4) Insertar alumno
    INSERT INTO alumno(numero_prestamos, numero_multas, usuario_id_usuario)
    VALUES (0, 0, v_id_usuario);
 
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `registrar_bibliotecario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_bibliotecario`(
    IN p_nombre VARCHAR(50),
    IN p_apellido VARCHAR(50),
    IN p_fecha_nacimiento DATE,
    IN p_dni VARCHAR(20),
    IN p_email VARCHAR(100),
    IN p_contraseÃ±a CHAR(60),
    IN p_turno VARCHAR(25)
)
BEGIN
    DECLARE v_id_persona INT;
    DECLARE v_id_perfil INT;
    DECLARE v_id_usuario INT;
 
    START TRANSACTION;
 
    -- 1) Insertar persona
    INSERT INTO persona(nombre, apellido, fecha_nacimiento, dni)
    VALUES (p_nombre, p_apellido, p_fecha_nacimiento, p_dni);
 
    SET v_id_persona = LAST_INSERT_ID();
 
    -- 2) Obtener id_perfil del rol 'bibliotecario'
    SELECT id_perfil INTO v_id_perfil
    FROM perfil
    WHERE tipo_perfil = 'bibliotecario';
 
    -- 3) Insertar usuario
    INSERT INTO usuario(email, contraseÃ±a, id_perfil, persona_id_persona)
    VALUES (p_email, p_contraseÃ±a, v_id_perfil, v_id_persona);
 
    SET v_id_usuario = LAST_INSERT_ID();
 
    -- 4) Insertar bibliotecario con su turno
    INSERT INTO bibliotecario(turno, usuario_id_usuario)
    VALUES (p_turno, v_id_usuario);
 
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `registrar_libro` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_libro`(
    IN p_titulo VARCHAR(100),
    IN p_edicion VARCHAR(100),
    IN p_anio_publicacion SMALLINT,
    IN p_isbn VARCHAR(20),
    IN p_stock INT,
    IN p_autor VARCHAR(50),
    IN p_editorial VARCHAR(50),
    IN p_categoria VARCHAR(50),
    IN p_genero VARCHAR(50)
)
BEGIN
    START TRANSACTION;
    INSERT INTO autor(nombre) VALUES (p_autor);
    SET @id_autor = LAST_INSERT_ID();
    INSERT INTO editorial(nombre) VALUES (p_editorial);
    SET @id_editorial = LAST_INSERT_ID();
    INSERT INTO categoria(nombre) VALUES (p_categoria);
    SET @id_categoria = LAST_INSERT_ID();
    INSERT INTO genero(nombre) VALUES (p_genero);
    SET @id_genero = LAST_INSERT_ID();

    INSERT INTO libro(titulo, edicion, anio_publicacion, isbn, stock, estado)
    VALUES (p_titulo, p_edicion, p_anio_publicacion, p_isbn, p_stock, IF(p_stock > 0, 1, 0));
    SET @id_libro = LAST_INSERT_ID();

    INSERT INTO rela_aut_lib(id_libro, id_autor) VALUES (@id_libro, @id_autor);
    INSERT INTO rela_edit_lib(id_libro, id_editorial) VALUES (@id_libro, @id_editorial);
    INSERT INTO rela_cat_lib_gen(id_libro, id_categoria, id_genero)
    VALUES (@id_libro, @id_categoria, @id_genero);
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `registrar_profesor` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_profesor`(
    IN p_nombre VARCHAR(50),
    IN p_apellido VARCHAR(50),
    IN p_fecha_nacimiento DATE,
    IN p_dni VARCHAR(20),
    IN p_email VARCHAR(100),
    IN p_contraseÃ±a CHAR(60)
)
BEGIN
    DECLARE v_id_persona INT;
    DECLARE v_id_perfil INT;
    DECLARE v_id_usuario INT;
 
    START TRANSACTION;
 
    -- 1) Insertar persona
    INSERT INTO persona(nombre, apellido, fecha_nacimiento, dni)
    VALUES (p_nombre, p_apellido, p_fecha_nacimiento, p_dni);
 
    SET v_id_persona = LAST_INSERT_ID();
 
    -- 2) Obtener id_perfil del rol 'profesor'
    SELECT id_perfil INTO v_id_perfil
    FROM perfil
    WHERE tipo_perfil = 'profesor';
 
    -- 3) Insertar usuario
    INSERT INTO usuario(email, contraseÃ±a, id_perfil, persona_id_persona)
    VALUES (p_email, p_contraseÃ±a, v_id_perfil, v_id_persona);
 
    SET v_id_usuario = LAST_INSERT_ID();
 
    -- 4) Insertar profesor
    INSERT INTO profesor(numero_prestamos, usuario_id_usuario)
    VALUES (0, v_id_usuario);
 
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-17 18:01:36
