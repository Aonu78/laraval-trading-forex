# Profile Health on User Sidebar TODO

## Plan:
**Information Gathered:**
- profileHealth calculated in Backend/ManageUserController::calculateProfileHealth() (0-100% score based on email/KYC/phone/image/address)
- Need same calculation for frontend dashboard sidebar.
- Replace support ticket link in 3 sidebars with health progress bar.

**Files:**
1. app/Services/UserDashboardService.php: Add calculateProfileHealth() + $data['profileHealth'] = $this->calculateProfileHealth($user);
2. 3 sidebars: Replace support ticket <li> with health div matching admin style.

**Followup:** view:clear

Ready? Confirm.
