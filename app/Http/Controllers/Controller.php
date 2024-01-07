<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\PathItem(path="/api")
 * @OA\Info(
 *     description="Swagger Documentation for the recipe management API",
 *     version="1.0.0",
 *     title="Recipe Management API",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="nksaib176@gmail.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * @OA\Schema(
 *    schema="UserResponse",
 *        @OA\Property(
 *            property="name",
 *            description="User's name",
 *            type="string",
 *            example="Kellen Boyer"
 *        ),
 *        @OA\Property(
 *            property="email",
 *            description="User's E-mail",
 *            type="string",
 *            nullable="false",
 *            example="kellen.boyer@example.com"
 *        ),
 *        @OA\Property(
 *            property="updated_at",
 *            description="User last update",
 *            type="string",
 *            nullable="false",
 *            example="2020-01-01T00:00:00.000000Z"
 *        ),
 *        @OA\Property(
 *            property="created_at",
 *            description="User created at",
 *            type="string",
 *            example="2020-01-01T00:00:00.000000Z"
 *        ),
 *        @OA\Property(
 *            property="id",
 *            description="User's identifier",
 *            type="integer",
 *            example="1"
 *        ),
 *    )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
