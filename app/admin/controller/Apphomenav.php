<?php
// +----------------------------------------------------------------------
// | Aushop 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\service\AppHomeNavService;
use app\service\ResourcesService;

/**
 * 手机管理-首页导航管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppHomeNav extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 登录校验
        $this->IsLogin();

        // 权限校验
        $this->IsPower();
    }

    /**
     * [Index 手机管理-首页导航列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 总数
        $total = AppHomeNavService::AppHomeNavTotal($this->form_where);

        // 分页
        $page_params = [
            'number'    =>  $this->page_size,
            'total'     =>  $total,
            'where'     =>  $this->data_request,
            'page'      =>  $this->page,
            'url'       =>  MyUrl('admin/apphomenav/index'),
        ];
        $page = new \base\Page($page_params);

        // 获取列表
        $data_params = [
            'where'         => $this->form_where,
            'm'             => $page->GetPageStarNumber(),
            'n'             => $this->page_size,
            'order_by'      => $this->form_order_by['data'],
        ];
        $ret = AppHomeNavService::AppHomeNavList($data_params);

        // 基础参数赋值
        MyViewAssign('params', $this->data_request);
        MyViewAssign('page_html', $page->GetPageHtml());
        MyViewAssign('data_list', $ret['data']);
        return MyView();
    }

    /**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-05T08:21:54+0800
     */
    public function Detail()
    {
        if(!empty($this->data_request['id']))
        {
            // 条件
            $where = [
                ['id', '=', intval($this->data_request['id'])],
            ];

            // 获取列表
            $data_params = [
                'm'             => 0,
                'n'             => 1,
                'where'         => $where,
            ];
            $ret = AppHomeNavService::AppHomeNavList($data_params);
            $data = (empty($ret['data']) || empty($ret['data'][0])) ? [] : $ret['data'][0];
            MyViewAssign('data', $data);
        }
        return MyView();
    }

    /**
     * [SaveInfo 添加/编辑页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 参数
        $params = $this->data_request;

        // 数据
        $data = [];
        if(!empty($params['id']))
        {
            // 获取列表
            $data_params = array(
                'm'     => 0,
                'n'     => 1,
                'where' => ['id'=>intval($params['id'])],
                'field' => '*',
            );
            $ret = AppHomeNavService::AppHomeNavList($data_params);
            $data = empty($ret['data'][0]) ? [] : $ret['data'][0];
        }

        // 静态数据
        MyViewAssign('common_platform_type', MyConst('common_platform_type'));
        MyViewAssign('common_app_event_type', MyConst('common_app_event_type'));

        // 编辑器文件存放地址
        MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue('app_nav'));

        // 数据
        unset($params['id']);
        MyViewAssign('params', $params);
        MyViewAssign('data', $data);
        return MyView();
    }

    /**
     * [Save 手机管理-首页导航添加/编辑]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function Save()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        return AppHomeNavService::AppHomeNavSave($params);
    }

    /**
     * [Delete 手机管理-首页导航删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        $params['user_type'] = 'admin';
        return AppHomeNavService::AppHomeNavDelete($params);
    }

    /**
     * [StatusUpdate 状态更新]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        return AppHomeNavService::AppHomeNavStatusUpdate($params);
    }
}
?>