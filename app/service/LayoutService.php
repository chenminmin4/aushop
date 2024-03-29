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
namespace app\service;

use think\facade\Db;
use app\layout\service\BaseLayout;

/**
 * 布局服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-22
 * @desc    description
 */
class LayoutService
{
    // 布局key
    public static $layout_key = [
        'home'  => [
            'type'  => 'layout_index_home_data',
            'name'  => '首页',
        ],
    ];

    /**
     * 布局配置保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     * @param   [array]          $params    [输入参数]
     */
    public static function LayoutConfigSave($key, $params)
    {
        // key 值是否存在
        if(!array_key_exists($key, self::$layout_key))
        {
            return DataReturn('布局key值有', -1);
        }

        // 获取配置信息
        $key_info = self::$layout_key[$key];
        $where = ['type'=>$key_info['type'], 'is_enable'=>1];
        $info = Db::name('Layout')->where($where)->find();

        // 配置信息
        $config = empty($params['config']) ? '' : BaseLayout::ConfigSaveHandle($params['config']);

        // 数据保存
        $data = [ 
            'type'      => $key_info['type'],
            'name'      => $key_info['name'],
            'config'    => $config,
        ];
        if(empty($info))
        {
            $data['add_time'] = time();
            if(Db::name('Layout')->insertGetId($data) <= 0)
            {
                return DataReturn('添加失败', -1);
            }
        } else {
            $data['upd_time'] = time();
            if(!Db::name('Layout')->where(['id'=>$info['id']])->update($data))
            {
                return DataReturn('更新失败', -1);
            }
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 布局配置获取-管理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     */
    public static function LayoutConfigAdminData($key)
    {
        if(array_key_exists($key, self::$layout_key))
        {
            $config = Db::name('Layout')->where(['type'=>self::$layout_key[$key]['type'], 'is_enable'=>1])->value('config');
            return BaseLayout::ConfigAdminHandle($config);
        }
        return '';
    }

    /**
     * 布局配置获取-展示使用
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     */
    public static function LayoutConfigData($key)
    {
        if(array_key_exists($key, self::$layout_key))
        {
            $config = Db::name('Layout')->where(['type'=>self::$layout_key[$key]['type'], 'is_enable'=>1])->value('config');
            return BaseLayout::ConfigHandle($config);
        }
        return '';
    }
}
?>