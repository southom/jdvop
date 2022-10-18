<?php
/**
 * @version    京东VOP接口 V1.0
 * @author     Southom <Southom@gmail.com>
 * @date       2021-12-28
 */
namespace southom;

class order extends jdvop
{ 
    const GET_FREIGHT                       = 'api/order/getFreight';                    //7.1 查询运费
    const PROMISE_CALENDAR                  = 'api/order/promiseCalendar';               //7.2 查询预约日历
    const SUBMIT_ORDER                      = 'api/order/submitOrder';                   //7.3 提交订单
    const SELECT_JD_ORDER_ID_BY_THIRD_ORDER = 'api/order/selectJdOrderIdByThirdOrder';   //7.4 反查订单
    const CONFIRM_ORDER                     = 'api/order/confirmOrder';                  //7.5 确认预占库存订单
    const CANCEL                            = 'api/order/cancel';                        //7.6 取消未确认订单
    const SELECT_JD_ORDER                   = 'api/order/selectJdOrder';                 //7.7 查询订单详情
    const ORDER_TRACK                       = 'api/order/orderTrack';                    //7.8 查询配送信息
    const CONFIRM_RECEIVED                  = 'api/order/confirmReceived';               //7.9 确认收货
    const SAVE_OR_UPDATE_PO_NO              = 'api/order/saveOrUpdatePoNo';              //7.10 更新采购单号
    const UPDATE_CUSTOM_ORDER_EXT           = 'api/order/updateCustomOrderExt';          //7.11 更新订单扩展字段
    const CHECK_NEW_ORDER                   = 'api/checkOrder/checkNewOrder';            //7.12 查询新建订单列表
    const CHECK_REFUSE_ORDER                = 'api/order/checkRefuseOrder';              //7.13 查询拒收订单列表
    const CHECK_COMPLETE_ORDER              = 'api/order/checkCompleteOrder';            //7.14 查询完成订单列表
    const GET_PROMISE_TIPS                  = 'api/order/getPromiseTips';                //7.15 查询配送预计送达时间
    const BATCH_CONFIRM_RECEIVED            = 'api/order/batchConfirmReceived';          //7.16 批量确认收货接口
    const DO_PAY                            = 'api/order/doPay';                         //8.3  重新发起支付接口


    /**
     * 查询准备提交的订单的运费。
     * @param  string $sku  [{"skuId":商品编号1,"num":商品数量1},{"skuId":商品编号2,"num":商品数量2}]（最多支持100种商品）
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @param  interger $paymentType  京东支付方式
     * @param  string   $queryExts //conFreight //续重运费
     * @return json
     */
    public function getFreight(string $sku,string $province,string $city,$county='',$town='',$paymentType=4, $queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'sku'=>$sku,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town,'paymentType'=>$paymentType,'queryExts'=>$queryExts];
        $json   = parent::curlPost($this->api_url. self::GET_FREIGHT ,$params);
        return $json;
    }
    /**
     * 获取京东预约日历。
     * @param  string $sku  [{"skuId":商品编号1,"num":商品数量1},{"skuId":商品编号2,"num":商品数量2}]（最多支持100种商品）
     * @param  string $province  京东一级地址编号
     * @param  string $city  京东二级地址编号
     * @param  string $county  京东三级地址编号
     * @param  string $town  京东四级地址编号(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @param  interger $paymentType  京东支付方式
     * @param  string   $queryExts //conFreight //续重运费
     * @return json
     */
    public function promiseCalendar(string $sku,string $province,string $city,string $county,int $paymentType,$queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'skuIds'=>$skuIds,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town,'paymentType'=>$paymentType,'queryExts'=>$queryExts]; 
        $json   = parent::curlPost($this->api_url. self::PROMISE_CALENDAR ,$params);
        return $json;
    }
    /**
     * 提交订单信息，生成京东订单。
     * @param  array $param
     * @return json
     */
    public function submitOrder(array $param){

        $companyName = '';
        $regCode     = '';
        $regAddr     = '';
        $regPhone    = '';
        $regBank     = '';
        $regBankAccount = '';

        $params   = [
            'token'                => $this->token['result']['access_token'],
            'thirdOrder'           => $param['thirdOrder'],                   //第三方的订单单号，必须在100字符以内
            'sku'                  => $param['sku'],                          //Json数组类型的字符串，参数格式：[{"skuId":商品编号, "num":商品数量, "price":10,"bNeedGift":false, "yanbao":[{"skuId":延保商品编号}]}](最高支持100种商品)
            'name'                 => $param['name'],                         //收货人姓名，最多20个字符
            'province'             => $param['province'],                     //一级地址编码：收货人省份地址编码
            'city'                 => $param['city'],                         //二级地址编码：收货人省份地址编码
            'county'               => $param['county'],                       //三级地址编码：收货人县（区）级地址编码
            'town'                 => $param['town'],                         //四级地址编码：收货人乡镇地址编码(如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
            'address'              => $param['address'],                      //收货人详细地址，最多100个字符
            'mobile'               => $param['mobile'],                       //手机号，最多20个字符

            'invoiceState'         => isset($param['invoiceState'])?$param['invoiceState']:2,                 //开票方式(2为集中开票，4 订单完成后开票)
            'invoiceType'          => isset($param['invoiceType'])?$param['invoiceType']:2,                  //发票类型（2增值税专用发票；3 电子票）当发票类型为2时，开票方式只支持2集中开票
            'selectedInvoiceTitle' => isset($param['selectedInvoiceTitle'])?$param['selectedInvoiceTitle']:5,         //发票类型：4：个人，5：单位
            'companyName'          => isset($param['companyName'])?$param['companyName']:$companyName,                  //发票抬头  (如果selectedInvoiceTitle=5则此字段必须) 需regCompanyName跟此字段传递一致
            'invoiceContent'       => isset($param['invoiceContent'])?$param['invoiceContent']:1,               //1:明细，100：大类 备注:若增值税专用发票则只能选1 明细
            'paymentType'          => isset($param['paymentType'])?$param['paymentType']:4,                  //支付方式枚举值。1：货到付款 4：预存款 5：公司转账 101：京东金采 102：商城金采(一般不适用，仅限确认开通商城账期的特殊情况使用，请与业务确认后使用) 20为混合支付
            'isUseBalance'         => isset($param['isUseBalance'])?$param['isUseBalance']:1,                 //使用余额 paymentType=4 时，此值固定是1其他支付方式0
            'submitState'          => isset($param['submitState'])?$param['submitState']:0,                  //是否预占库存，0是预占库存（需要调用确认订单接口），1是不预占库存，直接进入生产
            'invoicePhone'         => isset($param['invoicePhone'])?$param['invoicePhone']:$param['mobile'],                 //收票人电话
            'regCompanyName'       => isset($param['regCompanyName'])?$param['regCompanyName']:$companyName,               //专票资质公司名称
            'regCode'              => isset($param['regCode'])?$param['regCode']:$regCode,                      //专票资质纳税人识别号

            
            //非必填项
            'regAddr'              => isset($param['regAddr'])?$param['regAddr']:$regAddr,                      //专票资质注册地址 选填
            'regPhone'             => isset($param['regPhone'])?$param['regPhone']:$regPhone,                   //专票资质注册电话 选填
            'regBank'              => isset($param['regBank'])?$param['regBank']:$regBank,                      //专票资质注册银行 选填
            'regBankAccount'       => isset($param['regBankAccount'])?$param['regBankAccount']:$regBankAccount, //专票资质银行账号 选填

            'zip'                  => isset($param['zip'])?$param['zip']:'',                          //邮编，最多20个字符
            'phone'                => isset($param['phone'])?$param['phone']:'',                        //座机号，最多20个字符
            'email'                => isset($param['email'])?$param['email']:'',                        //邮箱（无实际业务意义，可固值xx）
            'remark'               => isset($param['remark'])?$param['remark']:'',                      //备注（少于100字）

            //以下非必填参数暂时不用
            /*
            'price'                => isset($param['price'])?$param['price']:'',                        //商品价格：该字段不传值将查询京东最新的售卖价下单；传值时会校验传入价格和当前价格是否相等：如果价格不相等，下单失败。
            'payDetails'           => isset($param['payDetails'])?$param['payDetails']:'',                   //支付明细。当paymentType为20时候必须递此字段 flag为区分字段 枚举：1 为个人，2为企业 paymentType为 支付类型 枚举定义：17, "微信支付"101, "金采支付",4,“预存款”payMoney为支付金额 details单位：元  [{"payMoney":20,"paymentType":"17","flag":"1"},{"payMoney":80,"paymentType":"4","flag":"2"}]
            'invoiceName'          => isset($param['invoiceName'])?$param['invoiceName']:'',                  //增专票收票人姓名
            'invoiceProvice'       => isset($param['invoiceProvice'])?$param['invoiceProvice']:'',               //增专票收票人所在省(京东地址编码) 选填
            'invoiceCity'          => isset($param['invoiceCity'])?$param['invoiceCity']:'',                  //增专票收票人所在市(京东地址编码) 选填
            'invoiceCounty'        => isset($param['invoiceCounty'])?$param['invoiceCounty']:'',                //增专票收票人所在区/县(京东地址编码) 选填
            'invoiceAddress'       => isset($param['invoiceAddress'])?$param['invoiceAddress']:'',               //增专票收票人所在地址当 invoiceType =2时 选填
            'reservingDate'        => isset($param['reservingDate'])?$param['reservingDate']:'',                //大家电配送日期：默认值为-1，0表示当天，1表示明天，2：表示后天; 如果为-1表示不使用大家电预约日历
            'installDate'          => isset($param['installDate'])?$param['installDate']:'',                  //大家电安装日期：默认按-1处理，0表示当天，1表示明天，2：表示后天
            'needInstall'          => isset($param['needInstall'])?$param['needInstall']:'',                  //是否选择了安装，默认为true，选择了“暂缓安装”，此为必填项，必填值为false。
            'promiseDate'          => isset($param['promiseDate'])?$param['promiseDate']:'',                  //中小件配送预约日期，格式：yyyy-MM-dd
            'promiseTimeRange'     => isset($param['promiseTimeRange'])?$param['promiseTimeRange']:'',             //中小件配送预约时间段，时间段如： 9:00-15:00
            'promiseTimeRangeCode' => isset($param['promiseTimeRangeCode'])?$param['promiseTimeRangeCode']:'',         //中小件预约时间段的标记
            'reservedDateStr'      => isset($param['reservedDateStr'])?$param['reservedDateStr']:'',              //家电配送预约日期，格式：yyyy-MM-dd
            'reservedTimeRange'    => isset($param['reservedTimeRange'])?$param['reservedTimeRange']:'',            //大家电配送预约时间段，如果：9:00-15:00
            'cycleCalendar'        => isset($param['cycleCalendar'])?$param['cycleCalendar']:'',                //循环日历, 客户传入最近一周可配送的时间段,客户入参:{"3": "09:00-10:00,12:00-19:00","4": "09:00-15:00"}
            'poNo'                 => isset($param['poNo'])?$param['poNo']:'',                         //采购单号，长度范围[1-26]
            'validHolidayVocation' => isset($param['validHolidayVocation'])?$param['validHolidayVocation']:'',         //节假日不可配送，默认值为false，表示节假日可以配送，为true表示节假日不配送
            'thrPurchaserAccount'  => isset($param['thrPurchaserAccount'])?$param['thrPurchaserAccount']:'',          //第三方平台采购人账号
            'thrPurchaserName'     => isset($param['thrPurchaserName'])?$param['thrPurchaserName']:'',             //第三方平台采购人姓名
            'thrPurchaserPhone'    => isset($param['thrPurchaserPhone'])?$param['thrPurchaserPhone']:'',            //第三方采购人电话（手机号，固定电话区号+号码）
            'customOrderExt'       => isset($param['thrPurchaserPhone'])?$param['thrPurchaserPhone']:'',            //对于有订单维度扩展字段需求的用户，提交订单时可以定义扩展字段信息，key需要提前申请开通，例：{"poNum": "20210419001"，"materialCode": "ABC-123"}
            'yanbao'               => isset($param['yanbao'])?$param['yanbao']:'',                       //延保商品信息
            'customSkuExt'         => isset($param['customSkuExt'])?$param['customSkuExt']:'',                 //对于有sku维度扩展字段需求的用户，提交订单时可以定义扩展字段信息，key需要提前申请开通，例：{"poNum": "222"，"materialCode": "ABC-123"}
            */
        ];
        $json = parent::curlPost($this->api_url. self::SUBMIT_ORDER ,$params);
        return $json;
    }
    /**
     * 订单反查接口，根据第三方订单号反查京东的订单号。
     * @param  string $thirdOrder 第三方订单号（非京东订单号）
     * @return json
     */
    public function selectJdOrderIdByThirdOrder(string $thirdOrder){
        $params = ['token'=>$this->token['result']['access_token'],'thirdOrder'=>$thirdOrder];  
        $json   = parent::curlPost($this->api_url. self::SELECT_JD_ORDER_ID_BY_THIRD_ORDER ,$params);
        return $json;
    }

    /**
     * 确认预占库存订单接口。
     * @param  string $jdOrderId 京东的订单单号(下单返回的父订单号)
     * @param  string $poNo 采购单号
     * @return json
     */
    public function confirmOrder(string $jdOrderId,$poNo=''){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderId'=>$jdOrderId,'poNo'=>$poNo];  
        $json   = parent::curlPost($this->api_url. self::CONFIRM_ORDER ,$params);
        return $json;
    }
    /**
     * 取消未确认订单接口。
     * @param  string $jdOrderId 京东的订单单号(下单返回的父订单号)
     * @return json
     */
    public function cancel(string $jdOrderId){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderId'=>$jdOrderId];  
        $json  = parent::curlPost($this->api_url. self::CANCEL ,$params);
        return $json;
    }
    /**
     * 查询京东订单信息接口。
     * @param  string $jdOrderId 京东的订单单号(下单返回的父订单号)
     * @param  string $queryExts 扩展参数。支持多个状态组合查询[英文逗号间隔] orderType 订单类型 jdOrderState 京东订单状态 name 商品名称 address 收件人地址 mobile 手机号 poNo 采购单号 finishTime 订单完成时间 createOrderTime 订单创建时间 paymentType 订单支付类型 outTime 订单出库时间  invoiceType 订单发票类型
     * @return json
     */
    public function selectJdOrder(string $jdOrderId,$queryExts=''){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderId'=>$jdOrderId,'queryExts'=>$queryExts]; 
        $json   = parent::curlPost($this->api_url. self::SELECT_JD_ORDER ,$params);
        return $json;
    }
    /**
     * 查询配送信息。
     * @param  string $jdOrderId 京东的订单单号(下单返回的父订单号)
     * @param  string $waybillCode 是否返回订单的配送信息。0不返回配送信息。1，返回配送信息。只支持最近2个月的配送信息查询。
     * @return json
     */
    public function orderTrack(string $jdOrderId,$waybillCode=1){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderId'=>$jdOrderId,'waybillCode'=>$waybillCode];
        $json   = parent::curlPost($this->api_url. self::ORDER_TRACK ,$params);
        return $json;
    }
    /**
     * 确认收货。仅适用于厂商直送订单。厂商直送订单可使用此接口确认收货并将订单置为完成状态。
     * @param  string $jdOrderId 京东的订单单号(下单返回的父订单号)
     * @return json
     */
    public function confirmReceived(string $jdOrderId){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderId'=>$jdOrderId]; 
        $json   = parent::curlPost($this->api_url. self::CONFIRM_RECEIVED ,$params);
        return $json;
    }
    /**
     * 更新订单上的PO单号，可选择用于配送单、发票等票面展示。
     * @param  string $jdOrderId 京东的订单单号(下单返回的父订单号)
     * @param  string $poNo 采购单号，长度范围[1-26]
     * @return json
     */
    public function saveOrUpdatePoNo(string $jdOrderId,string $poNo){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderId'=>$jdOrderId,'poNo'=>$poNo]; 
        $json  = parent::curlPost($this->api_url. self::SAVE_OR_UPDATE_PO_NO ,$params);
        return $json;
    }
    /**
     * 更新订单上的PO单号，可选择用于配送单、发票等票面展示。
     * @param  string $jdOrderId 京东的订单单号(下单返回的父订单号),拆单需要传子单号
     * @param  string $customOrderExt 对于有sku维度扩展字段需求的用户，提交订单后可以更新扩展字段信息，key需要提前申请开通，示例：{"key1": "value1"，"key2": "value2"}
     * @param  string $customSkuExt 对于有sku维度扩展字段需求的用户，提交订单后可以更新扩展字段信息，key需要提前申请开通，示例：{"skuId001":{"key1":"value1","key2":"value2"},"skuId002":{"key1":"value3","key2":"value4"}}
     * @return json
     */
    public function updateCustomOrderExt(string $jdOrderId,string $customOrderExt,string $customSkuExt){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderId'=>$jdOrderId,'customOrderExt'=>$customOrderExt,'customSkuExt'=>$customSkuExt]; 
        $json  = parent::curlPost($this->api_url. self::UPDATE_CUSTOM_ORDER_EXT ,$params);
        return $json;
    }
    /**
     * 查询所有新建的订单列表。可用于核对订单。
     * @param  string $date 查询日期，格式2018-11-7（不包含当天）
     * @param  interger $pageNo 页码，默认1
     * @param  interger $pageSize 页大小取值范围[1,100]，默认20
     * @param  Long $jdOrderIdIndex 最小订单号索引游标，为解决大于1W条订单无法查询问题。注意事项：该字段和pageNo互斥，订单数小于1W可以用pageNo分页的方式来查询，订单数大于1W则需要使用索引游标的方式来读取数据。使用方式：第一次查询无需传入该字段，返回订单信息后（第一次记录订单总条数）；第二次查询将第一次查询结果中最小的订单号传入，查询返回结果中不包含传入的订单号；递归这个流程，直到接口返回无数据为止，订单查询完毕，核对本地订单数和第一次接口返的订单数目是否一致。如果使用本字段：订单号必须大于1
     * @param  string $endDate  结束日期，格式2018-11-7。主要用于查询时间段内，跟date配合使用。
     * @return json
     */
    public function checkNewOrder(string $date,$pageNo=1,$pageSize=20,$jdOrderIdIndex='',$endDate=''){
        $params = ['token'=>$this->token['result']['access_token'],'date'=>$date,'endDate'=>$endDate,'pageNo'=>$pageNo,'pageSize'=>$pageSize,'jdOrderIdIndex'=>$jdOrderIdIndex]; 
        $json   = parent::curlPost($this->api_url. self::CHECK_NEW_ORDER ,$params);
        return $json;
    }

    /**
     * 查询所有拒收的订单列表。可用于核对订单。
     * @param  string $date 查询日期，格式2018-11-7（不包含当天）
     * @param  interger $pageNo 页码，默认1
     * @param  interger $pageSize 页大小取值范围[1,100]，默认20
     * @param  Long $jdOrderIdIndex 最小订单号索引游标，为解决大于1W条订单无法查询问题。注意事项：该字段和pageNo互斥，订单数小于1W可以用pageNo分页的方式来查询，订单数大于1W则需要使用索引游标的方式来读取数据。使用方式：第一次查询无需传入该字段，返回订单信息后（第一次记录订单总条数）；第二次查询将第一次查询结果中最小的订单号传入，查询返回结果中不包含传入的订单号；递归这个流程，直到接口返回无数据为止，订单查询完毕，核对本地订单数和第一次接口返的订单数目是否一致。如果使用本字段：订单号必须大于1
     * @param  string $endDate  结束日期，格式2018-11-7。主要用于查询时间段内，跟date配合使用。
     * @return json
     */
    public function checkRefuseOrder(string $date, $pageNo=1, $pageSize=20, $jdOrderIdIndex=0, $endDate=''){
        $params = ['token'=>$this->token['result']['access_token'],'date'=>$date,'endDate'=>$endDate,'pageNo'=>$pageNo,'pageSize'=>$pageSize,'jdOrderIdIndex'=>$jdOrderIdIndex]; 
        $json   = parent::curlPost($this->api_url. self::CHECK_REFUSE_ORDER ,$params);
        return $json;
    }
    /**
     * 查询所有完成的订单列表。可用于核对订单。
     * @param  string $date 京东的订单单号(下单返回的父订单号),拆单需要传子单号
     * @param  interger $pageNo 页码，默认1
     * @param  interger $pageSize 页大小取值范围[1,100]，默认20
     * @param  Long $jdOrderIdIndex 最小订单号索引游标，为解决大于1W条订单无法查询问题。注意事项：该字段和pageNo互斥，订单数小于1W可以用pageNo分页的方式来查询，订单数大于1W则需要使用索引游标的方式来读取数据。使用方式：第一次查询无需传入该字段，返回订单信息后（第一次记录订单总条数）；第二次查询将第一次查询结果中最小的订单号传入，查询返回结果中不包含传入的订单号；递归这个流程，直到接口返回无数据为止，订单查询完毕，核对本地订单数和第一次接口返的订单数目是否一致。如果使用本字段：订单号必须大于1
     * @param  string $endDate  结束日期，格式2018-11-7。主要用于查询时间段内，跟date配合使用。
     * @return json
     */
    public function checkCompleteOrder(string $date, $pageNo=1, $pageSize=20, $jdOrderIdIndex='', $endDate=''){
        $params = ['token'=>$this->token['result']['access_token'],'date'=>$date,'endDate'=>$endDate,'pageNo'=>$pageNo,'pageSize'=>$pageSize,'jdOrderIdIndex'=>$jdOrderIdIndex];  
        $json   = parent::curlPost($this->api_url. self::CHECK_COMPLETE_ORDER ,$params);
        return $json;
    }
    /**
     * 查询商品配送预计送达时间，需结合商品实际情况。
     * @param  string $skuId 商品编号
     * @param  interger $num 数量
     * @param  interger $province 一级地址
     * @param  interger $city 二级地址
     * @param  interger $county 三级地址
     * @param  interger $town 四级地址  (如果该地区有四级地址，则必须传递四级地址，没有四级地址则传0)
     * @return json
     */
    public function getPromiseTips(string $skuId,int $num,int $province,int $city,int $county,int $town){
        $params = ['token'=>$this->token['result']['access_token'],'skuId'=>$skuId,'num'=>$num,'province'=>$province,'city'=>$city,'county'=>$county,'town'=>$town]; 
        $json   = parent::curlPost($this->api_url. self::GET_PROMISE_TIPS ,$params);
        return $json;
    }
    /**
     * 仅适用于厂商直送订单。厂商直送订单可使用此接口批量确认收货并将订单置为完成状态。
     * @param  string $jdOrderIds 京东子单号，请以，(英文逗号)分割。例如：129408,129409 (最高支持50个订单)
     * @return json
     */
    public function batchConfirmReceived(string $jdOrderIds){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderIds'=>$jdOrderIds]; 
        $json   = parent::curlPost($this->api_url. self::BATCH_CONFIRM_RECEIVED ,$params);
        return $json;
    }
    /**
     * 下单成功支付失败的情况，可以调用此接口重新支付（此接口在特殊场景使用，正常无需调用）
     * @param  string $jdOrderId 京东系统订单号
     * @return json
     */
    public function doPay(string $jdOrderId){
        $params = ['token'=>$this->token['result']['access_token'],'jdOrderIds'=>$jdOrderId]; 
        $json   = parent::curlPost($this->api_url. self::DO_PAY ,$params);
        return $json;
    }

}