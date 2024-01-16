<?php
/**
 * OpenAI.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-22 20:31:42
 * @modified   2023-02-22 20:31:42
 */

namespace Plugin\Openai\Libraries\OpenAI;

use Exception;

class Base
{
    /**
     * @var string|bool|mixed
     */
    private string $apiKey = '';

    /**
     * @var int
     */
    protected int $maxTokens = 1000;

    /**
     * @var float
     */
    protected float $temperature = 0.5;

    /**
     * @var int
     */
    protected int $number = 1;

    /**
     * @var string
     */
    protected string $prompt;

    /**
     * OpenAI constructor.
     * @param string|null $apiKey
     */
    public function __construct(?string $apiKey = '')
    {
        if ($apiKey) {
            $this->apiKey = $apiKey;
        }
        if (empty($this->apiKey)) {
            $this->apiKey = env('OPENAI_API_KEY');
        }
    }

    /**
     * Get OpenAI instance.
     *
     * @param string|null $apiKey
     * @return Base
     */
    public static function getInstance(?string $apiKey = ''): static
    {
        return new self($apiKey);
    }

    /**
     * 设置 max_tokens的值
     * 一般来说，max_tokens值越大，模型的表现就越好，
     * 但是需要考虑到计算资源的限制，max_tokens值不宜过大。
     * 一般来说，max_tokens值可以设置在比较合理的范围内，比如500到1000之间。
     *
     * @param int $maxTokens
     * @return $this
     */
    public function setMaxTokens(int $maxTokens): static
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }

    /**
     * 设置temperature参数值, 参数的范围是0.0到2.0之间。
     * 在较低的温度下，模型会生成更加安全的文本，
     * 而在较高的温度下，模型会生成更加创新的文本。
     *
     * @param float $temperature
     * @return $this
     */
    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * 设置 n 参数值
     * 指定了返回结果的数量。n参数越大，返回的结果数量也越多。
     *
     * @param int $number
     * @return $this
     */
    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * 设置 prompt 参数值
     * 用于指定一段文本，用于提供给模型参考，以便于生成更加相关的文本
     *
     * @param string $prompt
     * @return $this
     * @throws Exception
     */
    public function setPrompt(string $prompt): static
    {
        $this->prompt = trim($prompt);
        if (empty($this->prompt)) {
            throw new Exception('prompt 不能为空!');
        }

        return $this;
    }

    /**
     * 发送请求到 OpenAI
     *
     * @param $url
     * @param $data
     * @return mixed
     * @throws Exception
     */
    protected function request($url, $data): mixed
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);

        return json_decode($response, true);
    }
}
