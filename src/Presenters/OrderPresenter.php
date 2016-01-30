<?php
namespace Minhbang\Shop\Presenters;
use Form;
use Laracasts\Presenter\Presenter;
use Minhbang\Kit\Traits\Presenter\DatetimePresenter;

/**
 * Class OrderPresenter
 *
 * @package Minhbang\Shop\Presenters
 */
class OrderPresenter extends Presenter
{
    use DatetimePresenter;

    /**
     * @return null|string
     */
    public function subtotal()
    {
        return price_format($this->entity->subtotal, 'đ', false, true);
    }

    /**
     * @return null|string
     */
    public function tax()
    {
        return price_format($this->entity->tax, 'đ', false, true);
    }

    /**
     * @return null|string
     */
    public function total()
    {
        return price_format($this->entity->subtotal + $this->entity->tax, 'đ', false, true);
    }

    /**
     * @return string
     */
    public function name()
    {
        return <<<"HTML"
<div class="customer-info">
<div class="name">{$this->entity->name}</div>
<span>- Tel: </span>{$this->entity->phone}<br />
<span>- E-mail: </span>{$this->entity->email}<br />
<span>- Adr: </span>{$this->entity->address}
</div>
HTML;
    }

    /**
     * @return string
     */
    public function products()
    {
        $html = '';
        foreach ($this->entity->products as $product) {
            $html .= "- <code>{$product->pivot->quantity}</code> {$product->name}<br />";
        }
        return '<div class="products-info">' . $html . '</div>';
    }

    /**
     * @return string
     */
    public function status()
    {
        $css = $this->entity->itemAlias('StatusCss', $this->entity->status);
        $status = $this->entity->itemAlias('Status', $this->entity->status);
        return "<span class=\"label label-{$css}\">$status</span>";
    }

    /**
     * @return string
     */
    public function status_button()
    {
        return Form::select ('status', $this->entity->statuses(), $this->entity->status,['class' => 'select-btngroup', 'data-size' => 'xs']);
    }
}