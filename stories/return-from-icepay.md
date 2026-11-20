%title: Return from ICEPAY
%status: complete
---

**As a** shopper,  
**I want to** come back to the shop after paying on ICEPAY,  
**So that** I know whether my order went through.

## Acceptance Criteria

### when-the-payment-is-completed

**Given** I was sent to ICEPAY after placing my order  
**When** I complete the payment  
**Then** I am sent back to the success page of the shop.

### when-the-payment-is-not-completed

**Given** I was sent to ICEPAY after placing my order  
**When** the payment expires or fails  
**Then** I am sent back to the shopping cart  
**And** my cart still holds the products of the order.
