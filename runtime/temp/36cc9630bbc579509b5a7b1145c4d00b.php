<?php /*a:2:{s:86:"/www/wwwroot/education.kedaweilai.com/application/admin/view/teacher/schedule_add.html";i:1575450212;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>添加教课时间安排</title>
    <meta name="keywords" content="添加教课时间安排" />
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
<style>
    .form{
        padding: 10px 10px 0 10px;
        background: #fff;
    }
</style>
<form class="layui-form layui-form-pane form" action="" lay-filter="add">


    <div class="layui-form-item">
        <label  class="layui-form-label">
            教师名称<span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" value="<?php echo htmlentities($teacher_name); ?>"  class="layui-input" disabled>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">星期几 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="weekday" lay-verify="required" placeholder="请输入星期1-7" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label" class="layui-input-inline">
            开始时间 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input class="layui-input"  autocomplete="off" placeholder="开始时间" name="kstime" id="start">
        </div>
    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label" class="layui-input-inline">
            结束时间 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input class="layui-input"  autocomplete="off" placeholder="结束时间" name="jstime" id="end">
        </div>
    </div>


    <input type="hidden" name="tid" value="<?php echo htmlentities($teacher_id); ?>">
    <div class="layui-form-item" style="text-align: center">
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认</div>
    </div>
</form>

<script>
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer
        //监听提交
        form.on('submit(submits)', function(data){
            $.ajax({
                type: "post",
                url: "<?php echo url('schedule_add'); ?>",
                dataType: "json",
                data:data.field,
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
                            parent.location.reload();
                            xadmin.close();
                        });
                    }else{
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            return  false;
                        });
                    }
                }
            });
            /*layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            })*/
            return false;
        });



    });


    //日期范围选择
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        laydate.render({
            elem: '#start'
            ,type: 'time'
        });
        //日期范围选择
        laydate.render({
            elem: '#end'
            ,type: 'time'
        });
    })

</script>
