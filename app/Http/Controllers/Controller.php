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
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
