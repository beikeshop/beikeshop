<?php
/**
 * OpenaiController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-27 16:13:08
 * @modified   2023-02-27 16:13:08
 */

namespace Plugin\Openai\Controllers;

use Beike\Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugin\Openai\Services\OpenAIService;

class OpenaiController extends Controller
{
    /**
     * OpenAI home page.
     *
     * @return mixed
     */
    public function index()
    {
        $plugin = plugin('openai');

        $error   = '';
        $baseUrl = config('beike.api_url') . '/api/openai';
        $apiType = plugin_setting('openai.api_type');
        if ($apiType == 'own') {
            $apiKey = plugin_setting('openai.api_key');
            if (empty($apiKey)) {
                $error = trans('Openai::common.empty_api_key');
            }
            $baseUrl = config('app.url') . '/admin/openai';
        }

        $data = [
            'name'        => $plugin->getLocaleName(),
            'description' => $plugin->getLocaleDescription(),
            'type'        => $apiType,
            'base'        => $baseUrl,
            'error'       => $error,
        ];

        return view('Openai::admin.openai', $data);
    }

    /**
     * Send chat completions with OpenAI API
     *
     * @param Request $request
     * @return array|mixed
     * @throws \Throwable
     */
    public function completions(Request $request)
    {
        try {
            $result = (new OpenAIService())->requestAI($request->all());
        } catch (\Exception $e) {
            $result = [
                'error' => $e->getMessage(),
            ];
        }

        return $result;
    }

    /**
     * Get histories
     *
     * @param Request $request
     * @return array|mixed
     */
    public function histories(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $result  = (new OpenAIService())->getOpenaiLogs($perPage);
        } catch (\Exception $e) {
            $result = [
                'error' => $e->getMessage(),
            ];
        }

        return $result;
    }
}
