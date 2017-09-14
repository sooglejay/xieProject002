<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/14
 * Time: 20:09
 */
class ReceiveWXinCode
{
    private function w($text)
    {
        $myfile = fopen("./test.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
    }

    /**
     * ReceiveWXinCode constructor.
     */
    public function __construct()
    {

        $code = -1;
        $state = -1;
        if (isset($_REQUEST['code'])) {
            $code = $_REQUEST['code'];
        }
        if (isset($_REQUEST['state'])) {
            $state = $_REQUEST['state'];
        }
        $this->w($code . "-" . $state);
    }
}

new ReceiveWXinCode();