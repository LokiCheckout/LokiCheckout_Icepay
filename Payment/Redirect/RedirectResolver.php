<?php declare(strict_types=1);

namespace Yireo\LokiCheckoutIcepay\Payment\Redirect;

use Magento\Framework\UrlFactory;
use Yireo\LokiCheckout\Payment\Redirect\RedirectResolverInterface;
use Yireo\LokiCheckout\Step\FinalStep\RedirectContext;

class RedirectResolver implements RedirectResolverInterface
{
    public function __construct(
        private UrlFactory $urlFactory,
    ) {
    }

    public function resolve(RedirectContext $redirectContext): false|string
    {
        if (str_starts_with($redirectContext->getPaymentMethod(), 'icepay_icepay')) {
            return $this->urlFactory->create()->getUrl('icepay/payment/redirect');
        }

        return false;
    }
}
