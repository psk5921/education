<?php /*a:2:{s:76:"/www/wwwroot/education.kedaweilai.com/application/admin/view/perm/index.html";i:1575450090;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>科大未来后台</title>
    <meta name="keywords" content="科大未来后台" />
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

 <!--   <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.config.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.all.min.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/lang/zh-cn/zh-cn.js"></script>-->
   
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
               <!-- <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="name" id="name" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <div class="layui-btn"  id="search" data-type="reload"><i class="layui-icon">&#xe615;</i>查询</div>
                        </div>
                    </form>
                </div>-->
                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('添加菜单','<?php echo url('add'); ?>',400,545)"><i class="layui-icon"></i>添加</button>
                    <button class="layui-btn" onclick="icon()">查看图标库</button>
                </div>
                <div class="layui-card-body ">
                    <table id="perm" lay-filter="perm"></table>
                </div>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                </script>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function icon(){
        var url = "<?php echo url('iconfont'); ?>";
        xadmin.open('图标库',url,800,600);
    }
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "菜单管理",
            second_nav: "菜单列表",
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
            elem: '#perm'
            ,url: "<?php echo url('index'); ?>" //数据接口
            ,page: false //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID', width:80,align:'center'}
                ,{field: 'sortnum', title: '排序',edit:'text',align:'center'}
                ,{field: 'name', title: '菜单名称',align:'center'}
               /* ,{field: 'create_time', title: '创建时间'}*/
                ,{field: 'controller', title: '控制器',align:'center'}
                ,{field: 'action', title: '方法',align:'center'}
                ,{field: 'status', title: '状态',width:80,align:'center'}
                ,{title:'操作', templet:'#barDemo',align:'center'}
            ]]
            ,id: 'perm',
        });
        //监听单元格编辑
        table.on('edit(perm)', function(obj){ //注：edit是固定事件名，test是table原始容器的属性 lay-filter="对应的值"
            var value = obj.value;
            var datas  = { 'sortnum' : value,'id':obj.data.id};
            //console.log(obj.value); //得到修改后的值
            //console.log(obj.field); //当前编辑的字段名
            //console.log(obj.data); //所在行的所有相关数据
            //console.log(datas); //所在行的所有相关数据
            $.ajax({
                type: "post",
                url: "<?php echo url('sortnum'); ?>",
                dataType: "json",
                data:datas,
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            parent.location.reload();
                            xadmin.del_tab();
                            xadmin.close();
                        });
                    }else if( data.code == 1 ){
                        layer.msg('操作成功',{anim:0,shade:0.6},function () {
                            location.reload();
                        });
                    }else{
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            return  false;
                        });
                    }
                }
            });
            layer.close();
        });
        //监听工具条
        table.on('tool(perm)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('update'); ?>?id="+data.id;
                xadmin.open('编辑菜单',url,400,545)
            }else if(obj.event === 'del'){
                layer.confirm('确认要删除菜单吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('delete'); ?>",
                        dataType: "json",
                        data:{id:data.id},
                        success: function(data, textStatus, request){
                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    xadmin.del_tab();
                                    xadmin.close();
                                    location.href = "<?php echo url('login/index'); ?>"
                                });
                            }else if( data.code == 1 ){
                                layer.msg('删除菜单成功',{anim:0,shade:0.6},function () {
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
                var name = $('#name');
                //执行重载
                table.reload('perm', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        name: name.val()
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
