<?php
session_start();

// catch the posted dummy data and write it to the local files as JSON
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // repackage the gauge data, but this time PHP style
  
  $data = array();
  for ($counter = 0; $counter < count($_POST['data']); $counter++) {
    $data['box' . $counter] = array(
      'coordinates' => array(
        'start_x' => $_POST['data'][$counter][0][0],
        'start_y' => $_POST['data'][$counter][0][1],
        'end_x' => $_POST['data'][$counter][0][2],
        'end_y' => $_POST['data'][$counter][0][3]
      ),
      'gaugeType' => $_POST['data'][$counter][1],
    );
  }

  $data['taskId'] = $_POST['subdirName'];

  // write the file
  $subfolderPath = '../../input/' . $_POST['subdirName'];
  $file = fopen($subfolderPath . '/data.json', 'w');
  fwrite($file, json_encode($data));
  fclose($file);

  // return the directory path
  echo $_POST['subdirName'];

}
?>
