<?php

namespace App\Http\Controllers;
use DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
    * 登录
    *
    * @param string $username
    * @param string $password
    */
    public function postLogin() {
        if (Auth::attempt(array('username'=>$_POST['username'], 'password'=>$_POST['password']))) {
            return Redirect::to('users/dashboard')->with('message', '欢迎登录');
        } else {
            return Redirect::to('users/login')->with('message', '用户名或密码错误')->withInput();
        }
    }
    
    /**
    * 任务列表
    *
    * @ param string $username
    *
    */
    public function user(){
        $username = $_POST['username'];
        $results = DB::select("select * from v_user where username = :username", ['username' => $username]);
        return response()->json($results);
    }
    
    /**
    * 添加任务
    *
    * @ param string $url
    * @ param string $initial
    * @ param string $target
    * @ param string $brush
    * @ param string $status
    * @ param string $time
    * @ param string $username
    *
    */
    public function postCreateTask(){
        $url = $_POST['url'];
        preg_match('#v_show/id_(.*?)\.html#i',$url,$matches);
        $id = $matches[1];
        $st = file_get_contents('http://v.youku.com/QVideo/~ajax/getVideoPlayInfo?__rt=1&__ro=&id='.$id.'&sid=0&type=vv&catid=103&qq-pf-to=pcqq.c2c');
        $stt = json_decode($st,true);
        $initial = $stt['vv'];
        $target = $stt['vv'] + $_POST['target'];
        $brush = 1;
        $status = '运行';
        $time = time();
        $username = $_POST['username'];
        $money = $_POST['money'];
        DB::insert("insert into v_task (url, initial, target, brush, status, time, username) values ('$url', '$initial','$target','$brush','$status','$time','$username')");
        DB::update('update v_user set money = '.$money.' where username = :username', ['username' => $username]);
    }
    
    /**
    * 任务列表
    *
    * @ param string $username
    *
    */
    public function task(){
        $username = $_POST['username'];
        $results = DB::select("select id,url,initial,target,brush,status,from_unixtime(time,'%Y/%m/%d')as time from v_task where username = :username order by id desc", ['username' => $username]);
        return response()->json($results);
    }
    
    /**
    * 删除任务
    *
    * @ param string $id
    *
    */
    public function delTask(){
        $id = $_POST['id'];
        DB::delete('delete from v_task where id = :id', ['id' => $id]);
    }
}