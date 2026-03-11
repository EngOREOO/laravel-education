# رودماب ليكتشرز Laravel

## الهدف من الرودماب

الملف ده بيحوّل المحتوى الموجود في المشروع إلى خطة شرح متدرجة وواضحة:

- يبدأ من التأسيس للمبتدئ.
- يستفيد من ملفات الشرح العربية الموجودة خارج `docs-12.x`.
- يبني بعدها مسار كامل اعتمادًا على توثيق Laravel 12 داخل `docs-12.x`.
- يفصل بين المسار الإجباري والمسارات المتقدمة والاختيارية.

---

## 1) المواد المقروءة خارج `docs-12.x`

دي المواد التأسيسية الموجودة بالفعل، ويفضل استخدامها في بداية الكورس:

- `laravel-setup-guide.md`: إنشاء المشروع، ربط قاعدة البيانات، Breeze، التشغيل الأول.
- `laravel-env-guide.md`: فهم ملف `.env` والإعدادات الأساسية.
- `شرح_MVC.md` و `laravel-mvc-detailed-guide.md`: فهم MVC ودورة الطلب.
- `laravel-models-guide.md`: أساسيات الـ Model وعمليات Eloquent الأساسية.
- `laravel-controllers-guide.md`: الـ Controllers وResource Controllers وPatterns مهمة.
- `laravel-migration-deep-guide.md`: Migrations وأنواع الأعمدة والتعديل على الجداول.
- `laravel-relationships-guide.md`: العلاقات الأساسية والمتقدمة.
- `(Advanced) laravel-crud-blog-guide.md`: مشروع CRUD عملي يربط المفاهيم ببعض.

ملاحظة:
الملفات `AGENTS.md`, `AGENT.md`, `CLAUDE.md` تعليمات عمل للأدوات وليست مادة شرح Laravel، و`project/README.md` و`README.md` ليسا مصدرًا تعليميًا أساسيًا للكورس.

---

## 2) فلسفة ترتيب الشرح

الترتيب المقترح هنا مبني على:

- التدرج من تشغيل المشروع إلى فهم البنية.
- الانتقال من HTTP layer إلى View ثم Database.
- تأخير الموضوعات المتقدمة مثل Queues / Broadcasting / Octane / Scout إلى ما بعد الأساسيات.
- فصل أدوات الإنتاجية والنشر والمراقبة عن المسار الأساسي حتى لا يتشتت الطالب.

---

## 3) المسار الإجباري الأساسي

ده هو المسار الذي أنصح أن يتشرح بالكامل لأي طالب Laravel.

### المرحلة الأولى: التأسيس وفهم البيئة

#### Lecture 1: Laravel Intro + Installation
- المصادر:
  - `docs-12.x/installation.md`
  - `docs-12.x/releases.md`
  - `docs-12.x/structure.md`
- الهدف:
  - Laravel إيه، وليه نستخدمه، وإيه الجديد في Laravel 12.
  - إنشاء مشروع جديد وفهم شكل المشروع.

#### Lecture 2: Setup عملي للمشروع
- المصادر:
  - `laravel-setup-guide.md`
  - `docs-12.x/starter-kits.md`
  - `docs-12.x/frontend.md`
- الهدف:
  - إنشاء مشروع فعلي.
  - فهم Starter Kits.
  - تشغيل التطبيق لأول مرة.

#### Lecture 3: Environment + Configuration
- المصادر:
  - `laravel-env-guide.md`
  - `docs-12.x/configuration.md`
- الهدف:
  - فهم `.env`.
  - config files.
  - الفرق بين local / production / testing.

#### Lecture 4: Request Lifecycle + Directory Structure
- المصادر:
  - `docs-12.x/lifecycle.md`
  - `docs-12.x/structure.md`
  - `شرح_MVC.md`
- الهدف:
  - الطلب يدخل Laravel إزاي.
  - دور `public`, `routes`, `app`, `bootstrap`, `config`, `resources`.

---

### المرحلة الثانية: MVC والـ HTTP Layer

#### Lecture 5: MVC Fundamentals
- المصادر:
  - `شرح_MVC.md`
  - `laravel-mvc-detailed-guide.md`
- الهدف:
  - العلاقة بين Model وView وController.
  - Route -> Controller -> Model -> View.

#### Lecture 6: Routing
- المصادر:
  - `docs-12.x/routing.md`
  - `docs-12.x/urls.md`
- الهدف:
  - أنواع الـ routes.
  - parameters.
  - named routes.
  - route model binding.

#### Lecture 7: Controllers
- المصادر:
  - `laravel-controllers-guide.md`
  - `docs-12.x/controllers.md`
- الهدف:
  - controllers العادية.
  - resource controllers.
  - dependency injection.

#### Lecture 8: Requests + Responses
- المصادر:
  - `docs-12.x/requests.md`
  - `docs-12.x/responses.md`
  - `docs-12.x/csrf.md`
- الهدف:
  - التعامل مع input وfiles.
  - response types.
  - redirects.
  - CSRF.

#### Lecture 9: Middleware
- المصادر:
  - `docs-12.x/middleware.md`
- الهدف:
  - middleware إيه ودورها في الحماية والتنظيم.
  - global vs route middleware.

#### Lecture 10: Validation
- المصادر:
  - `docs-12.x/validation.md`
  - `laravel-controllers-guide.md`
- الهدف:
  - validation quickstart.
  - Form Request Validation.
  - رسائل الأخطاء.

---

### المرحلة الثالثة: Views وBlade وواجهة التطبيق

#### Lecture 11: Views Basics
- المصادر:
  - `docs-12.x/views.md`
  - `docs-12.x/blade.md`
- الهدف:
  - view rendering.
  - passing data.
  - layouts.

#### Lecture 12: Blade Deep Dive
- المصادر:
  - `docs-12.x/blade.md`
- الهدف:
  - directives.
  - components.
  - slots.
  - stacks.
  - forms in Blade.

#### Lecture 13: Frontend Tooling
- المصادر:
  - `docs-12.x/vite.md`
  - `docs-12.x/frontend.md`
- الهدف:
  - ربط CSS / JS.
  - Vite.
  - متى نستخدم Blade فقط ومتى ندخل Vue / React / Svelte.

#### Lecture 14: Practical MVC Mini Project
- المصادر:
  - `(Advanced) laravel-crud-blog-guide.md`
  - `laravel-mvc-detailed-guide.md`
- الهدف:
  - تطبيق حي يربط routing + controller + model + views.

---

### المرحلة الرابعة: قاعدة البيانات

#### Lecture 15: Database Basics
- المصادر:
  - `docs-12.x/database.md`
  - `laravel-env-guide.md`
- الهدف:
  - الاتصال بقاعدة البيانات.
  - تشغيل queries مباشرة.
  - transactions.

#### Lecture 16: Migrations
- المصادر:
  - `laravel-migration-deep-guide.md`
  - `docs-12.x/migrations.md`
- الهدف:
  - إنشاء الجداول.
  - تعديلها.
  - rollback / refresh / fresh.

#### Lecture 17: Seeding + Factories
- المصادر:
  - `docs-12.x/seeding.md`
  - `docs-12.x/eloquent-factories.md`
- الهدف:
  - توليد بيانات تجريبية.
  - ربط factories بالعلاقات.

#### Lecture 18: Query Builder
- المصادر:
  - `docs-12.x/queries.md`
  - `docs-12.x/pagination.md`
- الهدف:
  - select / where / join / group / order.
  - pagination.

---

### المرحلة الخامسة: Eloquent ORM

#### Lecture 19: Eloquent Fundamentals
- المصادر:
  - `laravel-models-guide.md`
  - `docs-12.x/eloquent.md`
- الهدف:
  - model conventions.
  - CRUD.
  - scopes.
  - events.

#### Lecture 20: Eloquent Relationships
- المصادر:
  - `laravel-relationships-guide.md`
  - `docs-12.x/eloquent-relationships.md`
- الهدف:
  - one to one.
  - one to many.
  - many to many.
  - polymorphic.

#### Lecture 21: Eager Loading + Collections
- المصادر:
  - `docs-12.x/eloquent-relationships.md`
  - `docs-12.x/collections.md`
  - `docs-12.x/eloquent-collections.md`
- الهدف:
  - حل N+1.
  - التعامل مع collection methods عمليًا.

#### Lecture 22: Mutators + Casting + Serialization
- المصادر:
  - `docs-12.x/eloquent-mutators.md`
  - `docs-12.x/eloquent-serialization.md`
- الهدف:
  - accessors / mutators.
  - casts.
  - إخفاء وإظهار الحقول في JSON.

#### Lecture 23: API Resources
- المصادر:
  - `docs-12.x/eloquent-resources.md`
- الهدف:
  - تحويل الموديلات إلى JSON Responses منظمة.

---

### المرحلة السادسة: Authentication وSecurity

#### Lecture 24: Authentication Basics
- المصادر:
  - `docs-12.x/authentication.md`
  - `docs-12.x/starter-kits.md`
  - `docs-12.x/fortify.md`
- الهدف:
  - login / logout.
  - guards.
  - starter kits vs Fortify.

#### Lecture 25: Authorization
- المصادر:
  - `docs-12.x/authorization.md`
- الهدف:
  - gates.
  - policies.
  - حماية العمليات.

#### Lecture 26: Password Reset + Email Verification
- المصادر:
  - `docs-12.x/passwords.md`
  - `docs-12.x/verification.md`
- الهدف:
  - reset passwords.
  - verify email.

#### Lecture 27: Security Essentials
- المصادر:
  - `docs-12.x/hashing.md`
  - `docs-12.x/encryption.md`
  - `docs-12.x/csrf.md`
  - `docs-12.x/rate-limiting.md`
- الهدف:
  - hashing.
  - encryption.
  - CSRF.
  - rate limiting.

---

### المرحلة السابعة: الاختبار والجودة

#### Lecture 28: Testing Basics
- المصادر:
  - `docs-12.x/testing.md`
  - `docs-12.x/http-tests.md`
- الهدف:
  - unit vs feature.
  - testing requests.
  - assertions.

#### Lecture 29: Database Testing + Mocking
- المصادر:
  - `docs-12.x/database-testing.md`
  - `docs-12.x/mocking.md`
  - `docs-12.x/console-tests.md`
- الهدف:
  - factories in tests.
  - seeders in tests.
  - mocking services and facades.

#### Lecture 30: Pint + Error Handling + Logging
- المصادر:
  - `docs-12.x/pint.md`
  - `docs-12.x/errors.md`
  - `docs-12.x/logging.md`
- الهدف:
  - تنسيق الكود.
  - exception handling.
  - logs useful in debugging.

---

## 4) مسار ما بعد الأساسيات

ده المسار الذي يأتي بعد إنهاء 30 محاضرة الأساس.

### المرحلة الثامنة: Services وArchitecture

#### Lecture 31: Service Container + Dependency Injection
- المصادر:
  - `docs-12.x/container.md`
  - `docs-12.x/contracts.md`
  - `docs-12.x/facades.md`
- الهدف:
  - IoC container.
  - bindings.
  - facades vs contracts.

#### Lecture 32: Service Providers
- المصادر:
  - `docs-12.x/providers.md`
- الهدف:
  - register / boot.
  - متى نكتب provider جديد.

#### Lecture 33: Helpers + Strings + Collections Utility
- المصادر:
  - `docs-12.x/helpers.md`
  - `docs-12.x/strings.md`
  - `docs-12.x/collections.md`
- الهدف:
  - الأدوات اليومية التي تسرّع كتابة الكود.

---

### المرحلة التاسعة: الملفات والاتصال الخارجي

#### Lecture 34: Filesystem + Uploads
- المصادر:
  - `docs-12.x/filesystem.md`
- الهدف:
  - file storage.
  - public disk.
  - testing file uploads.

#### Lecture 35: HTTP Client + APIs
- المصادر:
  - `docs-12.x/http-client.md`
- الهدف:
  - consume external APIs.
  - retries.
  - testing external calls.

#### Lecture 36: Mail + Notifications
- المصادر:
  - `docs-12.x/mail.md`
  - `docs-12.x/notifications.md`
- الهدف:
  - mailables.
  - notification channels.

---

### المرحلة العاشرة: Asynchronous Laravel

#### Lecture 37: Events + Listeners
- المصادر:
  - `docs-12.x/events.md`
- الهدف:
  - event-driven design basics.

#### Lecture 38: Queues + Jobs + Scheduling
- المصادر:
  - `docs-12.x/queues.md`
  - `docs-12.x/scheduling.md`
- الهدف:
  - jobs.
  - failed jobs.
  - scheduled tasks.

#### Lecture 39: Cache + Session + Redis
- المصادر:
  - `docs-12.x/cache.md`
  - `docs-12.x/session.md`
  - `docs-12.x/redis.md`
- الهدف:
  - performance basics.
  - state management.

#### Lecture 40: Broadcasting + Realtime
- المصادر:
  - `docs-12.x/broadcasting.md`
  - `docs-12.x/reverb.md`
- الهدف:
  - realtime events.
  - channels.
  - Laravel Reverb.

---

## 5) المسار المتقدم والمتخصص

الجزء ده لا يشرح لكل الطلاب من البداية، لكنه مهم كمسارات اختيارية حسب نوع المشروع.

### Track A: API Auth + OAuth
- `docs-12.x/sanctum.md`
- `docs-12.x/passport.md`
- مناسب بعد authentication الأساسية.

### Track B: Search
- `docs-12.x/search.md`
- `docs-12.x/scout.md`
- مناسب لمشاريع المنتجات أو المحتوى الكبير.

### Track C: Monitoring & Debugging
- `docs-12.x/telescope.md`
- `docs-12.x/pulse.md`
- `docs-12.x/horizon.md`
- مناسب بعد queues وdeployment.

### Track D: Performance
- `docs-12.x/octane.md`
- `docs-12.x/concurrency.md`
- `docs-12.x/processes.md`
- مناسب للمشاريع ذات الحمل العالي.

### Track E: Deployment & Production
- `docs-12.x/deployment.md`
- `docs-12.x/envoy.md`
- مناسب بعد إنهاء مشروع فعلي.

### Track F: Local Dev Environments
- `docs-12.x/sail.md`
- `docs-12.x/valet.md`
- `docs-12.x/homestead.md`
- يشرح حسب نظام تشغيل الطلاب وبيئة العمل.

### Track G: Package / Extension Development
- `docs-12.x/packages.md`
- `docs-12.x/mcp.md`
- `docs-12.x/boost.md`
- مناسب للمستوى المتقدم فقط.

### Track H: AI in Laravel
- `docs-12.x/ai.md`
- `docs-12.x/ai-sdk.md`
- `docs-12.x/prompts.md`
- مناسب بعد فهم services وHTTP integrations.

### Track I: UX / Advanced Form Flows
- `docs-12.x/precognition.md`
- `docs-12.x/folio.md`
- `docs-12.x/localization.md`
- مفيد للمشاريع الحديثة وتجربة المستخدم الأفضل.

### Track J: Commerce / SaaS
- `docs-12.x/billing.md`
- `docs-12.x/cashier-paddle.md`
- `docs-12.x/pennant.md`
- `docs-12.x/socialite.md`

### Track K: Browser Testing
- `docs-12.x/dusk.md`

### Track L: Extra Reference Topics
- `docs-12.x/documentation.md`
- `docs-12.x/license.md`
- `docs-12.x/contributions.md`
- `docs-12.x/upgrade.md`
- `docs-12.x/mix.md`
- `docs-12.x/mongodb.md`

---

## 6) الترتيب المقترح النهائي للكورس

لو هتطلع الكورس على شكل مسار تعليمي منظم، الترتيب الأفضل يكون:

1. Lecture 1 إلى Lecture 4: تأسيس وتشغيل وفهم البيئة.
2. Lecture 5 إلى Lecture 10: MVC + HTTP + Validation.
3. Lecture 11 إلى Lecture 14: Views + Blade + أول مشروع عملي.
4. Lecture 15 إلى Lecture 23: Database + Query Builder + Eloquent بالكامل.
5. Lecture 24 إلى Lecture 27: Authentication + Authorization + Security.
6. Lecture 28 إلى Lecture 30: Testing + Debugging + Code Quality.
7. Lecture 31 إلى Lecture 40: Architecture + Integrations + Queues + Realtime.
8. بعد ذلك تتفتح الـ Tracks الاختيارية حسب نوع المشروع.

---

## 7) خطة مشاريع مرافقة للكورس

عشان الشرح يثبت، الأفضل يكون مع كل مرحلة مشروع صغير:

- بعد Lecture 10:
  - مشروع CRUD بسيط بدون Auth.
- بعد Lecture 23:
  - Blog أو Student Management System بعلاقات كاملة.
- بعد Lecture 27:
  - Dashboard فيها صلاحيات ومستخدمين وأدوار.
- بعد Lecture 30:
  - كتابة tests للمشروع السابق.
- بعد Lecture 40:
  - مشروع متكامل فيه queue + notifications + realtime updates.

---

## 8) ملاحظات تنفيذية

- لا تشرح `Passport`, `Octane`, `Horizon`, `Scout`, `Cashier`, `MCP`, `AI SDK` في البداية.
- لا تدخل في React / Vue قبل ما الطالب يفهم Blade وRouting وControllers كويس.
- `Query Builder` يسبق `Eloquent advanced`.
- `Validation` لازم يسبق مشروع CRUD العملي.
- `Policies` و`Gates` تتشرح بعد ما الطالب يفهم authentication فعليًا.
- `Queues` لا تتشرح قبل events وjobs basics.

---

## 9) النسخة المختصرة جدًا من الرودماب

لو محتاج تقسيم سريع فقط:

- Level 1: Installation, Env, MVC, Routing, Controllers, Requests, Validation.
- Level 2: Views, Blade, Vite, CRUD Project.
- Level 3: Database, Migrations, Seeders, Query Builder, Eloquent, Relationships.
- Level 4: Authentication, Authorization, Security.
- Level 5: Testing, Logging, Error Handling, Pint.
- Level 6: Container, Providers, Filesystem, HTTP Client, Mail, Notifications.
- Level 7: Events, Queues, Cache, Redis, Scheduling, Broadcasting, Reverb.
- Level 8: Tracks متقدمة واختيارية حسب التخصص.

---

## 10) الملف المعتمد كبداية شرح

لو هتبدأ الشرح فورًا، هذا هو التسلسل العملي الأنسب:

1. `laravel-setup-guide.md`
2. `laravel-env-guide.md`
3. `شرح_MVC.md`
4. `laravel-routing-guide.md`
5. `laravel-requests-validation-responses-guide.md`
6. `laravel-views-blade-guide.md`
7. `laravel-controllers-guide.md`
8. `laravel-migration-deep-guide.md`
9. `laravel-models-guide.md`
10. `laravel-relationships-guide.md`
11. `(Advanced) laravel-crud-blog-guide.md`
12. ثم الانتقال التدريجي إلى ملفات `docs-12.x` حسب الرودماب أعلاه

---

## الخلاصة

المسار المقترح ليس مجرد فهرسة لملفات التوثيق، بل ترتيب تدريسي:

- يبدأ بما هو موجود عندك بالعربي.
- يربطه مباشرة بالتوثيق الرسمي Laravel 12.
- يفصل بين ما يجب تدريسه لكل طالب وبين ما يصلح كمسارات تخصصية متقدمة.

لو التزمت بهذا الترتيب، فالكورس سيبقى منطقيًا، تدريجيًا، وقابلًا للتحويل إلى محاضرات فعلية بدون إعادة هيكلة لاحقًا.
