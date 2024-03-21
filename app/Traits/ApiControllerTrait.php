<?php
/**
 * Created by black40x@yandex.ru
 * Date: 07.10.2018
 */

namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait ApiControllerTrait
{
    /**
     * @param array $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function response(array $data, string $message = '', int $statusCode = 200): JsonResponse
    {
        return Response::json(
            data: $data,
            status: $statusCode,
            options: JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE
        );
    }


}
