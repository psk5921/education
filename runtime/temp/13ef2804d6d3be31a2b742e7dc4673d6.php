<?php /*a:2:{s:81:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\teacher\add.html";i:1571737310;s:83:"F:\phpStudy\PHPTutorial\WWW\yyzj_weapp_v1\application\admin\view\public\header.html";i:1571656122;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>教师添加</title>
    <meta name="keywords" content="教师添加" />
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
        <label class="layui-form-label">教师名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="teacher_name" lay-verify="required" placeholder="请输入教师名称" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教师描述 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <!--<input type="text" name="teacher_short" lay-verify="required" placeholder="请输入教师描述" autocomplete="off" class="layui-input">-->
            <textarea name="teacher_short" id="" cols="40" rows="5" placeholder="请输入教师描述" autocomplete="off"></textarea>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">点赞数量 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="zans" lay-verify="required" placeholder="请输入教师获得点赞数量" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label for="username" class="layui-form-label">
            <span class="x-red">*</span>所教科目</label>
        <div class="layui-input-inline">
            <select id="teacher_subject" name="teacher_subject" class="valid">
                <option value="1">English</option>
                <option value="2">Maths</option>
                <option value="3">History</option>
                <option value="4">Economics</option>
                <option value="5">ILETS</option>
                <option value="6">SAT</option>
                <option value="7">Physics</option>
                <option value="8">Art</option>
                <option value="9">Spanish</option>
                <option value="10">French</option>
                <option value="11">Business English</option>
                <option value="12">Travel English</option>
            </select>
        </div>
    </div>



    <div class="layui-form-item">
        <label for="username" class="layui-form-label">
            <span class="x-red">*</span>课程分类</label>
        <div class="layui-input-inline">
            <select id="category_id" name="category_id" class="valid">
            <?php if(is_array($types) || $types instanceof \think\Collection || $types instanceof \think\Paginator): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo htmlentities($vo['id']); ?>"><?php echo htmlentities($vo['edu_category_name']); ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教师年龄 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="teacher_age" lay-verify="required" placeholder="请输入教师年龄" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教师国籍 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <input type="text" name="teacher_nation" lay-verify="required" placeholder="请输入教师国籍" autocomplete="off" class="layui-input">
        </div>
    </div>


    <div class="layui-form-item">
        <label for="username" class="layui-form-label">
            <span class="x-red">*</span>状态</label>
        <div class="layui-input-inline">
            <select id="status" name="status" class="valid">
                <option value="0">正常</option>
                <option value="1">下架</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label for="username" class="layui-form-label">
            <span class="x-red">*</span>是否删除</label>
        <div class="layui-input-inline">
            <select id="deleted" name="deleted" class="valid">
                <option value="0">正常</option>
                <option value="1">删除</option>
            </select>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">教师简介 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
            <textarea name="teacher_introduce" id="teacher_introduce" cols="40" rows="5" placeholder="请输入教师简介" autocomplete="off"></textarea>
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
                            parent.location.href = "<?php echo url('login/index'); ?>"
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
</script>
