<?php
namespace app\member\controller;
use app\data\service\goods\GoodsService;
use app\data\service\store\StoreCollectionService;
use app\data\service\store\StoreService;
use think\Db;

class Storecollection extends MemberHome
{
    /**
     * 收藏和取消关注商家
     */
    public function store_collection(){
        if (request()->isAjax()) {
            $m_id = $this->m_id;
            $store_id = input('post.store_id');
            $data = [
                'm_id' => $m_id,
                'store_id' => $store_id,
                'sc_time' => time(),
            ];
            $collection = new StoreCollectionService();
            $where = [
                'm_id' => $m_id,
                'store_id' => $store_id,
            ];
            $is_true = $collection->collectionInfo($where);
            if ($is_true) {
                $res = $collection->collectionDel($where);
                if ($res) {
                    return ['status' => 1, 'msg' => '删除成功'];
                }
                return ['status' => 0, 'msg' => '删除失败'];
            } else {
                $res = $collection->collectionAdd($data);
                if ($res) {
                    return ['status' => 2, 'msg' => '收藏成功'];
                }
                return ['status' => 0, 'msg' => '收藏失败'];
            }
        }
    }

    /**
     * 取消多个关注商家
     * 邓赛赛
     */
    public function del_multiple(){
        $data = input('post.');
        if(empty($data['store_id'])){
            return ['status'=>0,'msg'=>'删除失败,未选择商品'];
        }
        $id_arr = $data['store_id'];
        $id_arr = implode(',',$id_arr);
        $collection = new StoreCollectionService();
        $where = [
            'm_id'=>$this->m_id,
            'store_id' => ['in',$id_arr]
        ];
        $res = $collection->del_multiple_collection($where);
        if($res){
            return ['status'=>1,'msg'=>'取消关注成功'];
        }else {
            return ['status' =>0, 'msg' => '删除失败,请稍后重试'];
        }
    }

    /**
     * 获取收藏店铺列表
     * 邓赛赛
     */
    public function store_list(){
        $m_id = $this->m_id;
        $page = input('param.page');
        $page = $page ? $page : 1;
        $page_size = input('param.page_size');
        $page_size = $page_size ? $page_size : 5;

        $collection = new StoreCollectionService();
        $where = [
            'sc.m_id'=>$m_id
        ];
        $field = 's.stroe_name,s.store_id,s.store_logo';
        $list['list'] = $collection->store_all($where,$order='sc.sc_id desc',$field,$page,$page_size);
        $where2=[
            'm_id'=>$m_id
        ];
        $total_num = $collection->get_num($where2); //关注总数量
        $total_page = ceil($total_num/$page_size);
        $list['page'] = $page;
        $list['page_size'] = $page_size;
        $new_num = count($list['list']);
        $list['new_num'] = $new_num;
        $list['total_num'] = $total_num;
        $list['total_page'] = $total_page;
        //店铺推荐
        if($page == $list['page']){
            $store = new StoreService();
            $where2 = [
                'store_tj'=>[">",0],
                'store_state'=>0
            ];
            //推荐店铺
            $tj = $store->get_limit_list($where2,$order='add_time desc','store_id,stroe_name,store_logo',1,20);
            if($tj){
                //循环查出店铺三个商品
                foreach($tj as $k => $v){
                        $where2 = [
                            'g.g_storeid'=>$v['store_id'],
                            'g.g_state'=>6,
                            'g_endtime'=>['>',time()],
                            'p.gp_stock'=>['>',0]
                        ];
                        $goods = Db::table('pai_goods')
                            ->alias('g')
                            ->join('pai_goods_product p','p.g_id=g.g_id','left')
                            ->join('pai_goods_dt_record gt','gt.g_id=g.g_id','left')
                            ->where($where2)
                            ->field('g.g_img,g.g_s_img,g.g_id,g.g_starttime,gt.gdr_id,gt.g_id gtrg_id,min(gdr_price) gdr_price')
                            ->group('gtrg_id')
                            ->order('g.g_id desc')
                            ->limit(3)
                            ->select();
                    $tj[$k]['goods'] = $goods;
                    if($goods){
                        //是否为最新商品(七天之内为最新)
                        foreach($goods as $kk => $vv){
                            $tj[$k]['goods'][$kk]['new_goods'] = (time() - $goods[$kk]['g_starttime'] < 86400*7) ? 1 : 2;
                        }
                    }
                }
            }

            $list['tj_list'] = $tj;
        }
        if(\request()->isAjax()){
            return ['status'=>1,'msg'=>'ok','data'=>$list];
        }
        $this->assign('list',$list);
        $this->assign('header_title','关注店铺');
        return $this->fetch();
    }

    /**
     * 搜索收藏店铺列表
     * 邓赛赛
     */
    public function search_store_list(){
        $m_id = $this->m_id;
        $store_name = input('param.store_name');
        $page = input('param.page');
        $page = $page ? $page : 1;
        $page_size = input('param.page_size');
        $page_size = $page_size ? $page_size : 5;
        if(\request()->isAjax()){
            $collection = new StoreCollectionService();
            $where = [
                'sc.m_id'=>$m_id,
                's.stroe_name'=>['like','%'.$store_name.'%']
            ];
            $field = 's.stroe_name,s.store_id,s.store_logo';
            $list['list'] = $collection->store_all($where,$order='sc.sc_id desc',$field,$page,$page_size);
            $where2=[
                'm_id'=>$m_id
            ];
            $total_num = $collection->get_num($where2); //关注总数量
            $total_page = ceil($total_num/$page_size);
            $list['page'] = $page;
            $list['page_size'] = $page_size;
            $new_num = count($list['list']);
            $list['new_num'] = $new_num;
            $list['total_num'] = $total_num;
            $list['total_page'] = $total_page;
            return ['status'=>1,'msg'=>'ok','data'=>$list];
        }
        $this->assign('store_name',$store_name);
        // $this->assign('header_path','/member/storecollection/store_list');
        $this->assign('header_title','店铺收藏');
        return $this->fetch();
    }

}
