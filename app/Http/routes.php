<?php

// 登录
$app->post('login/{username}/{password}', 'Controller@postLogin');
// 创建任务
$app->post('createTask', 'Controller@postCreateTask');
// 任务列表
$app->post('task', 'Controller@task');
// 删除任务
$app->post('delTask', 'Controller@delTask');
// 用户信息
$app->post('user', 'Controller@user');