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

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;
use Vivutio\Bundle\ConnectBundle\ConnectBundle;
use Vivutio\Bundle\IdentityBundle\IdentityBundle;
use Vivutio\Bundle\PartnerBundle\PartnerBundle;
use Vivutio\Bundle\PlaceBundle\PlaceBundle;
use Vivutio\Bundle\RegistryBundle\RegistryBundle;
use Vivutio\Bundle\ShellBundle\ShellBundle;

/**
 * The installation holds the whole core, and mounts its screens.
 */
final class TheInstallationBootsTest extends KernelTestCase
{
    /**
     * @return iterable<string, array{class-string}>
     */
    public static function coreBundles(): iterable
    {
        yield 'the registry' => [RegistryBundle::class];
        yield 'identity' => [IdentityBundle::class];
        yield 'the shell' => [ShellBundle::class];
        yield 'places' => [PlaceBundle::class];
        yield 'partners' => [PartnerBundle::class];
        yield 'the hub connection' => [ConnectBundle::class];
    }

    /**
     * A line missing from config/bundles.php fails here and names itself,
     * rather than surfacing as a blank page.
     *
     * @param class-string $class
     */
    #[DataProvider('coreBundles')]
    public function testTheCoreIsInstalled(string $class): void
    {
        self::assertArrayHasKey((new \ReflectionClass($class))->getShortName(), self::bootKernel()->getBundles());
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function mountedRoutes(): iterable
    {
        yield 'signing in' => ['identity_login', '/login'];
        yield 'signing out' => ['identity_logout', '/logout'];
        yield 'the team' => ['identity_team', '/team'];
    }

    #[DataProvider('mountedRoutes')]
    public function testTheCoresScreensAreMounted(string $name, string $path): void
    {
        self::bootKernel();
        $router = self::getContainer()->get('router');
        self::assertInstanceOf(RouterInterface::class, $router);

        self::assertSame($path, $router->getRouteCollection()->get($name)?->getPath(), $name.' is mounted at '.$path);
    }
}
