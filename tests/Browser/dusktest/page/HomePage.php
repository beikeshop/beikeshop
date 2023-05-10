<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage
{
    /**
     * The URL of the page.
     *
     * @var string
     */
    protected $url = '/';

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url);
    }

    /**
     * Get the page URL.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Click on the login link.
     *
     * @param Browser $browser
     * @return void
     */
    public function clickLoginLink(Browser $browser)
    {
        $browser->clickLink('Login');
    }

    /**
     * Fill in the email field.
     *
     * @param Browser $browser
     * @param string  $email
     * @return void
     */
    public function fillInEmailField(Browser $browser, $email)
    {
        $browser->type('input[name="email"]', $email);
    }

    /**
     * Fill in the password field.
     *
     * @param Browser $browser
     * @param string  $password
     * @return void
     */
    public function fillInPasswordField(Browser $browser, $password)
    {
        $browser->type('input[name="password"]', $password);
    }

    /**
     * Submit the login form.
     *
     * @param Browser $browser
     * @return void
     */
    public function submitLoginForm(Browser $browser)
    {
        $browser->press('Login');
    }
}
