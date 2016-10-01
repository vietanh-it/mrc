/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2016-10-01 12:08:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_user_refer
-- ----------------------------
DROP TABLE IF EXISTS `mrc_user_refer`;
CREATE TABLE `mrc_user_refer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `email_refer` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_user_refer
-- ----------------------------
INSERT INTO `mrc_user_refer` VALUES ('4', '2', 'sydao@ringier.com.vn', '6a42542086c32e6e04692fed3460783e_2', 'publish', '2016-10-01 04:06:16', null);
INSERT INTO `mrc_user_refer` VALUES ('5', '2', 'sydao@ringier2.com.vn', '6a42542086c32e6e04692fed3460783e_2', 'pending', '2016-10-18 11:50:44', null);
