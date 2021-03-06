<?php
namespace app\popularity\controller;
use app\data\service\article\ArticleService;
use app\data\service\articleType\ArticleTypeService;
use app\data\service\member\MemberService;
use app\data\service\popularity\PopularityCollectionService;
use app\data\service\popularity\PopularityGoodsService;
use app\data\service\popularity\PopularityMemberService;
use app\data\service\popularity\PopularityService;
use app\member\controller\MemberHome;
use think\Db;
use think\Request;
use RedisCache\RedisCache;
use \Redis;
use think\Config;


class Popularityorder extends MemberHome
{
    public function __construct(Request $request = null){
        parent::__construct($request);
        $popularity = new PopularityService();
        $popularity->pop_refresh($this->m_id);

//        if (null === self::$_instance) {
//            self::$_instance = new self();
////            self::$_instance->connect('10.0.2.62', 6379);
//            self::$_instance->connect('127.0.0.1', 6379);
//            self::$_instance->auth('');
//        }
//        return self::$_instance;


        //微信分享参数
        $this->assign('share_title',"拍吖吖：只要1元，火爆宝贝等你来！");
        $this->assign('share_desc','邀请好友，为你点赞，成为人气王，宝贝抱回家。');
        $this->assign('share_link',PAI_URL.'/popularity/popularitygoods/share_list');
        $this->assign('share_imgUrl',PAI_URL.'/uploads/logo/popularity.png');//此图片待设计
    }

    /**
     * 订单列表
     * lyh
     * @return \think\response\View
     */
    public function order_list(){
        $i = input('param.i/d', 0);

        $header_title = "人气购订单";

        $this->assign('header_title', $header_title);
        $this->assign('header_path', "/member/myhome/index");
        $this->assign('i', $i);
        return $this->fetch();
    }

    /**
     * 动态获取我参团的列表
     * 刘勇豪
     * @return array
     * @throws \Exception
     */
    public function getorderlist(){
        $m_id = $this->m_id;
        $page = input('param.page/d',1);
        $size = input('param.size/d',5);
        $status = input('param.status/d',0);//订单状态
        $keyword = input('param.keyword/s','');//订单搜索关键字

        // 待支付订单保留时间

        $where['pm.m_id'] = $m_id;
        $where['pm.pm_paystate'] = ['gt',1];
        $where['pm.pm_isdelete'] = ['lt',2];
        if($keyword){
            $where['pg.pg_name'] = ['like','%'.$keyword.'%'];
        }

        //订单状态（新需求）
        switch ($status)
        {
            case 0:
                // 全部订单
                break;
            case 1:
                // 进行中
                $where['pm.pm_state'] = 2;
                break;
            case 2:
                // 未填写地址的中奖中将订单
                $where = "";
                $where .= " pm.m_id = '".$m_id."' and pm.pm_paystate > 1 and pm.pm_isdelete < 2 ";
                if($keyword){
                    $where .= " and pg.pg_name like '%".$keyword."%' ";
                }
                $where .= " and pm.pm_state = 3 and pm.pm_order_status = 1 and( pm.pm_addressid is null or pm.pm_addressid = 0) ";
                break;
            case 3:
                // 填过地址的：待发货、待收货
                $where['pm.pm_state'] = 3;
                $where['pm.pm_addressid'] = ['gt',0];//填过地址
                $where['pm.pm_order_status'] = ['in','1,2'];
                break;
            case 4:
                // 已失败(未成团、未团中退款中、未团中退款完成)
                $where['pm.pm_paystate'] = ['in','2,3'];
                break;
            default:
                // 全部（我参团的）
                break;
        }


        $popularity = new PopularityService();
        $popularity_goods = new PopularityGoodsService();
        $order='pm.pm_id desc';
        $field = 'pm.pm_id,pm.m_id,pm.pm_state,pm.pm_periods,pm.pm_paystate,pm.add_time,pm.pm_paytime,pm.pm_popularity,pm.pg_id,pm.m_id,pm.m_id,pm.pm_addressid,pm.pm_order_status,pg.pg_name,pg.pg_market_price,pg.pg_price,pg.pg_membernum,pg.pg_state,pg.pg_img,pg.pg_s_img,pg.pg_type,pg.pg_spec,pg.pg_loser_point,pg.pg_sn';
        $limit = (($page-1) * $size).','.$size;
        $list = $popularity->get_pm_detail_limit_list($where,$order,$field,$limit);

        // 判断订单是否已经超出支付时间
        if(!empty($list)){
            foreach($list as $kk => $vv){

                // 4.人气排名
                $call_sort = $popularity->pm_sort_by_pmid($vv['pm_id']);
                if(!$call_sort['status']){
                    throw new \Exception($call_sort['msg']);
                }
                $pm_sort = $call_sort['data'];
                $list[$kk]['pm_sort'] = $pm_sort;

                // 如果没有pg_sn，给默认值
                if(empty($vv['pg_sn'])){
                    $list[$kk]['pg_sn'] = '2018001'.$vv['pg_id'];
                }

                // 获取二维码
                $list[$kk]['code']  = $popularity_goods->code($m_id,$vv['pg_id']);
                $list[$kk]['url']   = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/popularity/popularitygoods/new_people/pm_id/".$vv['pm_id'].'/pg_id/'.$vv['pg_id'].'?share=1';

                // 微信分享
                //微信分享参数
                $list[$kk]['share_title'] = '拍吖吖：只要¥'.$vv['pg_price'].'，你敢信？快跟我一起来抢购吧！';
                $list[$kk]['share_desc'] =$vv['pg_name'];
                $list[$kk]['share_link'] = PAI_URL.'/popularity/popularitygoods/new_people/pm_id/'.$vv['pm_id'].'/pg_id/'.$vv['pg_id'].'?share=1';
                $list[$kk]['share_imgUrl'] = PAI_URL.$vv['pg_img'];
            }
        }

        // 统计总条数
        $total_num = $popularity->get_pm_detail_count($where);
        if(empty($list)){
            return ['status' => 0, 'msg' => '没有数据', 'data' => $list,'total_num'=>$total_num];
        }
        return ['status' => 1, 'msg' => 'success', 'data' => $list,'total_num'=>$total_num];
    }

    /**
     * 订单搜索
     * lyh
     * @return \think\response\View
     */
    public function order_search(){
        $keyword = input('param.keyword/s','');//订单搜索关键字

        $this->assign('keyword', $keyword);
        $this->assign('header_title', "搜索结果");
        return view();
    }

    /**
     * 订单详情
     * lyh
     * @return \think\response\View
     */
    public function order_info(){
        $pm_id = input('param.pm_id/d',0);

        $popularity = new PopularityService();//
        $call_back = $popularity->order_info_page($pm_id);

        if(!$call_back['status']){
            return  $call_back['msg'];
        }

        $this->assign('info',$call_back['data'] );
        $this->assign('header_title','人气订单详情');
        //$this->assign('header_path','/popularity/popularityorder/order_list');
        return view();
    }

    /**
     * 确认订单（弃用）
     * lyh
     * @return \think\response\View
     */
    public function confirm_order(){

        return view();
    }

    /**
     * 支付结果（弃用）
     * lyh
     * @return \think\response\View
     */
    public function pay_result(){

        return view();
    }

    /**
     * 点赞排行帮
     * lyh
     * @return \think\response\View
     */
    public function pop_rank(){
        $pm_id = input('param.pm_id',0);

        $popularity = new PopularityService();
        $field = '*';
        $where['pm.pm_id'] = $pm_id;
        $info = $popularity->popgoods_info($where, $field);

        // 查询排名
        if(!empty($info)){
            $call_sort = $popularity->pm_sort_by_pmid($pm_id);
            $pm_sort = $call_sort['data'];
            $info['pm_sort'] = $pm_sort;

            //擂主二维码和待复制链接
            $popularityGoods = new PopularityGoodsService();
            if(!empty($info['pm_id'])){
                $info['challenger_code'] = $popularityGoods->code($info['m_id'],$info['pg_id']);
                $info['challenger_url'] = PAI_URL."/popularity/popularitygoods/new_people/pm_id/".$info['pm_id'].'/pg_id/'.$info['pg_id'].'?share=1';
            }

            $info['popularity_url'] = PAI_URL."/popularity/popularitygoods/new_people/pg_id/".$info['pg_id'].'?share=1';
            //详情页二维码
            $mid = 1;
            if($info['pg_type'] == 3){
                $mid = 6;
            }
            $path_3 = '/uploads/code/popularity/shop/'.$mid.'code_'.$info['pg_id'].".jpg";

            $info['popularity_code'] = $popularityGoods->hebingImg('/uploads/logo/father.png',$info['pg_img'],$path_3,$info['pg_name'],$info['pg_price'],$mid,$info['pg_id']);
        }

        //dump($info);die;
        $this->assign('header_title','好友点赞榜');
        $this->assign('info',$info);
        return view();
    }


    /**
     * 动态获取点赞列表
     * @return array
     */
    public function get_joinner_list(){
        $page = input('param.page/d',1);
        $size = input('param.size/d',5);
        $pm_id = input('param.pm_id/d',0);//擂主id

        $order='pj.pj_num desc';
        $field='*';
        $limit = (($page-1) * $size).','.$size;
        $where['pj.pm_id'] = $pm_id;

        $popularity = new PopularityService();

        $joinner_count = $popularity->joinner_count($where);


        $joinner_list = $popularity->joinner_list($where, $field,$order,$limit);
        if(!empty($joinner_list)){
            return ['status'=>0,'msg'=>'empty!','data'=>$joinner_list,'total_num'=>$joinner_count];
        }else{
            return ['status'=>1,'msg'=>'ok!','data'=>$joinner_list,'total_num'=>$joinner_count];
        }
    }


    /**
     * 分享活动详情(新人视角)
     */
    public function new_people()
    {
        $pm_id = input('param.pm_id',19);
        $m_id = $this->m_id;
        $m_id = $m_id?$m_id:0;
        $popularity = new PopularityService();
        $callback = $popularity -> invite_page($pm_id,$m_id);
        if(!$callback['status']){
            return $callback['msg'];
        }
        $info = $callback['data'];
        $popgoods_info = $info['popgoods_info'];
        $pm_sort = $info['pm_sort'];
        $total_joinner_num = $info['total_joinner_num'];
        $total_pm_num = $info['total_pm_num'];
        $joinner_list = $info['joinner_list'];
        $is_called = $info['is_called'];
        $this->assign('share_link',PAI_URL.'/popularity/popularitygoods/new_people/pm_id/'.$pm_id.'?share=1');
        $this->assign('header_title','分享活动详情');
        $this->assign('header_path',PAI_URL.'/popularity/popularitygoods/share_list');
        $this->assign('popgoods_info',$popgoods_info);
        $this->assign('pm_sort',$pm_sort);
        $this->assign('total_joinner_num',$total_joinner_num);
        $this->assign('total_pm_num',$total_pm_num);
        $this->assign('joinner_list',$joinner_list);
        $this->assign('pm_id',$pm_id);
        $this->assign('m_id',$m_id);
        $this->assign('is_called',$is_called);
        return view();
    }

    /**
     * 更新收货地址
     * 刘勇豪
     */
    public function change_address(){
        $address_id = input('param.address_id',0);
        $pm_id = input('param.pm_id',0);

        $popularity = new PopularityService();

        $call_back = $popularity->change_address($address_id,$pm_id);

        return $call_back;
    }

    /**
     * 订单删除
     * @return array
     */
    public function delete_pm(){
        $pm_id = input('param.pm_id',0);
        $m_id = $this->m_id;
        $popularity = new PopularityService();

        $call_back = $popularity->delete_pm($pm_id,$m_id);

        return $call_back;
    }

    /**
     * 订单签收
     * 刘勇豪
     * @return array
     */
    public function confirm_pm(){
        $pm_id = input('param.pm_id',0);
        $m_id = $this->m_id;
        $popularity = new PopularityService();

        $call_back = $popularity->confirm_pm($pm_id,$m_id);

        return $call_back;
    }

    /**
     * /popularity/popularityorder/test
     */
    public function test(){
//        $redis = RedisCache::getInstance();
//        $set = $redis->set('lyh','qew');
//        $get = $redis->get('lyh');
//        echo $get;


//        $redis->set('a',1);
//        $redis = new \Redis();
//        $redis->set('test','hello redis');
//        echo $redis->get('test');
//        $redis = new \Redis();
//        $lyh = $redis->set('lyh',213132);
//        $get = $redis->get('lyh');
//        dump($get);die;

//        $popularity = new PopularityService();
//        echo $callback = $popularity->num_to_wan(10000);

        $popularity = new PopularityService();
        $pg_id  = 38;
        $call_back = $popularity->get_award_time($pg_id,1);
        dump($call_back);
    }

    /**
     * 立即成团
     * 刘勇豪
     */
    public function line_public(){
        $pg_id = input('param.pg_id/d',0);
        $periods = input('param.periods/d',0);
        $popularity = new PopularityService();
        $callback = $popularity->line_public($pg_id,$periods);

        return $callback;
    }

    /**
     * 订单退款详情
     * 刘勇豪
     *
     * pm_paystate
     * pm_id
     */
    public function refund_info(){
        $pm_id = input('param.pm_id/d',0);

        $popularity = new PopularityService();

        $field = 'pm.*';
        $refund_info = $popularity->refund_info($pm_id,$field);

        $this->assign('header_title','退款详情');
        $this->assign('refund_info',$refund_info);
        return view();
    }

    /**
     * 查询当前用户是否有未填写地址的中奖人气订单
     * 刘勇豪
     * @return mixed
     * http://www.pai.com/popularity/popularityorder/find_no_address_aworder
     */
    public function find_no_address_aworder(){
        $m_id = $this->m_id;
        $popularity = new PopularityService();
        $callback = $popularity->find_no_address_aworder($m_id);

        return $callback;
    }

    public function update_pm_sn(){
        $popularity = new PopularityService();

    }



}