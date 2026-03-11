# ترتيب دروس Laravel الحالية

## الهدف

الملف ده هو الفهرس العملي السريع للدروس الموجودة فعلًا داخل المشروع، بترتيب تدريسي واضح من الصفر إلى ما بعد الأساسيات.

---

## المرحلة الأولى: البداية والتأسيس

1. `laravel-setup-guide.md`
2. `laravel-env-guide.md`
3. `شرح_MVC.md`

الهدف من المرحلة:
- إنشاء المشروع وتشغيله.
- فهم ملف `.env`.
- فهم معنى MVC قبل الدخول في التفاصيل.

---

## المرحلة الثانية: حركة الطلب داخل Laravel

4. `laravel-routing-guide.md`
5. `laravel-requests-validation-responses-guide.md`
6. `laravel-views-blade-guide.md`
7. `laravel-controllers-guide.md`

الهدف من المرحلة:
- فهم الرابط يروح فين.
- فهم البيانات تدخل إزاي وتتحقق إزاي ويرجع الرد إزاي.
- فهم شكل الصفحات وBlade.
- فهم دور الـ Controller في الربط بين كل الأجزاء.

---

## المرحلة الثالثة: البيانات وقاعدة البيانات

8. `laravel-migration-deep-guide.md`
9. `laravel-models-guide.md`
10. `laravel-relationships-guide.md`

الهدف من المرحلة:
- تصميم الجداول.
- التعامل مع Eloquent Models.
- فهم العلاقات بين الجداول.

---

## المرحلة الرابعة: التطبيق العملي

11. `(Advanced) laravel-crud-blog-guide.md`

الهدف من المرحلة:
- ربط ما سبق كله في مشروع CRUD عملي كامل.

---

## المرحلة الخامسة: التوسع التدريجي

12. `laravel-mvc-detailed-guide.md`
13. `docs-12.x/installation.md`
14. `docs-12.x/routing.md`
15. `docs-12.x/requests.md`
16. `docs-12.x/validation.md`
17. `docs-12.x/views.md`
18. `docs-12.x/blade.md`
19. `docs-12.x/migrations.md`
20. `docs-12.x/eloquent.md`
21. `docs-12.x/eloquent-relationships.md`

الهدف من المرحلة:
- الانتقال من الشرح العربي التأسيسي إلى التوثيق الرسمي Laravel 12 بشكل تدريجي ومنظم.

---

## الترتيب المختصر جدًا

لو عايز تسلسل سريع بدون تفاصيل:

1. Setup
2. Env
3. MVC
4. Routing
5. Requests + Validation + Responses
6. Views + Blade
7. Controllers
8. Migrations
9. Models
10. Relationships
11. CRUD Project

---

## الدرس الذي يلي الحالي

بعد `laravel-views-blade-guide.md` الدرس المنطقي التالي هو:

`laravel-controllers-guide.md`

لكن لو هدفك تمشي حسب بناء التطبيق من الواجهة للبيانات مباشرة، تقدر تبدأ بعده في:

`laravel-migration-deep-guide.md`

الاختيار الأنسب هنا يعتمد على أسلوبك في التدريس:

- لو تريد استكمال طبقة HTTP/MVC أولًا: اشرح `Controllers`
- لو تريد الدخول فورًا في بناء التطبيق عمليًا: اشرح `Migrations`
