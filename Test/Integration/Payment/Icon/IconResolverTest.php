<?php

declare(strict_types=1);

namespace LokiCheckout\Icepay\Test\Integration\Payment\Icon;

use LokiCheckout\Core\Payment\Icon\IconResolverContext;
use LokiCheckout\Core\Payment\Icon\IconResolverListing;
use LokiCheckout\Icepay\Payment\Icon\IconResolver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
final class IconResolverTest extends TestCase
{
    final public function testIconResolverIsListed(): void
    {
        $iconResolvers = ObjectManager::getInstance()->get(IconResolverListing::class)->getIconResolvers();

        $matches = array_filter($iconResolvers, fn ($resolver) => $resolver instanceof IconResolver);
        $this->assertCount(1, $matches);
    }

    final public function testEveryIcepayPaymentMethodHasAnIcon(): void
    {
        $methodCodes = $this->getIcepayMethodCodes();
        $this->assertCount(7, $methodCodes);

        $iconResolver = ObjectManager::getInstance()->get(IconResolver::class);
        foreach ($methodCodes as $methodCode) {
            $actual = $iconResolver->resolve($this->createIconResolverContext($methodCode));

            $this->assertIsString($actual, $methodCode);
            $this->assertStringContainsString('<svg', $actual, $methodCode);
        }
    }

    final public function testResolveIgnoresOtherPaymentMethods(): void
    {
        $iconResolver = ObjectManager::getInstance()->get(IconResolver::class);

        $this->assertFalse($iconResolver->resolve($this->createIconResolverContext('checkmo')));
    }

    private function createIconResolverContext(string $methodCode): IconResolverContext
    {
        return ObjectManager::getInstance()->create(IconResolverContext::class, [
            'paymentMethodCode' => $methodCode,
        ]);
    }

    private function getIcepayMethodCodes(): array
    {
        $paymentConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class)->getValue('payment');

        return array_values(array_filter(
            array_keys((array)$paymentConfig),
            fn (string $code) => str_starts_with($code, 'icepay_')
        ));
    }
}
