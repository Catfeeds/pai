<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:66:"D:\project\pai\public/../application/member/view/address/edit.html";i:1542102895;s:65:"D:\project\pai\public/../application/member/view/common/base.html";i:1543280491;s:67:"D:\project\pai\public/../application/member/view/common/header.html";i:1542767234;s:67:"D:\project\pai\public/../application/member/view/common/js_sdk.html";i:1541491283;}*/ ?>

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
        
<!-- <link rel="stylesheet" type="text/css" href="__CSS__/address/register.css"> -->
<link rel="stylesheet" type="text/css" href="__STATIC__/lib/layui/css/layui.css">
<link rel="stylesheet" type="text/css" href="__CSS__/address/edit.css">

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
        <!-- <script src="__JS__/imsdk/sdk/webim.js" type="text/javascript"></script> -->
        <!--web im sdk 登录 示例代码-->
        <!-- <script src="__JS__/imsdk/js/login/login.js" type="text/javascript"></script> -->
        <!-- <script src="__JS__/login/loginsdk.js"></script> -->
        <!--web im sdk 登出 示例代码-->
        <!-- <script src="__JS__/imsdk/js/logout/logout.js" type="text/javascript"></script> -->
        
        <title></title>
    </head>
    <body>
        <header>
<div class="header_nav">
    <div class="header_view">
        <div class="header_tit">
            <span><?php echo isset($header_title) ? $header_title :  ''; ?></span>
            <div class="header_back" onClick="javascript:history.go(-1);">
                <img src="__STATIC__/icon/publish/icon_nav_back@2x.png" name='out' class="smt">
            </div>
        </div>
    </div>
</div>
<!--<div class="header_nav">-->
    <!--<div class="header_view">-->
        <!--<div class="header_tit">-->
            <!--添加收货地址-->
            <!--<div class="header_back edit_compile">-->
                <!--<img src="__STATIC__/icon/publish/icon_nav_back@2x.png" name='out' class="smt">-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->
<!--</div>-->
</header>
        <header></header>
        
<main>
    <form method="post" action="<?php echo url('address/edit'); ?>" id="edit_form" onsubmit="return form_submit()">
        <div class="register_list">
            <div class="register_inp editaddress_inp">
                <p>收货人<span></span></p>
                <input type="text" name="name" placeholder="请使用真实姓名" value="<?php echo (isset($info['name']) && ($info['name'] !== '')?$info['name']:''); ?>">
            </div>
        </div>
        <div class="register_list">
            <div class="register_inp editaddress_inp">
                <p>联系方式<span></span></p>
                <input type="text" name="tel" placeholder="请输入手机号" value="<?php echo (isset($info['tel']) && ($info['tel'] !== '')?$info['tel']:''); ?>">
            </div>
        </div>
        <div class="register_list">
            <div class="register_inp editaddress_inp">
                <p>所在地区<span></span></p>

                <input id="demo1" type="text" name="pid "  value="<?php echo (isset($info['pname']) && ($info['pname'] !== '')?$info['pname']:''); ?> <?php echo (isset($info['cname']) && ($info['cname'] !== '')?$info['cname']:''); ?> <?php echo (isset($info['dname']) && ($info['dname'] !== '')?$info['dname']:''); ?>" readonly="readonly">
                <input id="value1" type="hidden" name="area_id" value="<?php echo (isset($info['regids']) && ($info['regids'] !== '')?$info['regids']:''); ?>"/>
                <img src="__STATIC__/icon/publish/icon_nav_jump@2x.png">
            </div>
        </div>
        <div class="register_list register_area" id="demo1">
            <div class="register_inp clear editaddress_inp">
                <textarea type="text" id="textarea" name="address" placeholder="请输入详细地址"><?php echo (isset($info['address']) && ($info['address'] !== '')?$info['address']:''); ?></textarea>
            </div>
        </div>
        <div class="register_list editaddress_margin">
            <div class="register_inps">
                <?php if(empty($value) != true and $info['is_default'] == 1): ?>
                <img src="__STATIC__/image/register/icon_yixuanze@2x.png" class="address_icon">
                <img src="__STATIC__/image/register/icon_weixuanze@2x.png" class="">
                <?php else: ?>
                <img src="__STATIC__/image/register/icon_yixuanze@2x.png" class="">
                <img src="__STATIC__/image/register/icon_weixuanze@2x.png" class="address_icon">
                <?php endif; ?>
                <span class="address_text">设为默认地址</span>
                <!-- 1为选中 0为非选中-->
                <input type="hidden" name="is_default" value="<?php echo (isset($info['is_default']) && ($info['is_default'] !== '')?$info['is_default']:'0'); ?>"/>
            </div>
        </div>
        <input type="hidden" name="address_id" value="<?php echo (isset($address_id) && ($address_id !== '')?$address_id:'0'); ?>"/>
        <input type="hidden" name="encrypt" value="<?php echo (isset($encrypt) && ($encrypt !== '')?$encrypt:0); ?>"/>
        <input type="hidden" name="pm_id" value="<?php echo (isset($pm_id) && ($pm_id !== '')?$pm_id:0); ?>"/>
        <div class="register_btn">
            保存该地址
        </div>

    </form>
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
<script src="__STATIC__/lib/layui/layui.js"></script>
<script src="__JS__/common/popups.js"></script>
<script type="text/javascript" src="__JS__/applicationIn/textareaauto.js"></script>
<script type="text/javascript" src="__JS__/address/site_common.js"></script>
<script>
    $(function(){

        $(".edit_compile").click(function(){
            var name=$("input[name='name']").val();
            var tel=$("input[name='tel']").val();
            var adress=$("textarea[name='address']").val();
            if(name==""&&tel==""&&adress==""){
                window.location.href="<?php echo url('address/index'); ?>";
                return false;
            }

            layer.confirm('是否保存本次编辑结果？', {
                title:false,/*标题*/
                closeBtn: 0,
                btnAlign: 'c',
                btn: ['不保存','保存'], /*按钮*/
                btn1:function(){
                    window.location.href="<?php echo url('address/index'); ?>";
                },
                btn2:function(){
                    submitbtn();
                }
            })

        })

        // 设为默认地址
        $(".register_inps img").click(function(){
            $(".register_inps img").eq(0).toggleClass("address_icon");
            $(".register_inps img").eq(1).toggleClass("address_icon");

            var is_default = $("input[name=is_default]").val();
            if(is_default == 0){
                $('input[name="is_default"]').val("1");
            }else{
                $('input[name="is_default"]').val("0");
            }
        })
        var text=document.getElementById("textarea")
          autoTextarea(text);// 调用
        $('.register_btn').click(function(){
            submitbtn()
        });
        function submitbtn(){
            //昵称
            var textname=/^[a-zA-Z0-9\_\u4e00-\u9fa5]{1,30}$/;
            // var myreg=/^[1][3,4,5,7,8][0-9]{9}$/;
            var name=$("input[name='name']").val();
            if(!textname.test(name)){
                layer.msg("<span style='color:#fff'>收货人含有非法字符或者空格</span>",{
                    time:2000
                });
                return false;
            }
            if(name==""){
                layer.msg("<span style='color:#fff'>收货人不能为空</span>",{
                    time:2000
                });
                return false;
            };
            if(name.length>15){
                layer.msg("<span style='color:#fff'>收货人姓名长度不能大于15位</span>",{
                    time:2000
                });
                return false;
            };
            if(name.length<2){
                layer.msg("<span style='color:#fff'>收货人姓名长度不能小于2位</span>",{
                    time:2000
                });
                return false;
            };
            //联系方式
            var tel=$("input[name='tel']").val();
            var reg =/^1[3-9][0-9]{9}$|^0\d{2,3}-?\d{7,8}$/;

            if(tel==""){
                layer.msg("<span style='color:#fff'>联系方式不能为空</span>",{
                    time:2000
                });
                return false;
            }else if (!reg.test(tel)) {
                layer.msg("<span style='color:#fff'>联系方式输入有误</span>",{
                    time:2000
                });
                return false;
            };
            //详细地址
            // var textaddress=/^[a-zA-Z0-9\s\_\u4e00-\u9fa5]{5,50}$/;
            // var textaddress=/^[\u4e00-\u9fa5a-zA-Z0-9]{5,50}$/;
            // var textaddress=/^\d{5,50}$/;
            var adress=$("textarea[name='address']").val();
            if(adress.length>50){
                layer.msg("<span style='color:#fff'>请输入5-50个字符</span>",{
                    time:3000
                });
                return false;
            }
            if(adress.length<5){
                layer.msg("<span style='color:#fff'>请输入5-50个字符</span>",{
                    time:3000
                });
                return false;
            }
            // if(!textaddress.test(adress)){
            //     layer.msg("<span style='color:#fff'>请输入5-50个字符</span>",{
            //         time:3000
            //     });
            //     return false;
            // }
            var index = layer.load(0, {shade: false});

            // 提交
            $.ajax({
                url:"<?php echo url('address/edit'); ?>",
                dataType:'JSON',
                type:'POST',
                data:$('#edit_form').serialize(),
                success: function(data) {
                    console.log(data);
                    if(data.status){
                        layer.close(index);
                        layer.msg("<span style='color:#fff'>"+data.msg+"</span>",{
                            time:3000
                        });
                        var encrypt = $("input[name=encrypt]").val();
                        var pm_id = $("input[name=pm_id]").val();
                        // if(encrypt>0){
                        //     window.location.href="/member/address/index/encrypt/" + encrypt + "/pm_id/"+pm_id;
                            
                        // }else{
                            window.history.go(-1);
                        // }
                        
                    }else{
                        //提示弹窗
                        layer.close(index);
                        layer.msg("<span style='color:#fff'>"+data.msg+"</span>",{
                            time:3000
                        });
                    }
                }
            });
        }
    })
</script>

    <!-- <script>
        $(function(){
            webimLogin();
        })
    </script>  -->
</html>