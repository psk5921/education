<?php /*a:2:{s:81:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\login\index.html";i:1571656122;s:83:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\Public\header.html";i:1571656122;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>科大未来后台登录</title>
    <meta name="keywords" content="科大未来后台登录" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/source/kdwl_admin/css/font.css">
    <link rel="stylesheet" href="/source/kdwl_admin/css/login.css">
    <link rel="stylesheet" href="/source/kdwl_admin/css/xadmin.css">
    <script type="text/javascript" src="/source/kdwl_admin/js/jquery.min.js"></script>
    <script type="text/javascript" src="/source/kdwl_admin/js/vue2.5.16.min.js"></script>
    <script src="/source/kdwl_admin/lib/layui/layui.js" charset="utf-8"></script>
    <!--[if lt IE 9]>
    <script src="/source/kdwl_admin/js/html5.min.js"></script>
    <script src="/source/kdwl_admin/js/respond.min.js"></script>
    <script src="/source/kdwl_admin/js/vue2.5.16.min.js"></script>
    <![endif]-->
</head>
<body class="login-bg">
<style>
    .input {
        padding: 0 0 0 45px !important;
    }
    .foot{
        font-size: 14px;
        margin-bottom: 10px;
        text-align: center;
    }
    .login #darkbannerwrap {
        width: 18px;
        height: 10px;
        position: relative;
        margin: 0px 0px 0 -58px;
    }
</style>

<div id="app">
    <div class="login layui-anim layui-anim-up">
        <div class="message">{{title}}</div>
        <div id="darkbannerwrap"></div>
        <div id="err_msg" :style="err_style" v-show="err">{{err_msg}}</div>
        <form method="post" class="layui-form">
            <div style="position: relative">
                <i class="layui-icon layui-icon-username" style="font-size: 30px; color: #1E9FFF;    position: absolute;
    top: 10px;
    left: 10px;"></i>
                <input name="username" placeholder="请输入用户名" type="text" class="layui-input" v-model="username"
                       :class="{ input: true }" >
                <hr class="hr15">
            </div>
            <div style="position: relative">
                <i class="layui-icon layui-icon-password" style="font-size: 30px; color: #1E9FFF;position: absolute;
    top: 10px;
    left: 10px;"></i>
                <input name="password" placeholder="请输入密码" type="password" class="layui-input" v-model="password"
                       :class="{ input: true }">
                <hr class="hr15">
                <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit" id="submit">
                <hr class="hr20">
            </div>
        </form>
        <div>
            <p class="foot"> 管理系统·2019 版权所有</p>
            <p class="foot">Copyright(C)xxx.com, All Rights Reserved.</p>
        </div>
    </div>
</div>


<script>
    var app = new Vue({
        el: '#app',
        data: {
            title: '后台管理系统登录',
            err: true,
            username: '',
            password: '',
            err_style: {
                'margin-bottom': '10px',
                'text-align': 'center',
                'color': 'red',
                'font-size': '16px',
            },
            err_msg: '',
            submit :true
        },
        methods: {},
        //监听属性
        watch: {
            username: function () {
                this.err = false;
            },
            password: function () {
                this.err = false;
            }
        },
        //计算属性
        computed: {

        }

    });
    $(function () {
        layui.use('form', function () {
            var form = layui.form;
            // layer.msg('玩命卖萌中', function(){
            //   //关闭后的操作
            //   });
            //监听提交
            if(app.submit){
                console.log(app.submit)
                app.submit = false;
                form.on('submit(login)', function (data) {
                    var msg = '';
                    if (false == data.field.username && msg == '') {
                        msg = '请输入用户名';
                    }
                    if (false == data.field.password && msg == '') {
                        msg = '请输入密码';
                    }
                    if (msg) {
                        app.err = true;
                        app.err_msg = msg;
                        return false;
                    }
                    $('#submit').val('正在登录中……');
                    $('#submit').css('background','rgb(30, 159, 255)');
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('login'); ?>",
                        dataType: "json",
                        data:data.field,
                        success: function(data, textStatus, request){
                            if( data.code == 20000){
                                $('#submit').val('登录');
                                $('#submit').css('background','');
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    location.href = "<?php echo url('index/index'); ?>"
                                });
                            }else{
                                $('#submit').val('登录');
                                app.err = true;
                                app.err_msg = data.msg;
                                return false;
                            }
                        }
                    });
                    /*layer.msg(JSON.stringify(data.field), function () {
                        location.href = 'index.html'
                    });*/
                    return false;
                });
            }

        });
    })
</script>
<!-- 底部结束 -->
</body>
</html>
