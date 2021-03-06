<?php
/**
 * User: Shine
 * Date: 2018/9/4
 * Time: 14:39
 */
namespace app\business\controller;

use app\data\service\BaseService;
use app\data\service\pointOrderPai\PointOrderPaiService;
use app\data\service\pointOrderAwardcode\PointOrderAwardcodeService;
use RedisCache\RedisCache;
use think\Db;

class Pointorder extends MemberHome{

    /**
    * 拍品订单列表
    * 创建人 刘勇豪
    * 时间 2018-07-09 10:40:00
    */
    public function order_list()
    {
        $status = input('param.status/d', 0);
        $g_name = trim(input('param.g_name/s', ''));
        $m_name = trim(input('param.m_name/s', ''));
        $o_sn = trim(input('param.o_sn/s', ''));
        $mobile = $m_mobile   = trim(input('param.m_mobile/s', ''));
        //中奖时间
        $start_time = trim(input('param.start_time/s', ''));
        $end_time = trim(input('param.end_time/s', ''));
        //订单状态
        $o_state = trim(input('param.o_state/d', 0));
        $excel = trim(input('param.excel/s', ''));

        $m_id = $this -> m_id;

        $where = '';
        if($status){
            switch ($status) {
                case 1:
                    // 参拍中
                    $where['po.o_state'] = 1;
                    break;
                case 2:
                    // 待发货
                    $where['po.o_state'] = 2;
                    break;
                case 3:
                    // 已发货
                    $where['po.o_state'] = 3;
                    break;
                case 4:
                    // 已签收
                    $where['po.o_state'] = 4;
                    break;
                case 5:
                    // 交易完成
                    $where['po.o_state'] = 5;
                    break;
                case 6:
                    // 未中拍
                    $where['po.o_state'] = 10;
                    break;
                case 7:
                    // 流拍
                    $where['po.o_state'] = 11;
                    break;
                case 8:
                    // 待付款
                    $where['po.o_paystate'] = 1;
                    break;
                case 9:
                    // 已付款
                    $where['po.o_paystate'] = 2;
                    break;
                case 10:
                    // 退款中
                    $where['po.o_paystate'] = 3;
                    break;
                case 11:
                    // 退款完成
                    $where['po.o_paystate'] = 4;
                    break;
            }
        }

        // 商品名搜索
        if($g_name){
            $where['pg.g_name'] = ['like', "%".$g_name."%"];
        }

        // 用户昵称搜索
        if($m_name){
            $where['m.m_name'] = ['like', "%".$m_name."%"];
        }

        //订单编号
        if($o_sn){
            $where['po.o_sn'] = $o_sn;
        }
        $pointOrderPai = new PointorderPaiService();
        //手机号码
        if($m_mobile){
            $where['m.m_mobile'] = htmlspecialchars($pointOrderPai->encrypt($m_mobile));
        }
        //中奖时间
        if($start_time || $end_time){
            if($start_time && $end_time){
                $where = [
                    'po.o_publishtime'=>['between time',[$start_time,$end_time]]
                ];
            }else{
                $this->error('时间必须是时间段','/business/pointorder/order_list','',1);
            }
        }
        //订单状态
        if($o_state){
            $where = [
                'po.o_state'=>$o_state,
            ];
            if($start_time && $end_time) {
                $where['po.o_publishtime'] = ['between time', [$start_time, $end_time]];
            }
        }

        $store_id = $pointOrderPai->get_my_storeid_by_mid($m_id);
        $where['po.store_id'] = $store_id;

        $order='po.o_id desc';
//        dump($where);die;

        //导出当前条件excel
        if($excel == 1){
            $order = 'po.o_addtime desc';
            $field = 'po.o_id,po.gs_id,po.o_paystate,po.o_state,po.o_deliverfee,po.o_paytime,po.o_publishtime,po.o_sn,po.o_periods,po.o_receiver,pg.g_name,s.stroe_name,po.o_totalpoint,m.m_name,m.m_mobile,po.o_mobile,po.o_express_way,po.o_express_num,po.o_seller_name,po.o_seller_mobile,po.gp_num,po.gp_id,po.o_address,pgp.gp_sn';
            $list = $pointOrderPai->getOrderInfoLimit($where, $field, $order,1000);
        }else if($excel == 2){
            $field = 'po.o_id,po.gs_id,po.o_paystate,po.o_state,po.o_deliverfee,po.o_paytime,po.o_paypoint,po.o_publishtime,po.o_sn,po.o_periods,pg.g_name,s.stroe_name,po.o_totalpoint,m.m_name,m.m_mobile,po.o_mobile,po.o_express_way,po.o_express_num,po.o_seller_name,po.o_seller_mobile,po.gp_num,po.gp_id,po.o_address,po.o_receiver';
            //导出昨日14：00-今日14：00的excel
            $yesterday = date('Y-m-d ',strtotime("-1 day")).'16:00:00';
            $today = date('Y-m-d ').'16:00:00';
            $where_state = [
                'po.o_state' => 2,
                'po.o_publishtime' => ['between time',[$yesterday,$today]],
            ];
            $order = 'po.o_addtime desc';
            $list = $pointOrderPai->getOrderInfoLimit($where_state, $field, $order,1000);
        } else{
            $field = "po.o_id,po.o_sn,po.store_id,po.o_point,po.o_paystate,po.o_mobile,po.o_state,po.gp_id,po.o_paypoint,po.o_receiver,po.gp_num,po.o_addtime,po.o_paytime,po.o_totalpoint,po.gs_id,po.o_periods,po.o_isdelete,po.o_gp_settlement_price,po.o_deliverfee,po.o_address,po.o_express_way,po.o_express_num,po.o_seller_name,po.o_seller_mobile ,pgp.gp_market_price,pgp.g_id,pgp.gp_img,pg.g_name,pg.g_endtime,pg.g_typeid,s.stroe_name,m.m_mobile,m.m_name,m.is_jq,s.store_logo";
            //查询列表
            $list = $pointOrderPai->getOrderInfoPaginate($where, $field, $order,5);
            $page = $list->render();
            $total = $list->total();
            $list = $list->toArray();
            $list = $list['data'];
        }
        if(!empty($list)){
            foreach($list as $k=>$v){
                // 幸运码数量
                $where_count['o_id'] = $v['o_id'];
                $pai_num = $pointOrderPai->countPaiNum($where_count);
                $list[$k]['pai_num'] = $pai_num;

                //参拍揭晓时间（当期最后下单时间）
                $gp_id = $v['gp_id'];
                $o_periods = $v['o_periods'];
                $call_publish = $pointOrderPai->get_pai_publish_time($gp_id, $o_periods);
                $publish_time = 0;
                if($call_publish['status']){
                    $publish_time = $call_publish['data'];
                }
                $list[$k]['o_publishtime'] = $publish_time;

                // 中奖者信息
                $pointOrderAwardcode = new PointOrderAwardcodeService();
                $awards_mem_info = $pointOrderAwardcode->get_awards_mem($gp_id,$o_periods);

                $award_m_avatar = '';//中奖者头像
                $award_m_name = '';//中奖者名称
                $award_m_name_secret = '';//中奖者名称保密
                if(!empty($awards_mem_info) && !empty($awards_mem_info['m_name_secret'])){
                    $award_m_avatar = $awards_mem_info['m_avatar'];//中奖者头像
                    $award_m_name = $awards_mem_info['m_name'];//中奖者名称
                    $award_m_name_secret = $awards_mem_info['m_name_secret'];//中奖者名称保密
                }
                $list[$k]['award_m_avatar'] = $award_m_avatar;
                $list[$k]['award_m_name'] = $award_m_name;
                $list[$k]['award_m_name_secret'] = $award_m_name_secret;
                $list[$k]['m_mobile'] = htmlspecialchars($pointOrderAwardcode->decrypt($v['m_mobile']));
                $list[$k]['o_mobile'] = $v['o_mobile'];
            }
        }
        if($excel){
            $base_service = new BaseService();
            if($excel == 1){
                //导出当前条件的excel
                $title = [
                    '订单编号','商品编号','商品名称','商家名称','商品类型','订单总价','运费','支付时间','中拍时间','支付状态','订单状态','参拍人昵称','参拍人手机号','收货人','收货人手机号','收货地址','物流名称','快递单号','发货人姓名','联系电话'
                ];
                //整合数据
                $data = $this->current_condition($list);
                //excel名称
                $filename = date('Y-m-d H:i').'积分订单';
                $base_service->exportExcel($data,$title,$filename);
                die;
            }else if($excel == 2){
                //导出待发货订单
                $data = $this->pending_delivery($list);
                $title = [
                    '中拍时间','订单编号','来源店铺','商品名称','支付积分','购买数量','收货人','手机号码','收货地址'
                ];
                $filename = date('Y-m-d H:i').'待发货订单';
                $base_service->exportExcel($data,$title,$filename);
                die;
            }
        }
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('status', $status);
        $this->assign('g_name', $g_name);
        $this->assign('m_name', $m_name);
        $this->assign('o_sn', $o_sn);
        $this->assign('start_time', $start_time);
        $this->assign('end_time', $end_time);
        $this->assign('o_state', $o_state);
        $this->assign('where', $where);
        $this->assign('m_mobile', $mobile);
        return $this->fetch();
    }

    //导出当前条件的excel
    public function current_condition($list){
        $data = array();
        foreach($list as $k => $v){
            switch($v['gs_id']){
                case 1:
                    $gs_name = '普通商品';
                    break;
                case 2:
                    $gs_name = '虚拟商品';
                    break;
                case 3:
                    $gs_name = '大宗商品';
                    break;
                default:
                    $gs_name = '';
                    break;
            }
            switch($v['o_paystate']){
                case 1:
                    $o_paystate = '待付款';
                    break;
                case 2:
                    $o_paystate = '已付款';
                    break;
                case 3:
                    $o_paystate = '退款中';
                    break;
                case 4:
                    $o_paystate = '退款完成';
                    break;
                default:
                    $o_paystate = '';
                    break;
            }
            switch($v['o_state']){
                case 1:
                    $o_state = '参拍中';
                    break;
                case 2:
                    $o_state = '已中拍';
                    break;
                case 3:
                    $o_state = '已发货';
                    break;
                case 4:
                    $o_state = '已签收（待评价）';
                    break;
                case 5:
                    $o_state = '交易完成';
                    break;
                case 10:
                    $o_state = '未中拍';
                    break;
                case 11:
                    $o_state = '流拍';
                    break;
                default:
                    $o_state = '';
                    break;
            }

            $o_deliverfee = $v['o_deliverfee'] > 0 ? $v['o_deliverfee'].'积分' : '包邮';
            $o_paytime = $v['o_paytime'] ? date('Y-m-d H:i:s',$v['o_paytime']) : '';
            $o_publishtime = $v['o_publishtime'] ? date('Y-m-d H:i:s',$v['o_publishtime']) : '';
            $data[$k] = [
                $v['o_sn'],
                $v['gp_sn'],
                $v['g_name'],
                $v['stroe_name'],
                $gs_name,
                $v['o_totalpoint'],
                $o_deliverfee,
                $o_paytime,
                $o_publishtime,
                $o_paystate,
                $o_state,
                $v['m_name'],
                $v['m_mobile'],
                $v['o_receiver'],
                $v['o_mobile'],
                $v['o_address'],
                $v['o_express_way'],
                $v['o_express_num'],
                $v['o_seller_name'],
                $v['o_seller_mobile'],
            ];
        }
        return $data;
    }
    /**
     * 导出待发货订单
     * 邓赛赛
     */
    public function pending_delivery($list){
        $data = array();

        foreach($list as $v){
//            $address = $this->address([$v['o_pid'],$v['o_cid'],$v['o_did']]);
            $data[] = [
                date('Y-m-d H:i:s',$v['o_publishtime']),
                $v['o_sn'],
                $v['stroe_name'],
                $v['g_name'],
                $v['o_paypoint'],
                $v['gp_num'],
                $v['o_receiver'],
                $v['m_mobile'],
                $v['o_address'],
            ];
        }
        return $data;
    }
//    //换取地址
//    public function address($data){
//        $redis = RedisCache::getInstance();
//        $address_id = $redis->get('address_id');
//        if($address_id){
//            $address_id = json_decode($address_id,true);
//        }else{
//            $address_id = Db::table('pai_region')->column('region_id,region_name');
//            $redis->set('address_id',json_encode($address_id),86400*30);
//        }
//        $address = '';
//        foreach($data as $v){
//            if($v){
//                $address .= $address_id[$v].',';
//            }
//        }
//        return trim($address,',');
//    }
}