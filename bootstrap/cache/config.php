<?php return array (
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'cluster' => NULL,
          'host' => 'api-mt1.pusher.com',
          'port' => 443,
          'scheme' => 'https',
          'encrypted' => true,
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'concurrency' => 
  array (
    'default' => 'process',
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => '12',
      'verify' => true,
      'limit' => NULL,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
      'verify' => true,
    ),
    'rehash_on_login' => true,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/data/data/com.termux/files/home/mithaqschool/resources/views',
    ),
    'compiled' => '/data/data/com.termux/files/home/mithaqschool/storage/framework/views',
  ),
  'Permissions' => 
  array (
    'default_roles' => 
    array (
      'super-admin' => 'مدير النظام',
      'admin' => 'مدير المدرسة',
      'teacher' => 'معلم',
      'student' => 'طالب',
      'guardian' => 'ولي أمر',
    ),
    'role_permissions' => 
    array (
      'super-admin' => '*',
      'admin' => 
      array (
        0 => 'view_any_user',
        1 => 'view_user',
        2 => 'create_user',
        3 => 'update_user',
        4 => 'delete_user',
        5 => 'view_any_role',
        6 => 'view_role',
        7 => 'create_role',
        8 => 'update_role',
        9 => 'delete_role',
        10 => 'view_any_permission',
        11 => 'view_permission',
        12 => 'assign_permission',
        13 => 'view_any_student',
        14 => 'view_student',
        15 => 'create_student',
        16 => 'update_student',
        17 => 'delete_student',
        18 => 'view_any_teacher',
        19 => 'view_teacher',
        20 => 'create_teacher',
        21 => 'update_teacher',
        22 => 'delete_teacher',
        23 => 'view_any_guardian',
        24 => 'view_guardian',
        25 => 'create_guardian',
        26 => 'update_guardian',
        27 => 'delete_guardian',
        28 => 'view_any_academic_year',
        29 => 'create_academic_year',
        30 => 'update_academic_year',
        31 => 'delete_academic_year',
        32 => 'view_any_semester',
        33 => 'create_semester',
        34 => 'update_semester',
        35 => 'delete_semester',
        36 => 'view_any_school_stage',
        37 => 'create_school_stage',
        38 => 'update_school_stage',
        39 => 'delete_school_stage',
        40 => 'view_any_grade',
        41 => 'create_grade',
        42 => 'update_grade',
        43 => 'delete_grade',
        44 => 'view_any_classroom',
        45 => 'create_classroom',
        46 => 'update_classroom',
        47 => 'delete_classroom',
        48 => 'view_any_course',
        49 => 'create_course',
        50 => 'update_course',
        51 => 'delete_course',
        52 => 'assign_teacher_to_course',
        53 => 'view_any_exam',
        54 => 'create_exam',
        55 => 'update_exam',
        56 => 'delete_exam',
        57 => 'view_any_exam_type',
        58 => 'create_exam_type',
        59 => 'update_exam_type',
        60 => 'delete_exam_type',
        61 => 'view_any_exam_result',
        62 => 'create_exam_result',
        63 => 'update_exam_result',
        64 => 'delete_exam_result',
        65 => 'view_any_attendance',
        66 => 'take_attendance',
        67 => 'update_attendance',
        68 => 'view_any_homework',
        69 => 'create_homework',
        70 => 'update_homework',
        71 => 'delete_homework',
        72 => 'view_any_homework_submission',
        73 => 'grade_homework_submission',
        74 => 'view_any_schedule',
        75 => 'create_schedule',
        76 => 'update_schedule',
        77 => 'delete_schedule',
        78 => 'send_message',
        79 => 'view_message',
        80 => 'send_notification',
        81 => 'view_notification',
        82 => 'view_any_behavior_evaluation',
        83 => 'create_behavior_evaluation',
        84 => 'update_behavior_evaluation',
        85 => 'delete_behavior_evaluation',
        86 => 'view_reports',
        87 => 'generate_student_report',
        88 => 'generate_financial_report',
        89 => 'generate_attendance_report',
        90 => 'view_any_invoice',
        91 => 'create_invoice',
        92 => 'update_invoice',
        93 => 'delete_invoice',
        94 => 'manage_fees',
        95 => 'view_admin_dashboard',
        96 => 'manage_general_settings',
        97 => 'manage_school_settings',
        98 => 'view_system_logs',
        99 => 'perform_backup',
        100 => 'export_data',
        101 => 'import_data',
        102 => 'clear_cache',
      ),
      'teacher' => 
      array (
        0 => 'view_any_student',
        1 => 'view_student',
        2 => 'view_any_course',
        3 => 'view_course',
        4 => 'take_attendance',
        5 => 'update_attendance',
        6 => 'view_any_exam',
        7 => 'create_exam',
        8 => 'update_exam',
        9 => 'view_any_exam_result',
        10 => 'create_exam_result',
        11 => 'update_exam_result',
        12 => 'view_any_homework',
        13 => 'create_homework',
        14 => 'update_homework',
        15 => 'grade_homework_submission',
        16 => 'view_any_monthly_grade',
        17 => 'create_monthly_grade',
        18 => 'update_monthly_grade',
        19 => 'view_any_semester_grade',
        20 => 'create_semester_grade',
        21 => 'update_semester_grade',
        22 => 'view_any_final_grade',
        23 => 'create_final_grade',
        24 => 'update_final_grade',
        25 => 'view_any_behavior_evaluation',
        26 => 'create_behavior_evaluation',
        27 => 'update_behavior_evaluation',
        28 => 'send_message',
        29 => 'view_message',
        30 => 'send_notification',
        31 => 'view_schedule',
        32 => 'view_teacher_dashboard',
      ),
      'student' => 
      array (
        0 => 'view_student_dashboard',
        1 => 'view_schedule',
        2 => 'submit_homework',
        3 => 'view_message',
        4 => 'view_notification',
        5 => 'view_own_grades',
        6 => 'view_own_attendance',
        7 => 'view_own_invoice',
      ),
      'guardian' => 
      array (
        0 => 'view_parent_dashboard',
        1 => 'view_children_grades',
        2 => 'view_children_attendance',
        3 => 'view_children_homework',
        4 => 'view_children_invoices',
        5 => 'view_schedule',
        6 => 'send_message',
        7 => 'view_message',
        8 => 'view_notification',
        9 => 'process_payment',
      ),
    ),
    'cache' => 
    array (
      'enabled' => true,
      'ttl' => 86400,
      'key' => 'user_permissions',
    ),
    'middleware' => 
    array (
      'check_permission' => 
      array (
        'redirect_to' => '/',
        'message' => 'غير مصرح لك بالوصول إلى هذه الصفحة',
      ),
      'check_role' => 
      array (
        'redirect_to' => '/',
        'message' => 'غير مصرح لك بالوصول إلى هذه الصفحة',
      ),
    ),
  ),
  'app' => 
  array (
    'name' => 'MithaqSchool',
    'env' => 'production',
    'debug' => true,
    'url' => 'https://mithaqschool-api.onrender.com',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'Asia/Riyadh',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:9ATC/+uLL+2jL+rx/MUPgF5PUgNI4C9E4/SplzMdM9M=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
      'store' => 'database',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Spatie\\Permission\\PermissionServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
      24 => 'App\\Providers\\AuthServiceProvider',
      25 => 'App\\Providers\\EventServiceProvider',
      26 => 'App\\Providers\\RouteServiceProvider',
      27 => 'App\\Providers\\AppServiceProvider',
      28 => 'App\\Providers\\NavigationServiceProvider',
      29 => 'App\\Providers\\ReportServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'sanctum',
        'provider' => 'users',
        'hash' => false,
      ),
      'sanctum' => 
      array (
        'driver' => 'sanctum',
        'provider' => NULL,
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'session' => 
      array (
        'driver' => 'session',
        'key' => '_cache',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
        'table' => 'cache',
        'lock_connection' => NULL,
        'lock_table' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/data/data/com.termux/files/home/mithaqschool/storage/framework/cache/data',
        'lock_path' => '/data/data/com.termux/files/home/mithaqschool/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'stores' => 
        array (
          0 => 'database',
          1 => 'array',
        ),
      ),
    ),
    'prefix' => 'mithaqschool-cache-',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
      2 => 'broadcasting/auth',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => 'http://localhost:5173',
      1 => 'http://127.0.0.1:5173',
    ),
    'allowed_origins_patterns' => 
    array (
      0 => '#^https?://localhost(:\\d+)?$#',
      1 => '#^https?://127\\.0\\.0\\.1(:\\d+)?$#',
      2 => '#^https?://192\\.168\\.\\d{1,3}\\.\\d{1,3}(:\\d+)?$#',
      3 => '#^https?://10\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}(:\\d+)?$#',
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => true,
  ),
  'database' => 
  array (
    'default' => 'pgsql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'postgres',
        'prefix' => '',
        'foreign_key_constraints' => true,
        'busy_timeout' => NULL,
        'journal_mode' => NULL,
        'synchronous' => NULL,
        'transaction_mode' => 'DEFERRED',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => 'db.cfnrjbytkjotppmioyyj.supabase.co',
        'port' => '5432',
        'database' => 'postgres',
        'username' => 'postgres',
        'password' => 'PiNC7t%swd?K(fk',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => 'db.cfnrjbytkjotppmioyyj.supabase.co',
        'port' => '5432',
        'database' => 'postgres',
        'username' => 'postgres',
        'password' => 'PiNC7t%swd?K(fk',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => 'db.cfnrjbytkjotppmioyyj.supabase.co',
        'port' => '5432',
        'database' => 'postgres',
        'username' => 'postgres',
        'password' => 'PiNC7t%swd?K(fk',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'require',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => 'db.cfnrjbytkjotppmioyyj.supabase.co',
        'port' => '5432',
        'database' => 'postgres',
        'username' => 'postgres',
        'password' => 'PiNC7t%swd?K(fk',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 
    array (
      'table' => 'migrations',
      'update_date_on_publish' => true,
    ),
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'mithaqschool-database-',
        'persistent' => false,
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'public',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/data/data/com.termux/files/home/mithaqschool/storage/app',
        'serve' => true,
        'throw' => true,
        'report' => true,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/data/data/com.termux/files/home/mithaqschool/storage/app/public',
        'url' => 'https://mithaqschool-api.onrender.com/storage',
        'visibility' => 'public',
        'throw' => false,
        'report' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
        'report' => false,
      ),
      'avatars' => 
      array (
        'driver' => 'local',
        'root' => '/data/data/com.termux/files/home/mithaqschool/storage/app/public/avatars',
        'url' => 'https://mithaqschool-api.onrender.com/storage/avatars',
        'visibility' => 'public',
        'throw' => false,
      ),
      'uploads' => 
      array (
        'driver' => 'local',
        'root' => '/data/data/com.termux/files/home/mithaqschool/storage/app/public/uploads',
        'url' => 'https://mithaqschool-api.onrender.com/storage/uploads',
        'visibility' => 'public',
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      '/data/data/com.termux/files/home/mithaqschool/public/storage' => '/data/data/com.termux/files/home/mithaqschool/storage/app/public',
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => NULL,
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => '/data/data/com.termux/files/home/mithaqschool/storage/logs/laravel.log',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => '/data/data/com.termux/files/home/mithaqschool/storage/logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
        'replace_placeholders' => true,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'handler_with' => 
        array (
          'stream' => 'php://stderr',
        ),
        'formatter' => NULL,
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
        'facility' => 8,
        'replace_placeholders' => true,
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => '/data/data/com.termux/files/home/mithaqschool/storage/logs/laravel.log',
      ),
      'activity' => 
      array (
        'driver' => 'daily',
        'path' => '/data/data/com.termux/files/home/mithaqschool/storage/logs/activity.log',
        'level' => 'info',
        'days' => 30,
        'replace_placeholders' => true,
      ),
      'permissions' => 
      array (
        'driver' => 'daily',
        'path' => '/data/data/com.termux/files/home/mithaqschool/storage/logs/permissions.log',
        'level' => 'info',
        'days' => 30,
        'replace_placeholders' => true,
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'log',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'scheme' => NULL,
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '2525',
        'username' => NULL,
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => 'mithaqschool-api.onrender.com',
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
        'retry_after' => 60,
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
        'retry_after' => 60,
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'MithaqSchool',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/data/data/com.termux/files/home/mithaqschool/resources/views/vendor/mail',
      ),
    ),
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'App\\Models\\Permission',
      'role' => 'App\\Models\\Role',
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'role_pivot_key' => NULL,
      'permission_pivot_key' => NULL,
      'model_morph_key' => 'model_id',
      'team_foreign_key' => 'team_id',
    ),
    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'events_enabled' => true,
    'teams' => false,
    'team_resolver' => 'Spatie\\Permission\\DefaultTeamResolver',
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => true,
    'display_role_in_exception' => true,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      \DateInterval::__set_state(array(
         'from_string' => true,
         'date_string' => '24 hours',
      )),
      'key' => 'spatie.permission.cache',
      'store' => 'file',
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
      'deferred' => 
      array (
        'driver' => 'deferred',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'connections' => 
        array (
          0 => 'database',
          1 => 'deferred',
        ),
      ),
      'background' => 
      array (
        'driver' => 'background',
      ),
    ),
    'batching' => 
    array (
      'database' => 'pgsql',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'pgsql',
      'table' => 'failed_jobs',
    ),
  ),
  'sanctum' => 
  array (
    'stateful' => 
    array (
      0 => 'https://mithaqschool-api.onrender.com',
      1 => 'localhost:5173',
      2 => '127.0.0.1',
      3 => '127.0.0.1:5173',
    ),
    'guard' => 
    array (
      0 => 'web',
    ),
    'expiration' => 10080,
    'token_prefix' => '',
    'middleware' => 
    array (
      'authenticate_session' => 'Laravel\\Sanctum\\Http\\Middleware\\AuthenticateSession',
      'encrypt_cookies' => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
      'validate_csrf_token' => 'Illuminate\\Foundation\\Http\\Middleware\\ValidateCsrfToken',
    ),
  ),
  'services' => 
  array (
    'postmark' => 
    array (
      'key' => NULL,
    ),
    'resend' => 
    array (
      'key' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
    'supabase' => 
    array (
      'url' => NULL,
      'key' => NULL,
      'bucket' => NULL,
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/data/data/com.termux/files/home/mithaqschool/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'mithaqschool-session',
    'path' => '/',
    'domain' => 'https://mithaqschool-api.onrender.com',
    'secure' => false,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'public_path' => NULL,
    'convert_entities' => true,
    'options' => 
    array (
      'font_dir' => '/data/data/com.termux/files/home/mithaqschool/storage/fonts',
      'font_cache' => '/data/data/com.termux/files/home/mithaqschool/storage/fonts',
      'temp_dir' => '/data/data/com.termux/files/usr/tmp',
      'chroot' => '/data/data/com.termux/files/home/mithaqschool',
      'allowed_protocols' => 
      array (
        'data://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'file://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'http://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'https://' => 
        array (
          'rules' => 
          array (
          ),
        ),
      ),
      'artifactPathValidation' => NULL,
      'log_output_file' => NULL,
      'enable_font_subsetting' => false,
      'pdf_backend' => 'CPDF',
      'default_media_type' => 'screen',
      'default_paper_size' => 'a4',
      'default_paper_orientation' => 'portrait',
      'default_font' => 'serif',
      'dpi' => 96,
      'enable_php' => false,
      'enable_javascript' => true,
      'enable_remote' => false,
      'allowed_remote_hosts' => NULL,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => true,
    ),
  ),
  'excel' => 
  array (
    'exports' => 
    array (
      'chunk_size' => 1000,
      'pre_calculate_formulas' => false,
      'strict_null_comparison' => false,
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => '
',
        'use_bom' => false,
        'include_separator_line' => false,
        'excel_compatibility' => false,
        'output_encoding' => '',
        'test_auto_detect' => true,
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
    ),
    'imports' => 
    array (
      'read_only' => true,
      'ignore_empty' => false,
      'heading_row' => 
      array (
        'formatter' => 'slug',
      ),
      'csv' => 
      array (
        'delimiter' => NULL,
        'enclosure' => '"',
        'escape_character' => '\\',
        'contiguous' => false,
        'input_encoding' => 'guess',
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
      'cells' => 
      array (
        'middleware' => 
        array (
        ),
      ),
    ),
    'extension_detector' => 
    array (
      'xlsx' => 'Xlsx',
      'xlsm' => 'Xlsx',
      'xltx' => 'Xlsx',
      'xltm' => 'Xlsx',
      'xls' => 'Xls',
      'xlt' => 'Xls',
      'ods' => 'Ods',
      'ots' => 'Ods',
      'slk' => 'Slk',
      'xml' => 'Xml',
      'gnumeric' => 'Gnumeric',
      'htm' => 'Html',
      'html' => 'Html',
      'csv' => 'Csv',
      'tsv' => 'Csv',
      'pdf' => 'Dompdf',
    ),
    'value_binder' => 
    array (
      'default' => 'Maatwebsite\\Excel\\DefaultValueBinder',
    ),
    'cache' => 
    array (
      'driver' => 'memory',
      'batch' => 
      array (
        'memory_limit' => 60000,
      ),
      'illuminate' => 
      array (
        'store' => NULL,
      ),
      'default_ttl' => 10800,
    ),
    'transactions' => 
    array (
      'handler' => 'db',
      'db' => 
      array (
        'connection' => NULL,
      ),
    ),
    'temporary_files' => 
    array (
      'local_path' => '/data/data/com.termux/files/home/mithaqschool/storage/framework/cache/laravel-excel',
      'local_permissions' => 
      array (
      ),
      'remote_disk' => NULL,
      'remote_prefix' => NULL,
      'force_resync_remote' => NULL,
    ),
  ),
  'scribe' => 
  array (
    'title' => 'MithaqSchool API Documentation',
    'description' => '',
    'intro_text' => '    This documentation aims to provide all the information you need to work with our API.

    <aside>As you scroll, you\'ll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
    You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>',
    'base_url' => 'https://mithaqschool-api.onrender.com',
    'routes' => 
    array (
      0 => 
      array (
        'match' => 
        array (
          'prefixes' => 
          array (
            0 => 'api/*',
          ),
          'domains' => 
          array (
            0 => '*',
          ),
        ),
        'include' => 
        array (
        ),
        'exclude' => 
        array (
        ),
      ),
    ),
    'type' => 'laravel',
    'theme' => 'default',
    'static' => 
    array (
      'output_path' => 'public/docs',
    ),
    'laravel' => 
    array (
      'add_routes' => true,
      'docs_url' => '/docs',
      'assets_directory' => NULL,
      'middleware' => 
      array (
      ),
    ),
    'external' => 
    array (
      'html_attributes' => 
      array (
      ),
    ),
    'try_it_out' => 
    array (
      'enabled' => true,
      'base_url' => NULL,
      'use_csrf' => false,
      'csrf_url' => '/sanctum/csrf-cookie',
    ),
    'auth' => 
    array (
      'enabled' => false,
      'default' => false,
      'in' => 'bearer',
      'name' => 'key',
      'use_value' => NULL,
      'placeholder' => '{YOUR_AUTH_KEY}',
      'extra_info' => 'You can retrieve your token by visiting your dashboard and clicking <b>Generate API token</b>.',
    ),
    'example_languages' => 
    array (
      0 => 'bash',
      1 => 'javascript',
    ),
    'postman' => 
    array (
      'enabled' => true,
      'overrides' => 
      array (
      ),
    ),
    'openapi' => 
    array (
      'enabled' => true,
      'version' => '3.0.3',
      'overrides' => 
      array (
      ),
      'generators' => 
      array (
      ),
    ),
    'groups' => 
    array (
      'default' => 'Endpoints',
      'order' => 
      array (
      ),
    ),
    'logo' => false,
    'last_updated' => 'Last updated: {date:F j, Y}',
    'examples' => 
    array (
      'faker_seed' => 1234,
      'models_source' => 
      array (
        0 => 'factoryCreate',
        1 => 'factoryMake',
        2 => 'databaseFirst',
      ),
    ),
    'strategies' => 
    array (
      'metadata' => 
      array (
        0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Metadata\\GetFromDocBlocks',
        1 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Metadata\\GetFromMetadataAttributes',
      ),
      'headers' => 
      array (
        0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Headers\\GetFromHeaderAttribute',
        1 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Headers\\GetFromHeaderTag',
        2 => 
        array (
          0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\StaticData',
          1 => 
          array (
            'only' => 
            array (
            ),
            'except' => 
            array (
            ),
            'data' => 
            array (
              'Content-Type' => 'application/json',
              'Accept' => 'application/json',
            ),
          ),
        ),
      ),
      'urlParameters' => 
      array (
        0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\UrlParameters\\GetFromLaravelAPI',
        1 => 'Knuckles\\Scribe\\Extracting\\Strategies\\UrlParameters\\GetFromUrlParamAttribute',
        2 => 'Knuckles\\Scribe\\Extracting\\Strategies\\UrlParameters\\GetFromUrlParamTag',
      ),
      'queryParameters' => 
      array (
        0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\QueryParameters\\GetFromFormRequest',
        1 => 'Knuckles\\Scribe\\Extracting\\Strategies\\QueryParameters\\GetFromInlineValidator',
        2 => 'Knuckles\\Scribe\\Extracting\\Strategies\\QueryParameters\\GetFromQueryParamAttribute',
        3 => 'Knuckles\\Scribe\\Extracting\\Strategies\\QueryParameters\\GetFromQueryParamTag',
      ),
      'bodyParameters' => 
      array (
        0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\BodyParameters\\GetFromFormRequest',
        1 => 'Knuckles\\Scribe\\Extracting\\Strategies\\BodyParameters\\GetFromInlineValidator',
        2 => 'Knuckles\\Scribe\\Extracting\\Strategies\\BodyParameters\\GetFromBodyParamAttribute',
        3 => 'Knuckles\\Scribe\\Extracting\\Strategies\\BodyParameters\\GetFromBodyParamTag',
      ),
      'responses' => 
      array (
        0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\UseResponseAttributes',
        1 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\UseTransformerTags',
        2 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\UseApiResourceTags',
        3 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\UseResponseTag',
        4 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\UseResponseFileTag',
        5 => 
        array (
          0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\Responses\\ResponseCalls',
          1 => 
          array (
            'only' => 
            array (
              0 => 'GET *',
            ),
            'except' => 
            array (
            ),
            'config' => 
            array (
              'app.debug' => false,
            ),
            'queryParams' => 
            array (
            ),
            'bodyParams' => 
            array (
            ),
            'fileParams' => 
            array (
            ),
            'cookies' => 
            array (
            ),
          ),
        ),
      ),
      'responseFields' => 
      array (
        0 => 'Knuckles\\Scribe\\Extracting\\Strategies\\ResponseFields\\GetFromResponseFieldAttribute',
        1 => 'Knuckles\\Scribe\\Extracting\\Strategies\\ResponseFields\\GetFromResponseFieldTag',
      ),
    ),
    'database_connections_to_transact' => 
    array (
      0 => 'pgsql',
    ),
    'fractal' => 
    array (
      'serializer' => NULL,
    ),
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
