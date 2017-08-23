<?php

use Webmozart\PathUtil\Path;

// Check for the filetypes config
$mimetypeConfigPath = Path::join(CONFIGS_DIR, 'mimetype.config.php');

if (!file_exists($mimetypeConfigPath)) {
    $mimetypes = getMimeTypes();

    $res = join("\n", [
        '<?php'
    ,   'return ['
    ,   join(",\n", $mimetypes)
    ,   '];'
    ]);

    file_put_contents($mimetypeConfigPath, $res);
}
