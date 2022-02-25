<?php

namespace App\JsonApi\Tags;

/**
 * @OA\Schema(
 *   schema="tags",
 *   @OA\Property(
 *     property="data",
 *     ref="#/components/schemas/tag"
 *   )
 * )
 */

/**
 * @OA\Schema(
 *   schema="tag",
 *   @OA\Property(
 *     property="data",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/tag-data")
 *   )
 * )
 */

/**
 * @OA\Schema(
 *     schema="tag-data",
 *     type="object",
 *     @OA\Property(
 *       property="type",
 *       type="string",
 *       example="tags"
 *     ),
 *     @OA\Property(
 *       property="id",
 *       type="string",
 *       example="2"
 *     ),
 *
 *     @OA\Property(
 *       property="attributes",
 *       @OA\Property(property="name", type="string", description="Name of tag", example="100% на депозит"),
 *       @OA\Property(property="description", type="string", description="Description of the tag", example="Бонус 100% на депозит"),
 *       @OA\Property(property="link", type="string", description="Context link to rules page", example="https://ya.ru/"),
 *       @OA\Property(property="progress", type="float", description="Tag Progress", example=0.94),
 *       @OA\Property(property="amount", type="float", description="Tag Progress", example=25.54)
 *     ),
 *
 *     @OA\Property(
 *       property="relationships",
 *       type="object",
 *
 *       @OA\Property(
 *         property="currency",
 *         type="object",
 *         @OA\Property(
 *           property="data",
 *           type="object",
 *             @OA\Property(property="type", type="string", example="currencies"),
 *             @OA\Property(property="id", type="string", example="840")
 *           )
 *         ),
 *
 *     ),
 *
 *     @OA\Property(
 *       property="links",
 *       type="object",
 *         @OA\Property(
 *           property="self",
 *           type="string",
 *           example="http://jsonapi.local/api/v1/tags/2"
 *         )
 *       )
 *   )
 * )
 */

/**
 * @OA\Get(
 * path="/users/{userId}/tags",
 * summary="Получение тегов пользователя",
 * description="Получение тегов пользователя",
 * operationId="/users/{userId}/tags",
 * tags={"tags"},
 *
 *      security={
 *          {"passport": {}},
 *      },
 *
 *   @OA\Parameter(
 *     description="User ID",
 *     in="path",
 *     name="userId",
 *     required=true,
 *     example="666",
 *   ),
 * @OA\Response(
 *   response=200,
 *   description="Success",
 *   @OA\MediaType(
 *     mediaType="application/vnd.api+json",
 *     @OA\Schema(ref="#/components/schemas/tag")
 *   )
 * ),
 * @OA\Response(
 *   response=404,
 *   description="Тег не найден",
 *   @OA\MediaType(
 *     mediaType="application/vnd.api+json"
 *   )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthenticated",
 *    @OA\MediaType(
 *        mediaType="application/vnd.api+json",
 *        @OA\Schema(ref="#/components/schemas/unauthenticated")
 *    )
 * ),
 * @OA\Response(
 *    response=403,
 *    description="Unauthorized",
 *    @OA\MediaType(
 *        mediaType="application/vnd.api+json",
 *        @OA\Schema(ref="#/components/schemas/forbidden")
 *    )
 * ),
 *
 *
 *
 * )
 */
