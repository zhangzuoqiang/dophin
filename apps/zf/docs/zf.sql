drop database if exists `zf`;
create database /*!32312 if not exists*/`zf` /*!40100 default character set utf8 */;
use `zf`;
set names utf8;

set foreign_key_checks=0;

-- ----------------------------
-- user_login
-- 用户登录表
-- ----------------------------
drop table if exists `user_login`;
create table `user_login` (
  `uid` int unsigned not null auto_increment,
  `username` char(16) not null default '' comment '用户名',
  `password` char(32) not null default '' comment '密码',
  `salt` char(8) not null default '' comment '私钥',
  `last_login_ip` bigint unsigned not null default 0 comment '上次登录IP',
  `last_login_time` int unsigned not null default 0 comment '上次登录时间',
  primary key (`uid`),
  unique key `username` (`username`) 
) engine=InnoDB default charset=utf8 comment = '用户登录表';

-- ----------------------------
-- user
-- 用户表
-- ----------------------------
drop table if exists `user`;
create table `user` (
  `uid` int unsigned not null auto_increment,
  `username` char(16) not null comment '用户名',
  `regip` bigint unsigned not null default 0 comment '注册IP',
  `regtime` int unsigned not null default 0 comment '注册时间',
  `from_channel_id` int unsigned not null default 0 comment '用户来源渠道(广告渠道)ID',
  `type` tinyint unsigned not null default 1 comment '用户类型：1-个人用户，2-独立经纪人，3-经纪人（经纪公司员工独立账号），4-经纪人（经纪公司附属账号），5-经纪公司',
  `nickname` char(16) not null default '' comment '昵称',
  `realname` char(16) not null default '' comment '用户真实姓名',
  `idcard` char(18) not null default '' comment '身份证号码',
  `qq` bigint unsigned not null default 0 comment '联系QQ',
  `email` char(60) not null default '' comment '电子邮箱',
  `mobile` char(11) not null default '' comment '手机',
  `exp` int unsigned not null default 0 comment '经验值',
  `credit` int unsigned not null default 0 comment '积分',
  `coin` int unsigned not null default 0 comment '虚拟币',
  `gold` int unsigned not null default 0 comment '金币',
  `gold_bond` int unsigned not null default 0 comment '金券（金币扩展货币）',
  `avatar_type` tinyint unsigned not null default 0 comment '头像类型格式:1-jpg,2-png,3-gif',
  `emails_tatus` tinyint unsigned not null default 0 comment '电子邮件绑定状态',
  `mobile_status` tinyint unsigned not null default 0 comment '手机绑定状态',
  `secure_status` tinyint unsigned not null default 0 comment '安全密保状态',
  `status` tinyint unsigned not null default 0 comment '账号状态:-1-逻辑删除,0-未激活,1-正常,2-冻结(网站管理员操作),3-锁定(玩家自己操作)',
  `gender` enum('0', '1', '2') not null default '2' comment '性别',
  `birth_date` int unsigned not null default 0 comment '出生日期(日期凌晨时间戳)',
  `province` mediumint unsigned not null default 0 comment '省级行政区号',
  `city` mediumint unsigned not null default 0 comment '市',
  `county` mediumint unsigned not null default 0 comment '县/区',
  primary key (`uid`),
  unique `username` (`username`),
  index `regip` (`regip`),
  index `regtime` (`regtime`),
  index `from_channel_id` (`from_channel_id`)
) engine = InnoDB default charset=utf8 comment = '用户表';

-- ----------------------------
-- user_info
-- 用户详细信息表
-- ----------------------------
drop table if exists `user_info`;
create table `user_info` (
  `uid` int unsigned not null auto_increment,
  `username` char(16) not null comment '用户名',
  `original_address` varchar(255) not null default '' comment '原籍详细地址',
  `address` varchar(255) not null default '' comment '详细的联系地址',
  primary key (`uid`) 
) engine = InnoDB default charset=utf8 comment = '用户详细信息表';


-- -----------------------------------------------------
-- advanced_password
-- 二级密码表，用于支付等关键操作的验证
-- -----------------------------------------------------
drop table if exists `advanced_password` ;
create table `advanced_password` (
  `uid` int unsigned not null auto_increment,
  `password` char(32) not null comment '支付密码',
  `salt` int unsigned not null default 0 comment '加密私钥',
  `status` tinyint unsigned not null default 1 comment '状态:0-禁用,1-正常',
  `error_times` tinyint unsigned not null default 1 comment '密码错误次数',
  primary key (`uid`)
) engine = innodb comment = '二级密码表，用于支付等关键操作的验证';


-- -----------------------------------------------------
-- user_security_question
-- 用户密保问题列表
-- -----------------------------------------------------
drop table if exists `user_security_question` ;
create table `user_security_question` (
  `id` int unsigned not null auto_increment,
  `uid` int unsigned not null default 0 comment '用户ID',
  `question` tinyint unsigned not null default 0 comment '使用系统问题编号（无自定义问题时生效）',
  `my_question` varchar(32) not null default '' comment '自定义问题',
  `answer` varchar(32) not null default '' comment '回答内容MD5值',
  primary key (`id`),
  index `uid` (`uid`)
) engine = innodb default charset=utf8 comment = '用户密保问题列表';


-- -----------------------------------------------------
-- user_login_log
-- 用户登录日志
-- -----------------------------------------------------
drop table if exists `user_login_log` ;
create table `user_login_log` (
  `uid` int unsigned not null auto_increment,
  `username` char(16) not null default '' comment '用户名',
  `login_data` varchar(255) not null default '' comment '登录的数据，保存最后几次登录的数组json格式：{[IP,时间戳],[IP,时间戳],[IP,时间戳]}',
  primary key (`uid`),
  unique key `username` (`username`)
) engine=innodb default charset=utf8 comment = '用户登录日志';


-- ----------------------------
-- Table structure for `user_session`
-- ----------------------------
drop table if exists `user_session`;
create table `user_session` (
  `us_id` int unsigned not null auto_increment,
  `sid` char(32) not null default '' comment '会话ID',
  `data` varchar(255) not null default '' comment '数据',
  `uid` int(11) not null default 0 comment '用户ID',
  `expire_time` int(11) not null default 0 comment '过期时间',
  primary key (`us_id`),
  unique key `sid` (`sid`),
  KEY `uid` (`uid`)
) engine=InnoDB default charset=utf8 comment = '用户SESSION表';

-- ----------------------------
-- user_online
-- 用户在线表
-- ----------------------------
drop table if exists `user_online`;
create table `user_online` (
  `uid` int unsigned not null auto_increment,
  `username` char(16) not null default '' comment '用户名',
  `last_login_ip` bigint unsigned not null default 0 comment '上次登录IP',
  `last_login_time` int unsigned not null default 0 comment '上次登录时间',
  `last_online_time` int unsigned not null default 0 comment '上次在线时间',
  primary key (`uid`),
  unique `username` (`username`)
) engine=InnoDB default charset=utf8 comment = '用户在线表';


-- -----------------------------------------------------
-- from_channels
-- 用户来源渠道
-- -----------------------------------------------------
drop table if exists `from_channels` ;
create table `from_channels` (
  `id` int unsigned not null auto_increment,
  `name` varchar(32) not null default '' comment '渠道名',
  `url` varchar(255) not null default '' comment '渠道链接地址',
  primary key (`id`)
) engine = innodb default charset=utf8 comment = '用户来源渠道';
insert into `from_channels` (`id`, `name`) VALUES
  (1, '直接进入'),
  (2, '百度'),
  (3, 'GOOGLE'),
  (10, '其他搜索引擎');



-- -----------------------------------------------------
-- category
-- 文章内容分类表
-- -----------------------------------------------------
drop table if exists `category` ;
create table `category` (
  `id` int unsigned not null auto_increment,
  `pid` int unsigned not null default 0 comment '上级分类ID',
  `name` varchar(15) not null comment '分类名',
  `status` tinyint unsigned not null default 1 comment '状态',
  primary key (`id`),
  index `pid` (`pid`)
) engine = innodb default charset=utf8 comment = '文章内容分类表';
insert into `category` VALUES
  (1, 0, '新闻', 1),
  (2, 0, '活动', 1);


-- -----------------------------------------------------
-- article
-- 文章表
-- -----------------------------------------------------
drop table if exists `article` ;
create table if not exists `article` (
  `id` int unsigned not null auto_increment,
  `cid` int unsigned not null default 0 comment '文章分类id',
  `views` mediumint unsigned not null default 0 comment '浏览数(点击数)',
  `title` varchar(150) not null default '' comment '标题',
  `title_image` varchar(255) not null default '' comment '标题图片',
  `keywords` varchar(255) not null default '' comment '关键字',
  `brief` varchar(255) not null comment '摘要',
  `editor_uid` int unsigned not null default 0 comment '负责编辑人员',
  `editor` varchar(45) not null default '' comment '负责编辑人员',
  `post_time` int unsigned not null default 0 comment '发布时间',
  `create_time` int unsigned not null default 0 comment '创建时间(后台创建)',
  `status` tinyint not null default 0 comment '状态:-1-已删除;0-待审核;1-正常',
  `html_file` varchar(100) not null default '' comment '生成的静态页路径',
  `redirect` varchar(100) not null default '' comment '跳转url',
  primary key (`id`),
  index `cid` (`cid`),
  index `post_time` (`post_time`)
) engine = innodb default charset=utf8 comment = '文章表';


-- -----------------------------------------------------
-- table `article_content`
-- -----------------------------------------------------
drop table if exists `article_content` ;
create table if not exists `article_content` (
  `id` int unsigned not null auto_increment,
  `content` text not null comment '内容',
  primary key (`id`)
) engine = innodb default charset=utf8 comment = '文章内容表';


-- -----------------------------------------------------
-- tags
-- TAG标签表
-- -----------------------------------------------------
drop table if exists `tags`;
create table `tags` (
  `id` mediumint unsigned not null auto_increment,
  `name` varchar(32) not null default '' comment 'TAG名称',
  `num` mediumint unsigned not null default 0 comment '使用该TAG的内容数',
  `hot` tinyint unsigned not null default 0 comment '是否热门标签',
  primary key  (`id`),
  key `idx_hot_num` (`hot`,`num`),
  key `idx_name` (`name`)
) engine=innodb default charset=utf8 comment='TAG标签表';

-- -----------------------------------------------------
-- tag_data
-- TAG标签数据
-- -----------------------------------------------------
drop table if exists `tag_data`;
create table `tag_data` (
  `tagid` mediumint unsigned not null default 0 comment '标签ID',
  `cid` smallint unsigned not null default 1 comment '分类ID',
  `nid` int unsigned not null default 0 comment '标签对应内容ID',
  key `idx_tagid` (`tagid`),
  key `idx_nid` (`nid`)
) engine=innodb default charset=utf8 comment='TAG标签表';

-- -----------------------------------------------------
-- links
-- 链接
-- -----------------------------------------------------
drop table if exists `links`;
create table `links` (
  `id` int unsigned not null auto_increment,
  `name` varchar(100) not null comment '链接名称',
  `url` varchar(50) not null comment '链接目标URL',
  `cid` smallint unsigned not null default '1' comment '所属分类ID',
  `cname` varchar(16) not null comment '所属分类名词',
  `note` varchar(255) default NULL comment '链接简介',
  `status` int unsigned not null default 0 comment '状态',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='链接';

-- -----------------------------------------------------
-- special
-- 专题
-- -----------------------------------------------------
drop table if exists `special`;
create table `special` (
  `id` int unsigned not null auto_increment,
  `cid` smallint unsigned not null default 1 comment '分类ID',
  `name` varchar(255) not null default '' comment '专题名称',
  `title` varchar(255) not null default '' comment '专题标题',
  `domain` varchar(255) not null default '' comment '专题域名',
  `charge_editor` varchar(60) not null default '' comment '责任编辑',
  `views` mediumint unsigned not null default 0 comment '查看数(专题首页被查看数)',
  `status` tinyint unsigned not null default 0 comment '状态:-1-已删除;0-待审核;1-正常;2-已过期',
  `sign` varchar(50) not null default 0 comment '专题标志符,用于生成缓存目录等',
  `relate` varchar(255) not null default '' comment '关联的专题ID',
  `create_time` int unsigned not null default 0 comment '创建时间',
  `public_time` int unsigned not null default 0 comment '发布时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='专题';


-- -----------------------------------------------------
-- comments
-- 评论系统
-- -----------------------------------------------------
drop table if exists `comments`;
create table `comments` (
  `id` int unsigned not null auto_increment,
  `nid` int unsigned not null default 0 comment '所属文章ID',
  `content` text not null comment '评论内容',
  `username` varchar(16) not null default '' comment '评论用户',
  `qq` int not null default 0 comment 'QQ',
  `email` varchar(50) not null default '' comment 'Email',
  `status` tinyint unsigned not null default 0 comment '状态',
  `posttime` int unsigned not null default 0 comment '评论时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='评论内容';





-- -----------------------------------------------------
-- county
-- 区县列表
-- -----------------------------------------------------
drop table if exists `county`;
create table `county` (
  `id` int unsigned not null auto_increment comment '行政区划代码',
  `name` varchar(30) not null default '' comment '名称',
  primary key (`id`),
  unique key `name` (`name`)
) engine=innodb default charset=utf8 comment='区县列表';
insert into `county`(`id`, `name`) values
  (350102, "鼓楼区"),
  (350103, "台江区"),
  (350104, "仓山区"),
  (350105, "马尾区"),
  (350111, "晋安区"),
  (350121, "闽侯县"),
  (350122, "连江县"),
  (350123, "罗源县"),
  (350124, "闽清县"),
  (350125, "永泰县"),
  (350128, "平潭县"),
  (350181, "福清市"),
  (350182, "长乐市");

-- -----------------------------------------------------
-- county
-- 区域/商圈列表
-- -----------------------------------------------------
drop table if exists `district`;
create table `district` (
  `id` int unsigned not null auto_increment,
  `county` int unsigned not null default 0 comment '所属区县',
  `name` varchar(30) not null default '' comment '名称',
  primary key (`id`),
  key `name` (`name`)
) engine=innodb default charset=utf8 comment='区域/商圈列表';


-- -----------------------------------------------------
-- housing_estate
-- 小区列表
-- -----------------------------------------------------
drop table if exists `housing_estate`;
create table `housing_estate` (
  `id` int unsigned not null auto_increment,
  `name` varchar(30) not null default '' comment '小区名称',
  `first_letter` char(1) not null default '' comment '小区名称首字母',
  `py_letter` varchar(20) not null default '' comment '小区名称拼音首字母',
  `pinyin` varchar(60) not null default '' comment '小区名称拼音',
  `address` varchar(255) not null default '' comment '小区地址',
  `county` int unsigned not null default 0 comment '所属区县',
  `district` int unsigned not null default 0 comment '所属区域/商圈',
  `status` varchar(60) not null default '' comment '状态：0-未认证（用户信息中自动添加），1-正常',
  primary key (`id`),
  unique key `name` (`name`)
) engine=innodb default charset=utf8 comment='小区列表';


-- -----------------------------------------------------
-- 
-- 采集房源列表
-- -----------------------------------------------------
drop table if exists ``;
create table `let_house` (
  `id` int unsigned not null auto_increment,
  `title` varchar(100) not null default '' comment '标题',
  `title_image` varchar(100) not null default '' comment '标题图片',
  `he_id` int unsigned not null default 0 comment '所属小区ID',
  `he_name` varchar(30) not null default '' comment '所属小区名称',
  `county_id` int unsigned not null default 0 comment '所属区县ID',
  `district_id` int unsigned not null default 0 comment '所属区域ID',
  `district_name` varchar(30) not null default '' comment '所属区域名称',
  `room` tinyint unsigned not null default 0 comment '室',
  `hall` tinyint unsigned not null default 0 comment '厅',
  `washroom` tinyint unsigned not null default 0 comment '卫',
  `area` smallint unsigned not null default 0 comment '面积（整租为全部面积，合租为单间面积）',
  `house_property` tinyint unsigned not null default 0 comment '住宅性质：普通公寓，别墅，商住两用，平房，四合院',
  `fitment` tinyint unsigned not null default 0 comment '装修情况：毛坏，简装，中装，精装，豪装',
  `face` tinyint unsigned not null default 0 comment '朝向',
  `equipment` varchar(200) not null default '' comment '配套设备',
  `status` tinyint unsigned not null default 0 comment '状态',
  `update_time` smallint unsigned not null default 0 comment '更新时间',
  `create_time` int unsigned not null default 0 comment '发布时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='采集房源列表';


-- -----------------------------------------------------
-- let_house
-- 出租房源
-- -----------------------------------------------------
drop table if exists `let_house`;
create table `let_house` (
  `id` int unsigned not null auto_increment,
  `title` varchar(100) not null default '' comment '标题',
  `title_image` varchar(100) not null default '' comment '标题图片',
  `he_id` int unsigned not null default 0 comment '所属小区ID',
  `he_name` varchar(30) not null default '' comment '所属小区名称',
  `county_id` int unsigned not null default 0 comment '所属区县ID',
  `district_id` int unsigned not null default 0 comment '所属区域ID',
  `district_name` varchar(30) not null default '' comment '所属区域名称',
  `room` tinyint unsigned not null default 0 comment '室',
  `hall` tinyint unsigned not null default 0 comment '厅',
  `washroom` tinyint unsigned not null default 0 comment '卫',
  `area` smallint unsigned not null default 0 comment '面积（整租为全部面积，合租为单间面积）',
  `house_property` tinyint unsigned not null default 0 comment '住宅性质：普通公寓，别墅，商住两用，平房，四合院',
  `fitment` tinyint unsigned not null default 0 comment '装修情况：毛坏，简装，中装，精装，豪装',
  `face` tinyint unsigned not null default 0 comment '朝向',
  `equipment` varchar(200) not null default '' comment '配套设备',
  `uid` int unsigned not null default 0 comment '发布用户ID',
  `contact_person` varchar(30) not null default '' comment '联系人',
  `contact_phone` varchar(30) not null default '' comment '联系电话',
  `update_time` smallint unsigned not null default 0 comment '更新时间',
  `create_time` int unsigned not null default 0 comment '发布时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='出租房源';


-- -----------------------------------------------------
-- let_house_content
-- 出租房源描述(可考虑保存到文本文件)
-- -----------------------------------------------------
drop table if exists `let_house_content`;
create table `let_house_content` (
  `id` int unsigned not null auto_increment,
  `content` text not null comment '内容描述',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='出租房源描述';


-- -----------------------------------------------------
-- let_house_equipment
-- 出租房源描述
-- -----------------------------------------------------
drop table if exists `let_house_equipment`;
create table `let_house_equipment` (
  `id` int unsigned not null auto_increment,
  `eq_id` int unsigned not null default 0 comment '设备ID',
  primary key (`id`),
  key `eq_id` (`eq_id`)
) engine=innodb default charset=utf8 comment='出租房源描述';


-- -----------------------------------------------------
-- house_images
-- 房源图片
-- -----------------------------------------------------
drop table if exists `house_images`;
create table `house_images` (
  `id` int unsigned not null auto_increment,
  `type` tinyint unsigned not null default 1 comment '房源类型：1-出租，2-求租',
  `is_cover` tinyint unsigned not null default 0 comment '是否封面图',
  `image_path` varchar(100) not null default '' comment '图片路径',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='出租房源';


-- -----------------------------------------------------
-- wanted_house
-- 求租房源
-- -----------------------------------------------------
drop table if exists `wanted_house`;
create table `wanted_house` (
  `id` int unsigned not null auto_increment,
  `title` varchar(100) not null default '' comment '标题',
  `county_id` int unsigned not null default 0 comment '所属区县ID',
  `district_id` int unsigned not null default 0 comment '所属区域ID',
  `district_name` varchar(30) not null default '' comment '所属区域名称',
  `rental` smallint unsigned not null default 0 comment '租金范围：可选项数组ID',
  `room` tinyint unsigned not null default 0 comment '居室需求范围',
  `contact_person` varchar(30) not null default '' comment '联系人',
  `contact_phone` varchar(30) not null default '' comment '联系电话',
  `update_time` smallint unsigned not null default 0 comment '更新时间',
  `create_time` int unsigned not null default 0 comment '发布时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='求租房源';


-- -----------------------------------------------------
-- wanted_house_content
-- 求租房源描述(可考虑保存到文本文件)
-- -----------------------------------------------------
drop table if exists `wanted_house_content`;
create table `wanted_house_content` (
  `id` int unsigned not null auto_increment,
  `content` text not null comment '内容描述',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='出租房源描述';


-- -----------------------------------------------------
-- broker
-- 经纪人
-- -----------------------------------------------------
drop table if exists `broker`;
create table `broker` (
  `id` int unsigned not null auto_increment,
  `name` varchar(255) default '' comment '经纪人姓名',
  `firm_id` int not null default 0 comment '经纪公司id',
  `firm_name` varchar(255) default '' comment '经纪公司名称',
  `create_time` int unsigned not null default 0 comment '加入时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='经纪人';


-- -----------------------------------------------------
-- brokerage_firm
-- 经纪公司
-- -----------------------------------------------------
drop table if exists `brokerage_firm`;
create table `brokerage_firm` (
  `id` int unsigned not null auto_increment,
  `name` varchar(255) default '' comment '经纪公司名称',
  `status` tinyint not null default 0 comment '状态:-1-已删除;0-待审核;1-正常;2-封号',
  `create_time` int unsigned not null default 0 comment '创建时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='经纪公司';


-- -----------------------------------------------------
-- broker_58
-- 经纪人（在58同城的信息）
-- -----------------------------------------------------
drop table if exists `broker_58`;
create table `broker_58` (
  `id` int unsigned not null auto_increment,
  `username` varchar(255) default '' comment '用户名',
  `callname` varchar(255) default '' comment '称呼',
  `auth_mobile` tinyint unsigned default 0 comment '手机认证',
  `auth_email` tinyint unsigned default 0 comment '邮箱认证',
  `auth_realname` tinyint unsigned default 0 comment '实名认证',
  `regtime` int not null default 0 comment '注册时间',
  primary key  (`id`)
) engine=innodb default charset=utf8 comment='经纪人';


-- -----------------------------------------------------
-- xzqh_code
-- 国内行政区划代码表
-- -----------------------------------------------------
drop table if exists `xzqh_code` ;
create table `xzqh_code` (
  `id` int unsigned not null auto_increment,
  `code` int unsigned not null default 0 comment '行政区划代码',
  `name` varchar(50) not null default 0 comment '行政区域名称',
  `lvl` tinyint unsigned not null default 0 comment '行政级别：1-省，2-市，3-县',
  `province` int unsigned not null default 0 comment '所属省级行政区划',
  `city` int unsigned not null default 0 comment '所属市级行政区划',
  `big_area` int unsigned not null default 0 comment '所属大区：1-华北，2-东北，3-华东，4-中南，5-西南，6-西北，7-港澳台',
  primary key (`id`),
  index `code` (`code`),
  index `lvl` (`lvl`),
  index `province` (`province`),
  index `city` (`city`),
  index `big_area` (`big_area`)
) engine = InnoDB default charset=utf8 comment = '行政区划代码表';



-- ----------------------------
-- Table structure for `user_password`
-- 统一加密方式,用来判断密码相同的用户数
-- 多重加密保证可判断密码是否相同的同时又保证一定安全性,可独立库保存
-- ----------------------------
drop table if exists `user_password`;
create table `user_password` (
  `id` int unsigned not null auto_increment,
  `password` char(32) not null default '' comment '密码,密码为全站统一md5(md5(md5(密码+统一密钥)+统一密钥))',
  `num` int not null comment '相同密码的数量',
  primary key (`id`),
  unique key `password` (`password`)
) engine = InnoDB default charset=utf8 comment = '用户密码';
