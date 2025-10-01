<?php declare(strict_types=1);

namespace LokiCheckout\Icepay\Payment\Icon;

use LokiCheckout\Core\Payment\Icon\IconResolverContext;
use LokiCheckout\Core\Payment\Icon\IconResolverInterface;

class IconResolver implements IconResolverInterface
{
    public function __construct(
        private array $iconMapping = [],
    ) {
    }

    public function resolve(IconResolverContext $iconResolverContext): false|string
    {
        $paymentMethodCode = $iconResolverContext->getPaymentMethodCode();
        if (!preg_match('/^icepay_(.*)$/', $paymentMethodCode)) {
            return false;
        }

        if (false === array_key_exists($paymentMethodCode, $this->iconMapping)) {
            return false;
        }

        $imageId = $this->iconMapping[$paymentMethodCode];
        return $iconResolverContext->getIconOutput($imageId);
    }
}
