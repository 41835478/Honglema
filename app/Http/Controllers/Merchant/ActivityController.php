<?php
/**
 * 
 * author: 田旭耕
 * 
 */
namespace App\Http\Controllers\Merchant;

use App\Http\Requests;
use Illuminate\Contracts\Http\Request;
use Illuminate\Support\Facades\Redirect;
use EasyWeChat\Foundation\Application;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityCommodityList;
use App\Models\Task;

class ActivityController extends RootController{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        $merchant_id = $_SESSION['merchant_id'];
        $activity = Activity::where('merchant_id',$merchant_id)->get();
        return view('merchant.activityOrder',['list'=>$activity]);
        //return view('merchant.index');
       
    }

    //发布活动
    public function addOrder(){
        return view('merchant.addOrder');
    }

    public function modify(){
        return view('merchant.personalData');
    }

    public function activityDetail($id){
        /*
            根据数据库中该活动的状态字段确定前台页面的展示
            获取$detail['status']
            1表示未开始
            2表示正在进行
            3表示已结束
        */
        $activityDetail = Activity::where('activity_id',$id)->first();

        //获取该活动下的所有commodity_id
        $commodity_ids = ActivityCommodityList::where('activity_id',$activityDetail['activity_id'])->get();
        
        return view('merchant.activity_detail',['detail'=>$activityDetail,'commodity_ids'=>$commodity_ids]);
    }
}