<?php
/**
* 积分评价商品图片Model
*-------------------------------------------------------------------------------------------
* 版权所有 杭州拍吖吖科技有限公司
* 创建日期 2018-07-02
* 版本 v.1.0.0
* 网站：www.pai.com
*--------------------------------------------------------------------------------------------
*/

namespace app\data\model\pointReview;
use app\data\model\BaseModel as BaseModel;
use think\Db;
use think\Cache;

class ReviewgoodsimgsModel extends BaseModel
{	
  protected $db = 'point_review_goods_imgs' ;//积分评论图片表
	
}