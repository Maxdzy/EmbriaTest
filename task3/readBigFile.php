<?php
$fh="Jako_pomnishi_ego.txt";

$handle = fopen($fh, "rb");
fseek($handle, 2000, SEEK_CUR);
$contents = fread($handle, 1096);
echo $contents;
fclose($handle);

