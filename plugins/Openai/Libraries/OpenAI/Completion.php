<?php
/**
 * Completion.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-02 14:37:15
 * @modified   2023-03-02 14:37:15
 */

namespace Plugin\Openai\Libraries\OpenAI;

class Completion extends Base
{
    /**
     * @throws \Exception
     */
    public function create()
    {
        $model = 'text-davinci-003';
        $url   = 'https://api.openai.com/v1/completions';
        $data  = [
            'prompt'      => $this->prompt,
            'max_tokens'  => $this->maxTokens,
            'temperature' => $this->temperature,
            'n'           => $this->number,
            'stop'        => '\n',
            'model'       => $model,
        ];
        $this->request($url, $data);
    }
}
