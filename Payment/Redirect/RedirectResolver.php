<?php declare(strict_types=1);

namespace LokiCheckout\Icepay\Payment\Redirect;

use Magento\Framework\UrlFactory;
use LokiCheckout\Core\Payment\Redirect\RedirectResolverInterface;
use LokiCheckout\Core\Step\FinalStep\RedirectContext;

class RedirectResolver implements RedirectResolverInterface
{
    public function __construct(
        private UrlFactory $urlFactory,
    ) {
    }

    public function resolve(RedirectContext $redirectContext): false|string
    {
        $paymentMethod = $redirectContext->getPaymentMethod();
        if (false === $paymentMethod || false === str_starts_with($paymentMethod->getCode(), 'icepay_')) {
            return false;
        }

        return $this->urlFactory->create()->getUrl('icepay/payment/redirect');
    }
}
