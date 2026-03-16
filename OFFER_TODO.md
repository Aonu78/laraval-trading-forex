# Exclusive Offer Feature TODO

## Plan Steps
1. [ ] Add textarea to `resources/views/backend/setting/general_setting.blade.php`
2. [ ] Update `app/Services/ConfigurationService.php::general()` to save `'exclusive_offer' => $request->exclusive_offer`
3. [ ] Ensure `Config::config()->exclusive_offer` works everywhere
4. [ ] Pass to user dashboard views via controller/service (e.g., `UserDashboardService`)
5. [ ] Test save/display

**User dashboard pages**: Show `{{ Config::config()->exclusive_offer }}` where needed.

Ready?
