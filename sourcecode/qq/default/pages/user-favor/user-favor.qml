<scroll-view scroll-y="{{true}}" class="scroll-box" bindscrolltolower="scroll_lower" lower-threshold="30">
    <view class="list-item" qq:if="{{data_list.length > 0}}">
      <view class="goods-item oh bg-white spacing-mb" qq:for="{{data_list}}" qq:for-item="item" >
        <navigator url="/pages/goods-detail/goods-detail?goods_id={{item.goods_id}}" hover-class="none">
          <image class="goods-image fl" src="{{item.images}}" mode="aspectFill" />
          <view class="goods-base">
            <view class="goods-title multi-text">{{item.title}}</view>
            <view class="oh goods-price">
              <text class="sales-price">{{currency_symbol}}{{item.price}}</text>
              <text qq:if="{{item.original_price > 0}}" class="original-price">{{currency_symbol}}{{item.original_price}}</text>
            </view>
          </view>
        </navigator>
        <button class="submit-cancel" type="default" size="mini" bindtap="cancel_event" data-value="{{item.goods_id}}" data-index="{{index}}" hover-class="none">取消</button>
      </view>
    </view>
    <view qq:if="{{data_list.length == 0}}">
        <import src="/pages/common/nodata.qml" />
        <template is="nodata" data="{{status: data_list_loding_status}}"></template>
    </view>
    <import src="/pages/common/bottom_line.qml" />
    <template is="bottom_line" data="{{status: data_bottom_line_status}}"></template>
</scroll-view>