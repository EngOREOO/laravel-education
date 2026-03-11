<div dir="rtl">

# 🏗️ شرح Migration في لارافيل - من الصفر للاحتراف

### فهم كامل لـ Migration بأسلوب بسيط وعملي

---

## 🤔 يعني إيه Migration؟

تخيل معايا السيناريو ده:

**بدون Migration:**
- إنت عامل جدول في الداتابيز على جهازك
- زميلك محمد عايز يشتغل على نفس المشروع
- هيروح يعمل نفس الجدول يدوياً في phpMyAdmin
- بعدين إنت عدلت الجدول وضفت عمود جديد
- محمد مش هيعرف! والداتابيز بتاعته هتبقى مختلفة عنك 😱

**مع Migration:**
- إنت بتكتب "وصفة" في كود
- محمد ينزل الكود ويعمل أمر واحد بس
- الداتابيز بتاعته تبقى نسخة طبق الأصل من بتاعتك! ✨

---

## 📚 التشبيه الكامل

**Migration زي "كتاب وصفات الطبخ" للداتابيز:**

```
كتاب الطبخ                    Migration
────────────────────────────────────────────
📖 الوصفة                  →  ملف الـ Migration
👨‍🍳 الطباخ                 →  لارافيل
🍲 الأكلة                   →  الجدول في الداتابيز
📝 المقادير                →  الأعمدة (Columns)
⏮️ إلغاء الوصفة            →  دالة down()
```

لما تدي الكتاب لأي حد، يقدر يعمل نفس الأكلة بالظبط!

---

## 🎯 ليه نستخدم Migration؟

### ✅ المميزات:

**1. التحكم في الإصدارات (Version Control)**
```
زي Git للكود، Migration هو Git للداتابيز!
```

**2. سهولة المشاركة مع الفريق**
```
كل الفريق يقدر يعمل نفس الداتابيز بأمر واحد
```

**3. التراجع عن التغييرات**
```
غلطت؟ ارجع بسهولة!
```

**4. توثيق تلقائي**
```
كل تغيير متسجل في ملف واضح
```

**5. بيئات متعددة**
```
نفس البنية في Development, Testing, Production
```

---

## 🛠️ إنشاء أول Migration

### الأمر الأساسي:

```bash
php artisan make:migration create_users_table
```

**تشريح الأمر:**
- `php artisan` - بنشغل لارافيل من الترمنال
- `make:migration` - اعمل ملف Migration جديد
- `create_users_table` - اسم الـ Migration (لازم يكون وصفي)

---

### 📝 تسمية الـ Migrations

**قواعد مهمة:**

**✅ لإنشاء جدول جديد:**
```bash
create_products_table
create_orders_table
create_categories_table
```

**✅ لتعديل جدول موجود:**
```bash
add_phone_to_users_table
add_status_to_orders_table
remove_old_column_from_products_table
```

**✅ أمثلة عملية:**
```bash
# إنشاء جدول منتجات
php artisan make:migration create_products_table

# إضافة عمود الصورة لجدول المنتجات
php artisan make:migration add_image_to_products_table

# حذف عمود السعر القديم
php artisan make:migration remove_old_price_from_products_table
```

---

## 📂 فين الملف؟

بعد ما تعمل Migration، هتلاقيه في:

```
database/migrations/2024_12_02_120000_create_products_table.php
```

**شرح اسم الملف:**
- `2024_12_02` - التاريخ
- `120000` - الوقت (12:00:00)
- `create_products_table` - الاسم اللي انت كتبته

**ليه التاريخ والوقت؟**
عشان لارافيل ينفذ الـ Migrations بالترتيب الصحيح!

---

## 🔍 داخل ملف الـ Migration

### البنية الأساسية:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * دالة تنفيذ التغييرات
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // هنا بنحدد الأعمدة
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * دالة التراجع عن التغييرات
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

### 🎭 الدالتين الأساسيتين:

**1. دالة `up()`** - تنفيذ التغيير ⬆️
```php
// لما تعمل: php artisan migrate
// بتشتغل دالة up() وتنفذ التغييرات
```

**2. دالة `down()`** - التراجع عن التغيير ⬇️
```php
// لما تعمل: php artisan migrate:rollback
// بتشتغل دالة down() وترجع التغييرات
```

**مثال توضيحي:**
```php
// up: اعمل الجدول
Schema::create('products', ...);

// down: امسح الجدول
Schema::dropIfExists('products');
```

---

## 📊 أنواع الأعمدة (Column Types)

### 1️⃣ الأعمدة الأساسية

#### `id()` - المفتاح الأساسي
```php
$table->id();
```
**النتيجة:** عمود `id` من نوع BIGINT بيزيد تلقائياً (1, 2, 3...)

**استخدامه:** 
- رقم فريد لكل صف
- مفتاح أساسي للجدول

---

#### `string()` - نص قصير
```php
$table->string('name');           // طول افتراضي 255
$table->string('phone', 15);      // طول محدد 15 حرف
```
**النتيجة:** عمود VARCHAR

**استخدامه:**
- الأسماء
- البريد الإلكتروني
- أرقام الهواتف
- العناوين القصيرة

**أمثلة:**
```php
$table->string('first_name');
$table->string('email');
$table->string('phone', 20);
$table->string('username', 50);
```

---

#### `text()` - نص طويل
```php
$table->text('description');
```
**النتيجة:** عمود TEXT (حتى 65,535 حرف)

**استخدامه:**
- المقالات
- الوصف التفصيلي
- المحتوى الطويل

**أنواع Text:**
```php
$table->text('content');            // نص عادي
$table->mediumText('article');      // نص متوسط (16 مليون حرف)
$table->longText('book');           // نص كبير جداً (4 مليار حرف)
```

---

#### `integer()` - أرقام صحيحة
```php
$table->integer('views');
$table->integer('age');
```
**النتيجة:** عمود INT

**استخدامه:**
- الأعمار
- عدد المشاهدات
- الكميات

**أنواع Integer:**
```php
$table->tinyInteger('age');         // -128 إلى 127
$table->smallInteger('quantity');   // -32,768 إلى 32,767
$table->integer('views');           // -2 مليار إلى 2 مليار
$table->bigInteger('population');   // أرقام ضخمة جداً
```

---

#### `decimal()` - أرقام عشرية
```php
$table->decimal('price', 8, 2);
```
**الشرح:**
- `8` - إجمالي الأرقام
- `2` - الأرقام بعد العلامة العشرية

**أمثلة:**
```php
$table->decimal('price', 8, 2);      // 999999.99
$table->decimal('rating', 3, 2);     // 5.00
$table->decimal('salary', 10, 2);    // 99999999.99
```

**استخدامه:**
- الأسعار
- الرواتب
- النسب المئوية
- التقييمات

---

#### `boolean()` - صح أو غلط
```php
$table->boolean('is_active');
$table->boolean('is_verified');
```
**النتيجة:** القيم: `0` (false) أو `1` (true)

**أمثلة:**
```php
$table->boolean('is_active')->default(true);
$table->boolean('is_admin')->default(false);
$table->boolean('email_verified');
$table->boolean('is_published');
```

---

#### `date()` و `time()` و `datetime()`
```php
$table->date('birth_date');          // 2024-12-02
$table->time('start_time');          // 14:30:00
$table->datetime('published_at');    // 2024-12-02 14:30:00
```

**استخدامه:**
```php
$table->date('hire_date');           // تاريخ التوظيف
$table->time('working_hours');       // ساعات العمل
$table->datetime('order_date');      // تاريخ ووقت الطلب
```

---

#### `timestamps()` - أعمدة التوقيت
```php
$table->timestamps();
```
**النتيجة:** بيعمل عمودين:
- `created_at` - وقت الإنشاء
- `updated_at` - وقت آخر تحديث

**لارافيل بيحدثهم تلقائياً!** ✨

---

### 2️⃣ أعمدة خاصة

#### `email()` - البريد الإلكتروني
```php
$table->string('email')->unique();
```

#### `json()` - بيانات JSON
```php
$table->json('settings');
$table->json('metadata');
```

#### `enum()` - قائمة محددة
```php
$table->enum('status', ['pending', 'approved', 'rejected']);
$table->enum('role', ['admin', 'user', 'guest']);
```

---

### 3️⃣ العلاقات بين الجداول

#### `foreignId()` - مفتاح أجنبي حديث
```php
$table->foreignId('user_id')
      ->constrained()
      ->onDelete('cascade');
```

**شرح:**
- `foreignId('user_id')` - عمود للربط مع جدول users
- `constrained()` - ربط تلقائي مع جدول users
- `onDelete('cascade')` - لما اليوزر يتمسح، كل بياناته تتمسح

**أمثلة:**
```php
// ربط المنتجات بالفئات
$table->foreignId('category_id')
      ->constrained()
      ->onDelete('cascade');

// ربط الطلبات بالمستخدمين
$table->foreignId('user_id')
      ->constrained()
      ->onDelete('cascade');

// ربط التعليقات بالمقالات
$table->foreignId('post_id')
      ->constrained()
      ->onDelete('cascade');
```

---

## 🎨 Modifiers - تعديل خصائص الأعمدة

### `nullable()` - يسمح بالقيمة الفارغة
```php
$table->string('phone')->nullable();
```
**بدون nullable:** الحقل إجباري  
**مع nullable:** الحقل اختياري

---

### `default()` - قيمة افتراضية
```php
$table->integer('views')->default(0);
$table->boolean('is_active')->default(true);
$table->string('status')->default('pending');
```

---

### `unique()` - قيمة فريدة
```php
$table->string('email')->unique();
$table->string('username')->unique();
$table->string('phone')->unique();
```
**معناها:** مينفعش يتكرر نفس القيمة مرتين

---

### `unsigned()` - أرقام موجبة فقط
```php
$table->integer('age')->unsigned();
$table->integer('quantity')->unsigned();
```

---

### `after()` - مكان العمود
```php
$table->string('middle_name')->after('first_name');
```
**معناها:** ضع العمود بعد عمود معين

---

### `comment()` - تعليق توضيحي
```php
$table->integer('views')->comment('عدد المشاهدات');
```

---

## 🎯 أمثلة عملية كاملة

### مثال 1: جدول المستخدمين

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('first_name', 50);
    $table->string('last_name', 50);
    $table->string('email')->unique();
    $table->string('phone', 15)->nullable();
    $table->string('password');
    $table->date('birth_date')->nullable();
    $table->enum('gender', ['male', 'female'])->nullable();
    $table->boolean('is_active')->default(true);
    $table->boolean('email_verified')->default(false);
    $table->timestamp('email_verified_at')->nullable();
    $table->timestamps();
});
```

**شرح:**
- رقم تعريفي فريد (`id`)
- الاسم الأول والأخير (إجباري)
- الإيميل فريد (مينفعش يتكرر)
- التليفون اختياري
- الباسورد مشفر
- تاريخ الميلاد والنوع اختياري
- حالة النشاط (افتراضياً فعّال)
- التحقق من الإيميل
- أوقات الإنشاء والتحديث

---

### مثال 2: جدول المنتجات

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description');
    $table->decimal('price', 10, 2);
    $table->decimal('discount_price', 10, 2)->nullable();
    $table->integer('quantity')->unsigned()->default(0);
    $table->string('sku')->unique();
    $table->string('image')->nullable();
    $table->boolean('is_featured')->default(false);
    $table->enum('status', ['draft', 'published', 'archived'])
          ->default('draft');
    $table->foreignId('category_id')
          ->constrained()
          ->onDelete('cascade');
    $table->timestamps();
});
```

**شرح:**
- اسم المنتج والـ slug الفريد
- وصف تفصيلي
- السعر والسعر بعد الخصم
- الكمية المتوفرة
- كود المنتج (SKU) فريد
- صورة المنتج (اختياري)
- هل مميز؟
- الحالة (مسودة، منشور، مؤرشف)
- ربط بجدول الفئات

---

### مثال 3: جدول الطلبات

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number')->unique();
    $table->foreignId('user_id')
          ->constrained()
          ->onDelete('cascade');
    $table->decimal('subtotal', 10, 2);
    $table->decimal('tax', 10, 2);
    $table->decimal('shipping', 10, 2);
    $table->decimal('total', 10, 2);
    $table->enum('status', [
        'pending',
        'processing', 
        'shipped',
        'delivered',
        'cancelled'
    ])->default('pending');
    $table->enum('payment_status', [
        'unpaid',
        'paid',
        'refunded'
    ])->default('unpaid');
    $table->string('payment_method')->nullable();
    $table->text('shipping_address');
    $table->text('notes')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();
});
```

---

### مثال 4: جدول التعليقات

```php
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
          ->constrained()
          ->onDelete('cascade');
    $table->foreignId('post_id')
          ->constrained()
          ->onDelete('cascade');
    $table->text('content');
    $table->boolean('is_approved')->default(false);
    $table->integer('likes')->default(0);
    $table->foreignId('parent_id')
          ->nullable()
          ->constrained('comments')
          ->onDelete('cascade');
    $table->timestamps();
});
```

**شرح:**
- ربط بالمستخدم والمقال
- محتوى التعليق
- حالة الموافقة
- عدد الإعجابات
- الـ `parent_id` للردود على التعليقات (تعليقات متداخلة)

---

## ⚙️ تعديل الجداول الموجودة

### إضافة أعمدة جديدة

```bash
php artisan make:migration add_phone_to_users_table
```

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone', 15)->nullable()->after('email');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('phone');
    });
}
```

---

### تعديل عمود موجود

```bash
php artisan make:migration modify_price_in_products_table
```

```php
use Illuminate\Database\Schema\Blueprint;

public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->decimal('price', 12, 2)->change();
    });
}
```

---

### حذف أعمدة

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('old_price');
        // أو حذف أكثر من عمود
        $table->dropColumn(['old_price', 'old_sku']);
    });
}
```

---

### إعادة تسمية عمود

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->renameColumn('name', 'product_name');
    });
}
```

---

## 🎮 أوامر الـ Migration المهمة

### `migrate` - تنفيذ كل الـ Migrations

```bash
php artisan migrate
```
**الاستخدام:** أول مرة أو لما يكون فيه migrations جديدة

---

### `migrate:rollback` - التراجع عن آخر Batch

```bash
php artisan migrate:rollback
```
**الاستخدام:** لما تعمل غلطة وعايز ترجع

**مع خطوات محددة:**
```bash
php artisan migrate:rollback --step=2
```

---

### `migrate:reset` - التراجع عن كل شيء

```bash
php artisan migrate:reset
```
**تحذير:** بيمسح كل الجداول!

---

### `migrate:refresh` - Reset + Migrate

```bash
php artisan migrate:refresh
```
**الاستخدام:** لما عايز تبدأ من الصفر

**مع Seeding:**
```bash
php artisan migrate:refresh --seed
```

---

### `migrate:fresh` - مسح كل الجداول + Migrate

```bash
php artisan migrate:fresh
```
**الفرق عن refresh:** بيمسح كل حاجة بما فيها الجداول القديمة

**مع Seeding:**
```bash
php artisan migrate:fresh --seed
```

---

### `migrate:status` - حالة الـ Migrations

```bash
php artisan migrate:status
```
**النتيجة:** قائمة بكل الـ migrations (اللي اتنفذت واللي لسه)

---

## 🎯 أفضل الممارسات (Best Practices)

### ✅ افعل:

**1. استخدم أسماء واضحة**
```bash
✅ create_user_profiles_table
✅ add_avatar_to_users_table
❌ new_migration
❌ fix
```

**2. اعمل Migration منفصل لكل تغيير**
```bash
# أفضل من Migration واحد كبير
php artisan make:migration add_phone_to_users_table
php artisan make:migration add_address_to_users_table
```

**3. دايماً اكتب `down()` صح**
```php
public function down()
{
    // لازم يعكس اللي عملته في up()
    Schema::dropIfExists('products');
}
```

**4. استخدم `nullable()` للأعمدة الاختيارية**
```php
$table->string('middle_name')->nullable();
```

**5. حدد طول الـ string**
```php
✅ $table->string('name', 100);
❌ $table->string('name');  // 255 طويل أوي أحياناً
```

---

### ❌ لا تفعل:

**1. متعدلش على migration قديم اتنفذ**
```bash
# لو عملت migrate، متعدلش في الملف
# اعمل migration جديد للتعديل
```

**2. متحذفش migrations من المشروع**
```bash
# الـ migrations دي سجل تاريخي مهم
```

**3. متستخدمش migrations في Production مباشرة**
```bash
# جرب الأول في Staging
```

---

## 🐛 حل المشاكل الشائعة

### المشكلة 1: "Table already exists"

**السبب:** الجدول موجود فعلاً

**الحل:**
```bash
# امسح الجدول يدوياً من phpMyAdmin
# أو استخدم:
php artisan migrate:fresh
```

---

### المشكلة 2: "SQLSTATE[42000]: Syntax error"

**السبب:** خطأ في صيغة SQL

**الحل:**
- تأكد من كتابة الأعمدة صح
- تأكد من الـ datatype صحيح
```php
✅ $table->string('name');
❌ $table->varchar('name');  // Laravel مش بيستخدم varchar
```

---

### المشكلة 3: "Nothing to migrate"

**السبب:** كل الـ migrations اتنفذت

**التأكد:**
```bash
php artisan migrate:status
```

---

### المشكلة 4: "Foreign key constraint fails"

**السبب:** بتحاول تمسح جدول مرتبط بجدول تاني

**الحل:**
```php
// استخدم onDelete
$table->foreignId('user_id')
      ->constrained()
      ->onDelete('cascade');
```

---

### المشكلة 5: "Class not found"

**السبب:** مكتبة doctrine/dbal مش موجودة (للتعديل على أعمدة)

**الحل:**
```bash
composer require doctrine/dbal
```

---

## 📊 مثال مشروع كامل

### سيناريو: نظام مكتبة إلكترونية

**1. جدول الكتب:**
```bash
php artisan make:migration create_books_table
```

```php
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('isbn')->unique();
    $table->text('description');
    $table->string('author');
    $table->string('publisher');
    $table->date('published_date');
    $table->integer('pages')->unsigned();
    $table->decimal('price', 8, 2);
    $table->string('cover_image')->nullable();
    $table->integer('stock')->unsigned()->default(0);
    $table->boolean('is_available')->default(true);
    $table->timestamps();
});
```

---

**2. جدول الفئات:**
```bash
php artisan make:migration create_categories_table
```

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

---

**3. ربط الكتب بالفئات (Many-to-Many):**
```bash
php artisan make:migration create_book_category_table
```

```php
Schema::create('book_category', function (Blueprint $table) {
    $table->id();
    $table->foreignId('book_id')
          ->constrained()
          ->onDelete('cascade');
    $table->foreignId('category_id')
          ->constrained()
          ->onDelete('cascade');
    $table->timestamps();
});
```

---

**4. جدول الاستعارات:**
```bash
php artisan make:migration create_borrowings_table
```

```php
Schema::create('borrowings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
          ->constrained()
          ->onDelete('cascade');
    $table->foreignId('book_id')
          ->constrained()
          ->onDelete('cascade');
    $table->date('borrowed_at');
    $table->date('due_date');
    $table->date('returned_at')->nullable();
    $table->enum('status', ['active', 'returned', 'overdue'])
          ->default('active');
    $table->decimal('fine', 8, 2)->default(0);
    $table->timestamps();
});
```

---

**5. تنفيذ كل شيء:**
```bash
php artisan migrate
```

---

## 🎓 تمارين للممارسة

### تمرين 1: نظام مطعم
اعمل migrations لـ:
- جدول الوجبات (meals)
- جدول الفئات (categories)
- جدول الطلبات (orders)
- جدول تفاصيل الطلبات (order_items)

### تمرين 2: نظام حجز فنادق
اعمل migrations لـ:
- جدول الفنادق (hotels)
- جدول الغرف (rooms)
- جدول الحجوزات (bookings)
- جدول المراجعات (reviews)

### تمرين 3: منصة تعليمية
اعمل migrations لـ:
- جدول الكورسات (courses)
- جدول الدروس (lessons)
- جدول التسجيلات (enrollments)
- جدول التقدم (progress)

---

## 📚 ملخص سريع

**Migration هو:**
✅ نظام للتحكم في بنية الداتابيز  
✅ يسهل العمل الجماعي  
✅ يوفر نسخ احتياطي للبنية  
✅ يسمح بالتراجع عن التغييرات  

**الأوامر المهمة:**
```bash
php artisan make:migration create_xxx_table
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh
php artisan migrate:status
```

**أنواع الأعمدة الأساسية:**
- `id()` - المفتاح الأساسي
- `string()` - نص قصير
- `text()` - نص طويل
- `integer()` - أرقام صحيحة
- `decimal()` - أرقام عشرية
- `boolean()` - صح/غلط
- `timestamps()` - التواريخ التلقائية

---

**مبروك! 🎉 دلوقتي فاهم الـ Migration كويس جداً!**

صُنع بحب ❤️ لكل مطور عايز يفهم لارافيل صح

</div>
