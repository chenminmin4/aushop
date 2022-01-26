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

use app\service\ApiService;
use app\service\PluginsService;
use app\service\ResourcesService;

/**
 * 应用调用入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Plugins extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();

        // 登录校验
        $this->IsLogin();

        // 权限校验
        $this->IsPower();
    }
    
    /**
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 参数
        $params = $this->GetClassVars();

        // 请求参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginsname',
                'error_msg'         => '应用名称有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginscontrol',
                'error_msg'         => '应用控制器有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginsaction',
                'error_msg'         => '应用操作方法有误',
            ],
        ];
        $ret = ParamsChecked($params['data_request'], $p);
        if($ret !== true)
        {
            if(IS_AJAX)
            {
                return DataReturn($ret, -5000);
            } else {
                MyViewAssign('msg', $ret);
                return MyView('public/tips_error');
            }
        }

        // 应用名称/控制器/方法
        $pluginsname = $params['data_request']['pluginsname'];
        $pluginscontrol = strtolower($params['data_request']['pluginscontrol']);
        $pluginsaction = strtolower($params['data_request']['pluginsaction']);

        // 视图初始化
        $this->PluginsViewInit($pluginsname, $pluginscontrol, $pluginsaction);

        // 编辑器文件存放地址定义
        MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue('plugins_'.$pluginsname));

        // 调用
        $ret = PluginsService::PluginsControlCall($pluginsname, $pluginscontrol, $pluginsaction, 'admin', $params);

        // ajax 返回的都是数组、使用统一api返回处理
        if(IS_AJAX)
        {
            return ApiService::ApiDataReturn(($ret['code'] == 0) ? $ret['data'] : $ret);
        }

        // 正确则返回视图内容
        if($ret['code'] == 0)
        {
            return $ret['data'];
        }

        // 是否未绑定商店账号
        if($ret['code'] == -300)
        {
            MyViewAssign('ext_html', '<p class="am-margin-top-sm"><button type="button" class="am-btn am-btn-secondary am-radius am-btn-xs am-margin-left-xs am-icon-gg store-accounts-event"> 绑定Aushop商店账户</button></p><p class="am-text-warning am-margin-top-xl">如已绑定、请到商城后台左侧菜单工具下面清除缓存再尝试访问！</p>');
        }

        // 调用失败
        MyViewAssign('msg', $ret['msg']);
        return MyView('public/tips_error');
    }

    /**
     * 获取类属性数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-07
     * @desc    description
     */
    public function GetClassVars()
    {
        $data = [];
        $vers = get_class_vars(get_class());
        foreach($vers as $k=>$v)
        {
            if(property_exists($this, $k))
            {
                $data[$k] = $this->$k;
            }
        }
        return $data;
    }

    /**
     * 视图初始化
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T22:46:29+0800
     * @param    [string]                   $plugins_name       [应用名称]
     * @param    [string]                   $plugins_control    [控制器名称]
     * @param    [string]                   $plugins_action     [方法]
     */
    public function PluginsViewInit($plugins_name, $plugins_control, $plugins_action)
    {
        // 应用名称/控制器/方法
        MyViewAssign('plugins_name', $plugins_name);
        MyViewAssign('plugins_control', $plugins_control);
        MyViewAssign('plugins_action', $plugins_action);
        
        // 当前操作名称
        $module_name = 'plugins';

        // 模块组
        $group = 'admin';

        // 控制器静态文件状态css,js
        $module_css = $module_name.DS.'css'.DS.$plugins_name.DS.$group.DS.$plugins_control;
        $module_css .= file_exists(ROOT_PATH.'static'.DS.$module_css.'.'.$plugins_action.'.css') ? '.'.$plugins_action.'.css' : '.css';
        MyViewAssign('module_css', file_exists(ROOT_PATH.'static'.DS.$module_css) ? $module_css : '');

        $module_js = $module_name.DS.'js'.DS.$plugins_name.DS.$group.DS.$plugins_control;
        $module_js .= file_exists(ROOT_PATH.'static'.DS.$module_js.'.'.$plugins_action.'.js') ? '.'.$plugins_action.'.js' : '.js';
        MyViewAssign('module_js', file_exists(ROOT_PATH.'static'.DS.$module_js) ? $module_js : '');

        // 应用公共css,js
        $plugins_css = $module_name.DS.'css'.DS.$plugins_name.DS.$group.DS.'common.css';
        MyViewAssign('plugins_css', file_exists(ROOT_PATH.'static'.DS.$plugins_css) ? $plugins_css : '');
        $plugins_js = $module_name.DS.'js'.DS.$plugins_name.DS.$group.DS.'common.js';
        MyViewAssign('plugins_js', file_exists(ROOT_PATH.'static'.DS.$plugins_js) ? $plugins_js : '');
    }
}
?>