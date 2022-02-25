<?php

namespace App\JsonApi\Tags;

use App\Models\Currency;
use App\Models\Tag;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    protected $resourceType = 'tags';

    /**
     * @param Tag $resource
     */
    public function getId($resource): string
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param Tag $resource
     */
    public function getAttributes($resource): array
    {
        return [
            'name' => $resource->name,
            'description' => $resource->description,
            'link' => $resource->link,
            'progress' => $resource->object->userTag->progress,
            'amount' => $resource->object->userTag->data['amount'] ?? null,
        ];
    }

    /**
     * @param Tag $tag
     */
    public function getRelationships($tag, $isPrimary, array $includeRelationships): array
    {
        return [
            'currency' => [
                self::SHOW_RELATED => false,
                self::DATA => function () use ($tag) {
                    return Currency::find(config('currency.bonus_base_currency.id'));
                },
            ],
        ];
    }
}
