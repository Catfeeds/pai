<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:71:"D:\project\pai\public/../application/index/view/index/search_index.html";i:1542858443;s:63:"D:\project\pai\public/../application/index/view/index/base.html";i:1543280491;s:66:"D:\project\pai\public/../application/index/view/common/js_sdk.html";i:1541491293;}*/ ?>

<!DOCTYPE html>
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta name="full-screen" content="yes">
        <meta name="x5-fullscreen" content="true">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 viewport-fit=cover">
        <meta name="format-detection" content="telephone=no">
        <link rel="stylesheet" type="text/css" href="__CSS__/common/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="__STATIC__/lib/bootstrap-fileinput-master/css/fileinput.min.css">
        <link rel="stylesheet" type="text/css" href="__CSS__/common/larea.css">
        <link rel="stylesheet" type="text/css" href="__CSS__/common/size.css">
        <link rel="stylesheet" type="text/css" href="__STATIC__/lib/layui/css/layui.css">
        <link rel="stylesheet" type="text/css" href="__CSS__/common/popups.css">
        
<link rel="stylesheet" type="text/css" href="__CSS__/mescroll/mescroll.min.css">
<link rel="stylesheet" type="text/css" href="__STATIC__/css/productlist/product_list.css">
<link rel="stylesheet" type="text/css" href="__CSS__/shop/index.css">
<link rel="stylesheet" type="text/css" href="__CSS__/index/index.css">
<link rel="stylesheet" type="text/css" href="__CSS__/storecollection/store_list.css">
<link rel="stylesheet" type="text/css" href="__CSS__/wallet/search_index.css"> 
        <script type="text/javascript" src="__JS__/jquery-1.11.1.min.js"></script>
        <script src="__JS__/common/rem.js"></script>
        <script src="__JS__/common/jquery_lazyload.js"></script>
        <script src="__JS__/common/lazyload.js"></script>
        <script src="__JS__/common/site.js"></script>
        <script src="__JS__/common/larea.js"></script>
        <script src="__JS__/common/bootstrap.min.js"></script>
        <script type="text/javascript" src="__STATIC__/lib/bootstrap-fileinput-master/js/fileinput.js"></script>
        <script type="text/javascript" src="__STATIC__/lib/bootstrap-fileinput-master/js/locales/zh.js"></script>
        <script src="__STATIC__/lib/layui/layui.js"></script>
        <script src="__JS__/common/popups.js"></script>
        <!-- <script src="__JS__/imsdk/sdk/webim.js" type="text/javascript"></script> -->
        <!--web im sdk 登录 示例代码-->
        <!-- <script src="__JS__/imsdk/js/login/login.js" type="text/javascript"></script> -->
        <!-- <script src="__JS__/login/loginsdk.js"></script> -->
        <!--web im sdk 登出 示例代码-->
        <!-- <script src="__JS__/imsdk/js/logout/logout.js" type="text/javascript"></script> -->
        <title>首页</title>
    </head>
    <body>
        <header></header>
        
<main>
    <div class="search_fixed">
        <!--搜索框-->
        <div class="index_search_pop_top clear">
            <a href="/">
                <div class="index_pop_text lf">
                    <img src="__STATIC__/icon/publish/icon_nav_back@2x.png" alt="" />
                </div>
            </a>
            <div class="index_search_pop_view clear lf">
                <div class="index_search_lfimg" class="lf">
                    <img src="__STATIC__/image/index/searchbar_icon_search@2x.png">
                </div>
                <input type="text" name="keyword" value="<?php echo $keyword; ?>" class="index_pop_inp lf">
                <div class="index_search_cancel rt">
                    <img src="__STATIC__/image/index/icon_search_clear@2x.png" alt="">
                </div>
            </div>
            <div type="submit" class="index_pop_sousuo rt">搜索</div>
        </div>
        <!--tab切换-->
        <div class="index_pop_class clear">
            <div class="index_pop_tab lf">
                <img src="__STATIC__/image/index/icon_nav_scroll@2x.png" alt="">
                <span>商品</span>
            </div>
            <div class="index_pop_tab  lf">
                <img src="__STATIC__/image/index/icon_nav_scroll@2x.png" alt="">
                <span>店铺</span>
            </div>
            <input type="hidden" name="type" value="<?php echo $type; ?>" />
        </div>
        <div class="shop_index_tab clear">
            <div class="shop_index_tab_list shop_index_tab_bold lf shop_index_all_list" data="8">
                <span>综合</span>
            </div>
            <div class="shop_index_tab_list shop_index_tab_filter lf">
                <span>人数</span>
                <div class="shop_index_tab_default">
                    <img src="__STATIC__/image/shop/icon_nav_defelt@2x.png" alt="">
                </div>
                <div class="shop_index_filter">
                    <img src="__STATIC__/image/shop/icon_nav_on@2x.png" alt="" data="3">
                    <img src="__STATIC__/image/shop/icon_nav_down@2x.png" alt="" class="shop_index_filter_img" data="4">
                </div>
            </div>
            <div class="shop_index_tab_list shop_index_tab_price lf">
                <span>价格</span>
                <div class="shop_index_tab_default">
                    <img src="__STATIC__/image/shop/icon_nav_defelt@2x.png" alt="">
                </div>
                <div class="shop_index_filter">
                    <img src="__STATIC__/image/shop/icon_nav_on@2x.png" alt="" data="5">
                    <img src="__STATIC__/image/shop/icon_nav_down@2x.png" alt="" class="shop_index_filter_img" data="6">
                </div>
            </div>
            <div class="shop_index_tab_list shop_filter lf">
                <span>筛选</span>
                <img src="__STATIC__/image/wallet/icon_nav_down_selected@2x.png" alt="" class="search_index_img search_xuanzhuan" />
                <img src="__STATIC__/image/wallet/icon_down@2x.png" alt="" class="search_index_img" />
            </div>
            <input type="hidden" name="order" />
        </div>
    </div>
    <div class="search_index_pop">
        <div class="shop-over"></div>
        <div class="search_index_positon">
            <div class="search_index_select clear">
                <div class="search_index_price_interval clear">
                    <span class="search_index_text lf">价格区间</span>
                    <div class="search_index_inp rt">
                        <input type="text" placeholder="最低价" name="little" />
                        <span>至</span>
                        <input type="text" placeholder="最高价" name="big" />
                    </div>
                </div>
                <div class=" clear">
                    <div class="search_index_search clear rt">
                        <div class=" lf">
                            <span class="search_index_img">
                                <img src="__STATIC__/image/wallet/icon_selected@2x.png" alt="" />
                            </span>
                            <p>
                                <span class="search_little">0</span>-<span class="search_big">100</span>
                            </p>
                        </div>
                        <div class="lf">
                            <span class="search_index_img">
                                <img src="__STATIC__/image/wallet/icon_selected@2x.png" alt="" />
                            </span>
                            <p>
                                <span class="search_little">100</span>-<span class="search_big">1000</span>
                            </p>
                        </div>
                        <div class="lf">
                            <span class="search_index_img">
                                <img src="__STATIC__/image/wallet/icon_selected@2x.png" alt="" />
                            </span>
                            <p>
                                <span class="search_little">1000</span>-<span class="search_big">10000</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="search_index_price_interval search_index_margin clear">
                    <span class="search_index_text lf">拼单状态</span>
                    <div class="search_index_status lf">仅显示有人正在拼的单</div>
                    <input name='activity' type='hidden'>
                </div>
            </div>
            <div class="search_index_btn">
                确定
            </div>
        </div>
    </div>
    <div id="mescroll" class="mescroll">
			<!--展示上拉加载的数据列表-->
			<div id="dataList" class="data-list">

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
    <!-- // </script> -->
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

//    var link = PAI_URL.'/member/register/it_to_rg/phone/<?php echo isset($info['m_mobile']) ? $info['m_mobile'] :  ""; ?>';
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
        imgUrl = "https://m.paiyy.com.cn"+'/uploads/logo/1.jpg';
    }
    wx.config({
      //  debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
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
<script src="__JS__/mescroll/mescroll.min.js"></script>
<script src="__JS__/Public.js"></script>
<script>
    



    // alert($("input[name='type']").val());
    // window.onpageshow = function(event) {
    //     if (event.persisted) {
    //         window.location.reload();
    //     }
    // }
    // window.sessionStorage.getItem("type")//从sessionStorage中取数据
    $(function () {
        var type = "<?php echo $type; ?>";
        var keyword = "<?php echo $keyword; ?>";
        // window.sessionStorage.setItem("type",type);//存数据到sessionStorage
        //创建MeScroll对象,内部已默认开启下拉刷新,自动执行up.callback,刷新列表数据;
        var mescroll = new MeScroll("mescroll", {
            //下拉刷新
            down: {
                isLock: true, //锁定下拉
            },
            //上拉加载的配置项
            up: {
                callback: getListData, //上拉回调,此处可简写; 相当于 callback: function (page) { getListData(page); }
                isBounce: false, //此处禁止ios回弹,解析(务必认真阅读,特别是最后一点): http://www.mescroll.com/qa.html#q10
                noMoreSize: 1, //如果列表已无数据,可设置列表的总数量要大于半页才显示无更多数据;避免列表数据过少(比如只有一条数据),显示无更多数据会不好看; 默认5
                empty: {
                    icon: "/static/image/index/no-cont.png", //图标,默认null
                    tip: "暂无相关搜索内容，但你还有吖吖", //提示
                },
                clearEmptyId: "dataList", //相当于同时设置了clearId和empty.warpId; 简化写法;默认null
                htmlLoading: '<p class="upwarp-progress mescroll-rotate"></p><p class="upwarp-tip">加载中..</p>', //上拉加载中的布局
                htmlNodata: '<p class="upwarp-nodata">我是有底线的~</p>', //无数据的布局
                toTop: {
                    //配置回到顶部按钮
                    src: "/static/image/application/mescroll-totop.png", //默认滚动到1000px显示,可配置offset修改
                    //offset : 1000
                }
            }
        });

        /*联网加载列表数据  page = {num:1, size:10}; num:当前页 从1开始, size:每页数据条数 */
        function getListData(page) {
            //联网加载数据
            getListDataFromNet(page.num, page.size, function (curPageData) {
                //方法四 (不推荐),会存在一个小问题:比如列表共有20条数据,每页加载10条,共2页.如果只根据当前页的数据个数判断,则需翻到第三页才会知道无更多数据,如果传了hasNext,则翻到第二页即可显示无更多数据.
                mescroll.endSuccess(curPageData.length);

                //设置列表数据
                setListData(curPageData);
            }, function () {
                //联网失败的回调,隐藏下拉刷新和上拉加载的状态;
                mescroll.endErr();
            });
        }

        //清空搜索框
        $('.index_search_cancel').on("click", function () {
            $('.index_pop_inp').val('');
        })
        //重置条件
        function resests() {
            $('input[name="little"]').val('');
            $('input[name="big"]').val('');
            $('input[name="order"]').val('');
            $('input[name="activity"]').val('');
        }
        //判断搜索的是商品还是商家
        if (type == 2) {
            $(".index_pop_class").children("div").eq(0).removeClass("index_pop_bold");
            $(".index_pop_class").children("div").eq(1).addClass("index_pop_bold");
            $(".shop_index_tab").removeClass("search_index_block");
            $(".mescroll").css({"top":"2rem"});
        } else if (type == 1) {
            $(".index_pop_class").children("div").eq(1).removeClass("index_pop_bold");
            $(".index_pop_class").children("div").eq(0).addClass("index_pop_bold");
            $(".shop_index_tab").addClass("search_index_block");
            $(".mescroll").css({"top":"2.85rem"});
        }
        //点击搜索的tab切换
        $(".index_pop_tab").click(function () {
            $(".index_pop_tab").removeClass("index_pop_bold");
            $(this).addClass("index_pop_bold");
            if ($(this).index() == 0) {
                $('.index_pop_class').children("input").val("1");
                $(".shop_index_tab").addClass("search_index_block");
                $(".mescroll").css({"top":"2.85rem"});
            } else {
                $('.index_pop_class').children("input").val("2");
                $(".shop_index_tab").removeClass("search_index_block");
                $(".search_index_pop").removeClass("search_index_block");
                $(".mescroll").css({"top":"2rem"});
            }
            //重置条件
            resests();
            //重置列表数据
            mescroll.resetUpScroll();
            //隐藏回到顶部按钮
            mescroll.hideTopBtn();
        })
        //点击搜索
        $(".index_pop_sousuo").click(function () {
            $(".shop_index_tab_list").removeClass("shop_index_tab_bold");
            $(".shop_index_tab_list").eq(0).addClass("shop_index_tab_bold");
            
            //重置条件
            resests();

            //重置列表数据
            mescroll.resetUpScroll();
            //隐藏回到顶部按钮
            mescroll.hideTopBtn();
        })

        //关闭筛选
        $('.shop-over').click(function () {
            $(".search_index_pop").toggleClass("search_index_block");
        })

        //点击筛选
        $(".shop_filter").click(function () {
            $(".search_index_pop").toggleClass("search_index_block");
            $(this).children("img").toggleClass("search_xuanzhuan");
        });

        //点击拼单状态
        $(".search_index_status").click(function () {
            $(this).toggleClass("search_index_status_red");
            if ($(this).is(".search_index_status_red") == true) {
                $('input[name="activity"]').val('1');
            } else {
                $('input[name="activity"]').val('');
            }
        })

        //点击确定
        $(".search_index_btn").click(function () {
            if($('input[name="little"]').val() != '' && !mm.test($('input[name="little"]').val())) {
                layer.msg('<span style="color:#fff;">您输入的最低价格式不正确</span>',{time:2000});
                return false;
            }
            if($('input[name="big"]').val() != '' && !mm.test($('input[name="big"]').val())) {
                layer.msg('<span style="color:#fff;">您输入的最高价格式不正确</span>',{time:2000});
                return false;
            }

            if($('input[name="little"]').val() != '' || $('input[name="big"]').val() != '' || $('.search_index_status').is('.search_index_status_red') == true) {
                //重置列表数据
                mescroll.resetUpScroll();
                //隐藏回到顶部按钮
                mescroll.hideTopBtn();
            }

            $(".search_index_pop").toggleClass("search_index_block");
        })

        //点击价格选择
        $(".search_index_search div").click(function () {
            $(".search_index_search div").removeClass("search_index_red");
            $(this).addClass("search_index_red");
            var lval = $(this).find(".search_little").html();
            var bval = $(this).find(".search_big").html();
            $("input[name='little']").val(lval);
            $("input[name='big']").val(bval);
        })

        //点击切换排序
        $('.shop_index_tab').find(".shop_index_tab_list").click(function () {
            var min_price = $("input[name='little']").val();
            var max_price = $("input[name='big']").val();
            var activity = $("input[name='activity']").val();
            $(".shop_index_tab_default").show();
            $(".shop_index_filter").css({ "display": "none" });
            $(this).find(".shop_index_tab_default").hide();
            $(this).find(".shop_index_filter").css({ "display": "inline-block" });
            $(this).find('.shop_index_filter').find('img').toggleClass('shop_index_filter_img');

            if ($(this).index() == 0) {
                $('input[name="order"]').val($(this).attr("data"));
            }
            if($(this).index() == 1 || $(this).index() == 2) {         
                $('input[name="order"]').val($(this).find('.shop_index_filter_img').attr("data"));
            }

            if ($(this).index() != 3) {
                $(".shop_index_tab_list").removeClass("shop_index_tab_bold");
                //重置列表数据
                mescroll.resetUpScroll();
                //隐藏回到顶部按钮
                mescroll.hideTopBtn();
            }
            $(this).addClass("shop_index_tab_bold");
            if (min_price != '' || max_price != '' || activity != '') {
                $('.shop_index_tab_list').eq(3).addClass('shop_index_tab_bold');
            }
        })

        /*设置列表数据*/
        function setListData(curPageData) {
            var listDom = document.getElementById("dataList");
            if ($('input[name="type"]').val() == 1) {
                for (var i = 0; i < curPageData.length; i++) {
                    var pd = curPageData[i];
                    if (pd.sum_gp_num == null) {
                        pd.sum_gp_num = '0';
                    }
                    if(pd.g_s_img==""){
                        pd.g_s_img="/static/image/index/pic_home_taplace@2x.png"
                    }
                    var str = '<a onClick="clic1('+pd.g_id+')"><div class="product_list_list lf">';
                    str += '<div class="product_list_pic">';
                    str += '<img src="' + pd.g_s_img + '">';
                    str += '<div class="product_list_number">' + pd.sum_gp_num + '人已参与</div>';
                    str += '</div>';
                    str += '<p class="product_list_tit_p">' + pd.g_name + '</p>';
                    str += '<p class="product_list_price clear">￥<span class="product_list_red">' + pd.min_gdr_price + '-' + pd.max_gdr_price + '</span></p>';
                    str += '<span class="product_list_oldprice">￥' + pd.gp_market_price + '</span>';
                    str += '</div></a>';

                    var liDom = document.createElement("a");
                    // liDom.setAttribute("href", "/member/goodsproduct/index/g_id/" + pd.g_id + "");
                    liDom.setAttribute("onClick", "clic1("+pd.g_id+")");
                    liDom.innerHTML = str;
                    listDom.appendChild(liDom);
                }
            } else if ($('input[name="type"]').val() == 2) {
                for (var i = 0; i < curPageData.length; i++) {
                    var pd = curPageData[i];
                    var store_logo = null;
                    if(pd.store_logo ==null){
                        pd.store_logo = '/static/image/myhome/TIM20180731142117.jpg'
                    }
                    var str = '<div class="store-list-plist">';
                    str += '<div class="store-list-item">';
                    str += '<div class="store-list-title">';
                    str += '<div class="store-list-logo">';
                    str += '<img src="' + pd.store_logo + '">';
                    str += '</div>';
                    str += '<h3>' + pd.stroe_name + '</h3>';
                    str += '<a class="store-list-link" onClick="clic2('+pd.store_id+')">进店</a>';
                    str += '</div>';
                    str += '<div class="store-list-img">';
                    for (var j = 0; j < pd.goods.length; j++) {
                        
                        if(pd.goods[j].g_s_img==""){
                            pd.goods[j].g_s_img = '/static/image/index/pic_home_taplace@2x.png'
                        }
                        str += '<div class="store-img-item">';
                        str += '<a href="/member/goodsproduct/index/g_id/' + pd.goods[j].g_id + '">';
                        str += '<img src="' + pd.goods[j].g_s_img + '">';
                        str += '<span>￥' + pd.goods[j].gp_market_price + '</span>';
                        str += '</a>';
                        str += '</div>';
                    }
                    str += '</div>';
                    str += '</div>';
                    str += '</div>';
                    var liDom = document.createElement("div");
                    liDom.className = 'store-list';
                    liDom.innerHTML = str;
                    listDom.appendChild(liDom);
                    
                }
            }

        }
        /*联网加载列表数据
        在您的实际项目中,请参考官方写法: http://www.mescroll.com/api.html#tagUpCallback
        请忽略getListDataFromNet的逻辑,这里仅仅是在本地模拟分页数据,本地演示用
        实际项目以您服务器接口返回的数据为准,无需本地处理分页.
        * */
        function getListDataFromNet(pageNum, pageSize, successCallback, errorCallback) {
            //延时一秒,模拟联网
            setTimeout(function () {
                $.ajax({
                    type: 'post',
                    url: '/index/index/search_index',
                    data: {
                        keyword: $('input[name="keyword"]').val(),
                        type: $('input[name="type"]').val(),
                        min_price: $('input[name="little"]').val(),
                        max_price: $('input[name="big"]').val(),
                        activity: $('input[name="activity"]').val(),
                        order: $('input[name="order"]').val(),
                        page: pageNum,
                        page_size: pageSize
                    },
                    dataType: 'json',
                    success: function (data) {
                        var listData = [];
                        for (var i = 0; i < data.list.length; i++) {
                            listData.push(data.list[i]);
                        }

                        //回调
                        successCallback(listData);
                    },
                    error: errorCallback
                });
            }, 1000)
        }
       
    });
    function clic1(id){
        var types=$('input[name="type"]').val();
        var keyword= "<?php echo $keyword; ?>";
        window.sessionStorage.setItem("data",types);
        window.sessionStorage.setItem("keyword",keyword);
        window.location.href = '/member/goodsproduct/index/g_id/'+ id;
    }
    function clic2(id){
        var types=$('input[name="type"]').val();
        var keyword= "<?php echo $keyword; ?>";
        window.sessionStorage.setItem("data",types);
        window.sessionStorage.setItem("keyword",keyword);
            window.location.href = '/member/shop/index/store_id/'+ id;
        }
</script> 
    <!-- 调用登陆的sdk -->
    <!-- <script>
        $(function(){
            webimLogin();
        })
    </script>  -->
</html>