<?php /*a:2:{s:85:"/www/wwwroot/education.kedaweilai.com/application/admin/view/user_program/update.html";i:1575450267;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>课程分类编辑</title>
    <meta name="keywords" content="课程分类编辑" />
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
<form class="layui-form layui-form-pane form" action="" lay-filter="update">
    <div class="layui-form-item">
        <label  class="layui-form-label">
           会员名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text"  value="<?php echo htmlentities($sel['info']); ?>" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label">
            课程名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text"  value="<?php echo htmlentities($sel['course_title']); ?>" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label">
            班级名称<span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text"  value="<?php echo htmlentities($sel['class_name']); ?>" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>
    <div class="layui-form-item">
        <label  class="layui-form-label">
            上课信息 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text"  value="<?php echo htmlentities($sel['time_str']); ?>" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item" id="package" >
        <label  class="layui-form-label">
            套餐名称<span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text"  value="<?php echo htmlentities($sel['package_str']); ?>" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px" >状态<span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="正常" <?php if($sel['status'] == 0): ?> checked="checked"<?php endif; ?>>
            <input type="radio" name="status" value="1" title="异常" <?php if($sel['status'] == 1): ?> checked="checked"<?php endif; ?>>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo htmlentities($sel['id']); ?>">
    <div class="layui-form-item" style="    position: fixed;
    bottom: 0;
    left: 38%;">
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认修改</div>
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
                url: "<?php echo url('update'); ?>",
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
</script>
