<?php
$command = escapeshellcmd('python2 ../py/processImage.py');
$output = shell_exec($command);
echo $output;


// $output = passthru("python2 ./../py/processImage.py ");
// echo $output
?>
