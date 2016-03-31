<?php
namespace Minhbang\Shop\Controllers\Frontend;

use Minhbang\Product\Models\Manufacturer;
use Minhbang\Product\Models\Product;
use Illuminate\Http\Request;
use Minhbang\Category\Category as Category;
use Minhbang\Kit\Extensions\Controller;
use Minhbang\Option\OptionableController;
use Minhbang\Shop\DisplayOption;

/**
 * Class SearchController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class SearchController extends Controller
{
    use OptionableController;

    /**
     * @var array
     */
    protected $key_column = [];

    public function __construct()
    {
        parent::__construct();
        $this->key_column = Product::$searchable_keys;
    }

    /**
     * @return array
     */
    protected function optionConfig()
    {
        return [
            'zone'  => 'shop',
            'group' => 'search',
            'class' => DisplayOption::class,
        ];
    }


    /**
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->buildBreadcrumbs([
            '#' => trans('shop::common.search'),
        ]);

        $q = $request->get('q');
        $params = $request->only(array_keys($this->key_column));
        $attributes = $this->getAttributes($params);
        $advanced = !empty($attributes);
        $category_id = mb_array_extract('category_id', $attributes);
        $query = Product::queryDefault()->withEnumTitles()
            ->whereAttributes($attributes)->searchKeyword($q);
        if ($category_id && ($category = Category::find($category_id))) {
            $query->categorized($category);
        }
        $products = $this->optionAppliedPaginate($query);
        $total = $products->total();
        $categories = app('category-manager')->root('product')->selectize();
        $enums = (new Product())->loadEnums('id');
        $manufacturers = Manufacturer::getList();

        $column_key = array_combine(
            array_values($this->key_column),
            array_keys($this->key_column)
        );
        $params['q'] = $q;

        return view(
            'shop::frontend.search.index',
            $enums + compact('q', 'products', 'total', 'categories', 'manufacturers', 'params', 'column_key', 'advanced')
        );
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function getAttributes($params)
    {
        $attributes = [];
        foreach ($params as $key => $value) {
            if ($value && isset($this->key_column[$key])) {
                $attributes[$this->key_column[$key]] = $value;
            }
        }

        return $attributes;
    }
}
