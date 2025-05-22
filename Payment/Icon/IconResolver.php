<?php declare(strict_types=1);

namespace Yireo\LokiCheckoutIcepay\Payment\Icon;

use Yireo\LokiCheckout\Payment\Icon\IconResolverContext;
use Yireo\LokiCheckout\Payment\Icon\IconResolverInterface;
use Yireo\LokiFieldComponents\ViewModel\ImageOutput;

class IconResolver implements IconResolverInterface
{
    public function __construct(
        private ImageOutput $imageOutput,
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
        return $this->imageOutput->get($imageId);
    }
}
