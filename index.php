<?php

require_once './dmidecode-class-custome.php';

$dimDecode = new DmiDecode($json = true);
echo $dimDecode->toJson("sudo /usr/sbin/dmidecode -t 17");

?>