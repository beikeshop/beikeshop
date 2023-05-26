<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage extends Page
{
    /**
     * Get the URL for the Page.
     */
    public function url(): string
    {
        return '/';
    }

    /**
     * Assert that the browser is on the Page.
     */
    public function assert(Browser $browser): void
    {
        //
    }

    /**
     * Get the element shortcuts for the Page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
