<?php

namespace App\JsonApi\Currencies;

use App\Models\Currency;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    protected $resourceType = 'currencies';

    /**
     * @param Currency $resource
     */
    public function getId($resource): string
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param Currency $resource
     */
    public function getAttributes($resource): array
    {
        return [
            'code' => $resource->code,
            'name' => $resource->name,
            'minorUnits' => $resource->minor_units,
        ];
    }

}
