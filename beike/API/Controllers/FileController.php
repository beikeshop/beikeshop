<?php
/**
 * FileController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-21 18:02:22
 * @modified   2023-08-21 18:02:22
 */

namespace Beike\API\Controllers;

use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\UploadRequest;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    /**
     * @param UploadRequest $request
     * @return JsonResponse
     */
    public function store(UploadRequest $request): JsonResponse
    {
        try {
            $file = $request->file('file');
            $type = $request->get('type');
            $path = $file->store($type, 'upload');

            $data = [
                'url'   => asset('upload/' . $path),
                'value' => 'upload/' . $path,
            ];

            $data = hook_filter('file.store.data', $data);

            return json_success(trans('shop/file.uploaded_success'), $data);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
