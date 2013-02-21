-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 06 月 21 日 23:07
-- 服务器版本: 5.1.53
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `mpcom_sql`
--

-- --------------------------------------------------------

--
-- 表的结构 `fo_adv`
--

CREATE TABLE IF NOT EXISTS `fo_adv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL COMMENT '广告位置名称',
  `code` varchar(30) NOT NULL COMMENT '广告编号',
  `block` varchar(30) NOT NULL DEFAULT '' COMMENT '广告模块',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当前广告所属的用户ID',
  `p_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '广告图片ID',
  `price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '广告价格',
  `size` varchar(30) NOT NULL DEFAULT '' COMMENT '广告图片大小:宽*高',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '广告链接',
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '广告标题',
  `brief` varchar(300) NOT NULL DEFAULT '' COMMENT '广告简介',
  `show_type` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '广告显示类型:1-文字链接,2-图片链接,3-图文广告',
  `status` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '广告状态:0-待售,1-已出售,2-赠送,3-禁用',
  `start_time` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '广告开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '广告结束时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_adv`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_attachment`
--

CREATE TABLE IF NOT EXISTS `fo_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(60) NOT NULL DEFAULT '0' COMMENT '附件分类：1-图片，2-flv视频附件，3-压缩附件，4-音频附件',
  `mime_type` varchar(60) NOT NULL DEFAULT '' COMMENT '附件格式',
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT '附件路径',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `description` varchar(3000) NOT NULL DEFAULT '' COMMENT '附件描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='附件列表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_attachment`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_attribute`
--

CREATE TABLE IF NOT EXISTS `fo_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '属性名称',
  `sign` varchar(60) NOT NULL DEFAULT '' COMMENT '标志符',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:-1-已删除;0-待审核;1-正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='属性表' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `fo_attribute`
--

INSERT INTO `fo_attribute` (`id`, `name`, `sign`, `status`) VALUES
(1, '通用状态', 'common_status', 1),
(2, '新闻状态', 'news_status', 1),
(3, '分类状态', 'category_status', 1),
(4, '公司状态', 'corp_status', 1),
(5, '用户状态', 'user_status', 1),
(6, '采集内容状态', 'spider_contents_status', 1);

-- --------------------------------------------------------

--
-- 表的结构 `fo_attr_item`
--

CREATE TABLE IF NOT EXISTS `fo_attr_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` tinyint(4) NOT NULL COMMENT '属性ID',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '属性项名称',
  `value` varchar(60) NOT NULL DEFAULT '' COMMENT '属性项值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='属性项' AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `fo_attr_item`
--

INSERT INTO `fo_attr_item` (`id`, `aid`, `name`, `value`) VALUES
(1, 1, '已删除', '-1'),
(2, 1, '待审核', '0'),
(3, 1, '正常', '1'),
(4, 2, '已删除', '-1'),
(5, 2, '草稿', '0'),
(6, 2, '发布', '1'),
(7, 2, '待审核', '2'),
(8, 3, '已删除', '-1'),
(9, 3, '正常', '1'),
(10, 4, '已删除', '-1'),
(11, 4, '待审核', '0'),
(12, 4, '正常', '1'),
(13, 5, '冻结', '-2'),
(14, 5, '已删除', '-1'),
(15, 5, '待审核', '0'),
(16, 5, '正常', '1'),
(17, 6, '已删除', '-1'),
(18, 6, '待处理', '0'),
(19, 6, '已处理', '1'),
(20, 6, '已审核（完成采编）', '2');

-- --------------------------------------------------------

--
-- 表的结构 `fo_brand`
--

CREATE TABLE IF NOT EXISTS `fo_brand` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spc_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '专题ID',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '分类ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌名',
  `official_url` varchar(255) NOT NULL DEFAULT '' COMMENT '官方网址',
  `status` tinyint(4) NOT NULL COMMENT '分类状态:1-正常,-1-删除',
  `content` text NOT NULL COMMENT '品牌说明',
  PRIMARY KEY (`id`),
  KEY `spc_id` (`spc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='品牌表' AUTO_INCREMENT=41 ;

--
-- 转存表中的数据 `fo_brand`
--

INSERT INTO `fo_brand` (`id`, `spc_id`, `cid`, `name`, `official_url`, `status`, `content`) VALUES
(1, 1, 302, '劳力士', 'www.rolex.com', 1, '劳力士（Rolex）是瑞士著名的手表制造商，前身为Wilsdorf and Davis公司，由德国人汉斯·威斯多夫（Hans Wilsdof）与英国人戴维斯（Alfred Davis）于1905年在伦敦合伙经营。1908年由汉斯·威尔司多夫（Hans Wilsdof）在瑞士的拉夏德芬（La Chaux-de-Fonds）注册更名为ROLEX。'),
(2, 2, 302, '欧米茄', 'www.omegawatches.com', 1, '<p>\r\n﻿  欧米茄是国际著名制表企业和品牌。</p>\r\n<p>\r\n﻿  1848年，<a href="http://baike.baidu.com/view/2773.htm" target="_blank">瑞士</a>联邦诞生，路易&middot;勃兰特（Louis Brandt）与拉夏德芬（La Chaux-de-Fonds）开始手表装嵌工作。1880年，路易&middot;勃兰特的儿子Louis-Paul及Csar将厂房搬迁至人力充足、资源丰富且交通方便的<a href="http://baike.baidu.com/view/263202.htm" target="_blank">比尔</a>（Bienne）地区。此后，采用机械化生产，统一规格零件，并引进新式分工系统进行装配工作，装制出精密准确、品质优良且价格合理的表款。</p>\r\n<p>\r\n﻿  举世闻名欧米茄19令机芯于1894年面世后，不仅成为其卓越的标志，公司也因此命名为&ldquo;欧米茄&rdquo;。自此时起，欧米茄以其先进制表技术，成为制表业的先锋达一百五十年之久。</p>\r\n'),
(3, 3, 302, '百达翡丽', 'www.patek.com', 1, '<p>\r\n﻿  百达翡丽，是一家始于1839年的瑞士著名钟表品牌，其每块表的平均零售价达13000美元至20000美元。百达翡丽在钟表技术上一直处于领先地位，拥有多项专利，其手表均在原厂采用手工精致，坚持品质、美丽、可靠的优秀传统，百达翡丽以其强烈的精品意识、精湛的工艺、源源不断的创新缔造了举世推崇的钟表品牌。</p>\r\n<h5>\r\n﻿  经典广告语：</h5>\r\n<p>\r\n﻿  &ldquo;没人能拥有百达翡丽，只不过为下一代保管而已。&rdquo;</p>\r\n'),
(4, 4, 302, '江诗丹顿', 'www.vacheron-constantin.com', 1, '<p>\r\n﻿  创立于1775年(清乾隆20年)，有247年历史，是世界上历史最悠久、延续时间最长的名牌手表。18世纪初其它名表还未出世，它已成为欧洲皇室贵胄的珍爱。公司创始人是哲学家让&middot;马克&middot;瓦什隆也是卢梭和伏尔泰的好朋友.江诗丹顿每年仅仅生产2万多只表，只只值得收藏，是真正意义上贵族的艺术品。在表坛的地位更有甚于百达翡丽。表盘上的瑞士国徽般的十字军标记，已经是品位、地位、和财富的象征。不管过去或今天，江诗丹顿始终在瑞士制表业史上担当关键的角色。</p>'),
(5, 5, 302, '爱彼', 'www.audemarspiguet.com', 1, '<p>\r\n﻿  爱彼（Audemars Piguet）是瑞士钟表国家品牌，在1889年举行的第十届巴黎环球钟表展览会中，爱彼的Grand Complication陀表参展，精湛设计引来极大回响，声名大噪，享誉国际，为爱彼表在表坛树立了崇高的地位。时至今日，爱彼表在Audemars与Piguet家族第四代子孙的领导下，成就骄人，深获钟表鉴赏家及收藏家的推崇，成为世界十大名表之一。</p>\r\n'),
(6, 6, 102, '卡地亚', 'www.cartier.com', 1, '<p>\r\n﻿  卡地亚（Cartier SA）是一间法国钟表及珠宝制造商，于1847年由Louis-Fran&ccedil;ois Cartier在巴黎Rue Montorgueil 31号创办。1874年，其子亚法&middot;卡地亚继承其管理权，由其孙子路易&middot;卡地亚、皮尔&middot;卡地亚与积斯&middot;卡地亚将其发展成世界著名品牌。现为瑞士历峰集团（Compagnie Financi&egrave;re Richemont SA）下属公司。1904年曾为飞机师阿尔拔图&middot;山度士&middot;度门设计世界上首只戴在手腕的腕表&mdash;&mdash;卡地亚山度士腕表 （Cartier Santos）。</p>\r\n'),
(7, 7, 302, '万国', 'www.iwc.com', 1, '<p>\r\n﻿  创立于1868年的万国表有&ldquo;机械表专家&rdquo;之称，每只万国腕表都要经历28次独立测试。创始人是美国人佛罗伦汀.琼斯(Florentine A Jones)。20世纪初，万国表在德国、奥地利等地销售量大增。如今，万国表在全球有700多个销售点，产品主要销往远东、瑞士和德国。目前隶属瑞士历峰集团。</p>\r\n'),
(8, 8, 302, '伯爵', 'www.piaget.com', 1, '自1874年创立以来，伯爵始终致力于提升创造力、修饰细节以及融合腕表和珠宝工艺等方面，体现高档品牌的风范。伯爵原本专于腕表机芯的研究和制造，后来进一步将这项精湛的技艺推广至珠宝工艺，因此得以在 1960 年代推出第一款珠宝腕表。伯爵具有不断自我超越、出类拔萃的能力，特别擅长研发稀有、珍贵和独一无二的作品。'),
(9, 9, 302, '积家', 'www.Jaeger-LeCoultre.com', 1, '积家钟表自1833年成立于瑞士的汝山谷(Vallée de Joux)以来，不仅以传统制表工艺的捍卫者自居，同时也是精确计时技术和设计领域中的先驱之一。出色的质量、创新的概念及细腻的做工，让积家钟表成为业界中的佼佼者。'),
(10, 10, 302, '宝玑', 'www.breguet.com', 1, '宝玑（Breguet）多年来一直是瑞士钟表最重要的代名词，近年在历经公司重组。1747年，宝玑在瑞士的纳沙泰出生，他大部分时间居于巴黎，一生中创造无数伟大的发明，他活跃于制表业中每一个范畴，连串的突破令他的事业不断攀上高峰，如改良自动表、发明自鸣钟用的鸣钟弹簧；以及避震装置等等；而其新古典主义的简洁设计更予人惊喜。'),
(11, 11, 101, '古驰', 'www.gucci.com', 1, '<p>\r\n﻿  &ldquo;Gucci王国&rdquo;由Guccio Gucci创立于30年代。他在佛罗伦萨开了第一家店，推出了一系列标志性产品，其中包括著名的竹包。GUCCI从此享誉国际时装界。随着时间的流逝，这家著名的服装店被赋予了了奢华、性感、现代的品质。它是现代奢华的终极之作。1970年，这个品牌开始涉足香水行业。从那以后，它推出如：Envy香水、淡香水、男士香水和后来推出的Envy me2香水等性感、魅力非凡的香水。</p>\r\n'),
(12, 12, 101, 'LV', 'www.louisvuitton.com', 1, 'Louis Vuitton 路易·威登 是法国历史上最杰出的皮件设计大师之一。于1854年在巴黎开了以自己名字命名的第一间皮箱店。一个世纪之后，路易·威登成为皮箱与皮件领域数一数二的品牌，并且成为上流社会的一个象征物。如今路易·威登这一品牌已经不仅限于设计和出售高档皮具和箱包，而是成为涉足时装、饰物、皮鞋、箱包、珠宝、手表、 传媒、名酒等领域的巨型潮流指标。'),
(13, 13, 101, '香奈儿', 'www.chanel.com', 1, '<p>\r\n﻿  创始人Gabrielle Chanel香奈儿于1913年在法国巴黎创立香奈儿，香奈儿的产品种类繁多，有服装、珠宝饰品、配件、化妆品、香水，每一种产品都闻名遐迩，特别是她的香水与时装。 香奈儿(CHANEL)是一个有80多年经历的著名品牌，香奈儿时装永远有着高雅、简洁、精美的风格，她善于突破传统，早40年代就成功地将&ldquo;五花大绑&rdquo;的女装推向简单、舒适，这也许就是最早的现代休闲服。</p>\r\n'),
(14, 14, 101, '爱马仕', 'www.hermes.com', 1, '<p>\r\n﻿  创立于1837年的Herm&egrave;s以制造高级马具起家，从20世纪初开始涉足高级服装业，上世纪五六十年代起陆续推出香水、西服、鞋饰、瓷器等产品，成为全方位横跨生活的品位代表。坚持自我、不随波逐流的Herm&egrave;s多年来一直保持着简约自然的风格，&ldquo;追求真我，回归自然&rdquo;是Herm&egrave;s设计的目的，让所有的产品至精至美、无可挑剔是Herm&egrave;s的一贯宗旨。Herm&egrave;s品牌所有的产品都选用最上乘的高级材料，注重工艺装饰，细节精巧，以其优良的质量赢得了良好的信誉。</p>\r\n'),
(15, 15, 101, '巴宝莉', 'www.burberry.com', 1, '<p>\r\n﻿  Burberry是极具英国传统风格的奢侈品牌，其多层次的产品系列满足了不同年龄和性别消费者需求，公司采用零售、批发和授权许可等方式使其知名度享誉全球。Burberry创办于1856年，是英国皇室御用品。过去的几十年，Burberry主要以生产雨衣，伞具及丝巾为主，而今Burberry强调英国传统高贵的设计，赢取无数人的欢心，成为一个永恒的品牌！</p>\r\n'),
(16, 16, 101, '范思哲', 'www.versace.com', 1, '<p>\r\n﻿  创立于1978年，品牌标志是神话中的蛇妖美杜莎（Medusa)，代表着致命的吸引力。 范思哲的设计风格非常鲜明，是独特的美感极强的艺术先锋，强调快乐与性感，领口常开到腰部以下，设计师拮取了古典贵族风格的豪华、奢丽，又能充分考虑穿着舒适及恰当的显示体型。范思哲善于采用高贵豪华的面料，借助斜裁方式，制作高档服饰。</p>\r\n'),
(17, 17, 404, '施华洛世奇', 'www.swarovski.com', 1, '<p>\r\n﻿  ◆&nbsp;&nbsp; <strong>施华洛世奇 (Swarovski) 品牌档案：</strong><br />\r\n﻿  中文名：施华洛世奇<br />\r\n﻿  英文名：Swarovski<br />\r\n﻿  国家：奥地利<br />\r\n﻿  创办年代：1895年<br />\r\n﻿  创始人：丹尼尔&middot;施华洛世奇一世 (Daniel Swarovski)<br />\r\n﻿  经典系列：&ldquo;银水晶&rdquo;系列 ，丹尼尔&middot;施华洛世奇系列，Swarovski Selection系列<br />\r\n﻿  拥有者：施华洛世奇家族</p>\r\n<p>\r\n﻿  施华洛世奇 (SWAROVSKI) 是世界上首屈一指的水晶制造商，每年为时装、首饰及水晶灯等工业提供大量优质的切割水晶石。同时施华洛世奇 (SWAROVSKI) 也是以优质、璀璨夺目和高度精确的水晶和相关产品闻名于世的奢侈品品牌。</p>\r\n<p>\r\n﻿  施华洛世奇 (Swarovski) 产品最为动人之处，不仅仅在于它们是多么巧妙地被打磨成数十个切面，以致其对光线有极好的折射能力，整个水晶制品看起来格外耀眼夺目，更在于施华洛世奇 (Swarovski) 一直通过其产品向人们灌输着一种精致文化。施华洛世奇 (Swarovski) 的魅力源自材料的品质和采用的制造方法。至于独特制法的详细情况，则不会向外人透露。施华洛世奇公司 (Swarovski AG) 这家古老而神秘的公司仍保持着家族经营方式，把水晶制作工艺作为商业秘密代代相传，独揽与水晶切割有关的专利和财富。</p>\r\n<p>\r\n﻿  施华洛世奇 (Swarovski) 高级定制系列创始于1989年，提供一系列精致瑰丽的首饰、手袋和配饰，同时见证者施华洛世奇 (Swarovski) 的精湛工艺。这些作品采用最优质的物料、独特的皮革、珍贵的宝石和璀璨的水晶，是红地毯上名人巨星的闪烁点缀。<br />\r\n﻿  <br />\r\n﻿  1995年施华洛世奇 (Swarovski) 为庆祝成立100周年于发源地华登斯设立了水晶梦幻世界。水晶世界成为奥地利参观人数排名第二的旅游景点，开幕至今已吸引超过700万名水晶爱好者，成为水晶迷必到的朝圣地。</p>\r\n'),
(18, 18, 101, '普拉达', 'www.prada.com', 1, '<p>\r\n﻿  1913年，Prada在意大利米兰的市中心创办了首家精品店，创始人Mario&middot;Prada（马里奥&middot;普拉达）所设计的时尚而品质卓越的手袋、旅行箱、皮质配件及化妆箱等系列产品，得到了来自皇室和上流社会的宠爱和追捧。今天，这家仍然备受青睐的精品店依然在意大利上层社会拥有极高的声誉与名望，Prada产品所体现的价值一直被视为日常生活中的非凡享受。</p>\r\n<p>\r\n﻿  70年代的时尚圈环境变迁，Prada几近濒临破产边缘。1978年Miuccia与其夫婿Patrizio&middot;Bertelli共同接管Prada并带领Prada迈向全新的里程碑。Miuccia担任Prada总设计师，通过她天赋的时尚才华不断地演绎着挑战与创新的传奇。而Patrizio&middot;Bertelli，一位充满创造力的企业家，不仅建立了Prada全世界范围的产品分销渠道以及批量生产系统，同时还巧妙地将Prada传&nbsp; 普拉达的包包统的品牌理念和现代化的先进技术进行了完美结合。<br />\r\n﻿  <br />\r\n﻿  　　其实在Miuccia接手之际，Prada仍是流传于欧洲的小牌子。这种代代相传的家族若没有一番创新与突破，很容易没落。Miuccia寻找和传统皮料不同的新颖材质，历经多方尝试，从空军降落伞使用的材质中找到尼龙布料，以质轻、耐用为根基，于是，&ldquo;黑色尼龙包&rdquo;一炮而红。<br />\r\n﻿  <br />\r\n﻿  　　90年代，打着&ldquo;Less is More&rdquo;口号的极简主义应运而生，而Prada简约且带有一股制服美学般的设计正好与潮流不谋而合。1993年，Prada推出秋冬男装与男鞋系列，一时之间旗下男女装、配件成为追求流行简约与现代摩登的最佳风范。90年代末期，休闲运动风潮发烧，Prada推出Prada Sport系列，兼具机能与流行的设计，造成一股旋风。历经二十多年的努力与奋斗，这个品牌不断地发展与演变。通过Miuccia与Patrizio&middot;Bertelli的默契合作，Prada已经从一个小型的家族事业发展成为世界顶级的奢华品牌。如今，共有166家直接经营的Prada和Miu Miu精品店分布于全球的主要城市和旅游景点。坐落于香港中环历山大厦的店铺是Prada的第170家精品店。这些&ldquo;淡绿色精品店&rdquo;以其独特的设计结合了功能性与优雅的气质，完美地衬托出Prada优秀的产品。最近，Prada Epicenters旗舰店相继成立，它们风格独树一帜，是将购物与文化进行融合的全新尝试。<br />\r\n﻿  <br />\r\n﻿  　　目前，Prada集团已经拥有Prada、Jil Sander、Church&#39;s、Helmut Lang、Genny和Car Shoe等极具声望的国际品牌，同时它还拥有Miu Miu品牌的独家许可权。所有Prada集团麾下的产品的加工生产都是由意大利Tuscany地区的Prada Spa管辖，这地区被公认为拥有最高端的皮具和鞋类生产工艺和技术。对于批量生产，Prada对产品高质量的要求丝毫没有松懈，对品质永不妥协的观点已成为Prada的企业理念。</p>\r\n'),
(19, 19, 101, '阿玛尼', 'www.giorgioarmani.com', 1, '<p>\r\n﻿  1974年，Giorgio Armani 与他的朋友 Sergio Galeotti 合资，成立了以 Giorgio Armani 为名字的男装品牌。1974年，当乔治.阿玛尼的第一个男装时装发布会在完成之后，人们称他是&ldquo;夹克衫之王&rdquo;。<br />\r\n﻿  <br />\r\n﻿  时至今日，阿玛尼公司的业务已遍及了一百多个国家。除了高级时装 Giorgio Armani 之外，还设有多个副牌，如成衣品牌 Emporio、女装品牌 Mani、休闲服及牛仔装品牌 Armani Jeans 等，其产品种类除了服装外，还设有领带、眼镜、丝巾、皮革用品、香水等。Emporio Armani 是非常成功的品牌，&quot;Emporio&quot; 的意大利语的意思是指百货公司，即&quot;Armani百货公司&quot;，这是 Armani 的年轻系列的牌子。<br />\r\n﻿  <br />\r\n﻿  阿玛尼系列品牌紧紧抓住国际潮流，创造出富有审美情趣的男装、女装；同时以使用新型面料及优良制作而闻名。不同于大多数长期经营的时装设计师，追溯阿玛尼 18年来的经营历史，很少有可笑的或非常过时的设计。他能够在市场需求和优雅时尚之间创造一种近乎完美、令人惊叹的平衡。<br />\r\n﻿  <br />\r\n﻿  就设计风格而言，它们既不潮流亦非传统，而是二者之间很好的结合，其服装似乎很少与时髦两字有关。事实上，在每个季节，它们都有一些适当的可理解的修改， 全然不顾那些足以影响一个设计师设计风格的时尚变化，因为设计师阿玛尼相信服装的质量更甚于款式更新。<br />\r\n﻿  &nbsp;<br />\r\n﻿  Armani 的服饰长期以来受到众多好莱坞巨星的追捧，在明星中流行着一句话：&ldquo;如果你不知道穿什么，就穿 Armani 吧。&rdquo;出道至今，Giorgio Armani 已荣获了世界各地30多项服装大奖，其中包括：美国国际设计师协会奖，生活成就奖以及闻名遐迩的 Cutty Sark。</p>\r\n'),
(20, 20, 101, '百利', 'www.bally.com', 1, '<p>\r\n﻿  1851年，瑞士人卡尔.百利创建了Bally(百利)品牌，经过150多年的努力，百利如今已经跻身世界顶级品牌行列。</p>\r\n<p>\r\n﻿  <br />\r\n﻿  平均来说，每双Bally皮鞋经过200道严格的制作与检验手才能上市。品质自不在话下。巴利品牌中的SCRIBE系列，就是这种观念的一个杰出代表。这 一经典系列的皮鞋，每双的平均制造期为12天，比一般皮鞋长3倍。鞋垫部分由极富弹性的薄橡胶与软木层叠而成，要求穿者隔日穿，穿时鞋垫可伸缩成体贴脚形 的曲线，舒适自然。</p>\r\n<p>\r\n﻿  <br />\r\n﻿  Bally包包款式一向严谨，可以随意搭配所有服饰.选料方面更加一丝不苟，主要由鳄鱼皮、蜥蜴皮、天鹅绒、貂皮灯芯绒、鸵鸟皮、小马毛、羔羊皮、绸缎、 染有金属色的鹿皮等奢华物料制作而成。金属钮扣一般都刻有BALLY家徽。隐形的走线设计，让皮具整体感觉柔和完整</p>\r\n<p>\r\n﻿  <br />\r\n﻿  作为世界级精品王国，Bally无论鞋子、皮包、时装、手表或饰品，都秉承瑞士产品品质无可挑剔的传统，散发着经典与时尚的魅力。除了超凡的制作技术，它 在某时期的鞋和手袋，还会具有相近的设计特色，用同类皮革制造，使用相同的缝制方法，甚至做标识的手法也相同。这种&ldquo;整体造型&rdquo;概念，诠释着百利品牌的完 美与和谐。</p>\r\n'),
(21, 21, 103, '资生堂', 'www.shiseido.com', 1, '<p>\r\n﻿  资生堂（Shiseido ）日本著名化妆品品牌。取名源自中文《易经》中的&ldquo;至哉坤元，万物资生&rdquo;，资生堂的涵义为孕育新生命，创造新价值。&ldquo;至哉坤元，万物资生&rdquo;意为&ldquo;赞美大地的美德，她哺育了新的生命，创造了新的价值。&rdquo;这一名称正是资生堂公司形象的反映，是将东方的美学及意识与西方的技术及商业实践相结合的先锋。将先进技术与传统理念相结合，用西方文化诠释含蓄的东方文化。</p>\r\n'),
(22, 22, 103, '法尔曼', 'www.evalmont.com', 1, '<p>\r\n﻿  VALMONT被誉为瑞士皇后级护肤品，是众多名流青睐的世界十大护肤品牌之一，倍受欧洲皇室、贵族追捧，法国前总统戴高乐，伊朗前皇后及公主，罗马尼亚及希腊国王，英格丽.褒曼&lsquo;索非亚.罗兰等等都是VALMONT的支持者。我们所熟悉的黎明、关芝琳都是它的拥护者。而伊能静却在她的《美丽教主》赞美我们VALMONT的眼唇护理霜、细胞活化面膜、骨胶原修复眼膜三支产品时都说&ldquo;VALMONT是不折不扣的贵妇&rdquo;。</p>\r\n<p>\r\n﻿  它的产品走的是沙龙路线，在百货公司一般无货，要通过特定的美容沙龙，五星宾馆的SPA，或是它的指定代理商才能买到。就算在瑞士苏黎世也只有两家地方可以买到Valmont。<br />\r\n﻿  <br />\r\n﻿  坐落日内瓦湖滨的Valmont，独享天然地域优势。不受污染的大自然，清澈无杂质的水源及清新的空气，均为研制完美的护肤品提供优越的发展条件。与此同时，瑞士的丰富资源为提炼产品的配方作出极大贡献。Valmont采用的瑞士冰山矿泉水，来自瑞士瓦莱洲冰川领域，离海拔二千米以上的地方；因此，水质特别纯净、温和，并拥有完美均衡的矿物成分。没有经过特别的处理或改造，泉水直接运用于VALMONT的护肤产品配方中；其丰富的矿物质是肌肤组织的理提供阿玛尼产品资讯、最新活动、产品信息和网上购物指南。想强化剂，能促进细胞新陈代谢及巩固皮肤的天然抵御能力。<br />\r\n﻿  <br />\r\n﻿  为了延迟肌肤老化征状的出现，Valmont特别挑选了DNA作为护肤配方的重要元素。多项研究的结果也显示，其美容效令人惊讶。Valmont以DNA于医学界的应用为基础，然后将其修护、滋润、抗游离基及细胞自行更生的效用发挥得淋漓尽致。<br />\r\n﻿  <br />\r\n﻿  明星产品：L&#39;Elixir Des Glaciers<br />\r\n﻿  <br />\r\n﻿  每年全球限量5000瓶，是来自瑞士冰山海拔2000米未经污染的泉水、野生三文鱼精子中的活细胞元素、与瑞士Valais有机种植园中提炼的三种植物精华的总结合。</p>\r\n'),
(23, 23, 103, 'La Prairie', 'www.laprairie.com', 1, '<p>\r\n﻿  La prairie（蓓莉）来自瑞士，融合了最先进科技，可能是全球最昂贵的护肤品牌，在欧洲是王室贵族，名媛淑女们的挚爱。<br />\r\n﻿  <br />\r\n﻿  La Prairie的产品在世界各大主要的百货公司基本上都有专柜销售，La Prairie 的知名度在瑞士各大护肤品排中始终都居于首位。在瑞士的蒙特里斯有一座传承皇室血统的高科技生化研究室&mdash;&mdash;&ldquo;la prairie调养中心&rdquo;，以独步全球的&ldquo;细胞疗法系统&rdquo;著称，成为全球追求青春永驻的名媛淑女及贵妇的天堂。而这个实验室也成为当今全世界生化美颜科技最高的指针。<br />\r\n﻿  <br />\r\n﻿  La Prairie的特色是富含细胞精华。细胞精华主要是结合一百多种与人体肌肤所需营养成分相同的细胞营养滋养系统及醣蛋白组合和植物精华，提供人体所需的营养成分，以活化及滋养人体细胞，使细胞改善并且强化本身肌肤的自然功能，如调节、保护、再生等能力。<br />\r\n﻿  <br />\r\n﻿  招牌的鱼子酱系列还运用了一般保养品罕见的鲟鱼子(Caviar)精华成分，La Prairie将这种传统用于鱼子酱内的成分，运用到保养品中，更增加该品牌保养品成分的独特性。</p>\r\n'),
(24, 24, 103, '瑞妍', 'www.cellap.ch', 1, '<p>\r\n﻿  瑞妍 活细胞医学先驱　位于瑞士洛桑（Lausanne）的瑞妍活细胞生化实验中心（Cellap Laboratories），是国际上少数专攻生物细胞及植物生化复合科技的知名实验中心。</p>\r\n<p>\r\n﻿  一九八七年，瑞妍活细胞生化实验中心结合生物学家、病理学家、皮肤科医师及保养品学权威，共同研创出一套含有稳定生物活性细胞分子的护肤极品─「瑞妍生物活性细胞护理系列」，可深度调理肌肤酵素活动，帮助促进再生修护及功能正常化，经日内瓦贝特乐纪念学院（Battelle Memorial Institute）检验，被证实能够完整保留生物细胞活性，而受到整型外科与皮肤科医师的认同后，更被医学美容界赞誉为缔造肌肤保养新纪元的划世纪产品。安全无虑的生物医学品管科技，为了确保百分之百的高品质，瑞妍活细胞生化实验中心采用严格医药产品标准的一致的制造及品管标准。原料的准备完全在无菌环境中完成，比照医院手术室，所有的器具必须经过消毒、作业皆在尘流控管下完成，生物活性细胞分子原料，均分别经瑞士四所实验室的细胞学及微生物学测试通过。 所有的成品经临床皮肤医学测试，通过瑞士联邦卫生署及欧美权威单位检验证明，经官方归类为低敏原（Hypo-Allergenic）保养品，堪称是化妆品界一大突破。延缓肌肤老化的生物活性配方，瑞妍生物活性细胞护肤系列独特的高渗透性乳霜配方，深获专家们一致推崇为最佳配方；为免破坏产品中所含珍贵生物活性细胞分子，均不添加任何色素或酒精，淡雅的清香全部来自天然植物香精油。</p>'),
(25, 25, 103, '丝维诗兰', 'www.swissline-cosmetics.com', 1, '<p>\r\n﻿  Swissline（丝维诗兰）由俄罗斯王子Michael Massalsky 于1994年在瑞士创立。他曾担任国际著名化妆品牌的副总裁，并创造了&ldquo;鱼子精华&rdquo;的神话，被誉为&ldquo;鱼子精华之父&rdquo;。<br />\r\n﻿  <br />\r\n﻿  Michael Massalsky召集了一批世界顶尖的科学家，致力于高科技护肤保养品的开发与研究。他倾其所有精力与时间，创建了一个梦想中的美丽国度，送给全世界女性朋友一份珍贵的礼物&mdash;&mdash;&ldquo;Swissline&rdquo;，它是最昂贵、最具疗效的护肤品之一，被称为尖端护肤品市场的&ldquo;劳斯莱斯&rdquo;。<br />\r\n﻿  <br />\r\n﻿  Swissline 护肤品结合了数百种珍贵植物萃取精华，运用生化科技结合成为接近人体肌肤细胞的成份&mdash;&mdash;活细胞再生元素 CELLACTEL 2 取代原有的活细胞，为细胞美容科学上一大突破。</p>\r\n'),
(26, 26, 103, '哲·碧卡狄', 'www.zbigatti.com', 1, '<p>\r\n﻿  Z.Bigatti「哲&middot;碧卡狄」率先推出的护肤产品是Re-Storation(修复)系列的青春复颜霜，在当今世界护肤品工业中，&quot;三合一&quot; 青春复颜霜是对消费者多步骤繁琐护肤习惯的第一位革命性创举者。Re-Storation(修复) 系列产品采用多重保湿酸因子，抗氧化剂，多种维生素和酶等原料，通过独特配方精心为适合各类肌肤调制而成，令其拥有年轻而美丽的肌肤。她是最具效力，最奢华和最有益的青春复颜霜。当我们的&quot;三合一&quot;新概念青春复颜霜一经推荐给深感日常护肤烦琐的消费者，瞬既获得了空前的成功。</p>\r\n<p>\r\n﻿  Z.Bigatti「哲&middot;碧卡狄」在其所有的护肤系列产品中，采用了众多有益的活性原料，使用简便，与此同时，雅致，奢华而不含香精，适合于各类皮肤和年龄的消费者。今天，作为世界上最尊贵，最独特和最令人渴望的护肤品品牌之一，Z.Bigatti「哲&middot;碧卡狄」继续为各类不同皮肤和年龄的消费者提供全方面产品及服务。Z.Bigatti「哲&middot;碧卡狄」倾注于护肤科学，致力于顾客的需求并满足她们的需求。</p>\r\n<p>\r\n﻿  目前，Z.Bigatti「哲&middot;碧卡狄」品牌有20余种护肤产品，本系列产品将继续吸引更多的忠诚渴望这一奢华尊贵品牌的消费者。Z.Bigatti「哲&middot;碧卡狄」品牌的产品正在从护肤产品系列扩展到包括护发，沐浴和个人护理产品等系列，继续为那些对其肌肤，头发和身体的呵护寻求卓效而尊贵之产品的消费者提供多重的选择。</p>\r\n<p>\r\n﻿  Z.Bigatti「哲&middot;碧卡狄」产品遍布于世界各地的顶级百货公司，美容院，精品店和药剂师房。Z.Bigatti「哲&middot;碧卡狄」，作为一个国际品牌，在世界市场销售其产品。</p>'),
(27, 27, 103, '希思黎', 'www.sisley-cosmetics.com', 1, '<p>\r\n﻿  Sisley（希思黎）1976年诞生于法国，由法国贵族修伯特.多纳诺伯爵创立。Sisley是以当时欧洲最新的植物美容学为基础，而创立的植物性护肤品牌，在全球享有盛誉。<br />\r\n﻿  <br />\r\n﻿  Sisley的所有产品均以植物萃取精华与植物香精油作为主要成分，配以独特配方研制而成，并坚持产品在上市前必须经过300名皮肤科医师的测试，包装上必须名符其实地被许可标示&ldquo;经过敏与敏感测试&rdquo;及出自法国巴黎原厂。所以，Sisley产品带给肌肤自然、安全、有效的承诺，不但成为敏感性、过敏性肌肤所衷心信赖的化妆品，其口碑也令Sisley成为欧美上流社会钟爱的贵族化品牌。<br />\r\n﻿  <br />\r\n﻿  Sisley的植物来源也务求来自最佳产地及最佳采收时节的优良品质。例如蕃茄精华采自西伯利亚即将冻结前的蕃茄叶。人参采用顶级韩国人参。玫瑰花瓣则采自保加利亚玫瑰。薰衣草更来自八百米高山以上。所以植物萃取都经测试检定，确保不含农药、没有污染、不含铅、并采自最佳的季节。<br />\r\n﻿  <br />\r\n﻿  英国安妮公主、黛安娜王妃、美国前总统肯尼迪的夫人杰奎琳等，都是希思黎的忠实顾客。<br />\r\n﻿  <br />\r\n﻿  Sisley 于2001年正式进入中国市场。</p>'),
(28, 28, 103, '幽兰', 'www.orlane.com', 1, '<p>\r\n﻿  Orlane (幽兰)1946年在法国创立，至今已有超过60年的历史和行业经验。<br />\r\n﻿  <br />\r\n﻿  源于法国的幽兰，早已被认定为最可靠及最见效的护肤品牌，信誉及口碑已逐渐迈向世界每一角落。于1985年起，幽兰业务更由意大利著名化妆品集团Kelemata Group接替，开始策略性地作全球发展。到目前为止，已有120个国家经销幽兰产品，并超过5000个销售点。<br />\r\n﻿  <br />\r\n﻿  1966年幽兰于巴黎Victor Hugo大道，设立了首间专业美颜中心。<br />\r\n﻿  <br />\r\n﻿  1968年幽兰推出的B21面霜，是全球第一个有抗衰老功能的面霜，它含有生物化学成分，有活化肌肤的功能。由于B21面霜的原料来价高，制作及配方复杂，所以价格十分昂贵，现在约900欧元，相等于当时一个月的薪金；但由于这个面霜的功效实在显著，因此当时欧洲的每位女性都希望拥有B21面霜。幽兰这个品牌亦因B21面霜闻名于世。<br />\r\n﻿  <br />\r\n﻿  1975年Mr.D&#39;Ornano退休，把幽兰交给一家美国集团管理。<br />\r\n﻿  <br />\r\n﻿  1985年幽兰正式加盟意大利著名化妆集团Kelemate。并推出B21活肤系列。<br />\r\n﻿  <br />\r\n﻿  1990年因肌肤的7个基本需要而研制推出更多种类的B21护肤产品。<br />\r\n﻿  <br />\r\n﻿  2001年法国幽兰美容中心，香港铜锣总店开幕。<br />\r\n﻿  <br />\r\n﻿  2002年法国幽兰于杭州开始第一美颜中心。</p>'),
(29, 29, 103, '兰蔻', 'www.lancome-usa.com', 1, '<p>\r\n﻿  Lancome (兰寇)是法国欧莱雅集团旗下品牌，诞生于1935年。<br />\r\n﻿  <br />\r\n﻿  创始人Armand Petitjean是一个香料和化妆品专家，他凭借着对香水的天才敏感嗅觉、执著不懈的冒险精神，为世界化妆品历史写下了精彩的一页。<br />\r\n﻿  <br />\r\n﻿  兰蔻通过不断的科学研究实现了女人们的梦想&mdash;&mdash;对持久美丽的追求。1936年，兰蔻发布了著名的护肤系列Nutrix,这一产品的滋养和活力配方被沿用至今，经久不衰。之后的1955年，兰蔻推出了一款新的护肤产品系列Oceane line，该系列产品蕴含极致纯净的海藻精华，这种独一无二的创新科技使得兰蔻品牌的科研声誉传遍全球。<br />\r\n﻿  <br />\r\n﻿  无论是香水、护肤品或者化妆品，兰寇都尽力的以科学方法研制新产品，他们拥有自己的研发中心 (Research &amp; Development) ，并分别在亚洲、美洲和欧洲雇用了超过二千七百名专家从事研发 ，始终不是一般美容品牌可比。<br />\r\n﻿  <br />\r\n﻿  1993年初，兰蔻正式在中国大型高档百货商店设立专柜，并拥有一批经过专业培训的美容顾问。1996年，兰蔻率先在上海成立了在中国的第一个专业美容护肤中心（位于巴黎春天）以及设备完善的美容培训中心。</p>'),
(30, 30, 103, '雅诗·兰黛', 'www.esteelauder.com', 1, '<p>\r\n﻿  雅诗兰黛于1946年在美国创立，其护肤品众多，清洁类大都以肤质分类，油/混合/干/敏感等，十分明确；保养以功效分类，适合交叉互叠，美白/修护/保湿/抗衰老等。品质细腻上乘，极淡香，散发优雅气质又安全度高。近年在彩妆上有大肆改良，保留原有的斯文温婉色系，添人娇媚闪亮新色与靓丽包装，益发动人。<br />\r\n﻿  <br />\r\n﻿  于1910年出生并成长于纽约的Estee Lauder，天生便和美丽结下不解之缘，令人既羡且妒，不但拥有女性梦寐以求的高贵亮丽的外表，古典精致的五官，而且还有极具创意的生意头脑，其卓越的领导才能及果断利落的处事作风，更叫不少男士望尘莫及。<br />\r\n﻿  <br />\r\n﻿  在很小的时候，她就受到当皮肤科医生的叔叔的影响，希望长大后也能成为一位出色的皮肤科医生。这理想，令Estee Lauder对于皮肤的健康问题异常关注，也对其日后的美容事业产生了关键性的影响。<br />\r\n﻿  <br />\r\n﻿  Estee Lauder公司的第一个实验室是由位于纽约郊外的一个小储藏室改造而成的。而她的第一个办公室，就是家中的餐厅。经过一次次试验，对产品百余次的推敲，终于由她当皮肤科医生的叔叔配制出了，清洁油、润肤露、泥浆面膜和著名的All-Purose Creme四款产品，产品虽然不多，但良好的品质及功效，使美容专栏作家、经销商及消费者都赞不绝口。就这样，Estee Lauder迈出了成功的第一步。接下来，雅诗兰黛开始为产品销售终日奔波。为在纽约第五大道的Saks开第一个专柜，她每天都去该店要求见总经理，直到有一天，该店的总经理说：你又来了，那就进来吧。她强调说只需要10分钟的时间介绍产品，并用产品给该店的总经理试用。10分钟之后，总经理被产品的性能和她契而不舍的精神所打动，最终在纽约第五大道的Saks开设了Estee Lauder的起家专柜。<br />\r\n﻿  <br />\r\n﻿  由于对自己产品的品质及效用深认不疑，Est&eacute;e经常亲自带着产品到处推销。无论是发型屋还是百货公司柜台，她都热心地拉着顾客试用其产品，因为她认为，只要产品能接触到顾客便已成功了一半。除此之外，Estee Lauder更开创了向顾客派发试用产品的先河，结果产品的销售出奇的理想，而这种市场策略也为其他公司所纷纷效仿。<br />\r\n﻿  <br />\r\n﻿  她凭着敏锐的市场触觉及创意十足的商业头脑，使Estee Lauder公司在20世纪50年代营业额已达到每年80万美坏分子，这在当时是一个十分惊人的数字。公司业绩十分理想而且不断稳步上扬，但Estee Lauder却从不放松对产品及服务素质的要求。她十分注重员工的服务态度，深信亲切热情的态度是成功销售的关键。所以，她常常亲自飞到各地的化妆品专柜或专卖店巡视业务，更亲自教授员工销售技巧，她常对员工亲切地说：&ldquo;让我教你怎样向客人介绍产品吧！她认真和务实的处事作风正好贯彻执行了她&ldquo;大胆想象，切实执行&rdquo;的座右铭。<br />\r\n﻿  <br />\r\n﻿  为了解决过敏性皮肤女性的美容难题，雅诗兰黛公司1968年开发出经医学过敏性测试的化妆品品牌，即众所周知的Clinique&mdash;&mdash;倩碧。1990年，为了适应全球环保潮流，成立了Origin有限公司，该公司研制的产吕，强调纯天然的植物配方，不经动物实验，所有包装皆可循环使用。在这里，美被赋予了更自然、健康的涵义。</p>'),
(31, 31, 103, '娇韵诗', 'www.clarins.com', 1, '<p>\r\n﻿  Clarins 创立于1954年，是法国权威的护肤品牌，是致力于研究天然植物的专家。在欧洲市场，娇韵诗持续高居首位。<br />\r\n﻿  <br />\r\n﻿  娇韵诗产品研发精神，是以植物性成分为主要诉求。事实上，娇韵诗所研发推出的第一瓶产品，正是植物精油。而所有产品的研发，必定是以植物性精华为主要有效成分。而且，在该品牌发给主顾客的护肤美容手册中，也必然明列每一项产品所含的植物性成分，以及该成分的效用。<br />\r\n﻿  <br />\r\n﻿  相对于大多数化妆品以市场行销导向为主的经营方式，特别强调正确美容知识传递的娇韵诗，特别重视站在第一线，肩负传递美容知识重任的专业美容顾问。在娇韵诗巴黎总公司的美容训练部门，是所有部门当中，编制最大、人数最多的部门。<br />\r\n﻿  <br />\r\n﻿  此外，娇韵诗在1987年推出的保养香水香醍露，也是以保养性、两性都可使用为诉求。这种保养性的香水概念，最近在市场上蔚为一股风潮，许多品牌也竞相推出不含酒精的保养性香水，而首开这股香水保养化风气之先的，正是娇韵诗。</p>'),
(32, 32, 103, '帕尔马', 'www.acquadiparma.it', 1, '<p>\r\n﻿  帕尔玛是来自意大利 Parma 市的香水品牌，它成立于1916年，目前归属于 LVMH 集团旗下。<br />\r\n﻿  <br />\r\n﻿  它以独特的西西里柑橘、熏衣草、迷迭香、马鞭草与保加利亚玫瑰，等天然植物为香料，让高雅的香气扑满全身。<br />\r\n﻿  <br />\r\n﻿  自上世纪五十年代起，Acqua Di Parma 就备受影视明星们的青睐，汉弗莱.博加特（Humphrey Bogart）﹑加利.格兰特（Cary Grant）和艾娃.嘉德纳(Ava Gardner)都曾使用该产品。今天，帕尔玛已拥有一批忠实的追随者，其中既包括蜚声全球的名人又包括著名的观点引领者。<br />\r\n﻿  <br />\r\n﻿  1993年，这一家族企业被 Diego Della Valle﹑ Montezemolo 和 Paolo Borgomanero 收购，他们分别是 Tod&#39;s﹑ 法拉利和La Perla公司的创始人或总裁。三位收购人致力于通过推出新的产品系列，从而扩展帕尔玛的业务范围：包括家居香氛（薰香，蜡烛）﹑家居系列（家用纺织品和毛巾）﹑旅行系列（皮革配饰）以及地中海香薰和护肤品系列 Blu Mediterraneo 等。<br />\r\n﻿  <br />\r\n﻿  2001年10月，LVMH 集团收购了 Acqua Di Parma 50%的资本，2003年9月又收购了余下的50%的资本，至此，便完全拥有了 Acqua Di Parma 公司。</p>'),
(33, 33, 103, '贝玲妃', 'www.benefitcosmetics.com', 1, '<p>\r\n﻿  BeneFit 的创始人，是一对生长于美国印地安那州的双胞胎姐妹。<br />\r\n﻿  <br />\r\n﻿  Benefit 吸引人以及创新之处，在于它是第一个针对肌肤美容问题提出解决之道的品牌。同时，也致力于将趣味创意融入产品之中，而产品的强项是平凡人最需要的修饰性系列，以上这三个元素成就了Benefit 在化妆品产业的专业地位与销售佳绩。<br />\r\n﻿  Fake～it 假装系列产品，各个都是 Benefit 的招牌货，可以修饰脸上各种不同的瑕疵。像 lip plump 嘟嘟嘴丰唇底膏，就标榜可以让水水瞬间变身成安洁莉娜裘莉；ooh la lift 向上提升眼蜜，可以立刻收起浮肿的眼袋和眼纹，美化眼周肌肤；boi-ing 无懈可击遮瑕膏，能在转眼间赶走恼人的黑眼圈，难怪备受专业彩妆师的一致推祟。<br />\r\n﻿  <br />\r\n﻿  其它特别的品项，还有 benetint 红粉菲菲唇颊霜，它是该品牌畅销全球的冠军商品。它可以用在脸和唇部，创造出如同玫瑰花般娇羞可人的脸蛋，也有不少用过的人说抹上之后，看起来很像刚运动完的自然红晕，很能展现天真无邪的诱人魅力；而 Dr. feelgood 油不得你毛孔隐形膏，它是全球热卖第二名的发烧货，最妙之处是可以修掉毛孔粗大问题，很适合在亚洲湿热的天气型态下使用，油美眉可少它不了；至于有数不清的名人、彩妆师一致大力推荐的 dandelion 蒲公英蜜粉盒，它可是被全美网友票选出来最漂亮的粉红色，号称可以为毫无生气的脸庞瞬间焕然一新，而且它很适合当腮红局部使用，打造出如自然透出红晕的苹果脸好气色。</p>'),
(34, 34, 103, '欧莱雅', 'www.lorealparis.com', 1, '<p>\r\n﻿  巴黎欧莱雅集团是世界著名的化妆品&nbsp; 欧莱雅专柜生产厂商,创立于1909年。现在的各类化妆品畅销全世界,广受欢迎。除化妆品以外，该集团还经营高档的消费品，并从事制药和皮肤病研究。</p>\r\n<p>\r\n﻿  化妆品、染发用具、护肤品、防晒用品、彩妆、淡香水和香水、皮肤病研究、制药，高档消费品。</p>\r\n<p>\r\n﻿  品牌： L&#39;Oreal - Maybelline - Lancome - Drakkar Dynamik - Retinol Re-Pulp (BIOTHERM) - Neutralia - Color Riche - Reverie - Sublime Finish - Rouge Chromatic - VICHY - KERASTASE</p>\r\n<p>\r\n﻿  1996年欧莱雅集团(L&rsquo;Or&eacute;al)收购美宝莲。该举动宣告了科技创新将与彩妆权威更完美的溶合在一起。6月，美宝莲由曼斐斯迁至世界时尚之都纽约。美宝莲纽约诞生了！以突破性的专利技术，美宝莲公司推出了新开发的妍彩系列（Great Wear）产品，包括：唇部彩妆、眼部彩妆及遮瑕产品。</p>\r\n<p>\r\n﻿  欧莱雅在中国的商务始于1966年设立在香港的经销处。事实上，该公司1933年就曾对广州、上海、北京等大城市进行过市场调查。</p>\r\n<p>\r\n﻿  1996年，欧莱雅公司和苏州医学院合作成立了苏州欧莱雅有限公司,同年又在苏州建立了第一家化妆品生产厂家,专门生产美宝莲(Maybelline)系列产品。两年后,第二家生产厂家在苏州建立,生产巴黎欧莱雅(L&#39;Oreal Paris)系列产品。</p>\r\n<p>\r\n﻿  1997年，欧莱雅公司在上海创办了中国总代表处,负责在中国经销欧莱雅公司各类产品,目前已在50多个城市开办了几百个销售点。</p>\r\n<p>\r\n﻿  2003年12月10日，欧莱雅中国以一个对外保密的价格，全资拿下了与之谈判4年的&ldquo;小护士&rdquo;品牌。所获包括&ldquo;小护士&rdquo;品牌、除了创始人李志达之外的所有管理团队、所有销售网点以及位于湖北省宜昌一生产基地等。欧莱雅的中国此举，是为了借助一个我国本土成熟低端品牌，完善其在中国竭力打造的品牌金字塔的塔基部分。</p>\r\n<p>\r\n﻿  时间2004年1月26日下午，欧莱雅集团宣布已经和科蒂集团签定协议，收购其旗下的品牌羽西。</p>\r\n<p>\r\n﻿  欧莱雅公司邀请了华裔电影明星巩俐作为其在大中华区的形象代表，成功打开中国市场。</p>'),
(35, 35, 103, '美宝莲', 'www.maybelline.com', 1, '<p>\r\n﻿  世界90多个国家及城市中，美宝莲纽约已经成为第一个针对女性生产化妆及护肤品的公司。至今已经有了95年品牌历史。</p>\r\n<p>\r\n﻿  1915年，公司创始人T.L.Williams将凡士林与煤粉混合，涂在他妹妹的眼睫毛上，使睫毛看上去更黑更浓密，就这样，世界上第一支意义上的睫毛膏诞生了。</p>\r\n<p>\r\n﻿  1997年，美宝莲纽约正式在中国面向女性销售化妆品。</p>\r\n<p>\r\n﻿  作为中国第一个化妆品品牌，与其他品牌相比，她拥有更多种类和更广泛的拥护人群。</p>\r\n<p>\r\n﻿  美宝莲纽约的产品非常丰富，从粉底、遮瑕膏、睫毛膏、眼影、眼线、唇彩、唇线、唇膏、指甲油、卸妆水到保湿霜、润肤露，应有尽有。</p>\r\n<p>\r\n﻿  美宝莲纽约同时也是世界知名的时尚领军人物。世界名模克里斯蒂特灵顿(Christy Turlington)，中国的Vogue封面女郎王雯琴(Anna Wang)，都是美宝莲纽约的品牌代言人。此外，美宝莲纽约同时与中国影星章子怡、紧密合作。近期，美宝莲纽约还与快乐女生冠军江映蓉签约，向中国地区推广一个以&ldquo;把握属于你的美（Power is in your hand）&rdquo;为主题的广告活动，向中国女性推广，&ldquo;简单4部打造自然美。&rdquo;</p>\r\n<p>\r\n﻿  美宝莲纽约在中国出售的纯矿物油粉底销量雄踞第一，并建立第一条生产帮助改善皮肤的矿物粉底生产线。</p>\r\n<p>\r\n﻿  美宝莲纽约在纽约成立了城市肌肤研究所，旨在研究城市居住环境对女性肌肤的影响，同时针对城市肌肤，生产一系列将以种籽为配方的护肤产品，从大自然探寻到肌肤的净化力量，让肌肤保持自然美丽。</p>\r\n<p>\r\n﻿  美宝莲纽约的口号是：&ldquo;Maybe she&rsquo;s born with it, maybe it&rsquo;s Maybelline&rdquo;&mdash;&mdash;美来自内心，美来自美宝莲。</p>\r\n<p>\r\n﻿  美宝莲纽约市公认的彩妆及美容时尚品牌，是纽约时装周以及其他大型时尚秀指定的彩妆产品美宝莲纽约还与来自中国的谭玉燕(Vivienne Tam)进行了紧密合作。</p>'),
(36, 36, 103, '海蓝之谜', 'www.cremedelamer.com', 1, '<p>\r\n﻿  La Mer（海蓝之谜）是雅诗兰黛集团旗下的品牌。<br />\r\n﻿  <br />\r\n﻿  一直以来被爱用者秘密珍传, La Mer海蓝之谜面霜与&quot;护肤奇迹&quot;和 &quot;极度奢华&quot;同义。 自太空物理学家麦克斯.贺伯四十年前发明它以来, 海蓝之谜面霜和它那营养丰富的神奇活性精粹就只能用 &quot; 传奇&quot; 二字形容。 现在, 海蓝之谜面霜有了一个系列的神奇护肤产品加入, 它们协同发挥功效, 令肌肤恢复健康和平衡。<br />\r\n﻿  <br />\r\n﻿  在美国最有名的Saks Fifth Avenue 百货店及伦敦的Harrod&#39;s, La Mer 都排名第一。奇迹直到现在，我们仍然无法用逻辑而科学的思考方式或合理的说法来解释&ldquo;海蓝之谜&rdquo;的神奇效果。然而，事实终究是事实，每张使用过面霜的脸庞就是最好的证明。使用后，肌肤马上能明显变得柔嫩紧实、细纹减少。较深的皱纹与粗大毛孔也能轻易获得改善。甚至最干燥及过敏性肌肤也能回复到健康自然的状态。当然，更有一些使用者像贺伯博士一样，体验到了惊人的修护效果。难怪他们遍试任何其他护肤品， 最后仍会成为La Mer海蓝之谜的永远追随者。</p>'),
(37, 37, 103, '雅芳', 'www.avon.com', 1, '<p>\r\n﻿  美国雅芳产品有限公司(AVON Products, Inc.)是全美500强企业之一，1886年创立于美国纽约。作为世界领先的美容化妆品及相关产品的直销公司，雅芳的产品包括著名的雅芳色彩系列、雅芳新活系列、雅芳柔肤系列、雅芳肌肤管理系列、维亮专业美发系列、雅芳草本家族系列、雅芳健康产&nbsp; 雅芳分店品和全新品牌Mark系列，以及种类繁多的流行珠宝饰品。</p>\r\n<p>\r\n﻿  始终引领世界最新潮流的国际美容巨子，全美500家最有实力的企业之一，一家属于女性的公司。雅芳深信，女性的进步和成功，就是雅芳的进步和成就。</p>\r\n<p>\r\n﻿  雅芳于1990年进入中国。雅芳（中国）有限公司现有74家分公司，覆盖国内23个省、5个自治区及4个直辖市，拥有雇员约2000人。位于广州的雅芳生产基地，累计投资超过6000多万美元，于1998年正式投入使用。雅芳（中国）有限公司为中国女性提供数百种各类产品，包括护肤品、彩妆品、个人护理品、香品、流行饰品、时尚内衣和健康食品等。1998年转型后，中国雅芳严格遵从政府要求，通过专卖店与专柜等零售渠道进行产品销售，转型成为零售业的经营模式。</p>'),
(38, 38, 103, '雅蔻', 'www.artdeco.de', 1, '<p>\r\n﻿  雅蔻ARTDECO创立于1985年，发源于最浪漫的欧洲，总部位于德国、设计中心位于巴黎，R&amp;D设立于意大利，创立至今23年，目前在全球64个国家行销，销量遥遥领先其他知名化妆品牌，占德国化妆品市场17%以上，为德国彩妆第一领导品牌。</p>\r\n<p>\r\n﻿  ARTDECO创始人Mr.Helmut Baurecht热爱艺术和时尚，尤其是1920-1930年间的ARTDECO艺术风格(ART代表艺术，DECO源自DECORATION，代表装饰、美化)。Mr.Helmut Baurecht将对ART DECO艺术的热爱，化为对彩妆时尚概念的延伸，不但以此时期的艺术为名，同时也对产品设计概念影响深远，至今，ARTDECO已成为欧洲艺术、时尚、女人之美的代名词！&nbsp; 2010夏季&ldquo;狩猎风情&rdquo;-彩妆ARTDECO秉承德国一丝不苟、追求完美的风格，注重品质，不断创新，多项产品曾荣获世界专利，并在多次世界彩妆大赛中荣得桂冠。现在，ARTDECO已成为欧洲各大商场和美容沙龙界最受的推崇的彩妆品牌。</p>\r\n<p>\r\n﻿  ARTDECO拥有千余个单品，几十个系列产品。系列产品有脸部彩妆、唇部彩妆、眼部彩妆、指甲彩妆、指甲护理、手部护理、足部护理、基础保养、深海鱼子、柔滑蚕丝、亮彩耀眼、时尚睫毛等系列，产品种类齐全，全方位的满足女性需要。ARTDECO每年春夏、秋冬均会举办一场大型流行彩妆发布会，ARTDECO每一次的产品发布，都广为欧洲时尚界期盼与惊艳。</p>'),
(39, 39, 103, '娇兰', 'www.guerlain.com', 1, '<p>\r\n﻿  娇兰创建于1828年，180多年来，娇兰共推出300余种香水，在法国几乎成为了香水代名词，并且在全球高档化妆品市场上也保持着至高声誉。娇兰1828年由法国药剂师 Pascal Guerlain 在巴黎创建。由于品质优异，娇兰很快风靡巴黎上流社会，并获得欧洲王室的青睐。<br />\r\n﻿  <br />\r\n﻿  娇兰所创制的香水几乎全部出自家庭成员之手。他们认为，好的品牌要延续传统，必须亲力亲为，就连挑选材料，他们也从不假手他人。在不断创造香水传奇的同时，娇兰还加强了护肤美容产品的开发和制作，各种护肤液、洁面奶、粉底、面霜等产品陆续面世。<br />\r\n﻿  <br />\r\n﻿  1980年，娇兰推出了被称为&ldquo;最奢华产品线&rdquo;的伊诗美系列护肤品。1984年，娇兰推出了阳光气息极浓的&ldquo;古铜系列&rdquo;粉底。1987年，娇兰凭借风格独特的&ldquo;幻彩流星&rdquo;彩妆系列再次震撼整个化妆品行业。1991 年，娇兰创造了&ldquo;流金系列&rdquo;，并大获成功。1994年后，娇兰开始涉足高级女士浴室用品及旅行用品。它的丝巾、化妆袋、真皮旅行袋、丝质浴袍、玻璃器皿、护发化妆工具等，全部产自法国本土，品质卓越。</p>');
INSERT INTO `fo_brand` (`id`, `spc_id`, `cid`, `name`, `official_url`, `status`, `content`) VALUES
(40, 40, 103, '伊丽莎白·雅顿', 'www.elizabetharden.com', 1, '<p>\r\n﻿  创始人 Elizabeth Arden ，是生于多伦多的加拿大籍美国人。有英国血统的她，曾在纽约为化妆品企业工作。<br />\r\n﻿  1910年她在美国第五大道开设了自己的美容院，从此开始她成功的职业生涯。<br />\r\n﻿  <br />\r\n﻿  1932年，她推出了第一个系列的各色口红，那时在全球她已经拥有了29家店面。她开始以雅顿小姐闻名，有完美主义的性格，不屈不挠，对细节的要求很高。她在1966年以83岁的高龄逝世，生前得到过英国女王和王太后的皇室嘉奖。刚开始的时候，她卖的是别人生产的香水，第一款自己配制的香水大约在1922年出品。<br />\r\n﻿  <br />\r\n﻿  1989年，Elizabeth Arden 被联合利华公司收购。现在该公司拥有两个生产香水的子公司：Elizabeth Arden公司，从事原来的业务；国际香氛公司(Parfums International)，从事新出品的香水业务。除了&ldquo;青青芳草&rdquo;以外，伊丽莎白.雅顿自己的香水系列里还包括&ldquo;红门&rdquo;(Red Door)，是一款花香型的提纯香，香水瓶的盖子采用明亮的标志性红色，在重新推出的时候用了名模琳达.伊万格丽斯塔(Linda Evangelista)做形象代言人。</p>');

-- --------------------------------------------------------

--
-- 表的结构 `fo_category`
--

CREATE TABLE IF NOT EXISTS `fo_category` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(10) unsigned NOT NULL DEFAULT '0' COMMENT '父分类',
  `type` varchar(32) NOT NULL DEFAULT '1' COMMENT '分类类型:news-文章分类,special-专题,corporation-商家分类,link-链接分类',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名',
  `path` varchar(100) NOT NULL DEFAULT '/' COMMENT '分类层次递进关系',
  `status` tinyint(4) NOT NULL COMMENT '分类状态:1-正常,-1-删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='分类表' AUTO_INCREMENT=10013 ;

--
-- 转存表中的数据 `fo_category`
--

INSERT INTO `fo_category` (`id`, `pid`, `type`, `name`, `path`, `status`) VALUES
(1, 0, 'news', '公告', '/1/', 1),
(2, 0, 'news', 'FAQ', '/2/', 1),
(3, 0, 'news', '综合新闻', '/3/', 1),
(101, 0, 'brand', '服饰、鞋帽、箱包', '/101/', 1),
(102, 0, 'brand', '珠宝、钟表', '/102/', 1),
(103, 0, 'brand', '美容化妆', '/103/', 1),
(104, 0, 'brand', '汽车、游艇、私人飞机', '/104/', 1),
(105, 0, 'brand', '家居建材', '/105/', 1),
(106, 0, 'brand', '运动健康、户外', '/106/', 1),
(107, 0, 'brand', '数码、家电', '/107/', 1),
(108, 0, 'brand', '食品饮料、保健品', '/108/', 1),
(109, 0, 'brand', '礼品、饰品、用具', '/109/', 1),
(110, 0, 'brand', '收藏品', '/11/', 1),
(111, 0, 'brand', '名烟、名酒', '/11/', 1),
(201, 101, 'brand', '男装', '/101/201/', 1),
(202, 101, 'brand', '女装', '/101/202/', 1),
(203, 101, 'brand', '运动装', '/101/203/', 1),
(204, 101, 'brand', '内衣', '/101/204/', 1),
(205, 101, 'brand', '配饰', '/101/205/', 1),
(211, 201, 'brand', 'T恤', '/101/201/211/', 1),
(212, 201, 'brand', '衬衫', '/101/201/212/', 1),
(213, 201, 'brand', 'POLO', '/101/201/213/', 1),
(214, 201, 'brand', '外套', '/101/201/214/', 1),
(215, 201, 'brand', '短裤', '/101/201/215/', 1),
(216, 201, 'brand', '西装', '/101/201/216/', 1),
(221, 201, 'brand', 'T恤', '/101/202/221/', 1),
(222, 201, 'brand', '衬衫', '/101/202/222/', 1),
(223, 201, 'brand', '外套', '/101/201/223/', 1),
(224, 201, 'brand', '短裤', '/101/201/224/', 1),
(225, 201, 'brand', '裙子', '/101/201/225/', 1),
(226, 201, 'brand', '礼服', '/101/201/226/', 1),
(231, 201, 'brand', '围巾', '/101/205/231/', 1),
(232, 201, 'brand', '眼镜', '/101/205/232/', 1),
(233, 201, 'brand', '领带', '/101/205/233/', 1),
(234, 201, 'brand', '手套', '/101/205/234/', 1),
(235, 201, 'brand', '袖扣', '/101/205/235/', 1),
(236, 201, 'brand', '腰带', '/101/205/236/', 1),
(261, 101, 'brand', '鞋靴', '/101/261/', 1),
(262, 101, 'brand', '帽子', '/101/262/', 1),
(263, 261, 'brand', '男鞋', '/101/261/263/', 1),
(264, 261, 'brand', '女鞋', '/101/261/264/', 1),
(265, 261, 'brand', '儿童', '/101/261/265/', 1),
(271, 262, 'brand', '男式', '/101/262/271/', 1),
(272, 262, 'brand', '女式', '/101/262/272/', 1),
(273, 262, 'brand', '儿童', '/101/262/273/', 1),
(281, 101, 'brand', '男包', '/101/281/', 1),
(282, 101, 'brand', '女包', '/101/282/', 1),
(283, 101, 'brand', '旅行箱包', '/101/283/', 1),
(301, 102, 'brand', '珠宝', '/102/301/', 1),
(302, 102, 'brand', '手表', '/102/302/', 1),
(303, 102, 'brand', '怀表', '/102/303/', 1),
(304, 102, 'brand', '挂钟', '/102/304/', 1),
(331, 103, 'brand', '护肤', '/103/331/', 1),
(341, 103, 'brand', '香水', '/103/341/', 1),
(351, 103, 'brand', '彩妆', '/103/351/', 1),
(371, 104, 'brand', '名车', '/104/371/', 1),
(372, 104, 'brand', '跑车', '/104/371/372/', 1),
(373, 104, 'brand', '轿车', '/104/371/373/', 1),
(374, 104, 'brand', '越野车', '/104/371/374/', 1),
(375, 104, 'brand', '赛车', '/104/371/375/', 1),
(376, 104, 'brand', '货车', '/104/371/376/', 1),
(377, 104, 'brand', '客车', '/104/371/377/', 1),
(378, 104, 'brand', '摩托车', '/104/371/378/', 1),
(379, 104, 'brand', '自行车', '/104/371/379/', 1),
(380, 104, 'brand', '电动自行车', '/104/371/380/', 1),
(391, 104, 'brand', '游艇', '/104/391/', 1),
(396, 104, 'brand', '私人飞机', '/104/396/', 1),
(401, 105, 'brand', '厨具', '/105/401/', 1),
(402, 105, 'brand', '餐具', '/105/402/', 1),
(403, 105, 'brand', '家具', '/105/403/', 1),
(404, 105, 'brand', '灯具', '/105/404/', 1),
(405, 105, 'brand', '家纺', '/105/405/', 1),
(406, 105, 'brand', '卫浴', '/105/406/', 1),
(421, 106, 'brand', '运动器械', '/106/421/', 1),
(422, 106, 'brand', '体育用品', '/106/422/', 1),
(423, 106, 'brand', '保健器械', '/106/423/', 1),
(441, 107, 'brand', '手机通讯', '/107/441/', 1),
(442, 107, 'brand', '笔记本', '/107/442/', 1),
(443, 107, 'brand', '平板电脑', '/107/443/', 1),
(444, 107, 'brand', '数码影像', '/107/444/', 1),
(445, 107, 'brand', '时尚影音', '/107/445/', 1),
(451, 107, 'brand', '大家电', '/107/451/', 1),
(452, 107, 'brand', '生活电器', '/107/452/', 1),
(453, 107, 'brand', '厨房电器', '/107/453/', 1),
(454, 107, 'brand', '健康电器', '/107/453/', 1),
(460, 107, 'brand', '护理保健', '/107/460/', 1),
(501, 108, 'brand', '葡萄酒', '/108/501/', 1),
(502, 108, 'brand', '白酒', '/108/502/', 1),
(503, 108, 'brand', '啤酒', '/108/503/', 1),
(504, 108, 'brand', '药酒', '/108/503/', 1),
(551, 109, 'brand', '烟具', '/109/551/', 1),
(552, 109, 'brand', '笔具', '/109/552/', 1),
(571, 110, 'brand', '钱币', '/110/571/', 1),
(572, 110, 'brand', '邮品', '/110/572/', 1),
(591, 111, 'brand', '名烟', '/111/591/', 1),
(592, 111, 'brand', '雪茄', '/111/592/', 1),
(593, 111, 'brand', '萄萄酒', '/111/593/', 1),
(594, 111, 'brand', '白酒', '/111/594/', 1),
(10001, 0, 'link', '友情链接', '/1002/', 1),
(10011, 0, 'special', '品牌专题', '/1001/', 1),
(10012, 0, 'special', '活动专题', '/1001/', 1);

-- --------------------------------------------------------

--
-- 表的结构 `fo_comments`
--

CREATE TABLE IF NOT EXISTS `fo_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(10) unsigned NOT NULL COMMENT '所属文章ID',
  `content` text NOT NULL COMMENT '评论内容',
  `username` varbinary(32) DEFAULT NULL COMMENT '评论用户',
  `qq` int(11) DEFAULT NULL COMMENT 'QQ',
  `email` varbinary(50) DEFAULT NULL COMMENT 'Email',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `posttime` int(11) NOT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论内容' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_comments`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_corp`
--

CREATE TABLE IF NOT EXISTS `fo_corp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL COMMENT '企业名称',
  `shortname` varchar(30) NOT NULL DEFAULT '' COMMENT '简称',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '企业的登录用户ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型:1-留学中介,2-留学培训学校',
  `person` varchar(32) NOT NULL DEFAULT '' COMMENT '法人代表',
  `province` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '省份,保存行政区划',
  `city` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市,保存行政区划',
  `county` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区/县,保存行政区划',
  `zipcode` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邮政编码',
  `address` varchar(32) NOT NULL DEFAULT '' COMMENT '企业联系地址',
  `phone1` varchar(32) NOT NULL DEFAULT '' COMMENT '企业联系电话1',
  `phone2` varchar(32) NOT NULL DEFAULT '' COMMENT '企业联系电话2',
  `phone3` varchar(32) NOT NULL DEFAULT '' COMMENT '企业联系电话3',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT 'email',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '公司网址',
  `staff_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '职员数量',
  `reg_capital` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '注册资本(单位万)',
  `cert_code` varchar(255) NOT NULL DEFAULT '' COMMENT '资格认定书编号',
  `cert_expire` int(10) NOT NULL DEFAULT '0' COMMENT '证书有效期',
  `cert_log` varchar(300) NOT NULL DEFAULT '' COMMENT '证书变更日期记录:时间戳\n时间戳(表示变更日期)',
  `intro` varchar(1000) NOT NULL DEFAULT '' COMMENT '简介',
  `found_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创办时间',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间(在网站注册信息)',
  `views` mediumint(9) NOT NULL DEFAULT '0' COMMENT '企业信息被查看次数',
  `today_views` mediumint(9) NOT NULL DEFAULT '0' COMMENT '企业信息当天被查看次数',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='公司/企业' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `fo_corp`
--

INSERT INTO `fo_corp` (`id`, `name`, `shortname`, `uid`, `type`, `person`, `province`, `city`, `county`, `zipcode`, `address`, `phone1`, `phone2`, `phone3`, `email`, `url`, `staff_num`, `reg_capital`, `cert_code`, `cert_expire`, `cert_log`, `intro`, `found_time`, `reg_time`, `views`, `today_views`, `status`) VALUES
(1, '福建省恒星出国留学服务中心', '恒星留学', 10001, 1, '陈南', 350000, 350100, 350102, 350001, '福建省福州市湖东路165号华闽大厦9层', '0591-87511183', '', '', 'dept@starstudying.com', 'http://www.starstudying.com/tel.asp', 0, 50, '教外综资认字【2000】57号', 1430928000, '2005年05月08日\r\n2009年4月28日\r\n2010年6月22日 ', '     福建省恒星出国留学服务中心是经国家公安部、教育部和国家工商行政管理局首批认定的公民出国留学中介服务机构之一。中心现与英国、爱尔兰、德国、荷兰、比利时、丹麦、加拿大、澳大利亚、新西兰、日本、新加坡、马来西亚等十余个国家的各类学校建立了良好的合作，同时与多个国家的驻华使领馆保持着友好关系。\r\n     中心建立了一支经验丰富、素质高的员工队伍，发展了一批有实力的海外合作伙伴，从而奠定了高质量服务的基础和高签证率的保障；至今已经为数百名学子实现了出国留学的梦想。\r\n\r\n【 服务范围 】\r\n     本中心为有志于赴海外留学发展的各个年龄段、各种学历、经济背景的人士提供以下服务：\r\n为您提供免费咨询评估；\r\n为您设计最佳留学途径；\r\n帮您选择学校、专业；\r\n帮您准备学校、签证申请材料；\r\n为您提供签证面试培训；\r\n为您安排学校住宿、接机；\r\n为您提供出发前培训； ', 1115481600, 1302599076, 13, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `fo_corp_type`
--

CREATE TABLE IF NOT EXISTS `fo_corp_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='公司/企业类型' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `fo_corp_type`
--

INSERT INTO `fo_corp_type` (`id`, `name`) VALUES
(1, '留学中介'),
(2, '留学培训学校'),
(3, '留学学校');

-- --------------------------------------------------------

--
-- 表的结构 `fo_goods_show`
--

CREATE TABLE IF NOT EXISTS `fo_goods_show` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `url` varchar(255) NOT NULL COMMENT '目标链接',
  `pic` varchar(255) NOT NULL COMMENT '图片',
  `price` mediumint(9) NOT NULL COMMENT '实际价格',
  `market_price` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '市场价/原价',
  `saller` varchar(60) NOT NULL DEFAULT '0' COMMENT '商家',
  `source` varchar(30) NOT NULL DEFAULT '0' COMMENT '来源网站',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品展示' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_goods_show`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_guestbook`
--

CREATE TABLE IF NOT EXISTS `fo_guestbook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '留言人',
  `content` varchar(10000) NOT NULL COMMENT '留言内容',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT 'EMAIL',
  `post_time` int(10) NOT NULL DEFAULT '0' COMMENT '留言时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言本' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_guestbook`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_link`
--

CREATE TABLE IF NOT EXISTS `fo_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` smallint(6) NOT NULL DEFAULT '1' COMMENT '所属分类ID',
  `name` varchar(100) NOT NULL COMMENT '链接名称',
  `url` varchar(150) NOT NULL COMMENT '链接目标URL',
  `note` varchar(1000) DEFAULT NULL COMMENT '链接简介',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  `bg_color` tinyint(4) NOT NULL DEFAULT '0' COMMENT '背景颜色',
  `font_color` tinyint(4) NOT NULL DEFAULT '0' COMMENT '字体颜色',
  `font_size` tinyint(4) NOT NULL DEFAULT '0' COMMENT '字体大小(像素)',
  `page` smallint(6) NOT NULL DEFAULT '0' COMMENT '显示的页面',
  `area` smallint(6) NOT NULL DEFAULT '0' COMMENT '显示页面中的区域',
  `pos` tinyint(4) NOT NULL DEFAULT '0' COMMENT '显示的页面中的位置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='链接' AUTO_INCREMENT=141 ;

--
-- 转存表中的数据 `fo_link`
--

INSERT INTO `fo_link` (`id`, `cid`, `name`, `url`, `note`, `status`, `bg_color`, `font_color`, `font_size`, `page`, `area`, `pos`) VALUES
(1, 10001, '闲逸寒舍', 'http://www.itblog.org/', '个人IT博客', 1, 1, 1, 0, 1, 2, 99),
(101, 302, '劳力士', '/html/r/rolex/index.html', '劳力士（Rolex）是瑞士著名的手表制造商', 1, 3, 1, 0, 1, 1, 1),
(102, 302, '欧米茄', '/html/o/omega/index.html', '欧米茄是国际著名制表企业和品牌。', 1, 3, 1, 0, 1, 1, 2),
(103, 302, '百达翡丽', '/html/p/patek/index.html', '没人能拥有百达翡丽，只不过为下一代保管而已。', 1, 3, 1, 0, 1, 1, 3),
(104, 302, '江诗丹顿', '/html/v/vacheron-constantin/index.html', '江诗丹顿是非凡创造的代名词', 1, 3, 1, 0, 1, 1, 4),
(105, 302, '爱彼', '/html/a/audemars-piguet/index.html', '世界十大名表之一', 1, 3, 1, 0, 1, 1, 5),
(106, 102, '卡地亚', '/html/c/cartier/index.html', '珠宝商的皇帝，帝皇的珠宝商', 1, 3, 1, 0, 1, 1, 6),
(107, 302, '万国', '/html/i/iwc/index.html', '身为卓越不凡的钟表工程师，万国表提供顶级的技术内涵、创新思维和品牌个性', 1, 3, 1, 0, 1, 1, 7),
(108, 102, '伯爵', '/html/p/piaget/index.html', '永远要做得比要求的更好', 1, 3, 1, 0, 1, 1, 8),
(109, 302, '积家', '/html/j/jaeger/index.html', '', 1, 3, 1, 0, 1, 1, 9),
(110, 302, '宝玑', '/html/b/breguet/index.html', '宝玑品牌不仅象征着非凡制表，它更是一种承载着历史和情感的文化传承。', 1, 3, 1, 0, 1, 1, 10),
(111, 101, '古驰', '/html/g/gucci/index.html', '意大利最大的时装集团', 1, 6, 1, 0, 1, 1, 11),
(112, 101, 'LV', '/html/l/lv/index.html', '', 1, 6, 1, 0, 1, 1, 12),
(113, 101, '香奈儿', '/html/c/chanel/index.html', '进入香奈儿的世界，发现最新时尚精品与配饰，香水及美容品，高级珠宝与腕表。', 1, 6, 1, 0, 1, 1, 13),
(114, 101, '爱马仕', '/html/h/hermes/index.html', '让所有的产品至精至美、无可挑剔，是Hermès的一贯宗旨。', 1, 6, 1, 0, 1, 1, 14),
(115, 101, '巴宝莉', '/html/b/burberry/index.html', '', 1, 6, 1, 0, 1, 1, 15),
(116, 101, '范思哲', '/html/v/versace/index.html', '希腊神话里的“蛇发女妖美杜莎”作为精神象征，代表着致命的吸引力。', 1, 6, 1, 0, 1, 1, 16),
(117, 404, '施华\\n洛世奇', '/html/s/swarovski/index.html', '世界上首屈一指的水晶制造商。', 1, 6, 1, 0, 1, 1, 17),
(118, 101, '普拉达', '/html/p/prada/index.html', 'Less is More', 1, 6, 1, 0, 1, 1, 18),
(119, 101, '阿玛尼', '/html/a/armani/index.html', '当你不知道要穿什么的时候，穿ARMANI就没错了！', 1, 6, 1, 0, 1, 1, 19),
(120, 101, '百利', '/html/b/bally/index.html', '缘起一个动人而美丽的故事缔造了百年的经典品牌', 1, 6, 1, 0, 1, 1, 20),
(121, 103, '资生堂', '/html/s/shiseido/index.html', '至哉坤元，万物资生', 1, 4, 1, 0, 1, 1, 21),
(122, 103, '法尔曼', '/html/v/valmont/index.html', '世界名媛贵妇最爱的五大奢华品牌', 1, 4, 1, 0, 1, 1, 22),
(123, 103, 'La Prairie', '/html/l/laprairie/index.html', '全球最昂贵的护肤品牌，欧洲王室贵族、名媛淑女们的挚爱。', 1, 4, 1, 0, 1, 1, 23),
(124, 103, '瑞妍', '/html/c/cellcosmet/index.html', '活细胞医学先驱', 1, 4, 1, 0, 1, 1, 24),
(125, 103, '丝维诗兰', '/html/s/swissline/index.html', ' 最自然的奢华', 1, 4, 1, 0, 1, 1, 25),
(126, 103, '哲·碧卡狄', '/html/z/zbigatti/index.html', '三合一" 青春复颜霜是对消费者多步骤繁琐护肤习惯的第一位革命性创举者', 1, 4, 1, 0, 1, 1, 26),
(127, 103, '希思黎', '/html/s/sisley/index.html', '护肤品中尊贵与优雅的经典代表', 1, 4, 1, 0, 1, 1, 27),
(128, 103, '幽兰', '/html/o/orlane/index.html', '法国贵族化的品牌', 1, 4, 1, 0, 1, 1, 28),
(129, 103, '兰蔻', '/html/l/lancome/index.html', '细腻、优雅、气质、非凡魅力', 1, 4, 1, 0, 1, 1, 29),
(130, 103, '雅诗·兰黛', '/html/E/estee-lauder/index.html', '全球最大的护肤、化妆品和香水公司', 1, 4, 1, 0, 1, 1, 30),
(131, 103, '娇韵诗', '/html/c/clarins/index.html', '功能性化妆品的第一品牌', 1, 5, 1, 0, 1, 1, 31),
(132, 103, '帕尔马', '/html/a/acquadiparma/index.html', '最早的古龙水', 1, 5, 1, 0, 1, 1, 32),
(133, 103, '贝玲妃', '/html/b/benefit/index.html', '精致以及有趣的产品、精确掌握流行的脉动', 1, 5, 1, 0, 1, 1, 33),
(134, 103, '欧莱雅', '/html/l/loreal/index.html', '知名度最高、历史最为悠久的大众化妆品品牌之一', 1, 5, 1, 0, 1, 1, 34),
(135, 103, '美宝莲', '/html/m/maybelline/index.html', '美来自内心，美来自美宝莲', 1, 5, 1, 0, 1, 1, 35),
(136, 103, '海蓝之谜', '/html/l/lamer/index.html', '雅诗兰黛集团旗下顶级化妆品品牌', 1, 5, 1, 0, 1, 1, 36),
(137, 103, '雅芳', '/html/a/avon/index.html', '一家属于女性的公司', 1, 5, 1, 0, 1, 1, 37),
(138, 103, '雅蔻', '/html/a/artdeco/index.html', '德国彩妆第一领导品牌', 1, 5, 1, 0, 1, 1, 38),
(139, 103, '娇兰', '/html/g/guerlain/index.html', '酩悦·轩尼诗－路易·威登集团旗下的产品', 1, 5, 1, 0, 1, 1, 39),
(140, 103, '伊丽莎白·雅顿', '/html/e/elizabetharden/index.html', '众香之巢,“美是自然和科学的结晶”', 1, 5, 1, 0, 1, 1, 40);

-- --------------------------------------------------------

--
-- 表的结构 `fo_media`
--

CREATE TABLE IF NOT EXISTS `fo_media` (
  `source_id` int(10) unsigned NOT NULL COMMENT '媒体的源ID,新闻等主键ID',
  `source_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '源类型:1-新闻,2-专题,3-链接,4-公司,5-用户',
  `local_file` varchar(255) NOT NULL COMMENT '本地硬盘的物理路径',
  `media_type` tinyint(4) NOT NULL COMMENT '媒体类型:1-图片,2-flash动画,3-视频,4-音频',
  `mime_type` varchar(50) NOT NULL COMMENT '媒体MIME类型',
  `size` int(10) NOT NULL COMMENT '媒体文件大小,单位字节',
  KEY `source_id` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `fo_media`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_news`
--

CREATE TABLE IF NOT EXISTS `fo_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布新闻的用户ID',
  `copy_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '新闻内容信息复制至哪条新闻，当同一新闻属于不同分类和专题时可用',
  `spc_id` smallint(10) unsigned NOT NULL DEFAULT '0' COMMENT '专题ID',
  `cid` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '分类ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `summary` varchar(255) NOT NULL DEFAULT '' COMMENT '摘要',
  `content` mediumtext NOT NULL COMMENT '内容',
  `author` varchar(60) NOT NULL DEFAULT '' COMMENT '作者',
  `editor` varchar(60) NOT NULL DEFAULT '' COMMENT '编辑',
  `from` varchar(100) NOT NULL DEFAULT '' COMMENT '来源',
  `from_url` varchar(255) NOT NULL DEFAULT '' COMMENT '来源地址',
  `views` mediumint(9) NOT NULL DEFAULT '0' COMMENT '查看数',
  `comments` mediumint(9) NOT NULL DEFAULT '0' COMMENT '评论数',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:-1-已删除;0-待审核;1-正常',
  `orderid` smallint(10) NOT NULL DEFAULT '0' COMMENT '排序ID',
  `has_image` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否包含图片:1-包含;0-不包含',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `post_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='新闻内容表' AUTO_INCREMENT=100001 ;

--
-- 转存表中的数据 `fo_news`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_page`
--

CREATE TABLE IF NOT EXISTS `fo_page` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `sign` varchar(30) NOT NULL COMMENT '页面标志',
  `name` varchar(30) NOT NULL COMMENT '页面名称',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='页面' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `fo_page`
--

INSERT INTO `fo_page` (`id`, `sign`, `name`) VALUES
(1, 'index', '首页');

-- --------------------------------------------------------

--
-- 表的结构 `fo_page_area`
--

CREATE TABLE IF NOT EXISTS `fo_page_area` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `page` smallint(6) NOT NULL COMMENT '页面ID',
  `sign` varchar(30) NOT NULL COMMENT '页面区域标志',
  `name` varchar(30) NOT NULL COMMENT '页面区域名称',
  PRIMARY KEY (`id`),
  KEY `page` (`page`),
  KEY `sign` (`sign`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='页面' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `fo_page_area`
--

INSERT INTO `fo_page_area` (`id`, `page`, `sign`, `name`) VALUES
(1, 1, 'default', '默认'),
(2, 1, 'friendlylink', '友情链接'),
(3, 1, 'newscenter', '新闻中心'),
(4, 1, 'jewelry', '珠宝/名表/首饰'),
(5, 1, 'dress', '服饰/鞋帽/包'),
(6, 1, 'digit', '数码时机'),
(7, 1, 'facial', '美容化妆');

-- --------------------------------------------------------

--
-- 表的结构 `fo_page_content`
--

CREATE TABLE IF NOT EXISTS `fo_page_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(100) NOT NULL COMMENT '显示内容',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `pic` varchar(255) NOT NULL COMMENT '图片',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `page` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '所属页面',
  `area` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '页面中的区块',
  `line` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '区块内的所处行数',
  `pos` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '区块内的位置(一行只有一条记录时为空）',
  PRIMARY KEY (`id`),
  KEY `page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='页面显示内容' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_page_content`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_redirect_log`
--

CREATE TABLE IF NOT EXISTS `fo_redirect_log` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL COMMENT '进入redirect的来源页面',
  `to` varchar(255) NOT NULL COMMENT '通过重定向后的页面',
  `create_time` int(10) unsigned NOT NULL COMMENT '进入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='跳转日志' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_redirect_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `fo_right`
--

CREATE TABLE IF NOT EXISTS `fo_right` (
  `rid` smallint(10) NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `right_name` varchar(10) NOT NULL DEFAULT '' COMMENT '权限名',
  `right_sign` varchar(100) NOT NULL DEFAULT '' COMMENT '权限标识',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='角色表' AUTO_INCREMENT=1105 ;

--
-- 转存表中的数据 `fo_right`
--

INSERT INTO `fo_right` (`rid`, `right_name`, `right_sign`) VALUES
(1, '管理员', 'admin'),
(1001, '查看用户信息', 'read_user'),
(1002, '添加用户信息', 'add_user'),
(1003, '修改用户信息', 'modify_user'),
(1004, '删除用户信息', 'delete_user'),
(1011, '查看新闻信息', 'read_news'),
(1012, '添加新闻信息', 'add_news'),
(1013, '修改新闻信息', 'modify_news'),
(1014, '删除新闻信息', 'delete_news'),
(1021, '查看广告', 'add_adv'),
(1022, '添加广告', 'add_adv'),
(1023, '修改广告', 'modify_adv'),
(1024, '删除广告', 'delete_adv'),
(1031, '查看友情链接', 'add_link'),
(1032, '添加友情链接', 'add_link'),
(1033, '修改友情链接', 'modify_link'),
(1034, '删除友情链接', 'delete_link'),
(1101, '查看留学中介信息', 'read_business'),
(1102, '添加留学中介信息', 'add_business'),
(1103, '修改留学中介信息', 'modify_business'),
(1104, '删除留学中介信息', 'delete_business');

-- --------------------------------------------------------

--
-- 表的结构 `fo_role`
--

CREATE TABLE IF NOT EXISTS `fo_role` (
  `rid` smallint(10) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(100) NOT NULL DEFAULT '' COMMENT '角色名',
  `role_sign` varchar(100) NOT NULL DEFAULT '' COMMENT '角色标识',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='角色表' AUTO_INCREMENT=1002 ;

--
-- 转存表中的数据 `fo_role`
--

INSERT INTO `fo_role` (`rid`, `role_name`, `role_sign`) VALUES
(1, '超级管理员', 'administrator'),
(2, '管理员', 'administer'),
(3, '总编', 'chief_editor'),
(4, '主编', 'managing_editor'),
(5, '责任编辑', 'charge_editor'),
(1001, '普通用户', 'common_user');

-- --------------------------------------------------------

--
-- 表的结构 `fo_role_right`
--

CREATE TABLE IF NOT EXISTS `fo_role_right` (
  `rid` int(10) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `right_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '权限ID列表',
  KEY `rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='角色表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `fo_role_right`
--

INSERT INTO `fo_role_right` (`rid`, `right_ids`) VALUES
(1, '1001,1002,1003,1006,1007,1008,1009'),
(2, '1'),
(1, '1'),
(2, '1');

-- --------------------------------------------------------

--
-- 表的结构 `fo_rss_source`
--

CREATE TABLE IF NOT EXISTS `fo_rss_source` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT 'RSS源名称',
  `sign` varchar(30) NOT NULL COMMENT 'RSS源标志名',
  `url` varchar(255) NOT NULL COMMENT 'RSS源地址',
  `orderid` tinyint(4) NOT NULL COMMENT 'RSS源显示顺序',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='RSS源' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `fo_rss_source`
--

INSERT INTO `fo_rss_source` (`id`, `name`, `sign`, `url`, `orderid`) VALUES
(1, '优网资讯', 'neeu_news', 'http://www.neeu.com/servlet/rss/news', 1),
(2, '钟表_奢华频道', 'yoka_luxury_watch', 'http://rss.yoka.com/luxury/watch.xml', 2),
(3, '顶级名车', 'yoka_luxury_car', 'http://rss.yoka.com/luxury/car.xml', 3),
(4, '奢华热点', 'feed_hululady_78', 'http://www.hululady.com/rss.php?rssid=78', 4),
(5, '名流风范', 'feed_hululady_79', 'http://www.hululady.com/rss.php?rssid=79', 5),
(6, '奢华前沿', 'feed_ibtimes_luxury', 'http://www.ibtimes.com.cn/rss/feed/shechipin.rss', 6);

-- --------------------------------------------------------

--
-- 表的结构 `fo_special`
--

CREATE TABLE IF NOT EXISTS `fo_special` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '分类ID',
  `sign` varchar(50) NOT NULL DEFAULT '0' COMMENT '专题标志符,用于生成缓存目录等',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '专题名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '专题标题',
  `summary` text NOT NULL COMMENT '专题内容简介',
  `domain` varchar(255) NOT NULL DEFAULT '' COMMENT '专题域名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '专题路径',
  `index_page` varchar(255) NOT NULL DEFAULT '' COMMENT '专题主页',
  `tpl_page` varchar(255) NOT NULL DEFAULT '' COMMENT '使用的模板页名称',
  `charge_editor` varchar(60) NOT NULL DEFAULT '' COMMENT '责任编辑',
  `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字,meta中使用',
  `meta_desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述,meta中使用',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:-1-已删除;0-待审核;1-正常;2-已过期',
  `relate` varchar(255) NOT NULL DEFAULT '' COMMENT '关联的专题ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `post_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='专题' AUTO_INCREMENT=41 ;

--
-- 转存表中的数据 `fo_special`
--

INSERT INTO `fo_special` (`id`, `cid`, `sign`, `name`, `title`, `summary`, `domain`, `path`, `index_page`, `tpl_page`, `charge_editor`, `keyword`, `meta_desc`, `status`, `relate`, `create_time`, `post_time`) VALUES
(1, 302, 'rolex', '劳力士', '劳力士名牌导航,劳力士最新产品', '', '', 'html/r/rolex/', 'index.html', '', 'admin', '劳力士,Rolex,劳力士腕表,瑞士,名表,rollex,rolx,劳力士网站,luxury watches', '本站收集劳力士相关的产品新闻和动态，同时也会提供劳力士产品的各方评价和网上导购和不同购物平台的网友评价。', 1, '', 1306398937, 1306398937),
(2, 302, 'omega', '欧米茄', '欧米茄', '', '', 'html/o/omega/', 'index.html', '', 'admin', '欧米茄,欧米茄手表,手表,华贵,制造厂,瑞士,瑞士手表,豪华手表', '劳力士相关的产品新闻和动态，欧米茄相关的产品新闻和活动、欧米茄产品点评和网上购物指南。', 1, '', 1307461199, 1307461199),
(3, 302, 'patek', '百达翡丽', '百达翡丽：世界第一名表', '', '', 'html/p/patek/', 'index.html', '', 'admin', '百达翡丽,百达翡丽手表,手表,瑞士,瑞士手表,豪华手表,帕泰克·菲利浦,柏达翡丽,百德菲丽', '百达翡丽相关的产品新闻和动态，百达翡丽相关的产品新闻和活动、百达翡丽产品点评和网上购物指南。', 1, '', 1307541559, 1307541559),
(4, 302, 'vacheron-constantin', '江诗丹顿', '江诗丹顿', '', '', 'html/v/vacheron-constantin/', 'index.html', '', 'admin', '江诗丹顿,世界名表,瑞士名表,钻石手表,多功能手表,机械表（手動上弦）,镶钻手表,万年历手表,钟表制造商,瑞士钟表日内瓦印记,Maison旗舰店,铂金,马耳他,名表,石英腕表,男士腕表,女士腕表,计时码表經典名表,月相盈亏显示,飞返式时针,陀飞轮,三问表,镂空三问表,江诗丹顿腕表,江诗丹顿手表,高级腕表,高级钟表,世界顶级腕表,世界顶级钟表,珠宝腕表', '江诗丹顿相关的产品新闻和动态，江诗丹顿相关的产品新闻和活动、江诗丹顿产品点评和网上购物指南。', 1, '', 1308647533, 1308647533),
(5, 302, 'audemars-piguet', '爱彼', '爱彼', '', '', 'html/a/audemars-piguet/', 'index.html', '', 'admin', '爱彼,爱彼手表,手表,瑞士,瑞士名表,豪华名表', '爱彼相关的产品新闻和动态，爱彼相关的产品新闻和活动、爱彼产品点评和网上购物指南。', 1, '', 1307542851, 1307542851),
(6, 102, 'cartier', '卡地亚', '卡地亚，打造奢侈钟表、独特珠宝作品、配饰和香水', '', '', 'html/c/cartier/', 'index.html', '', 'admin', '卡地亚,法国钟表,珠宝,配饰,香水', '珠宝商的皇帝，帝皇的珠宝商', 1, '', 1307543293, 1307543293),
(7, 303, 'iwc', '万国', '万国表（IWC）', '', '', 'html/i/iwc/', 'index.html', '', 'admin', '万国表,万国,iwc', '身为卓越不凡的钟表工程师，万国表提供顶级的技术内涵、创新思维和品牌个性', 1, '', 1307543572, 1307543572),
(8, 302, 'piaget', '伯爵', '伯爵: 钟表，珠宝-瑞士奢侈品牌', '', '', 'html/p/piaget/', 'index.html', '', 'admin', '伯爵,钟表,珠宝', '', 1, '', 1307543974, 1307543974),
(9, 302, 'jaeger', '积家', '积家,真正的腕表展现极致精确计时', '', '', 'html/j/jaeger/', 'index.html', '', 'admin', '积家,瑞士,瑞士名表,汝山谷', '世界十大名表之一', 1, '', 1307544393, 1307544393),
(10, 302, 'breguet', '宝玑', '宝玑：瑞士奢侈钟表', '', '', 'html/b/breguet/', 'index.html', '', 'admin', '宝玑,瑞士奢侈钟表', '宝玑品牌不仅象征着非凡制表，它更是一种承载着历史和情感的文化传承。', 1, '', 1307544777, 1307544777),
(11, 101, 'gucci', '古驰', '古驰：手提包，钱包，手表，香水', '', '', 'html/g/gucci/', 'index.html', '', 'admin', '古驰,手提包,钱包,手表,香水', '意大利最大的时装集团', 1, '', 1307545354, 1307545354),
(12, 101, 'lv', 'LV', '路易·威登(Louis Vuitton)：精致、品质、舒适的“旅行哲学”', '', '', 'html/l/lv/', 'index.html', '', 'admin', 'LV,Louis Vuitton,路易·威登', '提供Louis Vuitton时尚精品资讯、名牌导航和网上购物指南。', 1, '', 1308646027, 1308646027),
(13, 101, 'chanel', '香奈儿', '香奈儿时尚秀与配饰，香水及美容品，高级珠宝与腕表', '', '', 'html/c/chanel/', 'index.html', '', 'admin', '香奈儿,时尚,时尚秀,时尚配饰,美容品,腕表', '进入香奈儿的世界，发现最新时尚精品与配饰，香水及美容品，高级珠宝与腕表。', 1, '', 1307621083, 1307621083),
(14, 101, 'hermes', '爱马仕', '爱马仕', '', '', 'html/h/hermes/', 'index.html', '', '', '爱马仕,Hermes,法国,法国时尚', 'Hermès(爱马仕)早年以制造高级马具闻名于法国巴黎，及后推出的箱包、服装、丝巾、香水、珐琅 　　    [爱马仕]  爱马仕 饰品及家居用品，令品牌更全面多样化。 ', 1, '', 1308619903, 1308619903),
(15, 101, 'burberry', '巴宝莉', 'Burberry - 皇室御用品牌', '', '', 'html/b/burberry/', 'index.html', '', '', 'Burberry,巴宝莉,英国皇室御用品,赵晨浩', '', 1, '', 1308620671, 1308620671),
(16, 101, 'versace', '范思哲', '范思哲 - “蛇发女妖美杜莎”的精神象征', '', '', 'html/v/versace/', 'index.html', '', '', '范思哲,Versace,意大利时尚', '来自意大利的知名品牌范思哲Versace创造了一个时尚的帝国，范思哲的时尚产品渗透了生活的每个领域，范思哲品牌鲜明的设计风格，独特的美感，极强的先锋艺术表征让它风靡全球。', 1, '', 1308621123, 1308621123),
(17, 404, 'swarovski', '施华洛世奇', '施华洛世奇 - 世界上首屈一指的水晶制造商', '', '', 'html/s/swarovski/', 'index.html', '', '', '施华洛世奇,水晶灯,水晶坠饰', '施华洛世奇 (SWAROVSKI) 是世界上首屈一指的水晶制造商，产品最为动人之处，不仅仅在于它们是多么巧妙地被打磨成数十个切面，以致其对光线有极好的折射能力，整个水晶制品看起来格外耀眼夺目，更在于施华洛世奇 (Swarovski) 一直通过其产品向人们灌输着一种精致文化', 1, '', 1308621868, 1308621868),
(18, 101, 'prada', '普拉达', '普拉达 - 意大利时尚精品', '', '', 'html/p/prada/', 'index.html', '', '', 'Prada,普拉达,意大利,Miu Miu', '提供普拉达时尚精品资讯、名牌导航和网上购物指南。', 1, '', 1308640784, 1308640784),
(19, 101, 'armani', '阿玛尼', '阿玛尼 - 当你不知道要穿什么的时候，穿ARMANI就没错了！', '', '', 'html/a/armani/', 'index.html', '', '', 'Armani,阿玛尼,意大利,米兰', '提供阿玛尼产品资讯、最新活动、产品信息和网上购物指南。', 1, '', 1308641575, 1308641575),
(20, 101, 'bally', '百利', '百利 - 一个经营了百年的经典品牌，缘起一个动人而美丽的故事。', '', '', 'html/b/bally/', 'index.html', '', '', 'bally,百利,瑞士,SCRIBE', '提供百利产品资讯、最新活动、产品信息和网上购物指南。', 1, '', 1308641950, 1308641950),
(21, 103, 'shiseido', '资生堂', '资生堂 - 至哉坤元，万物资生', '', '', 'html/s/shiseido/', 'index.html', '', '', '资生堂,shiseido,日本', '提供资生堂产品资讯、最新活动、产品信息和网上购物指南。', 1, '', 1308643085, 1308643085),
(22, 103, 'valmont', '法尔曼', '法尔曼 - 世界名媛贵妇最爱的五大奢华品牌', '', '', 'html/v/valmont/', 'index.html', '', '', '法尔曼,Valmont,瑞士', '提供法尔曼产品资讯、最新活动、产品信息和网上购物指南。', 1, '', 1308643509, 1308643509),
(23, 103, 'laprairie', 'La Prairie', 'La Prairie - 全球最昂贵的护肤品牌，欧洲王室贵族、名媛淑女们的挚爱。', '', '', 'html/l/laprairie/', 'index.html', '', '', 'La Prairie,蓓莉,瑞士', '提供La Prairie时尚精品资讯、名牌导航和网上购物指南。', 1, '', 1308644950, 1308644950),
(24, 103, 'cellcosmet', '瑞妍', 'Cellcosmet - 活细胞医学先驱', '', '', 'html/c/cellcosmet/', 'index.html', '', '', 'Cellcosmet,瑞妍,瑞士', '提供Cellcosmet时尚精品资讯、名牌导航和网上购物指南。', 1, '', 1308660841, 1308660841),
(25, 103, 'swissline', '丝维诗兰', '丝维诗兰 - 最昂贵、最具疗效的护肤品之一，尖端护肤品市场的劳斯莱斯。', '', '', 'html/s/swissline/', 'index.html', '', '', 'Swissline,丝维诗兰,瑞士', '提供丝维诗兰时尚精品资讯、名牌导航和网上购物指南。', 1, '', 1308644674, 1308644674),
(26, 103, 'zbigatti', '哲·碧卡狄', '哲·碧卡狄', '', '', 'html/z/zbigatti/', 'index.html', '', '', '哲·碧卡狄,zbigatti', '哲·碧卡狄相关的产品新闻和动态，哲·碧卡狄相关的产品新闻和活动、哲·碧卡狄产品点评和网上购物指南。', 1, '', 1308661653, 1308661653),
(27, 103, 'sisley', '希思黎', '希思黎 - 护肤品中尊贵与优雅的经典代表', '', '', 'html/s/sisley/', 'index.html', '', '', '希思黎,sisley,法国', '希思黎相关的产品新闻和动态，希思黎相关的产品新闻和活动、希思黎产品点评和网上购物指南。', 1, '', 1308661950, 1308661950),
(28, 103, 'orlane', '幽兰', '幽兰 - 法国贵族化的品牌', '', '', 'html/o/orlane/', 'index.html', '', '', '幽兰,orlane,法国', '幽兰相关的产品新闻和动态，幽兰相关的产品新闻和活动、幽兰产品点评和网上购物指南。', 1, '', 1308662400, 1308662400),
(29, 103, 'lancome', '兰蔻', '兰蔻 - 细腻、优雅、气质、非凡魅力', '', '', 'html/l/lancome/', 'index.html', '', '', '兰蔻,lancome,法国', '兰蔻相关的产品新闻和动态，兰蔻相关的产品新闻和活动、兰蔻产品点评和网上购物指南。', 1, '', 1308662782, 1308662782),
(30, 103, 'Estee-Lauder', '雅诗·兰黛', '雅诗·兰黛 - 全球最大的护肤、化妆品和香水公司', '', '', 'html/E/estee-lauder/', 'index.html', '', '', 'Estee Lauder,雅诗·兰黛,美国', '雅诗·兰黛相关的产品新闻和动态，雅诗·兰黛相关的产品新闻和活动、雅诗·兰黛产品点评和网上购物指南。', 1, '', 1308663046, 1308663046),
(31, 103, 'clarins', '娇韵诗', '娇韵诗 - CLARINS是以生产丰胸、纤体、瘦身等功能性化妆品而著称，闻名全球，可称功能性化妆品的第一品牌', '', '', 'html/c/clarins/', 'index.html', '', '', '娇韵诗,clarins,法国', '娇韵诗相关的产品新闻和动态，娇韵诗相关的产品新闻和活动、娇韵诗产品点评和网上购物指南。', 1, '', 1308663441, 1308663441),
(32, 103, 'acquadiparma', '帕尔马', 'Acqua di Parma - 最早的古龙水', '', '', 'html/a/acquadiparma/', 'index.html', '', '', 'Acqua di Parma,帕尔马,古龙水,意大利', '帕尔马相关的产品新闻和动态，帕尔马相关的产品新闻和活动、帕尔马产品点评和网上购物指南。', 1, '', 1308663674, 1308663674),
(33, 103, 'benefit', '贝玲妃', '贝玲妃 - 精致以及有趣的产品、精确掌握流行的脉动', '', '', 'html/b/benefit/', 'index.html', '', '', 'BeneFit,贝玲妃,美国', '贝玲妃相关的产品新闻和动态，贝玲妃相关的产品新闻和活动、贝玲妃产品点评和网上购物指南。', 1, '', 1308664123, 1308664123),
(34, 103, 'loreal', '欧莱雅', '欧莱雅 - 知名度最高、历史最为悠久的大众化妆品品牌之一', '', '', 'html/l/loreal/', 'index.html', '', '', '欧莱雅,loreal,法国,巴黎', '欧莱雅相关的产品新闻和动态，欧莱雅相关的产品新闻和活动、欧莱雅产品点评和网上购物指南。', 1, '', 1308664674, 1308664674),
(35, 103, 'Maybelline', '美宝莲', '美宝莲 - 美来自内心，美来自美宝莲', '', '', 'html/m/maybelline/', 'index.html', '', '', 'maybelline,美宝莲', '美宝莲相关的产品新闻和动态，美宝莲相关的产品新闻和活动、美宝莲产品点评和网上购物指南。', 1, '', 1308664934, 1308664934),
(36, 103, 'LaMer', '海蓝之谜', '海蓝之谜 - 雅诗兰黛集团旗下的品牌', '', '', 'html/l/lamer/', 'index.html', '', '', '海蓝之谜,La Mer,雅诗兰', '海蓝之谜相关的产品新闻和动态，海蓝之谜相关的产品新闻和活动、海蓝之谜产品点评和网上购物指南。', 1, '', 1308665194, 1308665194),
(37, 103, 'avon', '雅芳', '雅芳:一家属于女性的公司', '', '', 'html/a/avon/', 'index.html', '', '', '雅芳,avon,美国', '雅芳相关的产品新闻和动态，雅芳相关的产品新闻和活动、雅芳产品点评和网上购物指南。', 1, '', 1308665822, 1308665822),
(38, 103, 'Artdeco', '雅蔻', '雅蔻 - 德国彩妆第一领导品牌', '', '', 'html/a/artdeco/', 'index.html', '', '', 'artdeco,雅蔻,德国', '雅蔻相关的产品新闻和动态，雅蔻相关的产品新闻和活动、雅蔻产品点评和网上购物指南。', 1, '', 1308666412, 1308666412),
(39, 103, 'Guerlain', '娇兰', '娇兰 - 酩悦·轩尼诗－路易·威登集团旗下的产品', '', '', 'html/g/guerlain/', 'index.html', '', '', '娇兰,guerlain,法国', '娇兰相关的产品新闻和动态，娇兰相关的产品新闻和活动、娇兰产品点评和网上购物指南。', 1, '', 1308666654, 1308666654),
(40, 103, 'elizabetharden', '伊丽莎白·雅顿', '伊丽莎白·雅顿 - 众香之巢,“美是自然和科学的结晶”', '', '', 'html/e/elizabetharden/', 'index.html', '', '', '伊丽莎白·雅顿,elizabeth arden', '伊丽莎白·雅顿相关的产品新闻和动态，伊丽莎白·雅顿相关的产品新闻和活动、伊丽莎白·雅顿产品点评和网上购物指南。', 1, '', 1308666951, 1308666951);

-- --------------------------------------------------------

--
-- 表的结构 `fo_tags`
--

CREATE TABLE IF NOT EXISTS `fo_tags` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG名称',
  `num` mediumint(10) NOT NULL DEFAULT '0' COMMENT '使用该TAG的内容数',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='TAG标签表' AUTO_INCREMENT=115 ;

--
-- 转存表中的数据 `fo_tags`
--

INSERT INTO `fo_tags` (`id`, `name`, `num`) VALUES
(1, '劳力士', 1),
(2, 'Rolex', 1),
(3, '瑞士', 11),
(4, '名表', 3),
(5, '欧米茄', 1),
(6, 'Omega', 1),
(7, '百达翡丽', 1),
(8, 'Patek', 1),
(9, '江诗丹顿', 1),
(10, 'vacheron-constantin', 1),
(11, '瑞士钟表', 2),
(12, '腕表', 1),
(13, '爱彼', 1),
(14, '爱彼手表', 1),
(15, '手表', 2),
(16, '卡地亚', 1),
(17, '法国钟表', 1),
(18, '万国表', 1),
(19, '万国', 1),
(20, 'iwc', 1),
(21, '伯爵', 1),
(22, '钟表', 1),
(23, '珠宝', 1),
(24, '伯爵珠宝', 1),
(25, '伯爵钟表', 1),
(26, '积家', 1),
(27, '瑞士名表', 2),
(28, '汝山谷', 1),
(29, '宝玑', 1),
(30, '古驰', 1),
(31, '手提包', 1),
(32, '钱包', 1),
(33, '香水', 1),
(34, 'LV', 2),
(35, 'Louis Vuitton', 1),
(36, '路易·威登', 1),
(37, '香奈儿', 1),
(38, '时尚', 1),
(39, '时尚秀', 1),
(40, '时尚配饰', 1),
(45, '爱马仕', 1),
(46, 'Hermes', 1),
(47, '法国', 7),
(48, '法国时尚', 1),
(49, 'Burberry', 1),
(50, '巴宝莉', 1),
(51, '英国皇室御用品', 1),
(52, '赵晨浩', 1),
(53, '范思哲', 1),
(54, 'Versace', 1),
(55, '意大利时尚', 1),
(56, '施华洛世奇', 1),
(57, '水晶灯', 1),
(58, '水晶坠饰', 1),
(59, 'Prada', 1),
(60, '普拉达', 1),
(61, '意大利', 3),
(62, 'Miu Miu', 1),
(63, 'Armani', 1),
(64, '阿玛尼', 1),
(65, '米兰', 1),
(66, 'bally', 1),
(67, '百利', 1),
(68, 'SCRIBE', 1),
(69, '资生堂', 1),
(70, 'shiseido', 1),
(71, '日本', 1),
(72, '法尔曼', 1),
(73, 'Valmont', 1),
(74, 'La Prairie', 1),
(75, '蓓莉', 1),
(76, 'Cellcosmet', 1),
(77, '瑞妍', 1),
(78, 'Swissline', 1),
(79, '丝维诗兰', 1),
(80, '哲·碧卡狄', 1),
(81, 'zbigatti', 1),
(82, '希思黎', 1),
(83, 'sisley', 1),
(84, '幽兰', 1),
(85, 'orlane', 1),
(86, '兰蔻', 1),
(87, 'lancome', 1),
(88, 'Estee Lauder', 1),
(89, '雅诗·兰黛', 1),
(90, '美国', 3),
(91, '娇韵诗', 1),
(92, 'clarins', 1),
(93, 'Acqua di Parma', 1),
(94, '帕尔马', 1),
(95, '古龙水', 1),
(96, 'BeneFit', 1),
(97, '贝玲妃', 1),
(98, '欧莱雅', 1),
(99, 'loreal', 1),
(100, '巴黎', 1),
(101, 'maybelline', 1),
(102, '美宝莲', 1),
(103, '海蓝之谜', 1),
(104, 'La Mer', 1),
(105, '雅诗兰', 1),
(106, '雅芳', 1),
(107, 'avon', 1),
(108, 'artdeco', 1),
(109, '雅蔻', 1),
(110, '德国', 1),
(111, '娇兰', 1),
(112, 'guerlain', 1),
(113, '伊丽莎白·雅顿', 1),
(114, 'elizabeth arden', 1);

-- --------------------------------------------------------

--
-- 表的结构 `fo_tag_data`
--

CREATE TABLE IF NOT EXISTS `fo_tag_data` (
  `tagid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '标签ID',
  `cid` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '分类ID:1-新闻,2-专题,3-链接,4-公司,5-用户,6-品牌,11-采集新闻',
  `nid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标签对应内容ID',
  KEY `idx_tagid` (`tagid`),
  KEY `idx_nid` (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='TAG标签表';

--
-- 转存表中的数据 `fo_tag_data`
--

INSERT INTO `fo_tag_data` (`tagid`, `cid`, `nid`) VALUES
(1, 2, 1),
(2, 2, 1),
(3, 2, 1),
(4, 2, 1),
(5, 2, 2),
(6, 2, 2),
(3, 2, 2),
(4, 2, 2),
(3, 2, 3),
(4, 2, 3),
(7, 2, 3),
(8, 2, 3),
(9, 2, 4),
(10, 2, 4),
(11, 2, 4),
(12, 2, 4),
(13, 2, 5),
(14, 2, 5),
(15, 2, 5),
(3, 2, 5),
(16, 2, 6),
(17, 2, 6),
(18, 2, 7),
(19, 2, 7),
(20, 2, 7),
(21, 2, 8),
(22, 2, 8),
(23, 2, 8),
(11, 2, 8),
(24, 2, 8),
(25, 2, 8),
(26, 2, 9),
(3, 2, 9),
(27, 2, 9),
(28, 2, 9),
(29, 2, 10),
(3, 2, 10),
(27, 2, 10),
(34, 2, 12),
(35, 2, 12),
(36, 2, 12),
(45, 2, 14),
(46, 2, 14),
(47, 2, 14),
(48, 2, 14),
(49, 2, 15),
(50, 2, 15),
(51, 2, 15),
(52, 2, 15),
(53, 2, 16),
(54, 2, 16),
(55, 2, 16),
(56, 2, 17),
(57, 2, 17),
(58, 2, 17),
(59, 2, 18),
(60, 2, 18),
(61, 2, 18),
(62, 2, 18),
(63, 2, 19),
(64, 2, 19),
(61, 2, 19),
(65, 2, 19),
(66, 2, 20),
(67, 2, 20),
(3, 2, 20),
(68, 2, 20),
(69, 2, 21),
(70, 2, 21),
(71, 2, 21),
(72, 2, 22),
(73, 2, 22),
(3, 2, 22),
(74, 2, 23),
(75, 2, 23),
(3, 2, 23),
(76, 2, 24),
(77, 2, 24),
(3, 2, 24),
(78, 2, 25),
(79, 2, 25),
(3, 2, 25),
(37, 2, 13),
(38, 2, 13),
(39, 2, 13),
(40, 2, 13),
(80, 2, 26),
(81, 2, 26),
(82, 2, 27),
(83, 2, 27),
(47, 2, 27),
(84, 2, 28),
(85, 2, 28),
(47, 2, 28),
(86, 2, 29),
(87, 2, 29),
(47, 2, 29),
(88, 2, 30),
(89, 2, 30),
(90, 2, 30),
(91, 2, 31),
(92, 2, 31),
(47, 2, 31),
(93, 2, 32),
(94, 2, 32),
(95, 2, 32),
(61, 2, 32),
(96, 2, 33),
(97, 2, 33),
(90, 2, 33),
(98, 2, 34),
(99, 2, 34),
(47, 2, 34),
(100, 2, 34),
(101, 2, 35),
(102, 2, 35),
(103, 2, 36),
(104, 2, 36),
(105, 2, 36),
(106, 2, 37),
(107, 2, 37),
(90, 2, 37),
(108, 2, 38),
(109, 2, 38),
(110, 2, 38),
(111, 2, 39),
(112, 2, 39),
(47, 2, 39),
(113, 2, 40),
(114, 2, 40);

-- --------------------------------------------------------

--
-- 表的结构 `fo_user`
--

CREATE TABLE IF NOT EXISTS `fo_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `realname` varchar(60) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `password` varbinary(32) NOT NULL DEFAULT '' COMMENT '密码',
  `pri_key` varchar(8) NOT NULL DEFAULT '' COMMENT '用户私钥',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:-1-已删除;0-未激活;1-正常;2-冻结',
  `gender` tinyint(4) NOT NULL DEFAULT '2' COMMENT '性别:0-女;1-男;2-保密',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'EMAIL',
  `msn` varchar(255) NOT NULL DEFAULT '' COMMENT 'MSN',
  `mobile` bigint(10) DEFAULT NULL COMMENT '手机',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `province` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '省份,保存行政区划',
  `city` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市,保存行政区划',
  `county` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区/县,保存行政区划',
  `coin` int(10) NOT NULL DEFAULT '0' COMMENT '金币',
  `point` int(10) NOT NULL DEFAULT '0' COMMENT '积分',
  `credit` int(10) NOT NULL DEFAULT '0' COMMENT '声望',
  `honor` int(10) NOT NULL DEFAULT '0' COMMENT '荣誉',
  `note` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=10001 ;

--
-- 转存表中的数据 `fo_user`
--

INSERT INTO `fo_user` (`uid`, `username`, `realname`, `password`, `pri_key`, `status`, `gender`, `email`, `msn`, `mobile`, `address`, `province`, `city`, `county`, `coin`, `point`, `credit`, `honor`, `note`) VALUES
(1, 'admin', '', '310dcbbf4cce62f762a2aaa148d556bd', '', 1, 1, 'admin@admin.com', '', 0, '', 350000, 350100, 350102, 1000000, 100000, 100, 100, '');

-- --------------------------------------------------------

--
-- 表的结构 `fo_user_data`
--

CREATE TABLE IF NOT EXISTS `fo_user_data` (
  `uid` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `active_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '激活时间',
  `last_login` int(10) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录IP',
  `login_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_online` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次在线时间:登录状态有刷新页面都会更新此值,由此判定是否在线',
  `oltime_total` int(10) NOT NULL DEFAULT '0' COMMENT '总在线时间',
  `oltime_thismonth` int(10) NOT NULL DEFAULT '0' COMMENT '本月在线时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '用户资料最新更新时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户数据表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `fo_user_data`
--

INSERT INTO `fo_user_data` (`uid`, `reg_time`, `active_time`, `last_login`, `last_login_ip`, `login_times`, `last_online`, `oltime_total`, `oltime_thismonth`, `update_time`) VALUES
(1, 1279034322, 0, 0, 0, 0, 0, 0, 0, 1279034322);

-- --------------------------------------------------------

--
-- 表的结构 `fo_user_role`
--

CREATE TABLE IF NOT EXISTS `fo_user_role` (
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `rid` varchar(255) NOT NULL DEFAULT '0' COMMENT '角色ID列表',
  KEY `uid` (`uid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色表';

--
-- 转存表中的数据 `fo_user_role`
--

INSERT INTO `fo_user_role` (`uid`, `rid`) VALUES
(1, '1'),
(1, '1,2');

-- --------------------------------------------------------

--
-- 表的结构 `fo_xzqh`
--

CREATE TABLE IF NOT EXISTS `fo_xzqh` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) unsigned NOT NULL COMMENT '上级行政区ID',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `lvl` varchar(255) NOT NULL COMMENT '等级:1-省/直辖市/自治区,2-地市级,3-县级',
  `city_code` varchar(255) NOT NULL COMMENT '行政区划代码',
  `area_code` varchar(255) NOT NULL COMMENT '电话区号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='行政区划' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `fo_xzqh`
--

