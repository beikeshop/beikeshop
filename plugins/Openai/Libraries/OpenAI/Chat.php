<?php
/**
 * Chat.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-02 14:30:10
 * @modified   2023-03-02 14:30:10
 */

namespace Plugin\Openai\Libraries\OpenAI;

class Chat extends Base
{
    /**
     * @var array 聊天上下文
     */
    private array $messages;

    /**
     * @param string|null $apiKey
     * @return static
     */
    public static function getInstance(?string $apiKey = ''): static
    {
        return new self($apiKey);
    }

    /**
     * https://platform.openai.com/docs/guides/chat/introduction
     *
     * @param $messages
     * @param $prompt
     * @return Chat
     */
    public function setMessages($messages, $prompt): self
    {
        $messages[]     = ['role' => 'user', 'content' => $prompt];
        $this->messages = $messages;

        return $this;
    }

    /**
     * 发送请求到 OpenAI
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(): mixed
    {
        $model = 'gpt-3.5-turbo';
        $url   = 'https://api.openai.com/v1/chat/completions';
        $data  = [
            'messages'    => $this->messages,
            'max_tokens'  => $this->maxTokens,
            'temperature' => $this->temperature,
            'n'           => $this->number,
            'stop'        => '\n',
            'model'       => $model,
        ];

        return $this->request($url, $data);
    }
}
