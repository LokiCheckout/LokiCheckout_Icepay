<?php

declare(strict_types=1);

namespace LokiCheckout\Icepay\Test\Unit\Payment\Redirect;

use LokiCheckout\Core\Step\FinalStep\RedirectContext;
use LokiCheckout\Icepay\Payment\Redirect\RedirectResolver;
use Magento\Framework\UrlFactory;
use Magento\Framework\UrlInterface;
use Magento\Payment\Model\MethodInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RedirectResolverTest extends TestCase
{
    public static function getIcepayMethodCodes(): array
    {
        return [
            ['icepay_bancontact'],
            ['icepay_creditcard'],
            ['icepay_eps'],
            ['icepay_ideal'],
            ['icepay_onlineueberweisen'],
            ['icepay_paypal'],
            ['icepay_sofort'],
        ];
    }

    #[DataProvider('getIcepayMethodCodes')]
    public function testResolveReturnsTheIcepayRedirectUrl(string $methodCode): void
    {
        $actual = $this->createInstance(true)->resolve($this->createRedirectContext($methodCode));

        $this->assertSame('https://example.com/icepay/payment/redirect/', $actual);
    }

    public function testResolveIgnoresOtherPaymentMethods(): void
    {
        $this->assertFalse($this->createInstance(false)->resolve($this->createRedirectContext('checkmo')));
    }

    public function testResolveIgnoresAMissingPaymentMethod(): void
    {
        $redirectContext = $this->createMock(RedirectContext::class);
        $redirectContext->method('getPaymentMethod')->willReturn(false);

        $this->assertFalse($this->createInstance(false)->resolve($redirectContext));
    }

    private function createRedirectContext(string $methodCode): RedirectContext
    {
        $paymentMethod = $this->createMock(MethodInterface::class);
        $paymentMethod->method('getCode')->willReturn($methodCode);

        $redirectContext = $this->createMock(RedirectContext::class);
        $redirectContext->method('getPaymentMethod')->willReturn($paymentMethod);

        return $redirectContext;
    }

    private function createInstance(bool $expectsUrl): RedirectResolver
    {
        $url = $this->createMock(UrlInterface::class);
        $url->expects($expectsUrl ? $this->once() : $this->never())
            ->method('getUrl')
            ->with('icepay/payment/redirect')
            ->willReturn('https://example.com/icepay/payment/redirect/');

        $urlFactory = $this->createMock(UrlFactory::class);
        $urlFactory->method('create')->willReturn($url);

        return new RedirectResolver($urlFactory);
    }
}
