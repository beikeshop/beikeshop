<?php
/**
 * OpenaiLog.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-13 16:48:17
 * @modified   2023-03-13 16:48:17
 */

namespace Plugin\Openai\Models;

use Illuminate\Database\Eloquent\Model;

class OpenaiLog extends Model
{
    public $timestamps = true;

    protected $table = 'openai_logs';

    protected $fillable = [
        'user_id', 'question', 'answer', 'request_ip', 'user_agent',
    ];

    protected $appends = ['created_format'];

    public function getCreatedFormatAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }
}
