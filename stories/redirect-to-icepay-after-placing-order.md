%title: Redirect to ICEPAY after placing the order
%status: complete
---

**As a** shopper,  
**I want to** be sent to ICEPAY after placing my order with an ICEPAY payment method,  
**So that** I can pay for my order.

## Acceptance Criteria

### when-the-order-is-placed-with-icepay

**Given** I chose an ICEPAY payment method (for instance iDEAL or Bancontact)  
**When** I place the order  
**Then** I am sent to the ICEPAY payment page for that payment method  
**And** I do not see the success page before I have paid.

### when-the-order-is-placed-with-another-payment-method

**Given** I chose a payment method that is not ICEPAY  
**When** I place the order  
**Then** I am not sent to ICEPAY.
