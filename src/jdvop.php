<?php
/**
 * @version    京东VOP接口 V1.0
 * @author     Southom <Southom@gmail.com>
 * @date       2021-12-28
 */
namespace southom;

class jdvop
{
	
    const GET_TOKEN         = 'oauth2/accessToken';  //2.3 获取Access Token
    const REFRESH_TOKEN     = 'oauth2/refreshToken'; //2.4 刷新 Access Token

    protected $token       = [];//Token
    protected $api_url     = ''; //接口URL
    public $account        = []; //接口帐户参数 

    protected $client;//实例化\GuzzleHttp\Client()

    /**
     * [__construct description]
     * @param string $url     https://bizapi.jd.com/
     * @param array  $account ['client_id'=> '','client_secret' => '','username'=> '','password'=> ''];
     */
    public function __construct(string $url,array $account)
    {
        $this->api_url = $url;
        $this->account = $account;
        $this->token   = $this->cache(); 
        
        if(empty($this->token) || (substr($this->token['result']['time'],0,10)+$this->token['result']['expires_in'])<time()){
            $this->getToken();
        }
    } 
    
    /**
     * 获取Access Token
     * @method POST,application/x-www-form-urlencoded
     * @return json
     */
    public function getToken(){
        //$headers  = ['Content-Type' => 'application/x-www-form-urlencoded' ];
        $params   = [
            'grant_type' => 'access_token',
            'client_id'  => $this->account['client_id'],
            'username'   => $this->account['username'],
            'password'   => strtolower(md5($this->account['password'])),
            'timestamp'  => date('Y-m-d H:i:s'),
        ];
        $params['sign']  = $this->createSign($params); 

        //echo $response->getStatusCode(); //200
        try {
        	$json  =  $this->curlPost($this->api_url. self::GET_TOKEN ,$params);
	        $token = json_decode($json,true);
	        if($token['success']==1 && $token['resultCode']=='0000'){
	        	$this->cache( $token);
                $this->token = $token;
                 return $json;
	        }else{
                echo $json;exit;
            }
	        
        } catch (\Exception $e) {
        	return json($e->getMessage());
        }
        
    }

    /**
     * 刷新 Access Token
     * @return json
     */
    public function refreshToken(){
    	if($this->token && $this->token['result']['refresh_token_expires']>time()){
    		 $params   = [
	            'refresh_token' => $token['result']['refresh_token'],
	            'client_id'     => $this->account['client_id'],
	            'client_secret' => $this->account['client_secret'],
	        ]; 
	        $json  = $this->curlPost($this->api_url. self::REFRESH_TOKEN ,$params);
	        $token = json_decode($json,true);
	        Cache::set(self::TOKEN_FILENAME, $token, 80000);
            $this->token = $token;
	        return $json;
    	}else{
    		return $this->getToken();
    	} 
    } 

    /**
     * 创建签名
     * @param array $params 
     * @example 按照以下顺序将字符串拼接起来： client_secret+timestamp+client_id+username+password+grant_type+client_secret
     * @return string
     */
    private function createSign(array $params){
        $string = $this->account['client_secret']. $params['timestamp']. $this->account['client_id']. $this->account['username']. strtolower(md5($this->account['password'])). $params['grant_type'].$this->account['client_secret'];
        return strtoupper(md5($string));
    }

    /**
     * POST提交
     * @param  string $url  接口地址
     * @param  array  $data 提交参数
     * @return array        返回数组
     */
    protected function curlPost(string $url,array $data){
        try{
            $client   = new \GuzzleHttp\Client();
            $response = $client->request('POST', $url ,['form_params'=>$data]);
            $body     = $response->getBody();
            $json     = $body->getContents();
        }catch(\Exception $e){
            $json = $e->getMessage();
        }
    
        return $json;
    }

    protected function cache($data = null){
        $file = dirname(__FILE__).'/TokenCache.php';
        if(empty($data)){
            return file_exists($file)?include($file):[];
        }else{
            file_put_contents($file,sprintf("<?php \n return %s;",var_export($data,true)));
        }
    }

}

