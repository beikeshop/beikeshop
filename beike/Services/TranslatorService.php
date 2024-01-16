<?php
/**
 * Translator.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-09-04 15:31:49
 * @modified   2023-09-04 15:31:49
 */

namespace Beike\Services;

interface TranslatorService
{
    public function translate($from, $to, $text): string;

    public function batchTranslate($from, $to, $texts): array;

    public function mapCode($code): string;
}
