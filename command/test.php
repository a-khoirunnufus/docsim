<?php

// phpinfo();

$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "/home/u835676996/public_html/docsim/command/error-output.txt", "a") // stderr is a file to write to
);

$cwd = '/home/u835676996/public_html/docsim';
$env = array(
    'HOME' => '/home/u835676996',
    'COMPOSER_HOME' => '/home/u835676996/.config/composer'
);

$process = proc_open('tail error_log.txt', $descriptorspec, $pipes, $cwd, $env);

if (is_resource($process)) {
    // $pipes now looks like this:
    // 0 => writeable handle connected to child stdin
    // 1 => readable handle connected to child stdout
    // Any error output will be appended to /tmp/error-output.txt

    fwrite($pipes[0], '<?php print_r($_ENV); ?>');
    fclose($pipes[0]);

    echo "<pre>";
    echo stream_get_contents($pipes[1]);
    echo "</pre>";
    fclose($pipes[1]);

    // It is important that you close any pipes before calling
    // proc_close in order to avoid a deadlock
    $return_value = proc_close($process);

    echo "command returned $return_value\n";
}