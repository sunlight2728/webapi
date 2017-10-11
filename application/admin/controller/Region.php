<?php
/**
 * Created by PhpStorm.
 * User: chenly
 * Date: 2017/10/11
 * Time: 14:09
 */

namespace app\admin\controller;

use app\common\controller\BaseAdmin;
use app\common\service\DataService;
use app\common\service\ToolService;
use think\Db;

class Region extends BaseAdmin{

    public $table = 'region';

    public function index()
    {
        $this->title = '地区管理';

        $get = $this->request->get();


        $country = "100000";
        $countrys = Db::name($this->table)->where("code",$country)->order('code asc')->select();
        $this->assign('countrys', $countrys);
        $this->assign('country', $country);

        if(isset($get['province']) && $get['province'] !== ''){
            $province = $get['province'];
            $provinces = Db::name($this->table)->where("code",$province)->order('code asc')->select();
            $this->assign('provinces', $provinces);
            $this->assign('province', $province);
        }else{
            $this->assign('provinces', "");
        }
        if(isset($get['city']) && $get['city'] !== ''){
            $city = $get['city'];
            $citys = Db::name($this->table)->where("code",$city)->order('code asc')->select();
            $this->assign('citys', $citys);
            $this->assign('city', $city);
        }else{
            $this->assign('citys', "");
        }
        $db = Db::name($this->table)->order('code asc');
        return parent::_list($db, true);

    }

    protected function _index_data_filter(&$data)
    {
        foreach ($data as &$vo) {
            ($vo['parentCode'] !== '100000') && ($vo['parentCode'] !=='0' );
            $vo['ids'] = join(',', ToolService::getArrSubIds($data, $vo['code'],"code","parentCode"));
        }
        $data = ToolService::arr2table($data,"code","parentCode");
    }

    public function getchildregion($parentCode){
        $db = Db::name($this->table)->where("parentCode",$parentCode)->order('code asc');
        $data = $db->select();
        return json($data);
    }
}