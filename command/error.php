<?php

$handle = popen("/bin/tail ../error_log.txt", "r");

$read = fread($handle, 2096);
echo "<pre>".$read."</pre>";

pclose($handle);