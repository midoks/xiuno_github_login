<?php

/**
 * GITHUB登录
 * midoks <midoks@163.com>
 * github: https://github.com/midoks/xiuno_github_login
 */

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql      = "DROP TABLE IF EXISTS `{$tablepre}xiuno_github_login`";

db_exec($sql);
