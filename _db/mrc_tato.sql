/*
Navicat MySQL Data Transfer

Source Server         : [LOCAL]
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : a_mrc

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-10-02 21:48:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mrc_tato
-- ----------------------------
DROP TABLE IF EXISTS `mrc_tato`;
CREATE TABLE `mrc_tato` (
  `object_id` bigint(20) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `country` varchar(11) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `deposit_rate` float DEFAULT NULL,
  `commission_rate` float DEFAULT NULL,
  `invoice_information` text,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `nick_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company_telephone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mrc_tato
-- ----------------------------
INSERT INTO `mrc_tato` VALUES ('596', 'Ringier 2', 'RVN', 'vn', 'vietanh@ringier.com.vn', 'www.vietanh.photography', '+84966948879', '+84966948879', '20', '10', 'invoice info', 'Anh', 'Tran', 'Viet', 'katori', 'Senior', '+84966948879', '+84966948879', 'vietanhtran.it@gmail.com', null);
