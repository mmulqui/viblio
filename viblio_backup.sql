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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alumno`
--

LOCK TABLES `alumno` WRITE;
/*!40000 ALTER TABLE `alumno` DISABLE KEYS */;
INSERT INTO `alumno` VALUES (16,0,0,18),(17,0,0,20);
/*!40000 ALTER TABLE `alumno` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autor`
--

LOCK TABLES `autor` WRITE;
/*!40000 ALTER TABLE `autor` DISABLE KEYS */;
INSERT INTO `autor` VALUES (1,NULL,'J. R. R. Tolkien',NULL,NULL,1),(2,NULL,'J. R. R. Tolkien',NULL,NULL,1),(3,NULL,'J. R. R. Tolkien',NULL,NULL,1),(4,NULL,'J. R. R. Tolkien',NULL,NULL,1),(5,NULL,'yo',NULL,NULL,1),(6,NULL,'George Orwell',NULL,NULL,1),(7,NULL,'undefined',NULL,NULL,1),(8,NULL,'Gabriel García Márquez',NULL,NULL,1),(9,NULL,'qsy',NULL,NULL,1),(10,NULL,'asdasd',NULL,NULL,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Fantasía',1),(2,'Fantasía',1),(3,'Fantasía',1),(4,'Fantasía',1),(5,'Fantasía',1),(6,'Ciencia ficción',1),(7,'terro',1),(8,'Ficción literaria',1),(9,'asdasd',1),(10,'asdasdasd',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editorial`
--

LOCK TABLES `editorial` WRITE;
/*!40000 ALTER TABLE `editorial` DISABLE KEYS */;
INSERT INTO `editorial` VALUES (1,'Minotauro',NULL,NULL,1),(2,'Minotauro',NULL,NULL,1),(3,'Minotauro',NULL,NULL,1),(4,'Minotauro',NULL,NULL,1),(5,'Bloomsbury',NULL,NULL,1),(6,'Secker & Warburg',NULL,NULL,1),(7,'undefined',NULL,NULL,1),(8,' Editorial Sudamericana',NULL,NULL,1),(9,'sada',NULL,NULL,1),(10,'asdasdasd',NULL,NULL,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'Épico',NULL,1),(2,'Épico',NULL,1),(3,'Épico',NULL,1),(4,'Épico',NULL,1),(5,'Aventura',NULL,1),(6,'Distopía',NULL,1),(7,'undefined',NULL,1),(8,'Realismo mágico',NULL,1),(9,'asdasd',NULL,1),(10,'asdasdasd',NULL,1);
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
  `anio_publicacion` year NOT NULL,
  `isbn` int NOT NULL,
  `estado` tinyint NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = eliminado (borrado logico)',
  PRIMARY KEY (`id_libro`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libro`
--

LOCK TABLES `libro` WRITE;
/*!40000 ALTER TABLE `libro` DISABLE KEYS */;
INSERT INTO `libro` VALUES (2,'Harry Potter y la piedra filosofal','1ra edición',1997,978074753,1,1),(3,'1984','1ra edición',1949,978045,1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'alumno',1),(2,'bibliotecario',1),(3,'profesor',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (10,'Miguel','Mulqui','2003-10-11','44344934'),(20,'asdf','asfasd','2029-11-11','434443434'),(21,'Jonas','Vera','2002-07-12','44224952'),(22,'Salma','Sanchez','2002-10-02','44256056'),(23,'Denis','Gomez','2000-08-09','33333333'),(24,'Rodrigo','Gaona','2002-08-31','22222222'),(25,'Maria','Valdez','1971-09-21','11111111');
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
  `fecha_devolucion` datetime NOT NULL,
  `id_libro` int NOT NULL,
  `id_estado` int NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_prestamo`),
  KEY `id_libro_idx` (`id_libro`),
  KEY `id_estado_idx` (`id_estado`),
  KEY `id_usuario_idx` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestamo`
--

LOCK TABLES `prestamo` WRITE;
/*!40000 ALTER TABLE `prestamo` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_aut_lib`
--

LOCK TABLES `rela_aut_lib` WRITE;
/*!40000 ALTER TABLE `rela_aut_lib` DISABLE KEYS */;
INSERT INTO `rela_aut_lib` VALUES (2,2,5),(3,3,6);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_cat_lib_gen`
--

LOCK TABLES `rela_cat_lib_gen` WRITE;
/*!40000 ALTER TABLE `rela_cat_lib_gen` DISABLE KEYS */;
INSERT INTO `rela_cat_lib_gen` VALUES (2,2,5,5),(3,3,6,6);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rela_edit_lib`
--

LOCK TABLES `rela_edit_lib` WRITE;
/*!40000 ALTER TABLE `rela_edit_lib` DISABLE KEYS */;
INSERT INTO `rela_edit_lib` VALUES (2,2,5),(3,3,6);
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
  KEY `id_estado_idx` (`id_estado`),
  KEY `id_usuario_idx` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (9,'miguelmulqui@hotmail.com','$2y$12$OhR4MySC5aEsVcj6papj0.6Mkg8DsvDfYixQnL4UCufVAok696bzG',NULL,2,10,1),(18,'bbb@hotmail.com','$2y$12$kjdxkX7Xv7OH5hQGEHYsNOHmvsKEHuVmuuKfdWu2u12JGIJiGCZVu',NULL,1,20,0),(19,'jonasalejandrovera123@hotmail.com','$2y$12$pam4oMm145EDrBWcLzSsQeFgNbAMyfIwPmvXhClBYXiVkOzZTQfgW',NULL,3,21,1),(20,'salmasanchez@hotmail.com','$2y$12$rUOtKp2uzSXSJsnex5osWOzN9ijmKj0V2svUGkvgGMT0elH6Qokqu',NULL,1,22,1),(21,'denisgomez@hotmail.com','$2y$12$hHZQfwT3nWpbPrE8zFfAd.7WG2AY.l39.Tm4JzzicQL6S2z1g7nW.',NULL,3,23,1),(22,'rodrigogaona@hotmail.com','$2y$12$pvc0MIqVkw.oc5viAMpE7.wzmdtpubBfDwXu7c.87CSzntv78vFTi',NULL,1,24,0),(23,'mariavaldez@gmail.com','$2y$12$RphA3KsBXO4fp1diArKAuOQ2XW1.zox7DDwQuWYBkVQCuZMXKavIa',NULL,2,25,0);
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
INSERT INTO `usuario_modulos` VALUES (9,1,1),(9,2,1),(9,3,1),(18,1,1),(18,2,1),(18,3,1),(19,1,1),(19,2,1),(19,3,1),(20,1,1),(20,2,1),(20,3,1),(21,1,1),(21,2,1),(21,3,1),(22,1,1),(22,2,1),(22,3,1),(23,1,1),(23,2,1),(23,3,1);
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
    IN p_anio_publicacion YEAR,
    IN p_estado TINYINT(1),
    IN p_autor VARCHAR(50),
    IN p_editorial VARCHAR(100),
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
    IN p_anio_publicacion year,
    IN p_isbn INT,
    IN p_estado TINYINT(1),
    IN p_autor VARCHAR(50),
    IN p_editorial VARCHAR(100),
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

-- Dump completed on 2026-06-08 13:17:48
