<?php
/**
 * Created by PhpStorm.
 * User: chenly
 * Date: 2017/10/13
 * Time: 14:21
 */

namespace app\api\controller;

use think\Request;
use think\controller\Rest;
use think\Db;

class Television extends Rest{
    /**
     * @param int $page
     * @param int $pageSize
     * @param $typeId
     * @return mixed
     */
    public function getTvForPageByType($page = 1,$pageSize = 15,$typeId){
        $options=[
            'page'=>$page
        ];

        $map[]=['exp','FIND_IN_SET('.$typeId.',a.type_ids)'];
        $lists = Db::field('a.*,b.name as countryName,c.name as provinceName,GROUP_CONCAT(d.name) AS typeNames')
            ->table("t_television")
            ->alias('a')
            ->join(' t_region b ',' a.country = b.code ','left')
            ->join(' t_region c ',' a.province = c.code ','left')
            ->join(' t_dict d','FIND_IN_SET(d.id , a.type_ids) ','left')
            ->where($map)
            ->group('a.id')
            ->order('a.id asc')
            ->paginate($pageSize,false,$options);

        $result = [
            "code"=>"10000",
            "desc"=>"",
            "data"=>$lists->all()
        ];
        return json($result);


    }

    /**
     *
     * 根据属性获取电视台记录
     * @param int $page
     * @param int $pageSize
     * @param int $typeId
     * @return mixed
     */
    public function getTvByProperty($page = 1,$pageSize = 15,$typeId=0){

        $where = array();
        if( $typeId> 0){
            switch($typeId){
                case 1:
                    $where=array(
                        "is_new"=>1
                    );
                    break;
                case 2:
                    $where=array(
                        "is_hot"=>1
                    );
                    break;
                case 3:
                    $where=array(
                        "is_recommend"=>1
                    );
                    break;
                default :
                    $where=array();
                    break;
            }
        }
        $options=[
            'page'=>$page
        ];

        $lists = Db::field('a.*,b.name as countryName,c.name as provinceName,GROUP_CONCAT(d.name) AS typeNames')
            ->table("t_television")
            ->alias('a')
            ->join(' t_region b ',' a.country = b.code ','left')
            ->join(' t_region c ',' a.province = c.code ','left')
            ->join(' t_dict d','FIND_IN_SET(d.id , a.type_ids) ','left')
            ->where($where)
            ->group('a.id')
            ->order('a.id asc')
            ->paginate($pageSize,false,$options);


        $result = [
            "code"=>"10000",
            "desc"=>"",
            "data"=>$lists->all()
        ];
        return json($result);

    }


    /**
     * APP首页数据
     * @return mixed
     */
    public function getTvForIndex(){

        $hot=array(
            "is_hot"=>1
        );
        $recommend=array(
            "is_recommend"=>1
        );
        $new=array(
            "is_new"=>1
        );

        $lists_hot = Db::field('a.*,b.name as countryName,c.name as provinceName,GROUP_CONCAT(d.name) AS typeNames')
            ->table("t_television")
            ->alias('a')
            ->join(' t_region b ',' a.country = b.code ','left')
            ->join(' t_region c ',' a.province = c.code ','left')
            ->join(' t_dict d','FIND_IN_SET(d.id , a.type_ids) ','left')
            ->where($hot)
            ->group('a.id')
            ->order('a.id asc')
            ->limit(4)->select();
        $lists_new = Db::field('a.*,b.name as countryName,c.name as provinceName,GROUP_CONCAT(d.name) AS typeNames')
            ->table("t_television")
            ->alias('a')
            ->join(' t_region b ',' a.country = b.code ','left')
            ->join(' t_region c ',' a.province = c.code ','left')
            ->join(' t_dict d','FIND_IN_SET(d.id , a.type_ids) ','left')
            ->where($new)
            ->group('a.id')
            ->order('a.id asc')
            ->limit(4)->select();
        $lists_recommend = Db::field('a.*,b.name as countryName,c.name as provinceName,GROUP_CONCAT(d.name) AS typeNames')
            ->table("t_television")
            ->alias('a')
            ->join(' t_region b ',' a.country = b.code ','left')
            ->join(' t_region c ',' a.province = c.code ','left')
            ->join(' t_dict d','FIND_IN_SET(d.id , a.type_ids) ','left')
            ->where($recommend)
            ->group('a.id')
            ->order('a.id asc')
            ->limit(4)->select();
        $result_array = [
            "hots"=>$lists_hot,
            "recommends"=>$lists_recommend,
            "news"=>$lists_new
        ];
        $result = [
            "code"=>"10000",
            "desc"=>"",
            "data"=>$result_array
        ];
        return json($result);
    }

    /**获取用户历史 或者 关注的记录
     * @param int $page
     * @param int $pageSize
     * @param $userId
     * @param $typeId
     * @return mixed
     */
    public function getTvByUserIdAndTypeId($page = 1,$pageSize = 15,$userId,$typeId){

        $where = [
            "e.user_id"=>$userId,
            "e.type_id"=>$typeId
        ];

        $options=[
            'page'=>$page
        ];

        $lists = Db::field('a.*,b.name as countryName,c.name as provinceName,GROUP_CONCAT(d.name) AS typeNames')
            ->table("t_television")
            ->alias('a')
            ->join(' t_region b ',' a.country = b.code ','left')
            ->join(' t_region c ',' a.province = c.code ','left')
            ->join(' t_dict d','FIND_IN_SET(d.id , a.type_ids) ','left')
            ->join(' t_user_tv e','e.tv_id = a.id ')
            ->where($where)
            ->group('a.id')
            ->order('a.id asc')
            ->paginate($pageSize,false,$options);

        $result = [
            "code"=>"10000",
            "desc"=>"",
            "data"=>$lists->all()
        ];
        return json($result);
    }
}