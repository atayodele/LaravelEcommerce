<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @SWG\Swagger(
     *     basePath="",
     *     schemes={"http", "https"},
     *     host=L5_SWAGGER_CONST_HOST,
     *     @SWG\Info(
     *         version="1.0.0",
     *         title="E-commerce",
     *         description="L5 Swagger API description",
     *         @SWG\Contact(
     *             email="your-email@domain.com"
     *         ),
     *     )
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
