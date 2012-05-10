<?php
/**
 * @file
 *   Service implementation.
 *   {service_description}.
 */
Class {class_name} extends DrupalMessageQueueService {

  /**
   * Extends buildMessage
   * @see DrupalMessageQueueService::buildMessage()
   *
   * @param array $params
   *   request parameters.
   *
   * @return mixed
   *   Processed requested details.
   */
  protected function buildMessage($params) {
    $wrapper = array();

    return $wrapper;
  }

  /**
   * Extends buildHeaders
   * @see DrupalMessageQueueService::buildHeaders()
   *
   * @param array $params
   *   request parameters for headers.
   *
   * @return array
   *   An array of service headers.
   */
  protected function buildHeaders($params) {
    return array(
      'ServiceName' => '{class_name}',
    ) + parent::buildHeaders($params);
  }

  /**
   * Creates service object.
   *
   * @return object
   *   Service object.
   */
  public static function create() {
    return messagequeue_service_load('{service_name}');
  }
  /**
   * Throw exception in case of any problems reported by the response.
   *
   * @param object $pool
   *   Pool object for AMQ server.
   * @param array $params
   *   Array of service input parameters.
   * @param object|array $response
   *   Response of service call.
   *
   * @see DrupalMessageQueueService::handleExceptions()
   */
  protected function handleExceptions($pool, $params, $response) {
    if (isset($response->faultCode)) {
      throw new Exception(t('!message (!code - !reason)', array('!message' => $response->faultMessage, '!code' => $response->faultCode, '!reason' => $response->faultReason)));
    }
    if (!isset($response)) {
      throw new Exception('Service response didnt find the postcode');
    }
  }

  /**
   *  Method should return stubbed reponse.
   *
   * @param object $pool
   *   Pool object for AMQ server.
   * @param array $message
   *   Array of service input parameters.
   *
   * @return mixed
   *   Reponse of external service call.
   */
  protected function invokeStub($pool, $message, $headers) {
    $xmlpath = drupal_get_path('module', '{module_name}') . '/stubs/' . $this->machine_name;
    $xmlfile = '/default.xml';
    $xml_file = $xmlpath . $xmlfile;
    $response = new stdClass();
    $response->status = 1;
    $response->body = file_get_contents($xml_file);
    return $response;
  }
}
