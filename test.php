<?php
///**
// * Created by PhpStorm.
// * User: sooglejay
// * Date: 17/9/1
// * Time: 21:56
// */
////date_default_timezone_set("PRC");
////echo strtotime("6/30/2017 17:53:53");
////echo  date("m/d/Y H:i:s");
////echo dirname(__FILE__)
////;
//
//
//
////php aes加密类
//class AESMcrypt {
//    public $iv = null;
//    public $key = null;
//    public $bit = 128;
//    private $cipher;
//    public function __construct($bit, $key, $iv, $mode) {
//        if(empty($bit) || empty($key) || empty($iv) || empty($mode))
//            return NULL;
//        $this->bit = $bit;
//        $this->key = $key;
//        $this->iv = $iv;
//        $this->mode = $mode;
//        switch($this->bit) {
//            case 192:$this->cipher = MCRYPT_RIJNDAEL_192; break;
//            case 256:$this->cipher = MCRYPT_RIJNDAEL_256; break;
//            default: $this->cipher = MCRYPT_RIJNDAEL_128;
//        }
//        switch($this->mode) {
//            case 'ecb':$this->mode = MCRYPT_MODE_ECB; break;
//            case 'cfb':$this->mode = MCRYPT_MODE_CFB; break;
//            case 'ofb':$this->mode = MCRYPT_MODE_OFB; break;
//            case 'nofb':$this->mode = MCRYPT_MODE_NOFB; break;
//            default: $this->mode = MCRYPT_MODE_CBC;
//        }
//    }
//    public function encrypt($data) {
//        $data = base64_encode(mcrypt_encrypt( $this->cipher, $this->key, $data, $this->mode, $this->iv));
//        return $data;
//    }
//    public function decrypt($data) {
//        $data = mcrypt_decrypt( $this->cipher, $this->key, base64_decode($data), $this->mode, $this->iv);
//        $data = rtrim(rtrim($data));
//        return $data;
//    }
//}
////使用方法
//$aes = new AESMcrypt($bit = 128, $key = 'liangshanyidong!@#$%12345^&*9678', $iv = '0987654321fedcba', $mode = 'cbc');
//$pas = '{"Lid":1,"Password":"e10adc3949ba59abbe56e057f20f883e","mobile":"15928620723","userId":"user"}';
//$c = $aes->encrypt($pas);
//var_dump($c);
//echo "\n\n";
//var_dump($aes->decrypt($c));
//
//
//
//
//
//

$key="liangshanyidong!@#$%12345^&*9678";
//$key previously generated safely, ie: openssl_random_pseudo_bytes
$plaintext = '{"Lid":1,"Password":"e10adc3949ba59abbe56e057f20f883e","mobile":"15928620723","userId":"user"}';
$ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
$iv = openssl_random_pseudo_bytes($ivlen);
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);

echo $ciphertext_raw."\n\n";
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
$ciphertext='OENDODU1MDg2QzNFM0I2MzdERjY0NUQ5NTJGMkUwRTY2NUE2M0UxNTUzOEM1NjU0QzM5MkU2OTlBRTI0QUQzODlGQTVBNzdCQThEQTc0NUI4NjBCQzhCMTQ1QTJDNTEwQ0FDNjQwRTIxQjNDOEMwRTVGODU3NTRFOEQzOUUzM0M4RTU3NDY3QjlGREM3NkY0NjI0ODhFQkFBOUFGRDBENTYwMjJFMEMzMkFDRTE4MzlFRTRFODEwQkFBRTUzQzY2';
echo $ciphertext."\n\n";
//decrypt later....
$c = base64_decode($ciphertext);
$ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
$iv = substr($c, 0, $ivlen);
$hmac = substr($c, $ivlen, $sha2len=32);
$ciphertext_raw = substr($c, $ivlen+$sha2len);
$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
{
    echo $original_plaintext."\n";
}
?>
