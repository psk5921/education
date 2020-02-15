<?php /*a:2:{s:78:"/www/wwwroot/education.kedaweilai.com/application/admin/view/vip/jifen_ls.html";i:1575450346;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>积分记录</title>
    <meta name="keywords" content="积分记录" />
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
                <div class="layui-card-header">
                    <button class="layui-btn" id="add_vip"  onclick="location.href='<?php echo url('ls'); ?>'" >返回列表</button>
                </div>
                <!--<div class="layui-card-header">
                    <button class="layui-btn" id="add_vip"  onclick="xadmin.open('添加用户积分记录','<?php echo url('jifen_add'); ?>?id=<?php echo htmlentities($id); ?>',450)"><i class="layui-icon"></i>添加</button>
                </div>-->
                <div class="layui-card-body ">

                    <table id="address" lay-filter="address"></table>
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
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "会员列表",
            second_nav: "积分记录",
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
            elem: '#address'
            //,url: "<?php echo url('groupListUser'); ?>" //数据接口
            ,url: "<?php echo url('jifen_ls'); ?>?id="+<?php echo htmlentities($id); ?>
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID', width:80,align: "center"}
                ,{field:'nickname',title:'用户昵称',align:"center"}
                ,{field: 'intergral', title: '变动的积分',align: "center"}
                ,{field: 'createtime', title: '变动的时间',align: "center"}
                ,{field: 'type', title: '变动类型',align: "center"}
               /* ,{title:'操作', templet:'#barDemo',align: "center"}*/
            ]]
            ,id: 'address',
        });
        //监听工具条
        table.on('tool(address)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('jifen_update'); ?>?id="+data.id;
                xadmin.open('编辑积分记录',url,400)
            }else if(obj.event === 'del'){
                layer.confirm('确认要强制删除用户积分记录吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('jifen_del'); ?>",
                        dataType: "json",
                        data:{id:data.id,gid:<?php echo htmlentities($id); ?>},
                        success: function(data, textStatus, request){
                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    parent.location.reload();
                                    xadmin.del_tab();
                                    xadmin.close();
                                });
                            }else if( data.code == 1 ){
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
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
    });


</script>
</html>
