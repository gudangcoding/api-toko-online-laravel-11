<?php

namespace App\Docs;

use Dedoc\Scramble\Contracts\DocumentTransformer;
use Dedoc\Scramble\OpenApiContext;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class ScrambleSecurityTransformer implements DocumentTransformer
{
    public function handle(OpenApi $document, OpenApiContext $context)
    {
        // Add a global bearer auth security scheme and mark it as default security requirement
        $document->secure(
            SecurityScheme::http('bearer', 'JWT')
                ->as('bearerAuth')
                ->setDescription('Bearer token for API authentication (Authorization: Bearer <token>)')
                ->default()
        );
    }
}
