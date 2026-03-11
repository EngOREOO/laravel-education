<div dir="rtl">

# شرح Migrations في Laravel

### محاضرة تفصيلية جدًا من الصفر مع أمثلة كثيرة و Best Practices و Clean Schema Design

---

## قبل ما نبدأ

الدرس ده من أهم دروس Laravel كلها.

ليه؟

لأن أي تطبيق حقيقي تقريبًا فيه:

- users
- products
- posts
- orders
- comments
- categories

وكل دول محتاجين جداول في قاعدة البيانات.

السؤال هنا:

> إزاي نبني الجداول دي بشكل صحيح ومنظم وآمن وقابل للتطوير؟

الإجابة:

## Migrations

---

## السؤال الأول: يعني إيه Migration أصلًا؟

الـ Migration هي ملف كود يصف تغييرًا في قاعدة البيانات.

بمعنى أبسط:

> بدل ما تفتح phpMyAdmin أو أي أداة وتعمل الجداول يدويًا، أنت تكتب التغييرات في كود، وLaravel تنفذها لك.

يعني كأنك تقول لـ Laravel:

- اعمل جدول users
- حط فيه الأعمدة دي
- اربط الجدول ده بجدول تاني
- ولو احتجنا نرجع، امسح التغيير ده

---

## السؤال الثاني: ليه نستخدم Migrations؟

تخيل السيناريو ده:

أنت عملت جدول `products` يدويًا على جهازك.
زميلك في الفريق نزل المشروع.

كيف سيعرف:

- اسم الجدول
- الأعمدة
- أنواعها
- العلاقات
- القيم الافتراضية

ولو بعد أسبوع أضفت عمود جديد، كيف سيتابعه؟

هنا تظهر أهمية الـ Migrations.

---

## Migrations تحل لك مشاكل مهمة جدًا

### 1. توحيد بنية قاعدة البيانات

كل من يعمل:

```bash
php artisan migrate
```

يحصل على نفس الجداول.

---

### 2. توثيق تاريخ تغييرات قاعدة البيانات

كل ملف migration يمثل خطوة واضحة:

- إنشاء جدول
- إضافة عمود
- حذف عمود
- تعديل بنية

يعني الداتابيز عندك لها "تاريخ" واضح مثل الكود تمامًا.

---

### 3. سهولة التراجع

لو أخطأت:

```bash
php artisan migrate:rollback
```

وترجع خطوة للخلف.

---

### 4. سهولة العمل في الفريق

بدل أن تشرح لكل شخص يدويًا كيف يبني الجداول، يكفي أن تسلمه الكود.

---

### 5. سهولة النشر

في السيرفر:

```bash
php artisan migrate
```

فيأخذ نفس البنية المطلوبة.

---

## التشبيه البسيط جدًا

الـ Migration مثل:

> وصفة مكتوبة لبناء أو تعديل قاعدة البيانات

يعني:

- اسم الطبخة = اسم الـ migration
- خطوات الطبخ = كود الـ migration
- تنفيذ الطبخة = `php artisan migrate`
- التراجع = `php artisan migrate:rollback`

---

## السؤال الثالث: ما الفرق بين Migration و Database نفسها؟

مهم جدًا.

### Database

هي المكان الفعلي الذي تُخزَّن فيه البيانات.

### Migration

هي الكود الذي يصف شكل هذه القاعدة.

يعني:

- database = الشيء الحقيقي الموجود
- migration = التعليمات التي تبني هذا الشيء

---

## السؤال الرابع: هل الـ Migration تخزن البيانات؟

في الأصل:

> لا

الـ migration تهتم بالبنية `schema`:

- tables
- columns
- indexes
- foreign keys

أما البيانات نفسها فعادة نستخدم لها:

- seeders
- factories

يعني:

- migration = شكل الجدول
- seeder = تعبئة بيانات

---

# الجزء الأول: إنشاء أول Migration

## الأمر الأساسي

```bash
php artisan make:migration create_products_table
```

### ماذا يفعل هذا الأمر؟

Laravel تنشئ ملفًا جديدًا داخل:

```text
database/migrations
```

مثلًا:

```text
database/migrations/2026_03_11_120000_create_products_table.php
```

---

## السؤال: لماذا اسم الملف طويل هكذا؟

لأن الاسم يتكون من:

- التاريخ
- الوقت
- اسم الـ migration

والسبب:

> Laravel تنفذ الـ migrations بالترتيب الزمني

---

## السؤال: لماذا نسميها `create_products_table`؟

لأن الاسم يجب أن يكون وصفيًا.

### أسماء جيدة

```text
create_products_table
create_orders_table
add_phone_to_users_table
add_status_to_orders_table
remove_old_price_from_products_table
```

### أسماء سيئة

```text
update_table
new_change
fix_data
test_migration
```

الاسم الجيد يجب أن يشرح:

- ماذا تغير؟
- وفي أي جدول؟

---

# الجزء الثاني: شكل ملف الـ Migration

## مثال كامل

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

## تعال نشرح السطور واحدة واحدة

### `use Illuminate\Database\Migrations\Migration;`

دي الكلاس الأساسية للـ migrations.

---

### `use Illuminate\Database\Schema\Blueprint;`

دي الكلاس التي نستخدمها لتعريف الأعمدة داخل الجدول.

---

### `use Illuminate\Support\Facades\Schema;`

دي الـ facade التي نستخدمها لإنشاء الجداول أو تعديلها.

---

### `return new class extends Migration`

ده anonymous class.

Laravel الحديثة تستخدم هذا الشكل بدل class مسماة غالبًا.

---

## أهم دالتين في أي Migration

### `up()`

دي الدالة التي تنفذ التغيير.

يعني لو عملت:

```bash
php artisan migrate
```

Laravel تنفذ `up()`.

---

### `down()`

دي دالة التراجع.

يعني لو عملت:

```bash
php artisan migrate:rollback
```

Laravel تنفذ `down()`.

---

## القاعدة الذهبية

> `up()` تبني التغيير
> و `down()` ترجع التغيير

مثال:

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('products');
}
```

---

# الجزء الثالث: إنشاء جدول من الصفر

## مثال عملي

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description');
        $table->decimal('price', 10, 2);
        $table->integer('quantity')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
```

---

## السؤال: ماذا يعني كل سطر؟

### `$table->id();`

ينشئ عمود:

```text
id
```

ويكون:

- primary key
- auto increment

يعني Laravel تزيده تلقائيًا:

1, 2, 3, 4...

---

### `$table->string('name');`

عمود نص قصير.

غالبًا يستخدم في:

- name
- title
- email
- phone
- slug

الطول الافتراضي عادة 255.

---

### `$table->text('description');`

عمود نص أطول من `string`.

يستخدم للوصف أو المحتوى الطويل.

---

### `$table->decimal('price', 10, 2);`

ممتاز للأسعار.

معناه:

- 10 = إجمالي عدد الأرقام
- 2 = عدد الأرقام بعد العلامة العشرية

مثال:

```text
99999999.99
```

---

### `$table->integer('quantity')->default(0);`

عدد صحيح، وقيمته الافتراضية 0.

يعني لو لم ترسل قيمة، تكون:

```text
0
```

---

### `$table->boolean('is_active')->default(true);`

قيمة نعم/لا.

مفيدة في:

- active / inactive
- published / unpublished
- featured / not featured

---

### `$table->timestamps();`

ينشئ:

- `created_at`
- `updated_at`

Laravel تتعامل معهما تلقائيًا في Eloquent.

---

# الجزء الرابع: أشهر أنواع الأعمدة

## 1. `string()`

```php
$table->string('name');
$table->string('email');
$table->string('phone', 20);
```

### تستخدم في

- أسماء
- عناوين قصيرة
- إيميلات
- سلاجز
- أرقام تليفون

---

## 2. `text()`

```php
$table->text('description');
```

### تستخدم في

- وصف طويل
- مقالات
- ملاحظات

أنواعها:

```php
$table->text('content');
$table->mediumText('article');
$table->longText('book_content');
```

---

## 3. `integer()`

```php
$table->integer('views');
$table->integer('quantity');
```

### تستخدم في

- عدد المشاهدات
- كمية
- عمر
- ترتيب

أنواع مرتبطة بها:

```php
$table->tinyInteger('age');
$table->smallInteger('rank');
$table->integer('views');
$table->bigInteger('population');
```

---

## 4. `decimal()`

```php
$table->decimal('price', 10, 2);
$table->decimal('salary', 12, 2);
$table->decimal('rating', 3, 2);
```

### تستخدم في

- الأسعار
- الرواتب
- النسب
- التقييمات

### السؤال المتوقع

ليه ما نستخدمش `float` للأسعار؟

لأن `decimal` أدق في القيم المالية.

وده `best practice` مهم جدًا.

---

## 5. `boolean()`

```php
$table->boolean('is_active');
$table->boolean('is_featured')->default(false);
```

---

## 6. `date()`, `time()`, `dateTime()`, `timestamp()`

```php
$table->date('birth_date');
$table->time('start_time');
$table->dateTime('published_at');
$table->timestamp('email_verified_at')->nullable();
```

### تستخدم في

- التواريخ
- المواعيد
- لحظة النشر
- لحظة التحقق

---

## 7. `json()`

```php
$table->json('settings')->nullable();
```

### تستخدم في

- إعدادات مرنة
- metadata
- بيانات لا تحتاج جدولًا منفصلًا

لكن لا تسيء استخدامها.

لو البيانات علاقة مستقلة وواضحة، فغالبًا الأفضل جدول منفصل.

---

## 8. `enum()`

```php
$table->enum('status', ['draft', 'published', 'archived']);
```

### تستخدم في

- status
- role
- payment_status

### تنبيه مهم

`enum` مفيدة، لكن لو الحالات كثيرة أو تتغير كثيرًا، أحيانًا `string` + constants أو table منفصل يكون أريح.

---

# الجزء الخامس: Modifiers

## `nullable()`

```php
$table->string('phone')->nullable();
```

يعني الحقل اختياري.

### السؤال المهم

ما الفرق بين:

- nullable
- default

`nullable` يعني:
القيمة ممكن تكون `null`.

`default` يعني:
لو لم ترسل قيمة، ضع قيمة افتراضية.

---

## `default()`

```php
$table->boolean('is_active')->default(true);
$table->integer('quantity')->default(0);
```

---

## `unique()`

```php
$table->string('email')->unique();
$table->string('slug')->unique();
```

يعني لا تتكرر القيمة.

---

## `unsigned()`

```php
$table->integer('quantity')->unsigned();
```

يعني لا يسمح بالأرقام السالبة.

مفيد في:

- quantity
- stock
- counters

---

## `after()`

```php
$table->string('phone')->after('email');
```

يحدد موضع العمود بعد عمود معين.

لكن:

> لا تعتمد عليه كمنطق مهم في المشروع

هو غالبًا للتنظيم فقط.

---

## `comment()`

```php
$table->integer('views')->comment('عدد المشاهدات');
```

مفيد أحيانًا للتوضيح.

---

# الجزء السادس: العلاقات داخل الـ Migrations

## السؤال: كيف أربط جدولًا بجدول؟

مثال:

- المنتج ينتمي إلى فئة
- التعليق ينتمي إلى مقال
- الطلب ينتمي إلى مستخدم

هنا نستخدم:

## `foreignId()`

مثال:

```php
$table->foreignId('category_id')
    ->constrained()
    ->onDelete('cascade');
```

---

## شرح السطر ده

### `foreignId('category_id')`

ينشئ عمودًا اسمه:

```text
category_id
```

---

### `constrained()`

يعني:

اربطه تلقائيًا بالجدول المناسب.

Laravel تفهم أن:

```text
category_id
```

ترتبط بجدول:

```text
categories
```

---

### `onDelete('cascade')`

يعني:

لو الفئة اتحذفت، المنتجات المرتبطة بها تتحذف أيضًا.

### السؤال المتوقع

هل دائمًا نستخدم `cascade`؟

لا.

على حسب طبيعة البيانات.

أحيانًا نستخدم:

- `cascade`
- `restrict`
- `nullOnDelete`

حسب المطلوب.

---

## أمثلة

### ربط المنتج بالفئة

```php
$table->foreignId('category_id')
    ->constrained()
    ->onDelete('cascade');
```

### ربط المقال بالمستخدم

```php
$table->foreignId('user_id')
    ->constrained()
    ->onDelete('cascade');
```

### تعليق له parent comment

```php
$table->foreignId('parent_id')
    ->nullable()
    ->constrained('comments')
    ->onDelete('cascade');
```

---

# الجزء السابع: تعديل جدول موجود

## إضافة عمود جديد

### أنشئ migration

```bash
php artisan make:migration add_phone_to_users_table
```

### داخلها

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone', 20)->nullable()->after('email');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('phone');
    });
}
```

---

## تعديل عمود موجود

مثال:

```php
Schema::table('products', function (Blueprint $table) {
    $table->string('name', 300)->change();
});
```

### تنبيه

بعض أنواع التعديلات قد تحتاج دعم إضافي بحسب قاعدة البيانات والإعدادات.

لكن الفكرة الأساسية:

> لا تعدل migration القديمة بعد تنفيذها في مشروع حقيقي

بل اعمل migration جديدة.

---

## حذف عمود

```php
Schema::table('users', function (Blueprint $table) {
    $table->dropColumn('phone');
});
```

---

## إعادة تسمية عمود

```php
Schema::table('users', function (Blueprint $table) {
    $table->renameColumn('name', 'full_name');
});
```

---

# الجزء الثامن: أوامر الـ Migrations المهمة

## `migrate`

```bash
php artisan migrate
```

ينفذ كل الـ migrations غير المنفذة.

---

## `migrate:rollback`

```bash
php artisan migrate:rollback
```

يرجع آخر batch.

### السؤال المتوقع

يعني إيه batch؟

يعني مجموعة migrations اتنفذت مع بعض في نفس الدفعة.

---

## `migrate:reset`

```bash
php artisan migrate:reset
```

يرجع كل الـ migrations.

---

## `migrate:refresh`

```bash
php artisan migrate:refresh
```

يعني:

- rollback لكل شيء
- ثم migrate من جديد

---

## `migrate:fresh`

```bash
php artisan migrate:fresh
```

ده يمسح كل الجداول ثم يعيد بناءها.

### تحذير

ده قوي جدًا.

ممتاز في التطوير.
لكن خطر لو استخدمته على بيانات مهمة.

---

## `migrate:status`

```bash
php artisan migrate:status
```

يعرض:

- أي migrations تنفذت
- وأيها لم يتنفذ

---

# الجزء التاسع: مثال حقيقي كامل

## جدول users

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamp('email_verified_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

### لماذا هذا التصميم جيد؟

- فيه primary key
- email unique
- password string
- email verification nullable
- timestamps

---

## جدول categories

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});
```

---

## جدول products

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('quantity')->unsigned()->default(0);
    $table->boolean('is_active')->default(true);
    $table->foreignId('category_id')
        ->constrained()
        ->onDelete('cascade');
    $table->timestamps();
});
```

### هذا المثال يعلمك:

- إنشاء جدول
- string
- text
- decimal
- boolean
- default
- unsigned
- foreign key
- timestamps

---

# الجزء العاشر: Best Practices مهمة جدًا

## 1. اجعل كل migration لها غرض واحد واضح

جيد:

```text
create_products_table
add_phone_to_users_table
add_status_to_orders_table
```

سيء:

```text
update_everything_table
misc_changes
new_fix
```

---

## 2. لا تعدل migration قديمة بعد أن تُنفذ في مشروع حقيقي

لو migration نُفذت واشتغل بها الفريق أو السيرفر:

> لا ترجع تعدلها غالبًا

بل أنشئ migration جديدة تكمل التغيير.

وده من أهم مبادئ العمل المنظم.

---

## 3. استخدم أسماء جداول واضحة

عادة:

- model مفرد: `Product`
- table جمع: `products`

---

## 4. استخدم الأعمدة المناسبة

- السعر = `decimal`
- الحالة = `boolean` أو `enum`
- النص الطويل = `text`
- الاسم = `string`

---

## 5. فكر في الـ nullability جيدًا

اسأل نفسك:

- هل الحقل لازم يكون مطلوب دائمًا؟
- هل الحقل اختياري؟
- هل يحتاج default بدل nullable؟

---

## 6. ضع قيودًا منطقية

مثل:

- `unique()` للإيميل والـ slug
- `unsigned()` للكميات
- `foreignId()` للعلاقات

---

## 7. لا تفرط في `enum`

ممتازة أحيانًا.
لكن ليست دائمًا الحل الأفضل.

---

## 8. فكر في الحذف جيدًا

هل لو حذفنا المستخدم:

- نحذف طلباته؟
- نمنع الحذف؟
- نجعل القيمة null؟

اختيار `onDelete` يجب أن يكون واعيًا.

---

# الجزء الحادي عشر: أخطاء شائعة جدًا

## 1. `Table already exists`

السبب:

- migration تُنشئ جدولًا موجودًا بالفعل

### الحل

- راجع حالة migrations
- استخدم `migrate:status`
- أو rollback/fresh في بيئة التطوير لو مناسب

---

## 2. `Nothing to migrate`

السبب:

- كل الـ migrations الحالية تم تنفيذها بالفعل

وده ليس خطأ فعليًا غالبًا.

---

## 3. `SQLSTATE ... syntax error`

السبب:

- خطأ في تعريف migration
- نوع عمود غير صحيح
- قوس ناقص
- سلسلة method chaining غير صحيحة

---

## 4. `Foreign key constraint fails`

السبب غالبًا:

- الجدول المرتبط غير موجود وقت التنفيذ
- نوع العمود غير متطابق
- تحاول إدخال قيمة مرتبطة غير موجودة

---

## 5. المشكلة الأشهر: تعديل migration قديمة بعد التنفيذ

في التطوير الفردي الصغير قد تمر.
لكن في الشغل المنظم ده يسبب فوضى.

---

# الجزء الثاني عشر: أسئلة المبتدئ التي يجب أن تعرفها

## هل أعمل migration قبل model ولا model قبل migration؟

الاثنان ممكن.

لكن عمليًا كثير من الناس يعملون:

```bash
php artisan make:model Product -m
```

فتنشأ model و migration معًا.

---

## هل كل جدول لازم يكون له `id()`؟

غالبًا نعم في أغلب المشاريع.

إلا لو عندك سبب خاص لتصميم مختلف.

---

## هل كل جدول لازم يكون فيه `timestamps()`؟

ليس شرطًا، لكن غالبًا نعم.

لأنها مفيدة جدًا:

- تعرف متى أُنشئ السجل
- ومتى عُدّل

---

## هل أستخدم `string` ولا `text`؟

لو القيمة قصيرة غالبًا:

- `string`

لو طويلة:

- `text`

---

## هل أستخدم `nullable()` ولا `default()`؟

اسأل نفسك:

- هل غياب القيمة له معنى؟ → `nullable`
- هل فيه قيمة منطقية تلقائية؟ → `default`

---

## هل migration مسؤولة عن العلاقات في model؟

لا.

migration تعرف:

- foreign keys
- columns

لكن methods مثل:

- `belongsTo`
- `hasMany`

تكتب في الـ models.

---

# الجزء الثالث عشر: مثال تدريبي للطلاب

## المطلوب

اعمل قاعدة بيانات بسيطة لمشروع مكتبة.

### الجداول

1. `authors`
2. `books`
3. `categories`

### الشروط

- كل كاتب له اسم وبايو اختياري
- كل كتاب له عنوان ووصف وسعر وحالة نشر
- كل كتاب ينتمي لكاتب واحد
- كل كتاب ينتمي لفئة واحدة

### حاول تصمم:

- أسماء الجداول
- الأعمدة
- أنواعها
- العلاقات
- defaults المناسبة

---

# الجزء الرابع عشر: الملخص النهائي

## الـ Migration هي

ملف كود يصف تغييرًا في قاعدة البيانات.

---

## أهم ما تعلمته

- `make:migration`
- `up()` و `down()`
- `Schema::create`
- `Schema::table`
- أنواع الأعمدة
- modifiers
- foreign keys
- أوامر migrate / rollback / refresh / fresh
- best practices في تصميم schema

---

## الجملة الذهبية

لو عايز تلخص الدرس كله في سطر واحد:

> الـ Migrations في Laravel هي الطريقة المنظمة والاحترافية لبناء وتعديل قاعدة البيانات بالكود، بحيث تكون بنية الداتابيز واضحة، قابلة للتتبع، سهلة التنفيذ، وسهلة التراجع.

---

## الخطوة التالية

بعد Migrations، الدرس الطبيعي التالي هو:

## `09-laravel-models-guide.md`

لأنك بعد ما بنيت شكل الجداول، لازم تفهم الكائنات التي ستتعامل مع هذه الجداول داخل Laravel.

</div>
