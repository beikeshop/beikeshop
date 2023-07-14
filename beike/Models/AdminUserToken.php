<?php
/**
 * AdminUserToken.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 10:18:56
 * @modified   2023-04-20 10:18:56
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUserToken extends Base
{
    protected $fillable = ['admin_user_id', 'token'];

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class);
    }
}
