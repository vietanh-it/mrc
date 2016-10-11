/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50620
Source Host           : localhost:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50620
File Encoding         : 65001

Date: 2016-10-11 16:10:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_guests
-- ----------------------------
DROP TABLE IF EXISTS `mrc_guests`;
CREATE TABLE `mrc_guests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `passport_no` varchar(255) DEFAULT NULL,
  `passport_issue_date` date DEFAULT NULL,
  `passport_expiration_date` date DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `country_of_birth` varchar(255) DEFAULT NULL,
  `issued_in` varchar(255) DEFAULT NULL,
  `is_visa` varchar(255) DEFAULT NULL,
  `travel_insurance` varchar(255) DEFAULT NULL,
  `embarkation_date` date DEFAULT NULL,
  `embarkation_city` varchar(255) DEFAULT NULL,
  `last_inbound_flight_no` int(11) DEFAULT NULL,
  `last_inbound_originating_airport` varchar(255) DEFAULT NULL,
  `last_inbound_date` date DEFAULT NULL,
  `last_inbound_arrival_time` varchar(255) DEFAULT NULL,
  `debarkation_date` date DEFAULT NULL,
  `debarkation_city` varchar(255) DEFAULT NULL,
  `occasion_note` text,
  `speacial_note` text,
  `room_no` int(11) DEFAULT NULL,
  `diet_note` text,
  `medical_note` text,
  `speacial_assistant_note` text,
  `bedding_note` varchar(255) DEFAULT NULL,
  `first_outbound_date` date DEFAULT NULL,
  `first_outbound_flight_no` varchar(255) DEFAULT NULL,
  `first_outbound_departure_time` varchar(255) DEFAULT NULL,
  `first_outbound_destination_airport` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_guests
-- ----------------------------
INSERT INTO `mrc_guests` VALUES ('6', null, '429', '2', '', '', '', '', 'male', '1970-01-01', '', '', '1970-01-01', '1970-01-01', '', '', '', 'no', 'no', '1970-01-01', '', '0', '', '1970-01-01', '', '1970-01-01', 'Ho Chi Minh City', '', null, '5', '', '', '', 'queen', '1970-01-01', '', '', '', '2016-10-08 10:57:00');
INSERT INTO `mrc_guests` VALUES ('8', null, '459', '2', '', '', '', '', 'male', '1970-01-01', '', '', '1970-01-01', '1970-01-01', '', '', '', 'no', 'no', '1970-01-01', '', '0', '', '1970-01-01', '', '1970-01-01', '', '', null, '0', '', '', '', '', '1970-01-01', '', '', '', '2016-10-11 09:04:35');
INSERT INTO `mrc_guests` VALUES ('9', null, '459', '2', '', '', '', '', 'male', '1970-01-01', '', '', '1970-01-01', '1970-01-01', '', '', '', 'no', 'no', '1970-01-01', '', '0', '', '1970-01-01', '', '1970-01-01', '', '', null, '0', '', '', '', '', '1970-01-01', '', '', '', '2016-10-11 09:04:35');
INSERT INTO `mrc_guests` VALUES ('10', null, '459', '2', '', '', '', '', 'male', '1970-01-01', '', '', '1970-01-01', '1970-01-01', '', '', '', 'no', 'no', '1970-01-01', '', '0', '', '1970-01-01', '', '1970-01-01', '', '', null, '0', '', '', '', '', '1970-01-01', '', '', '', '2016-10-11 09:04:36');
