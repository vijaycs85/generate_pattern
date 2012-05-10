  $service = new {class_name}();
  /* Edit this to true to make a default pool disabled initially */
  $service->disabled = FALSE;
  $service->api_version = 1;
  $service->machine_name = "{service_name}";
  $service->description = "{service_description}.";
  $service->pool = "eurostar_dev";
  $service->base_class = "{class_name}";
  $service->cache = 0;
  $service->debug = 0;
  $service->stub = 0;
  $service->logging_level = 0;
  $service->queue = array(
    'request' => '/queue/com.{module_name}.request',
    'response' => '/queue/com.{module_name}.response',
  );
  $services['{service_name}'] = $service;
