<?php

$tpl = new Helper('views/detalleView.html');

$chipid = explode("/",$_SERVER["REQUEST_URI"]);

$tpl->assign("CHIP_ID", end($chipid));

$tpl->printToScreen();

?>
