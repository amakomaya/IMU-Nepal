<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    // Include to pre-defined routes from package or not. Middlewares
    'use_package_routes' => true,

    // Middlewares which should be applied to all package routes.
    // For laravel 5.1 and before, remove 'web' from the array.
    'middlewares' => ['web', 'auth'],

    // The url to this package. Change it if necessary.
    'url_prefix' => 'laravel-filemanager',

    /*
    |--------------------------------------------------------------------------
    | Multi-User Mode
    |--------------------------------------------------------------------------
    */

    // If true, private folders will be created for each signed-in user.
    'allow_multi_user' => true,
    // If true, share folder will be created when allow_multi_user is true.
    'allow_share_folder' => true,

    // Flexible way to customize client folders accessibility
    // If you want to customize client folders, publish tag="lfm_handler"
    // Then you can rewrite userField function in App\Handler\ConfigHander class
    // And set 'user_field' to App\Handler\ConfigHander::class
    // Ex: The private folder of user will be named as the user id.
    'user_field' => UniSharp\LaravelFilemanager\Handlers\ConfigHandler::class,

    /*
    |--------------------------------------------------------------------------
    | Working Directory
    |--------------------------------------------------------------------------
    */

    // Which folder to store files in project, fill in 'public', 'resources', 'storage' and so on.
    // You should create routes to serve images if it is not set to public.
    'base_directory' => 'public',

    'images_folder_name' => 'photos',
    'files_folder_name'  => 'files',

    'shared_folder_name' => 'shares',
    'thumb_folder_name'  => 'thumbs',

    /*
    |--------------------------------------------------------------------------
    | Startup Views
    |--------------------------------------------------------------------------
    */

    // The default display type for items.
    // Supported: "grid", "list"
    'images_startup_view' => 'grid',
    'files_startup_view' => 'list',

    /*
    |--------------------------------------------------------------------------
    | Upload / Validation
    |--------------------------------------------------------------------------
    */

    // If true, the uploaded file will be renamed to uniqid() + file extension.
    'rename_file' => false,

    // If rename_file set to false and this set to true, then non-alphanumeric characters in filename will be replaced.
    'alphanumeric_filename' => false,

    // If true, non-alphanumeric folder name will be rejected.
    'alphanumeric_directory' => false,

    // If true, the uploading file's size will be verified for over than max_image_size/max_file_size.
    'should_validate_size' => false,

    'max_image_size' => 50000,
    'max_file_size' => 900000,

    // If true, the uploading file's mime type will be valid in valid_image_mimetypes/valid_file_mimetypes.
    'should_validate_mime' => false,

    // available since v1.3.0
    'valid_image_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/gif',
        'image/svg+xml',
    ],

    // If true, image thumbnails would be created during upload
    'should_create_thumbnails' => true,

    // Create thumbnails automatically only for listed types.
    'raster_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
    ],

    // permissions to be set when create a new folder or when it creates automatically with thumbnails
    'create_folder_mode' => 0755,

    // permissions to be set on file upload.
    'create_file_mode' => 0644,
    
    // If true, it will attempt to chmod the file after upload
    'should_change_file_mode' => true,

    // available since v1.3.0
    // only when '/laravel-filemanager?type=Files'
    'valid_file_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/gif',
        'image/svg+xml',
        'application/pdf',
        'text/plain',
        'text/csv',
        'video/mp4',
        'video/quicktime',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/docx',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/x-htmlmail-template',
        'application/vnd.google-earth.kml+xml kml',
        'application/x-decomail-template',
        'application/x-kddi-htmlmail',
        'application/x-htmlmail-template',
        'application/andrew-inset',
        'application/atom+xml',
        'application/mac-binhex40',
        'application/mac-compactpro',
        'application/mathml+xml',
        'application/msword',
        'application/octet-stream',
        'application/oda',
        'application/ogg',
        'application/pdf',
        'application/postscript',
        'application/rdf+xml',
        'application/smil',
        'application/srgs',
        'application/srgs+xml',
        'application/vnd.mif',
        'application/vnd.ms-excel',
        'application/vnd.ms-powerpoint',
        'application/vnd.rn-realmedia',
        'application/vnd.wap.wbxml',
        'application/vnd.wap.wmlc',
        'application/vnd.wap.wmlscriptc',
        'application/voicexml+xml',
        'application/x-bcpio',
        'application/x-cdlink',
        'application/x-chess-pgn',
        'application/x-cpio',
        'application/x-csh',
        'application/x-director',
        'application/x-dvi',
        'application/x-futuresplash',
        'application/x-gtar',
        'application/x-hdf',
        'application/xhtml+xml',
        'application/x-ipix',
        'application/x-ipscript',
        'application/x-javascript',
        'application/x-koan',
        'application/x-latex',
        'application/xml',
        'application/xml',
        'application/xml-dtd',
        'application/x-mpeg',
        'application/x-netcdf',
        'application/x-sh',
        'application/x-shar',
        'application/x-shockwave-flash',
        'application/xslt+xml',
        'application/x-smaf',
        'application/x-stuffit',
        'application/x-sv4cpio',
        'application/x-sv4crc',
        'application/x-tar',
        'application/x-tcl',
        'application/x-tex',
        'application/x-texinfo',
        'application/x-troff',
        'application/x-troff-man',
        'application/x-troff-me',
        'application/x-troff-ms',
        'application/x-ustar',
        'application/x-wais-source',
        'application/x-xp',
        'application/zip',
        'audio/3gpp2',
        'audio/basic',
        'audio/midi',
        'audio/mp4',
        'audio/mpeg',
        'audio/x-aiff',
        'audio/x-mpegurl',
        'audio/x-ms-wma',
        'audio/x-pn-realaudio',
        'audio/x-wav',
        'chemical/x-pdb',
        'chemical/x-xyz',
        'image/bmp',
        'image/cgm',
        'image/gif',
        'image/gif',
        'image/ief',
        'image/jpeg',
        'image/jpeg',
        'image/png',
        'image/png',
        'image/svg+xml',
        'image/tiff',
        'image/vnd.djvu',
        'image/vnd.wap.wbmp',
        'image/x-cmu-raster',
        'image/x-icon',
        'image/x-portable-anymap',
        'image/x-portable-bitmap',
        'image/x-portable-graymap',
        'image/x-portable-pixmap',
        'image/x-rgb',
        'image/x-xbitmap',
        'image/x-xpixmap',
        'image/x-xwindowdump',
        'model/iges',
        'model/mesh',
        'model/vrml',
        'text/calendar',
        'text/css',
        'text/html',
        'text/plain',
        'text/richtext',
        'text/rtf',
        'text/sgml',
        'text/tab-separated-values',
        'text/vnd.wap.wml',
        'text/vnd.wap.wmlscript',
        'text/x-hdml;charset=Shift_JIS',
        'text/x-setext',
        'video/3gpp',
        'video/3gpp2',
        'video/mpeg',
        'video/quicktime',
        'video/vnd.mpegurl',
        'video/x-ms-asf',
        'video/x-msvideo',
        'video/x-ms-wmv',
        'video/x-sgi-movie',
        'x-conference/x-cooltalk',
        'application/octet-stream',
        'application/octet-stream ucm',
        'application/octet-stream xcsf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document docx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet xlsx',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation pptx',
        'video/x-flv',
        'application/java-archive',
        'video/mp4',
        '.db'
    ],

    /*
    |--------------------------------------------------------------------------
    | Image / Folder Setting
    |--------------------------------------------------------------------------
    */

    'thumb_img_width' => 200,
    'thumb_img_height' => 200,

    /*
    |--------------------------------------------------------------------------
    | File Extension Information
    |--------------------------------------------------------------------------
    */

    'file_type_array' => [
        'pdf'  => 'Adobe Acrobat',
        'doc'  => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls'  => 'Microsoft Excel',
        'xlsx' => 'Microsoft Excel',
        'zip'  => 'Archive',
        'gif'  => 'GIF Image',
        'jpg'  => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png'  => 'PNG Image',
        'ppt'  => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
    ],

    'file_icon_array' => [
        'pdf'  => 'fa-file-pdf-o',
        'doc'  => 'fa-file-word-o',
        'docx' => 'fa-file-word-o',
        'xls'  => 'fa-file-excel-o',
        'xlsx' => 'fa-file-excel-o',
        'zip'  => 'fa-file-archive-o',
        'gif'  => 'fa-file-image-o',
        'jpg'  => 'fa-file-image-o',
        'jpeg' => 'fa-file-image-o',
        'png'  => 'fa-file-image-o',
        'ppt'  => 'fa-file-powerpoint-o',
        'pptx' => 'fa-file-powerpoint-o',
    ],

    /*
    |--------------------------------------------------------------------------
    | php.ini override
    |--------------------------------------------------------------------------
    |
    | These values override your php.ini settings before uploading files
    | Set these to false to ingnore and apply your php.ini settings
    |
    | Please note that the 'upload_max_filesize' & 'post_max_size'
    | directives are not supported.
    */
    'php_ini_overrides' => [
        'memory_limit'        => '256M',
    ],

];
