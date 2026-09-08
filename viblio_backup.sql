CREATE DATABASE  IF NOT EXISTS `viblio_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `viblio_db`;
-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: viblio_db
-- ------------------------------------------------------
-- Server version	8.0.46

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumno`
--

LOCK TABLES `alumno` WRITE;
/*!40000 ALTER TABLE `alumno` DISABLE KEYS */;
INSERT INTO `alumno` VALUES (16,0,0,18),(17,0,0,20),(18,0,0,24),(19,0,0,25),(20,0,0,26),(21,0,0,27),(22,0,0,28),(23,0,0,29),(24,0,0,30),(25,0,0,31),(26,0,0,32),(27,0,0,33),(28,0,0,34),(29,0,0,35),(30,0,0,36),(31,0,0,37);
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
  `accion` varchar(255) NOT NULL,
  `detalle` text,
  `ip` varchar(45) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id_auditoria`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,9,'login_exitoso','','::1','2026-09-04 22:59:18'),(2,9,'baja_usuario','dni: 46522893','::1','2026-09-05 00:43:56'),(3,9,'baja_usuario','dni: 40626704','::1','2026-09-05 00:44:32'),(4,NULL,'registro_usuario','email: facundoesquivel03@gmail.com','::1','2026-09-05 01:06:36'),(5,NULL,'registro_usuario','email: scdm0407@gmail.com','::1','2026-09-05 01:08:54'),(6,NULL,'registro_usuario','email: gg10exequiel@gmail.com','::1','2026-09-05 01:11:33'),(7,NULL,'registro_usuario','email: lub899176@gmail.com','::1','2026-09-05 01:13:47'),(8,NULL,'registro_usuario','email: marquitosk05@gmail.com','::1','2026-09-05 01:16:28'),(9,NULL,'registro_usuario','email: sosapatricio2025@gamil.com','::1','2026-09-05 01:18:53'),(10,NULL,'registro_usuario','email: miguelangelromero2o1553@gmail.com','::1','2026-09-05 01:21:32'),(11,9,'modificar_usuario','id editado: 25','::1','2026-09-05 01:24:06'),(12,9,'modificar_usuario','id editado: 27','::1','2026-09-05 01:24:17'),(13,9,'modificar_usuario','id editado: 28','::1','2026-09-05 01:24:36'),(14,9,'modificar_usuario','id editado: 29','::1','2026-09-05 01:25:55'),(15,9,'modificar_usuario','id editado: 30','::1','2026-09-05 01:26:02'),(16,9,'modificar_usuario','id editado: 31','::1','2026-09-05 01:26:11'),(17,9,'modificar_usuario','id editado: 32','::1','2026-09-05 01:26:21'),(18,9,'modificar_usuario','id editado: 33','::1','2026-09-05 01:26:33'),(19,NULL,'registro_usuario','email: lucidavis@gmail.com','::1','2026-09-05 01:30:04'),(20,NULL,'registro_usuario','email: tobiias398@gamil.com','::1','2026-09-05 01:31:23'),(21,NULL,'registro_usuario','email: sebalr4m@gmail.com','::1','2026-09-05 01:36:04'),(22,9,'modificar_usuario','id editado: 36','::1','2026-09-05 01:38:14'),(23,NULL,'registro_usuario','email: alexanderalarcon949@gmail.com','::1','2026-09-05 01:40:12');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autor`
--

LOCK TABLES `autor` WRITE;
/*!40000 ALTER TABLE `autor` DISABLE KEYS */;
INSERT INTO `autor` VALUES (1,NULL,'J. R. R. Tolkien',NULL,NULL,1),(2,NULL,'J. R. R. Tolkien',NULL,NULL,1),(3,NULL,'J. R. R. Tolkien',NULL,NULL,1),(4,NULL,'J. R. R. Tolkien',NULL,NULL,1),(5,NULL,'yo',NULL,NULL,1),(6,NULL,'George Orwell',NULL,NULL,1),(7,NULL,'undefined',NULL,NULL,1),(8,NULL,'Gabriel García Márquez',NULL,NULL,1),(9,NULL,'qsy',NULL,NULL,1),(10,NULL,'asdasd',NULL,NULL,1),(11,NULL,'Stephen King',NULL,NULL,1),(12,NULL,'Jane Austen',NULL,NULL,1),(13,NULL,'Stephen King',NULL,NULL,1),(14,NULL,'John Green',NULL,NULL,1),(15,NULL,'fulanito',NULL,NULL,1),(16,NULL,'fulanito',NULL,NULL,1),(17,NULL,'J.K. Rowlling',NULL,NULL,1),(18,NULL,'Kayser',NULL,NULL,1),(19,NULL,'Marcos Kayser',NULL,NULL,1),(20,NULL,'Homero',NULL,NULL,1),(21,NULL,'Bran Stocker',NULL,NULL,1);
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
INSERT INTO `bibliotecario` VALUES (1,NULL,9),(2,'Mañana',19),(3,'tarde',21),(4,'Mañana',22),(5,'tarde',23);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Fantasía',1),(2,'Fantasía',1),(3,'Fantasía',1),(4,'Fantasía',1),(5,'Fantasía',1),(6,'Ciencia ficción',1),(7,'terro',1),(8,'Ficción literaria',1),(9,'asdasd',1),(10,'asdasdasd',1),(11,'Novela',1),(12,'Novela',1),(13,'Novela',1),(14,'Novela',1),(15,'motivacion',1),(16,'psicologia',1),(17,'novela',1),(18,'Novela',1),(19,'Novela',1),(20,'Novela',1),(21,'Novela',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editorial`
--

LOCK TABLES `editorial` WRITE;
/*!40000 ALTER TABLE `editorial` DISABLE KEYS */;
INSERT INTO `editorial` VALUES (1,'Minotauro',NULL,NULL,1),(2,'Minotauro',NULL,NULL,1),(3,'Minotauro',NULL,NULL,1),(4,'Minotauro',NULL,NULL,1),(5,'Bloomsbury',NULL,NULL,1),(6,'Secker & Warburg',NULL,NULL,1),(7,'undefined',NULL,NULL,1),(8,' Editorial Sudamericana',NULL,NULL,1),(9,'sada',NULL,NULL,1),(10,'asdasdasd',NULL,NULL,1),(11,'Plaza & Janés',NULL,NULL,1),(12,'Alianza Editorial',NULL,NULL,1),(13,'Plaza & Janés',NULL,NULL,1),(14,'Nube de Tinta',NULL,NULL,1),(15,'Nube de Tinta',NULL,NULL,1),(16,'Alianza Editorial',NULL,NULL,1),(17,'Kapeluz',NULL,NULL,1),(18,'Alienigena',NULL,NULL,1),(19,'Kapeluf',NULL,NULL,1),(20,'Kapeluz',NULL,NULL,1),(21,'Paridi',NULL,NULL,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'Épico',NULL,1),(2,'Épico',NULL,1),(3,'Épico',NULL,1),(4,'Épico',NULL,1),(5,'Aventura',NULL,1),(6,'Distopía',NULL,1),(7,'undefined',NULL,1),(8,'Realismo mágico',NULL,1),(9,'asdasd',NULL,1),(10,'asdasdasd',NULL,1),(11,'Terror',NULL,1),(12,'Romántico',NULL,1),(13,'Terror',NULL,1),(14,'Romántico',NULL,1),(15,'financiero',NULL,1),(16,'educativo',NULL,1),(17,'fantasia',NULL,1),(18,'Terror',NULL,1),(19,'Drama',NULL,1),(20,'fantasia',NULL,1),(21,'Terror',NULL,1);
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
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
  `isbn` varchar(17) NOT NULL,
  `estado` tinyint NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_libro`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libro`
--

LOCK TABLES `libro` WRITE;
/*!40000 ALTER TABLE `libro` DISABLE KEYS */;
INSERT INTO `libro` VALUES (2,'Harry Potter y la piedra filosofal','1ra edición',1997,'978074753',1,1),(3,'1984','1ra edición',1949,'978045',1,1),(8,'El Resplandor','1ra edición',1977,'978-8497592208',1,1),(9,'Orgullo y Prejuicio','2da edición',1813,'978-8420674190',1,1),(10,'It (Eso)','1ra edición',1986,'978-8497594851',1,1),(11,'Bajo la Misma Estrella','1ra edición',2012,'978-8425342664',1,1),(12,'Padre rico, Padre pobre','1ra edición',2024,'11111111',1,1),(13,'El poder de la persuacion','1ra edición',2020,'111222222',1,1),(14,'Harry Potter La Reliqui de la muerte','1ra edición',2006,'33333333',1,1),(15,'Alien','1ra edición',1960,'4444444',1,1),(16,'Piranicidio','1ra edición',2026,'12312312313',1,1),(17,'La odisea','1ra edición',1800,'2222222',1,1),(18,'Dracula','1ra edición',1897,'5555555',1,1);
/*!40000 ALTER TABLE `libro` ENABLE KEYS */;
UNLOCK TABLES;

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
INSERT INTO `modulos_config` VALUES (1,'Catálogo de Libros','catalogo'),(2,'Mis Préstamos','prestamos'),(3,'Mis Reservas','reservas');
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
  `monto` decimal(10,2) unsigned NOT NULL,
  `fecha_multa` date NOT NULL,
  `estado` tinyint NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `id_alumno` int DEFAULT NULL,
  PRIMARY KEY (`id_multa`),
  KEY `id_alumno` (`id_alumno`),
  CONSTRAINT `multa_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumno` (`id_alumno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multa`
--

LOCK TABLES `multa` WRITE;
/*!40000 ALTER TABLE `multa` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'alumno',1),(2,'bibliotecario',1),(3,'profesor',1),(4,'asd',1),(5,'sss',1),(6,'invitado',1);
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (10,'Miguel','Mulqui','2003-10-11','44344934'),(20,'asdf','asfasd','2029-11-11','434443434'),(21,'Jonas','Vera','2002-07-12','44224952'),(22,'Salma','Sanchez','2002-10-02','44256056'),(23,'Denis','Gomez','2000-08-09','33333333'),(24,'Rodrigo','Gaona','2002-08-31','22222222'),(25,'Maria','Valdez','1971-09-21','11111111'),(26,'Daiara','Mulqui','1997-12-02','40626704'),(27,'gonzalo','gauna','2002-01-11','55555555'),(28,'huber','ramirez','2006-05-19','46522893'),(29,'Facundo','Caballero','2003-08-11','44982366'),(30,'Santiago','Mora','1999-04-07','41415458'),(31,'Gonzalo','Gauna','2004-08-13','45901768'),(32,'Lujan','Benitez','2004-08-12','45903304'),(33,'Marcos','Kayser','2005-09-16','46321944'),(34,'Patricio','Sosa','2004-06-03','45899843'),(35,'Miguel','Romero','2001-03-10','43329003'),(36,'Lucia','Davis','2006-04-03','46468071'),(37,'Tobias','Almiron','2005-01-25','46065755'),(38,'Alan','Ramirez','2005-11-23','46394081'),(39,'Alexander','Alarcon','1999-09-02','42037330');
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestamo`
--

LOCK TABLES `prestamo` WRITE;
/*!40000 ALTER TABLE `prestamo` DISABLE KEYS */;
INSERT INTO `prestamo` VALUES (1,434409,'2026-09-01 19:36:15','2026-09-08 19:36:15','2026-09-01 19:36:28',2,2,25),(2,323827,'2026-09-04 19:11:44','2026-09-11 19:11:44','2026-09-04 19:12:05',2,2,9),(3,906374,'2026-09-04 19:11:58','2026-09-11 19:11:58','2026-09-04 19:12:11',3,2,9),(4,469197,'2026-09-04 20:07:04','2026-09-11 20:07:04','2026-09-04 20:07:45',9,2,27),(5,597116,'2026-09-04 20:07:20','2026-09-11 20:07:20','2026-09-04 20:07:25',10,2,27),(6,751774,'2026-09-04 20:09:32','2026-09-11 20:09:32','2026-09-04 20:10:16',10,2,28),(7,419805,'2026-09-04 20:09:45','2026-09-11 20:09:45','2026-09-04 20:10:10',2,2,28),(8,605120,'2026-09-04 20:11:56','2026-09-11 20:11:56','2026-09-04 20:12:23',2,2,29),(9,150913,'2026-09-04 20:12:07','2026-09-11 20:12:07','2026-09-04 20:12:19',3,2,29),(10,739335,'2026-09-04 20:14:20','2026-09-11 20:14:20','2026-09-04 20:14:58',2,2,30),(11,135387,'2026-09-04 20:14:37','2026-09-11 20:14:37','2026-09-04 20:14:52',11,2,30),(12,425512,'2026-09-04 20:16:55','2026-09-11 20:16:55','2026-09-04 20:17:36',3,2,31),(13,385843,'2026-09-04 20:17:16','2026-09-11 20:17:16','2026-09-04 20:17:33',8,2,31),(14,772896,'2026-09-04 20:19:22','2026-09-11 20:19:22','2026-09-04 20:19:54',8,2,32),(15,577121,'2026-09-04 20:19:38','2026-09-11 20:19:38','2026-09-04 20:19:51',10,2,32),(16,671123,'2026-09-04 20:22:01','2026-09-11 20:22:01','2026-09-04 20:25:26',2,2,33),(17,206006,'2026-09-04 20:22:39','2026-09-11 20:22:39','2026-09-04 20:25:30',3,2,33),(18,809058,'2026-09-04 20:30:18','2026-09-11 20:30:18','2026-09-04 20:30:23',12,2,34),(19,876519,'2026-09-04 20:31:58','2026-09-11 20:31:58','2026-09-04 20:32:57',2,2,35),(20,411495,'2026-09-04 20:32:20','2026-09-11 20:32:20','2026-09-04 20:32:51',12,2,35),(21,921560,'2026-09-04 20:37:24','2026-09-11 20:37:24','2026-09-04 20:38:18',2,2,36),(22,832720,'2026-09-04 20:37:35','2026-09-11 20:37:35','2026-09-04 20:38:22',3,2,36),(23,484570,'2026-09-04 20:40:55','2026-09-11 20:40:55','2026-09-04 20:42:56',2,2,37),(24,632460,'2026-09-04 20:41:13','2026-09-11 20:41:13','2026-09-04 20:43:00',3,2,37),(25,832645,'2026-09-04 20:45:08','2026-09-11 20:45:08','2026-09-04 20:45:14',14,2,20);
/*!40000 ALTER TABLE `prestamo` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_aut_lib`
--

LOCK TABLES `rela_aut_lib` WRITE;
/*!40000 ALTER TABLE `rela_aut_lib` DISABLE KEYS */;
INSERT INTO `rela_aut_lib` VALUES (2,2,5),(3,3,6),(8,8,11),(9,9,12),(10,10,13),(11,11,14),(12,12,15),(13,13,16),(14,14,17),(15,15,18),(16,16,19),(17,17,20),(18,18,21);
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_cat_lib_gen`
--

LOCK TABLES `rela_cat_lib_gen` WRITE;
/*!40000 ALTER TABLE `rela_cat_lib_gen` DISABLE KEYS */;
INSERT INTO `rela_cat_lib_gen` VALUES (2,2,5,5),(3,3,6,6),(8,8,11,11),(9,9,12,12),(10,10,13,13),(11,11,14,14),(12,12,15,15),(13,13,16,16),(14,14,17,17),(15,15,18,18),(16,16,19,19),(17,17,20,20),(18,18,21,21);
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_edit_lib`
--

LOCK TABLES `rela_edit_lib` WRITE;
/*!40000 ALTER TABLE `rela_edit_lib` DISABLE KEYS */;
INSERT INTO `rela_edit_lib` VALUES (2,2,5),(3,3,6),(8,8,11),(9,9,12),(10,10,13),(11,11,14),(12,12,15),(13,13,16),(14,14,17),(15,15,18),(16,16,19),(17,17,20),(18,18,21);
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
INSERT INTO `reserva` VALUES (1,'2026-09-01 21:06:50','2026-09-01 21:06:50',653216,2,3,25),(2,'2026-09-04 20:07:39','2026-09-04 20:07:39',686380,8,3,27),(3,'2026-09-04 20:10:05','2026-09-04 20:10:05',592494,11,3,28),(4,'2026-09-04 20:12:15','2026-09-04 20:12:15',529047,8,3,29),(5,'2026-09-04 20:14:47','2026-09-04 20:14:47',310683,10,3,30),(6,'2026-09-04 20:17:27','2026-09-04 20:17:27',578585,2,3,31),(7,'2026-09-04 20:19:47','2026-09-04 20:19:47',327706,9,3,32),(8,'2026-09-04 20:25:21','2026-09-04 20:25:21',600937,10,3,33),(9,'2026-09-04 20:32:32','2026-09-04 20:32:32',919010,10,3,35),(10,'2026-09-04 20:37:57','2026-09-04 20:37:57',621721,8,3,36),(11,'2026-09-04 20:42:51','2026-09-04 20:42:51',358636,13,3,37),(12,'2026-09-04 20:47:03','2026-09-04 20:47:03',794400,15,3,20),(13,'2026-09-04 20:47:47','2026-09-04 20:47:47',628237,2,3,20),(14,'2026-09-04 20:50:06','2026-09-04 20:50:06',446047,16,3,20);
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
  `contraseña` varchar(255) DEFAULT NULL,
  `avatar` blob,
  `id_perfil` int DEFAULT NULL,
  `persona_id_persona` int NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT (1) COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_usuario`,`persona_id_persona`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `contraseña` (`contraseña`),
  KEY `id_perfil` (`id_perfil`),
  KEY `fk_usuario_persona1_idx` (`persona_id_persona`),
  CONSTRAINT `fk_usuario_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (9,'miguelmulqui@hotmail.com','$2y$12$OhR4MySC5aEsVcj6papj0.6Mkg8DsvDfYixQnL4UCufVAok696bzG',NULL,2,10,1),(18,'bbb@hotmail.com','$2y$12$kjdxkX7Xv7OH5hQGEHYsNOHmvsKEHuVmuuKfdWu2u12JGIJiGCZVu',NULL,1,20,0),(19,'jonasalejandrovera123@hotmail.com','$2y$12$pam4oMm145EDrBWcLzSsQeFgNbAMyfIwPmvXhClBYXiVkOzZTQfgW',NULL,3,21,1),(20,'salmasanchez@hotmail.com','$2y$12$rUOtKp2uzSXSJsnex5osWOzN9ijmKj0V2svUGkvgGMT0elH6Qokqu',NULL,1,22,1),(21,'denisgomez@hotmail.com','$2y$12$hHZQfwT3nWpbPrE8zFfAd.7WG2AY.l39.Tm4JzzicQL6S2z1g7nW.',NULL,3,23,1),(22,'rodrigogaona@hotmail.com','$2y$12$pvc0MIqVkw.oc5viAMpE7.wzmdtpubBfDwXu7c.87CSzntv78vFTi',NULL,1,24,0),(23,'mariavaldez@gmail.com','$2y$12$RphA3KsBXO4fp1diArKAuOQ2XW1.zox7DDwQuWYBkVQCuZMXKavIa',NULL,2,25,0),(24,'mulquidaiara@gmail.com','$2y$12$HqmU1rJjP2Xt3PFiC49X6e9A8Ve1NwFpeA40IybdTwR4qLRUYUzlC',NULL,1,26,0),(25,'gonzalogauna@hotmail.com','$2y$12$oGXOpLZfXxP4M8ECH0s6N.TkbKA42xAz4GsrP6k5g4Nkl68wF9R4m',NULL,2,27,1),(26,'huberramirez@hotmail.com','$2y$12$mRPWwp/qpKBKuoWQNjqln.ctVRjTGIRtjNvwwyll5gvJQpaY5bduG',NULL,1,28,0),(27,'facundoesquivel03@gmail.com','$2y$12$kPF.gmRiENBGeYXdLHE0MOok3/jIXH2spvFL5ycW0yDlFn7DRnlgW',NULL,2,29,1),(28,'scdm0407@gmail.com','$2y$12$Fi3ICFoEl063RABeajKKkORz/FVR3.puWcbmmKBcfZmJtnuCb1WUi',NULL,2,30,1),(29,'gg10exequiel@gmail.com','$2y$12$5lKh80KA6lRQpWdyhL.qV.cfImzSJAzpw2vMQ6oZOGKaMayxLSHri',NULL,2,31,1),(30,'lub899176@gmail.com','$2y$12$aOyBmcHaIlj7ujjH6BjhV.mw0QOUbFPO.NJbKy8P.lNrkFjChaX76',NULL,2,32,1),(31,'marquitosk05@gmail.com','$2y$12$IH40moyiNcbsRMM5W7af5u34E0i/TJXfA/UTwCQeN9U4w6nUR1AeG',NULL,2,33,1),(32,'sosapatricio2025@gamil.com','$2y$12$QhmA/pH6S7RUnydHNDkhQuPOUULxAXLO3C8ghDdtYJw/JPb1DVC3y',NULL,2,34,1),(33,'miguelangelromero2o1553@gmail.com','$2y$12$KTwgxiItIqcDRUWJZVy9CeqskfIdds4AKyRb2r2q6fIZ5PIFG0aAa',NULL,2,35,1),(34,'lucidavis@gmail.com','$2y$12$gGa/PUjKJm0euW/4w5Ff.OWsrtMhAul9r8WvCe6rNySukVEC9salS',NULL,1,36,1),(35,'tobiias398@gamil.com','$2y$12$TxlfQ0oZmnVU7Iz325Fbw.PbUakYkJxn/B4LJ9PraNaWsblE.dmzO',NULL,1,37,1),(36,'sebalr4m@gmail.com','$2y$12$zbAbPhgj0APY0uY2IO1FpebrBJfkv.ZwXBHqwA6Kk8KPl5OQ0tanG',NULL,2,38,1),(37,'alexanderalarcon949@gmail.com','$2y$12$cO2.jCGC29K6q2Pw0hbu2.vmX7xoFqZgT0WpCYCQzRQVvASwHHCQO',NULL,1,39,1);
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
INSERT INTO `usuario_modulos` VALUES (9,1,1),(9,2,1),(9,3,1),(18,1,1),(18,2,1),(18,3,1),(19,1,1),(19,2,1),(19,3,1),(20,1,1),(20,2,1),(20,3,0),(21,1,1),(21,2,1),(21,3,1),(22,1,1),(22,2,1),(22,3,1),(23,1,1),(23,2,1),(23,3,1),(24,1,1),(24,2,1),(24,3,1),(25,1,1),(25,2,1),(25,3,1),(26,1,1),(26,2,1),(26,3,1),(27,1,1),(27,2,1),(27,3,1),(28,1,1),(28,2,1),(28,3,1),(29,1,1),(29,2,1),(29,3,1),(30,1,1),(30,2,1),(30,3,1),(31,1,1),(31,2,1),(31,3,1),(32,1,1),(32,2,1),(32,3,1),(33,1,1),(33,2,1),(33,3,1),(34,1,1),(34,2,1),(34,3,1),(35,1,1),(35,2,1),(35,3,1),(36,1,1),(36,2,1),(36,3,1),(37,1,1),(37,2,1),(37,3,1);
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
    IN p_estado TINYINT(1),
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

    -- Obtener libro
    SELECT id_libro INTO v_id_libro FROM libro WHERE isbn = p_isbn;

    -- Obtener relaciones actuales
    SELECT id_autor INTO v_id_autor
    FROM rela_aut_lib WHERE id_libro = v_id_libro;

    SELECT id_editorial INTO v_id_editorial
    FROM rela_edit_lib WHERE id_libro = v_id_libro;

    SELECT id_categoria, id_genero INTO v_id_categoria, v_id_genero
    FROM rela_cat_lib_gen WHERE id_libro = v_id_libro;

    -- Actualizar tablas relacionadas con los nuevos nombres
    UPDATE autor SET nombre = p_autor WHERE id_autor = v_id_autor;
    UPDATE editorial SET nombre = p_editorial WHERE id_editorial = v_id_editorial;
    UPDATE categoria SET nombre = p_categoria WHERE id_categoria = v_id_categoria;
    UPDATE genero SET nombre = p_genero WHERE id_genero = v_id_genero;

    -- Actualizar libro
    UPDATE libro
    SET titulo = p_titulo,
        edicion = p_edicion,
        anio_publicacion = p_anio_publicacion,
        estado = p_estado
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
    IN p_contraseña CHAR(60)
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
    INSERT INTO usuario(email, contraseña, id_perfil, persona_id_persona)
    VALUES (p_email, p_contraseña, v_id_perfil, v_id_persona);
 
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
    IN p_contraseña CHAR(60),
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
    INSERT INTO usuario(email, contraseña, id_perfil, persona_id_persona)
    VALUES (p_email, p_contraseña, v_id_perfil, v_id_persona);
 
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
    IN p_estado TINYINT(1),
    IN p_autor VARCHAR(50),
    IN p_editorial VARCHAR(50),
    IN p_categoria VARCHAR(50),
    IN p_genero VARCHAR(50)
)
BEGIN
    START TRANSACTION;
    -- 1) Insertar autor
    INSERT INTO autor(nombre) VALUES (p_autor);
    SET @id_autor = LAST_INSERT_ID();
    -- 2) Insertar editorial
    INSERT INTO editorial(nombre) VALUES (p_editorial);
    SET @id_editorial = LAST_INSERT_ID();
    -- 3) Insertar categoría
    INSERT INTO categoria(nombre) VALUES (p_categoria);
    SET @id_categoria = LAST_INSERT_ID();
    -- 4) Insertar género
    INSERT INTO genero(nombre) VALUES (p_genero);
    SET @id_genero = LAST_INSERT_ID();
    -- 5) Insertar libro (sin FK)
    INSERT INTO libro(titulo, edicion, anio_publicacion, isbn, estado)
    VALUES (p_titulo, p_edicion, p_anio_publicacion, p_isbn, p_estado);
    SET @id_libro = LAST_INSERT_ID();
    -- 6) RELACIONES
    INSERT INTO rela_aut_lib(id_libro, id_autor)
    VALUES (@id_libro, @id_autor);
    INSERT INTO rela_edit_lib(id_libro, id_editorial)
    VALUES (@id_libro, @id_editorial);
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
    IN p_contraseña CHAR(60)
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
    INSERT INTO usuario(email, contraseña, id_perfil, persona_id_persona)
    VALUES (p_email, p_contraseña, v_id_perfil, v_id_persona);
 
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

-- Dump completed on 2026-09-08 17:12:46
