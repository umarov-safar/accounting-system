<?php

return [

    /*
     * Path to the directory where index.yaml openapi file located
     */
    'apidoc_dir' => public_path('api-docs/v1'),

    /*
     * Dir template where client package will be generated
     */
    'output_dir_template' => env('OPENAPI_CLIENT_OUTPUT_DIR') ?: base_path('clients' . DIRECTORY_SEPARATOR . 'client'),

    /*
     * Git user
     */
    'git_user' => 'greensight/ensi',

    /*
     * Git repository name template
     */
    'git_repo_template' => 'backend-service-skeleton-client',

    /*
     * Git host
     */
    'git_host' => 'gitlab.com',

    /*
     * Args for generate nodejs client
     */
    'js_args' => [
        /*
         * Specific generator params from https://openapi-generator.tech/docs/generators/typescript-fetch/
         */
        'params' => [
            'npmName' => '@ensi/backend-service-skeleton-client',
            'useES6' => true,
            'useSingleRequestParameter' => true,
            'withInterfaces' => true,
            'typescriptThreePlus' => true,
        ],

        /*
         * Need generate nest js module, only for backend services
         */
        'generate_nestjs_module' => true,

        'files_to_ignore_during_cleanup' => ['.git', '.gitignore'],
    ],

    /*
     * Args for generate php client
     */
    'php_args' => [
        /*
         * Package name for composer, use standard pattern namespace/package
         */
        'composer_name' => 'ensi/backend-service-skeleton-client',

        /*
         * Specific generator params from https://openapi-generator.tech/docs/generators/php/
         */
        'params' => [
            'apiPackage' => 'Api',
            'invokerPackage' => 'Ensi\BackendServiceSkeletonClient',
            'modelPackage' => 'Dto',
            'packageName' => 'BackendServiceSkeletonClient',
        ],


        'laravel_package_config_key' => 'openapi-clients.backend-service-skeleton',

        'files_to_ignore_during_cleanup' => ['.git', '.gitignore'],

        /**
         * Directory where you can place templates to override default ones. Used in -t
         */
        'template_dir' => base_path('vendor/ensi/openapi-client-generator-php-templates/templates'),
    ],
];
