/*
Navicat MySQL Data Transfer

Source Server         : [127.0.0.1 - LOCAL]
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-10-31 18:55:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_journey_type_info
-- ----------------------------
DROP TABLE IF EXISTS `mrc_journey_type_info`;
CREATE TABLE `mrc_journey_type_info` (
  `object_id` bigint(20) NOT NULL,
  `navigation` varchar(255) DEFAULT NULL,
  `related_journey_type` bigint(20) DEFAULT NULL,
  `starting_point` varchar(500) DEFAULT NULL,
  `nights` int(11) DEFAULT NULL,
  `destination` varchar(500) DEFAULT NULL,
  `ship` varchar(255) DEFAULT NULL,
  `map_image` bigint(20) DEFAULT NULL,
  `itinerary` text,
  `include` text,
  PRIMARY KEY (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_journey_type_info
-- ----------------------------
INSERT INTO `mrc_journey_type_info` VALUES ('85', null, null, 'Saigon', '7', '25', '686', '965', 'a:2:{i:0;a:3:{s:8:\"day_name\";s:1:\"1\";s:8:\"day_port\";s:2:\"21\";s:11:\"day_content\";s:423:\"Transfer from the InterContinental Asiana Saigon Hotel to the port of My Tho by coach to embark cruise. Please note: Your guide will collect your passports at the meeting point so we can arrange the immigration formalities.\r\n\r\nRegistration Details\r\nINTERCONTINENTAL ASIANA SAIGON HOTEL, Purple Jade Bar (Level One) /\r\n\r\n39 Le Duan, Ben Nghe, Ho Chi Minh City, Vietnam\r\n\r\nTel: (+84) 8 3520 9999\r\n\r\nRegistration is at 10.30am\";}i:1;a:3:{s:8:\"day_name\";s:1:\"2\";s:8:\"day_port\";s:2:\"20\";s:11:\"day_content\";s:318:\"In the morning passengers will visit Cai Be and its colourful floating market. In the afternoon take an exciting Sampan boat excursion to Sa Dec via Vinh Long, along canals and backwaters and see the local market and the ancient house of Mr, Huyn Thuy Le, the ‘lover’ of Marguerite Duras, a famous French novelist.\";}}', 'Cruise Price Includes: Entrance fees, guide services (English language), gratuities to crew, main meals, locally made soft drinks, local beer and local spirits, jugged coffee and selection of teas and tisanes, mineral water. Transfers between the meeting point and the ship at the start and end of a voyage.\r\n\r\nCruise Price Excludes: International flights, port dues (if levied), laundry, all visa costs, fuel surcharges (see terms and conditions), imported beverages such as wines, premium spirits and liqueurs, fancy soft drinks like Perrier, espressos and cappuccinos at bar and tips to tour guides, local guides, bus drivers, boat operators and cyclo drivers.');
INSERT INTO `mrc_journey_type_info` VALUES ('125', null, null, 'Nha Rong', '6', '26', '708', '158', 'a:1:{i:0;a:3:{s:8:\"day_name\";s:1:\"1\";s:8:\"day_port\";s:2:\"20\";s:11:\"day_content\";s:423:\"Transfer from the InterContinental Asiana Saigon Hotel to the port of My Tho by coach to embark cruise. Please note: Your guide will collect your passports at the meeting point so we can arrange the immigration formalities.\r\n\r\nRegistration Details\r\nINTERCONTINENTAL ASIANA SAIGON HOTEL, Purple Jade Bar (Level One) /\r\n\r\n39 Le Duan, Ben Nghe, Ho Chi Minh City, Vietnam\r\n\r\nTel: (+84) 8 3520 9999\r\n\r\nRegistration is at 10.30am\";}}', 'In the morning passengers will visit Cai Be and its colourful floating market. In the afternoon take an exciting Sampan boat excursion to Sa Dec via Vinh Long, along canals and backwaters and see the local market and the ancient house of Mr, Huyn Thuy Le, the ‘lover’ of Marguerite Duras, a famous French novelist.');
INSERT INTO `mrc_journey_type_info` VALUES ('128', null, null, 'Nha Rong', '10', '26', '65', '158', 'a:1:{i:0;a:3:{s:8:\"day_name\";s:1:\"1\";s:8:\"day_port\";s:2:\"21\";s:11:\"day_content\";s:423:\"Transfer from the InterContinental Asiana Saigon Hotel to the port of My Tho by coach to embark cruise. Please note: Your guide will collect your passports at the meeting point so we can arrange the immigration formalities.\r\n\r\nRegistration Details\r\nINTERCONTINENTAL ASIANA SAIGON HOTEL, Purple Jade Bar (Level One) /\r\n\r\n39 Le Duan, Ben Nghe, Ho Chi Minh City, Vietnam\r\n\r\nTel: (+84) 8 3520 9999\r\n\r\nRegistration is at 10.30am\";}}', 'Cruise Price Includes: Entrance fees, guide services (English language), gratuities to crew, main meals, locally made soft drinks, local beer and local spirits, jugged coffee and selection of teas and tisanes, mineral water. Transfers between the meeting point and the ship at the start and end of a voyage.\r\n\r\nCruise Price Excludes: International flights, port dues (if levied), laundry, all visa costs, fuel surcharges (see terms and conditions), imported beverages such as wines, premium spirits and liqueurs, fancy soft drinks like Perrier, espressos and cappuccinos at bar and tips to tour guides, local guides, bus drivers, boat operators and cyclo drivers.');
INSERT INTO `mrc_journey_type_info` VALUES ('895', 'upstream', '128', 'Siem Reap', '7', '25', '686', '965', null, 'Cruise Price Includes: Entrance fees, guide services (English language), gratuities to crew, main meals, locally made soft drinks, local beer and local spirits, jugged coffee and selection of teas and tisanes, mineral water. Transfers between the meeting point and the ship at the start and end of a voyage.\r\n\r\nCruise Price Excludes: International flights, port dues (if levied), laundry, all visa costs, fuel surcharges (see terms and conditions), imported beverages such as wines, premium spirits and liqueurs, fancy soft drinks like Perrier, espressos and cappuccinos at bar and tips to tour guides, local guides, bus drivers, boat operators and cyclo drivers.');
