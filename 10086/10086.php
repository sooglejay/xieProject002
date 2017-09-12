<?php

class MM
{
    /**
     * MM constructor.
     */
    public function __construct()
    {
        $userPhone = "";
        if(isset($_REQUEST["userPhone"])){
            $userPhone = $_REQUEST["userPhone"];
        }
        $_REQUEST["userPhone"];
        $token = shell_exec("java -jar jar/des_util.jar  " . $userPhone);
        $url = "http://223.87.14.70:16500/ocsfront/ocsfront/html5/webChat.jsp";
        $s = "?channel=zyydwshwx&token=" . $token;
        echo json_encode($url . $s);
    }
}

new MM();
?>

