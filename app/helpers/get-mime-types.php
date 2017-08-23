<?php


/**
 * Generate an enumerable mapping of extensions and mimetypes using the Apache
 * @return array Associative collection of file extensions and their content mime-type
 */
function getMimeTypes() {

    // define the resource route
    $mimesListPath = 'http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types';

    // capture the data file and remove extra space on either end
    $mimesList = trim(file_get_contents($mimesListPath));

    // replace all tabs with spaces
    $mimesList = preg_replace('/\t+/', ' ', $mimesList);

    // break up the list into a collection
    $mimesList = explode("\n", $mimesList);

    // init our result collection
    $res = [];

    // iterate over mime type list to generate ext mappings
    foreach ($mimesList as $type) {

        // bug out on "commented" lines
        if (strpos($type, '#') === 0) {
            continue;
        }

        // breakup line text by spaces
        $parts = explode(' ', $type);

        // remove empty indexes
        $parts = array_filter($parts);

        // capture content-type string
        $contentType = $parts[0];

        // allocate all exts into a new collection
        $exts = array_slice($parts, 1);

        //
        foreach ($exts as $ext) {
            array_push($res, "'${ext}' => '${contentType}'");
        }

    }

    // Sort the mimetype list for readability/referencing
    natsort($res);

    return $res;

}
