/*
Navicat MySQL Data Transfer

Source Server         : cnxlocal
Source Server Version : 50723
Source Host           : localhost:3306
Source Database       : procesos

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2018-10-15 19:14:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for procesos
-- ----------------------------
DROP TABLE IF EXISTS `procesos`;
CREATE TABLE `procesos` (
  `id_proceso` int(15) NOT NULL AUTO_INCREMENT,
  `nro_proceso` varchar(8) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `sede` varchar(255) DEFAULT NULL,
  `presupuesto` double(200,2) DEFAULT NULL,
  `id_usuario` int(15) DEFAULT NULL,
  PRIMARY KEY (`id_proceso`),
  KEY `id_usuario` (`id_usuario`) USING BTREE,
  CONSTRAINT `procesos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of procesos
-- ----------------------------
INSERT INTO `procesos` VALUES ('10', '00000010', 'Proceso nuevo', '2018-10-15', 'Bogotá DC.', '34500.24', null);
INSERT INTO `procesos` VALUES ('11', '00000011', 'Nevo proceso con usuario', '2018-10-15', 'Bogotá DC.', '234.34', '1');
INSERT INTO `procesos` VALUES ('12', '00000001', 'Nuevo proceso usuario 2', '2018-10-15', 'Bogotá DC.', '34500.34', '7');
INSERT INTO `procesos` VALUES ('13', '00000002', 'Otro proeso user 2', '2018-10-15', 'Mexico', '25600.23', '7');

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int(15) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(255) NOT NULL,
  `identificacion` double(40,0) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `activo` int(1) DEFAULT '1' COMMENT '1= Activo, 0=Inactivo',
  `auth` int(1) DEFAULT '0' COMMENT '1=Autenticado, 0= No Autenticado',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES ('1', 'Julio Barrera', '12345', '098fe02038d5a38710065364fecc95da', '1', '0');
INSERT INTO `usuarios` VALUES ('7', 'David Alexander Vivas Botina', '54321', '098fe02038d5a38710065364fecc95da', '1', '0');
