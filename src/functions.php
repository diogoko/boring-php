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


/**
 * If this flag is passed to the `preg_match_pattern_array` function, each key of the patterns
 * array will be used as a regular expression to be searched.
 *
 * This flag cannot be used together with the `PREG_MATCH_VALUE` flag.
 */
define('PREG_MATCH_KEY', 1);


/**
 * If this flag is passed to the `preg_match_pattern_array` function, each value of the patterns
 * array will be used as a regular expression to be searched.
 *
 * This flag cannot be used together with the `PREG_MATCH_KEY` flag.
 */
define('PREG_MATCH_VALUE', 0);


/**
 * If this flag is passed to the `preg_match_pattern_array` function, the key of the patterns
 * array will be returned if the corresponding regular expression matches.
 *
 * This flag cannot be used together with the `PREG_RETURN_VALUE` flag.
 */
define('PREG_RETURN_KEY', 2);


/**
 * If this flag is passed to the `preg_match_pattern_array` function, the value of the patterns
 * array will be returned if the corresponding regular expression matches.
 *
 * This flag cannot be used together with the `PREG_RETURN_KEY` flag.
 */
define('PREG_RETURN_VALUE', 0);


/**
 * Perform multiple regular expression matches.
 *
 * @param string[] $patterns The patterns to be searched for
 * @param string $subject The input string
 * @param array $matches If matches is provided, then it is filled
 *                       with the results of search just like the `preg_match` function
 * @param int $flags Can be `PREG_OFFSET_CAPTURE` combined with `PREG_MATCH_KEY` (or `PREG_MATCH_VALUE`) combined with `PREG_RETURN_KEY` (or `PREG_RETURN_VALUE`)
 * @param int $offset The alternate place from which to start the search (in bytes)
 * @return mixed The first pattern that matched according to the flags, or null if there was no match
 */
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


/**
 * Get a value from the session, initializing the session if needed 
 * and handling non-existing keys.
 *
 * Beware that this function returns null for non-existing keys.
 *
 * @param string $key The key of the value
 * @return mixed The value of the key in the session or null if not found
 */
function session_get($key) {
    if (!session_id()) {
        session_start();
    }
    
    return $_SESSION[$key] ?? null;
}


/**
 * Set a value in the session, initializing the session if needed.
 *
 * @param string $key The key to be used to store the value
 * @param mixed $value The value to be stored
 */
function session_put($key, $value) {
    if (!session_id()) {
        session_start();
    }

    $_SESSION[$key] = $value;
}


/**
 * Delete a value from the session.
 *
 * Beware that this function returns null for non-existing keys.
 *
 * @param string $key The key of the value to be deleted
 * @return mixed The value of the key that was deleted
 */
function session_delete($key) {
    $value = session_get($key);
    unset($_SESSION[$key]);
    
    return $value;
}


/**
 * Calculate a topological sort of a dependency graph.
 *
 * The dependency graph is encoded as an adjacency list in which each
 * key is a node and each value is the array of nodes that the key depends
 * on. Only strings are supported as nodes.
 *
 * @param array $graph The dependency graph to be analysed
 * @return array A sequence of nodes where a node's dependencies are always before
 *               the node itself
 */
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


/**
 * Create a subgraph from a dependency graph that contains only some
 * of the nodes and their dependencies.
 *
 * The dependency graph is encoded as an adjacency list in which each
 * key is a node and each value is the array of nodes that the key depends
 * on. Only strings are supported as nodes.
 *
 * @param array $graph The dependency graph to be analysed
 * @param array $deps The list of nodes to be included in the subgraph
 * @return array The subgraph containing only the nodes of the $deps parameter
 *               and their dependencies (including transitive)
 */
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


/**
 * Flatten a list of arrays into one continuous array.
 *
 * @param array $arrays The array of arrays
 * @return array An array that is the concatenation of all arrays
 */
function array_flatten($arrays) {
    if (count($arrays) === 0) {
        return [];
    }

    return call_user_func_array('array_merge', $arrays);
}


/**
 * Filter an array by its keys.
 *
 * @param array $array The array to be filtered
 * @param array $keys The keys that are to be kept
 * @return array The array containing only the filtered keys and their values
 */
function array_filter_keys($array, $keys) {
    return array_intersect_key($array, array_flip($keys));
}


/**
 * Return all the values of an array by their keys.
 *
 * @param array The array to be filtered
 * @param array The list of keys to be kept
 * @return array The values whose keys matched the list
 */
function array_values_from_keys($array, $keys) {
    return array_values(array_filter_keys($array, $keys));
}
