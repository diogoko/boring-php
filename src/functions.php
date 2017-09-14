<?php

/**
 * Escape a string to be used in an HTML document.
 *
 * This function assumes UTF-8 encoding.
 *
 * @param string $s The string to be encoded
 * @return string The encoded string
 */
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}


/**
 * Include and evaluate a PHP script in an isolated context, raising an
 * error on failure.
 *
 * @param string $__file The file to be included
 * @param array $locals The variables that will be available to the included file
 */
function require_with_locals($__file, $locals = []) {
    extract($locals);
    unset($locals);
    require $__file;
}


/**
 * Mark the beginning of a template that will inherit from another template.
 *
 * A call to this function should be paired with `child_template_end`.
 */
function child_template_start() {
    ob_start();
}


/**
 * Mark the beginning of a block that will be included in a parent template.
 *
 * A call to this function should be paired with `block_end`.
 */
function block_start() {
    ob_start();
}


/**
 * Mark the ending of the block of a child template.
 *
 * @return string The content of the block
 */
function block_end() {
    return ob_get_clean();
}


/**
 * Mark the ending of a child template and render the parent template.
 *
 * Usually the locals passed on to the parent template will include the
 * contents of the blocks delimited with `block_start` and `block_end`.
 *
 * @param string $parent The path of the parent template
 * @param array $locals The variables that will be available to the parent template
 */
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


/**
 * Get the normalized base path of the request URI.
 *
 * @param string $request_uri The request URI to be parsed, or the value
 *                            from `$_SERVER['REQUEST_URI']` if not specified
 * @param string $script_name The script path, or the value of
 *                            `$_SERVER['REDIRECT_BASE']` if not specified, or
 *                            the value of `$_SERVER['SCRIPT_NAME']`
 * @return string The path that can be used to link to the requested script
 */
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


/**
 * Get the path after the request script, if any.
 *
 * If no path was specified after the script, `'/'` is returned. Any path
 * always starts with a slash and never ends with one.
 *
 * @param string $request_uri The request URI to be parsed, or the value
 *                            from `$_SERVER['REQUEST_URI']` if not specified
 * @param string $script_name The script path, or the value of
 *                            `$_SERVER['REDIRECT_BASE']` if not specified, or
 *                            the value of `$_SERVER['SCRIPT_NAME']`
 * @return string The path after the requested script
 */
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


/**
 * Error handler to convert any raised notices, warnings, and errors to exceptions.
 *
 * This function is supposed be used with `set_error_handler`.
 */
function error_to_exception_handler($severity, $message, $file, $line) {
    if ((error_reporting() & $severity) === 0) {
        return;
    }
    
    throw new ErrorException($message, 0, $severity, $file, $line);
}


/**
 * Check if the current request sent an Accept header compatible
 * with the given mime type.
 *
 * @param string $mime The mime type to be matched
 * @return bool True if the header exists and is compatible, false otherwise
 */
function http_request_accept($mime) {
    return strpos($_SERVER['HTTP_ACCEPT'] ?? '', $mime) !== false;
}


/**
 * Build a regular expression suitable to be used to match a path with parameters.
 *
 * @param string $path The path template, with segments that start with a colon
 *                     indicating a parameter that accepts a sequence of word-characters
 * @return string The PCRE-compatible regular expression, including delimiters
 */
function preg_build_from_path($path) {
    return '#^' . preg_replace('/:(\w+)/', '(?P<$1>[^/]+)', $path) . '$#';
}


/**
 * Send a response with a JSON encoded value.
 *
 * This function sets the `Content-type` response header.
 *
 * @param mixed $data The value to be sent
 */
function json_send($data) {
    header('Content-type: application/json');
    echo json_encode($data);
}


/**
 * Parse a JSON encoded value from the request body.
 *
 * @return mixed The decoded value, mapping objects to arrays
 */
function json_from_request_body() {
    return json_decode(file_get_contents('php://input'), true);
}


/**
 * Send a redirection header and finish execution of the script.
 *
 * @param string $url The URL to be redirected to
 */
function redirect($url) {
    header("Location: $url");
    exit();
}


/**
 * Apply a callback to the keys of an array.
 *
 * @param array $array The array to be processed
 * @param callback $callback Callback function to run for each key of the array
 * @return An array containing all keys and respective values after applying
 *         the callback to each key
 */
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


function topological_sort($graph) {
    $sort = new \MJS\TopSort\Implementations\FixedArraySort();

    foreach ($graph as $from => $to_list) {
        if (!empty($to_list)) {
            $sort->add($from, $to_list);
        }
        
        foreach ($to_list as $dep) {
            $sort->add($dep);
        }
    }

    return $sort->sort();
}


function dependencies_filter($graph, $deps) {
    $filtered = [];
    while (!empty($deps)) {
        $d = array_shift($deps);

        if (!empty($graph[$d])) {
            $neighbors = $graph[$d];
            $filtered[$d] = $neighbors;
            $deps = array_merge($deps, $neighbors);
        }
    }

    return $filtered;
}


function array_flatten($arrays) {
    if (count($arrays) === 0) {
        return [];
    }

    return call_user_func_array('array_merge', $arrays);
}


function array_filter_keys($array, $keys) {
    return array_intersect_key($array, array_flip($keys));
}


function array_values_from_keys($array, $keys) {
    return array_values(array_filter_keys($array, $keys));
}
