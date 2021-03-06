<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="{{URL::asset('css/sm.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/sm-extend.min.css')}}">
    <style>
        .content .content-block-my{
            padding: 0;
        }
        .blackfont{
	        color : #333;
        }
        .content-no-margin{
	        margin: 0;
        }
        .list-block .item-title.label{
            width:25%;
        }
        .weui_uploader_hd {
            padding-top: 0;
            padding-right: 0;
            padding-left: 0;
        }
        .weui_uploader_hd .weui_cell_ft {
            font-size: 1em;
        }
        .weui_uploader_bd {
            overflow: hidden;
        }
        .weui_uploader_files {
            list-style: none;
        }
        .weui_uploader_file {
            margin-top: .5rem;
            float: left;
            margin-right: 9px;
            margin-bottom: 9px;
            width: 100%;
            height: 160px;
            background: no-repeat center center;
            background-size: cover;
        }
        .weui_uploader_status {
            position: relative;
        }
        .weui_uploader_status:before {
            content: " ";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .weui_uploader_status .weui_uploader_status_content {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            color: #FFFFFF;
        }
        .weui_uploader_status .weui_icon_warn {
            display: block;
        }
        .weui_uploader_input_wrp {
            margin-top: .5rem;
            float: left;
            position: relative;
            margin-right: 9px;
            margin-bottom: 9px;
            width: 100%;
            height: 160px;
            border: 1px solid #D9D9D9;
        }
        .weui_uploader_input_wrp:before,
        .weui_uploader_input_wrp:after {
            content: " ";
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            background-color: #D9D9D9;
        }
        .weui_uploader_input_wrp:before {
            width: 2px;
            height: 39.5px;
        }
        .weui_uploader_input_wrp:after {
            width: 39.5px;
            height: 2px;
        }
        .weui_uploader_input_wrp:active {
            border-color: #999999;
        }
        .weui_uploader_input_wrp:active:before,
        .weui_uploader_input_wrp:active:after {
            background-color: #999999;
        }
        .weui_uploader_input {
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
        .save{
            margin-right: .9rem;
        }
        .buttons-tab .button.active {
            color:black;
            border-color:#DD2625;
        }
        .buttons-tab .button {
            color:black;
        }
        .toast_msg{
            height:4rem !important;
        }
    </style>
</head>
<body>
@yield("body")
<script type='text/javascript' src="{{URL::asset('js/zepto.min.js')}}" charset='utf-8'></script>
<script type='text/javascript' src="{{URL::asset('js/sm.min.js')}}" charset='utf-8'></script>
<script type='text/javascript' src="{{URL::asset('js/sm-extend.min.js')}}" charset='utf-8'></script>
<script type="text/javascript" src="{{URL::asset('js/sm-city-picker.min.js')}}" charset="utf-8"></script>
<script type="text/javascript" src="{{URL::asset('js/jquery-1.8.3.min.js')}}" charset="utf-8"></script>
<script type="text/javascript" src="{{URL::asset('js/ajaxfileupload.js')}}" charset="utf-8"></script>
<script>
    $j=jQuery.noConflict();

    //上传身份证
    $('#idimg').change(function(){
        uploadIdimg();
    });
    function uploadIdimg(){
        $.showPreloader('正在上传...');
        $j.ajaxFileUpload({
            url:"/picture",//需要链接到服务器地址
            secureuri:false,
            fileElementId:"idimg",//文件选择框的id属性
            dataType: 'json',   //json
            success: function (data, status) {
                var urls = data.urls;
                var $htmls = '';
                var imgdata = document.getElementsByName("idimgurl");
                if(imgdata.length==1){
                    $htmls += '<li class="weui_uploader_file images" style="width:80px;height:80px;background-image:url('+urls[0]+')">'
                            +'<input type="hidden" name ="idimgurl" id="idimgurl" value="'+urls[0]+'"></li>';
                    $('#idimgdiv').hide();
                }else{
                    var limit = urls.length>2?2:urls.length;
                    for(var i=0; i<limit; i++){
                        $htmls += '<li class="weui_uploader_file images" style="width:80px;height:80px;background-image:url('+urls[i]+')">'
                                +'<input type="hidden" name ="idimgurl" id="idimgurl" value="'+urls[i]+'"></li>';
                    }
                }
                var imgdata2 = document.getElementsByName("idimgurl");
                $('#idfile').append($htmls);
                if(imgdata2.length>=2){
                $('#idimgdiv').hide()};
                $.hidePreloader();
                $.toast("添加成功", 1000);
            },error:function(data, status, e){
                $.hidePreloader();
                $.toast("添加失败", 1000);
            }
        });

        $('#idimg').bind('change', function () {
            uploadIdimg();
        });
    }
    //上传多图(注册相册)
    $('#imgupload').change(function(){
        $.showPreloader('正在上传...');
        $j.ajaxFileUpload({
            url:"/picture",//需要链接到服务器地址
            secureuri:false,
            fileElementId:"imgupload",//文件选择框的id属性
            dataType: 'json',   //json
            success: function (data, status) {
                var urls = data.urls;
                var $htmls = '';
                for(var i=0; i<urls.length; i++){
                    $htmls += '<li class="weui_uploader_file images" style="width:80px;height:80px;background-image:url('+urls[i]+')">\
                    <input type="hidden" id="manyimg" value="'+urls[i]+'"></li>';
                }
                $('#imgfiles').append($htmls);
                $.hidePreloader();
                $.toast("添加成功", 1000);
            },error:function(data, status, e){
                $.hidePreloader();
                $.toast("添加失败", 1000);
            }
        });
    });

    //上传网红照片()
    $('#uploadalbum').change(function(){
        $.showPreloader('正在上传...');
        $j.ajaxFileUpload({
            url:"/picture",//需要链接到服务器地址
            secureuri:false,
            fileElementId:"uploadalbum",//文件选择框的id属性
            dataType: 'json',   //json
            success: function (data, status) {
                var urls = data.urls;
                var htmls = '';
                var imgdata = new Array();
                for(var i=0; i<urls.length; i++){
                       imgdata[i] = urls[i];
                }
                $.ajax({
                    url: "/star/uploadimg",
                    type: "POST",
                    traditional: true,
                    dataType: "JSON",
                    data: {'imgdata[]':imgdata}
                    ,success: function(data) {
                     //   $('#album').append(htmls);
                        location.reload();

                        $.toast("提交成功!",1000);
                    },headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.hidePreloader();
                $.toast("添加成功", 1000);
            },error:function(data, status, e){
                $.hidePreloader();
                $.toast("添加失败", 1000);
            }
        });
    });

//upload task picture(上传后可显示)
 // $('#taskimgupload').change(uploadTaskImg());
 $('#taskimgupload').change(function(){
    uploadTaskImg();
     if($('[id=taskimg]').length==4){
         $('#taskimgInput').hide();
     }
});
//submit task result
    function uploadTaskImg(){
        $.showPreloader('正在上传...');
        $j.ajaxFileUpload({
            url:"/picture",//需要链接到服务器地址
            secureuri:false,
            fileElementId:"taskimgupload",//文件选择框的id属性
            dataType: 'json',   //json
            success: function (data, status) {
                var urls = data.urls;
                var $htmls = '';
                for(var i=0; i<urls.length; i++){
                    $htmls += '<li class="weui_uploader_file images" style="width:80px;height:80px;background-image:url('+urls[i]+')">\
                    <input type="hidden" id="taskimg" value="'+urls[i]+'"></li>';
                }
                $('#taskimgs').append($htmls);
                $.hidePreloader();
                $.toast("添加成功", 1000);
            },error:function(data, status, e){
                $.hidePreloader();
                $.toast("添加失败", 1000);
            }
        });
        $('#taskimgupload').bind('change', function () {
                                    uploadTaskImg();
                                });
    }

 //star album upload
    //验证码页面,倒计时按钮,点击确认事件
    var waittime = 60;
    var countdown = waittime;
    function settime(me) {urls.length+imgdata.length
        var obj=$(me);
        if (countdown <= 0) {
            obj.css('color','#0894ec');
            obj.text("获取验证码");
            countdown = waittime;
            return ;
        } else {
            obj.css('color','gray');
            obj.text("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {
            settime(obj);
        },1000);
    }
    $('#sendcode').click(function(){
        if(countdown == waittime) {
            $.toast("发送成功",1000);
            settime(this);
        }
    });
    $("#confirmcode").click(function(){
        if(true){
            $.showPreloader('正在验证中...');
            setTimeout(function () {
                $.hidePreloader();
                $.toast("验证成功",1000);
            }, 2000);
            setTimeout(function(){
                document.getElementById("codecfm").click();
            },3000);
        }else{
            $.toast("验证失败,请重新输入!");
        }
    });


    $(function () {
        $("#city-picker").cityPicker({
            toolbarTemplate: '<header class="bar bar-nav">\
            <button class="button button-link pull-right close-picker">确定</button>\
            <h1 class="title">地区</h1>\
            </header>'
        });
    });

    //地区选择器
    $(function () {
        $("#address-picker").cityPicker({
            toolbarTemplate: '<header class="bar bar-nav">\
            <button class="button button-link pull-right close-picker">确定</button>\
            <h1 class="title">地区</h1>\
            </header>'
        });
    });

    //日期选择器
    $("#datetime-picker").calendar({
        value: ['1993-01-01']
    });

    //头像修改页面编辑按钮
    $(document).on('click','.create-actions', function () {
        var buttons1 = [
            {
                text: '从手机相册选择',
                onClick: function() {
                    $.alert("从手机相册选择");
                }
            }
        ];
        var buttons2 = [
            {
                text: '取消'
            }
        ];
        var groups = [buttons1, buttons2];
        $.actions(groups);
    });

    //保存单行内容按钮
    $.set_value = function(va){
        $('#f_'+va).text($('#'+va).val());
    }


    //设置性别
    $.set_sex = function(){
        var text = $("#sex_picker").val();
        if(text == '男'){
            $('#f_sex').text('男');
            $('#sexvalue').val('1');
            $('#cupli').hide();
        }
        else if(text =='女'){
            $('#f_sex').text('女');
        $('#sexvalue').val('2');
            $('#cupli').show();
    }else{
        $('#sexvalue').val('0');
            $('#f_sex').text('未知');}
    }
//设置地址
    $.set_address = function(v1,v2){
        $('#f_dizhi').text($('#'+v1).val() +$('#'+v2).val());
    }

    //设置身高与体重等具有三个滚动的值
    $.set_scrollValue = function(v1,v2){
        //取值
        var value = $('#'+v1).val();
        //去空格
        value = value.replace(/\s+/g,"");
        //删除0开头
        value = value.replace(/^0*/g,"");
        //显示
        $('#'+v2).text(value);
    }

    //编辑身高与体重等具有三个滚动的值
    $.save_scrollValue = function(v1,v2){
        //取值
        var value = $('#'+v1).val();
        //去空格
        value = value.replace(/\s+/g,"");
        //删除0开头
        value = value.replace(/^0*/g,"");
        //显示
        if(v2=="weight"){
            $('#f_d'+v2).text(value+" KG");
        }
        if(v2=="height"){
            $('#f_d'+v2).text(value+" CM");
        }

        var data = {};
        data[v2]=value;
        $.ajax({
            url: "/star/update",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data:  data
            ,success: function(data) {
                $.toast("修改成功!",1000);
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    //设置尺寸
    $.set_size = function(){
        $('#f_cloth').text($('#f_shirt').val()+'/'+$('#f_pants').val()+'/'+$('#f_shoe').val());
    }

    //设置支付宝
    $.setAlipay = function(){
        $('#f_zhifubao').text($('#zhifubao').val()+'/'+$('#zhifubaoname').val());
    }

    //编辑尺寸
    $.save_size = function(){
        $('#f_cloth').text($('#f_shirt').val()+'/'+$('#f_pants').val()+'/'+$('#f_shoe').val());
        $.ajax({
            url: "/star/update",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data: {
                'shirt_size': $("#f_shirt").val(),
                'pants_size': $("#f_pants").val(),
                'shoes_size': $("#f_shoe").val(),}
            ,success: function(data) {
                $.toast("提交成功!",1000);
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }
//保存性别
    $.save_sex = function(){
        var text = $("input[name='sex-radio']:checked").val();
        if(text == '1'){
            $('#f_dsex').text('男');
             $('#cupli').hide();}
        else if(text =='2'){
            $('#f_dsex').text('女');
            $('#cupli').show();
        } else
            $('#f_dsex').text('未知');
        $.ajax({
            url: "/star/update",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data: {  'sex': text}
            ,success: function(data) {
                $.toast("提交成功!",1000);
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

//保存身份证信息
    $.saveIdInfo=function(){
        var imgdata = document.getElementsByName("idimgurl");
        var name = $("#id_name").val();
        var idcode= $("#id_code").val();
        if(imgdata.length<2){
            $.toast("需上传两张图片",1000);
        }
        else{
        $.ajax({
            url: "/star/update",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data:  {
                "ID_card1":imgdata[0].value,
                "ID_card2":imgdata[1].value,
                "real_name":name,
                "ID_number":idcode
            }
            ,success: function(data) {
                $.toast("修改成功!",1000);
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });}
    }

//保存编辑资料(单项)
    $.save_edit = function(va1,tag){
        $('#f_'+va1).text($('#'+va1).val());
        var data = {};
        data[tag]=$('#'+va1).val();
        $.ajax({
            url: "/star/update",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data:  data
            ,success: function(data) {
                $.toast("修改成功!",1000);
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    //保存修改地址
    $.save_address = function(){
        var detail = $('#daddress').val();
        var address_picker = $('#address-picker').val().split(" ");
        var region = address_picker[2];
        var city = address_picker[0];
        var province=address_picker[0];
        var data = {};
        data["province"]=province;
        data["city"]=city;
        data["region"]=region;
        data["address"]=detail;
        $.ajax({
            url: "/star/update",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data:  data
            ,success: function(data) {
                $.toast("修改成功!",1000);
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }
    //完成注册
    $('#finish').click(function() {

        var imgdata = new Array();
        var i = 0;
        $('[id=manyimg]').each(function(){
            imgdata[i] = $(this).val();
            i++;
        });
        var citypicks =  $('#address-picker').val().split(" ");
        var province = citypicks[0];
        var city = citypicks[1];
        var region = citypicks[2];
        var sex = $('#sexvalue').val();
        $.ajax({
            url: "/star/register",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data: {
                "name": $('#f_dnickname').text(),
                "sex": sex,

                "avatar":$("#f_wx_headimg").attr('src'),
                "cup": $('#f_dcup').text(),
                "weight": $('#f_weight_picker').text(),
                "height": $('#f_height_picker').text(),
                "age": $('#f_nianling').text(),
                "occupation": $('#f_zhiye').text(),
                "education": $('#f_deducation').text(),

                "experience": $('#jingli').val(),
                "tag":$('#f_tag_picker').text(),

                "shirt_size": $('#f_shirt').val(),
                "pants_size": $('#f_pants').val(),
                "shoes_size": $('#f_shoe').val(),

                "cellphone": $('#phonenum').val(),
                "address": $('#address-detail').val(),

                "weibo_id": $('#f_weiboid').text(),
                "weipai_id": $('#f_weipaiid').text(),
                "miaopai_id": $('#f_miaopaiid').text(),
                "meipai_id": $('#f_meipaiid').text(),
                "kuaishou_id": $('#f_kuaishouid').text(),

                "province":province,
                "city":city,
                "region":region,
                "wechat":$('#f_weixin').text(),
                "alipay_name":$('#zhifubao').val(),
                "alipay_account":$('#zhifubaoname').val(),
                "imgdata[]":imgdata

            },success: function(data) {
                 if(data=="exist"){
                    $.toast("已注册",1000);
                    window.location.href="/star/activityList";
                }else{
                $.toast("注册成功!",1000);
                setTimeout(function(){
                    window.location.href="/star/activityList";
                },1000);}
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    //确认收货
   function ship_confirm(task_id){
       $.ajax({
           url: "/star/ship_confirm",
           type: "POST",
           traditional: true,
           dataType: "JSON",
           data: {task_id:task_id}
           ,success: function(data) {
               $.toast("确认成功!",1000);
               setTimeout(function(){
                   location.reload();
               },1000);
           },headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
   }

    //网红确定完成任务
    function finish_task(task_id){
        $.confirm('确认完成任务?', function () {
            $.ajax({
                url: "/star/finish_task",
                type: "POST",
                traditional: true,
                dataType: "JSON",
                data: {task_id:task_id}
                ,success: function(data) {
                    $.toast("确认成功!",1000);
                    setTimeout(function(){
                        location.reload();
                    },1000);
                },headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

    }

    //网红提交任务结果
$.submmitTaskResult=function(task_id){
    var imgdata = new Array();
    var i = 0;
    $('[id=taskimg]').each(function(){
        imgdata[i] = $(this).val();
        i++;
    });
    var playback=$('#url').val();
    var views=$('#viewnumber').val();
    var duration=$('#dtime').val();
    $.confirm('确认提交任务结果?', function () {
        $.ajax({
            url: "/star/submitTaskResult",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data: {
                'task_id':task_id,
                'playback':playback,
                'views':views,
                'duration':duration,
                'imgdata[]':imgdata,}
            ,success: function(data) {
                $.toast("提交成功!",1000);
                location.href="/star/order";
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
}

//网红取消抢单
$.cancelOrder=function(id){
    $.confirm('确认取消任务?', function () {
        $.ajax({
            url: "/star/cancelOrder",
            type: "POST",
            traditional: true,
            dataType: "JSON",
            data: {order_id:id}
            ,success: function(data) {
                $.toast("取消成功!",1000);
                setTimeout(function(){
                    location.href="/star/order";
                },1000);
            },headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
}


</script>
<script>
//注册上传头像
$('#headimgEdit').change(function(){
    $.showPreloader('正在上传...');
    $j.ajaxFileUpload({
        url:"/picture",//需要链接到服务器地址
        secureuri:false,
        fileElementId:"headimgEdit",//文件选择框的id属性
        dataType: 'json',   //json
        success: function (data, status) {
            var urls = data.urls;
            for(var i=0; i<urls.length; i++){
                $('#f_wx_headimg1').attr('src',urls[i]);
                $('#f_wx_headimg2').attr('src',urls[i]);
            }
            $.hidePreloader();
            $.toast("添加成功", 1000);
        },error:function(data, status, e){
            $.hidePreloader();
            $.toast("添加失败", 1000);
        }
    });
    $.ajax({
                url: "/star/update",
                type: "POST",
                traditional: true,
                dataType: "JSON",
                data: {  'avatar': $('#f_wx_headimg').attr("src")}
                ,success: function(data) {
                    $.toast("提交成功!",1000);
                },headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
    );

});

</script>

<script>
    //注册上传头像
    $('#headimgUpload').change(function(){
        $.showPreloader('正在上传...');
        $j.ajaxFileUpload({
            url:"/picture",//需要链接到服务器地址
            secureuri:false,
            fileElementId:"headimgUpload",//文件选择框的id属性
            dataType: 'json',   //json
            success: function (data, status) {
                var urls = data.urls;
                for(var i=0; i<urls.length; i++){
                    $('#f_wx_headimg').attr('src',urls[i]);
                }
                $.hidePreloader();
                $.toast("添加成功", 1000);
            },error:function(data, status, e){
                $.hidePreloader();
                $.toast("添加失败", 1000);
            }
        });
    });
    //注册第一页检测
    function finishFirst(){
        var avatar=$("f_wx_headimg").attr('src');
        var nickname=$('#f_dnickname').text();
        var sex=$('#f_sex').text();
        var phone=$('#f_phonenum').text();
        var result=true;
        var reg = /^\d{11}$/;
       if(nickname==""){
            $.toast("昵称不能为空",3000);
            result=false;
        }else if(sex==""){
            $.toast("性别不能为空",2000);
            result=false;
        }else if(phone==""){
            $.toast("手机号不能为空",1000);
            result=false;
        }else if($('#f_weiboid').text()==""&&$('#f_weipaiid').text()==""&&$('#f_miaopaiid').text()==""&&$('#f_meipaiid').text()==""&&$('#f_kuaishouid').text()==""){
            $.toast("平台信息至少填写一个",4000);
            result=false;
        }else if(!reg.test(phone)){
           $.toast("手机号码错误",4000);
           result=false;
       }
        if(result==true){
            $('#linkReg2').click();
        }

    }
    </script>
<script>
    //罩杯选择
    $("#dcup").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">罩杯</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['30', '32', '34', '36', '38', '40', '42', '44']
            },
            {
                textAlign: 'center',
                values: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']
            }
        ]
    });
//学历选择
    $("#deducation").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">学历</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['小学', '初中', '高中', '大专', '本科', '硕士', '博士']
            }
        ]
    });
//上装选择
    $("#f_shirt").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">上装尺寸</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['S', 'M', 'L', 'XL', 'XXL', 'XXXL']
            }
        ]
    });
    //下装选择
    $("#f_pants").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">下装尺寸</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39',
                '40','41','42','43','44']
            }
        ]
    });
    //鞋子选择
    $("#f_shoe").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">鞋子选择</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45']
            }
        ]
    });
    //性别选择
    $("#sex_picker").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">性别</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['男', '女']
            }
        ]
    });
    //性别选择
    $("#sex_picker").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">性别</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['男', '女']
            }
        ]
    });
    //体重选择
    $("#weight_picker").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">体重</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['1', '2', '3', '4', '5', '6', '7','8','9','0']
            },
            {
                textAlign: 'center',
                values: ['1', '2', '3', '4', '5', '6', '7','8','9','0']
            },
            {
                textAlign: 'center',
                values: ['1', '2', '3', '4', '5', '6', '7','8','9','0']
            }
        ]
    });
    //身高选择
    $("#height_picker").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">身高</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['1', '2', '3', '4', '5', '6', '7','8','9','0']
            },
            {
                textAlign: 'center',
                values: ['1', '2', '3', '4', '5', '6', '7','8','9','0']
            },
            {
                textAlign: 'center',
                values: ['1', '2', '3', '4', '5', '6', '7','8','9','0']
            }
        ]
    });
    //网红标签选择
    $("#tag_picker").picker({
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">网红标签</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['美妆达人', '运动健身', '时尚娱乐', '优秀主播', '母婴达人', '弹唱达人', '潮搭达人','美食达人']
            },
        ]
    });
</script>


</body>
</html>