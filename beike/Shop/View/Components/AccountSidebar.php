<?php

namespace Beike\Shop\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AccountSidebar extends Component
{
    private $customer;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->customer = current_customer();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|
     */
    public function render(): View
    {
        $data = [
            'customer' => $this->customer,
        ];
        return view('components.account.sidebar', $data);
    }
}
