%title: Show the ICEPAY payment methods
%status: complete
---

**As a** shopper,  
**I want to** see the ICEPAY payment methods with their logos in the checkout,  
**So that** I can recognise and choose the way I want to pay.

## Acceptance Criteria

### when-icepay-is-enabled

**Given** ICEPAY is enabled with a merchant ID and secret  
**And** the payment methods Bancontact, Credit Card, EPS, iDEAL, Online Überweisen, PayPal and SOFORT are enabled  
**When** I open the payment step of the checkout  
**Then** I see all seven ICEPAY payment methods  
**And** each payment method shows its own logo.

### when-an-icepay-payment-method-is-chosen

**Given** I see the ICEPAY payment methods  
**When** I choose iDEAL and reload the page  
**Then** iDEAL is still selected.

### when-icepay-is-disabled

**Given** ICEPAY is disabled  
**When** I open the payment step of the checkout  
**Then** I do not see any ICEPAY payment method  
**And** I can still choose the other payment methods.
