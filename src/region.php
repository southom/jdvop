<?php
/**
 * @version    京东VOP接口 V1.0
 * @author     Southom <Southom@gmail.com>
 * @date       2021-12-28
 */
namespace southom;

class region extends jdvop
{ 

    const GET_PROVINCE   = 'api/area/getProvince'; //3.1 查询一级地址
    const GET_CITY       = 'api/area/getCity';     //3.2 查询二级地址
    const GET_COUNTY     = 'api/area/getCounty';   //3.3 查询三级地址
    const GET_TOWN       = 'api/area/getTown';     //3.4 查询四级地址
    const GET_JD_ADDRESS = 'api/area/getJDAddressFromAddress'; //3.6 地址详情转换京东地址编码


    
    /**
     * 获取一级地址
     * @return json
     */
    public function getProvince(){
        $params = ['token'=>$this->token['result']['access_token']]; 
        $json   = parent::curlPost($this->api_url. self::GET_PROVINCE ,$params);
        return $json;
    }

    /**
     * 获取二级地址
     * @param  string $id 上级id
     * @return json
     */
    public function getCity(string $id){
        $params = ['token'=>$this->token['result']['access_token'],'id'=>$id]; 
        $json   = parent::curlPost($this->api_url. self::GET_CITY ,$params);
        return $json;
    }
    

    /**
     * 获取三级地址
     * @param string $id 上级id
     * @return json
     */
    public function getCounty(string $id){
        $params = ['token'=>$this->token['result']['access_token'],'id'=>$id]; 
        $json   = parent::curlPost($this->api_url. self::GET_COUNTY ,$params);
        return $json;
    }

    /**
     * 获取四级地址
     * @param  string $id 上级id
     * @return json
     */
    public function getTown(string $id){
        $params = ['token'=>$this->token['result']['access_token'],'id'=>$id];
        $json   =parent::curlPost($this->api_url. self::GET_TOWN ,$params);
        return $json;
    }

    /**
     * 根据地址详情转换为京东地址编码。该接口不能保证所有地址都匹配到京东地址，也不能保证所有匹配到的京东地址都正确。因而，优先推荐使用逐级选择的方法。
     * @param  string $address 详细地址
     * @return json
     */
    public function getJdAddress(string $address){
        $params = ['token'=>$this->token['result']['access_token'],'address'=>$address];
        $json   =parent::curlPost($this->api_url. self::GET_JD_ADDRESS ,$params);
        return $json;
    }

   

}