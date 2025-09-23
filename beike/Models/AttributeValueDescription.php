<?php
/**
 * AttributeValueDescription.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-01-03 20:22:18
 * @modified   2023-01-03 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeValueDescription extends Base
{
    use HasFactory;

    protected $fillable = ['attribute_value_id', 'locale', 'name'];
}
