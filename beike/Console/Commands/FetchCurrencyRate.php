<?php

namespace Beike\Console\Commands;

use Beike\Services\CurrencyService;
use Illuminate\Console\Command;

class FetchCurrencyRate extends Command
{
    protected $signature = 'fetch:currency-rate';

    protected $description = '更新货币汇率';

    public function handle()
    {
        $today = date('Y-m-d');

        $this->info(sprintf('获取 %s 汇率数据开始', $today));

        try {
            $tableRows = [];
            $data      = CurrencyService::getInstance()->getRatesFromApi($today);

            foreach ($data as $key => $val) {
                $tableRows[] = [$key, $val];
            }

            $this->table(['货币', '汇率'], $tableRows);

            $this->info(sprintf('获取 %s 汇率数据完成', $today));
        } catch (\Exception) {
            $this->error(sprintf('获取 %s 汇率数据失败', $today));
        }
    }
}
