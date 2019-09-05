<?php

/**
 * GITHUB登录
 * midoks <midoks@163.com>
 * github: https://github.com/midoks/xiuno_github_login
 */

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql      = "CREATE TABLE IF NOT EXISTS `{$tablepre}xiuno_github_login` (
    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
    `uid` int(11) NOT NULL COMMENT '用户ID',
    `openid` varchar(64) NOT NULL COMMENT 'openid',
    `create_date` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='GITHUB登录'";
$conn = db_exec($sql);

// 初始化
$kv = kv_get('xiuno_github_login');
if (empty($kv)) {
    $kv = array(
        'client_id'     => '',
        'client_secret' => '',
    );
    kv_set('xiuno_github_login', $kv);
}