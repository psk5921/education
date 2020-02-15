<?php /*a:2:{s:86:"/www/wwwroot/education.kedaweilai.com/application/admin/view/order/good_order_add.html";i:1575439973;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1572258006;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>添加课程订单</title>
    <meta name="keywords" content="添加课程订单" />
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
<style>
    .form{
        padding: 10px 10px 0 10px;
        background: #fff;
    }
    .layui-form-item .layui-input-inline {
        float: left;
        width: 280px;
        margin-right: 10px;
    }
</style>
<form class="layui-form layui-form-pane form" action="" lay-filter="add_order">

    <div class="layui-form-item">
        <label class="layui-form-label">会员： <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select name="uid" lay-search="" lay-verify="required">
                <option value="">请选择会员</option>
                <?php if($user): foreach($user as $u): ?>
                <option value="<?php echo htmlentities($u['id']); ?>"><?php echo htmlentities($u['info']); ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">课程： <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select name="course" lay-search="" lay-filter="course" lay-verify="required">
                <option value="">请选择课程</option>
                <?php if($course): foreach($course as $item): ?>
                <option value="<?php echo htmlentities($item['id']); ?>"><?php echo htmlentities($item['course_title']); ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item" id="class" style="display: none">
        <label class="layui-form-label">班级： <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select name="class" lay-search="" lay-filter="class" lay-verify="required">
            </select>
        </div>
    </div>

    <div class="layui-form-item" id="time" style="display: none">
        <label class="layui-form-label">上课时间： <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select name="time" lay-search="" lay-filter="time" lay-verify="required">
            </select>
        </div>
    </div>


    <div class="layui-form-item" id="package" style="display: none">
        <label class="layui-form-label">套餐： <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select name="package" lay-search="" lay-filter="package" lay-verify="required">
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">上课地址： <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="class_address" lay-verify="required" placeholder="请输入上课地址" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">实际支付： <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="price" lay-verify="required|number" placeholder="实际支付金额" autocomplete="off" class="layui-input">
        </div>
    </div>




    <div class="layui-form-item" style="    position: fixed;
    bottom: 0;
    left: 38%;">
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认添加</div>
    </div>
</form>

<script>
    var button =  false;
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer
        //监听提交
        if(!button){
            button = true;
            form.on('submit(submits)', function(data){
                $.ajax({
                    type: "post",
                    url: "<?php echo url('good_order_add'); ?>",
                    dataType: "json",
                    data:data.field,
                    success: function(data, textStatus, request){
                        button = false;
                        if( data.code == 20006){
                            //用户信息失效
                            layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                xadmin.del_tab();
                                xadmin.close();
                                location.reload();
                                //parent.location.href = "<?php echo url('login/index'); ?>"
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
                return false;
            });
        }


        form.on('select(course)', function(data){
            if(data.value != ''){
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getInfoFromCourse'); ?>",
                    dataType: "json",
                    data:{id:data.value,type:1},
                    success: function(data, textStatus, request){
                        if( data.code == 20006){
                            //用户信息失效
                            layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                xadmin.del_tab();
                                xadmin.close();
                                parent.location.href = "<?php echo url('login/index'); ?>"
                            });
                        }else if( data.code == 1 ){
                            //显示内容区域层
                            $('#class').show();
                            $('[name="class"]').html(data.data);
                            form.render();
                        }else{
                            layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                return  false;
                            });
                        }
                    }
                });
            }else{
                $('#class').hide();
                $('[name="class"]').html('');
               return ;
            }
           // console.log(data.elem); //得到select原始DOM对象
           // console.log(data.value); //得到被选中的值
           // console.log(data.othis); //得到美化后的DOM对象
        });
        form.on('select(class)', function(data){
            if(data.value != ''){
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getInfoFromCourse'); ?>",
                    dataType: "json",
                    data:{id:data.value,type:2},
                    success: function(data, textStatus, request){
                        if( data.code == 20006){
                            //用户信息失效
                            layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                xadmin.del_tab();
                                xadmin.close();
                                parent.location.href = "<?php echo url('login/index'); ?>"
                            });
                        }else if( data.code == 1 ){
                            //显示内容区域层
                            $('#time').show();
                            $('[name="time"]').html(data.data.time);
                            $('#package').show();
                            $('[name="package"]').html(data.data.package);
                            form.render();
                        }else{
                            layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                return  false;
                            });
                        }
                    }
                });
            }else{
                $('#time').hide();
                $('#package').hide();
                $('[name="time"]').html('');
                $('[name="package"]').html('');
                return ;
            }
            // console.log(data.elem); //得到select原始DOM对象
            // console.log(data.value); //得到被选中的值
            // console.log(data.othis); //得到美化后的DOM对象
        });
    });
</script>
