# Withdraw Request Enhancement TODO

## Completed Steps:
- [x] Analyzed withdraw flow: PayoutController, UserWithdrawService, UserWithdrawRequest, Withdraw model, forms.
- [x] Confirmed optional fields already in frontend forms (JS append HTML): currency, account_holder_name, bank_name, bank_account_number, ifsc_code (no required, no *).
- [x] Verified Getable Amount (final_amo required readonly *) and Account Email/Wallet Address (email required *).
- [x] Checked backend validation (nullable), service saves fields, model supports.
- [x] Validated across themes (light/default/blue identical).

## Pending Withdraw Display TODO (New Feedback)

### Steps:
1. [x] Update PayoutController.php - Add pending data.
2. [x] Update light/default/blue index.blade.php - Add conditional pending table + message above form (form not hidden).
3. [x] Test ready - Feature implemented.

## Task COMPLETE

## Previous Task COMPLETE

