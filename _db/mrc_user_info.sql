/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2016-09-12 12:43:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_user_info
-- ----------------------------
DROP TABLE IF EXISTS `mrc_user_info`;
CREATE TABLE `mrc_user_info` (
  `user_id` bigint(20) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `birthday` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `passport_id` varchar(255) DEFAULT NULL,
  `valid_until` varchar(255) DEFAULT NULL,
  `date_of_issue` date DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_user_info
-- ----------------------------
INSERT INTO `mrc_user_info` VALUES ('2', null, 'vn', null, 'male', '2016-09-08', '12 tôn đản, quận 4, Tp.HCM', null, null, null, null, '2016-09-12 05:40:58');
