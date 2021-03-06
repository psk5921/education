<?php /*a:2:{s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/index/welcome.html";i:1575367893;s:79:"/www/wwwroot/education.kedaweilai.com/application/admin/view/public/header.html";i:1575443563;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <meta name="keywords" content="后台管理" />
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
<link rel="stylesheet" href="/source/kdwl_admin/css/admin.css">
<style>
    .chise{
        color:#FF5722!important;
    }
    .ft30{
        font-size: 30px!important;
    }
    .pos{
        position: relative;
        left: 50%;
    }
  body{
      background: #f1f1f1;
  }
</style>
<body>
<div id="app">


<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <!--<div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <blockquote class="layui-elem-quote">欢迎管理员：
                        <span class="x-red">{{username}}</span> ！当前时间：<span class="x-red">{{now}}</span>&nbsp;&nbsp;&nbsp;
                        上一次登录时间：<span class="x-red">{{login_time}}</span>&nbsp;&nbsp;&nbsp;
                        总登录次数：<span class="x-red">{{login_count}}</span>&nbsp;&nbsp;&nbsp;
                        登录IP：<span class="x-red">{{login_ip}}</span>
                    </blockquote>
                </div>
            </div>
        </div>-->
        <div class="layui-col-sm6 layui-col-md2">
            <div class="layui-card">
                <div class="layui-card-header">
                    总会员数量
                   <!-- <span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>-->
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font chise" id="total"><i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30">&#xe63d;</i> </p>
                    <p>
                        Total Members Counts
                       <!-- <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>-->
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md2">
            <div class="layui-card">
                <div class="layui-card-header">
                    当月新增会员数量
                    <!--<span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>-->
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font chise" id="current"><i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30">&#xe63d;</i> </p>
                    <p>
                        Current Month Members Counts
                       <!-- <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>-->
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md2">
            <div class="layui-card">
                <div class="layui-card-header">
                    粉丝用户数量
                  <!--  <span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>-->
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font chise" id="fans"><i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30">&#xe63d;</i> </p>
                    <p>
                        Fans Counts
                       <!-- <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>-->
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md2">
            <div class="layui-card">
                <div class="layui-card-header">
                    付费用户数量
                    <!--<span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>-->
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font chise" id="pays"><i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30">&#xe63d;</i> </p>
                    <p>
                        Pays Counts
                     <!--   <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>-->
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md2">
            <div class="layui-card">
                <div class="layui-card-header">
                    总收入
                    <!--<span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>-->
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font chise" id="total_pays"><i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30">&#xe63d;</i> </p>
                    <p>
                        Total Pays
                        <!--   <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>-->
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm6 layui-col-md2">
            <div class="layui-card">
                <div class="layui-card-header">
                    当月新增收入
                    <!--<span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>-->
                </div>
                <div class="layui-card-body layuiadmin-card-list">
                    <p class="layuiadmin-big-font chise" id="current_pays"><i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30">&#xe63d;</i> </p>
                    <p>
                        Current Month Pays
                        <!--   <span class="layuiadmin-span-color">88万 <i class="layui-icon"></i></span>-->
                    </p>
                </div>
            </div>
        </div>
        <div class="layui-col-sm12">
            <div class="layui-card">
                <div class="layui-card-header">
                    营业收入统计图(Statistical Chart Of Operating Revenue)
                </div>
                <div class="layui-card-body">
                    <div class="layui-row">
                        <div class="layui-col-sm12" id="main1" style="height: 300px;text-align: center">
                            <i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30 chise">&#xe63d;</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-sm12">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm6 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">商城销售统计列表(Shopping Mall Sales Statistics List)</div>
                        <div class="layui-card-body">
                            <table class="layui-table layuiadmin-page-table" lay-skin="line" id="html2" >
                                <i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30 chise pos">&#xe63d;</i>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">课程销售统计列表(Course Sales Statistics List)</div>
                        <div class="layui-card-body">
                            <table class="layui-table layuiadmin-page-table" lay-skin="line" id="html1">
                                <i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30 chise pos">&#xe63d;</i>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-sm12">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm6 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">最新商城订单列表(Latest Shopping Mall Order List)</div>
                        <div class="layui-card-body">
                            <table class="layui-table layuiadmin-page-table" lay-skin="line" id="html3" >
                                <i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30 chise pos">&#xe63d;</i>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">最新课程订单列表(Latest Course Order List)</div>
                        <div class="layui-card-body" >
                            <table class="layui-table layuiadmin-page-table" lay-skin="line"  id="html4">
                                <i class="layui-anim layui-icon layui-anim-rotate layui-anim-loop ft30 chise pos">&#xe63d;</i>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style id="welcome_style"></style>
    </div>
</div>
</div>
</div>
</div>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            username: "<?php echo htmlentities($username); ?>",
            now:"<?php echo htmlentities($now); ?>",
            login_count:"<?php echo htmlentities($login_count); ?>",
            login_time:"<?php echo htmlentities($login_time); ?>",
            login_ip:"<?php echo htmlentities($login_ip); ?>",
            server_software:"<?php echo htmlentities($server_software); ?>",
            server_name:"<?php echo htmlentities($server_name); ?>",
            php_version:"<?php echo htmlentities($php_version); ?>",
            sapi_name:"<?php echo htmlentities($sapi_name); ?>",
            version:"<?php echo htmlentities(app()->version()); ?>",
            mysql_version:"<?php echo htmlentities($mysql_version); ?>",
            upload:"<?php echo htmlentities($upload); ?>",
            max_execution_time:"<?php echo htmlentities($max_execution_time); ?>s",
            max_file_uploads:"<?php echo htmlentities($max_file_uploads); ?>M",
            memory_usage:"<?php echo htmlentities($memory_usage); ?>",
        },
        methods: {},
        //监听属性
        watch: {

        },
        //计算属性
        computed: {

        }

    });
</script>
<script src="/source/kdwl_admin/js/echarts.min.js"></script>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    function inits() {
        var index_load = {
            init:function() {
                this.getData_floor1();
               this.getData_floor2();
               this.getData_floor3();
               this.getData_floor4();
            },
            getData_floor1:function () {
                //获取四个数值ajax 请求
                $.get("<?php echo url('getData_floor1'); ?>",'',function (res) {
                    if(res.code == 1){
                        $('#total').html(res.data.total);
                        $('#current').html(res.data.current);
                        $('#fans').html(res.data.fans);
                        $('#pays').html(res.data.pays);
                        $('#total_pays').html(res.data.total_pays);
                        $('#current_pays').html(res.data.current_pays);
                    }
                },'json')
            },
            getData_floor2:function () {
                //获取四个数值ajax 请求
                $.get("<?php echo url('getData_floor2'); ?>",'',function (res) {
                    if(res.code == 1){
                        $('#main1').html('');
                        chart(res.data)
                    }
                },'json')
            },
            getData_floor3:function () {
                //获取四个数值ajax 请求
                $.get("<?php echo url('getData_floor3'); ?>",'',function (res) {
                    $('#html1').parent().find('i').remove();
                    $('#html2').parent().find('i').remove();
                    if(res.code == 1){
                        $('#html1').html(res.data.html1);
                        $('#html2').html(res.data.html2);
                    }
                },'json')
            },
            getData_floor4:function () {
                //获取四个数值ajax 请求
                $.get("<?php echo url('getData_floor4'); ?>",'',function (res) {
                    $('#html3').parent().find('i').remove();
                    $('#html4').parent().find('i').remove();
                    if(res.code == 1){
                        $('#html3').html(res.data.html3);
                        $('#html4').html(res.data.html4);
                    }
                },'json')
            },
        };
        index_load.init();
    }
    inits();
    var  chart = function (dom) {
        var myChart = echarts.init(document.getElementById('main1'));
        var option = {
            legend: {},
            tooltip: {},
            dataset: {
                source: dom
            },
            xAxis: {type: 'category'},
            yAxis: {},
            // Declare several bar series, each will be mapped
            // to a column of dataset.source by default.
            series: [
                {type: 'bar',color:['#00BFFF']},
                {type: 'bar',color:['#00FFFF']}
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    };

   function fahuo(id) {
       if(isNaN(id)) return false;
       //确认发货
       var url = "<?php echo url('order/deliver'); ?>?id="+id;
       xadmin.open('确认发货',url,400,250)
   }
    // 指定图表的配置项和数据
    /*var option = {
        grid: {
            top: '5%',
            right: '1%',
            left: '1%',
            bottom: '10%',
            containLabel: true
        },
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            type: 'category',
            data: ['周一','周二','周三','周四','周五','周六','周日']
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            name:'用户量',
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: 'line',
            smooth: true
        }]
    };*/


  /*  // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main2'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        grid: {
            top: '5%',
            right: '2%',
            left: '1%',
            bottom: '10%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : ['周一','周二','周三','周四','周五','周六','周日']
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'PV',
                type:'line',
                areaStyle: {normal: {}},
                data:[120, 132, 101, 134, 90, 230, 210],
                smooth: true
            },
            {
                name:'UV',
                type:'line',
                areaStyle: {normal: {}},
                data:[45, 182, 191, 234, 290, 330, 310],
                smooth: true,

            }
        ]
    };


    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);*/


   /* // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main3'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
        },
        series : [
            {
                name: '访问来源',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[
                    {value:335, name:'直接访问'},
                    {value:310, name:'邮件营销'},
                    {value:234, name:'联盟广告'},
                    {value:135, name:'视频广告'},
                    {value:1548, name:'搜索引擎'}
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };



    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);*/

  /*  // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main4'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            formatter: "{a} <br/>{b} : {c}%"
        },
        series: [
            {
                name: '硬盘使用量',
                type: 'gauge',
                detail: {formatter:'{value}%'},
                data: [{value: 88, name: '已使用'}]
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);*/
</script>
</body>
</html>