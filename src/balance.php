<?php
/**
 * @version    京东VOP接口 V1.0
 * @author     Southom <Southom@gmail.com>
 * @date       2021-12-28
 */
namespace southom;

class balance extends jdvop
{ 

    const GET_UNION_BALANCE  = 'api/price/getUnionBalance';  //8.1 查询余额
    const GET_BALANCE_DETAIL = 'api/price/getBalanceDetail'; //8.2 查询余额变动明细
    
    /**
     * 查询金采和预存款余额的余额。
     * @param  string $type 账户余额类型，多选，可用英文逗号拼接。1：账户余额。2：金采余额。
     * @return json
     */
    public function getUnionBalance($type='1,2'){
        $params = ['token'=>$this->token['result']['access_token'],'pin'=>$this->account['username'],'type'=>$type]; 
        $json   = parent::curlPost($this->api_url. self::GET_UNION_BALANCE ,$params);
        return $json;
    }

    /**
     * 仅支持预存款余额明细查询，不支持金采余额明细查询。
     * @param  interger $pageNum 分页查询当前页数，默认为1
     * @param  interger $pageSize 每页记录数，默认为20
     * @param  string $orderId 订单号或流水单
     * @param  string $startDate 开始日期，格式必须：yyyyMMdd
     * @param  string $endDate 截止日期，格式必须：yyyyMMdd
     * @return json
     */
    public function getBalanceDetail($pageNum=1,$pageSize=200,$orderId='',$startDate='',$endDate=''){
        $params = ['token'=>$this->token['result']['access_token'],'pageNum'=>$pageNum,'pageSize'=>$pageSize];
        if($orderId){
            $params['orderId'] = $orderId;
        }
        if($startDate){
            $params['startDate'] = $startDate;
        }
        if($endDate){
            $params['endDate'] = $endDate;
        }
        $json   = parent::curlPost($this->api_url. self::GET_BALANCE_DETAIL ,$params);
        return $json;
    }

    

}