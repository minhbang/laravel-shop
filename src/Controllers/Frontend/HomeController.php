<?php
namespace Minhbang\LaravelShop\Controllers\Frontend;

use Minhbang\LaravelKit\Extensions\Controller;

/**
 * Class HomeController
 *
 * @package Minhbang\LaravelShop\Controllers\Frontend
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function index()
    {
        $this->buildBreadcrumbs(['#' => trans('category::common.category')]);
        return view('shop::frontend.home.index', compact('categories'));
    }
}
