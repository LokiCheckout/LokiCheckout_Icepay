<?php

declare(strict_types=1);

namespace LokiCheckout\Icepay\Test\Integration;

use LokiCheckout\Core\Config\LokiCheckoutModules;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsEnabled;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsRegistered;

final class ModuleTest extends TestCase
{
    use AssertModuleIsEnabled;
    use AssertModuleIsRegistered;

    final public function testModule(): void
    {
        foreach (['LokiCheckout_Icepay', 'LokiCheckout_Core', 'Icepay_Payment', 'Magento_Store'] as $moduleName) {
            $this->assertModuleIsEnabled($moduleName);
            $this->assertModuleIsRegistered($moduleName);
        }
    }

    final public function testModuleIsRegisteredAsLokiCheckoutModule(): void
    {
        $lokiCheckoutModules = ObjectManager::getInstance()->get(LokiCheckoutModules::class);

        $this->assertTrue($lokiCheckoutModules->hasModule('LokiCheckout_Icepay'));
    }
}
