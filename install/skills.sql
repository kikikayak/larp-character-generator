/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50615
 Source Host           : localhost
 Source Database       : charGen

 Target Server Type    : MySQL
 Target Server Version : 50615
 File Encoding         : utf-8

 Date: 02/17/2014 09:52:59 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `skills`
-- ----------------------------
DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `skillID` int(11) NOT NULL AUTO_INCREMENT,
  `skillName` varchar(50) DEFAULT NULL,
  `skillCost` int(11) DEFAULT NULL,
  `skillAccess` varchar(50) DEFAULT NULL,
  `skillType` varchar(255) DEFAULT NULL,
  `maxQuantity` int(11) DEFAULT NULL,
  `costIncrement` int(11) DEFAULT NULL,
  `shortDescription` varchar(255) DEFAULT NULL,
  `skillDescription` longtext,
  `cheatSheetNote` varchar(255) DEFAULT NULL,
  `skillDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`skillID`),
  KEY `SkillID` (`skillID`)
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
