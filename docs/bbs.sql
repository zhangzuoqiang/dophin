drop database if exists `antbbs`;
create database /*!32312 if not exists*/`antbbs` /*!40100 default character set utf8 */;
use `antbbs`;
set names utf8;

set foreign_key_checks=0;


-- -----------------------------------------------------
-- table `bbs_area`
-- -----------------------------------------------------
drop table if exists `bbs_area` ;
create  table `bbs_area` (
  `id` smallint unsigned not null auto_increment comment '版块id',
  `pid` int unsigned not null default 0 comment '上级版块id，默认为0，表示为顶级版块',
  `name` varchar(45) not null default '' comment '版块名称',
  `orderid` smallint unsigned not null default 0 comment '显示顺序',
  `topics` int unsigned not null default 0 comment '主题帖数量',
  `posts` int unsigned not null default 0 comment '所有帖子数据（含回复帖）',
  `todayposts` int unsigned not null default 0 comment '今日帖子数',
  `lastpost` int unsigned not null default 0 comment '最后一次发表帖子时间',
  `is_post` tinyint not null default 1 comment '是否可以发表帖子',
  `level` tinyint not null default 1 comment '版块层级:1-分区，2-版块，3-子版块',
  `status` tinyint not null default 0 comment '0-关闭;1-正常;2-暂时关闭',
  `open_time` int unsigned not null default 0 comment '开放时间',
  `allowanonymous` tinyint not null default 1 comment '是否允许匿名访问',
  primary key (`id`),
  index `pid` (`pid`),
  index `lastpost` (`lastpost`),
  index `orderid` (`orderid`) 
) engine = InnoDB default charset=utf8 comment = '论坛版块区域';

-- ----------------------------
-- Records of bbs_area
-- ----------------------------
INSERT INTO bbs_area VALUES ('1', '0', '坛务区', '0', '0', '0', '0', '0', '1', '1', '1', '0', '0');
INSERT INTO bbs_area VALUES ('2', '1', '公告', '0', '0', '0', '0', '0', '1', '2', '1', '0', '0');
INSERT INTO bbs_area VALUES ('3', '0', '休闲区', '0', '0', '0', '0', '0', '1', '1', '1', '0', '0');
INSERT INTO bbs_area VALUES ('4', '3', '灌水', '0', '0', '0', '0', '0', '1', '2', '1', '0', '0');


-- -----------------------------------------------------
-- table `bbs_topic`
-- -----------------------------------------------------
drop table if exists `bbs_topic` ;
create  table `bbs_topic` (
  `id` mediumint unsigned not null auto_increment comment '主题id',
  `aid` int not null default 0 comment '所属版块id',
  `title` varchar(100) not null default '' comment '标题',
  `authorid` int unsigned not null default 0 comment '主题发起者用户id',
  `author` varchar(100) not null default '' comment '主题发起者用户名',
  `posttime` int unsigned not null default 0 comment '发帖时间',
  `lastpost` int unsigned not null default 0 comment '最后发帖/回复时间',
  `lastposter_id` int unsigned not null default 0 comment '最后发帖/回复人ID',
  `lastposter` char(16) not null default '' comment '最后发帖/回复人',
  `views` int unsigned not null default 0 comment '查看数',
  `replies` int unsigned not null default 0 comment '回复数',
  `need_coin` tinyint unsigned not null default 0 comment '是否需要金钱购买',
  `status` tinyint not null default 0 comment '0-关闭;1-正常;2-锁定（不能回复）',
  primary key (`id`),
  index `aid` (`aid`),
  index `posttime` (`posttime`),
  index `lastpost` (`lastpost`)
) engine = InnoDB default charset=utf8 comment = '论坛主题列表';


-- -----------------------------------------------------
-- table `bbs_post`
-- -----------------------------------------------------
drop table if exists `bbs_post` ;
create  table `bbs_post` (
  `id` int unsigned not null auto_increment comment '帖子id',
  `tid` int unsigned not null default 0 comment '所属主题id',
  `istopic` int not null default 0 comment '是否主题帖本身',
  `storey` int unsigned not null default 0 comment '',
  `authorid` int unsigned not null default 0 comment '主题发起者用户id',
  `author` varchar(100) not null default '' comment '主题发起者用户名',
  `status` tinyint not null default 0 comment '0-关闭;1-正常;2-锁定（不能回复）',
  `posttime` int unsigned not null default 0 comment '发帖时间',
  `title` varchar(100) not null default '' comment '标题',
  `content` text not null comment '内容',
  primary key (`id`),
  index `tid` (`tid`),
  index `istopic` (`istopic`),
  index `tid_istopic` (`tid`, `istopic`)
) engine = InnoDB default charset=utf8 comment = '论坛所有帖子列表';