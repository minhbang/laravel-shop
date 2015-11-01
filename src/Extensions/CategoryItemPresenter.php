<?php
namespace Minhbang\LaravelShop\Extensions;

use ShopWidget;
use Minhbang\LaravelProduct\Models\Product;
use Minhbang\LaravelCategory\CategoryItemPresenter as Presenter;

class CategoryItemPresenter extends Presenter
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelProduct\Models\Product[] $products
     *
     * @return null|string
     */
    public function htmlProductsList($products)
    {
        return ShopWidget::productsList($products, $this->entity->title);
    }

    /**
     * @param integer $limit
     *
     * @return string
     */
    public function htmlProductsTop($limit = 6)
    {
        return $this->htmlProductsList(Product::topOf($this->entity, $limit));
    }
}