<?php
/**
 * CurrencyService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-28 16:29:54
 * @modified   2022-07-28 16:29:54
 */

namespace Beike\Services;

use Beike\Repositories\CurrencyRepo;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    private static $instance;

    private $currencies = [];

    public function __construct()
    {
        foreach (CurrencyRepo::listEnabled() as $result) {
            $this->currencies[$result->code] = $result;
        }
    }

    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function format($amount, $currency, $value = '', $format = true)
    {
        if (empty($this->currencies)) {
            return $amount;
        }

        $currencyRow = $this->currencies[$currency] ?? null;
        if (empty($currencyRow)) {
            return $amount;
        }

        $symbol_left   = $currencyRow->symbol_left;
        $symbol_right  = $currencyRow->symbol_right;
        $decimal_place = $currencyRow->decimal_place;

        if (! $value) {
            $value = $currencyRow->value;
        }

        $amount = $value ? (float) $amount * $value : (float) $amount;

        $amount = round($amount, (int) $decimal_place);

        if (! $format) {
            return $amount;
        }

        $string = '';
        if ($amount < 0) {
            $string = '-';
        }

        if ($symbol_left) {
            $string .= $symbol_left;
        }

        $string .= number_format(abs($amount), (int) $decimal_place, __('currency.decimal_point'), __('currency.thousand_point'));

        if ($symbol_right) {
            $string .= ' ' . $symbol_right;
        }

        return $string;
    }

    public function convert($value, $from, $to)
    {
        if (isset($this->currencies[$from])) {
            $from = $this->currencies[$from]->value;
        } else {
            $from = 1;
        }

        if (isset($this->currencies[$to])) {
            $to = $this->currencies[$to]->value;
        } else {
            $to = 1;
        }

        return $value * ($to / $from);
    }

    public function getRatesFromApi(string $date)
    {
        $cacheKey = 'currency:rates:' . $date;
        if ($rates = cache()->get($cacheKey)) {
            return $rates;
        }
        if (empty(system_setting('base.rate_api_key'))) {
            return [];
        }
        $data = Http::get(
            sprintf(
                'https://v6.exchangerate-api.com/v6/%s/latest/%s',
                system_setting('base.rate_api_key'),
                system_setting('base.currency', 'USD')
            )
        )->json();
        $rates = $data['conversion_rates'] ?? [];
        cache()->set($cacheKey, $rates);

        return $rates;
    }
}
