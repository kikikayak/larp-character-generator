/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50615
 Source Host           : localhost
 Source Database       : zombiesCG

 Target Server Type    : MySQL
 Target Server Version : 50615
 File Encoding         : utf-8

 Date: 01/31/2014 15:11:27 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `characters`
-- ----------------------------
DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters` (
  `characterID` int(11) NOT NULL AUTO_INCREMENT,
  `playerID` int(11) NOT NULL,
  `charName` varchar(50) DEFAULT NULL,
  `countryID` int(11) DEFAULT NULL,
  `communityID` int(11) DEFAULT NULL,
  `raceID` int(11) DEFAULT NULL,
  `charAge` int(11) DEFAULT NULL,
  `charType` varchar(50) NOT NULL,
  `attribute1` int(11) NOT NULL,
  `attribute2` int(11) NOT NULL,
  `attribute3` int(11) NOT NULL,
  `attribute4` int(11) NOT NULL,
  `attribute5` int(11) NOT NULL,
  `vitality` int(11) NOT NULL,
  `keyRelationships` longtext,
  `notes` longtext,
  `staffNotes` longtext,
  `charDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`characterID`),
  KEY `CharacterID` (`characterID`),
  KEY `CountryID` (`countryID`),
  KEY `PlayerID` (`playerID`),
  KEY `RaceID` (`raceID`),
  KEY `ReligionID` (`communityID`),
  CONSTRAINT `communityID` FOREIGN KEY (`communityID`) REFERENCES `communities` (`communityID`),
  CONSTRAINT `countryID` FOREIGN KEY (`countryID`) REFERENCES `countries` (`countryID`),
  CONSTRAINT `playerID` FOREIGN KEY (`playerID`) REFERENCES `players` (`playerID`),
  CONSTRAINT `raceID` FOREIGN KEY (`raceID`) REFERENCES `races` (`raceID`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `charfeats`
-- ----------------------------
DROP TABLE IF EXISTS `charfeats`;
CREATE TABLE `charfeats` (
  `characterID` int(11) NOT NULL,
  `featID` int(11) NOT NULL,
  PRIMARY KEY (`characterID`,`featID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `charheaders`
-- ----------------------------
DROP TABLE IF EXISTS `charheaders`;
CREATE TABLE `charheaders` (
  `characterID` int(11) NOT NULL DEFAULT '0',
  `headerID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`characterID`,`headerID`),
  KEY `CharacterID` (`characterID`),
  KEY `HeaderID` (`headerID`),
  CONSTRAINT `charheaders_ibfk_1` FOREIGN KEY (`characterID`) REFERENCES `characters` (`characterID`),
  CONSTRAINT `charheaders_ibfk_2` FOREIGN KEY (`headerID`) REFERENCES `headers` (`HeaderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `charskills`
-- ----------------------------
DROP TABLE IF EXISTS `charskills`;
CREATE TABLE `charskills` (
  `characterID` int(11) NOT NULL DEFAULT '0',
  `skillID` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`characterID`,`skillID`),
  KEY `CharID` (`characterID`),
  KEY `SkillID` (`skillID`),
  CONSTRAINT `charskills_ibfk_1` FOREIGN KEY (`characterID`) REFERENCES `characters` (`characterID`),
  CONSTRAINT `charskills_ibfk_2` FOREIGN KEY (`skillID`) REFERENCES `skills` (`SkillID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `charspells`
-- ----------------------------
DROP TABLE IF EXISTS `charspells`;
CREATE TABLE `charspells` (
  `characterID` int(11) NOT NULL DEFAULT '0',
  `spellID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`characterID`,`spellID`),
  KEY `CharID` (`characterID`),
  KEY `SpellID` (`spellID`),
  CONSTRAINT `charspells_ibfk_1` FOREIGN KEY (`characterID`) REFERENCES `characters` (`characterID`),
  CONSTRAINT `charspells_ibfk_2` FOREIGN KEY (`spellID`) REFERENCES `spells` (`SpellID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `chartraits`
-- ----------------------------
DROP TABLE IF EXISTS `chartraits`;
CREATE TABLE `chartraits` (
  `traitID` int(11) NOT NULL DEFAULT '0',
  `characterID` int(11) NOT NULL DEFAULT '0',
  `charTraitDetails` longtext,
  PRIMARY KEY (`traitID`,`characterID`),
  KEY `CharacterID` (`characterID`),
  KEY `TraitID` (`traitID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `communities`
-- ----------------------------
DROP TABLE IF EXISTS `communities`;
CREATE TABLE `communities` (
  `communityID` int(11) NOT NULL AUTO_INCREMENT,
  `communityName` varchar(50) DEFAULT NULL,
  `communityDescription` longtext,
  `communityDefault` tinyint(4) DEFAULT '0',
  `communityDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`communityID`),
  KEY `ReligionID` (`communityID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `countries`
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `countryID` int(11) NOT NULL AUTO_INCREMENT,
  `countryName` varchar(100) DEFAULT NULL,
  `countryDescription` longtext,
  `countryDefault` tinyint(4) DEFAULT '0',
  `countryDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`countryID`),
  KEY `CountryID` (`countryID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `cp`
-- ----------------------------
DROP TABLE IF EXISTS `cp`;
CREATE TABLE `cp` (
  `CPTrackID` int(11) NOT NULL AUTO_INCREMENT,
  `CPType` varchar(50) DEFAULT NULL,
  `characterID` int(11) DEFAULT NULL,
  `playerID` int(11) DEFAULT NULL,
  `numberCP` double DEFAULT NULL,
  `CPCatID` int(11) DEFAULT NULL,
  `CPNote` varchar(255) DEFAULT NULL,
  `CPDateStamp` datetime DEFAULT NULL,
  `staffMember` varchar(50) DEFAULT NULL,
  `CPDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`CPTrackID`),
  KEY `CharacterID` (`characterID`),
  KEY `CPTrackID` (`CPTrackID`),
  KEY `NumberCP` (`numberCP`),
  KEY `PlayerID` (`playerID`),
  KEY `UserID` (`staffMember`)
) ENGINE=InnoDB AUTO_INCREMENT=3212 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `cpcategories`
-- ----------------------------
DROP TABLE IF EXISTS `cpcategories`;
CREATE TABLE `cpcategories` (
  `CPCatID` int(11) NOT NULL AUTO_INCREMENT,
  `CPCatName` varchar(50) DEFAULT NULL,
  `CPCatDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`CPCatID`),
  KEY `CPCatID` (`CPCatID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `feats`
-- ----------------------------
DROP TABLE IF EXISTS `feats`;
CREATE TABLE `feats` (
  `featID` int(11) NOT NULL AUTO_INCREMENT,
  `featName` varchar(50) DEFAULT NULL,
  `featCost` int(11) DEFAULT NULL,
  `featPrereq` varchar(255) DEFAULT NULL,
  `featShortDescription` varchar(255) DEFAULT NULL,
  `featDescription` longtext,
  `featCheatSheetNote` varchar(255) DEFAULT NULL,
  `featDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`featID`),
  KEY `featID` (`featID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `headers`
-- ----------------------------
DROP TABLE IF EXISTS `headers`;
CREATE TABLE `headers` (
  `headerID` int(11) NOT NULL AUTO_INCREMENT,
  `headerName` varchar(50) DEFAULT NULL,
  `headerCost` int(11) DEFAULT NULL,
  `headerAccess` varchar(50) DEFAULT NULL,
  `headerDescription` longtext,
  `headerDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`headerID`),
  KEY `HeaderID` (`headerID`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `hiddenfeatsaccess`
-- ----------------------------
DROP TABLE IF EXISTS `hiddenfeatsaccess`;
CREATE TABLE `hiddenfeatsaccess` (
  `characterID` int(11) NOT NULL,
  `featID` int(11) NOT NULL,
  PRIMARY KEY (`characterID`,`featID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `hiddenheadersaccess`
-- ----------------------------
DROP TABLE IF EXISTS `hiddenheadersaccess`;
CREATE TABLE `hiddenheadersaccess` (
  `headerID` int(11) NOT NULL DEFAULT '0',
  `characterID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`headerID`,`characterID`),
  KEY `CharacterID` (`characterID`),
  KEY `H_SkillID` (`headerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `hiddenskillsaccess`
-- ----------------------------
DROP TABLE IF EXISTS `hiddenskillsaccess`;
CREATE TABLE `hiddenskillsaccess` (
  `skillID` int(11) NOT NULL DEFAULT '0',
  `characterID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`skillID`,`characterID`),
  KEY `CharacterID` (`characterID`),
  KEY `H_SkillID` (`skillID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `hiddenspellsaccess`
-- ----------------------------
DROP TABLE IF EXISTS `hiddenspellsaccess`;
CREATE TABLE `hiddenspellsaccess` (
  `spellID` int(11) DEFAULT NULL,
  `characterID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `log`
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `logID` int(11) NOT NULL AUTO_INCREMENT,
  `logTimestamp` timestamp NULL DEFAULT NULL,
  `logMessage` varchar(255) DEFAULT NULL,
  `playerID` int(11) DEFAULT NULL,
  `characterID` int(11) DEFAULT NULL,
  `logDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`logID`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `players`
-- ----------------------------
DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `playerID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `memberStatus` varchar(10) DEFAULT NULL,
  `membershipPaid` varchar(50) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `tmpPassword` varchar(15) DEFAULT NULL,
  `userRole` varchar(50) DEFAULT NULL,
  `userStatus` varchar(50) DEFAULT NULL,
  `resetRequest` int(11) DEFAULT NULL,
  `playerDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`playerID`),
  KEY `MembershipPaid` (`membershipPaid`),
  KEY `PlayerID` (`playerID`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `races`
-- ----------------------------
DROP TABLE IF EXISTS `races`;
CREATE TABLE `races` (
  `raceID` int(11) NOT NULL AUTO_INCREMENT,
  `raceName` varchar(50) DEFAULT NULL,
  `raceDescription` longtext,
  `racePrereqs` varchar(50) DEFAULT NULL,
  `raceDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`raceID`),
  KEY `RaceID` (`raceID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `settingsID` int(11) NOT NULL AUTO_INCREMENT,
  `baseCP` int(11) DEFAULT NULL,
  `baseAttribute` int(11) DEFAULT NULL,
  `useRaces` varchar(50) DEFAULT NULL,
  `communityLabel` varchar(50) DEFAULT NULL,
  `communityLabelPlural` varchar(50) DEFAULT NULL,
  `attribute1Label` varchar(50) DEFAULT NULL,
  `attribute2Label` varchar(50) DEFAULT NULL,
  `attribute3Label` varchar(50) DEFAULT NULL,
  `attribute4Label` varchar(50) DEFAULT NULL,
  `attribute5Label` varchar(50) DEFAULT NULL,
  `vitalityLabel` varchar(50) DEFAULT NULL,
  `gameYear` int(11) DEFAULT NULL,
  `campaignName` varchar(50) DEFAULT NULL,
  `themeID` int(50) DEFAULT NULL,
  `contactName` varchar(50) DEFAULT NULL,
  `contactEmail` varchar(50) DEFAULT NULL,
  `webmasterName` varchar(50) DEFAULT NULL,
  `webmasterEmail` varchar(50) DEFAULT NULL,
  `paypalEmail` varchar(50) DEFAULT NULL,
  `announcements` longtext,
  `copyrightYear` int(11) DEFAULT NULL,
  `generatorLocation` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`settingsID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `skillattributecosts`
-- ----------------------------
DROP TABLE IF EXISTS `skillattributecosts`;
CREATE TABLE `skillattributecosts` (
  `skillID` int(11) DEFAULT NULL,
  `attributeNum` int(11) unsigned DEFAULT NULL COMMENT 'The number of the attribute (e.g. Air = 1, Earth = 2, etc)',
  `attributeCost` int(11) DEFAULT NULL COMMENT 'The attribute cost to use this skill. '
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `skills`
-- ----------------------------
DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `skillID` int(11) NOT NULL AUTO_INCREMENT,
  `skillName` varchar(50) DEFAULT NULL,
  `skillCost` int(11) DEFAULT NULL,
  `skillAccess` varchar(50) DEFAULT NULL,
  `skillType` varchar(50) DEFAULT NULL,
  `maxQuantity` int(11) DEFAULT NULL,
  `costIncrement` int(11) DEFAULT NULL,
  `shortDescription` varchar(255) DEFAULT NULL,
  `skillDescription` longtext,
  `cheatSheetNote` varchar(255) DEFAULT NULL,
  `skillDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`skillID`),
  KEY `SkillID` (`skillID`)
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `skillsheaders`
-- ----------------------------
DROP TABLE IF EXISTS `skillsheaders`;
CREATE TABLE `skillsheaders` (
  `skillID` int(11) NOT NULL DEFAULT '0',
  `headerID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`skillID`,`headerID`),
  KEY `HeaderID` (`headerID`),
  KEY `SkillID` (`skillID`),
  CONSTRAINT `skillsheaders_ibfk_1` FOREIGN KEY (`headerID`) REFERENCES `headers` (`headerID`),
  CONSTRAINT `skillsheaders_ibfk_2` FOREIGN KEY (`skillID`) REFERENCES `skills` (`skillID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `spellattributecosts`
-- ----------------------------
DROP TABLE IF EXISTS `spellattributecosts`;
CREATE TABLE `spellattributecosts` (
  `spellID` int(11) DEFAULT NULL,
  `attributeNum` int(11) DEFAULT NULL,
  `attributeCost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `spells`
-- ----------------------------
DROP TABLE IF EXISTS `spells`;
CREATE TABLE `spells` (
  `spellID` int(11) NOT NULL AUTO_INCREMENT,
  `spellName` varchar(50) DEFAULT NULL,
  `spellCost` int(11) DEFAULT NULL,
  `spellAccess` varchar(50) DEFAULT NULL,
  `spellShortDescription` varchar(255) DEFAULT NULL,
  `spellDescription` longtext,
  `spellCheatSheetNote` varchar(255) DEFAULT NULL,
  `spellDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`spellID`),
  KEY `SpellID` (`spellID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `spellskills`
-- ----------------------------
DROP TABLE IF EXISTS `spellskills`;
CREATE TABLE `spellskills` (
  `skillID` int(11) NOT NULL DEFAULT '0',
  `spellID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`spellID`,`skillID`),
  KEY `spellID` (`spellID`),
  KEY `skillID` (`skillID`),
  CONSTRAINT `skillID` FOREIGN KEY (`skillID`) REFERENCES `skills` (`skillID`),
  CONSTRAINT `spellID` FOREIGN KEY (`spellID`) REFERENCES `spells` (`spellID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table to associate spells with their skill prerequisites. ';

-- ----------------------------
--  Table structure for `themes`
-- ----------------------------
DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `themeID` int(11) NOT NULL AUTO_INCREMENT,
  `themeName` varchar(20) DEFAULT NULL,
  `pageBG` varchar(7) DEFAULT NULL,
  `highlightColor` varchar(7) DEFAULT NULL,
  `adminPageBG` varchar(50) DEFAULT NULL,
  `adminHighlightColor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`themeID`),
  KEY `ThemeID` (`themeID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `traits`
-- ----------------------------
DROP TABLE IF EXISTS `traits`;
CREATE TABLE `traits` (
  `traitID` int(11) NOT NULL AUTO_INCREMENT,
  `traitName` varchar(50) DEFAULT NULL,
  `traitStaff` varchar(50) DEFAULT NULL,
  `traitAccess` varchar(50) DEFAULT NULL,
  `traitDescriptionStaff` longtext,
  `traitDescriptionPublic` longtext,
  `traitDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`traitID`),
  KEY `TraitID` (`traitID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userID` varchar(50) NOT NULL,
  `playerID` int(11) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `permissions` varchar(50) DEFAULT NULL,
  `resetRequest` int(11) DEFAULT NULL,
  `userDeleted` datetime DEFAULT NULL,
  PRIMARY KEY (`userID`),
  KEY `PlayerID` (`playerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- **************************************
-- Set up base data
-- **************************************

-- ----------------------------
--  Records of `communities`
-- ----------------------------
BEGIN;
INSERT INTO `communities` VALUES ('1', 'None', null, '1', null);
COMMIT;

-- ----------------------------
--  Records of `countries`
-- ----------------------------
BEGIN;
INSERT INTO `countries` VALUES ('1', 'None', '', '0', null);
COMMIT;

BEGIN;
INSERT INTO `cpcategories` 
VALUES 
      ('1', 'Event', null), 
      ('2', 'Event Pre-Reg', null), 
      ('3', 'Setup', null), 
      ('4', 'Cleanup', null), 
      ('5', 'Character History', null), 
      ('6', 'PEL', null), 
      ('7', 'Prop donation', null), 
      ('8', 'Transportation', null), 
      ('9', 'Promotion', null), 
      ('10', 'Other', null), 
      ('11', 'Unspecified', null), 
      ('13', 'Staffing', null), 
      ('14', 'Base CP', null), 
      ('15', 'Floating CP transfer', null), 
      ('16', 'PC Questionnaire', null), 
      ('17', 'NPC Referral', null), 
      ('18', 'Kitchen Help', null);
COMMIT;

-- ----------------------------
--  Records of `headers`
-- ----------------------------
BEGIN;
INSERT INTO `headers` VALUES ('19', 'Open Skills', '0', 'Public', null, null);
COMMIT;

-- ----------------------------
--  Records of `players`
-- ----------------------------
BEGIN;
INSERT INTO `players` VALUES ('1', 'System', 'Administrator', 'admin@larpcharactergenerator.com', null, null, '308a039677e0ac0564426fd56b8362fc0a6076ffbdbc14c96', null, 'Admin', 'active', null, null);
COMMIT;

-- ----------------------------
--  Records of `races`
-- ----------------------------
BEGIN;
INSERT INTO `races` VALUES ('1', 'Human', null, null, null);
COMMIT;

-- ----------------------------
--  Records of `settings`
-- ----------------------------
BEGIN;
INSERT INTO `settings` VALUES ('1', '30', '2', 'No', 'Community', 'Communities', 'Air', 'Earth', 'Fire', 'Water', 'Void', 'Vitality', '2008', 'Zombies', '3', 'Director', 'test@test.com', 'Webmaster', 'test@test.com', 'test@test.com', '', '2014', 'http://www.larpcharactergenerator.com/gameName');
COMMIT;

-- ----------------------------
--  Records of `themes`
-- ----------------------------
BEGIN;
INSERT INTO `themes` VALUES ('1', 'Classic', '#006633', '#669966', '#333366', '#666699');
COMMIT;

-- ----------------------------
--  Records of `traits`
-- ----------------------------
BEGIN;
INSERT INTO `traits` VALUES ('1', 'Human', 'System', 'Public', '', '', null);
COMMIT;


SET FOREIGN_KEY_CHECKS = 1;
