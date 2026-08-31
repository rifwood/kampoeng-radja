[33mbeb2d82[m[33m ([m[1;36mHEAD[m[33m -> [m[1;32mdasbboard-fase2[m[33m, [m[1;31morigin/dasbboard-fase2[m[33m)[m chore: snapshot dashboard fase 2
 LOG.md                                             | 324 [32m++++++[m[31m-[m
 app/Actions/ClosingEvent/CreateClosingEvent.php    |  31 [32m+[m
 app/Actions/ClosingEvent/UpdateClosingEvent.php    |  43 [32m+[m
 app/Actions/Employee/SyncEmployeeAccountRole.php   |  44 [32m+[m
 app/Actions/Employee/UpdateEmployee.php            |   9 [32m+[m[31m-[m
 app/Exports/Attendance/DailyAttendanceSheet.php    | 171 [32m++++[m
 app/Exports/Attendance/MonthlyAttendanceExport.php |  45 [32m+[m
 .../Attendance/MonthlyAttendanceSummarySheet.php   | 157 [32m++++[m
 app/Exports/ClosingEvent/ClosingEventsExport.php   | 222 [32m+++++[m
 app/Exports/Employee/EmployeesExport.php           | 211 [32m+++++[m
 app/Http/Controllers/Admin/AbsensiController.php   | 121 [32m++[m[31m-[m
 .../Controllers/Admin/EventPromoController.php     | 128 [32m++[m[31m-[m
 .../Controllers/Admin/GaleriEventController.php    | 233 [32m+++++[m
 app/Http/Controllers/Admin/HomeHeroController.php  |  68 [32m++[m
 app/Http/Controllers/Admin/WahanaController.php    | 300 [32m+++++++[m
 .../ClosingEvent/ClosingEventController.php        | 303 [32m+++++++[m
 .../ClosingEvent/ClosingEventMasterController.php  | 124 [32m+++[m
 app/Http/Controllers/DashboardController.php       | 250 [32m++++[m[31m--[m
 .../Controllers/Employee/EmployeeController.php    | 166 [32m++[m[31m--[m
 app/Http/Controllers/PublicPageController.php      |  98 [32m++[m[31m-[m
 app/Http/Middleware/HandleInertiaRequests.php      |  15 [32m+[m
 app/Http/Requests/Admin/SaveAbsensiRequest.php     |  87 [32m+[m[31m-[m
 app/Http/Requests/Admin/StoreEventPromoRequest.php |  24 [32m+[m[31m-[m
 .../Requests/Admin/StoreGaleriEventRequest.php     |  41 [32m+[m
 app/Http/Requests/Admin/StoreWahanaRequest.php     |  33 [32m+[m
 .../Requests/Admin/UpdateEventPromoRequest.php     |  24 [32m+[m[31m-[m
 .../Requests/Admin/UpdateGaleriEventRequest.php    |  43 [32m+[m
 app/Http/Requests/Admin/UpdateHomeHeroRequest.php  |  88 [32m++[m
 app/Http/Requests/Admin/UpdateWahanaRequest.php    |  35 [32m+[m
 .../ClosingEvent/ClosingEventMasterRequest.php     |  30 [32m+[m
 .../Requests/ClosingEvent/ClosingEventRules.php    |  27 [32m+[m
 .../ClosingEvent/StoreClosingEventRequest.php      |  21 [32m+[m
 .../ClosingEvent/UpdateClosingEventRequest.php     |  39 [32m+[m
 app/Models/Absensi.php                             |   9 [32m+[m
 app/Models/ClosingEvent.php                        |  85 [32m++[m
 app/Models/EventPromo.php                          |  99 [32m+++[m
 app/Models/GaleriEvent.php                         |  13 [32m+[m[31m-[m
 app/Models/GaleriEventFoto.php                     |  20 [32m+[m[31m-[m
 app/Models/HomeHero.php                            |  30 [32m+[m
 app/Models/JenisEvent.php                          |  20 [32m+[m
 app/Models/Lokasi.php                              |  20 [32m+[m
 app/Models/Pic.php                                 |  20 [32m+[m
 app/Models/Wahana.php                              |  48 [32m+[m[31m-[m
 app/Models/WahanaFoto.php                          |  29 [32m+[m
 app/Support/AttendanceAccess.php                   |  40 [32m+[m
 app/Support/ClosingEventAccess.php                 |  61 [32m++[m
 app/Support/WhatsAppNumber.php                     |  55 [32m++[m
 composer.json                                      |   1 [32m+[m
 composer.lock                                      | 785 [32m++++++++++++++[m[31m---[m
 ...26_08_18_000001_create_closing_event_tables.php |  59 [32m++[m
 ...00001_add_attendance_times_to_absensi_table.php |  23 [32m+[m
 ..._add_tanggal_selesai_to_closing_event_table.php |  23 [32m+[m
 ...23_000001_add_status_to_closing_event_table.php |  33 [32m+[m
 ..._add_home_promo_fields_to_event_promo_table.php |  32 [32m+[m
 ...00002_add_visibility_fields_to_wahana_table.php |  23 [32m+[m
 .../2026_08_25_000003_create_wahana_foto_table.php |  26 [32m+[m
 .../2026_08_25_000004_create_home_hero_table.php   |  32 [32m+[m
 database/seeders/ClosingEventMasterSeeder.php      |  38 [32m+[m
 database/seeders/DatabaseSeeder.php                |   5 [32m+[m[31m-[m
 docs/DASHBOARD/ATTENDANCE/PERMISSIONS.md           |  96 [32m++[m[31m-[m
 docs/DASHBOARD/ATTENDANCE/PRD.md                   | 304 [32m+++++[m[31m--[m
 docs/DASHBOARD/ATTENDANCE/UI_SPEC.md               | 154 [32m++[m[31m--[m
 docs/DASHBOARD/CLOSING-EVENT/PERMISSIONS.md        | 229 [32m+++++[m
 docs/DASHBOARD/CLOSING-EVENT/PRD.md                | 950 [32m+++++++++++++++++++++[m
 docs/DASHBOARD/CLOSING-EVENT/README.md             | 150 [32m++++[m
 docs/DASHBOARD/CLOSING-EVENT/UI_SPEC.md            | 533 [32m++++++++++++[m
 docs/DASHBOARD/CLOSING-EVENT/references/README.md  |  33 [32m+[m
 .../CLOSING-EVENT/references/closing_event.png     | Bin [31m0[m -> [32m1397215[m bytes
 .../references/closing_event_master.png            | Bin [31m0[m -> [32m1428211[m bytes
 docs/DASHBOARD/DASHBOARD-HOME/PERMISSIONS.md       |  74 [32m+[m[31m-[m
 docs/DASHBOARD/DASHBOARD-HOME/PRD.md               | 137 [32m+[m[31m--[m
 docs/DASHBOARD/DASHBOARD-HOME/README.md            | 236 [32m+[m[31m----[m
 docs/DASHBOARD/DASHBOARD-HOME/UI_SPEC.md           | 655 [32m+[m[31m-------------[m
 .../DASHBOARD-HOME/references/dashboard-home.png   | Bin [31m82459[m -> [32m1288701[m bytes
 docs/DASHBOARD/EMPLOYEE/PERMISSIONS.md             |  63 [32m+[m[31m-[m
 docs/DASHBOARD/EMPLOYEE/PRD.md                     | 251 [32m++[m[31m----[m
 docs/DASHBOARD/EMPLOYEE/UI_SPEC.md                 |  95 [32m++[m[31m-[m
 docs/DASHBOARD/README.md                           |   8 [32m+[m[31m-[m
 docs/GLOBAL/ACCESS_CONTROL.md                      |   4 [32m+[m[31m-[m
 docs/GLOBAL/ACCESS_CONTROL_MATRIX.md               |  20 [32m+[m[31m-[m
 docs/GLOBAL/AGENT_RULES.md                         |  11 [32m+[m[31m-[m
 docs/GLOBAL/PROJECT_CONTEXT.md                     |   4 [32m+[m[31m-[m
 docs/LANDING-PAGE/ASSETS.md                        |   8 [32m+[m
 docs/LANDING-PAGE/CONTENT.md                       |   2 [32m+[m[31m-[m
 docs/LANDING-PAGE/TODO.md                          |  11 [32m+[m[31m-[m
 docs/README.md                                     |  14 [32m+[m[31m-[m
 .../about/struktur-organisasi-kampoeng-radja.png   | Bin [31m0[m -> [32m873803[m bytes
 public/assets/brand/kampoeng-radja-navbar.png      | Bin [31m0[m -> [32m280907[m bytes
 .../decorations/product-section-crown-divider.png  | Bin [31m0[m -> [32m264801[m bytes
 public/assets/products/temp/istana-balon-temp.png  | Bin [31m0[m -> [32m61678[m bytes
 .../products/temp/lantai-multimedia-temp.png       | Bin [31m0[m -> [32m57810[m bytes
 .../products/temp/outing-class-hero-temp.png       | Bin [31m0[m -> [32m1045045[m bytes
 public/assets/products/temp/outing-class-temp.png  | Bin [31m0[m -> [32m70337[m bytes
 public/assets/products/temp/paket-arisan-temp.png  | Bin [31m0[m -> [32m66412[m bytes
 .../assets/products/temp/paket-birthday-temp.png   | Bin [31m0[m -> [32m66782[m bytes
 .../products/temp/paket-fasilitator-temp.png       | Bin [31m0[m -> [32m55351[m bytes
 .../assets/products/temp/tenda-panggung-temp.png   | Bin [31m0[m -> [32m54612[m bytes
 .../assets/products/temp/wisata-edukasi-temp.png   | Bin [31m0[m -> [32m64531[m bytes
 public/assets/promotions/promo-agus.jpeg           | Bin [31m0[m -> [32m135602[m bytes
 public/assets/promotions/promo-berempat.jpeg       | Bin [31m0[m -> [32m157622[m bytes
 .../assets/promotions/promo-honda-agustus-2026.png | Bin [31m1012521[m -> [32m0[m bytes
 public/assets/promotions/promo-honda.jpeg          | Bin [31m0[m -> [32m187843[m bytes
 .../promotions/promo-ulang-tahun-agustus-2026.png  | Bin [31m698308[m -> [32m0[m bytes
 resources/js/Components/Home/ProductShowcase.vue   | 277 [32m++++++[m
 .../js/Components/Internal/AttendanceTimeInput.vue |  81 [32m++[m
 .../Internal/CMS/Home/CmsSectionCard.vue           |  34 [32m+[m
 .../Components/Internal/CMS/Home/HeroManager.vue   | 179 [32m++++[m
 .../Components/Internal/CMS/Home/PromoManager.vue  | 215 [32m+++++[m
 resources/js/Components/Layout/AppNavbar.vue       |   6 [32m+[m[31m-[m
 resources/js/Layouts/InternalDashboardLayout.vue   |  48 [32m+[m[31m-[m
 resources/js/Pages/Admin/EventPromo/Create.vue     |   8 [32m+[m[31m-[m
 resources/js/Pages/Admin/EventPromo/Edit.vue       |   8 [32m+[m[31m-[m
 resources/js/Pages/Admin/EventPromo/Index.vue      |  10 [32m+[m[31m-[m
 .../Admin/EventPromo/Partials/EventPromoForm.vue   |  23 [32m+[m[31m-[m
 resources/js/Pages/GaleriEvent.vue                 | 187 [32m+++[m[31m-[m
 resources/js/Pages/Home.vue                        | 430 [32m+++++++++[m[31m-[m
 resources/js/Pages/Internal/Absensi/Index.vue      | 485 [32m+++++++++[m[31m--[m
 resources/js/Pages/Internal/CMS/Gallery/Index.vue  | 329 [32m+++++++[m
 resources/js/Pages/Internal/CMS/Home/Index.vue     |  60 [32m++[m
 resources/js/Pages/Internal/CMS/Wahana/Index.vue   | 311 [32m+++++++[m
 .../Internal/ClosingEvent/ClosingEventForm.vue     |  97 [32m+++[m
 .../js/Pages/Internal/ClosingEvent/Create.vue      |   7 [32m+[m
 resources/js/Pages/Internal/ClosingEvent/Edit.vue  |   7 [32m+[m
 resources/js/Pages/Internal/ClosingEvent/Index.vue | 416 [32m+++++++++[m
 .../js/Pages/Internal/ClosingEvent/Masters.vue     | 272 [32m++++++[m
 resources/js/Pages/Internal/ClosingEvent/Show.vue  | 166 [32m++++[m
 resources/js/Pages/Internal/Dashboard/Index.vue    | 630 [32m++++++++[m[31m------[m
 resources/js/Pages/Internal/Employee/Index.vue     | 186 [32m+++[m[31m-[m
 resources/js/Pages/Internal/Employee/Show.vue      | 161 [32m++[m[31m--[m
 resources/js/Pages/TentangKami.vue                 |   6 [32m+[m[31m-[m
 resources/js/Pages/Wahana.vue                      |  68 [32m+[m[31m-[m
 routes/console.php                                 |  49 [32m++[m
 routes/web.php                                     |  63 [32m+[m[31m-[m
 tests/Feature/Admin/EventPromoCrudTest.php         | 160 [32m+++[m[31m-[m
 tests/Feature/Admin/GaleriEventCmsTest.php         | 199 [32m+++++[m
 tests/Feature/Admin/WahanaCmsTest.php              | 236 [32m+++++[m
 tests/Feature/ClosingEventTest.php                 | 291 [32m+++++++[m
 tests/Feature/DashboardHomeTest.php                |  91 [32m+[m[31m-[m
 tests/Feature/EmployeeManagementTest.php           |  94 [32m+[m[31m-[m
 tests/Feature/LandingPageTest.php                  |  16 [32m+[m[31m-[m
 140 files changed, 12882 insertions(+), 2250 deletions(-)
