/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : 19can

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2012-05-07 09:33:38
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `user_login`
-- ----------------------------
DROP TABLE IF EXISTS `user_login`;
CREATE TABLE `user_login` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(12) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(8) NOT NULL DEFAULT '' COMMENT '私钥',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_login
-- ----------------------------
INSERT INTO user_login VALUES ('1', 'admin', '', '');
INSERT INTO user_login VALUES ('2', 'test', 'pwd', 'test1234');
INSERT INTO user_login VALUES ('3', 'test2', 'pwd', 'test1234');
