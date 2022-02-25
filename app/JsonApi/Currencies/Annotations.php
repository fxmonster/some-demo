<?php

namespace App\JsonApi\Currencies;

/**
 * @OA\Schema(
 *   schema="currency",
 *   @OA\Property(
 *     property="data",
 *     ref="#/components/schemas/currency-data"
 *   )
 * )
 */

/**
 * @OA\Schema(
 *   schema="currencies",
 *   @OA\Property(
 *     property="data",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/currency-data")
 *   )
 * )
 */

/**
 * @OA\Schema(
 *   schema="currency-data",
 *     type="object",
 *
 *     @OA\Property(
 *       property="type",
 *       type="string",
 *       example="currencies"
 *     ),
 *     @OA\Property(
 *       property="id",
 *       type="string",
 *       example="840"
 *     ),
 *
 *     @OA\Property(
 *       property="attributes",
 *       @OA\Property(property="code", type="string", description="alpha-3 code", example="USD"),
 *       @OA\Property(property="name", type="string", description="Название валюты", example="Dollar"),
 *       @OA\Property(property="minor_units", type="integer", description="Количество минорных единиц", example=2)
 *     ),
 *
 *     @OA\Property(
 *       property="links",
 *       type="object",
 *         @OA\Property(
 *           property="self",
 *           type="string",
 *           example="http://jsonapi.local/api/v1/currencies/840"
 *         )
 *      )
 *    )
 * )
 */

/**
 * @OA\Get(
 * path="/currencies",
 * summary="Список всех валют",
 * description="Список всех валют",
 * operationId="currencies",
 * tags={"currencies"},
 *
 *   @OA\Parameter(
 *     description="Get user available currencies - 1",
 *     in="query",
 *     name="filter[availableForCurrencyAccountCreation]",
 *     required=false
 *   ),
 *
 *  security={
 *  {"passport": {}},
 *   },
 * @OA\Response(
 *   response=200,
 *   description="Список валют",
 *   @OA\MediaType(
 *     mediaType="application/vnd.api+json",
 *     @OA\Schema(ref="#/components/schemas/currencies")
 *   )
 * )
 * )
 */

/**
 * @OA\Get(
 * path="/currencies/{currencyId}",
 * summary="Возвращает валюту по ID",
 * description="Возвращает валюту по ID",
 * operationId="currencies/{currencyId}",
 * tags={"currencies"},
 *   @OA\Parameter(
 *     description="currencyId",
 *     in="path",
 *     name="currencyId",
 *     required=true,
 *     example="840",
 *     @OA\Schema(
 *       type="integer",
 *       format="int64"
 *     ),
 *   ),
 * security={
 *  {"passport": {}},
 *   },
 * @OA\Response(
 *   response=200,
 *   description="Success",
 *   @OA\MediaType(
 *     mediaType="application/vnd.api+json",
 *     @OA\Schema(ref="#/components/schemas/currency")
 *   )
 * )
 * )
 */
