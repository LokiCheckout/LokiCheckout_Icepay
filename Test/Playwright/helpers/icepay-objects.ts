import {expect, Page} from '@playwright/test';
import {PaymentMethod, PlaceOrderButton, ShippingMethod} from '@loki-checkout/checkout-objects';

export const ICEPAY_CHECKOUT_URL = /^https:\/\/checkout\.icepay\.com\/checkout\/[^/]+\/test\//;

export async function placeIcepayOrder(page: Page, methodCode: string) {
    await (new ShippingMethod(page as any, 'flatrate_flatrate')).select();
    await (new PaymentMethod(page as any, methodCode)).select();
    await (new PlaceOrderButton(page as any)).click();
}

/**
 * The hosted ICEPAY test payment page, offering the buttons "Complete", "Expire" and "Back"
 */
export class IcepayTestPaymentPage {
    page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async expectToBeLoaded(paymentType: string) {
        await expect(this.page).toHaveURL(ICEPAY_CHECKOUT_URL, {timeout: 60000});
        await expect(this.page.getByText('This is a test payment made with ' + paymentType)).toBeVisible({timeout: 30000});
    }

    async complete() {
        await this.page.getByRole('button', {name: 'Complete', exact: true}).click();
    }

    async expire() {
        await this.page.getByRole('button', {name: 'Expire', exact: true}).click();
    }
}
