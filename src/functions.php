<?php

function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function require_with_locals($__file, $locals = []) {
    extract($locals);
    unset($locals);
    require $__file;
}

function child_template_start() {
    ob_start();
}

function block_start() {
    ob_start();
}

function block_end() {
    return ob_get_clean();
}

function child_template_end($parent, $locals = []) {
    ob_end_clean();
    require_with_locals($parent, $locals);
}

define('PREG_MATCH_KEY', 1);
define('PREG_MATCH_VALUE', 0);
define('PREG_RETURN_KEY', 2);
define('PREG_RETURN_VALUE', 0);

function preg_match_pattern_array($patterns, $subject, &$matches = null, $flags = PREG_MATCH_VALUE | PREG_RETURN_VALUE, $offset = 0) {
    $preg_match_flags = $flags & PREG_OFFSET_CAPTURE;
    foreach ($patterns as $key => $value) {
        $re = ($flags & PREG_MATCH_KEY) ? $key : $value;
        $result = preg_match($re, $subject, $matches, $preg_match_flags, $offset);
        if ($result === 1) {
            return ($flags & PREG_RETURN_KEY) ? $key : $value;
        } else if ($result === false) {
            return false;
        }
    }

    return null;
}

function http_normalized_base_path($request_uri = null, $script_name = null) {
    if ($request_uri === null) {
        $request_uri = $_SERVER['REQUEST_URI'];
    }

    if ($script_name === null) {
        $script_name = $_SERVER['REDIRECT_BASE'] ?? $_SERVER['SCRIPT_NAME'];
    }

    $request_uri = strtok($request_uri, '?');
    if (strpos($request_uri, $script_name) !== 0) {
        $script_name = dirname($script_name);
    }
    
    return $script_name;
}

function http_normalized_path_info($request_uri = null, $script_name = null) {
    if ($request_uri === null) {
        $request_uri = $_SERVER['REQUEST_URI'];
    }

    if ($script_name === null) {
        $script_name = $_SERVER['REDIRECT_BASE'] ?? $_SERVER['SCRIPT_NAME'];
    }

    $request_uri = strtok($request_uri, '?');
    if (strpos($request_uri, $script_name) !== 0) {
        $script_name = dirname($script_name);
    }
    
    $path = substr($request_uri, strlen($script_name));
    $path = '/' . trim($path, '/');

    return $path;
}

function error_to_exception_handler($severity, $message, $file, $line) {
    if ((error_reporting() & $severity) === 0) {
        return;
    }
    
    throw new ErrorException($message, 0, $severity, $file, $line);
}

function http_request_accept($mime) {
    return strpos($_SERVER['HTTP_ACCEPT'], $mime) !== false;
}

function preg_build_from_path($path) {
    return '#^' . preg_replace('/:(\w+)/', '(?P<$1>[^/]+)', $path) . '$#';
}

function json_send($data) {
    header('Content-type: application/json');
    echo json_encode($data);
}

function json_from_request_body() {
    return json_decode(file_get_contents('php://input'), true);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function array_map_keys($array, $callback) {
    return array_combine(array_map($callback, array_keys($array)), array_values($array));
}

function session_get($key) {
    if (!session_id()) {
        session_start();
    }
    
    return $_SESSION[$key] ?? null;
}

function session_put($key, $value) {
    if (!session_id()) {
        session_start();
    }

    $_SESSION[$key] = $value;
}

function session_delete($key) {
    $value = session_get($key);
    unset($_SESSION[$key]);
    
    return $value;
}
