/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2016-10-11 16:10:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_guest_addon
-- ----------------------------
DROP TABLE IF EXISTS `mrc_guest_addon`;
CREATE TABLE `mrc_guest_addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guest_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `addon_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_guest_addon
-- ----------------------------
INSERT INTO `mrc_guest_addon` VALUES ('5', '6', '429', '105');
INSERT INTO `mrc_guest_addon` VALUES ('7', '8', '459', '0');
INSERT INTO `mrc_guest_addon` VALUES ('8', '9', '459', '0');
INSERT INTO `mrc_guest_addon` VALUES ('9', '10', '459', '0');
