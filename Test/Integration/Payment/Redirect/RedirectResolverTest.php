<?php

declare(strict_types=1);

namespace LokiCheckout\Icepay\Test\Integration\Payment\Redirect;

use LokiCheckout\Core\Payment\Redirect\RedirectResolverListing;
use LokiCheckout\Icepay\Payment\Redirect\RedirectResolver;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
final class RedirectResolverTest extends TestCase
{
    final public function testRedirectResolverIsListed(): void
    {
        $redirectResolvers = ObjectManager::getInstance()->get(RedirectResolverListing::class)->getRedirectResolvers();

        $matches = array_filter($redirectResolvers, fn ($resolver) => $resolver instanceof RedirectResolver);
        $this->assertCount(1, $matches);
    }
}
