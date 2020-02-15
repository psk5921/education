<?php /*a:2:{s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/vip/baby_list.html";i:1573627305;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1572258006;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>会员宝宝</title>
    <meta name="keywords" content="会员宝宝" />
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

    <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.config.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/ueditor.all.min.js"></script>
    <script src="https://education.kedaweilai.com/static/plus/ueditor/lang/zh-cn/zh-cn.js"></script>
   
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
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right;margin-right: 15px;"
       onclick="location.href='<?php echo url('ls'); ?>'" title="返回">
        <i class="layui-icon layui-icon-return" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <?php if($cnt == 0): ?>
                <div class="layui-card-header">
                    <button class="layui-btn" id="add_vip"  onclick="xadmin.open('添加会员宝宝','<?php echo url('add_baby'); ?>?id=<?php echo htmlentities($id); ?>',450)"><i class="layui-icon"></i>添加</button>
                </div>
                <?php endif; ?>
                <div class="layui-card-body ">
                    <table id="baby" lay-filter="baby"></table>
                </div>
                <script type="text/html" id="barDemo">
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                   <!-- <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>-->
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
            second_nav: "会员宝宝信息",
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
            elem: '#baby'
            //,url: "<?php echo url('groupListUser'); ?>" //数据接口
            ,url: "<?php echo url('baby_list'); ?>?id="+<?php echo htmlentities($id); ?>
            ,page: true //开启分页
            ,cols: [[ //表头
                {field:'baby_name',title:'宝宝姓名',align:"center"}
                ,{field: 'baby_en_name', title: '英文名',align: "center"}
                ,{field: 'baby_sex', title: '性别',align: "center"}
                ,{field: 'year', title: '出生年月',align: "center"}
                ,{field: 'baby_school', title: '宝宝学校',align: "center"}
                ,{field: 'createtime', title: '添加时间',align: "center"}
                 ,{title:'操作', templet:'#barDemo',align: "center"}
            ]]
            ,id: 'baby',
        });
        //监听工具条
        table.on('tool(baby)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('update_baby'); ?>?id="+data.id;
                xadmin.open('编辑宝宝',url,400)
            }else if(obj.event === 'del'){
                layer.confirm('确认要删除宝宝吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('del_baby'); ?>",
                        dataType: "json",
                        data:{id:data.id,gid:<?php echo htmlentities($id); ?>},
                        success: function(data, textStatus, request){
                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    xadmin.del_tab();
                                    xadmin.close();
                                    location.href = "<?php echo url('login/index'); ?>"
                                });
                            }else if( data.code == 1 ){
                                layer.msg('data.msg',{anim:0,shade:0.6},function () {
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
