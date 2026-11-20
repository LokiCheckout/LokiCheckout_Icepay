import {SuccessPage} from '@loki-checkout/checkout-objects';
import {setupCheckout} from '@loki/setup-checkout';
import {test, expect} from '@loki/test';

import {IcepayTestPaymentPage, placeIcepayOrder} from './helpers/icepay-objects';
import moduleConfig, {hasSandboxCredentials} from './config/config';

test.describe('ICEPAY redirect test', () => {
    test.skip(!hasSandboxCredentials, 'ICEPAY_MERCHANT_ID and ICEPAY_MERCHANT_SECRET are not set');

    for (const [methodCode, paymentType] of [['icepay_ideal', 'ideal'], ['icepay_bancontact', 'bancontact']]) {
        test('should send me to ICEPAY after placing an order with ' + methodCode, async ({page, context}) => {
            const redirects: string[] = [];
            page.on('framenavigated', frame => {
                if (frame === page.mainFrame()) {
                    redirects.push(frame.url());
                }
            });

            await setupCheckout(page as any, context, moduleConfig);
            await placeIcepayOrder(page, methodCode);

            await new IcepayTestPaymentPage(page).expectToBeLoaded(paymentType);
            expect(redirects.some(url => url.includes('/checkout/onepage/success')), 'No success page before paying').toBe(false);
        });
    }

    test('should show the success page once the ICEPAY payment is completed', async ({page, context}) => {
        await setupCheckout(page as any, context, moduleConfig);
        await placeIcepayOrder(page, 'icepay_ideal');

        const icepayPage = new IcepayTestPaymentPage(page);
        await icepayPage.expectToBeLoaded('ideal');
        await icepayPage.complete();

        await new SuccessPage(page as any).expectToBeLoaded();
    });

    test('should restore my cart when the ICEPAY payment expires', async ({page, context}) => {
        await setupCheckout(page as any, context, moduleConfig);
        await placeIcepayOrder(page, 'icepay_ideal');

        const icepayPage = new IcepayTestPaymentPage(page);
        await icepayPage.expectToBeLoaded('ideal');
        await icepayPage.expire();

        await expect(page).toHaveURL(/\/checkout\/cart\/?$/, {timeout: 60000});
        await expect(page.locator('body')).not.toContainText('You have no items in your shopping cart.');
        await new SuccessPage(page as any).expectNotToBeLoaded();
    });
});
