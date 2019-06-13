<?php
namespace think;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\ValidationData ;

/**
 * Class Token
 * @package app\lib
 */
class Jwt{

    /** token令牌
     * @var
     */
    private $token;

    /** 签发域名
     * @var string
     */
    private $iss;

    /** 接收域名
     * @var string
     */
    private $aud;

    /** 用户 uid
     * @var
     */
    private $uid;

    /** 过期时间 默认7200s
     * @var
     */
    private $expire = 7200;

    /** 密钥
     * @var
     */
    private $key = '!@#$$$$%%^^&';

    /** 解析器
     * @var
     */
    private $parser;

    /** 实例
     * @var
     */
    private static $_instance;

    private function __construct(){}
    private function __clone(){}

    /** 获取实例
     * @return Token
     */
    public static function getInstance(){
        if (!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /** 设置 token
     * @return string
     */
    public function genToken(){
        return (string)$this->token;
    }

    /** 设置 iss
     * @param $iss
     * @return $this
     */
    public function setIss($iss){
        $this->iss = $iss;
        return $this;
    }

    /** 设置 aud
     * @param $aud
     * @return $this
     */
    public function setAud($aud){
        $this->aud = $aud;
        return $this;
    }

    /** 设置 uid
     * @param $uid
     * @return $this
     */
    public function setUid($uid){
        $this->uid = $uid;
        return $this;
    }

    /** 设置 expire
     * @param $expire
     * @return $this
     */
    public function setExpire($expire){
        $this->expire = $expire;
        return $this;
    }

    public function setKey($key){
        $this->key = $key;
        return $this;
    }

    /** 设置 token
     * @param $token
     * @return $this
     */
    public function setToken($token){
        $this->token = $token;
        return $this;
    }

    /** jwt encode token
     * @return $this
     */
    public function encode(){
        $time = time();
        $this->token = (new Builder())
                ->issuedBy($this->iss) // 配置发行人（ISS权利要求）
                ->permittedFor($this->aud) // 设置接收人
                ->identifiedBy(md5($this->uid), true) // 当前token设置的标识
                ->issuedAt($time) // token创建时间
//                ->canOnlyBeUsedAfter($time + 60) // 当前时间在这个时间前，token不能使用
                ->expiresAt($time + $this->expire) // 设置过期时间
                ->withClaim('uid', $this->uid) // 给token设置一个id
                ->getToken(new Sha256(), new Key($this->key)); // 对上面的信息使用sha256算法签名
        return $this;
    }

    /** jwt decode token
     * @return bool
     */
    public function decode(){
        if (!$this->parser){
            $this->parser = (new Parser())->parse((string)$this->token);
            $this->uid = $this->parser->getClaim('uid');
        }
        return $this->parser;
    }

    /** verify token
     * @return mixed
     */
    public function verify(){
        return $this->decode()->verify(new Sha256(),$this->key);
    }

    /** 验证 validate
     * @return mixed
     */
    public function validate(){
        $this->decode();
        $data = new ValidationData();
        $data->setIssuer($this->iss);
        $data->setAudience($this->aud);
        $data->setId(md5($this->uid));
        return $this->parser->validate($data);
    }
}