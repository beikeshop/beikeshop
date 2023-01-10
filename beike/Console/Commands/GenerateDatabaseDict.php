<?php
/**
 * GenerateDatabaseDict.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-11-11 09:00:25
 * @modified   2022-11-11 09:00:25
 */

namespace Beike\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use UniSharp\DocUs\Parser;

class GenerateDatabaseDict extends Command
{
    protected $signature = 'make:dict';

    protected $description = '生成数据字典 Markdown 文档';

    public function handle()
    {
        $filePath = storage_path('database.md');
        // $filePath = '/Users/edward/Guangda/Products/beike/beikedocs/docs/dev/database.md';
        $tables   = $this->getTables();
        $markdown = view('vendor.unisharp.markdown', compact('tables'))->render();
        file_put_contents($filePath, $markdown);
        dump('done');
    }

    /**
     * 获取所有数据表信息
     * @return Collection
     */
    private function getTables(): Collection
    {
        $schema = Parser::getSchema();

        return $schema->map(function ($item) {
            $tableName       = $item['name'];
            $result          = collect(DB::select("SHOW TABLE STATUS WHERE Name='{$tableName}'"))->first();
            $item['comment'] = $result->Comment;

            return $item;
        });
    }
}
