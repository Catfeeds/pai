<?php
namespace app\member\controller;
use app\data\service\goods\GoodsCollectionService;
use app\data\service\goods\GoodsService;
use RedisCache\RedisCache;
use think\Request;

class Goodscollection extends MemberHome
{
    /**
     * @return mixed
     * 添加/删除 收藏商品
     * 邓赛赛
     */
    public function add_collection()
    {
        if (request()->isAjax()) {
            $redus = RedisCache::getInstance(1);
            $m_id = $this->m_id;
            $g_id = input('post.g_id');
            $data = [
                'm_id' => $m_id,
                'g_id' => $g_id,
                'g_time' => time(),
            ];
            $collection = new GoodsCollectionService();
            $where = [
                'm_id' => $m_id,
                'g_id' => $g_id,
            ];
            $is_true = $collection->collectionInfo($where);
            if ($is_true) {
                $res = $collection->collectionDel($where);
                if ($res) {
                    $redus->del('goods_collection_'.$m_id.'_'.$g_id);
                    return ['status' => 1, 'msg' => '取消关注成功'];
                }
                return ['status' => 0, 'msg' => '网络错误失败'];
            } else {
                unset($where['m_id']);
                $goods = new GoodsService();
                $g_storeid = $goods->get_info($where, 'g_storeid');
                $data['store_id'] = $g_storeid['g_storeid'];
                $res = $collection->collectionAdd($data);
                if ($res) {
                    $redus->set('goods_collection_'.$m_id.'_'.$g_id,1);
                    return ['status' => 1, 'msg' => '收藏成功'];
                }
                return ['status' => 0, 'msg' => '收藏失败'];
            }
        }
    }

    /**
     * 删除多个藏品
     * 邓赛赛
     */
    public function del_multiple(){
        $data = input('post.');
        if(empty($data['g_id'])){
            return ['status'=>0,'msg'=>'删除失败,未选择商品'];
        }
        $id_arr = $data['g_id'];

        $id_arr = implode(',',$id_arr);

        $collection = new GoodsCollectionService();
        $where = [
            'm_id'=>$this->m_id,
            'g_id'=>['in',$id_arr]
        ];
        $res = $collection->del_multiple_collection($where);
        if($res){
            return ['status'=>1,'msg'=>'删除成功'];
        }else {
            return ['status' => 0, 'msg' => '删除失败,请稍后重试'];
        }
    }
    /**
     * 获取收藏商品的列表
     * 邓赛赛
     */
    public function goods_list(){
        $m_id = $this->m_id;
        $collection = new GoodsCollectionService();
        $page = input('param.page');
        $page_size = input('param.page_size');

        $page = empty($page) ? 1 : $page;
        $page_size = empty($page_size) ? 6 : $page_size;
        $offset = ($page-1) * $page_size;
        $where=[
            'gc.m_id'=>$m_id
        ];
        $order = 'gdr.g_id desc,gdr.gdr_price asc';
        $field = 'g.g_name,g.g_state,g.g_id,g.g_img,g.g_s_img,gdr.gdr_membernum,min(gdr.gdr_price) gdr_price,gdr.gdr_id,gdr.gdt_id,gp.gp_id,gp.gp_market_price';
        $list['list'] = $collection->collectionPage($where,$order,$field,$offset,$page_size);
        $total_num = $collection->collection_num(['m_id'=>$m_id]);      //收藏总数量
        $total_page = ceil($total_num/$page_size);
        $list['page'] = $page;
        $list['page_size'] = $page_size;
        $new_num = count($list['list']);
        $list['new_num'] = $new_num;
        $list['total_num'] = $total_num;
        $list['total_page'] = $total_page;

        //推荐(当前页等于总页数和大于总页数时显示)
        if($page >= $list['total_page']){
            $where=[
                'g.is_tj'=>2,
                'g.g_state'=>6,
                'g.g_starttime'=>['<=',time()],
                'gp.gp_stock'=>['>',0],
            ];
            $list['goods_tj'] = $collection->collection_tj($where,$order,$field);
        }
        if(\request()->isAjax()){
            return ['status'=>1,'msg'=>'ok','data'=>$list];
        }
        $this->assign('list',$list);
        $this->assign('header_title','收藏夹');
        $this->assign('header_path','/member/myhome/index');
        return $this->fetch();
    }

    /**
     * 搜索收藏商品的列表
     * 邓赛赛
     */
    public function search_goods_list(){
        $m_id = $this->m_id;
        $collection = new GoodsCollectionService();
        $g_name = input('param.g_name');
        $page = input('param.page');
        $page_size = input('param.page_size');
        if(\request()->isAjax()){
            $page = empty($page) ? 1 : $page;
            $page_size = empty($page_size) ? 6 : $page_size;
            $offset = ($page-1) * $page_size;
            $where=[
                'gc.m_id'=>$m_id,
                'g.g_name'=>['like','%'.$g_name.'%']
            ];
            $order = 'gdr.g_id desc,gdr.gdr_price asc';
            $field = 'g.g_name,g.g_state,g.g_id,g.g_img,gdr.gdr_membernum,min(gdr.gdr_price) gdr_price,gdr.gdr_id,gdr.gdt_id,gp.gp_id,gp.gp_market_price';
            $list['list'] = $collection->collectionPage($where,$order,$field,$offset,$page_size);
            $total_num = $collection->collection_num(['m_id'=>$m_id]);      //收藏总数量
            $total_page = ceil($total_num/$page_size);
            $list['page'] = $page;
            $list['page_size'] = $page_size;
            $new_num = count($list['list']);
            $list['new_num'] = $new_num;
            $list['total_num'] = $total_num;
            $list['total_page'] = $total_page;
            return ['status'=>1,'msg'=>'ok','data'=>$list];
        }
        $this->assign('g_name',$g_name);
        $this->assign('header_title','收藏商品');
        $this->assign('header_path','/member/goodscollection/goods_list');
        return $this->fetch();
    }

}