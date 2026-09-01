<?php

if (!defined('ABSPATH')) {
  exit;
}

add_action('rest_api_init', 'vfwp_intranet_register_chatbot_routes');

function vfwp_intranet_register_chatbot_routes() {
  register_rest_route('vfwp/v1', '/chat', array(
    'methods'             => 'POST',
    'callback'            => 'vfwp_intranet_rest_chatbot_chat',
    'permission_callback' => 'vfwp_intranet_rest_chatbot_permissions',
  ));
}

function vfwp_intranet_rest_chatbot_permissions(WP_REST_Request $request) {
  return (bool) apply_filters(
    'vfwp_intranet_chatbot_rest_permission',
    is_user_logged_in(),
    $request
  );
}

function vfwp_intranet_rest_chatbot_chat(WP_REST_Request $request) {
  $message = vfwp_intranet_chatbot_sanitize_message($request->get_param('message'));

  if ($message === '') {
    return new WP_Error(
      'vfwp_chatbot_empty_message',
      __('Please enter a message.', 'vfwp'),
      array('status' => 400)
    );
  }

  $endpoint = vfwp_intranet_chatbot_get_endpoint();
  $model = vfwp_intranet_chatbot_get_model();

  if ($endpoint === '' || $model === '') {
    return new WP_Error(
      'vfwp_chatbot_not_configured',
      __('The chatbot endpoint is not configured.', 'vfwp'),
      array('status' => 500)
    );
  }

  $body = vfwp_intranet_chatbot_build_openai_request($request, $message, $model);
  $headers = array(
    'Accept'       => 'application/json',
    'Content-Type' => 'application/json',
  );
  $api_key = vfwp_intranet_chatbot_get_api_key();

  if ($api_key !== '') {
    $headers['Authorization'] = 'Bearer ' . $api_key;
  }

  $response = wp_remote_post($endpoint, array(
    'headers' => $headers,
    'body'    => wp_json_encode($body),
    'timeout' => (int) apply_filters('vfwp_intranet_chatbot_request_timeout', 30),
  ));

  if (is_wp_error($response)) {
    vfwp_intranet_chatbot_log_debug('request_failed', array(
      'endpoint' => vfwp_intranet_chatbot_redact_endpoint($endpoint),
      'model'    => $model,
      'message_count' => count($body['messages']),
      'error'    => $response->get_error_message(),
    ));

    return new WP_Error(
      'vfwp_chatbot_request_failed',
      $response->get_error_message(),
      array('status' => 502)
    );
  }

  $status_code = wp_remote_retrieve_response_code($response);
  $response_body = wp_remote_retrieve_body($response);
  $data = json_decode($response_body, true);

  if ($status_code < 200 || $status_code >= 300) {
    vfwp_intranet_chatbot_log_debug('upstream_error', array(
      'endpoint'        => vfwp_intranet_chatbot_redact_endpoint($endpoint),
      'model'           => $model,
      'message_count'   => count($body['messages']),
      'upstream_status' => $status_code,
      'upstream_error'  => vfwp_intranet_chatbot_get_upstream_error_message($data, $status_code),
    ));

    return new WP_Error(
      'vfwp_chatbot_upstream_error',
      vfwp_intranet_chatbot_get_upstream_error_message($data, $status_code),
      array(
        'status'          => 502,
        'upstream_status' => $status_code,
      )
    );
  }

  if (!is_array($data)) {
    vfwp_intranet_chatbot_log_debug('invalid_response', array(
      'endpoint'        => vfwp_intranet_chatbot_redact_endpoint($endpoint),
      'model'           => $model,
      'message_count'   => count($body['messages']),
      'upstream_status' => $status_code,
    ));

    return new WP_Error(
      'vfwp_chatbot_invalid_response',
      __('The chatbot endpoint returned an invalid response.', 'vfwp'),
      array('status' => 502)
    );
  }

  $content = vfwp_intranet_chatbot_extract_openai_content($data);

  if ($content === '') {
    vfwp_intranet_chatbot_log_debug('empty_response', array(
      'endpoint'        => vfwp_intranet_chatbot_redact_endpoint($endpoint),
      'model'           => $model,
      'message_count'   => count($body['messages']),
      'upstream_status' => $status_code,
    ));

    return new WP_Error(
      'vfwp_chatbot_empty_response',
      __('The chatbot endpoint did not return a message.', 'vfwp'),
      array('status' => 502)
    );
  }

  return rest_ensure_response(array(
    'response' => vfwp_intranet_chatbot_format_response_html($content),
    'sources'  => vfwp_intranet_chatbot_sanitize_sources($data),
    'prompts'  => vfwp_intranet_chatbot_sanitize_prompts($data),
  ));
}

function vfwp_intranet_chatbot_build_openai_request(WP_REST_Request $request, $message, $model) {
  $messages = vfwp_intranet_chatbot_build_messages($request, $message);
  $body = array(
    'model'    => $model,
    'messages' => $messages,
    'stream'   => false,
  );

  $temperature = vfwp_intranet_chatbot_get_numeric_setting(
    'VFWP_INTRANET_CHATBOT_TEMPERATURE',
    'VFWP_INTRANET_CHATBOT_TEMPERATURE'
  );

  if ($temperature !== null) {
    $body['temperature'] = $temperature;
  }

  $max_tokens = vfwp_intranet_chatbot_get_integer_setting(
    'VFWP_INTRANET_CHATBOT_MAX_TOKENS',
    'VFWP_INTRANET_CHATBOT_MAX_TOKENS'
  );

  if ($max_tokens !== null) {
    $body['max_tokens'] = $max_tokens;
  }

  return apply_filters('vfwp_intranet_chatbot_openai_request_body', $body, $request);
}

function vfwp_intranet_chatbot_build_messages(WP_REST_Request $request, $message) {
  $messages = array();
  $system_prompt = vfwp_intranet_chatbot_get_system_prompt($request);

  if ($system_prompt !== '') {
    $messages[] = array(
      'role'    => 'system',
      'content' => $system_prompt,
    );
  }

  $history = $request->get_param('messageHistory');
  $history_limit = (int) apply_filters('vfwp_intranet_chatbot_history_limit', 20, $request);

  if (is_array($history)) {
    $history = array_slice($history, -1 * max(1, $history_limit));

    foreach ($history as $entry) {
      if (!is_array($entry)) {
        continue;
      }

      $role = isset($entry['type']) && $entry['type'] === 'assistant' ? 'assistant' : 'user';
      $content = vfwp_intranet_chatbot_sanitize_message(isset($entry['content']) ? $entry['content'] : '');

      if ($content === '') {
        continue;
      }

      $messages[] = array(
        'role'    => $role,
        'content' => $content,
      );
    }
  }

  $last_message = end($messages);

  if (
    !is_array($last_message) ||
    $last_message['role'] !== 'user' ||
    $last_message['content'] !== $message
  ) {
    $messages[] = array(
      'role'    => 'user',
      'content' => $message,
    );
  }

  return $messages;
}

function vfwp_intranet_chatbot_get_system_prompt(WP_REST_Request $request) {
  $prompt = vfwp_intranet_chatbot_get_setting(
    'VFWP_INTRANET_CHATBOT_SYSTEM_PROMPT',
    'VFWP_INTRANET_CHATBOT_SYSTEM_PROMPT',
    __('You are a helpful assistant for the EMBL intranet. Answer clearly and concisely.', 'vfwp')
  );
  $assistant = vfwp_intranet_chatbot_sanitize_message($request->get_param('assistant'));

  if ($assistant !== '' && $assistant !== 'all') {
    $prompt .= "\n" . sprintf(
      __('The user selected this service context: %s.', 'vfwp'),
      $assistant
    );
  }

  return apply_filters('vfwp_intranet_chatbot_system_prompt', $prompt, $request);
}

function vfwp_intranet_chatbot_get_endpoint() {
  $temporary_endpoint = vfwp_intranet_chatbot_get_temporary_setting('openai_endpoint');

  if ($temporary_endpoint !== '') {
    return esc_url_raw(apply_filters('vfwp_intranet_chatbot_openai_endpoint', $temporary_endpoint));
  }

  $endpoint = vfwp_intranet_chatbot_get_setting(
    'VFWP_INTRANET_CHATBOT_OPENAI_ENDPOINT',
    'VFWP_INTRANET_CHATBOT_OPENAI_ENDPOINT',
    ''
  );

  if ($endpoint === '') {
    $base_url = vfwp_intranet_chatbot_get_setting('OPENAI_BASE_URL', 'OPENAI_BASE_URL', '');
    if ($base_url !== '') {
      $endpoint = trailingslashit(untrailingslashit($base_url)) . 'chat/completions';
    }
  }

  if ($endpoint === '') {
    $endpoint = 'https://api.openai.com/v1/chat/completions';
  }

  return esc_url_raw(apply_filters('vfwp_intranet_chatbot_openai_endpoint', $endpoint));
}

function vfwp_intranet_chatbot_get_model() {
  $temporary_model = vfwp_intranet_chatbot_get_temporary_setting('openai_model');

  if ($temporary_model !== '') {
    return sanitize_text_field($temporary_model);
  }

  $model = vfwp_intranet_chatbot_get_setting(
    'VFWP_INTRANET_CHATBOT_OPENAI_MODEL',
    'VFWP_INTRANET_CHATBOT_OPENAI_MODEL',
    ''
  );

  if ($model === '') {
    $model = vfwp_intranet_chatbot_get_setting('OPENAI_MODEL', 'OPENAI_MODEL', 'gpt-4o-mini');
  }

  return sanitize_text_field($model);
}

function vfwp_intranet_chatbot_get_api_key() {
  $temporary_api_key = vfwp_intranet_chatbot_get_temporary_setting('openai_api_key');

  if ($temporary_api_key !== '') {
    return trim($temporary_api_key);
  }

  $api_key = vfwp_intranet_chatbot_get_setting(
    'VFWP_INTRANET_CHATBOT_OPENAI_API_KEY',
    'VFWP_INTRANET_CHATBOT_OPENAI_API_KEY',
    ''
  );

  if ($api_key === '') {
    $api_key = vfwp_intranet_chatbot_get_setting('OPENAI_API_KEY', 'OPENAI_API_KEY', '');
  }

  return trim($api_key);
}

function vfwp_intranet_chatbot_get_temporary_setting($name) {
  if (!vfwp_intranet_chatbot_allow_temporary_settings()) {
    return '';
  }

  $value = get_transient('vfwp_intranet_chatbot_' . $name);

  if (!is_string($value)) {
    return '';
  }

  return trim($value);
}

function vfwp_intranet_chatbot_allow_temporary_settings() {
  $environment = function_exists('wp_get_environment_type') ? wp_get_environment_type() : '';
  $docker_environment = getenv('ENVIRONMENT');
  $host = wp_parse_url(home_url(), PHP_URL_HOST);
  $is_local_host = in_array($host, array('localhost', '127.0.0.1'), true) || substr((string) $host, -17) === '.docker.localhost';

  return (bool) apply_filters(
    'vfwp_intranet_chatbot_allow_temporary_settings',
    in_array($environment, array('local', 'development'), true) ||
      in_array($docker_environment, array('dev', 'local'), true) ||
      (defined('WP_DEBUG') && WP_DEBUG && $is_local_host)
  );
}

function vfwp_intranet_chatbot_get_setting($constant_name, $environment_name, $default = '') {
  if (defined($constant_name)) {
    return (string) constant($constant_name);
  }

  $value = getenv($environment_name);

  if ($value !== false && $value !== '') {
    return (string) $value;
  }

  return (string) $default;
}

function vfwp_intranet_chatbot_get_numeric_setting($constant_name, $environment_name) {
  $value = vfwp_intranet_chatbot_get_setting($constant_name, $environment_name, '');

  if ($value === '' || !is_numeric($value)) {
    return null;
  }

  return (float) $value;
}

function vfwp_intranet_chatbot_get_integer_setting($constant_name, $environment_name) {
  $value = vfwp_intranet_chatbot_get_setting($constant_name, $environment_name, '');

  if ($value === '' || !is_numeric($value)) {
    return null;
  }

  return max(1, (int) $value);
}

function vfwp_intranet_chatbot_sanitize_message($message) {
  if (!is_scalar($message)) {
    return '';
  }

  $message = trim((string) $message);

  if ($message === '') {
    return '';
  }

  return sanitize_textarea_field($message);
}

function vfwp_intranet_chatbot_extract_openai_content($data) {
  if (isset($data['choices'][0]['message']['content'])) {
    return vfwp_intranet_chatbot_stringify_content($data['choices'][0]['message']['content']);
  }

  if (isset($data['choices'][0]['delta']['content'])) {
    return vfwp_intranet_chatbot_stringify_content($data['choices'][0]['delta']['content']);
  }

  if (isset($data['response'])) {
    return vfwp_intranet_chatbot_stringify_content($data['response']);
  }

  return '';
}

function vfwp_intranet_chatbot_stringify_content($content) {
  if (is_string($content)) {
    return trim($content);
  }

  if (!is_array($content)) {
    return '';
  }

  $parts = array();

  foreach ($content as $part) {
    if (is_string($part)) {
      $parts[] = $part;
      continue;
    }

    if (is_array($part) && isset($part['text']) && is_string($part['text'])) {
      $parts[] = $part['text'];
    }
  }

  return trim(implode("\n", $parts));
}

function vfwp_intranet_chatbot_format_response_html($content) {
  $content = esc_html($content);
  $content = nl2br($content, false);

  return apply_filters('vfwp_intranet_chatbot_response_html', $content);
}

function vfwp_intranet_chatbot_sanitize_sources($data) {
  $sources = array();

  if (isset($data['sources']) && is_array($data['sources'])) {
    $sources = $data['sources'];
  } elseif (isset($data['choices'][0]['message']['sources']) && is_array($data['choices'][0]['message']['sources'])) {
    $sources = $data['choices'][0]['message']['sources'];
  }

  return array_values(array_filter(array_map(function ($source) {
    if (!is_array($source)) {
      return null;
    }

    return array(
      'domain'      => sanitize_text_field(isset($source['domain']) ? $source['domain'] : ''),
      'title'       => sanitize_text_field(isset($source['title']) ? $source['title'] : ''),
      'url'         => esc_url_raw(isset($source['url']) ? $source['url'] : ''),
      'description' => sanitize_text_field(isset($source['description']) ? $source['description'] : ''),
    );
  }, $sources)));
}

function vfwp_intranet_chatbot_sanitize_prompts($data) {
  $prompts = array();

  if (isset($data['prompts']) && is_array($data['prompts'])) {
    $prompts = $data['prompts'];
  } elseif (isset($data['choices'][0]['message']['prompts']) && is_array($data['choices'][0]['message']['prompts'])) {
    $prompts = $data['choices'][0]['message']['prompts'];
  }

  return array_values(array_filter(array_map(function ($prompt) {
    if (is_string($prompt)) {
      return array(
        'action_text' => sanitize_text_field($prompt),
        'action_url'  => '',
      );
    }

    if (!is_array($prompt) || empty($prompt['action_text'])) {
      return null;
    }

    return array(
      'action_text' => sanitize_text_field($prompt['action_text']),
      'action_url'  => esc_url_raw(isset($prompt['action_url']) ? $prompt['action_url'] : ''),
    );
  }, $prompts)));
}

function vfwp_intranet_chatbot_get_upstream_error_message($data, $status_code) {
  if (is_array($data) && isset($data['error']['message'])) {
    return sanitize_text_field($data['error']['message']);
  }

  return sprintf(
    __('The chatbot endpoint returned HTTP %d.', 'vfwp'),
    (int) $status_code
  );
}

function vfwp_intranet_chatbot_log_debug($event, $context) {
  if (!defined('WP_DEBUG') || !WP_DEBUG) {
    return;
  }

  $context['event'] = $event;
  error_log('[vfwp-chatbot] ' . wp_json_encode($context));
}

function vfwp_intranet_chatbot_redact_endpoint($endpoint) {
  $parts = wp_parse_url($endpoint);

  if (!is_array($parts) || empty($parts['host'])) {
    return '';
  }

  $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
  $path = isset($parts['path']) ? $parts['path'] : '';

  return $scheme . $parts['host'] . $path;
}
