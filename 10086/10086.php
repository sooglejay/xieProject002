<?php

class MM
{

    /**
     * MM constructor.
     */
    public function __construct()
    {

        $plantText = "userPhone=13993553953&accessName=刘阳&position=四川&habit=打篮球&hobby=看书";
        $token = shell_exec("java -jar jar/des_util.jar  " . $plantText);
        $url = "http://223.87.14.70:16500/ocsfront/ocsfront/html5/webChat.jsp";
        $s = "?channel=zyydwshwx&token=" . $token;
        echo json_encode($url . $s);
    }
}

new MM();
?>

