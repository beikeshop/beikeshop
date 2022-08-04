<?php

namespace Beike\Shop\View\Components;

use Beike\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

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
        return view('components.account.sidebar', ['customer' => $this->customer]);
    }
}
