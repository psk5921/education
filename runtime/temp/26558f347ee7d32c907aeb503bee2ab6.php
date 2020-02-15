<?php /*a:3:{s:83:"F:\phpstudy_pro\WWW\education.kedaweilai.com\application\admin\view\user\index.html";i:1580716807;s:86:"F:\phpstudy_pro\WWW\education.kedaweilai.com\application\admin\view\public\header.html";i:1580716583;s:86:"F:\phpstudy_pro\WWW\education.kedaweilai.com\application\admin\view\public\footer.html";i:1580722453;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>管理员列表</title>
    <meta name="keywords" content="管理员列表" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <script>
        var start = new Date().getTime();
    </script>
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

 <!--   <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.config.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.all.min.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/lang/zh-cn/zh-cn.js"></script>-->
    <style>
        #progress {
            position: fixed;
            height: 2px;
            background: #009688;
            transition: opacity 500ms linear;
            z-index:100000;
        }

        #progress.done {
            opacity: 0
        }

        #progress span {
            position: absolute;
            height: 2px;
            -webkit-box-shadow: #009688 1px 0 6px 1px;
            -webkit-border-radius: 100%;
            opacity: 1;
            width: 150px;
            right: -10px;
            -webkit-animation: pulse 2s ease-out 0s infinite;
        }

        @-webkit-keyframes pulse {
            30% {
                opacity: .6
            }
            60% {
                opacity: 0;
            }
            100% {
                opacity: .6
            }
        }
    </style>

</head>

<script type="text/javascript" src="/source/kdwl_admin/js/xadmin.js"></script>
<body>

<div class="x-nav" id="app">
          <span class="layui-breadcrumb">
            <a href="JavaScript:void(0)">{{first_nav}}</a>
            <a href="JavaScript:void(0)">{{second_nav}}</a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="username" id="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <div class="layui-btn"  id="search" data-type="reload"><i class="layui-icon">&#xe615;</i>查询</div>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('添加管理员','<?php echo url('add'); ?>',400,350)"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <table id="user" lay-filter="user"></table>
                </div>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    {{# if(d.id != 1){ }}
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    {{# } }}
                </script>
            </div>
        </div>
    </div>
</div>
<script>
    function _init() {
        var end = new Date().getTime();
        var ox = end - start;
        $({property: 0}).stop().animate({property: 100}, {
            duration: ox,
            progress: function () {
                var percentage = Math.round(this.property);
                $('#progress').css('width', percentage + "%")
                if (percentage == 100) {
                    $("#progress").addClass("done");//完成，隐藏进度条
                }
            }
        });
    }
    function _init1() {
        var end = new Date().getTime();
        var ox = (end - start) < 500 ? (end - start) + 400 : (end - start);
        $(window.parent.document).find('#progress').removeClass("done");;
        $(window.parent.document).find('#progress').attr('style','');
        $(window.parent.document).find('#progress').animate({
            width: "100%",
        }, ox ,function () {
            $(window.parent.document).find('#progress').addClass("done");//完成，隐藏进度条
        });
       /* $(window.parent.document).find('#progress').attr('style','');
        $(window.parent.document).stop().animate({width: '100%'}, {
            duration: ox,
            complete: function () {
                $(window.parent.document).find('#progress').addClass("done");//完成，隐藏进度条
            }
        });*/
    }
</script>
</body>
<script>
    window.onload = _init1();
    //$(window.parent.document).find('#progress').css('width','0');

    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "管理员管理",
            second_nav: "管理员列表",
        },
        methods: {},
        //监听属性
        watch: {

        },
        //计算属性
        computed: {

        }

    });
    layui.use(['table'], function(){
        var table = layui.table;
        //第一个实例
        table.render({
            elem: '#user'
            ,url: "<?php echo url('index'); ?>" //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID', width:80,align:'center'}
                ,{field: 'username', title: '登录名',align:'center'}
                ,{field: 'role_id', title: '角色身份',align:'center'}
                ,{field: 'createtime', title: '添加时间',align:'center'}
                ,{field: 'logintime', title: '上一次登录时间',align:'center'}
                ,{field: 'loginip', title: '上一次登录IP',width:130,align:'center'}
                ,{field: 'logincount', title: '登录次数', width: 80,align:'center'}
                ,{field: 'status', title: '状态',width:80,align:'center'}
                ,{title:'操作', templet:'#barDemo',align:'center'}
            ]]
            ,id: 'user',
        });
        //监听工具条
        table.on('tool(user)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('update'); ?>?id="+data.id;
                xadmin.open('编辑管理员',url,400,350)
            }else if(obj.event === 'del'){
                layer.confirm('确认要删除该管理员吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('delete'); ?>",
                        dataType: "json",
                        data:{id:data.id},
                        success: function(data, textStatus, request){
                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    parent.location.reload();
                                    xadmin.del_tab();
                                    xadmin.close();
                                });
                            }else if( data.code == 1 ){
                                layer.msg('删除管理员成功',{anim:0,shade:0.6},function () {
                                    location.reload();
                                    xadmin.close();
                                });
                            }else{
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    return  false;
                                });
                            }
                        }
                    });
                    layer.close(index);
                });
            }else{

            }
        });
        var $ = layui.$, active = {
            reload: function(){
                var username = $('#username');
                //执行重载
                table.reload('user', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        username: username.val()
                    }
                }, 'data');
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });

</script>
</html>
