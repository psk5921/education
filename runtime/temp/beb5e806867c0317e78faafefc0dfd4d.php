<?php /*a:2:{s:89:"/www/wwwroot/education.kedaweilai.com/application/admin/view/category/discuss_update.html";i:1575449808;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>课程评论编辑</title>
    <meta name="keywords" content="课程评论编辑" />
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
            评论用户 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="uid"   placeholder="" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label  class="layui-form-label">
            课程名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <select id="cid" name="cid" class="valid">
                <?php if(is_array($course) || $course instanceof \think\Collection || $course instanceof \think\Paginator): $i = 0; $__LIST__ = $course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['course_title']); ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label">评论内容 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
           <textarea class="layui-textarea" name="content" placeholder="请输入评论内容" lay-verify="required" style="width: 420px;
    resize: none;"></textarea>
        </div>
    </div>

    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">状态<span class="x-red">*</span></label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="0" title="等待审核" checked="checked" lay-filter="status">
            <input type="radio" name="status" value="1" title="审核通过" lay-filter="status">
            <input type="radio" name="status" value="2" title="审核驳回" lay-filter="status">
        </div>
    </div>

    <div class="layui-form-item" style="display: none" id="reason">
        <label class="layui-form-label">驳回原因 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <textarea class="layui-textarea" name="reason" placeholder="请输入驳回原因" style="width: 420px;
    resize: none;"></textarea>
            <!--<input type="text" name="reason" lay-verify="required" placeholder="请输入驳回原因" autocomplete="off" class="layui-input">-->
        </div>
    </div>


    <input type="hidden" name="id">
    <div class="layui-form-item" style="text-align: center" >
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
                url: "<?php echo url('discuss_update'); ?>",
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

        form.on('radio(status)', function(data){
            var status = data.value;
            if(status == 2){
                $('#reason').show();
                $('#reason').find('textarea').val('');
            }else{
                $('#reason').find('textarea').val('');
                $('#reason').hide();
            }
        });
        //表单初始赋值
        form.val('update', {
            "id": "<?php echo htmlentities($sel['id']); ?>", // "name": "value"
            "uid": "<?php echo htmlentities($user['info']); ?>", // "name": "value"
            "cid": "<?php echo htmlentities($sel['cid']); ?>", // "name": "value"
            "content": "<?php echo htmlentities($sel['content']); ?>", // "name": "value"
            "status": "<?php echo htmlentities($sel['status']); ?>" ,// "name": "value"
            "reason": "<?php echo htmlentities($sel['reason']); ?>" ,// "name": "value"


        })
    });
</script>


