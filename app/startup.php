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



// init database tables
class Q {

    private function __construct() {}

    public static function CREATE_TABLE($tableName, $tableProps) {
        return "CREATE TABLE IF NOT EXISTS {$tableName} ("
            .join(',', $tableProps)
            .');'
            ;
    }

}


$db->exec(Q::CREATE_TABLE('users', [
    'id        INTEGER PRIMARY KEY'
,   'email     TEXT NOT NULL'
,   'fullname  TEXT NOT NULL'
,   'password  TEXT NOT NULL'
,   'type      TEXT NOT NULL'
]));


$db->exec(Q::CREATE_TABLE('password_resets', [
    'id       INTEGER PRIMARY KEY'    // autoindex
,   'token    TEXT NOT NULL'          // TOKEN CREATED WHEN PASSWORD RESET WAS REQUESTED
,   'user_id  INTEGER NOT NULL'       // USER ID FOR PASSWORD RESET
,   'expires  INTEGER NOT NULL'       // UNIX EPOCH TIMESTAMP
,   'expired  INTEGER'                // BOOLEAN FLAG
]));


$db->exec(Q::CREATE_TABLE('files', [
    'id             INTEGER PRIMARY KEY'    // autoindex
,   'alias          TEXT NOT NULL'          // shortname for the file (ex: 'my-file.mp3' gets mapped as 'my-file-mp3' to avoid auto mime-type handling by the server)
,   'impressions    INTEGER NOT NULL'       // this value is updated anytime a user requests the file; userful for analytics down the line
,   'downloads      INTEGER'                // this value determines the total number of impressions that download can have before we cut off its access
,   'filepath       TEXT NOT NULL'          // the physical file path of the asset on the server
,   'mimetype       TEXT NOT NULL'          // the mimetype we can use to resolve how the file should be delivered
]));


$db->exec(Q::CREATE_TABLE('requests', [
    'id             INTEGER PRIMARY KEY'    // autoindex
,   'file_id        INTEGER NOT NULL'
,   'client_id      INTEGER NOT NULL'
,   'req_time       INTEGER NOT NULL'       // time in seconds
,   'client_ip      TEXT NOT NULL'          //
,   'proxy_ip       TEXT NOT NULL'          //
,   'real_ip        TEXT NOT NULL'          //
,   'request_url    TEXT NOT NULL'          // the full request URI string so we can see if folks are passing argument params or not
]));


$db->exec(Q::CREATE_TABLE('clients', [
    'id             INTEGER PRIMARY KEY'    // autoindex
,   'device         TEXT NOT NULL'          // a hash ID or "fingerprint" we use to ensure uniqueness
,   'user_agent     TEXT NOT NULL'
,   'remote_port    TEXT NOT NULL'
]));



// Check if there are no admins and force the user to setup one up
$stmt   = $db->query("SELECT * FROM users WHERE type='admin'");
$admins = $stmt->fetch(\PDO::FETCH_ASSOC);

if (empty($admins) && REQUEST_URI !== ROUTES['register_admin']) {
    $targetPath = ROUTES['register_admin'];
    logger("redirecting user to ${targetPath} to register new admin user.");
    redirect($targetPath);
}
