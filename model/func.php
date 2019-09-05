<?php

/**
 * GITHUB登录
 * midoks <midoks@163.com>
 * github: https://github.com/midoks/xiuno_github_login
 */

/**
 * 获取用户信息
 * $openid 微信openid
 */
function github_login_read_user_by_openid($openid) {
    $arr = db_find_one('xiuno_github_login', array('openid' => $openid));
    if ($arr) {
        $arr2 = user_read($arr['uid']);
        return $arr2;
    }
    return $arr;
}

/**
 * UID 已绑定微信
 */
function github_isbind_user_by_uid($uid) {
    $arr = db_find_one('xiuno_github_login', array('uid' => $uid));
    if ($arr) {
        return $arr;
    }
    return false;
}

/**
 * GITHUB账号绑定
 */
function github_bind_uid($uid, $openid) {
    $time = time();
    $bind = array(
        'uid'         => $uid,
        'openid'      => $openid,
        'create_date' => $time,
    );
    $r = db_insert('xiuno_github_login', $bind);
    if (empty($r)) {
        return false;
    }
    return true;
}

/**
 * 解除GITHUB账号绑定
 */
function github_unbind_uid($uid) {
    $r = db_delete('xiuno_github_login', array('uid' => $uid));
    if (empty($r)) {
        return false;
    }
    return true;
}