<?php
namespace app\pointpai\controller;

//use app\data\service\pointReview\RevieworderService as RevieworderService;
use app\data\service\pointReview\PointReviewService as PointReviewService;
use app\data\service\member\MemberService as MemberService;
use app\data\service\BaseService as BaseService;
use app\data\service\pointOrderPai\PointOrderPaiService;
use think\Controller;
use think\Request;
use think\Cookie;

class Review extends MemberHome
{
    /**
	* 发布评价
	* 创建人 刘勇豪
	* 时间 2018-07-12 10:21:00
	*/
    public function review_add(){
        $m_id = $this->m_id;
        $o_id = input('param.o_id/d',0);//假设订单id
        if(!$o_id){
            return "参数错误！";
        }

        if (request()->isAjax() || request()->isPost() ) {
            $data = input('post.');
            $data['m_id']  = $m_id;//评论人ID

            // 添加评论
            $point_review = new PointReviewService();
            $call_back = $point_review->reviewAdd($data);

            return $call_back;
        }

        // 订单详情
        $pointOrderPai = new PointOrderPaiService();
        $orderInfo = $pointOrderPai->getMoreInfo(['po.o_id'=>$o_id]);

        $this->assign('orderInfo', $orderInfo);
        $this->assign('o_id', $o_id);
        $this->assign('header_title','发表评价');
        return $this->fetch();
    }

    /**
	* 我的评价列表
	* 创建人 刘勇豪
	* 时间 2018-07-12 10:21:00
	*/
    public function get_content(){
        $m_id = $this->m_id;

        $page = input('param.page/d',0);
        $size = input('param.size/d',5);

        $point_review = new PointReviewService();
        $where['m.m_id'] = $m_id;
        $where['pro.state'] = 0;
        $where['prg.state'] = 0;
        $order='pro.ro_id desc';
        $field='pgp.gp_img,pg.g_name,po.o_point,prg.rg_content,pro.add_time as ro_add_time,prg.rg_id,po.gp_num,s.store_id,prg.gp_id,pg.g_id,m.m_id,m.m_name,m.m_avatar';

        $limit = (($page-1) * $size).','.$size;
        $list = $point_review->pointReviewOrderDetailLimitList($where,$order,$field,$limit);
        if(!empty($list)){
            // 统计总条数
            $count = $point_review->pointReviewOrderDetailLimitCount($where);
            return ['status'=>1,'msg'=>"ok",'data'=>$list,'total_num'=>$count];
        }else{
            return ['status'=>0,'msg'=>"empty",'data'=>'','list'=>$list,'total_num'=>0];
        }
    }

    /**
     * 商品评论
     * 刘勇豪
     */
    public function gp_review_list(){
        $gp_id = input('param.gp_id/d',0);

        $this->assign('header_title','商品评价列表');
        $this->assign('gp_id',$gp_id);
        return $this->fetch();
    }

    /**
     * ajax获取评价列表
     * 刘勇豪
     * @return array
     */
    public function get_gp_review_list(){
        $page = input('param.page/d',0);
        $size = input('param.page_size/d',5);
        $gp_id = input('param.gp_id/d',0);

        $pointOrderPai = new PointOrderPaiService();
        $where = '';
        $where['po.gp_id'] = $gp_id;
        $where['po.o_state'] = ['IN','2,3,4,5'];
        $order = 'po.o_periods asc';
        $field='po.o_id,po.o_point,po.o_periods,po.o_addtime,po.o_paytime,prg.rg_content,pro.add_time as ro_add_time,prg.rg_id,po.gp_num,prg.gp_id,m.m_id,m.m_name,m.m_avatar,m.m_s_avatar,prg.goods_score';
        $limit = (($page-1) * $size).','.$size;
        $list = $pointOrderPai->get_limit_order_review($where,$field,$order,$limit);

        // 中奖人数
        $where_count = '';
        $where_count['gp_id'] = $gp_id;
        $where_count['o_state'] = ['IN','2,3,4,5'];
        $review_count = $pointOrderPai->pointOrderPaiCount($where_count);

        if(!empty($list)){
            return ['status'=>1,'msg'=>"ok",'data'=>$list,'total_num'=>$review_count];
        }else{
            return ['status'=>0,'msg'=>"empty",'data'=>$list,'total_num'=>0];
        }
    }

}
