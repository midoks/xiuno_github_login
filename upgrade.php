<?php

/**
 * GITHUB登录
 * midoks <midoks@163.com>
 * github: https://github.com/midoks/xiuno_github_login
 */

!defined('DEBUG') AND exit('Forbidden');

$kv1 = kv_get('xiuno_github_login');

$kv                  = array();
$kv['client_id']     = $kv1['client_id'];
$kv['client_secret'] = $kv1['client_secret'];
$kv['app_name']      = $kv1['app_name'];

kv_set('xiuno_github_login', $kv);