<?php /*a:2:{s:83:"F:\phpstudy_pro\WWW\education.kedaweilai.com\application\admin\view\role\index.html";i:1575450120;s:86:"F:\phpstudy_pro\WWW\education.kedaweilai.com\application\admin\view\public\header.html";i:1580716583;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>科大未来后台</title>
    <meta name="keywords" content="科大未来后台" />
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
                            <input type="text" name="rolename" id="rolename" placeholder="请输入角色名" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <div class="layui-btn"  id="search" data-type="reload"><i class="layui-icon">&#xe615;</i>查询</div>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('添加角色','<?php echo url('add'); ?>',400,200)"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <table id="role" lay-filter="role"></table>
                </div>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="perm">分配权限</a>
                </script>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "管理员管理",
            second_nav: "角色列表",
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
            elem: '#role'
            ,url: "<?php echo url('index'); ?>" //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID', width:80,align:'center'}
                ,{field: 'name', title: '角色名',align:'center'}
               /* ,{field: 'createtime', title: '创建时间'}*/
                ,{field: 'status', title: '状态',width:80,align:'center'}
                ,{title:'操作', templet:'#barDemo',align:'center'}
            ]]
            ,id: 'role',
        });
        //监听工具条
        table.on('tool(role)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('update'); ?>?id="+data.id;
                xadmin.open('编辑角色',url,400,200)
            }else if(obj.event === 'del'){
                layer.confirm('确认要删除该角色吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
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
                                layer.msg('删除角色成功',{anim:0,shade:0.6},function () {
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
            }else if(obj.event === 'perm'){
                var url = "<?php echo url('perm'); ?>?role_id="+data.id;
                xadmin.open('分配权限',url,700,600)
            }else{

            }
        });
        var $ = layui.$, active = {
            reload: function(){
                var rolename = $('#rolename');
                //执行重载
                table.reload('role', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        name: rolename.val()
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
