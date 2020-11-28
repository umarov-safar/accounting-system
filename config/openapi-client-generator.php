<?php

return [

    /*
     * Path to the directory where index.yaml openapi file located
     */
    'apidoc_dir' => public_path('api-docs'),

    /*
     * Dir template where client package will be generated
     */
    'output_dir_template' => base_path('clients'.DIRECTORY_SEPARATOR.'client'),

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
            'npmName' => '@ensi/backend-service-skeleton',
            'useES6' => true,
            'useSingleRequestParameter' => true,
            'withInterfaces' => true,
            'typescriptThreePlus' => true,
        ],

        /*
         * Need generate nest js module, only for backend services
         */
        'generate_nestjs_module' => true,
    ],

    /*
     * Args for generate php client
     */
    'php_args' => [
        /*
         * Package name for composer, use standard pattern namespace/package
         */
        'composer_name' => 'ensi/backend-service-skeleton',

        /*
         * Specific generator params from https://openapi-generator.tech/docs/generators/php/
         */
        'params' => [
            'apiPackage' => 'Api',
            'invokerPackage' => 'Ensi\BackendServiceSkeleton',
            'modelPackage' => 'Dto',
            'packageName' => 'BackendServiceSkeletonClient',
        ],
    ],
];
