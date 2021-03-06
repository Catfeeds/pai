<?php

namespace app\member\controller;
use app\data\service\goods\GoodsService;
use app\data\service\goodsCategory\GoodsCategoryService;
use app\data\service\search\SearchHotService;
use app\data\service\search\SearchService;
use RedisCache\RedisCache;
use think\Controller;
use think\Db;

class Classify extends IndexHome
{
    /**
     * @return \think\response\View
     * 分类信息
     * 邓赛赛
     */
    public function classify(){
        $m_id = $this->m_id;
        $goodsCategory = new GoodsCategoryService();
        //分类列表
        $list = $goodsCategory->getCategory();
        //所有分类类型
        $where = [
            'g.g_state'=>6,
            'g.g_endtime'=>['>',time()],
            'gp.gp_stock'=>['>',0],
        ];
        $arr_gc_id = Db::table('pai_goods g')
            ->join('pai_goods_product gp','g.g_id=gp.g_id','left')
            ->where($where)
            ->group('g.gc_id')
            ->column('g.gc_id');
        //剔除三级空分类
        foreach($list as $k => $v){
            foreach($v['son'] as $k2 => $v2){
                foreach($v2['son'] as $k3 => $v3){
                    if(!in_array($v3['gc_id'],$arr_gc_id)){
                        unset($list[$k]['son'][$k2]['son'][$k3]);
                    }
                }
            }
        }
        //剔除二级空分类
        foreach($list as $k => $v){
            foreach($v['son'] as $k2 => $v2){
                if(!$v2['son']){
                    if(!in_array($v2['gc_id'],$arr_gc_id)){
                        unset($list[$k]['son'][$k2]);
                    }
                }
            }
        }
        //剔除一级空分类
        foreach($list as $k => $v){
            if(!$v['son']){
                if(!in_array($v['gc_id'],$arr_gc_id)){
                    unset($list[$k]);
                }
            }
        }
        $title = array_column($list,'gc_name');
        $list = array_values($list);
        $searchs = array();
        if($m_id){
            $where = [
                'm_id'=>$m_id
            ];
            $search = new SearchService();
            $self = $search->get_limit($where,'ps_addtime desc','ps_keyword',1,20);
            $searchs['self'] = array_column($self,'ps_keyword');
        }
        $search_hot = new SearchHotService();
        $hot = $search_hot->get_list([],'psh_sort asc','psh_keyword','');
        //热搜信息
        $searchs['hot'] = array_column($hot,'psh_keyword');

        $this->assign([
            'title'=>json_encode($title),
            'list'=>json_encode($list),
            'searchs'=>$searchs
        ]);
        return view();
    }

    /**
     * @return \think\response\View
     * 商品分类列表
     * 邓赛赛
     */
    public function index(){
        $gc_id = input('param.gc_id');
        $page = input('param.page') ? input('page') : 1;
        $page_size = input('param.page_size') ? input('param.page_size') : 10;
        $goodsCategory = new GoodsCategoryService();
        //当前分类
        $info = $goodsCategory->goodsCategoryInfo(['gc_id'=>$gc_id]);
        $where = [
            'state'=>0,
        ];

        //父ID为0时表示以及分类用gc_id取二级分类
        if($info['parent_id'] == 0){
            $where['parent_id'] = $gc_id;
            $data['parent_id'] =$gc_id;
        }else{
            $where['parent_id'] = $info['parent_id'];
            $data['parent_id'] = $info['parent_id'];
        }
        //二级分类
        $titleAll = $goodsCategory->getCategoryList($where,'gc_id asc','gc_id,gc_name,parent_id',$cache='');
        //删除空二级分类
        $where9 = [
            'g.g_state'=>6,
            'g.g_endtime'=>['>',time()],
            'gp.gp_stock'=>['>',0],
        ];
        $arr_gc_id = Db::table('pai_goods g')
            ->join('pai_goods_product gp','g.g_id=gp.g_id','left')
            ->where($where9)
            ->group('g.gc_id')
            ->column('g.gc_id');
        foreach($titleAll as $k => $v){
            if(!in_array($v['gc_id'],$arr_gc_id)){
                unset($titleAll[$k]);
            }
        }
        $titleAll = array_values($titleAll);
        $data['gc_title'] = $info['gc_name'];
        $data['titleAll'] = $titleAll;
        $data['gc_id'] = $gc_id;



        $goods = new GoodsService();
        if($info['parent_id'] == 0){
            $gc_ids = array_column($titleAll,'gc_id');
            $gc_ids = implode(",",$gc_ids);
            //无二级分类时，查询以及分类
            $gc_ids = empty($gc_ids) ? $gc_id : $gc_ids;
            $where2['g.gc_id'] = ['in',$gc_ids];
        }else{
            $where2['g.gc_id'] = $gc_id;
        }
        $where2['g.g_state'] = 6;
        $where2['g.g_endtime'] = ['>', time()];
        $where2['p.gp_stock'] = ['>',0];
        $list = $goods->goods_category_list($where2,'g.g_id,g.g_name,g.g_img,g.g_s_img,g.gc_id,p.gp_id,p.gp_market_price,dtr.gdr_id,min(dtr.gdr_price) min_price,max(dtr.gdr_price) max_price','g.g_endtime asc',$page_size,$page);
        $data['list'] = $list;
        if(request()->isAjax()){        //ajax请求
            return $data['list'];
        }
        $header_title = $goodsCategory->getValue(['gc_id'=>$gc_id],'gc_name');
        $this->assign('header_title',$header_title);
        $this->assign('data',$data);
        return view();
    }


}
