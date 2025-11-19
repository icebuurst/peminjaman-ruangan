Repository functions & methods inventory

Summary
- Grep found ~200 function/method occurrences across the workspace.
- Notable places with top-level functions: `public/webhook.php` (deployment webhook helpers) and many JS functions inside Blade views.
- Main PHP app code uses class methods (controllers, models, exports). No DB stored procedures or SQL trigger files found in repo.

Top-level (global) PHP functions
- public/webhook.php:
  - handleWebhook($config)
  - deploy($config)
  - executeCommand($command, $config)
  - verifySignature($payload, $githubSignature, $secret)
  - logMessage($message, $config)
  - showInfoPage($config)

Controllers (main app folder) — key controllers and their public methods
- app/Http/Controllers/BookingController.php
  - index(), create(), store(Request), show(string), edit(string), update(Request,string), updateStatus(Request,string), destroy(string), laporan(Request), export(Request)
- app/Http/Controllers/JadwalRegulerController.php
  - index(), create(), store(Request), show(string), edit(string), update(Request,string), destroy(string)
- app/Http/Controllers/RoomController.php
  - index(), create(), store(Request), show(string), edit(string), update(Request,string), destroy(string)
- app/Http/Controllers/UserController.php
  - index(), create(), store(Request), edit($id), update(Request,$id), destroy($id)
- app/Http/Controllers/NotificationController.php
  - index(), unreadCount(), markAsRead($id), markAllAsRead()
- app/Http/Controllers/AuthController.php
  - showLogin(), login(Request), register(Request), logout(Request)
- app/Http/Controllers/DashboardController.php
  - index()
- app/Http/Controllers/AppController.php
  - index()

Models — notable methods (relations/scopes/helpers)
- app/Models/User.php
  - casts(), bookings(), notifications(), unreadNotificationsCount()
- app/Models/Room.php
  - bookings(), jadwalReguler()
- app/Models/Booking.php
  - user(), room()
- app/Models/JadwalReguler.php
  - room()
- app/Models/Notification.php
  - user(), scopeUnread($query), scopeRecent($query,$limit=10), markAsRead()

Exports & Helpers
- app/Exports/BookingsExport.php
  - __construct($startDate,$endDate), collection(), headings(): array, map($booking): array, styles(Worksheet), columnWidths(): array, private getStatusText($status)

Blade/JS functions (examples)
- resources/views/bookings/laporan.blade.php
  - resetFilter()
- resources/views/jadwal-reguler/index.blade.php
  - switchView(view, event)
- resources/views/layouts/app.blade.php and multiple compiled view files include many client-side helper functions: toggleSidebar(), showToast(), confirmDelete(), loadNotifications(), updateNotificationBadge(), etc.

Notes & issues observed
- No SQL stored procedures / triggers found in repository files.
- Laravel encryption is available (framework). No repository-wide custom encrypt()/decrypt() usages were found in app code during prior checks.
- Storage: uploaded room images exist under `storage/app/public/rooms` as hashed filenames; some DB `foto` values may not match hashed names (view-level fallback was implemented earlier).
- Logs show multiple "Call to a member function format() on null" errors coming from views (dashboard, jadwal-reguler edit, rooms edit) — indicates some date/time values may be null and need defensive checks.

Next steps you can ask me to run now
- Produce a full per-file list (CSV/JSON) of all functions and method signatures.
- Implement a non-destructive view-level fallback to match room image basenames in storage.
- Tighten booking overlap check to include time overlap (same-day, same-room checks).

If you want, I can now generate a CSV/JSON with exact file paths and signatures, or implement any of the three next steps above.
