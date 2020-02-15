<?php /*a:2:{s:76:"/www/wwwroot/education.kedaweilai.com/application/admin/view/vip/update.html";i:1575450345;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>会员编辑</title>
    <meta name="keywords" content="会员编辑" />
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
<script type="text/javascript" src="/source/kdwl_admin/js/jquery.min.js"></script>
<style>
    .form{
        padding: 10px 10px 0 10px;
        background: #fff;
    }
</style>
<body>
<div class="x-nav" id="app">
          <span class="layui-breadcrumb">
            <a href="JavaScript:void(0)">{{first_nav}}</a>
            <a href="JavaScript:void(0)">{{second_nav}}</a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right;margin-right: 15px;"
       onclick="location.href='<?php echo url('ls'); ?>'" title="返回">
        <i class="layui-icon layui-icon-return" style="line-height:30px"></i></a>

</div>
<form class="layui-form layui-form-pane form" action="" lay-filter="update">
    <div class="layui-form-item">
        <label class="layui-form-label">会员昵称 </label>
        <div class="layui-input-inline">
            <input type="text" name="nickname"   placeholder="" autocomplete="off" class="layui-input" disabled>
        </div>
    </div>


    <!---->
    <div class="layui-form-item layui-row layui-col-xs12">
        <label class="layui-form-label">用户头像</label>
        <div class="layui-col-xs3">
            <div class=" thumbBox mag0 magt3">
               <!-- <img class="layui-upload-img thumbImg">-->
                <?php if($sel['avatar']): ?>
                <a href="<?php echo htmlentities($sel['avatar']); ?>"><img class="layui-upload-img oldthumbImg" src="<?php echo htmlentities($sel['avatar']); ?>" width="150" height="150"></a>
                <?php endif; ?>
                <input type="hidden" name="avatar" id="thumbImg"/>
            </div>
        </div>
    </div>
    <!---->

    <div class="layui-form-item">
        <label class="layui-form-label">手机号码</label>
        <div class="layui-input-inline">
            <input type="text" name="mobile"  placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>


    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">性别</label>
        <div class="layui-input-block">
            <input type="radio" name="gender" value="0" title="未知">
            <input type="radio" name="gender" value="1" title="男">
            <input type="radio" name="gender" value="2" title="女">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">会员积分 </label>
        <div class="layui-input-inline">
            <input type="text" name="credit"  placeholder="" autocomplete="off" class="layui-input">
        </div>
    </div>

    <!--<div class="layui-form-item">
        <label class="layui-form-label">省份</label>
        <div class="layui-input-inline">
            <input type="text" name="province" lay-verify="required" placeholder="请输入省份" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">城市</label>
        <div class="layui-input-inline">
            <input type="text" name="city" lay-verify="required" placeholder="请输入城市" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">地区</label>
        <div class="layui-input-inline">
            <input type="text" name="country" lay-verify="required" placeholder="请输入地区" autocomplete="off" class="layui-input">
        </div>
    </div>-->

    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="line-height: 20px">身份标识</label>
        <div class="layui-input-block">
            <input type="radio" name="identity" value="0" title="粉丝用户" >
            <input type="radio" name="identity" value="1" title="付费用户" >
            <input type="radio" name="identity" value="2" title="教师" >
        </div>
    </div>

   

    <div class="layui-form-item" pane="">
        <label  class="layui-form-label" >
           能否核销</label>
        <div class="layui-input-block">
            <input type="radio" name="is_verification" value="0" title="不能" checked="checked">
            <input type="radio" name="is_verification" value="1" title="能">
        </div>
    </div>



    <input type="hidden" name="id">
    <div class="layui-form-item" style="text-align: center">
        <div class="layui-btn " style="width: 100px;height: 40px;line-height: 40px"
             onclick="location.href='<?php echo url('ls'); ?>'">返回列表
        </div>
        <div class="layui-btn " lay-submit="" lay-filter="submits" style="width: 100px;height: 40px;line-height: 40px">确认修改</div>
    </div>
</form>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "会员列表",
            second_nav: "编辑会员",
        },
        methods: {},
        //监听属性
        watch: {},
        //计算属性
        computed: {}

    });
    layui.use(['form','upload'], function(){
        var form = layui.form
            ,upload = layui.upload
            ,layer = layui.layer
        
        //上传缩略图
       /* upload.render({
            elem: '.thumbBox',
            url:  "<?php echo url('move'); ?>",
            done: function(res, index, upload){
                var obj=res.data;
                $('.thumbImg').attr('src',obj);
                $('#thumbImg').val(obj);
            }
        });*/
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
        //表单初始赋值
        form.val('update', {
            "id": "<?php echo htmlentities($sel['id']); ?>", // "name": "value"
            "nickname": "<?php echo htmlentities($sel['nickname']); ?>", // "name": "value"
            "mobile": "<?php echo htmlentities($sel['mobile']); ?>" ,// "name": "value"
            "gender": "<?php echo htmlentities($sel['gender']); ?>" ,// "name": "value"
            "credit": "<?php echo htmlentities($sel['credit']); ?>" ,// "name": "value"
            "province": "<?php echo htmlentities($sel['province']); ?>" ,// "name": "value"
            "city": "<?php echo htmlentities($sel['city']); ?>" ,// "name": "value"
            "country": "<?php echo htmlentities($sel['country']); ?>" ,// "name": "value"
            "identity": "<?php echo htmlentities($sel['identity']); ?>" ,// "name": "value"
            "is_verification": "<?php echo htmlentities($sel['is_verification']); ?>" ,// "name": "value"
            "avatar": "<?php echo htmlentities($sel['avatar']); ?>" ,// "name": "value"


        })
    });
</script>
