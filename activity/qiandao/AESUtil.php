<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/25
 * Time: 11:27
 */
class AESUtil
{
    public function encryptAES($data, $key)
    {
        $iv = "\x0\x1\x2\x3\x4\x5\x6\x7\x8\x9\xa\xb\xc\xd\xe\xf";
        $data = $this->pkcs5_pad($data);
        $key = hex2bin(md5(trim($key)));
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, "", "cbc", "");
        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return bin2hex($encrypted);
    }

    public function decryptAES($encryptedData, $key)
    {
        $iv = "\x0\x1\x2\x3\x4\x5\x6\x7\x8\x9\xa\xb\xc\xd\xe\xf";
        $key = hex2bin(md5(trim($key)));
        $encryptedData = hex2bin($encryptedData);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, "", "cbc", "");
        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encryptedData);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return utf8_encode(trim($this->pkcs5_unpad($decrypted)));
    }

    private function pkcs5_pad($text)
    {
        $blocksize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, "cbc");
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    public function verifyChecksum($MerchantId, $OrderId, $Amount, $AuthDesc, $WorkingKey, $CheckSum)
    {
        $str = "$MerchantId|$OrderId|$Amount|$AuthDesc|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler, $str);
        if ($adler == $CheckSum)
            return true;
        else
            return false;
    }

    public function getChecksum($MerchantId, $OrderId, $Amount, $redirectUrl, $WorkingKey)
    {
        $str = "$MerchantId|$OrderId|$Amount|$redirectUrl|$WorkingKey";
        $adler = 1;
        $adler = $this->adler32($adler, $str);
        return $adler;
    }

    private function adler32($adler, $str)
    {
        $BASE = 65521;
        $s1 = $adler & 0xffff;
        $s2 = ($adler >> 16) & 0xffff;
        for ($i = 0; $i < strlen($str); $i++) {
            $s1 = ($s1 + Ord($str[$i])) % $BASE;
            $s2 = ($s2 + $s1) % $BASE;
        }
        return $this->leftshift($s2, 16) + $s1;
    }

    private function leftshift($str, $num)
    {
        $str = DecBin($str);
        for ($i = 0; $i < (64 - strlen($str)); $i++)
            $str = "0" . $str;
        for ($i = 0; $i < $num; $i++) {
            $str = $str . "0";
            $str = substr($str, 1);
            //echo "str : $str <BR>";
        }
        return $this->cdec($str);
    }

    private function cdec($num)
    {
        $dec = 0;
        for ($n = 0; $n < strlen($num); $n++) {
            $temp = $num[$n];
            $dec = $dec + $temp * pow(2, strlen($num) - $n - 1);
        }
        return $dec;
    }
}

if (!function_exists("hex2bin")) {
    function hex2bin($hexdata)
    {
        $bindata = "";
        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }
}