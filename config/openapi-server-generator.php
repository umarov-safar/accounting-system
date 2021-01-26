<?php

return [

    /*
     * Path to the directory where index.yaml openapi file located
     */
    'apidoc_dir' => public_path('api-docs'),

    /*
     * Path to the directory where dto model files are generated
     * Matches the -o option in openapi generator
     */
    'temp_dir' => base_path('generated'),

    /*
     * Path relative to the app directory where dto models will be located
     */
    'destination_dir' => 'Http/Api{version}/OpenApiGenerated',

    /**
     * Directory where you can place templates to override default ones. . Used in -t
     */
    'template_dir' => resource_path('openapi_generator' . DIRECTORY_SEPARATOR . 'server_templates'),

    /*
     * Preserve only enums.
     */
    'only_enums_mode' => true,
];
