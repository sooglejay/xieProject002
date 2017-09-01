<?php

/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/9/1
 * Time: 21:56
 */

$s = strtotime(date("Y-m-d H:i:s"));
echo gettype($s)."\n";
echo date("Y-m-d H:i", $s);