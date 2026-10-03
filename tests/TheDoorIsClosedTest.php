<?php

declare(strict_types=1);

/*
 * This file is part of the vivutio core.
 *
 * (c) Ezekiel Mjema <https://github.com/eemjema>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * What a stranger meets: one open address, and every other one sends them to
 * it.
 */
final class TheDoorIsClosedTest extends WebTestCase
{
    public function testTheSignInFormIsOpen(): void
    {
        $browser = self::createClient();
        $browser->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form input[name="_password"]');
    }

    /**
     * Every page there is sends a stranger to sign in. An address no page
     * answers is not found for anybody, since routing runs before the
     * firewall, which says nothing about who works here.
     */
    public function testEveryOtherPageSendsAStrangerToSignIn(): void
    {
        $browser = self::createClient();

        foreach (['/team', '/logout'] as $address) {
            $browser->request('GET', $address);
            self::assertResponseRedirects('http://localhost/login', null, $address.' sends a stranger to sign in');
        }
    }
}
