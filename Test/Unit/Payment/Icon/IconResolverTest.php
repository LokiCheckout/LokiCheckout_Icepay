<?php

declare(strict_types=1);

namespace LokiCheckout\Icepay\Test\Unit\Payment\Icon;

use LokiCheckout\Core\Payment\Icon\IconResolverContext;
use LokiCheckout\Icepay\Payment\Icon\IconResolver;
use PHPUnit\Framework\TestCase;

final class IconResolverTest extends TestCase
{
    public function testResolveReturnsTheMappedIcon(): void
    {
        $iconResolverContext = $this->createIconResolverContext('icepay_ideal');
        $iconResolverContext->expects($this->once())
            ->method('getIconAsset')
            ->with('LokiCheckout_Icepay::images/method/ideal', ['svg'])
            ->willReturn('<svg></svg>');

        $this->assertSame('<svg></svg>', $this->createInstance()->resolve($iconResolverContext));
    }

    public function testResolveIgnoresOtherPaymentMethods(): void
    {
        $iconResolverContext = $this->createIconResolverContext('checkmo');
        $iconResolverContext->expects($this->never())->method('getIconAsset');

        $this->assertFalse($this->createInstance()->resolve($iconResolverContext));
    }

    public function testResolveIgnoresAnUnmappedIcepayMethod(): void
    {
        $iconResolverContext = $this->createIconResolverContext('icepay_unknown');
        $iconResolverContext->expects($this->never())->method('getIconAsset');

        $this->assertFalse($this->createInstance()->resolve($iconResolverContext));
    }

    private function createIconResolverContext(string $paymentMethodCode): IconResolverContext
    {
        $iconResolverContext = $this->createMock(IconResolverContext::class);
        $iconResolverContext->method('getPaymentMethodCode')->willReturn($paymentMethodCode);

        return $iconResolverContext;
    }

    private function createInstance(): IconResolver
    {
        return new IconResolver([
            'icepay_ideal' => 'LokiCheckout_Icepay::images/method/ideal',
        ]);
    }
}
