<?php
namespace Minhbang\Shop\Controllers\Frontend;

use Minhbang\Kit\Extensions\Controller;
/**
 * Class HomeController
 *
 * @package Minhbang\Shop\Controllers\Frontend
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function index()
    {
        $categories = app('category-manager')->root('product')->roots();
        return view('shop::frontend.category.index', compact('categories'));
    }
}
