# think-jwt
一个php的jwt封装包，方便直接使用，我这里安装是的 ```"lcobucci/jwt": "^3.3"```  


# 基本使用
使用之前必须先安装 ```jwt```，使用 composer 安装
```
composer require lcobucci/jwt
```
然后再安装本包
```
composer require think/jwt
```
安装之后，即可在需要使用的类中use引入
```
use think\Jwt;
```
然后调用方法：genToken获取 token  
```
$token = Jwt::getInstance()
            ->setIss('cyb.cn.admin') // 签发者
            ->setAud('cyb.cn') // 接收者
            ->setUid(1) // uid
            ->setExpire(7200) // 过期时间
            ->setKey('key') // 密钥
            ->encode()
            ->genToken();
```
验证 token
```
try{
	$a = Jwt::getInstance()
	            ->setIss('cyb.cn.admin')// 签发者
	            ->setAud('cyb.cn') // 接收者
	            ->setKey('key') // 密钥
	            ->setToken($token) // token
	            ->validate()
	$$b = Jwt::getInstance()
	            ->setIss('cyb.cn.admin')// 签发者
	            ->setAud('cyb.cn') // 接收者
	            ->setKey('key') // 密钥
	            ->setToken($token) // token
	            ->validate()
	if($a && $b){
		return true;
	}
	return false
}catch (\Exception $e){
	return false;
}	            
```
在thinkphp5里基本使用就是以上如此了，封装这个只为方便使用
