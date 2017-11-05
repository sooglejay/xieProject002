<?php
/**
 * Created by PhpStorm.
 * User: sooglejay
 * Date: 17/10/30
 * Time: 18:19
 */
require_once dirname(__FILE__) . "/ExportHandler.php";
$e = new ExportHandler();
$e->doDownload();
