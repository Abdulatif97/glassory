<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(title="My First API", version="0.1", @OA\Contact(email=" "), @OA\License(name="MIT"))
 * @OA\Server(url="http://localhost:8000/")
 * @OA\BearerAuth()
 */
class Controller extends BaseController
{
    /**
     * @OA\Info(
     *     version="1.0",
     *     title="Example for response examples value"
     * )
     */
    /**
     * @OA\PathItem(path="/api")
     */


    /**
     * @OA\Schema(
     *  schema="Result",
     *  title="Sample schema for using references",
     * 	@OA\Property(
     * 		property="status",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="error",
     * 		type="string"
     * 	)
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var array
     */
    protected static $_empty_response = [
        'success' => TRUE,
        'message' => '',
        'errors'  => [],
        'payload' => [],
    ];

    /**
     * @var array
     */
    protected $response_array;

    const PAGE_SIZE = 15;

    function __construct()
    {
        $this->response_array = self::$_empty_response;
    }
}
