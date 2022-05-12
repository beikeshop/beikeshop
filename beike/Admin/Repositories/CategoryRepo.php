<?php

namespace Beike\Admin\Repositories;

use Illuminate\Support\Facades\DB;

class CategoryRepo
{
    public static function flatten(string $locale, $separator = ' > '): array
    {
        $sql = "SELECT cp.category_id AS id, TRIM(LOWER(GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '{$separator}'))) AS name, c1.parent_id, c1.position";
        $sql .= " FROM category_paths cp";
        $sql .= " LEFT JOIN categories c1 ON (cp.category_id = c1.id)";
        $sql .= " LEFT JOIN categories c2 ON (cp.path_id = c2.id)";
        $sql .= " LEFT JOIN category_descriptions cd1 ON (cp.path_id = cd1.category_id)";
        $sql .= " WHERE cd1.locale = '" . $locale . "' GROUP BY cp.category_id ORDER BY name ASC";
        $results = DB::select($sql);

        return $results;
    }
}
