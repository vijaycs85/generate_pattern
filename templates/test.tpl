<?php
/**
 * @file
 *   Testing functionalities of {service_name} service.
 */
class {class_name}TestCase extends DrupalWebTestCase {
  /**
   *  Define the name,group and description  for this unit test.
   */
  public static function getInfo() {
    return array(
      'name' => '{class_name} unit test',
      'description' => 'unit test for {class_name} service',
      'group' => 'Interaction',
    );
  }
  /**
   * Initialize the class properties.
   */
  public function setUp() {
    parent::setUp('{module_name}');
  }
  /**
   * Method holds helper function test cases.
   *
   */
  public function setUpStub() {
    $service = {class_name}::create();
    $service->stub = TRUE;
    $service->invoke();
   }
}
