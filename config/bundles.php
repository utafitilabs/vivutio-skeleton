<?php

declare(strict_types=1);

/*
 * This file is part of the vivutio skeleton.
 *
 * (c) Ezekiel Mjema <https://github.com/eemjema>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Vivutio\Bundle\RegistryBundle\RegistryBundle::class => ['all' => true],
    Vivutio\Bundle\IdentityBundle\IdentityBundle::class => ['all' => true],
    Vivutio\Bundle\ShellBundle\ShellBundle::class => ['all' => true],
    Vivutio\Bundle\PlaceBundle\PlaceBundle::class => ['all' => true],
    Vivutio\Bundle\PartnerBundle\PartnerBundle::class => ['all' => true],
    Vivutio\Bundle\ConnectBundle\ConnectBundle::class => ['all' => true],
];
