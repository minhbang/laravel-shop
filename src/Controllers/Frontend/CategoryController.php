<?php
namespace Minhbang\LaravelShop\Controllers\Frontend;

use Category;
use Minhbang\LaravelCategory\CategoryItem;
use Minhbang\LaravelProduct\Models\Product;
use Minhbang\LaravelKit\Extensions\Controller;

/**
 * Class CategoryController
 *
 * @package Minhbang\LaravelShop\Controllers\Frontend
 */
class CategoryController extends Controller
{
    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function index()
    {
        $categories = Category::of('product')->getRoots();
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
        /** @var \Minhbang\LaravelCategory\CategoryItem $category */
        if (is_null($category = CategoryItem::findBySlug($slug))) {
            abort(404);
        }
        $products = Product::orderPosition()->categorized($category)->paginate(12);
        $this->buildBreadcrumbs(
            [
                route('category.index') => trans('category::common.category'),
                '#'                     => $category->title,
            ]
        );
        return view('shop::frontend.category.show', compact('category', 'products'));
    }
}
