<?php
namespace Minhbang\Shop\Presenters;

use Html;
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
        return price_format($this->entity->subtotal, config('shop.currency_short'), false, true, config('shop.decimals'));
    }

    /**
     * @return null|string
     */
    public function tax()
    {
        return price_format($this->entity->tax, config('shop.currency_short'), false, true, config('shop.decimals'));
    }

    /**
     * @return null|string
     */
    public function total()
    {
        return price_format($this->entity->subtotal + $this->entity->tax, config('shop.currency_short'), false, true, config('shop.decimals'));
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
    public function status_buttons()
    {
        return Form::select('status', $this->entity->statuses(), $this->entity->status, ['class' => 'select-btngroup', 'data-size' => 'xs']);
    }

    /**
     * @param int $status
     *
     * @return string
     */
    public function status_button($status)
    {
        $type = $this->entity->itemAlias('StatusCss', $status);
        $label = $this->entity->itemAlias('Status', $status);
        $url = route('backend.order.status', ['order' => $this->entity->id, 'status' => $status]);

        return Html::linkButton($url, $label, ['class' => 'post-link', 'type' => $type, 'size' => 'xs']);
    }
}