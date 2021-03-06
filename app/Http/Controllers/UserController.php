<?php
/**
 * Created by IntelliJ IDEA.
 * User: 王得屹
 * Date: 2016/4/19
 * Time: 11:23
 */
namespace App\Http\Controllers;

use Hash;
use Auth;
use Mail;
use Validator;
use App\User;
use App\Models\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller{
    //后台用户列表页
    public function index(){
        session_start();
        if(isset($_SESSION['username'])){
            $_SESSION['active'] = 'user';
            $user = User::paginate(10);
            $confirmUser = User::where('name',$_SESSION['username'])->first();
            if ($confirmUser->is_admin == 1)
                return view('/cms/user',['users' => $user ,'is_super_admin' => $confirmUser->is_super_admin]);
            else
            {
                echo "<script>history.go(-1); alert('该用户没有权限操作!');</script>";
                return;
            }
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //查找用户
    public function searchUser(){
        session_start();
        if(isset($_SESSION['username'])){
            $user = User::where('name','like','%'.Input::get('name').'%')->paginate(6);
            $confirmUser = User::where('name',$_SESSION['username'])->first();
            return view('/cms/user',['users' => $user ,'is_super_admin' => $confirmUser->is_super_admin]);
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //删除用户
    public function deleteUser($id){
        session_start();
        if(isset($_SESSION['username'])){
            $user = User::where('id',$id)->first();

            $log = new Log();
            $log->username = $_SESSION['username'];
            $log->operation = 'delete';
            $log->operated_data = '用户信息';
            $log->operated_username = $user->name;
            $log->save();

            User::where('id',$id)->delete();
            $user = User::paginate(10);
            $confirmUser = User::where('name',$_SESSION['username'])->first();
            return Redirect::back()->with(['users' => $user ,'is_super_admin' => $confirmUser->is_super_admin]);
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //后台用户创建页
    public function createUserIndex(){
        session_start();
        if(isset($_SESSION['username'])){
            $user = User::where('name',$_SESSION['username'])->first();
            if($user->is_admin == 1){
                return view('/cms/user_create',['is_super_admin' => $user->is_super_admin]);
            }
            else{
                echo "<script>history.go(-1); alert('该用户没有权限操作!');</script>";
                return;
            }
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //创建后台用户
    public function createUser(){
        session_start();
        if(isset($_SESSION['username'])){
            $validator = Validator::make(Input::all(), User::$rules);

            if ($validator->passes()) {
                $user = new User();

                $user->name = Input::get('name');
                $user->nickname = Input::get('nickname');
                $user->email = Input::get('email');
                $password = str_random(8);
                $user->password = Hash::make($password);
                $user->is_admin = Input::get('is_admin');

                $right = Input::get('right');
                if($right){
                    $str = implode("",$right);

                    if (strstr($str,'工厂'))
                        $user->factory_right = 1;
                    if (strstr($str,'品牌商'))
                        $user->brand_right = 1;
                    if (strstr($str,'设计师'))
                        $user->designer_right = 1;
                    if (strstr($str,'档口'))
                        $user->stall_right = 1;
                    if (strstr($str,'红人'))
                        $user->celebrity_right = 1;
                }
                $user->contact_only = Input::get('contact_only');

                $log = new Log();
                $log->username = $_SESSION['username'];
                $log->operation = 'insert';
                $log->operated_data = '用户信息';
                $log->operated_username = $user->name;
                $log->save();

                $user->save();

                Mail::send('/cms/mail_create_user', ['user' => $user , 'password' => $password], function ($m) use ($user) {
                    $m->to($user->email, $user->name)->subject('红了吗后台管理系统账号创建通知');
                });
                return Redirect::intended('/cms/user');
            }else {
                // 验证没通过就显示错误提示信息
                echo "<script>history.back(); alert('用户名或邮箱已存在!');</script>";
            }
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //用户信息页
    public function user_info_confirm(){
        session_start();
        if(isset($_SESSION['username'])){
            $_SESSION['active'] = null;
            $confirmUser = User::where('name',$_SESSION['username'])->first();
            return view('/cms/user_info_confirm',['user' => $confirmUser]);
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //用户信息修改页
    public function user_info(){
        session_start();
        if(isset($_SESSION['username'])){
            $_SESSION['active'] = null;
            return view('/cms/user_info');
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //修改用户信息
    public function updateUser(){
        session_start();
        if(isset($_SESSION['username'])){
            if (Auth::attempt(array('name'=>$_SESSION['username'], 'password'=>Input::get('currentPassword')))){
                $password = Input::get('password');
                $passwordConfirm = Input::get('passwordConfirm');
                if(strlen($password) < 8){
                    echo "<script>history.back(); alert('密码至少8位!');</script>";
                    return;
                }
                if($password == $passwordConfirm){
                    $password = Hash::make($password);
                }
                else{
                    echo "<script>history.back(); alert('请输入相同的密码!');</script>";
                    return;
                }
                $user = User::where('name',$_SESSION['username'])->first();
                $user->password = $password;

                $user->save();
                
                return Redirect::intended('/cms/index');
            }
            else{
                echo "<script>history.back(); alert('当前密码错误!');</script>";
                return;
            }
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //用户权限修改页
    public function modifyUserRight($id){
        session_start();
        if(isset($_SESSION['username'])){
            $user = User::where('id',$id)->first();
            return view('/cms/user_right',['user' => $user]);
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //修改用户权限
    public function updateUserRight($id){
        session_start();
        if(isset($_SESSION['username'])){
            $user = User::where('id',$id)->first();
            $user->is_admin = Input::get('is_admin');

            $user->factory_right = 0;
            $user->brand_right = 0;
            $user->designer_right = 0;
            $user->stall_right = 0;
            $right = Input::get('right');
            
            if($right){
                $str = implode("",$right);

                if (strstr($str,'工厂'))
                    $user->factory_right = 1;
                if (strstr($str,'品牌商'))
                    $user->brand_right = 1;
                if (strstr($str,'设计师'))
                    $user->designer_right = 1;
                if (strstr($str,'档口'))
                    $user->stall_right = 1;
                if (strstr($str,'红人'))
                    $user->celebrity_right = 1;
            }
            $user->contact_only = Input::get('contact_only');

            $log = new Log();
            $log->username = $_SESSION['username'];
            $log->operation = 'update';
            $log->operated_data = '用户信息';
            $log->operated_username = $user->name;
            $log->save();

            $user->save();
            return Redirect::to('/cms/user');
        }
        else{
            return Redirect::intended('/cms/login');
        }
    }
    //忘记密码
    public function forget(){
        $user = User::where('name',Input::get('name'))->first();
        if($user){
            $password = str_random(8);
            $user->password = Hash::make($password);
            $user->save();

            Mail::send('/cms/mail_forget', ['password' => $password], function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('红了吗后台管理系统账号密码重置');
            });

            return view('/cms/login');
        }
        else{
            echo "<script>history.back(); alert('该用户不存在!');</script>";
        }
    }
}