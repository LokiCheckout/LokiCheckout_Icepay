import coreConfig from '@loki-checkout/config';

export const merchantId = process.env.ICEPAY_MERCHANT_ID || '';
export const merchantSecret = process.env.ICEPAY_MERCHANT_SECRET || '';
export const hasSandboxCredentials = merchantId !== '' && merchantSecret !== '';

export const icepayMethods = [
    'icepay_bancontact',
    'icepay_creditcard',
    'icepay_eps',
    'icepay_ideal',
    'icepay_onlineueberweisen',
    'icepay_paypal',
    'icepay_sofort',
];

const methodConfig = Object.fromEntries(
    icepayMethods.map(code => ['payment/icepay/' + code.replace('icepay_', '') + '/active', 1])
);

export default {
    ...coreConfig,
    modules: [
        'LokiCheckout_Icepay',
        'Icepay_Payment',
    ],
    secure_config: {
        'payment/icepay/merchant_secret': merchantSecret || 'loki-test-secret',
    },
    config: {
        ...coreConfig.config,
        'currency/options/base': 'EUR',
        'currency/options/default': 'EUR',
        'currency/options/allow': 'EUR',
        'payment/icepay/active': 1,
        'payment/icepay/merchant_id': merchantId || 'loki-test-merchant',
        ...methodConfig,
    }
};
