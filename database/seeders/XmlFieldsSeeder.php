<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class XmlFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('xml_fields')->insert(
            [
                ['field_title' => 'additional image link', 'field_slug' => 'additional_image_link'],
                ['field_title' => 'additional product type', 'field_slug' => 'additional_product_type'],
                ['field_title' => 'adwords grouping', 'field_slug' => 'adwords_grouping'],
                ['field_title' => 'adwords labels', 'field_slug' => 'adwords_labels'],
                ['field_title' => 'adwords redirect', 'field_slug' => 'adwords_redirect'],
                ['field_title' => 'age group', 'field_slug' => 'age_group'],
                ['field_title' => 'availability', 'field_slug' => 'availability'],
                ['field_title' => 'brand', 'field_slug' => 'brand'],
                ['field_title' => 'color', 'field_slug' => 'color'],
                ['field_title' => 'condition', 'field_slug' => 'condition'],
                ['field_title' => 'country', 'field_slug' => 'country'],
                ['field_title' => 'currency', 'field_slug' => 'currency'],
                ['field_title' => 'date', 'field_slug' => 'date'],
                ['field_title' => 'date1', 'field_slug' => 'date1'],
                ['field_title' => 'date2', 'field_slug' => 'date2'],
                ['field_title' => 'date3', 'field_slug' => 'date3'],
                ['field_title' => 'delivery period', 'field_slug' => 'delivery_period'],
                ['field_title' => 'description', 'field_slug' => 'description'],
                ['field_title' => 'ean', 'field_slug' => 'ean'],
                ['field_title' => 'end_date', 'field_slug' => 'end_date'],
                ['field_title' => 'energy efficiency_class', 'field_slug' => 'energy_efficiency_class'],
                ['field_title' => 'excluded destination', 'field_slug' => 'excluded_destination'],
                ['field_title' => 'expiration date', 'field_slug' => 'expiration_date'],
                ['field_title' => 'gender', 'field_slug' => 'gender'],
                ['field_title' => 'google product category', 'field_slug' => 'google_product_category'],
                ['field_title' => 'gtin', 'field_slug' => 'gtin'],
                ['field_title' => 'id', 'field_slug' => 'id'],
                ['field_title' => 'identifier exists', 'field_slug' => 'identifier_exists'],
                ['field_title' => 'image link', 'field_slug' => 'image_link'],
                ['field_title' => 'isbn', 'field_slug' => 'isbn'],
                ['field_title' => 'item group id', 'field_slug' => 'item_group_id'],
                ['field_title' => 'item model id', 'field_slug' => 'item_model_id'],
                ['field_title' => 'link', 'field_slug' => 'link'],
                ['field_title' => 'manufacturer', 'field_slug' => 'manufacturer'],
                ['field_title' => 'material', 'field_slug' => 'material'],
                ['field_title' => 'model code', 'field_slug' => 'model_code'],
                ['field_title' => 'mpn', 'field_slug' => 'mpn'],
                ['field_title' => 'multipack', 'field_slug' => 'multipack'],
                ['field_title' => 'pattern', 'field_slug' => 'pattern'],
                ['field_title' => 'pickup costs', 'field_slug' => 'pickup_costs'],
                ['field_title' => 'price', 'field_slug' => 'price'],
                ['field_title' => 'price_old', 'field_slug' => 'price_old'],
                ['field_title' => 'product category', 'field_slug' => 'product_category'],
                ['field_title' => 'product subcategory', 'field_slug' => 'product_subcategory'],
                ['field_title' => 'product subsubcategory', 'field_slug' => 'product_subsubcategory'],
                ['field_title' => 'product type', 'field_slug' => 'product_type'],
                ['field_title' => 'promotion', 'field_slug' => 'promotion'],
                ['field_title' => 'sale price', 'field_slug' => 'sale_price'],
                ['field_title' => 'sale price_effective_date', 'field_slug' => 'sale_price_effective_date'],
                ['field_title' => 'shipping country', 'field_slug' => 'shipping_country'],
                ['field_title' => 'shipping price', 'field_slug' => 'shipping_price'],
                ['field_title' => 'shipping region', 'field_slug' => 'shipping_region'],
                ['field_title' => 'shipping service', 'field_slug' => 'shipping_service'],
                ['field_title' => 'shipping country', 'field_slug' => 'shipping_country'],
                ['field_title' => 'shipping price', 'field_slug' => 'shipping_price'],
                ['field_title' => 'shipping region', 'field_slug' => 'shipping_region'],
                ['field_title' => 'shipping service', 'field_slug' => 'shipping_service'],
                ['field_title' => 'shipping weight', 'field_slug' => 'shipping_weight'],
                ['field_title' => 'shop id', 'field_slug' => 'shop_id'],
                ['field_title' => 'size', 'field_slug' => 'size'],
                ['field_title' => 'sku', 'field_slug' => 'sku'],
                ['field_title' => 'start date', 'field_slug' => 'start_date'],
                ['field_title' => 'stock', 'field_slug' => 'stock'],
                ['field_title' => 'title', 'field_slug' => 'title'],
                ['field_title' => 'unit pricing base measure', 'field_slug' => 'unit_pricing_base_measure'],
                ['field_title' => 'unit pricing measure', 'field_slug' => 'unit_pricing_measure'],
                ['field_title' => 'variant code', 'field_slug' => 'variant_code'],

            ]
        );
    }
}
