<?php
// $command = escapeshellcmd("Python ../py/processImage.py");
// exec($command, $output);
// var_dump($output);
$output = passthru("python ../py/processImage.py ");
echo $output
?>
