<?php

/**
 * Redirects the user to a given page; aliaas of the built-in header() function
 * @param  string $route Desired route to send the user to
 * @return null
 */
function redirect($route) {
    header("location: ${route}");
}
