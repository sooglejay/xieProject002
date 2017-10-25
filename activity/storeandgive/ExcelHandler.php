<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/10/25
 * Time: 11:18
 */
require_once dirname(__FILE__).'/StoreAndGiveExport.php';
$handler = new StoreAndGiveExport();
$handler->doDownload();
