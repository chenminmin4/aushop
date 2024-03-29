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

use app\service\PaymentService;
use app\service\StoreService;
use app\service\ResourcesService;

/**
 * 支付方式管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Payment extends Common
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
     * [Index 支付方式列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
        // 插件列表
        $ret = PaymentService::PlugPaymentList();
        MyViewAssign('data_list', $ret['data']);

        // 不能删除的支付方式
        MyViewAssign('cannot_deleted_list', PaymentService::$cannot_deleted_list);

        // 适用平台
        MyViewAssign('common_platform_type', MyConst('common_platform_type'));

        // 应用商店
        MyViewAssign('store_payment_url', StoreService::StorePaymentUrl());

        // 插件更新信息
        $upgrade = PaymentService::PaymentUpgradeInfo($ret['data']);
        MyViewAssign('upgrade_info', $upgrade['data']);

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

        // 商品信息
        if(!empty($params['id']))
        {
            $data_params = [
                'where'             => ['id'=>$params['id']],
                'm'                 => 0,
                'n'                 => 1,
            ];
            $data = PaymentService::PaymentList($data_params);
            if(empty($data[0]))
            {
                return $this->error('没有相关支付方式', MyUrl('admin/payment/index'));
            }
            MyViewAssign('data', $data[0]);
        }

        // 适用平台
        MyViewAssign('common_platform_type', MyConst('common_platform_type'));

        // 参数
        MyViewAssign('params', $params);

        // 编辑器文件存放地址
        MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue('payment'));

        return MyView();
    }

	/**
	 * [Save 支付方式保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function Save()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始操作
        return PaymentService::PaymentUpdate($this->data_request);
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
            $this->error('非法访问');
        }

        // 开始操作
        return PaymentService::PaymentStatusUpdate($this->data_request);
    }

    /**
     * 安装
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Install()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始操作
        return PaymentService::Install($this->data_request);
    }

    /**
     * 卸载
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Uninstall()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始操作
        return PaymentService::Uninstall($this->data_request);
    }

    /**
     * 删除插件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始操作
        return PaymentService::Delete($this->data_request);
    }

    /**
     * 上传插件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Upload()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始操作
        return PaymentService::Upload($this->data_request);
    }
}
?>