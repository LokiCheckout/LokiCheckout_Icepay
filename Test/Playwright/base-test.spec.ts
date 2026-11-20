import {PaymentMethod, ShippingMethod} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';

import moduleConfig, {icepayMethods} from './config/config';

test.describe('LokiCheckout_Icepay test', () => {
    test('should show all ICEPAY payment methods with their icons', async ({page, context}) => {
        await setupCheckout(page as any, context, moduleConfig);
        await (new ShippingMethod(page as any, 'flatrate_flatrate')).select();

        for (const methodCode of icepayMethods) {
            const paymentMethod = new PaymentMethod(page as any, methodCode);
            await expect(paymentMethod.getLocator(), methodCode).toBeAttached();
            await expect(paymentMethod.getLocator().locator('xpath=..').locator('svg'), methodCode).toHaveCount(1);
        }

        await (new PaymentMethod(page as any, 'icepay_ideal')).select();
        await page.reload();
        await expect(new PaymentMethod(page as any, 'icepay_ideal').getLocator()).toBeChecked();
    });

    test('should hide ICEPAY when ICEPAY is disabled', async ({page, context}) => {
        await setupCheckout(page as any, context, {
            ...moduleConfig,
            config: {
                ...moduleConfig.config,
                'payment/icepay/active': 0,
            },
        });
        await (new ShippingMethod(page as any, 'flatrate_flatrate')).select();

        await expect(new PaymentMethod(page as any, 'checkmo').getLocator()).toBeAttached();
        for (const methodCode of icepayMethods) {
            await expect(new PaymentMethod(page as any, methodCode).getLocator(), methodCode).toHaveCount(0);
        }
    });
});
