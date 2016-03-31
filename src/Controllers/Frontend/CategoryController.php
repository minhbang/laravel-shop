<?php
namespace Minhbang\Shop\Controllers\Frontend;

use Minhbang\Category\Category as Category;
use Minhbang\Option\OptionableController;
use Minhbang\Product\Models\Product;
use Minhbang\Kit\Extensions\Controller;
use Minhbang\Shop\DisplayOption;

/**
 * Class CategoryController
 *
 * @package Minhbang\Shop\Controllers\Frontend
 */
class CategoryController extends Controller
{
    use OptionableController;

    /**
     * @return array
     */
    protected function optionConfig()
    {
        return [
            'zone'  => 'shop',
            'group' => 'category',
            'class' => DisplayOption::class,
        ];
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function index()
    {
        $categories = app('category-manager')->root('product')->roots();
        $this->buildBreadcrumbs(['#' => trans('category::common.category')]);

        return view('shop::frontend.category.index', compact('categories'));
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        /** @var \Minhbang\Category\Category $category */
        if (is_null($category = Category::findBySlug($slug))) {
            abort(404);
        }

        $query = Product::queryDefault()->categorized($category);
        $products = $this->optionAppliedPaginate($query, true);
        $this->buildBreadcrumbs(
            [
                route('category.index') => trans('category::common.category'),
                '#'                     => $category->title,
            ]
        );

        return view('shop::frontend.category.show', compact('category', 'products'));
    }
}
