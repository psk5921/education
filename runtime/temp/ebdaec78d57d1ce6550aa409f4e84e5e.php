<?php /*a:2:{s:82:"/www/wwwroot/education.kedaweilai.com/application/admin/view/clbum/class_time.html";i:1573652243;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1572258006;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>班级时间</title>
    <meta name="keywords" content="班级时间" />
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
                <div class="layui-card-header">
                    <button class="layui-btn" id="add_vip"  onclick="xadmin.open('添加上课时间','<?php echo url('classtime_add'); ?>?class_id=<?php echo htmlentities($class_id); ?>',450,300)"><i class="layui-icon"></i>添加上课时间</button>
                </div>
                <div class="layui-card-body ">
                    <table id="Ctime" lay-filter="Ctime"></table>
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
            first_nav: "班级列表",
            second_nav: "上课时间",
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
            elem: '#Ctime'
            //,url: "<?php echo url('groupListUser'); ?>" //数据接口
            ,url: "<?php echo url('class_time'); ?>?class_id="+<?php echo htmlentities($class_id); ?>
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID',align: "center"}
                ,{field: 'displayorder', title: '排序', align: "center"}

                /*,{field: 'week_start', title: '开始星期',align: "center",width:"10%"}
                ,{field: 'week_end', title: '结束星期',align: "center",width:"10%"}

                ,{field: 'startfree', title: '开始时间',align: "center",width:"10%"}
                ,{field: 'endfree', title: '结束时间',align: "center",width:"10%"}*/
                ,{field: 'time', title: '课程时间',align: "center"}
                //,{field: 'createtime', title: '创建时间',align: "center"}
                ,{field: 'status', title: '状态',align: "center"}
                 ,{title:'操作', templet:'#barDemo',align: "center"}
            ]]
            ,id: 'Ctime',
        });
        //监听工具条
        table.on('tool(Ctime)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                var url = "<?php echo url('classtime_update'); ?>?id="+data.id+"&class_id="+<?php echo htmlentities($class_id); ?>;
                xadmin.open('编辑上课时间',url,550,300)
            }else if(obj.event === 'del'){
                layer.confirm('确认要强制删除课程时间吗？操作不可逆哦！！',{title:'系统提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('classtime_del'); ?>",
                        dataType: "json",
                        data:{id:data.id,class_id:<?php echo htmlentities($class_id); ?>},
                        success: function(data, textStatus, request){
                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    xadmin.del_tab();
                                    xadmin.close();
                                    location.href = "<?php echo url('login/index'); ?>"
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
