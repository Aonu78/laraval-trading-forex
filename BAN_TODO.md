# Ban User Feature TODO

## Steps
1. [x] Migration created (`2026_03_16_205155_add_is_banned_to_users_table.php`)
2. [x] Model cast added (`app/Models/User.php`)
3. [ ] Run `php artisan migrate --path=database/migrations/2026_03_16_205155_add_is_banned_to_users_table.php`
4. [ ] Add ban link to view (`resources/views/backend/users/details.blade.php`)
5. [ ] Add ban method to ManageUserController.php
6. [ ] Add route to routes/admin.php
7. [ ] Add ban check to user login (UserLogin service or middleware)

**Proceed?**
