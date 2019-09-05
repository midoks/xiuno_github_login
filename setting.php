<?php

/**
 * GITHUB登录
 * midoks <midoks@163.com>
 * github: https://github.com/midoks/xiuno_github_login
 */

!defined('DEBUG') AND exit('Access Denied.');

if ($method == 'GET') {
    $kv = kv_get('xiuno_github_login');

    $input                  = array();
    $input['client_id']     = form_text('client_id', $kv['client_id']);
    $input['client_secret'] = form_text('client_secret', $kv['client_secret']);
    $input['app_name']      = form_text('app_name', $kv['app_name']);

    include _include(APP_PATH . 'plugin/xiuno_github_login/setting.htm');

} else {

    $kv                  = array();
    $kv['client_id']     = param('client_id');
    $kv['client_secret'] = param('client_secret');
    $kv['app_name']      = param('app_name');
    kv_set('xiuno_github_login', $kv);

    message(0, '修改成功');
}
