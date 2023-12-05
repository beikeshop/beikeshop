<?php
/**
 * AddCountryContinent.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-12-05 11:33:30
 * @modified   2023-12-05 11:33:30
 */

namespace Beike\Console\Commands;

use Beike\Models\Country;
use Illuminate\Console\Command;

class AddCountryContinent extends Command
{
    protected $signature = 'continent:add';

    protected $description = '为国家表添加大洲';

    public const CONTINENT = [
        'Africa'        => 'AF',
        'Antarctica'    => 'AN',
        'Asia'          => 'AS',
        'Europe'        => 'EU',
        'North America' => 'NA',
        'Oceania'       => 'OA',
        'South America' => 'SA',
    ];

    /**
     * @throws \Throwable
     */
    public function handle(): void
    {
        $filePath   = resource_path('data/country-by-continent.json');
        $continents = json_decode(file_get_contents($filePath), true);
        foreach ($continents as $continent) {
            $this->info("Handle for {$continent['country']}");
            $code = self::CONTINENT[$continent['continent']];
            Country::query()->where('name', $continent['country'])->update(['continent' => $code]);
        }
    }
}
