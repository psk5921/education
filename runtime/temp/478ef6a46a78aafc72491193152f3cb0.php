<?php /*a:2:{s:83:"/www/wwwroot/education.kedaweilai.com/application/admin/view/order/shop_detail.html";i:1575449665;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>订单详情</title>
    <meta name="keywords" content="订单详情" />
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
    .form {
        padding: 10px 10px 0 10px;
        background: #fff;
    }
    .text-default {
        color: #000 !important;
    }
    .border{
        margin: 20px 0 20px 20px;
        border: 1px solid #efefef;
        padding-right: 0;
        padding: 30px !important;
        padding-left: 15px !important;
        padding-bottom: 20px !important;
        min-height: 120px;
    }
    .border ul{

    }
    .text {
        color: #999;
    }
    .border ul li {
        list-style: none;
    }
    .border ul li {
        line-height: 26px;
    }
    .col-sm {
        float: left;
        padding-right: 10px;
        padding-left: 10px;
        position: relative;
    }
    .font18 {
        font-size: 20px;
        font-weight: bold;
        color: #eb6060 !important;
    }
    .table {
        table-layout: fixed;
        position: relative;
        color: #666;
    }
    .table>tbody+tbody {
        border-top: none;
    }

    .table tr {
        height: 24px;
    }

    .table > caption + thead > tr:first-child > td, .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > td, .table > thead:first-child > tr:first-child > th {
        border: none;
        font-weight: normal;
        color: #999;
        border-top: 1px solid #efefef;
    }
    .table tr th{
        color: #333 !important;
    }
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table > thead > tr > td.full, .table > tbody > tr > td.full, .table > tfoot > tr > td.full {
        white-space: normal;
        line-height: 20px;
    }

    .modal .table th, .modal .table td {
        text-align: left;
        position: relative;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        border-top: 1px solid #efefef;
        vertical-align: middle;
    }
    .table tfoot {
        border-bottom: 1px solid #efefef;
    }

    .table.table-hover tbody tr:hover {
        background: #f9f9f9;
    }

    fieldset[disabled] input[type=checkbox], fieldset[disabled] input[type=radio], input[type=checkbox].disabled, input[type=checkbox][disabled], input[type=radio].disabled, input[type=radio][disabled] {
        cursor: not-allowed;
        opacity: 0.4;
        background: #f5f5f5;
    }
    fieldset[readonly] input[type=checkbox], fieldset[readonly] input[type=radio], input[type=checkbox].readonly, input[type=checkbox][readonly], input[type=radio].readonly, input[type=radio][readonly] {
        cursor: not-allowed;
        opacity: 0.4;
        background: #f5f5f5;
    }
    .page-table-header{
        padding: 8px;
        border-top:1px solid #efefef;
    }
    .page-table-header input[type=checkbox]{
        margin-right: 5px;
    }
    .table > thead > tr > th input[type=checkbox],
    .table > tbody > tr > td input[type=checkbox] {
        margin-top: -2px;
    }

    .table > tfoot > tr > th .btn, .table > tfoot > tr > td .btn,
    .table > tfoot > tr > th .fui-pager, .table > tfoot > tr > td .fui-pager {
        margin-top: 2px;
    }

    .table thead > tr > th a {
        color: #999;
    }

    .table thead > tr > th .dropdown-menu a {
        color: #999;
    }

    .table > thead > tr > th {
        color: #999;
    }

    .table > tbody > tr > td.select {
        background: #f8f8f8
    }

    .table > tbody > tr > td.empty span {
        text-align: center;
        padding: 10px 0;
        font-size: 16px;
        color: #999;
        display: block;
        border: none;
    }
    .table .trorder td {
        border-right: 1px solid #efefef;
    }
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        border-top: 1px solid #efefef;
        vertical-align: middle;
    }
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .order-title {
        line-height: 62px;
        font-size: 16px;
        color: #000;
        font-weight: bold;
    }
    .table .trorder td:nth-of-type(1) {
        border: none;
    }
    .table .trorder, .table .trfooter {
        border: 1px solid #efefef;
    }
    .table tr {
        height: 24px;
    }
    p {
        line-height: 2;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
    }
</style>
<div class="layui-row">
    <div class="layui-col-xs6">
        <div class="border">
            <ul class="ul">
                <li class="text"><span class="col-sm">订单编号：</span><span class="text-default"><?php echo htmlentities($order['ordersn']); ?></span></li>
                <li class="text"><span class="col-sm">买家：</span><span class="text-default"><?php if($nickname): ?><?php echo htmlentities($nickname); else: ?>匿名<?php endif; ?></span></li>
                <li class="text"><span class="col-sm">配送方式：</span><span class="text-default">配送</span></li>
                <li class="text"><span class="col-sm">收件人信息：</span><span class="text-default"><?php echo htmlentities($address['delivery_name']); ?> <?php echo htmlentities($address['delivery_mobile']); ?>  <?php echo htmlentities($address['delivery_area']); ?>  <?php echo htmlentities($address['delivery_address']); ?></span></li>
            </ul>
        </div>
    </div>
    <div class="layui-col-xs6">
        <div class="border">
            <ul class="ul">
                <li class="text" style="
    line-height: 22px;"><span class="col-sm">订单状态：</span>
                    <?php if($order['status'] == 0): ?>
                    <span class="text-default font18">待付款</span> (等待买家付款)
                    <?php endif; if($order['status'] == 1): ?>
                    <span class="text-default font18">待发货</span> (等待发货)
                    <?php endif; if($order['status'] == 2): ?>
                    <span class="text-default font18">待收货</span> (等待收货)
                    <?php endif; if($order['status'] == 3): ?>
                    <span class="text-default font18">已完成</span>
                    <?php endif; if($order['status'] == 4): ?>
                    <span class="text-default font18">已取消</span> (用户取消)
                    <?php endif; ?>

                </li>
                <?php if($order['express_no'] && $order['express_name']): ?>
                <li class="text"><span class="col-sm">快递公司：</span><span class="text-default"><?php echo htmlentities($order['express_name']); ?></span></li>
                <li class="text"><span class="col-sm">快递单号：</span><span class="text-default"><?php echo htmlentities($order['express_no']); ?></span></li>
                <li class="text"><span class="col-sm">发货时间：</span><span class="text-default"><?php echo htmlentities(date('Y-m-d H:i:s',!is_numeric($order['deliver_time'])? strtotime($order['deliver_time']) : $order['deliver_time'])); ?></span></li>
                <?php endif; if($order['status'] == 0): ?>
                <li class="text" style="margin-top: 15px;
    margin-left: 10px;"><a class="layui-btn layui-btn-danger layui-btn-xs" onclick="operation('payment','<?php echo htmlentities($order['id']); ?>')">确认付款</a></li>
                <?php endif; if($order['status'] == 1): ?>
                <li class="text" style="margin-top: 15px;
    margin-left: 10px;"><a class="layui-btn layui-btn-danger layui-btn-xs" onclick="operation('deliver','<?php echo htmlentities($order['id']); ?>')">确认发货</a></li>
                <?php endif; if($order['status'] == 2): ?>
                <li class="text" style="margin-top: 15px;
    margin-left: 10px;"><a class="layui-btn layui-btn-danger layui-btn-xs" onclick="operation('received','<?php echo htmlentities($order['id']); ?>')">确认收货</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div style="padding: 20px">
        <h3 class="order-title">商品信息</h3>
        <table class="table ">
            <thead>
            <tr class="trorder" style="background: #fff">
                <th class="" style="width: 75px;text-align: right;padding-right: 0">
                    商品标题
                </th>
                <th style="">
                </th>
                <th style="text-align: center;width: 10%">单价</th>
                <th style="text-align: center;width: 10%; border-right: 1px solid #efefef;">数量</th>
            </tr>
            </thead>
            <tbody>
            <?php if($order_goods): foreach($order_goods as $item): ?>
            <tr class="trorder" style="background: #fff">
                <td style="text-align: right;padding-right: 0">
                    <img src="<?php echo htmlentities($item['img']); ?>" style="width:52px;height:52px;border:1px solid #efefef; padding:1px;">
                </td>
                <td style="min-width: 300px">
                    <?php echo htmlentities($item['title']); ?>
                    <br/>
                    <?php echo htmlentities($item['short_title']); ?>
                </td>
                <td style="text-align: center">
                    <p>￥<?php echo htmlentities($item['price']); ?></p>
                </td>
                <td style="text-align: center">
                    <p><?php echo htmlentities($item['num']); ?></p>
                </td>


            </tr>
            <?php endforeach; ?>
            <?php endif; ?>


            </tbody>

            <tfoot style="padding-top: 20px">
            <tr class="trorder">
                <td colspan="2" style="padding-left: 20px;">                 </td>

                <td colspan="6" style="   text-align: right;
    padding: 15px;
">
                    <div class="price">
                        <p><span class="price-inner">商品数量：</span><span style="font-size: 14px;font-weight: bold;color: #e4393c"><?php echo htmlentities($order['total']); ?></span></p>
                        <p><span class="price-inner">运费：</span>￥0.00</p>
                        <p> <span class="price-inner">订单金额：</span><span style="font-weight: bold"><?php echo htmlentities($order['price']); ?></span></p>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <script>
        function operation(event,id){
            if(event == 'payment'){
                //确认付款
                layer.confirm('确认此订单已付款吗？',{title:'提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('payment'); ?>",
                        dataType: "json",
                        data:{id:id},
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
            }
            if(event == 'deliver'){
                //确认发货
                var url = "<?php echo url('deliver'); ?>?id="+id;
                xadmin.open('确认发货',url,400,250)
            }
            if(event == 'received'){
                //确认收货
                layer.confirm('确认此订单已收货吗？',{title:'提示'}, function(index){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('received'); ?>",
                        dataType: "json",
                        data:{id:id},
                        success: function(data, textStatus, request){

                            if( data.code == 20006){
                                //用户信息失效
                                layer.msg(data.msg,{anim:0,shade:0.6},function () {
                                    parent.location.reload();
                                    xadmin.del_tab();
                                    xadmin.close();
                                    // parent.location.href = "<?php echo url('login/index'); ?>"
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
            }
        }
    </script>
</div>

