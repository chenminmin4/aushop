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
namespace app\index\form;

use think\facade\Db;
use app\service\PaymentService;
use app\service\ExpressService;

/**
 * 订单动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-08
 * @desc    description
 */
class Order
{
    // 基础条件
    public $condition_base = [
        ['is_delete_time', '=', 0],
        ['user_is_delete_time', '=', 0],
    ];

    /**
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-29
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        // 用户信息
        if(!empty($params['system_user']))
        {
            $this->condition_base[] = ['user_id', '=', $params['system_user']['id']];
        }
    }

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        // 基础配置
        $base = [
            'key_field'     => 'id',
            'is_search'     => 1,
            'search_url'    => MyUrl('index/order/index'),
            'detail_title'  => '基础信息',
            'is_middle'     => 0,
        ];

        // 表单配置
        $form = [
            [
                'label'         => '基础信息',
                'view_type'     => 'module',
                'view_key'      => 'order/module/goods',
                'grid_size'     => 'xl',
                'search_config' => [
                    'form_type'             => 'input',
                    'form_name'             => 'id',
                    'where_type'            => 'like',
                    'where_type_custom'     => 'in',
                    'where_value_custom'    => 'WhereBaseGoodsInfo',
                    'placeholder'           => '请输入订单号/商品名称/型号',
                ],
            ],
            [
                'label'         => '订单状态',
                'view_type'     => 'field',
                'view_key'      => 'status',
                'view_data_key' => 'name',
                'view_data'     => MyConst('common_order_status'),
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_order_status'),
                    'data_key'          => 'id',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => '支付状态',
                'view_type'     => 'module',
                'view_key'      => 'order/module/pay_status',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'form_name'         => 'pay_status',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_order_pay_status'),
                    'data_key'          => 'id',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => '总价(元)',
                'view_type'     => 'field',
                'view_key'      => 'total_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => '支付金额(元)',
                'view_type'     => 'field',
                'view_key'      => 'pay_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => '单价(元)',
                'view_type'     => 'field',
                'view_key'      => 'price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => '订单模式',
                'view_type'     => 'field',
                'view_key'      => 'order_model',
                'view_data_key' => 'name',
                'view_data'     => MyConst('common_site_type_list'),
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_site_type_list'),
                    'data_key'          => 'value',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => '下单平台',
                'view_type'     => 'field',
                'view_key'      => 'client_type',
                'view_data_key' => 'name',
                'view_data'     => MyConst('common_platform_type'),
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_platform_type'),
                    'data_key'          => 'value',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => '地址信息',
                'view_type'     => 'module',
                'view_key'      => 'order/module/address',
                'grid_size'     => 'sm',
                'search_config' => [
                    'form_type'             => 'input',
                    'form_name'             => 'id',
                    'where_type'            => 'like',
                    'where_type_custom'     => 'in',
                    'where_value_custom'    => 'WhereValueAddressInfo',
                ],
            ],
            [
                'label'         => '取货信息',
                'view_type'     => 'module',
                'view_key'      => 'order/module/take',
                'width'         => 125,
                'search_config' => [
                    'form_type'             => 'input',
                    'form_name'             => 'id',
                    'where_type'            => 'like',
                    'where_type_custom'     => 'in',
                    'where_value_custom'    => 'WhereValueTakeInfo',
                ],
            ],
            [
                'label'         => '退款金额(元)',
                'view_type'     => 'field',
                'view_key'      => 'refund_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => '退货数量',
                'view_type'     => 'field',
                'view_key'      => 'returned_quantity',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                ],
            ],
            [
                'label'         => '购买总数',
                'view_type'     => 'field',
                'view_key'      => 'buy_number_count',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                ],
            ],
            [
                'label'         => '增加金额(元)',
                'view_type'     => 'field',
                'view_key'      => 'increase_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => '优惠金额(元)',
                'view_type'     => 'field',
                'view_key'      => 'preferential_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => '支付方式',
                'view_type'     => 'field',
                'view_key'      => 'payment_name',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'form_name'         => 'payment_id',
                    'where_type'        => 'in',
                    'data'              => PaymentService::PaymentList(),
                    'data_key'          => 'id',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => '留言信息',
                'view_type'     => 'field',
                'view_key'      => 'user_note',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'where_type'        => 'like',
                ],
            ],
            [
                'label'         => '扩展信息',
                'view_type'     => 'module',
                'view_key'      => 'order/module/extension',
                'grid_size'     => 'sm',
                'search_config' => [
                    'form_type'         => 'input',
                    'form_name'         => 'extension_data',
                    'where_type'        => 'like',
                ],
            ],
            [
                'label'         => '快递公司',
                'view_type'     => 'field',
                'view_key'      => 'express_name',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'form_name'         => 'express_id',
                    'data'              => ExpressService::ExpressList(),
                    'where_type'        => 'in',
                    'data_key'          => 'id',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => '快递单号',
                'view_type'     => 'field',
                'view_key'      => 'express_number',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'input',
                    'where_type'        => 'like',
                ],
            ],
            [
                'label'         => '是否评论',
                'view_type'     => 'module',
                'view_key'      => 'order/module/is_comments',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'             => 'select',
                    'where_type'            => 'in',
                    'form_name'             => 'user_is_comments',
                    'data'                  => MyConst('common_is_text_list'),
                    'data_key'              => 'id',
                    'data_name'             => 'name',
                    'where_type_custom'     => 'WhereTypyUserIsComments',
                    'where_value_custom'    => 'WhereValueUserIsComments',
                    'is_multiple'           => 1,
                ],
            ],
            [
                'label'         => '确认时间',
                'view_type'     => 'field',
                'view_key'      => 'confirm_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '支付时间',
                'view_type'     => 'field',
                'view_key'      => 'pay_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '发货时间',
                'view_type'     => 'field',
                'view_key'      => 'delivery_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '完成时间',
                'view_type'     => 'field',
                'view_key'      => 'collect_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '取消时间',
                'view_type'     => 'field',
                'view_key'      => 'cancel_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '关闭时间',
                'view_type'     => 'field',
                'view_key'      => 'close_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '创建时间',
                'view_type'     => 'field',
                'view_key'      => 'add_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '更新时间',
                'view_type'     => 'field',
                'view_key'      => 'upd_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => '操作',
                'view_type'     => 'operate',
                'view_key'      => 'order/module/operate',
                'align'         => 'center',
                'fixed'         => 'right',
            ],
        ];

        // 是否启用订单批量支付
        if(MyC('home_is_enable_order_bulk_pay') == 1)
        {
            array_unshift($form, [
                'view_type'         => 'checkbox',
                'is_checked'        => 0,
                'checked_text'      => '反选',
                'not_checked_text'  => '全选',
                'not_show_key'      => 'status',
                'not_show_data'     => [0,2,3,4,5,6],
                'align'             => 'center',
                'width'             => 80,
                'view_key'          => 'order_form_checkbox_value',
            ]);
        }

        return [
            'base'  => $base,
            'form'  => $form,
        ];
    }

    /**
     * 评论条件符号处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $form_key     [表单数据key]
     * @param   [array]           $params       [输入参数]
     */
    public function WhereTypyUserIsComments($form_key, $params = [])
    {
        if(isset($params[$form_key]))
        {
            // 条件值是 0,1
            // 解析成数组，都存在则返回null，则1 >， 0 =
            $value = explode(',', urldecode($params[$form_key]));
            if(count($value) == 1)
            {
                return in_array(1, $value) ? '>' : '=';
            }
        }
        return null;
    }

    /**
     * 评论条件值处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $form_key     [表单数据key]
     * @param   [array]           $params       [输入参数]
     */
    public function WhereValueUserIsComments($value, $params = [])
    {
        return (count($value) == 2) ? null : 0;
    }

    /**
     * 取货码条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueTakeInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单 id
            $ids = Db::name('OrderExtractionCode')->where('code', '=', $value)->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 收件地址条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueAddressInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单 id
            $ids = Db::name('OrderAddress')->where('name|tel|address', 'like', '%'.$value.'%')->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 基础条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereBaseGoodsInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 订单号
            $ids = Db::name('Order')->where(['order_no'=>$value])->column('id');

            // 获取订单详情搜索的订单 id
            if(empty($ids))
            {
                $ids = Db::name('OrderDetail')->where('title|model', 'like', '%'.$value.'%')->column('order_id');
            }

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>