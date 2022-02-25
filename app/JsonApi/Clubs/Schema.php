<?php

namespace App\JsonApi\Clubs;

use App\Helpers\ImageHelper;
use App\Models\Club;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    protected $resourceType = 'clubs';

    public function __construct(SchemaFactoryInterface $factory, protected EncodingParametersInterface $parameters)
    {
        parent::__construct($factory);
    }

    /**
     * @param Club $resource
     */
    public function getId($resource): string
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param Club $resource
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->name,
            'image' => ImageHelper::getFullImageUrl(Club::IMG_PATH, $resource->image),
        ];
    }
}
