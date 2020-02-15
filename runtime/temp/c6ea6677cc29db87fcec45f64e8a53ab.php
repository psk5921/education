<?php /*a:2:{s:80:"/www/wwwroot/education.kedaweilai.com/application/admin/view/supply/preview.html";i:1573193944;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>查看教师申请信息</title>
    <meta name="keywords" content="查看教师申请信息" />
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
       onclick="location.href='<?php echo url('lists'); ?>'" title="返回">
        <i class="layui-icon layui-icon-return" style="line-height:30px"></i></a>

</div>
<form class="layui-form layui-form-pane form" action="" lay-filter="view">

    <div class="layui-form-item">
        <label class="layui-form-label">会员昵称 </label>
        <div class="layui-input-inline">
            <input type="text" name="uid"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">国籍 </label>
        <div class="layui-input-inline">
            <input type="text" name="nationality"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">工作类型 </label>
        <div class="layui-input-inline">
            <input type="text" name="work_type"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">所教科目 </label>
        <div class="layui-input-inline">
            <input type="text" name="subject"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">联系类型 </label>
        <div class="layui-input-inline">
            <input type="text" name="contact_way"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">联系方式 </label>
        <div class="layui-input-inline">
            <input type="text" name="contact"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">空闲时间 </label>
        <div class="layui-input-inline">
            <input type="text" name="avaliable_time"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教师上传的图片 </label>
        <div class="layui-input-inline">
            <?php if($view['images']): foreach($view['images'] as $item): ?>
            <a href="<?php echo htmlentities($item); ?>" target="_blank"> <img src="<?php echo htmlentities($item); ?>" alt=""></a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教师简介 </label>
        <div class="layui-input-inline">
            <input type="text" name="introduce"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">宝宝学校 </label>
        <div class="layui-input-inline">
            <input type="text" name="baby_school"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">家庭住址 </label>
        <div class="layui-input-inline">
            <input type="text" name="address"  autocomplete="off" class="layui-input" disabled>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">备注信息 </label>
        <div class="layui-input-inline">
            <textarea name="remarks" id="" cols="30" rows="10" class="layui-textarea" disabled style="resize: none"></textarea>
        </div>
    </div>


    <div class="layui-form-item" style="text-align: center">
        <div class="layui-btn " onclick="location.href='<?php echo url('lists'); ?>'" style="width: 100px;height: 40px;line-height: 40px">返回列表</div>
    </div>
</form>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            first_nav: "教师申请",
            second_nav: "查看教师申请信息",
        },
        methods: {},
        //监听属性
        watch: {},
        //计算属性
        computed: {}

    });
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer
        //表单初始赋值
        form.val('view', {
            "uid": "<?php echo htmlentities($view['uid']); ?>" // "name": "value"
            ,"nationality": "<?php echo htmlentities($view['nationality']); ?>"
            ,"work_type": "<?php echo htmlentities($view['work_type']); ?>"
            ,"subject": "<?php echo htmlentities($view['subject']); ?>" //复选框选中状态
            ,"contact_way": "<?php echo htmlentities($view['contact_way']); ?>" //开关状态
            ,"contact": "<?php echo htmlentities($view['contact']); ?>"
            ,"avaliable_time": "<?php echo htmlentities($view['avaliable_time']); ?>"
            ,"introduce": "<?php echo htmlentities($view['introduce']); ?>"
        })


    });
</script>
