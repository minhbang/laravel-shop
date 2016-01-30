<?php
namespace Minhbang\Shop\Requests;

use Minhbang\Kit\Extensions\Request;

/**
 * Class OrderRequest
 *
 * @package Minhbang\Shop\Requests
 */
class OrderRequest extends Request
{
    public $trans_prefix = 'shop::order';
    public $rules = [
        'name'    => 'required|max:60',
        'email'   => 'required|email|max:40',
        'phone'   => 'required|max:40',
        'address' => 'required|max:255',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var \Minhbang\Product\Models\Order $order */
        if ($order = $this->route('order')) {
            //update Order
        } else {
            // create Order
        }
        return $this->rules;
    }

}
