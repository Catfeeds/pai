<?php
/**
 * 公共Service
 *-------------------------------------------------------------------------------------------
 * 版权所有 广州市素材火信息科技有限公司
 * 创建日期 2017-07-12
 * 版本 v.1.0.0
 * 网站：www.sucaihuo.com
 *--------------------------------------------------------------------------------------------
 */

namespace app\data\service\pointGoods;
use app\data\model\pointGoods\PointGoodsModel;
use app\data\service\admit\AdmitService;
use app\data\service\BaseService as BaseService;
use app\data\service\config\ConfigService;
use app\data\service\member\MemberService;
use app\data\service\popularity\PopularityGoodsService;
use app\data\service\region\RegionService;
use think\Db;
class PointGoodsService extends BaseService
{
    protected $cache = 'point_goods';
    protected $pid = 0;

    public function __construct()
    {
        parent::__construct();
        $this->point_goods = new PointGoodsModel();
        $this->cache = 'point_goods';
    }


    /**
     * $data 插入数据
     * $m_id 用户ID
     * $g_state 发布商品状态
     * 用户发布商品
     * 创建人 邓赛赛
     */
    public function add_goods($data,$m_id,$g_state = 2){

        $files = request()->file('g_img');
        if($files){
            $imgs = $this->uploads("shop",$data['g_img']);
        }else{
            if(!empty($data['g_img']) && is_array($data['g_img']) ){
                $data['g_img'] = array_values(array_filter($data['g_img']));
                $imgs = $this->point_goods->ba64_img($data['g_img'],'shop');
            }else{
                $imgs[0]='';
            }
        }
        $store_id = Db::table("pai_store")->where('m_id',$m_id)->field('store_id')->find();         //获取商家id
        $insertGoods = [
            'g_name'                    => empty($data['g_name']) ? '' : $data['g_name'],
            'pid'                       => empty($data['pid']) ? '' :$data['pid'],
            'g_secondname'              =>empty($data['g_secondname']) ? '' :$data['g_secondname'] ,
            'g_mid'                     =>$m_id,
            'g_storeid'                 =>empty($store_id['store_id']) ? '' : $store_id['store_id'],
            'g_description'             =>empty($data['g_description']) ? '' : $data['g_description'],
            'g_typeid'                  =>empty($data['g_typeid']) ? '' : $data['g_typeid'] ,
            'gc_id'                     =>empty($data['gc_id']) ? '' : $data['gc_id'] ,
            'g_express'                 =>0,                         //运费
            'g_express_way'             =>empty($data['g_express_way']) ? '' : $data['g_express_way'],                 //运送方式
            'g_peoplenumber'             =>empty($data['g_peoplenumber']) ? '' : $data['g_peoplenumber'],                 //成团人数
            'g_addtime'                 =>time(),
            'g_starttime'               => empty($data['g_starttime']) ? '' : $data['g_starttime'],
            'g_endtime'                 => empty($data['g_endtime']) ? '' : $data['g_endtime'],
            'g_img'                     => empty($imgs[0]) ? '' : $imgs[0],                                            //主图
            'g_state'                   => $g_state,                                                                    //商品状态
        ];

        $address = explode(',',$insertGoods['pid']);
        $insertGoods['pid'] = empty($address[0]) ? '' : $address[0];
        $insertGoods['cid'] = empty($address[1]) ? '' : $address[1];
        $insertGoods['aid'] = empty($address[2]) ? '' : $address[2];
        $id = $this->point_goods->insertId($insertGoods);
        if($id){
            $config = new ConfigService();
            $wehre = [
                'c_code'=>'FH_HS',
                'c_state'=>0,
            ];
            $gp_gift_peanut = $config->configGetValue($wehre,'c_value');
            $insertProduct = [
                'gp_stock'              =>  empty($data['gp_stock'])             ? '' : $data['gp_stock'],
                'gp_settlement_price'   =>  empty($data['gp_settlement_price'])  ? '' : $data['gp_settlement_price'],
                'gp_market_price'       =>  empty($data['gp_market_price'])      ? '' : $data['gp_market_price'],
                'gp_description'        =>  empty($data['g_description'])        ? '' : $data['g_description'],
                'gp_sale_price'         =>  empty($data['gp_sale_price'])      ? '' : $data['gp_sale_price'],
                'gp_gift_peanut'         =>  $data['gp_sale_price']*$gp_gift_peanut,
                'gp_sn'                 => "SN".time().$id,
                'g_id'                  => $id,
                'gp_img'                => empty($imgs[0])   ? ''   :   $imgs[0],
                'gp_state'              => $g_state,
            ];
            //添加puoduct表
            Db::table("pai_point_goods_product")->insert($insertProduct);

            $imgArr = array();
            //保存草稿时可能为假
            if(!empty($imgs) && array_filter($imgs)){
                foreach($imgs as $k => $v){
                    $imgArr[$k] = [
                        'gi_name'=>$v,
                        'gi_sort'=>$k+1,
                        'g_id'=>$id,
                    ];
                }
                //添加pai_goods_imgs表
                Db::table("pai_point_goods_imgs")->insertAll($imgArr);
            }


            //详情二维码
            $p_goods = new PopularityGoodsService();
            $mid = 3;
            $path_3 = '/uploads/code/pointpai/shop/'.$mid.'code_'.$id.".jpg";
            $goodsData['code']  = $p_goods->hebingImg('/uploads/logo/father.png',$imgs[0],$path_3,$data['g_name'],$insertProduct['gp_sale_price'],$mid,$id);

            return  ['status'=>1,'msg'=>'ok','date'=>['g_id'=>$id]];
        }else{
            $msg =  ['status'=>0,'msg'=>'添加商品失败,请稍后重试'];
            return $msg;
        }
    }

    /**
     * 取消发布商品
     * 邓赛赛
     */
    public function cancelShop($where,$update){
        $res = $this->point_goods->getSave($where,$update);
        return $res;
    }

    /**
     * 获取商品列表
     * 邓赛赛
     */
    public function get_list($where=[],$order='g_id asc',$field='*'){
        $list = $this->point_goods->getList($where,$order,$field,'');
        return $list;
    }

    /**
     * 获取商品列表(分页)
     * 邓赛赛
     */
    public function get_page_list($where=[],$order='g_id asc',$field='*',$page_size=20){
        $list = $this->point_goods->getPaginate($where, $order, $field, $page_size, $cache='');
        return $list;
    }

    /**
     * 获取商品列表(分页)
     * 邓赛赛
     */
    public function get_join_list($where=[],$order='g.g_id asc',$field='*',$page_size=20){
        $list = Db('point_goods g')
            ->join('pai_point_goods_product p','g.g_id=p.g_id','left')
            ->where($where)
            ->field($field)
            ->order($order)
            ->paginate($page_size,false,['query'=>request()->param() ]);
        return $list;
    }

    /**
     * 获取某个字段
     * 邓赛赛
     */
    public function get_value($where=[],$field='*'){
        $list = $this->point_goods->get_value($where,$field);
        return $list;
    }

    /**
     * 修改商品
     * @param $where
     * @param $data
     * @return bool|int|string
     * 邓赛赛
     */
    public function get_save($where,$data){
        $res = $this->point_goods->getSave($where,$data,"");
        return $res;
    }

    /**
     * 获取goods信息详情
     * @param $where
     * @param string $field
     * @return array|false|\PDOStatement|string|\think\Model
     * 邓赛赛
     */
    public function get_goods_info($where,$field="g.*,gp.*"){

        $info = Db::table("pai_point_goods")
            ->alias('g')
            ->where($where)
            ->join("pai_point_goods_product gp",'g.g_id=gp.g_id','left')
            ->join("pai_member m",'m.m_id=g.g_mid','left')
            ->field($field)
            ->find();
        if($info){
            $imgWhere = ['g_id'=>$where['g.g_id']];
            $info['img'] = Db::table('pai_goods_imgs') ->order('gi_sort asc')->where($imgWhere)->select();
        }
        return $info;
    }

    //我的商品列表（后台）
    public function admin_goods_list($where,$field='pgp.*,pg.*',$order='pg.g_id desc'){
        $list = Db($this->cache)->alias('pg')                                         //分页数据
            ->where($where)
            ->join('pai_point_goods_product pgp','pg.g_id=pgp.g_id','left')
            ->field($field)
            ->order($order)
            ->paginate(10);
        return $list;
    }


    /**
     * @param $where
     * @param string $field
     * @return array|false|\PDOStatement|string|\think\Model
     * 获取单条数据
     * 邓赛赛
     */
    public  function get_info($where,$field='*'){
        $res = $this->point_goods->getInfo($where,$field);
        return $res;
    }

    /**
     * @param $where
     * @param string $field
     * @return array|bool
     * 获取某列字段
     * 邓赛赛
     */
    public function get_column($where,$field='g_id'){
        $list = $this->point_goods->getColumn($where,$field);
        return $list;
    }

    /**
     * 获取goods信息详情
     * @param $where
     * @param string $field
     * @return array|false|\PDOStatement|string|\think\Model
     * 刘勇豪
     */
    public function goods_info($where='',$field="*"){
        $info = Db("point_goods")->alias("pg")
            ->join('pai_point_goods_product pgp','pg.g_id=pgp.g_id','left')
            ->field($field)
            ->where($where)
            ->find();

        if(!empty($info)){
            // 开始倒计时 结束倒计时
            $g_starttime = $info['g_starttime'];
            $g_endtime = $info['g_endtime'];
            $left_starttime = $g_starttime - time();
            $left_endtime = $g_endtime - time();
            $info['left_starttime'] = $left_starttime;
            $info['left_endtime'] = $left_endtime;

            // 地区
            $region = new RegionService();
            $pid = $info['pid'];
            $cid = $info['cid'];
            $aid = $info['aid'];
            $p_name = $region->regionName_by_region_code($pid);
            $c_name = $region->regionName_by_region_code($cid);
            $a_name = $region->regionName_by_region_code($aid);
            $info['p_name'] = $p_name;
            $info['c_name'] = $c_name;
            $info['a_name'] = $a_name;

            // 商品轮播图
            $banner_list = Db("point_goods_imgs")->where(['g_id'=>$info['g_id']])->select();
            $info['banner_list'] = $banner_list;
        }

        return $info;
    }

    /**
     * 判断拍品是不是自己发布的
     * 刘勇豪
     * @param int $gp_id
     * @param int $m_id
     * @return array
     */
    public function is_my_goods($gp_id=0,$m_id=0){
        if(!$gp_id){
            return ['status'=>0,'msg'=>'参数错误！'];
        }

        $where['pgp.gp_id'] = $gp_id;
        $gp_info = Db("point_goods_product")->alias("pgp")
            ->join('pai_point_goods pg', 'pg.g_id = pgp.g_id', 'left')
            ->where($where)
            ->find();

        if(empty($gp_info)){
            return ['status'=>0,'msg'=>'拍品不存在！'];
        }

        $g_mid = $gp_info['g_mid'];

        if($g_mid == $m_id){
            return ['status'=>0,'msg'=>'您不能参拍您自己发布的商品哦~'];
        }

        return ['status'=>1,'msg'=>'这是别人发布的商品，可以购买！~'];
    }

    /**
     * 积分列表
     * 邓赛赛
     */
    public function point_goods_list($where=[],$field='*',$order='pg.g_id asc',$page,$page_size,$cache=''){
        $page = $page<1 ? 1 : $page;
        $offset = ($page - 1) * $page_size;
        $list = $this->point_goods->goods_list($where,$field,$order,$offset,$page_size,$cache);
        return $list;
    }

    /**
     * 编辑中的商品
     * 邓赛赛
     */
    public function continue_edit($data,$where,$g_state,$m_id){
        $img = Db::table("pai_point_goods_imgs")->field('gi_name')->where(['g_id'=>$data['g_id']])->select();       //获取之前主图片
        $files = request()->file('g_img');
        if(!$img && $g_state != 7){
            if(empty($data['g_img']) && empty($files) && empty($img)){   //查看是否上传图片或者已有图片
                return  ['status'=>0,'msg'=>'请上传图片'];
            }
        }
        if($files){
            $imgs = $this->uploads("shop","g_img");
        }else{
            if(!empty($data['g_img'])){
                $imgs = $this->point_goods->ba64_img($data['g_img'],'shop');
                if(!$imgs){
                    $where3 = [
                        'g_id'=>$data['g_id']
                    ];
                    $imgs[0] = $this->point_goods->get_value($where3,'g_img');
                }
            }else{
                $imgs[0]='';
            }
        }
        $store_id = Db::table("pai_store")->where(['m_id'=>$where['m_id']])->field('store_id')->find();     //获取商家id
        $insertGoods = [
            'g_name'                    => empty($data['g_name']) ? '' : $data['g_name'],
            'pid'                       => empty($data['pid']) ? '' :$data['pid'],
            'g_secondname'              =>empty($data['g_secondname']) ? '' :$data['g_secondname'] ,
            'g_mid'                     =>$m_id,
            'g_storeid'                 =>empty($store_id['store_id']) ? '' : $store_id['store_id'],
            'g_description'             =>empty($data['g_description']) ? '' : $data['g_description'],
            'g_typeid'                  =>empty($data['g_typeid']) ? '' : $data['g_typeid'] ,
            'gc_id'                     =>empty($data['gc_id']) ? '' : $data['gc_id'] ,
            'g_express'                 =>0,                         //运费
            'g_express_way'             =>empty($data['g_express_way']) ? '' : $data['g_express_way'],                 //运送方式
            'g_peoplenumber'             =>empty($data['g_peoplenumber']) ? '' : $data['g_peoplenumber'],                 //成团人数
            'g_addtime'                 =>time(),
            'g_starttime'               => empty($data['g_starttime']) ? '' : $data['g_starttime'],
            'g_endtime'                 => empty($data['g_endtime']) ? '' : $data['g_endtime'],
            'g_img'                     => empty($imgs[0]) ? '' : $imgs[0],                                            //主图
            'g_state'                   => $g_state,                                                                    //商品状态
        ];
        $insertGoods = array_filter($insertGoods);
        $address = explode(',',$insertGoods['pid']);
        $insertGoods['pid'] = empty($address[0]) ? '' : $address[0];
        $insertGoods['cid'] = empty($address[1]) ? '' : $address[1];
        $insertGoods['aid'] = empty($address[2]) ? '' : $address[2];
        $res = $this->point_goods->getSave(['g_id'=>$data['g_id']],$insertGoods);
        if($res){
            $config = new ConfigService();
            $wehre = [
                'c_code'=>'FH_HS',
                'c_state'=>0,
            ];
            $gp_gift_peanut = $config->configGetValue($wehre,'c_value');
            $insertProduct = [
                'gp_stock' => $data['gp_stock'],
                'gp_settlement_price' =>  $data['gp_settlement_price'],
                'gp_market_price' => $data['gp_market_price'],
                'gp_description' => empty($data['gp_description']) ? '' : $data['gp_description'],
                'gp_sale_price'         =>  empty($data['gp_sale_price'])      ? '' : $data['gp_sale_price'],
                'gp_gift_peanut'         =>  $data['gp_sale_price']*$gp_gift_peanut,
                'gp_sn'                 => "SN".time().$data['g_id'],
                'gp_img' =>empty($imgs[0]) ? '' : $imgs[0],
                'gp_state' => $g_state,
            ];
            Db::table("pai_point_goods_product")->where(['g_id'=>$data['g_id']])->update($insertProduct);         //添加puoduct表

            $imgArr = array();
            $imgs = array_unique($imgs);
            if(array_filter($imgs)){
                foreach($imgs as $k => $v){
                    $imgArr[$k] = [
                        'gi_name'=>$v,
                        'gi_sort'=>count($img)+$k+1,
                        'g_id'=>$data['g_id'],
                    ];
                }
                Db::table("pai_point_goods_imgs")->insertAll($imgArr);      //添加pai_goods_imgs表
            }

            //合成详情二维码
            $pl_goods = new PopularityGoodsService();
            $mid = 3;
            $path_3 = '/uploads/code/pointpai/shop/'.$mid.'code_'.$data['g_id'].".jpg";
            //详情二维码
            $merge = 'uploads/code/pointpai/shop/'.$mid.'merge_'.$data['g_id'].".jpg";
            if(is_file($merge)){
                unlink(trim($merge,'/'));
            }
            //编辑时生成新二维码
            $where = [
                'g_id'=>$data['g_id']
            ];
            $g_img = $this->point_goods->get_value($where,'g_img');
            $goodsData['code']  = $pl_goods->hebingImg('/uploads/logo/father.png',$g_img,$path_3,$data['g_name'],$insertProduct['gp_settlement_price'],$mid,$data['g_id'],1);
//            dump($goodsData['code']);die;
            return  ['status'=>1,'msg'=>'ok'];
        }else{
            $msg =  ['status'=>0,'msg'=>'添加商品失败,请稍后重试'];
            return $msg;
        }
    }

    /**
     * 是否收藏
     * 刘勇豪
     * @param int $g_id
     * @param int $m_id
     * @return int
     */
    public function is_collection($g_id=0,$m_id=0){
        $is_collecttion = 0;
        if(!$g_id || !$m_id){
            return $is_collecttion;
        }

        $where['g_id'] = $g_id;
        $where['m_id'] = $m_id;
        $find = Db("point_goods_collection")->where($where)->find();
        if(!empty($find)){
            $is_collecttion = 1;
        }
        return $is_collecttion;
    }


}