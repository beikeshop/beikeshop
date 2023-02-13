<?php

namespace Beike\Console\Commands;

use Beike\Services\SitemapService;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成网站站点地图sitemap.xml文件';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SitemapService::gen();
        dump('Success!');
    }
}
