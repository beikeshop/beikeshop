<?php
/**
 * MigrateFromOpenCart.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-01-03 18:57:53
 * @modified   2023-01-03 18:57:53
 */

namespace Beike\Console\Commands;

use Beike\Admin\Services\CategoryService;
use Beike\Admin\Services\ProductService;
use Beike\Models\Brand;
use Beike\Models\Category;
use Beike\Models\CategoryDescription;
use Beike\Models\CategoryPath;
use Beike\Models\Product;
use Beike\Models\ProductDescription;
use Beike\Models\ProductSku;
use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MigrateFromOpenCart extends Command
{
    public const PER_PAGE = 1000;

    public const LANG_MAPPING = [
        'zh_cn' => 11,
        'es'    => 6,
    ];

    protected $signature = 'migrate:oc';

    protected $description = '从 OpenCart 迁移数据';

    protected ConnectionInterface $ocdb;

    private $ocProductVariants;

    private $ocVariantDescriptions;

    private $ocVariantValues;

    private $ocVariantValueDescriptions;

    private $ocProductImages;

    private $ocProductCategories;

    private int $page = 1;

    public function __construct()
    {
        parent::__construct();
        $this->ocdb = DB::connection('opencart');
    }

    /**
     * 导入OC产品数据
     */
    public function handle()
    {
        $this->importCategories();
        $this->importBrands();
        $this->importProducts();
    }

    /**
     * 导入分类数据
     */
    private function importCategories()
    {
        Category::query()->truncate();
        CategoryDescription::query()->truncate();
        CategoryPath::query()->truncate();

        $this->ocdb->table('category')
            ->orderBy('category_id')
            ->chunk(self::PER_PAGE, function ($ocCategories) {
                $bkCategories = [];
                foreach ($ocCategories as $ocCategory) {
                    $bkCategories[] = [
                        'id'              => $ocCategory->category_id,
                        'parent_id'       => $ocCategory->parent_id,
                        'position'        => $ocCategory->sort_order,
                        'active'          => $ocCategory->status,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
                Category::query()->insert($bkCategories);
            });

        $descriptions   = [];
        $ocDescriptions = $this->ocdb->table('category_description')->orderBy('category_id')->get();
        $langMapping    = array_flip(self::LANG_MAPPING);
        foreach ($ocDescriptions as $description) {
            $descriptions[] = [
                'category_id'     => $description->category_id,
                'locale'          => $langMapping[$description->language_id],
                'name'            => $description->name,
                'content'         => html_entity_decode($description->description, ENT_QUOTES),
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }
        CategoryDescription::query()->insert($descriptions);

        CategoryService::repairCategories();
    }

    /**
     * 导入品牌数据
     */
    private function importBrands()
    {
        Brand::query()->truncate();
        $this->ocdb->table('manufacturer')
            ->orderBy('manufacturer_id')
            ->chunk(self::PER_PAGE, function ($ocBrands) {
                $bkBrands = [];
                foreach ($ocBrands as $ocBrand) {
                    $bkBrands[] = [
                        'id'         => $ocBrand->manufacturer_id,
                        'name'       => $ocBrand->name,
                        'first'      => mb_strtoupper(mb_substr($ocBrand->name, 0, 1)),
                        'logo'       => $ocBrand->image,
                        'sort_order' => $ocBrand->sort_order,
                        'status'     => $ocBrand->status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Brand::query()->insert($bkBrands);
            });
    }

    /**
     * 导入产品
     */
    private function importProducts()
    {
        $this->ocProductVariants              = $this->ocdb->table('product_variant')->get()->groupBy('product_id');
        $this->ocVariantDescriptions          = $this->ocdb->table('variant_description')->get()->groupBy('variant_id');
        $this->ocVariantValues                = $this->ocdb->table('variant_value')->get()->keyBy('variant_value_id');
        $this->ocVariantValueDescriptions     = $this->ocdb->table('variant_value_description')->get()->groupBy('variant_value_id');
        $this->ocProductImages                = $this->ocdb->table('product_image')->get()->groupBy('product_id');
        $this->ocProductCategories            = $this->ocdb->table('product_to_category')->get()->groupBy('product_id');

        $this->clearData();
        $this->ocdb->table('product')
            ->where('parent_id', 0)
            // ->where('product_id', 1894)
            // ->where('sku', '10012378')
            ->orderBy('product_id')
            ->chunk(self::PER_PAGE, function ($ocProducts) {
                $this->importProduct($ocProducts);
            });
    }

    /**
     * 导入单个产品
     * @param Collection $ocProducts
     */
    private function importProduct(Collection $ocProducts)
    {
        $total         = $ocProducts->count();
        $ocProductIds  = $ocProducts->pluck('product_id');
        $childProducts = $this->ocdb->table('product')->whereIn('parent_id', $ocProductIds)->get()->groupBy('parent_id');
        foreach ($ocProducts as $index => $ocProduct) {
            dump("Start handle Page: $this->page - {$index}/{$total}");
            $ocProductId     = $ocProduct->product_id;
            $productVariants = $this->ocProductVariants[$ocProductId] ?? [];
            $childProducts   = $childProducts[$ocProductId]           ?? [];
            $bkProduct       = $this->generateBeikeProduct($ocProduct, $productVariants, $childProducts);
            (new ProductService)->create($bkProduct);
        }
        $this->page++;
    }

    /**
     * 构造 beike 产品
     */
    private function generateBeikeProduct($ocProduct, $productVariants, $childProducts)
    {
        $variables = $this->generateVariables($ocProduct, $childProducts);
        $bkProduct = [
            'active'       => $ocProduct->status,
            'brand_id'     => $ocProduct->manufacturer_id,
            'position'     => $ocProduct->sort_order,
            'tax_class_id' => $ocProduct->tax_class_id,
            'variables'    => json_encode($variables),
        ];
        $bkProduct['descriptions'] = $this->generateDescriptions($ocProduct);
        $bkProduct['images']       = [$ocProduct->image];
        $bkProduct['skus']         = $this->generateSkus($ocProduct, $productVariants, $childProducts, $variables);
        $bkProduct['categories']   = $this->generateCategories($ocProduct);

        return $bkProduct;
    }

    /**
     * 生成 beike 产品规格
     * @return array[]
     */
    private function generateVariables($ocProduct, $childProducts): array
    {
        $productIds = [$ocProduct->product_id];
        foreach ($childProducts as $childProduct) {
            $productIds[] = $childProduct->product_id;
        }

        $locales = locales();
        $items   = $values = [];
        foreach ($productIds as $productId) {
            $productVariants = $this->ocProductVariants[$productId] ?? [];
            foreach ($productVariants as $productVariant) {
                $productVariantId      = $productVariant->variant_id;
                $productVariantValueId = $productVariant->variant_value_id;

                $variants                 = $this->ocVariantDescriptions[$productVariantId];
                $variantValue             = $this->ocVariantValues[$productVariantValueId];
                $variantValueDescriptions = $this->ocVariantValueDescriptions[$productVariantValueId];

                $variants = $variants->keyBy('language_id');
                $names    = [];
                foreach ($locales as $locale) {
                    $variant                = $variants[self::LANG_MAPPING[$locale['code']]];
                    $names[$locale['code']] = $variant->name;
                }

                $valueNames               = [];
                $variantValueDescriptions = $variantValueDescriptions->keyBy('language_id');
                foreach ($locales as $locale) {
                    $variantValueDescription     = $variantValueDescriptions[self::LANG_MAPPING[$locale['code']]];
                    $valueNames[$locale['code']] = $variantValueDescription->name;
                }

                $values[$productVariantValueId] = [
                    'variant_value_id' => $productVariantValueId,
                    'name'             => $valueNames,
                    'image'            => $variantValue->image,
                ];

                $items[$productVariantId] = [
                    'variant_id' => $productVariantId,
                    'name'       => $names,
                    'values'     => $values,
                    'isImage'    => (bool) $variantValue->image,
                ];
            }
        }

        $items = array_values($items);
        foreach ($items as $index => $item) {
            $items[$index]['values'] = array_values($item['values']);
        }

        return $items;
    }

    /**
     * 生成 beike 产品描述
     * @param $ocProduct
     * @return array
     */
    private function generateDescriptions($ocProduct): array
    {
        $descriptions   = [];
        $locales        = locales();
        $ocDescriptions = $this->ocdb->table('product_description')
            ->where('product_id', $ocProduct->product_id)
            ->get()
            ->keyBy('language_id')
            ->toArray();

        foreach ($locales as $locale) {
            $ocDescription                 = $ocDescriptions[self::LANG_MAPPING[$locale['code']]];
            $descriptions[$locale['code']] = [
                'name'    => $ocDescription->name,
                'content' => html_entity_decode($ocDescription->description, ENT_QUOTES),
            ];
        }

        return $descriptions;
    }

    /**
     * 生成 beike 产品 SKU
     *
     * @param       $ocProduct
     * @param       $productVariants
     * @param       $childProducts
     * @param array $variables
     * @return array
     */
    private function generateSkus($ocProduct, $productVariants, $childProducts, array $variables): array
    {
        // 简单商品
        if (count($productVariants) == 0) {
            return $this->generateSimpleSku($ocProduct);
        }

        $masterSku = $childSkus = [];
        // 有主商品
        if (count($productVariants) > 0) {
            $masterSku = $this->generateMasterSku($ocProduct, $productVariants, $variables);
        }
        // 有子商品
        if (count($childProducts) > 0) {
            $childSkus = $this->generateChildSkus($childProducts, $variables);
        }
        if ($masterSku) {
            array_push($childSkus, $masterSku);
        }

        return $childSkus;
    }

    /**
     * 获取简单商品 SKU
     * @param $ocProduct
     * @return array[]
     */
    private function generateSimpleSku($ocProduct): array
    {
        $images = $this->ocProductImages[$ocProduct->product_id] ?? [];

        return [
            [
                'image'        => $images,
                'model'        => $ocProduct->model,
                'sku'          => $ocProduct->sku,
                'price'        => $ocProduct->price,
                'origin_price' => $ocProduct->price,
                'cost_price'   => $ocProduct->cost_price,
                'quantity'     => $ocProduct->quantity,
                'variants'     => null,
                'position'     => $ocProduct->sort_order,
                'is_default'   => 1,
            ],
        ];
    }

    /**
     * 获取主商品 SKU
     * @param $ocProduct
     * @param $productVariants
     * @param $variables
     * @return array
     */
    private function generateMasterSku($ocProduct, $productVariants, $variables): array
    {
        $variants = $this->generateVariants($productVariants, $variables);
        $images   = $this->ocProductImages[$ocProduct->product_id] ?? [];

        return [
            'image'        => $images,
            'model'        => $ocProduct->model,
            'sku'          => $ocProduct->sku,
            'price'        => $ocProduct->price,
            'origin_price' => $ocProduct->price,
            'cost_price'   => $ocProduct->cost_price,
            'quantity'     => $ocProduct->quantity,
            'variants'     => $variants,
            'position'     => $ocProduct->sort_order,
            'is_default'   => 1,
        ];
    }

    /**
     * 获取子商品SKU
     * @param $childProducts
     * @param $variables
     * @return array
     */
    private function generateChildSkus($childProducts, $variables): array
    {
        $items = [];
        foreach ($childProducts as $ocProduct) {
            $ocProductId     = $ocProduct->product_id;
            $productVariants = $this->ocProductVariants[$ocProductId] ?? [];

            $images   = $this->ocProductImages[$ocProductId] ?? [];
            $variants = $this->generateVariants($productVariants, $variables);
            $items[]  = [
                'image'        => $images,
                'model'        => $ocProduct->model,
                'sku'          => $ocProduct->sku,
                'price'        => $ocProduct->price,
                'origin_price' => $ocProduct->price,
                'cost_price'   => $ocProduct->cost_price,
                'quantity'     => $ocProduct->quantity,
                'variants'     => $variants,
                'position'     => $ocProduct->sort_order,
                'is_default'   => 0,
            ];
        }

        return $items;
    }

    /**
     * 生成商品 SKU 多规格数据
     *
     * @param $productVariants
     * @param $variables
     * @return array
     */
    private function generateVariants($productVariants, $variables): array
    {
        $items = [];
        foreach ($productVariants as $productVariant) {
            $variantId      = $productVariant->variant_id;
            $variantValueId = $productVariant->variant_value_id;
            foreach ($variables as $index1 => $variable) {
                if ($variantId == $variable['variant_id']) {
                    foreach ($variable['values'] as $index2 => $value) {
                        if ($variantValueId == $value['variant_value_id']) {
                            $items[$index1] = (string) $index2;
                        }
                    }
                }
            }
        }

        return $items;
    }

    /**
     * 生成商品分类关联
     *
     * @param $ocProduct
     * @return array
     */
    private function generateCategories($ocProduct): array
    {
        $ocProductCategories   = $this->ocProductCategories[$ocProduct->product_id] ?? [];
        if ($ocProductCategories) {
            return $ocProductCategories->pluck('category_id')->toArray();
        }

        return [];
    }

    /**
     * 清空产品相关数据
     */
    private function clearData()
    {
        Product::query()->truncate();
        ProductSku::query()->truncate();
        ProductDescription::query()->truncate();
    }
}
