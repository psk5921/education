<?php /*a:2:{s:82:"/www/wwwroot/education.kedaweilai.com/application/admin/view/user_program/add.html";i:1575450267;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>添加用户课程</title>
    <meta name="keywords" content="添加用户课程" />
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
            会员名称<span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="uid" name="uid" class="valid" lay-filter="uid"  lay-search>
                <?php if(is_array($users) || $users instanceof \think\Collection || $users instanceof \think\Paginator): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['info']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label">
            课程名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="course_id" name="course_id" class="valid" lay-filter="course_id" lay-search>
                <option value="0">请选择课程</option>
                <?php if(is_array($course) || $course instanceof \think\Collection || $course instanceof \think\Paginator): $i = 0; $__LIST__ = $course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['course_title']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item" id="class" style="display: none">
        <label  class="layui-form-label">
            班级名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="class_id" name="class_id" class="valid" lay-filter="class_id" lay-search >

            </select>
        </div>
    </div>


    <div class="layui-form-item" id="time" style="display: none">
        <label  class="layui-form-label">
            上课信息 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="time_id" name="time_id" class="valid" lay-search >

            </select>
        </div>
    </div>

    <div class="layui-form-item" id="package" style="display: none">
        <label  class="layui-form-label">
            套餐名称<span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="package_id" name="package_id" class="valid" lay-search>

            </select>
        </div>
    </div>

    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px" style="display: none">状态<span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="正常" checked="checked">
            <input type="radio" name="status" value="1" title="异常">
        </div>
    </div>


    <div class="layui-form-item" style="    position: fixed;
    bottom: 0;
    left: 38%;">
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
                url: "<?php echo url('add'); ?>",
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

        form.on('select(course_id)', function(data){
            var v = data.value;
            if(v == 0 ){
                return;
            }
            $.ajax({
                type: "post",
                url: "<?php echo url('get_data'); ?>",
                dataType: "json",
                data:{id:v,type:1},
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            xadmin.del_tab();
                            xadmin.close();
                            parent.location.href = "<?php echo url('login/index'); ?>"
                        });
                    }else if( data.code == 1 ){
                        $('#class').show();
                        $('#class_id').html('');
                        $('#class_id').append(data.data);
                        form.render('select', 'add');
                    }else{
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            return  false;
                        });
                    }
                }
            });

        });
        form.on('select(class_id)', function(data){
            var v = data.value;
            if(v == 0 ){
                return;
            }
            $.ajax({
                type: "post",
                url: "<?php echo url('get_data'); ?>",
                dataType: "json",
                data:{id:v,type:2},
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            xadmin.del_tab();
                            xadmin.close();
                            parent.location.href = "<?php echo url('login/index'); ?>"
                        });
                    }else if( data.code == 1 ){
                        $('#time').show();
                        $('#time_id').html('');
                        $('#time_id').append(data.data);
                        form.render('select', 'add');
                    }else{
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            return  false;
                        });
                    }
                }
            });
            $.ajax({
                type: "post",
                url: "<?php echo url('get_data'); ?>",
                dataType: "json",
                data:{id:v,type:3},
                success: function(data, textStatus, request){
                    if( data.code == 20006){
                        //用户信息失效
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            xadmin.del_tab();
                            xadmin.close();
                            parent.location.href = "<?php echo url('login/index'); ?>"
                        });
                    }else if( data.code == 1 ){
                        $('#package').show();
                        $('#package_id').html('');
                        $('#package_id').append(data.data);
                        form.render('select', 'add');
                    }else{
                        layer.msg(data.msg,{anim:0,shade:0.6},function () {
                            return  false;
                        });
                    }
                }
            });

        });
        //表单初始赋值
        /*  form.val('add', {
              "username": "贤心" // "name": "value"
              ,"password": "123456"
              ,"interest": 1
              ,"like[write]": true //复选框选中状态
              ,"close": true //开关状态
              ,"sex": "女"
              ,"desc": "我爱 layui"
          })*/


    });


    //日期范围选择


</script>
