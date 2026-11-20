<?php

declare(strict_types=1);

namespace LokiCheckout\Icepay\Test\Integration\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\Redirect;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Test\Fixture\PlaceOrder as PlaceOrderFixture;
use Magento\Checkout\Test\Fixture\SetBillingAddress as SetBillingAddressFixture;
use Magento\Checkout\Test\Fixture\SetDeliveryMethod as SetDeliveryMethodFixture;
use Magento\Checkout\Test\Fixture\SetGuestEmail as SetGuestEmailFixture;
use Magento\Checkout\Test\Fixture\SetPaymentMethod as SetPaymentMethodFixture;
use Magento\Checkout\Test\Fixture\SetShippingAddress as SetShippingAddressFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use PHPUnit\Framework\TestCase;

#[
    AppArea('frontend'),
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    DataFixture(SetBillingAddressFixture::class, ['cart_id' => '$cart.id$']),
    DataFixture(SetShippingAddressFixture::class, ['cart_id' => '$cart.id$']),
    DataFixture(SetGuestEmailFixture::class, ['cart_id' => '$cart.id$']),
    DataFixture(SetDeliveryMethodFixture::class, ['cart_id' => '$cart.id$']),
    DataFixture(SetPaymentMethodFixture::class, ['cart_id' => '$cart.id$']),
    DataFixture(PlaceOrderFixture::class, ['cart_id' => '$cart.id$'], as: 'order'),
]
final class RedirectTest extends TestCase
{
    protected function tearDown(): void
    {
        ObjectManager::getInstance()->get(CheckoutSession::class)->setLastOrderId(null);
    }

    final public function testIcepayOrderRedirectsToIcepay(): void
    {
        $this->setLastOrder('icepay_ideal');

        $url = ObjectManager::getInstance()->create(Redirect::class)->get();

        $this->assertStringContainsString('icepay/payment/redirect', $url);
    }

    final public function testOtherOrderRedirectsToTheSuccessPage(): void
    {
        $this->setLastOrder('checkmo');

        $url = ObjectManager::getInstance()->create(Redirect::class)->get();

        $this->assertStringContainsString('checkout/onepage/success', $url);
    }

    private function setLastOrder(string $paymentMethodCode): void
    {
        $order = DataFixtureStorageManager::getStorage()->get('order');
        $this->assertInstanceOf(OrderInterface::class, $order);

        $orderRepository = ObjectManager::getInstance()->get(OrderRepositoryInterface::class);
        $order = $orderRepository->get($order->getEntityId());
        $order->getPayment()->setMethod($paymentMethodCode);
        $orderRepository->save($order);

        ObjectManager::getInstance()->get(CheckoutSession::class)->setLastOrderId($order->getEntityId());
    }
}
