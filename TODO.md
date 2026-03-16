# Add Trade Profit Percent Field - Implementation TODO

## Approved Plan Steps (6/6 remaining)

### 1. [x] Create Migration
- Ran: `php artisan make:migration add_trade_profit_percent_to_users_table --table=users` (2026_03_16_200449_add_trade_profit_percent_to_users_table.php)
- Edited migration file

### 2. [x] Update Model (app/Models/User.php)
- Added cast `'trade_profit_percent' => 'integer'`

### 3. [x] Update View (resources/views/backend/users/details.blade.php)
- Added input field next to trade_win_rate

### 4. [x] Update Validation (app/Http/Requests/AdminUserRequest.php)
- Added `'trade_profit_percent' => 'required|integer|between:0,100'`

### 5. [x] Update Service (app/Services/AdminUserService.php)
- Added save `$user->trade_profit_percent = $request->trade_profit_percent;`

### 6. [x] Update Trade Controller (app/Http/Controllers/CryptoTradeController.php)
- **Fixed**: profit = stake * (percent/100) **ignores market move**, loss = full stake

## Post-Implementation
- [x] Migration exists
- [x] **Fixed profit % of stake** (100 stake, 100% = 100 profit guaranteed on win)
- [x] Ready - test new trade with 100% settings
