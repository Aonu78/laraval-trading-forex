# Plain Password Implementation TODO

- [x] 1. Create migration for `plain_password` column on `users` table
- [x] 2. Update `UserRegistration.php` to save `plain_password` on register
- [x] 3. Update `AdminUserService.php` to update both passwords from admin
- [x] 4. Update `AdminUserRequest.php` validation rules for password fields
- [x] 5. Update `details.blade.php` to show/edit plain and encrypted passwords
- [x] 6. Update `ForgotPasswordController.php` to sync `plain_password` on reset
- [x] 7. Update `UserController.php` to sync `plain_password` on password change
- [x] 8. Update `GoogleController.php` to save `plain_password`
- [x] 9. Update `FacebookController.php` to save `plain_password`
- [x] 10. Run the migration

