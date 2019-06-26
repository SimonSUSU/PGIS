/*
Navicat MySQL Data Transfer

Source Server         : 本地开发
Source Server Version : 50554
Source Host           : 192.168.102.196:3306
Source Database       : pgis

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2019-06-26 16:37:19
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
INSERT INTO `wz_area` VALUES ('3', '海南特勤局', '110.369502,20.02657', '0', '1', '1561520170', '1561533619', 'upload/gltf/201906/26/4495d131c8b9c8b97.gltf', '0.18', '0.00', '0', '110.369456,20.026583', '90', '0', '-89');
INSERT INTO `wz_area` VALUES ('4', '海口喜来登酒店', '110.215062,20.052018', '0', '1', '1561520437', '1561520437', null, '1.00', '0.00', '0', '110.369502,20.02657', '0', '0', '0');

-- ----------------------------
-- Table structure for wz_purview
-- ----------------------------
DROP TABLE IF EXISTS `wz_purview`;
CREATE TABLE `wz_purview` (
  `purview_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '路径',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '父ID',
  `sorting` int(11) unsigned DEFAULT '0' COMMENT '排序：从大到小',
  `is_system` tinyint(1) unsigned DEFAULT '2' COMMENT '1系统级，2不是系统级',
  `status` tinyint(1) unsigned DEFAULT '2' COMMENT '1继承上级, 2不继承',
  PRIMARY KEY (`purview_id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COMMENT='权限节点';

-- ----------------------------
-- Records of wz_purview
-- ----------------------------
INSERT INTO `wz_purview` VALUES ('1', '区域管理', 'area/index/', '0', '52', '2', '2');
INSERT INTO `wz_purview` VALUES ('2', '区域添加', 'area/add/', '1', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('3', '区域修改', 'area/edit/', '1', '9', '2', '2');
INSERT INTO `wz_purview` VALUES ('4', '区域删除', 'area/del/', '1', '6', '2', '2');
INSERT INTO `wz_purview` VALUES ('5', '区域工作人员管理', 'area/manager/', '1', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('6', '兑换收银', 'change/index/', '0', '97', '2', '2');
INSERT INTO `wz_purview` VALUES ('7', '办公桌面', 'desktop/index/', '0', '200', '1', '2');
INSERT INTO `wz_purview` VALUES ('8', '个人档案修改', 'desktop/my/', '46', '0', '1', '2');
INSERT INTO `wz_purview` VALUES ('9', '商品管理', 'goods/index/', '0', '90', '2', '2');
INSERT INTO `wz_purview` VALUES ('10', '采购申请管理', 'goods/apply/', '9', '20', '2', '2');
INSERT INTO `wz_purview` VALUES ('11', '采购申请', 'goods/add/', '9', '18', '2', '2');
INSERT INTO `wz_purview` VALUES ('12', '商品修改', 'goods/edit/', '9', '16', '2', '2');
INSERT INTO `wz_purview` VALUES ('13', '商品查看', 'goods/view/', '9', '15', '2', '2');
INSERT INTO `wz_purview` VALUES ('14', '商品删除', 'goods/del/', '9', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('15', '申请重采', 'goods/reapply/', '9', '14', '2', '2');
INSERT INTO `wz_purview` VALUES ('16', '删除商品图片', 'goods/delPic/', '12', '0', '2', '1');
INSERT INTO `wz_purview` VALUES ('17', '设置商品封面图', 'goods/setCover/', '12', '0', '2', '1');
INSERT INTO `wz_purview` VALUES ('18', '帮助文档', 'help/index/', '0', '74', '2', '2');
INSERT INTO `wz_purview` VALUES ('19', '帮助添加', 'help/add/', '18', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('20', '帮助查看', 'help/view/', '18', '9', '2', '2');
INSERT INTO `wz_purview` VALUES ('21', '帮助修改', 'help/edit/', '18', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('22', '帮助删除', 'help/del/', '18', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('23', '推荐管理', 'hot/index/', '0', '46', '2', '2');
INSERT INTO `wz_purview` VALUES ('24', '推荐添加', 'hot/add/', '23', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('25', '推荐修改', 'hot/edit/', '23', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('26', '推荐删除', 'hot/del/', '23', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('27', '日志系统', 'log/index/', '0', '69', '2', '2');
INSERT INTO `wz_purview` VALUES ('28', '系统登录日志', 'log/login/', '27', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('29', '验证码发送日志', 'log/qcode/', '27', '9', '2', '2');
INSERT INTO `wz_purview` VALUES ('30', '结算系统', 'order/index/', '0', '95', '2', '2');
INSERT INTO `wz_purview` VALUES ('31', '兑换单详情', 'order/view/', '30', '94', '2', '2');
INSERT INTO `wz_purview` VALUES ('32', '积分明细', 'points/index/', '0', '84', '2', '2');
INSERT INTO `wz_purview` VALUES ('33', '积分明细导出', 'points/do_excel/', '32', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('34', '积分导入', 'points/import/', '32', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('35', '权限节点管理', 'purview/index/', '0', '42', '2', '2');
INSERT INTO `wz_purview` VALUES ('36', '权限节点添加', 'purview/add/', '35', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('37', '权限节点修改', 'purview/edit/', '35', '8', '2', '2');
INSERT INTO `wz_purview` VALUES ('38', '权限节点删除', 'purview/del/', '35', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('39', '权限组管理', 'purviewgroup/index/', '0', '44', '2', '2');
INSERT INTO `wz_purview` VALUES ('40', '权限组添加', 'purviewgroup/add/', '39', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('41', '权限组修改', 'purviewgroup/edit/', '39', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('42', '权限组删除', 'purviewgroup/del/', '39', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('43', '权限配置', 'purviewgroup/purview/', '39', '9', '2', '2');
INSERT INTO `wz_purview` VALUES ('44', '权限组状态设置', 'purviewgroup/setStatus/', '39', '4', '2', '2');
INSERT INTO `wz_purview` VALUES ('45', '所有权限组数据显示', 'purviewgroup/all/', '39', '1', '1', '2');
INSERT INTO `wz_purview` VALUES ('46', '系统设置', 'setting/index/', '0', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('47', '超市管理', 'store/index/', '0', '55', '2', '2');
INSERT INTO `wz_purview` VALUES ('48', '超市状态设置', 'store/set_status/', '47', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('49', '超市添加', 'store/add/', '47', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('50', '超市修改', 'store/edit/', '47', '8', '2', '2');
INSERT INTO `wz_purview` VALUES ('51', '超市删除', 'store/del/', '47', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('52', '超市工作人员管理', 'store/manager/', '47', '7', '2', '2');
INSERT INTO `wz_purview` VALUES ('53', '所有超市数据显示', 'store/all/', '47', '1', '1', '2');
INSERT INTO `wz_purview` VALUES ('54', '超市查看', 'store/view/', '47', '3', '1', '2');
INSERT INTO `wz_purview` VALUES ('55', '用户管理', 'user/index/', '0', '49', '2', '2');
INSERT INTO `wz_purview` VALUES ('56', '用户添加', 'user/add/', '55', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('57', '用户修改', 'user/edit/', '55', '7', '2', '2');
INSERT INTO `wz_purview` VALUES ('58', '用户属性设置', 'user/set_field/', '55', '8', '2', '2');
INSERT INTO `wz_purview` VALUES ('59', '所有用户数据显示', 'user/all/', '55', '0', '1', '2');
INSERT INTO `wz_purview` VALUES ('60', '帮扶采集', 'villager/index/', '0', '88', '2', '2');
INSERT INTO `wz_purview` VALUES ('61', '村民添加', 'villager/add/', '60', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('62', '村民修改', 'villager/edit/', '60', '8', '2', '2');
INSERT INTO `wz_purview` VALUES ('63', '村民相片删除', 'villager/delPic/', '62', '0', '2', '1');
INSERT INTO `wz_purview` VALUES ('64', '村民积分明细', 'villager/points/', '60', '6', '2', '2');
INSERT INTO `wz_purview` VALUES ('65', '手工录入村民积分', 'villager/points_add/', '60', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('66', '村民档案查看', 'villager/view/', '60', '9', '1', '2');
INSERT INTO `wz_purview` VALUES ('67', '审批系统', 'workflow/index/', '0', '80', '2', '2');
INSERT INTO `wz_purview` VALUES ('68', '商品采购审批', 'workflow/goods/', '67', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('69', '问卷评分审批', 'workflow/assess/', '67', '7', '2', '2');
INSERT INTO `wz_purview` VALUES ('70', '兑换结算审批', 'workflow/balance/', '67', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('72', '审批详情', 'workflow/view/', '67', '3', '2', '2');
INSERT INTO `wz_purview` VALUES ('73', '执行审批', 'workflow/review/', '67', '4', '2', '2');
INSERT INTO `wz_purview` VALUES ('75', '设置商品上下架状态', 'goods/set_status/', '9', '11', '2', '2');
INSERT INTO `wz_purview` VALUES ('76', '商品提交审核', 'goods/review/', '9', '12', '2', '2');
INSERT INTO `wz_purview` VALUES ('78', '评分系统', 'assess/index/', '0', '87', '2', '2');
INSERT INTO `wz_purview` VALUES ('79', '添加主卷', 'assess/add/', '78', '15', '2', '2');
INSERT INTO `wz_purview` VALUES ('80', '删除主卷', 'assess/del/', '78', '14', '2', '2');
INSERT INTO `wz_purview` VALUES ('81', '主卷配置列表', 'assess/config/', '78', '12', '2', '2');
INSERT INTO `wz_purview` VALUES ('82', '主卷配置详细', 'assess/config_edit/', '78', '11', '2', '2');
INSERT INTO `wz_purview` VALUES ('83', '添加具体指标', 'assess/item_add/', '78', '9', '2', '2');
INSERT INTO `wz_purview` VALUES ('84', '修改具体指标', 'assess/item_edit/', '78', '8', '2', '2');
INSERT INTO `wz_purview` VALUES ('85', '删除具体指标', 'assess/item_del/', '78', '7', '2', '2');
INSERT INTO `wz_purview` VALUES ('86', '主卷整体预览', 'assess/view/', '78', '6', '2', '2');
INSERT INTO `wz_purview` VALUES ('87', '问卷作答列表', 'assess/user/', '78', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('88', '问卷作答表单', 'assess/write/', '78', '4', '2', '2');
INSERT INTO `wz_purview` VALUES ('89', '修改主卷', 'assess/edit/', '78', '14', '2', '2');
INSERT INTO `wz_purview` VALUES ('90', '发布主卷', 'assess/set_status/', '78', '14', '2', '2');
INSERT INTO `wz_purview` VALUES ('91', '审批日志', 'goods/workflowlog/', '9', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('92', '批量导入', 'villager/import/', '60', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('93', '提交审批', 'assess/submit/', '78', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('94', '报表系统', 'report/index/', '0', '75', '2', '2');
INSERT INTO `wz_purview` VALUES ('95', '评分公示表', 'report/assess/', '94', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('96', '导出评分公示表', 'report/assess_do_excel/', '95', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('98', 'clearcache', 'setting/flushMemcache/', '46', '0', '1', '2');
INSERT INTO `wz_purview` VALUES ('99', '问答表单查看', 'assess/write_view/', '78', '3', '2', '2');
INSERT INTO `wz_purview` VALUES ('100', '兑换结算', 'balance/index/', '30', '93', '2', '2');
INSERT INTO `wz_purview` VALUES ('101', '按自然村批量打印公示表仅限村委', 'report/assess_do_print/', '95', '2', '2', '2');
INSERT INTO `wz_purview` VALUES ('102', '整站数据可视化监控台', 'desktop/analysis/', '7', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('103', '进度报表', 'report/progress/', '94', '200', '2', '2');
INSERT INTO `wz_purview` VALUES ('104', '积分报表', 'report/points/', '94', '180', '2', '2');
INSERT INTO `wz_purview` VALUES ('105', '系统访问日志', 'log/access/', '27', '8', '2', '2');
INSERT INTO `wz_purview` VALUES ('119', '在线问答', 'ask/index/', '0', '73', '2', '2');
INSERT INTO `wz_purview` VALUES ('120', '我要提问', 'ask/add/', '119', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('121', '回复提问', 'ask/reply/', '119', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('122', '删除提问', 'ask/del/', '119', '1', '2', '2');
INSERT INTO `wz_purview` VALUES ('123', '问答详细查看', 'ask/view/', '119', '0', '2', '1');
INSERT INTO `wz_purview` VALUES ('124', '导出待审的商品', 'workflow/do_excel/', '67', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('125', '微信端打分', 'wxassess/write/', '78', '2', '2', '2');
INSERT INTO `wz_purview` VALUES ('126', '档案导出XLS', 'villager/do_excel/', '60', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('127', '微信模板消息日志', 'log/wxmsg/', '27', '7', '2', '2');
INSERT INTO `wz_purview` VALUES ('128', '系统操作日志', 'log/postlog/', '27', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('129', '解绑村民微信', 'villager/unbind/', '60', '7', '2', '2');
INSERT INTO `wz_purview` VALUES ('130', '结算单详细查看', 'balance/view/', '100', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('131', '结算提交审批', 'balance/to_workflow/', '100', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('132', '评分标签管理', 'assesstag/index/', '0', '41', '2', '2');
INSERT INTO `wz_purview` VALUES ('133', '标签添加', 'assesstag/add/', '132', '10', '2', '2');
INSERT INTO `wz_purview` VALUES ('134', '标签修改', 'assesstag/edit/', '132', '9', '2', '2');
INSERT INTO `wz_purview` VALUES ('135', '标签删除', 'assesstag/del/', '132', '7', '2', '2');
INSERT INTO `wz_purview` VALUES ('136', '评分项绑定标签管理', 'assesstag/config/', '132', '6', '2', '2');
INSERT INTO `wz_purview` VALUES ('138', '进度报表导出', 'report/progress_to_excel/', '103', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('139', '积分报表导出', 'report/points_to_excel/', '104', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('140', '乡镇出题审批', 'workflow/town_assess/', '67', '5', '2', '2');
INSERT INTO `wz_purview` VALUES ('141', '乡镇问卷配置', 'assess/towncadre_config/', '78', '0', '2', '2');
INSERT INTO `wz_purview` VALUES ('142', '题库', 'questions/index/', '78', '0', '2', '2');

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
