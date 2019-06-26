/*
Navicat MySQL Data Transfer

Source Server         : 本地开发
Source Server Version : 50554
Source Host           : 192.168.102.196:3306
Source Database       : pgis

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2019-06-26 12:01:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wz_area
-- ----------------------------
DROP TABLE IF EXISTS `wz_area`;
CREATE TABLE `wz_area` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地区名',
  `lnglat` varchar(255) DEFAULT NULL COMMENT '经纬度',
  `sorting` int(11) unsigned DEFAULT '0' COMMENT '排序：从大到小',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态：1启用，2禁用',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '注册时间',
  `last_time` int(11) unsigned DEFAULT '0' COMMENT '最后修改时间',
  `gltf_file` varchar(255) DEFAULT NULL COMMENT 'Gltf文件',
  `gltf_scale` double(10,2) DEFAULT '1.00' COMMENT '模型缩放倍数',
  `gltf_height` double(10,2) DEFAULT '0.00' COMMENT '模型高度',
  `gltf_scene` int(255) DEFAULT '0' COMMENT '模型场景序号',
  `gltf_lnglat` varchar(255) DEFAULT NULL COMMENT '模型位置/座标',
  `gltf_rotateX` int(11) DEFAULT '0' COMMENT '模型 rotateX',
  `gltf_rotateY` int(11) DEFAULT '0' COMMENT '模型 rotateY',
  `gltf_rotateZ` int(11) DEFAULT '0' COMMENT '模型 rotateZ',
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='区域表';

-- ----------------------------
-- Records of wz_area
-- ----------------------------
INSERT INTO `wz_area` VALUES ('3', '海南特勤局', '110.369502,20.02657', '0', '1', '1561520170', '1561521553', 'upload/gltf/201906/26/3735d12e82a95c7a7.gltf', '0.20', '0.00', '0', '110.369502,20.02657', '90', '0', '-90');
INSERT INTO `wz_area` VALUES ('4', '海口喜来登酒店', '110.215062,20.052018', '0', '1', '1561520437', '1561520437', null, '1.00', '0.00', '0', '110.369502,20.02657', '0', '0', '0');

-- ----------------------------
-- Table structure for wz_purviewgroup
-- ----------------------------
DROP TABLE IF EXISTS `wz_purviewgroup`;
CREATE TABLE `wz_purviewgroup` (
  `purviewgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '权限组名称',
  `sorting` int(11) unsigned DEFAULT '0' COMMENT '排序',
  `purview` text COMMENT '权限ID字符串',
  `remark` varchar(255) DEFAULT NULL COMMENT '权限组备注',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1启用，2禁用',
  `add_time` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `last_time` int(10) unsigned DEFAULT '0' COMMENT '最后修改时间',
  PRIMARY KEY (`purviewgroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='权限组';

-- ----------------------------
-- Records of wz_purviewgroup
-- ----------------------------
INSERT INTO `wz_purviewgroup` VALUES ('1', '电商办人员组', '99', '30|31|100|130|67|140|70|73|72|94|103|138|104|139|18|20|119|120|121|102', ' ', '1', '1555758193', '1560926216');
INSERT INTO `wz_purviewgroup` VALUES ('2', '村淘店人员组', '50', '6|30|31|100|130|131|9|10|11|12|13|15|76|75|14|91|18|20|119|120|121', ' ', '1', '1555761233', '1560846636');
INSERT INTO `wz_purviewgroup` VALUES ('3', '乡镇人员组', '80', '9|13|60|61|62|129|64|126|92|78|82|83|84|85|86|87|99|141|93|32|33|67|69|73|72|94|103|104|95|18|20|119|120|121|47', null, '1', '1555761519', '1560840211');
INSERT INTO `wz_purviewgroup` VALUES ('4', '村委打分组', '70', '60|64|78|87|88|99|125|32|33|94|103|104|95|101|96|18|20|119|120|121', '只负责打分，没权限提交评分', '1', '1555763006', '1560840182');
INSERT INTO `wz_purviewgroup` VALUES ('5', '物价局人员组', '90', '9|10|13|67|68|73|72|124|18|20|119|120|121|102', null, '1', '1555763116', '1560840207');
INSERT INTO `wz_purviewgroup` VALUES ('6', '财政局人员组', '95', '30|31|100|130|32|33|18|20|119|120|121|102', null, '1', '1555763124', '1560840203');
INSERT INTO `wz_purviewgroup` VALUES ('7', '平台管理员', '100', '6|30|31|100|130|9|10|11|12|13|15|76|75|14|91|60|61|62|129|64|126|92|78|79|90|89|80|81|82|83|84|85|86|87|99|141|93|32|33|67|68|69|140|70|73|72|124|94|103|138|104|139|95|96|18|19|20|21|22|119|120|121|122|27|28|29|105|127|128|47|49|50|52|48|51|1|2|3|4|5|55|56|58|57|23|24|25|26|39|40|43|41|44|42|35|36|37|38|132|133|134|135|136|102|46', '拥有所有权限', '1', '1555924158', '1560397665');
INSERT INTO `wz_purviewgroup` VALUES ('8', '村委提交组', '75', '60|64|78|87|99|93|94|103|104|95|101|96|18|20|119|120|121', '只负责提交，没权限打分', '1', '1556273682', '1560840187');
INSERT INTO `wz_purviewgroup` VALUES ('9', '市场监督局', '5', '9|13|18|20|119|120|121|102', null, '1', '1557738883', '1560397205');
INSERT INTO `wz_purviewgroup` VALUES ('10', '扶贫办人员组', '10', '94|103|104|18|20|119|120|121|102', null, '1', '1557795962', '1560397187');
INSERT INTO `wz_purviewgroup` VALUES ('11', '村委打分组加村淘店人员组', '4', '6|30|31|100|130|131|9|10|11|12|13|15|76|75|14|91|60|64|78|87|88|99|125|32|33|94|103|104|18|20|119|120|121', '村委打分员村淘店人员双重身份', '1', '1558677774', '1560927711');
INSERT INTO `wz_purviewgroup` VALUES ('12', '村委打分组加村委提交组', '3', '60|64|78|87|88|99|125|93|32|33|94|103|104|95|101|96|18|20|119|120|121', '村委打分组和村委提交组双重身份', '1', '1559611537', '1560840163');

-- ----------------------------
-- Table structure for wz_setting
-- ----------------------------
DROP TABLE IF EXISTS `wz_setting`;
CREATE TABLE `wz_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `webName` varchar(255) DEFAULT NULL COMMENT '网站名称',
  `webSize` varchar(255) DEFAULT NULL COMMENT '网站网址',
  `webInfo` varchar(255) DEFAULT NULL COMMENT '简述',
  `webEmail` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `webTel` varchar(255) DEFAULT NULL COMMENT '电话',
  `webAddr` varchar(255) DEFAULT NULL COMMENT '地址',
  `webAbout` text COMMENT '简介',
  `webIcp` varchar(255) DEFAULT NULL COMMENT 'icp备案号',
  `keywords` varchar(255) DEFAULT NULL COMMENT '网站关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '网站描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='系统配置表';

-- ----------------------------
-- Records of wz_setting
-- ----------------------------
INSERT INTO `wz_setting` VALUES ('1', '华盾空间数据可视化平台', 'www.pgis.com', '华盾空间数据可视化平台', 'simon@jianzu.info', '400-662-3920', '海口市龙华区滨海大道复兴城D3区1层', '<p>本项目旨在建设惠农超市信息化平台，积极创新扶贫扶志模式，打造“脱贫攻坚惠农超市”，通过信息化系统的搭建，智能化物联网的应用，构建全链扶贫工作信息化应用体系，实现移动化、智能化的深度扶贫惠农。通过政府主导、社会捐助、农户参与的方式，按照县为单位、规模控制、分级管理、精准识别的原则，对贫困户和普通村民建档立卡，村民通过社会劳动换积分，即可凭村民名下的积分在惠农超市内进行商品兑换。本方案对信息化平台搭建、惠农超市申报/入库/出售/核算的智能化应用及配套终端、村民建档/积分管理/实名消费、政府科学管理平台等方面做出详细可行性方案阐述，以帮助惠农超市实现低成本快速布点、村民物资获取更便捷、扶贫惠农工作更精准、更阳光，推动扶贫工作由粗放到集约、由漫灌到滴灌的实质性转变，切实做到扶真贫、真扶贫。</p>', '沪ICP备16027162号', '华盾空间数据可视化平台', '华盾空间数据可视化平台');

-- ----------------------------
-- Table structure for wz_user
-- ----------------------------
DROP TABLE IF EXISTS `wz_user`;
CREATE TABLE `wz_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号，必须唯一',
  `password` varchar(255) DEFAULT '' COMMENT '用户密码',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态：1启用，2禁用，3删除',
  `realName` varchar(100) DEFAULT NULL COMMENT '真实姓名',
  `purviewgroup_id` int(11) unsigned DEFAULT '0' COMMENT '所属权限组ID',
  `last_login_time` int(11) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '注册时间',
  `last_time` int(11) unsigned DEFAULT '0' COMMENT '最后修改日期',
  `pics` text COMMENT '相片，多个用,分隔。  (需要配合村民字段is_villager=2使用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- ----------------------------
-- Records of wz_user
-- ----------------------------
INSERT INTO `wz_user` VALUES ('1', '13698967443', '', '1', 'Simon', '7', '1561347203', '1555830531', '1561347287', '', '');
