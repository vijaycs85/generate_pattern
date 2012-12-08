<?php
/**
 * @file
 *
 */

/**
 *
 */
function generate_test_files() {
  $code_object = new GenerateCode('./inputs/test_import_data.csv');
  $code_object->generateFiles('test');
}
function generate_service_files() {
  $code_object = new GenerateCode('./inputs/service_import.csv');
  $code_object->generateFiles('service');
}

/**
 * Code generator class
 */
class GenerateCode {
  public $file;
  public $header;
  public function __construct($file) {
    if ($this->file = fopen(realpath($file), 'r+')) {
      $this->headers = explode(',', trim(fgets($this->file)));
    }
  }

  /**
   * @param $type
   */
  public function generateFiles($type) {
    while (!feof($this->file)) {
      $line = fgets($this->file);
      if (!empty($line)) {
        $data = array_combine($this->headers, explode(',', trim($line)));

      }
    }
   switch($type) {
     case 'service';
       $this->generateService($data);
       break;
     case 'test':
       $this->generateTest($data);
       break;
   }
  }

  /**
   * @param $data
   */
  protected function generateTest($data) {
    @extract($data);

    $output_dir = realpath('./outputs');
    $tpl_dir = realpath('./templates');
    $module_path = $output_dir . '/' . $module_name;
    $file = $module_path . '/' . $filename;

    // create module folder, if doesn't exist
    if (!is_dir($module_path)) {
      mkdir($module_path);
    }
    $tpl_content = file_get_contents($tpl_dir . '/test.tpl');
    $fp = fopen($file, 'w+');
    $tpl_content = str_replace('{class_name}', $class_name, $tpl_content);
    $tpl_content = str_replace('{module_name}', $module_name, $tpl_content);
    fwrite($fp, $tpl_content);
    fclose($fp);
  }

  /**
   * @param $data
   */
  protected  function generateService($data) {
    @extract($data);
    $output_dir = realpath('./outputs');
    $tpl_dir = realpath('./templates');
    $module_path = $output_dir . '/' . $module_name;
    $service_path = $module_path . '/' . 'services' . '/' . $service_name;
    $stub_path = $module_path . '/' . 'stubs' . '/' . $service_name;
    $test_path = $module_path . '/' . 'tests' . '/' . $service_name;

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
    $files['messagequeue'] = $module_path . '/' . $service_name . '.' . $module_name . '.messagequeue_default_service.inc';
    $files['service'] = $service_path . '/' . $module_name . '.' . $service_name . '.inc';
    $files['stub'] = $stub_path . '/' . 'default.xml';
    $files['test'] = $test_path . '/' . $module_name . '.' . $service_name . '.test';
    foreach ($files as $tpl => $file) {
      $tpl_content = file_get_contents($tpl_dir . '/' . $tpl. '.tpl');
      $fp = fopen($file, 'w+');
      $tpl_content = str_replace('{class_name}', $class_name, $tpl_content);
      $tpl_content = str_replace('{service_name}', $service_name, $tpl_content);
      $tpl_content = str_replace('{service_description}', $service_description, $tpl_content);
      $tpl_content = str_replace('{module_name}', $module_name, $tpl_content);
      fwrite($fp, $tpl_content);
      fclose($fp);
    }
    // Copy stub response for excpetions.
    $stub_src = $tpl_dir . '/' . 'stubs';
    $stub_dst = $stub_path . '/';
    if (is_dir($stub_src)) {
      if ($dh = opendir($stub_src)) {
        while (($file = readdir($dh)) !== FALSE) {
          if($file != '.' && $file != '..') {
            @copy($stub_src. '/' . $file, $stub_dst.$file);
          }
        }
        closedir($dh);
      }
    }
  }
}