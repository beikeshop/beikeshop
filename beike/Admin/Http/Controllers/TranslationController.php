<?php
/**
 * TranslationController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-09-04 14:31:37
 * @modified   2023-09-04 14:31:37
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function translateText(Request $request): JsonResponse
    {
        try {
            $from = $request->post('from');
            $to   = $request->post('to');
            $text = $request->post('text');

            $result = TranslationService::translate($from, $to, $text);

            return json_success(trans('common.get_success'), $result);
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
