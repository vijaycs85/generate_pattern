<?php
if ($input_fp = fopen(realpath('./inputs/service_import.csv'), 'r+')) {
  $headers = explode(',', trim(fgets($input_fp)));
  
  while (!feof($input_fp)) {
    $line = fgets($input_fp);
    if (!empty($line)) {
      $service = array_combine($headers, explode(',', trim($line)));
      generateService($service);
    }
  }
}

function generateService($service_details) {
  @extract($service_details);
  $level_limiter = '\\';
  $output_dir = realpath('./outputs');
  $tpl_dir = realpath('./templates');
  $module_path = $output_dir . $level_limiter . $module_name;
  $service_path = $module_path . $level_limiter . 'services' . $level_limiter . $service_name;
  $stub_path = $module_path . $level_limiter . 'stubs' . $level_limiter . $service_name;
  $test_path = $module_path . $level_limiter . 'tests' . $level_limiter . $service_name;
  
  // create module folder, if doesn't exist
  if (!is_dir($module_path)) {
    mkdir($module_path);
  }
  
  // create service folder, if doesn't exist
  if (!is_dir($service_path)) {
    mkdir($service_path, 0777, TRUE);
  }
  // create stub folder, if doesn't exist
  if (!is_dir($stub_path)) {
    mkdir($stub_path, 0777, TRUE);
  }
  // create test folder, if doesn't exist
  if (!is_dir($test_path)) {
    mkdir($test_path, 0777, TRUE);
  }
  $files['messagequeue'] = $module_path . $level_limiter . $service_name . '.' . $module_name . '.messagequeue_default_service.inc';
  $files['service'] = $service_path . $level_limiter . $module_name . '.' . $service_name . '.inc';
  $files['stub'] = $stub_path . $level_limiter . 'default.xml';
  $files['test'] = $test_path . $level_limiter . $module_name . '.' . $service_name . '.test';
  foreach ($files as $tpl => $file) {
    $tpl_content = file_get_contents($tpl_dir . $level_limiter . $tpl. '.tpl');
    $fp = fopen($file, 'w+');
    $tpl_content = str_replace('{class_name}', $class_name, $tpl_content);
    $tpl_content = str_replace('{service_name}', $service_name, $tpl_content);
    $tpl_content = str_replace('{service_description}', $service_description, $tpl_content);
    $tpl_content = str_replace('{module_name}', $module_name, $tpl_content);
    fwrite($fp, $tpl_content);
    fclose($fp);
  }
  // Copy stub response for excpetions.
  $stub_src = $tpl_dir . $level_limiter . 'stubs';
  $stub_dst = $stub_path . $level_limiter;
  if (is_dir($stub_src)) {
    if ($dh = opendir($stub_src)) {
        while (($file = readdir($dh)) !== false) {
          if($file != '.' && $file != '..') {
              @copy($stub_src. $level_limiter . $file, $stub_dst.$file);
          }
        }
        closedir($dh);
    }
  }
}
?>