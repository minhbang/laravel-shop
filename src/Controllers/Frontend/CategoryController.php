<?php
namespace Minhbang\Shop\Controllers\Frontend;

use Minhbang\Category\Item as Category;
use Minhbang\Product\Models\Product;
use Minhbang\Kit\Extensions\Controller;

/**
 * Class CategoryController
 * @package Minhbang\Shop\Controllers\Frontend
 */
class CategoryController extends Controller
{
    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function index()
    {
        $categories = app('category')->manage('product')->roots();
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
        /** @var \Minhbang\Category\Item $category */
        if (is_null($category = Category::findBySlug($slug))) {
            abort(404);
        }
        $products = Product::orderPosition()->categorized($category)->paginate(12);
        $this->buildBreadcrumbs(
            [
                route('category.index') => trans('category::common.category'),
                '#' => $category->title,
            ]
        );
        return view('shop::frontend.category.show', compact('category', 'products'));
    }
}
