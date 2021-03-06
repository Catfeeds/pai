<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:71:"D:\project\pai\public/../application/member/view/orderpai/pai_rule.html";i:1541491284;s:65:"D:\project\pai\public/../application/member/view/common/base.html";i:1542013165;s:67:"D:\project\pai\public/../application/member/view/common/header.html";i:1541491283;s:67:"D:\project\pai\public/../application/member/view/common/js_sdk.html";i:1541491283;}*/ ?>

<!DOCTYPE html>
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta name="full-screen" content="yes">
        <meta name="x5-fullscreen" content="true">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 viewport-fit=cover">
        <meta name="format-detection" content="telephone=no">
        <!--<link rel="stylesheet" type="text/css" href="__CSS__/mui/mui.min.css">-->
        <!--<link rel="stylesheet" type="text/css" href="__CSS__/common/bootstrap.min.css">-->

        <!--<link rel="stylesheet" type="text/css" href="__STATIC__/lib/bootstrap-fileinput-master/css/fileinput.min.css">-->
        <link rel="stylesheet" type="text/css" href="__CSS__/common/larea.css">
        <link rel="stylesheet" type="text/css" href="__STATIC__/lib/layui/css/layui.css">
        <link rel="stylesheet" type="text/css" href="__CSS__/common/size.css">
        <link rel="stylesheet" type="text/css" href="__CSS__/common/popups.css">
        
<link rel="stylesheet" type="text/css" href="__CSS__/goodsproduct/rule.css">
<style>
    .header_nav{
        background: #fff;
    }
    .header_tit span{
        color:#000;
    }
</style>

        <script type="text/javascript" src="__JS__/jquery-1.11.1.min.js"></script>
        <script src="__JS__/common/rem.js"></script>
        <script src="__JS__/common/jquery_lazyload.js"></script>
        <script src="__JS__/common/lazyload.js"></script>
        <!--<script src="__JS__/mui/mui.min.js"></script>-->
        <!--<script src="__JS__/mui/mui.pullToRefresh.js"></script>-->
        <!--<script src="__JS__/mui/mui.pullToRefresh.material.js"></script>-->
        <script src="__JS__/common/site.js"></script>
        <script src="__JS__/common/larea.js"></script>
        <!--<script src="__JS__/common/bootstrap.min.js"></script>-->
        <!--<script type="text/javascript" src="__STATIC__/lib/bootstrap-fileinput-master/js/fileinput.js"></script>-->
        <!--<script type="text/javascript" src="__STATIC__/lib/bootstrap-fileinput-master/js/locales/zh.js"></script>-->
        <script src="__STATIC__/lib/layui/layui.js"></script>
        <script src="__JS__/common/popups.js"></script>
        <script src="__JS__/common/vconsole.min.js"></script>
        <title></title>
    </head>
    <body>
        <header>
<div class="header_nav">
    <div class="header_view">
        <div class="header_tit">
            <span><?php echo isset($header_title) ? $header_title :  ''; ?></span>
            <div class="header_back" <?php if(empty($header_path) || (($header_path instanceof \think\Collection || $header_path instanceof \think\Paginator ) && $header_path->isEmpty())): ?> onClick="javascript:history.back();" <?php else: ?> onClick="javascript:window.location.href='<?php echo $header_path; ?>'" <?php endif; ?>>
                <img src="__STATIC__/icon/publish/icon_nav_back@2x.png" name='out' class="smt">
            </div>
        </div>
    </div>
</div>
</header>
        <header></header>
        
<main>
    <div class="rule">
        <div>
            <div class="rule_list">
                <div class="rule_bg_img">
                    <img src="__STATIC__/image/goodsproduct/tuoyuan1818@2x.png" alt=""/>
                </div>
                <p>如何获得吖吖码</p>
            </div>
            <div class="rule_mian_text">
                <span>用户下单付款，将获得与参团份数相对应的吖吖码。吖吖码由加密的用户ID、付款序列号组成。付款序列号将直接决定是否团中，用户ID为唯一的既定的不可更改的标识</span>
            </div>
        </div>
        <div>
            <div class="rule_list">
                <div class="rule_bg_img">
                    <img src="__STATIC__/image/goodsproduct/tuoyuan1818@2x.png" alt=""/>
                </div>
                <p>吖吖码揭晓方式</p>
            </div>
            <div class="rule_mian_text">
                <p>一、达成目标</p>
                <span class="rule_main_span">人次目标数由商品的市场价与折扣类型计算而出，达到目标将立即计算团中的吖吖号</span>
            </div>
            <div class="rule_mian_text rule_margin">
                <p>二、获得时间戳</p>
                <span class="rule_main_span">拍吖吖根据该轮最后一个人次的付款时间计算时间戳，获得一个总秒数</span>
            </div>
            <div class="rule_mian_text rule_margin">
                <p>三、时间戳取余</p>
                <span class="rule_main_span">通过时间戳获得的总秒数与总人次取余数再+1，得到一个付款序列号，即确定团中的吖吖码</span>
            </div>
        </div>
        <div>
            <div class="rule_list">
                <div class="rule_bg_juxing">
                    <img src="__STATIC__/image/goodsproduct/yuxing1905@2x.png" alt=""/>
                </div>
                <p>举例说明</p>
            </div>
            <div class="rule_mian_text">
                <span>1.商品A的5折价目标人次为100人，您作为第50人次参团付款，得到一个吖吖码[YYM233520-<small>50</small>]</span>
            </div>
            <div class="rule_mian_text rule_margin_top">
                <span>2.第100人次付款时间为2018-07-23 12:00:00，目标达成后则可根据时间戳计算出总秒数：1532318400（s)</span>
            </div>
            <div class="rule_mian_text rule_margin_top">
                <span>3. 1532318400÷100 =15323184……0，余数0+1=1，本轮团中的吖吖码则为[YYM568121-<small>1</small>]</span>
            </div>
        </div>
        <div>
            <div class="rule_list">
                <div class="rule_bg_juxing">
                    <img src="__STATIC__/image/goodsproduct/yuxing1905@2x.png" alt=""/>
                </div>
                <p>注意事项</p>
            </div>
            <div class="rule_mian_text">
                <span>时间戳指格林威治时间1970年01月01日00时00分00秒(北京时间1970年01月01日08时00分00秒秒)起至现在的总秒数。时间戳是一份能够表示一份数据在一个特定时间点已经存在的完整的可验证的数据，它可以用来支撑公开密钥基础设施的“不可否认” 服务，绝对公平公正</span>
            </div>
        </div>
    </div>
</main>

        <footer></footer>
    </body>

    <!--bugtags 开始-->
    <!-- <script src="https://dn-bts.qbox.me/sdk/bugtags-1.0.3.js"></script> -->
    <!-- <script> -->
        <!-- // VERSION_NAME 替换为项目的版本，VERSION_CODE 替换为项目的子版本 -->
        <!-- // new Bugtags('bbbe041d223432b3e8bf8a294674dfe5','VERSION_NAME','VERSION_CODE'); -->
    <!-- </script> -->
    <!--bugtags 结束-->

    
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="__STATIC__/js/iphoneXfooter.js"></script>
<script>
//    title = '邀您入驻拍吖吖，享大福利！';
    var title = '';
    var share_title = "<?php echo isset($share_title) ? $share_title :  ''; ?>";
    if($.trim(share_title)){
        title = share_title;
    }else{
        title = '拍吖吖：让快乐充满你的拍子！';
    }

//    var desc = '5折！3折！1折！还有1元价！尽在拍吖吖，快来抢购吧';
    var desc = '';
    var share_desc = "<?php echo isset($share_desc) ? $share_desc :  ''; ?>";
    if($.trim(share_desc)){
        desc = share_desc;
    }else{
        desc = '5折！3折！1折！还有1元价！尽在拍吖吖，快来抢购吧！';
    }

    var link = '';
    var share_link = "<?php echo isset($share_link) ? $share_link :  ''; ?>";
    if($.trim(share_link)){
        link = share_link;
    }else{
        link = "https://m.paiyy.com.cn/";
    }
    //var imgUrl = 'https://gss0.bdstatic.com/70cFsj3f_gcX8t7mm9GUKT-xh_/avatar/100/r6s1g4.gif';
    var imgUrl = '';
    var share_imgUrl = "<?php echo isset($share_imgUrl) ? $share_imgUrl :  ''; ?>";
    if($.trim(share_imgUrl)){
        imgUrl = share_imgUrl;
    }else{
        imgUrl = "https://m.paiyy.com.cn/uploads/logo/1.jpg";
    }
    wx.config({
//        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "<?php echo $appId; ?>", // 必填，公众号的唯一标识
        timestamp: "<?php echo $timestamp; ?>", // 必填，生成签名的时间戳
        nonceStr: "<?php echo $noncestr; ?>", // 必填，生成签名的随机串
        signature: "<?php echo $signature; ?>",// 必填，签名
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
        ], // 必填，需要使用的JS接口列表
    });
    wx.ready(function(){
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: title,
            link: link,
            imgUrl: imgUrl,
        });
        //分享给朋友
        wx.onMenuShareAppMessage({
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: imgUrl, // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        });
        //分享到QQ
        wx.onMenuShareQQ({
            title:title,
            desc: desc,
            link:link,
            imgUrl: imgUrl,
        });
        //分享到腾讯微博
        wx.onMenuShareWeibo({
            title:title,
            desc: desc,
            link:link,
            imgUrl: imgUrl,
        });
        wx.onMenuShareQZone({
            title:title,
            desc: desc,
            link:link,
            imgUrl: imgUrl,
        });
    });

</script>

</html>