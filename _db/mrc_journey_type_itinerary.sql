/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2016-09-07 20:37:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_journey_type_itinerary
-- ----------------------------
DROP TABLE IF EXISTS `mrc_journey_type_itinerary`;
CREATE TABLE `mrc_journey_type_itinerary` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `journey_type_id` int(11) DEFAULT NULL,
  `day` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_journey_type_itinerary
-- ----------------------------
INSERT INTO `mrc_journey_type_itinerary` VALUES ('7', '85', '1', '21', 'In the morning passengers will visit Cai Be and its colourful floating market. In the afternoon take an exciting Sampan boat excursion to Sa Dec via Vinh Long, along canals and backwaters and see the local market and the ancient house of Mr, Huyn Thuy Le, the ‘lover’ of Marguerite Duras, a famous French novelist.');
INSERT INTO `mrc_journey_type_itinerary` VALUES ('8', '85', '2', '20', 'In the morning passengers will visit Cai Be and its colourful floating market. In the afternoon take an exciting Sampan boat excursion to Sa Dec via Vinh Long, along canals and backwaters and see the local market and the ancient house of Mr, Huyn Thuy Le, the ‘lover’ of Marguerite Duras, a famous French novelist.');
