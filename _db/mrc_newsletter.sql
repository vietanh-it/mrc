/*
Navicat MySQL Data Transfer

Source Server         : [127.0.0.1 - LOCAL]
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-09-15 11:18:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_newsletter
-- ----------------------------
DROP TABLE IF EXISTS `mrc_newsletter`;
CREATE TABLE `mrc_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_newsletter
-- ----------------------------
