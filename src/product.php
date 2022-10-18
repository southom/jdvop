<?php
/**
 * @version    京东VOP接口 V1.0
 * @author     Southom <Southom@gmail.com>
 * @date       2021-12-28
 */
namespace southom;

class product extends jdvop
{ 

    const GET_PAGE_NUM        = 'api/product/getPageNum';       //4.1 查询商品池编号
    const GET_SKU_BY_PAGE     = 'api/product/getSkuByPage';     //4.2 查询池内商品编号
    const GET_DETAIL          = 'api/product/getDetail';        //4.3 查询商品详情
    const GET_DETAIL_STYLE    = 'api/product/getDetailStyle';   //    查询商品详情样式
    const SKU_IMAGE           = 'api/product/skuImage';         //4.4 查询商品图片
    const SKU_STATE           = 'api/product/skuState';         //4.5 查询商品上下架状态
    const CHECK               = 'api/product/check';            //4.6 验证商品可售性   
    const CHECK_AREA_LIMIT    = 'api/product/checkAreaLimit';   //4.7 查询商品区域购买限制  
    const GET_SKU_GIFT        = 'api/product/getSkuGift';       //4.8 查询赠品信息
    const GET_YANBAO_SKU      = 'api/product/getYanbaoSku';     //4.9 查询商品延保
    const GET_IS_COD          = 'api/product/getIsCod';         //4.10 验证货到付款
    const GET_BATCH_IS_COD    = 'api/product/getBatchIsCod';    //4.11 批量验证货到付款
    const SEARCH              = 'api/search/search';            //4.12 搜索商品
    const GET_SIMILAR_SKU     = 'api/product/getSimilarSku';    //4.13 查询同类商品
    const GET_CATEGORY        = 'api/product/getCategory';      //4.14 查询分类信息
    const TOTAL_CHECK_NEW     = 'api/product/totalCheckNew';    //4.15 商品可采校验接口
    const GET_SELL_PRICE      = 'api/price/getSellPrice';       //5.1  查询商品售卖价
    const GET_NEW_STOCK_BY_ID = 'api/stock/getNewStockById';    //6.1.1 查询商品库存
    
    /**
     * 查询所有商品池编号，商品池编号将用于获取池内商品编号
     * @return json
     */
    public function getPageNum(){

        $params = ['token'=>$this->token['result']['access_token']]; 
        $json   = parent::curlPost($this->api_url. self::GET_PAGE_NUM ,$params);
        return $json;
    }

    /**
     * 查询单个商品池下的商品列表
     * @param  string $pageNum 商品池编码
     * @param  interger $pageNo 页码，默认取第一页
     * @return json
     */
    public function getSkuByPage(string $pageNum,$pageNo=1){

        $params = ['token'=>$this->token['result']['access_token'],'pageNum'=>$pageNum,'pageNo'=>$pageNo]; 
        $json   = parent::curlPost($this->api_url. self::GET_SKU_BY_PAGE ,$params);
        return $json;
    }

    /**
     * 查询单个商品的详细信息，不校验是否存在于商品池中。
     * @param  string $sku 商品编号，只支持单个查询
     * @param  string $queryExts nappintroduction //移动端商品详情大字段;nintroduction //PC端商品详情大字段；wxintroduction //微信小程序商品详情大字段，仅提供图片地址，需要客户添加显示逻辑；
     *         contractSkuExt  //获取客户侧分类编号，需要京东运营维护京东SKU与客户分类编号的映射
     * @return json
     */
    public function getDetail(string $sku,$queryExts='wxintroduction'){

        $params = ['token'=>$this->token['result']['access_token'],'sku'=>$sku,'queryExts'=>$queryExts];
        $json   = parent::curlPost($this->api_url. self::GET_DETAIL ,$params);
        return $json;
    }
    /**
     * 根据（商详接口）查询商品大字段接口是否包含 * 'div skudesign='100011'''/div' * 判断，有的话需调用此接口
     * @param  string $sku 商品编号，只支持单个查询
     * @param  string $queryExts pcStyleContent：PC端CSS样式原始文本;appStyleContent：APP端CSS样式原始文本;pcStyleUrl：PC端CSS样式URL，默认返回该样式
     *         introduction中包含<div skudesign="100010">就查PC端样式;introduction中包含<div skudesign="100011">就查APP端样式
     * @return json
     */
    public function getDetailStyle(string $sku,$queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'sku'=>$sku,'queryExts'=>$queryExts]; 
        $json   = parent::curlPost($this->api_url. self::GET_DETAIL_STYLE ,$params);
        return $json;
    }
    /**
     * 查询单个商品的主图、轮播图。
     * @param  string $sku 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @return json
     */
    public function skuImage(string $sku){
        $params = ['token'=>$this->token['result']['access_token'],'sku'=>$sku]; 
        $json   = parent::curlPost($this->api_url. self::SKU_IMAGE ,$params);
        return $json;
    }
    /**
     * 查询商品的在主站商城上下架状态，此接口不校验是否存在于商品池中。
     * @param  string $sku 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @return json
     */
    public function skuState(string $sku){
        $params = ['token'=>$this->token['result']['access_token'],'sku'=>$sku]; 
        $json   = parent::curlPost($this->api_url. self::SKU_STATE ,$params);
        return $json;
    }
    /**
     * 查询商品可售性、是否支持专票等影响销售的重要属性
     * @param  string $skuIds 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @param  string $queryExts  扩展参数：英文逗号间隔输入。noReasonToReturn //无理由退货类型;thwa //无理由退货文案类型;isSelf // 是否自营;isJDLogistics // 是否京东配送;taxInfo //商品税率
     * @return json
     */
    public function check(string $skuIds,$queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'queryExts'=>$queryExts]; 
        $json   = parent::curlPost($this->api_url. self::CHECK ,$params);
        return $json;
    }
    /**
     * 查询商品的在主站商城上下架状态，此接口不校验是否存在于商品池中。
     * @param  string $skuIds 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号,京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @param  bool   $isAreaRestrict  true 或空值代表区域受限 false 区域不受限
     * @return json
     */
    public function checkAreaLimit(string $skuIds,string $province,string $city,string $county,$town=0,$isAreaRestrict=true){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'province'=>$province,'city'=>$city,'county'=>$county,$town=>intval($town),'isAreaRestrict'=>$isAreaRestrict]; 
        $json   = parent::curlPost($this->api_url. self::CHECK_AREA_LIMIT ,$params);
        return $json;
    } 
    /**
     * 根据此接口查询主商品附带的赠品或者附件。
     * @param  string $skuIds 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @return json
     */
    public function getSkuGift(string $skuIds,string $province,string $city,string $county,string $town){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town];
        $json   = parent::curlPost($this->api_url. self::GET_SKU_GIFT ,$params);
        return $json;
    } 
    /**
     * 根据此接口查询可随主商品一并购买的延保等服务商品。
     * @param  string $skuIds 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @return json
     */
    public function getYanbaoSku(string $skuIds,string $province,string $city,string $county,string $town){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town];
        $json   = parent::curlPost($this->api_url. self::GET_YANBAO_SKU ,$params);
        return $json;
    }
    /**
     * 根据此接口查询可随主商品一并购买的延保等服务商品。
     * @param  string $skuIds 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @param  string $queryExts  skuIds //返回具体的skuId明细，例102194,13781
     * @return json
     */
    public function getIsCod(string $skuIds,string $province,string $city,string $county,$queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town,'queryExts'=>$queryExts];
        $json   = parent::curlPost($this->api_url. self::GET_IS_COD ,$params);
        return $json;
    }
    /**
     * 批量验证商品在指定区域是否可使用货到付款。
     * @param  string $skuIds 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @param  string $queryExts  skuIds //返回具体的skuId明细，例102194,13781
     * @return json
     */
    public function getBatchIsCod(string $skuIds,string $province,string $city,string $county,$queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town,'queryExts'=>$queryExts];
        $json   = parent::curlPost($this->api_url. self::GET_BATCH_IS_COD ,$params);
        return $json;
    }
    
    /**
     * 根据搜索条件查询符合要求的商品列表。
     * @param  string $Keyword 搜索关键字，需要编码
     * @param  string $catId 分类Id,只支持三级类目Id
     * @param  interger $pageIndex 当前第几页
     * @param  interger $pageSize  当前页显示
     * @param  string $Min  价格区间搜索，低价
     * @param  string $Max  价格区间搜索，高价
     * @param  string $Brands  品牌搜索 多个品牌以逗号分隔，需要编码
     * @param  string $cid1  一级分类
     * @param  string $cid2  二级分类
     * @param  string $sortType  销量降序="sale_desc";价格升序="price_asc";价格降序="price_desc";上架时间降序="winsdate_desc";按销量排序_15天销售额="sort_totalsales15_desc"; 按15日销量排序="sort_days_15_qtty_desc"; 按30日销量排序="sort_days_30_qtty_desc"; 按15日销售额排序="sort_days_15_gmv_desc"; 按30日销售额排序="sort_days_30_gmv_desc";
     * @return json
     */
    public function search($Keyword='',$catId='',$pageIndex='',$pageSize='',$Min='',$Max='',$Brands='',$cid1='',$cid2='',$sortType=''){

        $params = [
            'token'     => $this->token['result']['access_token'],
            'Keyword'   => $Keyword,
            'catId'     => $catId,
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
            'Min'       => $Min,
            'Max'       => $Max,
            'Brands'    => $Brands,
            'cid1'      => $cid1,
            'cid2'      => $cid2,
            'sortType'  => $sortType
        ]; 
        $json = parent::curlPost($this->api_url. self::SEARCH ,$params);
        return $json;
    }

    /**
     * 查询被指定为同一类的商品，如同一款式不同颜色的商品，需要注意符合此条件的商品并不一定被指定为同类商品。
     * @param  string $skuId 商品编号
     * @return json
     */
    public function getSimilarSku(string $skuId){
        $params = ['token'=>$this->token['result']['access_token'],'skuId'=>$skuId];
        $json   = parent::curlPost($this->api_url. self::GET_SIMILAR_SKU ,$params);
        return $json;
    }

    /**
     * 根据分类id查询对应分类信息。
     * @param  string $cid 分类id（可通过商品详情接口查询）
     * @return json
     */
    public function getCategory(string $cid){
        $params = ['token'=>$this->token['result']['access_token'],'cid'=>$cid];
        $json   = parent::curlPost($this->api_url. self::GET_CATEGORY ,$params);
        return $json;
    }

    /**
     * 客户商品详情页面、加入购物车以及下单的时候，校验商品是不是可采购：包括是否在商品池、是否主站上架状态、是否预约预售、是否合同支持购买此商品、是否区域限售。所以整合商品可售验证+商品上下架接口+商品区域销售限制的校验，上述校验全都通过才会告诉客户商品可采购。本接口仅用时实时校验，不建议客户将可采状态存库。
     * @param  string $skuIds 商品编号，支持批量，以“,”（半角）分隔  (最高支持100个商品)
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @param  string $queryExts  skuIds //返回具体的skuId明细，例102194,13781
     * @return json
     */
    public function totalCheckNew(string $skuIds,string $province,string $city,string $county,$queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town,'queryExts'=>$queryExts];
        $json   = parent::curlPost($this->api_url. self::TOTAL_CHECK_NEW ,$params);
        return $json;
    }


    /**
     * 批量查询商品售卖价。查询在客户商品池中的商品价格。
     * @param  string $sku 商品编号，请以，(英文逗号)分割。例如：129408,129409(最高支持100个商品)
     * @param  string $skuInfos  [{"skuId":1234,"num":1},{"skuId":5678,"num":1}]，不传时取sku对应的商品编号，数量默认为1。此字段有值则不校验sku字段
     * @param  string $queryExts  为英文半角分隔的多个枚举值，枚举值不同，本接口的出参不同。枚举值如下：price //大客户默认价格(根据合同类型查询价格)。nakedPrice//未税价。
     * @return json
     */
    public function getSellPrice(string $sku,$skuInfos='',$queryExts='price'){
        $params = ['token'=>$this->token['result']['access_token'],'sku'=>$sku,'skuInfos'=>$skuInfos,'queryExts'=>$queryExts];
        $json   = parent::curlPost($this->api_url. self::GET_SELL_PRICE ,$params);
        return $json;
    }
    /**
     * 批量获取库存接口。批量查询在客户指定区域的库存信息，最多返回数量50，超过100统一返回有货。实际库存为50--100，但用户查询数量大于真实库存数量时显示“无货”，小于等于真实库存数量时显示“有货”。
     * @param  array  $skuNums 商品和数量  [{skuId: 569172,num:101}]。“{skuId: 569172,num:10}”为1条记录，此参数最多传入100条记录。
     * @param  string $area 格式：13_1000_4277_0 (分别代表1、2、3、4级地址)
     * @return json
     */
    public function getNewStockById(string $skuNums,string $area){
        $params = ['token'=>$this->token['result']['access_token'],'skuNums'=>$skuNums,'area'=>$area];
        $json   = parent::curlPost($this->api_url. self::GET_NEW_STOCK_BY_ID ,$params);
        return $json;
    }
}
