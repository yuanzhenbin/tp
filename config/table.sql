CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
  `phone` varchar(20) DEFAULT '' COMMENT '电话',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0-未知 1-男 2-女',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-正常 2-删除',
  `add_time` int(10) DEFAULT '0' COMMENT '添加时间',
  `del_time` int(10) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';