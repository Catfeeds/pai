<?php
namespace app\business\controller;
use app\data\model\pointGoods\PointGoodsProductModel;
use app\data\service\config\ConfigService;
use app\data\service\goods\GoodsService;
use app\data\service\goodsCategory\GoodsCategoryService;
use app\data\service\goodsImgs\GoodsImgsService;
use app\data\service\pointGoods\PointGoodsImgsService;
use app\data\service\goodsSpec\GoodsSpecService;
use app\data\service\pointGoods\PointGoodsProductService;
use app\data\service\pointGoods\PointGoodsService;
use app\data\service\region\RegionService;
use app\data\service\store\StoreService;
use RedisCache\RedisCache;
use think\Db;

class Pointgoods extends MemberHome
{
    /**
     *积分商品列表
     * 邓赛赛
     */
    public function goods_list(){
        $m_id = $this->m_id;
        $point_goods = new PointGoodsService();
        $g_state = input('param.g_state/d',0);
        $g_name = input('param.g_name/s');
        $g_state = $g_state ?  $g_state : 0;
        $where['pg.g_state'] = $g_state ?  $g_state : 0;
        switch ($where['pg.g_state']) {
            case 0:             //全部
                $where['pg.g_state'] =['in', [1, 2, 4, 6, 7, 8]] ;
                $where['pgp.gp_stock'] = ['>',0];
                break;
            case 1:             //竞拍中
                $where['pg.g_state'] = 6;
                $where['pg.g_endtime'] = ['>',time()];
                $where['pgp.gp_stock'] = ['>',0];
                break;
            case 2:             //草稿
                $where['pg.g_state'] = ['in', [1, 7]];
                break;
            case 3:             //审核中
                $where['pg.g_state'] = 2;
                break;
            case 4:             //已失败
                $where['pg.g_state'] = ['in', [4, 8, 9]];
                break;
            default:
                $where['pg.g_state'] =['in', [1, 2, 4, 6, 7, 8]] ;
                $where['pgp.gp_stock'] = ['>',0];
                break;
        }
        //搜索字段
        if($g_name){
            $where['pg.g_name'] = ['like','%'.$g_name.'%'];
        }
        $where['pg.g_mid'] = $m_id;
        $field = "pg.g_img,pg.g_name,pg.g_id,pg.g_state,pg.g_endtime,pg.is_heat,pg.is_top,pg.is_tj,pgp.gp_stock,pgp.gp_market_price,pgp.gp_settlement_price,pgp.gp_sn,pgp.gp_id,pgp.gp_sale_price";
        $list = $point_goods->admin_goods_list($where, $field, 'pg.g_addtime desc');
        $total_num = $list->total();
        $page = $list->render();
        $this->assign(['list' => $list, 'g_state' => $g_state,'total_num'=>$total_num,'page'=>$page]);
        return $this->fetch();
    }

	/**
	* 商品发布
     * 邓赛赛
	*/
    public function add_goods()
    {
        $m_id = $this->m_id;
        $store = new StoreService();
        $goods = new GoodsService();
        $point_goods = new PointGoodsService();
        if (request()->isPost()) {                                  //发布商品
            $data = input('post.');
            $pid  = input('param.pid');
            $cid  = input('param.cid');
            $aid  = input('param.aid');
            $data['pid'] = trim($pid.','.$cid.','.$aid,',');
            $data['g_limited'] = 1;
            $g_endtime = input('post.g_endtime');
            $g_starttime = input('post.g_starttime');
            $data['g_starttime'] = empty($g_starttime)  ?    time() : strtotime($g_starttime);
            $data['g_endtime']   = empty($g_endtime)    ?    $data['g_starttime']+86400*30 : strtotime($g_endtime);

            //保存草稿商品时直接插入
            if(!empty($data['cancel'])){
                $res = $point_goods->add_goods($data, $m_id,7);
                return $res;
            }

            //验证发布商品
            $check = $goods->check_goods($data);
            if($check){
                return $check;
            }
            $res = $point_goods->add_goods($data, $m_id,2);
            return $res;
        }

        //商品分类
        $address = $this->address(0);
//        $category = $this->category();
        //商家类型
        $store_type = $store->get_value(['m_id' => $m_id],'store_type');

        //商品类型(特殊属性如：大宗、实物、虚拟)
        $goods_spec = new GoodsSpecService();
        $specWhere['gs_state'] = 0;
        if($store_type==2){
            $specWhere['gs_store_type'] =2;
        }
        //未中拍返回花生折扣
        $config = new ConfigService();
        $wehre = [
            'c_code'=>'FH_HS',
            'c_state'=>0,
        ];
        $gp_gift_peanut = $config->configGetValue($wehre,'c_value');
        $spec = $goods_spec->goodsSpecList($specWhere);
        $category = $this->category(0);                        //店铺一级分类
        $this->assign(['category1'=>$category,'address'=>$address,'spec'=>$spec,'g_state'=>0,'gp_gift_peanut'=>$gp_gift_peanut]);
        return view();
    }

//    /**
//     * @return array
//     * 再次发拍
//     * 邓赛赛
//     */
//    public function supply()
//    {
//        $goods = new GoodsService();
//        $point_goods = new PointGoodsService();
//        $where = [
//            'g_mid' => $this->m_id,
//            'g_id' => input('param.g_id')
//        ];
//        $info = $point_goods->get_info($where,"*");
//        if($info['g_state'] != 8){
//            return ['status'=>0,'msg'=>'此商品目前非流拍状态'];
//        }
//        if (request()->isPost()) {
//            $data['g_id'] = input('post.g_id');
//            $data['g_starttime'] = input('post.g_starttime');
//            $data['g_endtime'] = input('post.g_endtime');
//            $data['gp_stock'] = input('post.gp_stock');
//            $data['gp_settlement_price'] = input('post.gp_settlement_price');
//            $data['gp_market_price'] = input('post.gp_market_price');
//
//            if(!trim($data['g_id'])){
//                return ['status' => 0, 'msg' => '未找到此商品'];
//            }
//            if(empty(trim((int)$data['gp_settlement_price']))){
//                return ['status' => 0, 'msg' => '请输入结算价'];
//            }
//            if(empty(trim((int)$data['gp_market_price']))){
//                return ['status' => 0, 'msg' => '请输入市场价'];
//            }
//
//            if($data['gp_market_price']*0.8 < $data['gp_settlement_price']){
//                return ['status' => 0, 'msg' => '结算价不可高于市场价80%'];
//            }
//
//            if(!trim($data['g_starttime'])){
//                return ['status' => 0, 'msg' => '请输入开始时间'];
//            }
//            if(!trim($data['g_endtime'])){
//                return ['status' => 0, 'msg' => '请输入结束时间'];
//            }
//            if((time()-strtotime($data['g_starttime'])) > 0){
//                return ['status'=>0,'msg'=>'开始时间不能小于发布时间'];
//            }
//            if((strtotime($data['g_endtime']) - strtotime($data['g_starttime']))<86400*3 || (strtotime($data['g_endtime']) - strtotime($data['g_starttime'])>86400*15)){
//                return ['status'=>0,'msg'=>'开始时间和结束时间区间在3-15日'];
//            }
//            if(empty(trim((int)$data['gp_stock']))){
//                return ['status' => 0, 'msg' => '请输入库存数量'];
//            }
//
//            $goods_update = [
//                'g_starttime' => strtotime($data['g_starttime']),
//                'g_endtime' => strtotime($data['g_endtime']),
//                'g_state' => 6,
//            ];
//            $product_update = [
//                'gp_stock' => $data['gp_stock'],
//                'gp_sn' => "SN" . time() . rand(1000, 9999) . $data['g_id'],
//            ];
//            $product = new PointGoodsProductService();
//            Db::startTrans();
//            try {                                                            //日期和库存同时成功才成功
//                $point_goods->get_save($where, $goods_update);
//                $product->goodsProductSave(['g_id' => $data['g_id']], $product_update);
//                Db::commit();
//                $msg = ['status' => 1, 'msg' => '上架成功'];
//                // 提交事务
//            } catch (\Exception $e) {
//                // 回滚事务
//                Db::rollback();
//                $msg = ['status' => 0, 'msg' => '上架失败'];
//            }
//            return $msg;
//        }
//    }

    /**
     * 继续编辑商品(继续中和未通过)
     * 邓赛赛
     */
    public function reedit()
    {
        $m_id = $this->m_id;
        $g_id = input('param.g_id');
        $goods = new GoodsService();
        $point_goods = new PointGoodsService();
        $where = [
            'g_mid' => $m_id,
            'g.g_id' => $g_id,
        ];
        $info = $point_goods->get_goods_info($where, ['g.g_id' => $g_id, "g.*,gp.*"]);            //商品详情
        $array = [2,4,7,8,9];
        if(!in_array($info['g_state'],$array)){
            $this->error('此商品类型不可修改');
        }
        $store = new StoreService();
        if (request()->isPost()) {
            $data = input('post.');

            $pid  = input('param.pid');
            $cid  = input('param.cid');
            $aid  = input('param.aid');
            $data['pid'] = trim($pid.','.$cid.','.$aid,',');
            $data['g_limited'] = 1;
            //再次保存时
            $g_state = 7;
            //在此编辑时
            if(empty($data['cancel'])){
                $g_endtime = input('post.g_endtime');
                $g_starttime = input('post.g_starttime');
                //转化时间戳做验证使用
                $data['g_starttime'] = empty($g_starttime)  ?    time() : strtotime($g_starttime);
                $data['g_endtime']   = empty($g_endtime)    ?    $data['g_starttime']+86400*30 : strtotime($g_endtime);

                $check = $goods->check_goods($data);
                if($check){
                    if($info['g_img']){
                        if($check['msg'] != '商品图片不能为空'){
                            return $check;
                        }
                    }else{
                        return $check;
                    }
                }
            }
            $where = ['m_id' => $m_id];
            $res = $point_goods->continue_edit($data, $where,2,$m_id);
            return $res;
        }

        $goods_spec = new GoodsSpecService();                                           //商品类型(属性表)
        $spec = $goods_spec->goodsSpecList(['gs_state' => 0]);
        $goods_category = new GoodsCategoryService();
       $category1 = $this->category(0);                        //店铺一级分类

        //地址列表
        $region = new RegionService();
        $region_list = $region->regionList();

        //关联父级gc_id（根据gc_id查出所有父级gc_id和分类）
        $str_gc_id = $info['gc_id'];
        $gc_id = $info['gc_id'];
        while($gc_id){
            $where = [
                'gc_id'=>$gc_id
            ];
            $gc_id = $goods_category->getValue($where,'parent_id');
            if(!$gc_id){
                continue;
            }
            $str_gc_id = $gc_id.','.$str_gc_id;
        }
        $info['str_gc_id'] = explode(',',$str_gc_id);
        $store_type = $store->get_value(['m_id'=>$m_id],'store_type');
        //商品信息、 商品特殊属性、商家类型、商品状态
        $info['g_starttime']    = $info['g_starttime']  ? date('Y-m-d H:i',$info['g_starttime']) : 0;
        $info['g_endtime']      = $info['g_endtime']    ? date('Y-m-d H:i',$info['g_endtime'])   : 0;
        $point_goods_img = new PointGoodsImgsService();
        $info['img'] = $point_goods_img->get_list(['g_id'=>$g_id],'gi_sort asc','*');
        $this->assign(['info' => $info, 'spec' => $spec,'store_type'=>$store_type]);
        //所有地址列表
        $this->assign('region_list',$region_list);
        //省份地址
        $address = $this->address();
        $this->assign('address',$address);

        //调用指定分类列表
        //二级分类
        if(!empty($info['str_gc_id'][0])){
            $category2 = $this->category($info['str_gc_id'][0]);
        }else{
            $category2 = array();
        }
        //三级分类
        if(!empty($info['str_gc_id'][1])){
            $category3 = $this->category($info['str_gc_id'][1]);
        }else{
            $category3 = array();
        }
        //一级分类
        $this->assign('category1',$category1);
        //二级分类
        $this->assign('category2',$category2);
        //三级分类
        $this->assign('category3',$category3);
        //未中拍返回花生折扣
        $config = new ConfigService();
        $wehre = [
            'c_code'=>'RQ_FH_HS',
            'c_state'=>0,
        ];
        $gp_gift_peanut = $config->configGetValue($wehre,'c_value');
        $this->assign('gp_gift_peanut',$gp_gift_peanut);

        return $this->fetch('pointgoods/add_goods');
    }

    /**
     * 取消发布商品
     * 邓赛赛
     */
    public function cancel()
    {
        $where = [
            'g_id' => input('post.g_id'),
            'g_mid' => $this->m_id,
        ];
        $update = [
            'g_state' => 5
        ];
        $point_goods =  new PointGoodsService();
        $res = $point_goods->cancelShop($where, $update);
        if ($res) {
            return ['status' => 1, 'msg' => '取消发布成功'];
        } else {
            return ['status' => 0, 'msg' => '取消发布失败'];
        }
    }
    /**
     * @return mixed
     * 删除图片
     * 邓赛赛
     */
    public function delete_img()
    {
        $data = input('post.');
        $where = [
            'gi_id' => $data['gi_id'],
            'g_id' => $data['g_id']
        ];
        $goods = new PointGoodsImgsService();
        $res = $goods->del_goods_img($where);
        return $res;
    }
    /**
     * @return array
     * 修改库存
     * 邓赛赛
     */
    public function set_stock(){
        $where['g_id'] = input('post.g_id');
        $data['gp_stock'] = input('post.gp_stock/d');
        $product_goods = new PointGoodsProductService();
        $res = $product_goods->goodsProductSetField($where,$data);
        if($res){
            return ['status'=>1,'msg'=>'修改成功','data'=>$data];
        }else{
            return ['status'=>0,'msg'=>'修改失败'];
        }
    }

    /**
     * 设置热拍、推荐、置顶
     * 邓赛赛
     */
    public function set_goods(){
        $where['g_id'] = input('post.g_id');
        $where['g_mid'] = $this->m_id;
        $data['is_heat'] = input('post.is_heat/d');
        $data['is_top'] = input('post.is_top/d');
        $data['is_tj'] = input('post.is_tj/d');
        if( $data['is_heat'] === null){
            unset( $data['is_heat']);
        }
        if( $data['is_top'] === null){
            unset( $data['is_top']);
        }
        if( $data['is_tj'] === null){
            unset( $data['is_tj']);
        }
        $point_goods = new PointGoodsService();
        $res = $point_goods->get_save($where,$data);
        if($res){
            $redis = RedisCache::getInstance();
            $redis->del('pointgoods');
            return ['status'=>1,'msg'=>'修改成功','data'=>$data];
        }else{
            return ['status'=>0,'msg'=>'修改失败'];
        }
    }

    /**
     * 获取地址
     * 邓赛赛
     */
    public function address($region_code=0){
        $region_code = input('param.region_code');
        $regon = new RegionService();
        if(!$region_code){
            $where = [
                'father_id' => 0
            ];
        }else{
            $where = [
                'father_id' => $region_code
            ];
        }
        $list = $regon->regionList($where,'','region_id,region_name,region_id,region_code');
        return $list;
    }

    /**
     * 获取分类信息
     * 邓赛赛
     */
    public function category($gc_id=0){
        $g_category = new GoodsCategoryService();
        $where = [
            'parent_id'=>$gc_id,
            'state'=>0
        ];
        $list = $g_category->getCategoryList($where,$order='gc_id desc',$field='*');
        return $list;
    }





}
