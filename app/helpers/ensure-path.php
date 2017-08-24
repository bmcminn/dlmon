<?php


/**
 * Determine if the given directory path exists and create it if not
 * @return string Directory path to validate and create
 */
function ensurePath($path) {

    if (is_dir($path)) {
        return $path;
    }

    mkdir($path);

    return $path;
}
