<div dir="rtl">

# شرح Models في Laravel

### محاضرة تفصيلية جدًا للمبتدئ عن الـ Model و Eloquent من الصفر حتى الشغل النظيف

---

## قبل ما نبدأ

إحنا في الدروس السابقة فهمنا:

- الـ Routing
- الـ Requests و Validation و Responses
- الـ Views و Blade
- الـ Controllers
- الـ Migrations

دلوقتي وصلنا لجزء مهم جدًا:

## الـ Model

وده الجزء اللي بيتعامل مع البيانات نفسها داخل Laravel.

لو الـ migrations بنت شكل الجداول،
فالـ models هي الطريقة اللي هنتعامل بيها مع الجداول دي في الكود.

---

## السؤال الأول: يعني إيه Model أصلًا؟

الـ Model هو كلاس يمثل جدولًا في قاعدة البيانات.

يعني مثلًا:

- جدول `users` ↔ Model اسمها `User`
- جدول `products` ↔ Model اسمها `Product`
- جدول `posts` ↔ Model اسمها `Post`

بمعنى أبسط:

> الـ Model هي "الواجهة البرمجية" التي تتعامل بها مع الجدول.

يعني بدل ما كل مرة تكتب SQL خام مثل:

```sql
SELECT * FROM products WHERE id = 1;
```

في Laravel تكتب:

```php
$product = Product::find(1);
```

---

## السؤال الثاني: ما الفرق بين Model والجدول؟

مهم جدًا.

### الجدول

هو الشيء الحقيقي داخل قاعدة البيانات.

مثال:

```text
products
```

فيه أعمدة مثل:

- id
- name
- price
- quantity

---

### الـ Model

هو الكلاس داخل Laravel الذي يمثّل هذا الجدول.

مثال:

```php
class Product extends Model
{
    //
}
```

إذًا:

- الجدول = البيانات الحقيقية
- الـ model = الطريقة التي نتعامل بها مع هذه البيانات

---

## السؤال الثالث: لماذا نستخدم Models بدل SQL مباشر؟

لأن Laravel توفر لك:

- كود أوضح
- قراءة أسهل
- كتابة أسرع
- ربط طبيعي مع العلاقات
- تكامل ممتاز مع بقية الفريم وورك

مثال:

بدل:

```sql
INSERT INTO products (name, price) VALUES ('Laptop', 15000);
```

تكتب:

```php
Product::create([
    'name' => 'Laptop',
    'price' => 15000,
]);
```

وده أوضح وأسهل على المبتدئ والمحترف معًا.

---

## Eloquent يعني إيه؟

Laravel فيها ORM اسمه:

## Eloquent

وهو النظام الذي يجعل الـ Models تتعامل مع قاعدة البيانات بشكل object-oriented.

بمعنى:

بدل ما تفكر فقط في:

- rows
- columns

هتبدأ تفكر أيضًا في:

- objects
- properties
- relations
- methods

---

## السؤال الرابع: يعني إيه ORM؟

ORM اختصار:

## Object Relational Mapping

وده معناه ببساطة:

ربط بين:

- الـ objects في الكود
- والجداول في قاعدة البيانات

فـ `Product` object تمثل سطرًا من جدول `products`.

---

# الجزء الأول: إنشاء Model

## أبسط أمر

```bash
php artisan make:model Product
```

ده ينشئ ملفًا هنا:

```text
app/Models/Product.php
```

وشكله غالبًا يكون:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
}
```

---

## إنشاء Model مع Migration

```bash
php artisan make:model Product -m
```

ده يعمل:

- model
- migration

وده شائع جدًا لأن model والجدول غالبًا يولدان معًا.

---

## إنشاء Model + Migration + Controller

```bash
php artisan make:model Product -mcr
```

يعني:

- `m` = migration
- `c` = controller
- `r` = resource controller

---

## إنشاء كل شيء مرة واحدة

```bash
php artisan make:model Product -a
```

ده يعمل عادة:

- model
- migration
- controller
- factory
- seeder
- policy
- requests أحيانًا حسب السياق

---

# الجزء الثاني: أين تعيش الـ Model؟ وما علاقتها بالجدول؟

## مكان الـ Model

غالبًا:

```text
app/Models/Product.php
```

---

## اسم الـ Model

يكون غالبًا:

- مفرد
- PascalCase

مثال:

- `User`
- `Product`
- `Order`
- `Comment`

---

## اسم الجدول

Laravel تتوقع عادة:

- model مفرد
- table جمع

يعني:

- `Product` ↔ `products`
- `User` ↔ `users`
- `Category` ↔ `categories`

---

## السؤال: لو اسم الجدول مختلف؟

تقدر تحدده يدويًا:

```php
class Product extends Model
{
    protected $table = 'items';
}
```

وده معناه:

> الـ Model اسمها Product، لكن تتعامل مع جدول اسمه items

---

# الجزء الثالث: أبسط شكل للـ Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
}
```

هذا يكفي كبداية.

لكن بعد قليل سنحتاج خصائص مهمة جدًا.

---

# الجزء الرابع: أهم خصائص الـ Model

## 1. `$table`

لو اسم الجدول مختلف عن المتوقع:

```php
protected $table = 'items';
```

---

## 2. `$primaryKey`

Laravel تتوقع أن المفتاح الأساسي هو:

```text
id
```

لو مختلف:

```php
protected $primaryKey = 'product_id';
```

---

## 3. `incrementing` و `keyType`

لو المفتاح ليس رقمًا متزايدًا، مثل UUID:

```php
protected $primaryKey = 'uuid';
public $incrementing = false;
protected $keyType = 'string';
```

---

## 4. `$fillable`

من أهم الحاجات جدًا.

```php
protected $fillable = [
    'name',
    'description',
    'price',
    'quantity',
    'category_id',
];
```

### السؤال: يعني إيه fillable؟

يعني:

> الحقول المسموح تعبئتها جماعيًا

مثلًا:

```php
Product::create([
    'name' => 'Laptop',
    'price' => 15000,
]);
```

هنا Laravel تحتاج أن تعرف:

هل مسموح للحقلين دول يدخلوا؟

لو ليسا داخل `$fillable`، ستمنع العملية.

---

## السؤال: لماذا Laravel تمنع هذا أصلًا؟

للحماية من مشكلة اسمها:

## Mass Assignment

يعني المستخدم أو الكود يرسل حقولًا لم تكن تقصد السماح بها.

مثال خطير:

```php
User::create($request->all());
```

ولو الطلب فيه:

```php
[
    'name' => 'Ahmed',
    'email' => 'a@test.com',
    'is_admin' => true,
]
```

قد يتم حفظ `is_admin` لو لم تكن حذرًا.

لذلك `$fillable` مهمة جدًا.

---

## 5. `$guarded`

عكس `$fillable`.

مثال:

```php
protected $guarded = ['id'];
```

يعني:

كل الحقول مسموحة إلا `id`.

### السؤال: أيهما أفضل؟

في كثير من الحالات:

> `$fillable` أوضح وأكثر أمانًا

لأنك تحدد المسموح صراحة.

---

## 6. `$hidden`

لو عندك حقول لا تريد أن تظهر في JSON:

```php
protected $hidden = [
    'password',
    'remember_token',
];
```

مفيدة جدًا مع:

- APIs
- `toArray()`
- `toJson()`

---

## 7. `$visible`

عكس hidden.

```php
protected $visible = [
    'name',
    'email',
];
```

يعني:

لا تظهر إلا هذه الحقول فقط.

---

## 8. `$casts`

مهمة جدًا جدًا.

```php
protected $casts = [
    'is_active' => 'boolean',
    'price' => 'decimal:2',
    'published_at' => 'datetime',
    'metadata' => 'array',
];
```

### السؤال: لماذا casts مهمة؟

لأن Laravel تحول القيم تلقائيًا لنوع مناسب.

مثال:

```php
if ($product->is_active) {
    //
}
```

لو `is_active` casted إلى boolean، تتعامل معها بشكل طبيعي.

---

## 9. `$timestamps`

Laravel تفترض أن الجدول فيه:

- `created_at`
- `updated_at`

لو جدولك لا يحتوي عليهما:

```php
public $timestamps = false;
```

لكن غالبًا في أغلب الجداول الحديثة:

> نترك timestamps شغالة

---

# الجزء الخامس: أول تعامل عملي مع الـ Model

## إنشاء سجل جديد

### الطريقة الأولى: `create()`

```php
$product = Product::create([
    'name' => 'Laptop',
    'price' => 15000,
    'quantity' => 5,
]);
```

### السؤال: متى تعمل؟

لما:

- يكون عندك `$fillable`
- وتريد إنشاء سريع وواضح

---

### الطريقة الثانية: `new` ثم `save()`

```php
$product = new Product();
$product->name = 'Laptop';
$product->price = 15000;
$product->quantity = 5;
$product->save();
```

### السؤال: أيهما أستخدم؟

`create()` أسرع وأوضح في الحالات البسيطة.

`new + save()` مفيدة لو:

- تريد بناء الكائن تدريجيًا
- أو تعمل logic قبل الحفظ

---

### الطريقة الثالثة: `firstOrCreate()`

```php
$product = Product::firstOrCreate(
    ['name' => 'Laptop'],
    ['price' => 15000, 'quantity' => 5]
);
```

يعني:

- لو موجود، هاته
- لو غير موجود، أنشئه

---

### الطريقة الرابعة: `updateOrCreate()`

```php
$product = Product::updateOrCreate(
    ['name' => 'Laptop'],
    ['price' => 16000, 'quantity' => 7]
);
```

يعني:

- لو موجود، حدّثه
- لو غير موجود، أنشئه

---

# الجزء السادس: قراءة البيانات

## `all()`

```php
$products = Product::all();
```

تجيب كل السجلات.

### السؤال: هل أستخدمها دائمًا؟

لا.

في الجداول الكبيرة:

`all()` قد تكون غير مناسبة.

غالبًا الأفضل في القوائم:

```php
Product::paginate(10);
```

---

## `find()`

```php
$product = Product::find(1);
```

تبحث بالـ ID.

لو لم تجد شيئًا:

ترجع `null`.

---

## `findOrFail()`

```php
$product = Product::findOrFail(1);
```

لو لم تجد:

Laravel ترمي exception غالبًا ينتج عنها `404`.

وده شائع جدًا في controllers.

---

## `first()`

```php
$product = Product::first();
```

ترجع أول سجل.

---

## `where()`

```php
$product = Product::where('name', 'Laptop')->first();
```

أو:

```php
$products = Product::where('price', '>', 10000)->get();
```

---

## شروط متعددة

```php
$products = Product::where('price', '>', 10000)
    ->where('quantity', '>', 0)
    ->get();
```

---

## ترتيب

```php
$products = Product::orderBy('price', 'asc')->get();
$products = Product::latest()->get();
$products = Product::oldest()->get();
```

---

## تحديد عدد

```php
$products = Product::take(5)->get();
$products = Product::limit(10)->get();
```

---

## pagination

```php
$products = Product::paginate(10);
```

وفي Blade:

```blade
{{ $products->links() }}
```

### السؤال: ليه pagination مهمة؟

لأن:

- الأداء أفضل
- تجربة المستخدم أفضل
- لا تحمل الصفحة آلاف السجلات مرة واحدة

---

# الجزء السابع: تحديث البيانات

## `update()`

```php
$product = Product::findOrFail(1);

$product->update([
    'price' => 17000,
    'quantity' => 8,
]);
```

---

## تغيير الحقول ثم `save()`

```php
$product = Product::findOrFail(1);
$product->price = 17000;
$product->quantity = 8;
$product->save();
```

### السؤال: الفرق بين `update()` و `save()`؟

`update()`:

- سريعة للحالات البسيطة
- تأخذ array

`save()`:

- مفيدة لو غيّرت properties واحدة واحدة

---

## `increment()` و `decrement()`

```php
$product->increment('quantity');
$product->increment('quantity', 5);

$product->decrement('quantity');
$product->decrement('quantity', 2);
```

مفيدة جدًا في:

- stock
- views
- counters

---

# الجزء الثامن: حذف البيانات

## `delete()`

```php
$product = Product::findOrFail(1);
$product->delete();
```

---

## `destroy()`

```php
Product::destroy(1);
Product::destroy([1, 2, 3]);
```

---

## حذف بشرط

```php
Product::where('quantity', 0)->delete();
```

---

## Soft Deletes

### السؤال: يعني إيه Soft Delete؟

يعني:

> السجل لا يُحذف فعليًا من الجدول، بل يُعلَّم كمحذوف

وده مفيد لو:

- تريد استرجاعه
- تريد audit history
- تريد الاحتفاظ بالبيانات

---

### في migration

```php
$table->softDeletes();
```

وده ينشئ عمود:

```text
deleted_at
```

---

### في model

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
}
```

---

### الاستخدام

```php
$product->delete();

$deletedProducts = Product::onlyTrashed()->get();
$allProducts = Product::withTrashed()->get();

$product->restore();
$product->forceDelete();
```

---

# الجزء التاسع: العلاقات داخل الـ Model

## السؤال: ليه العلاقات مهمة؟

لأن الجداول في الحياة الحقيقية لا تعيش وحدها.

مثال:

- كل منتج ينتمي إلى فئة
- كل مستخدم له طلبات
- كل مقال له تعليقات

فالـ model لا تخزن بيانات نفسها فقط،
بل تعرف أيضًا كيف ترتبط بغيرها.

---

## `belongsTo`

مثال: المنتج ينتمي إلى فئة

```php
public function category()
{
    return $this->belongsTo(Category::class);
}
```

---

## `hasMany`

مثال: الفئة لها منتجات كثيرة

```php
public function products()
{
    return $this->hasMany(Product::class);
}
```

---

## `belongsToMany`

مثال: المنتج له وسوم كثيرة، والوسم له منتجات كثيرة

```php
public function tags()
{
    return $this->belongsToMany(Tag::class);
}
```

---

## الاستخدام

```php
$product = Product::findOrFail(1);

echo $product->category->name;

foreach ($product->tags as $tag) {
    echo $tag->name;
}
```

---

# الجزء العاشر: Eager Loading

## السؤال: ما المشكلة التي يحلها؟

مشكلة مشهورة جدًا اسمها:

## N+1 Problem

مثال سيء:

```php
$products = Product::all();

foreach ($products as $product) {
    echo $product->category->name;
}
```

هنا قد Laravel تعمل استعلامًا إضافيًا لكل منتج.

---

## الحل

```php
$products = Product::with('category')->get();
```

أو:

```php
$products = Product::with(['category', 'tags'])->get();
```

### القاعدة العملية

لو تعرف أنك ستستخدم العلاقة:

> اعمل eager loading من البداية

---

# الجزء الحادي عشر: Scopes

## السؤال: يعني إيه Scope؟

لو عندك استعلام يتكرر كثيرًا:

```php
Product::where('is_active', true)->where('quantity', '>', 0)
```

بدل ما تكرره، اعمله scope.

---

## Local Scope

```php
class Product extends Model
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
```

### الاستخدام

```php
$products = Product::active()->available()->get();
```

وده أنظف بكثير.

---

# الجزء الثاني عشر: Accessors و Mutators و Casts

## Accessor

تستخدم لما تريد تعديل القيمة عند القراءة.

مثال:

```php
public function getPriceFormattedAttribute()
{
    return number_format($this->price, 2) . ' ج.م';
}
```

الاستخدام:

```php
echo $product->price_formatted;
```

---

## Mutator

تستخدم لما تريد تعديل القيمة قبل الحفظ.

مثال:

```php
public function setNameAttribute($value)
{
    $this->attributes['name'] = trim($value);
}
```

---

## Casts

غالبًا في Laravel الحديثة، كثير من التحويلات الأفضل تكون عبر:

```php
protected $casts = [
    'is_active' => 'boolean',
    'price' => 'decimal:2',
    'published_at' => 'datetime',
    'metadata' => 'array',
];
```

---

# الجزء الثالث عشر: Events داخل الـ Model

## السؤال: إمتى أستخدمها؟

لو عندك سلوك مرتبط دائمًا بالـ model.

مثال:

- إنشاء slug عند الإنشاء
- تنظيف cache بعد التحديث
- حذف ملف عند الحذف

مثال:

```php
protected static function booted()
{
    static::creating(function ($product) {
        $product->slug = \Illuminate\Support\Str::slug($product->name);
    });
}
```

### تنبيه مهم

مفيد جدًا، لكن لا تبالغ.

لو المنطق صار معقدًا جدًا، service أو observer قد يكون أفضل.

---

# الجزء الرابع عشر: مثال Model نظيف

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function getPriceFormattedAttribute()
    {
        return number_format($this->price, 2) . ' ج.م';
    }
}
```

---

# الجزء الخامس عشر: Clean Code و Best Practices

## 1. لا تجعل الـ Model مجرد حاوية فارغة لو عندك منطق خاص بها

لو عندك:

- scopes
- relations
- casts
- accessors بسيطة

مكانها الطبيعي في الـ model.

---

## 2. لكن لا تحول الـ Model إلى وحش

لو model بدأت تحتوي:

- business logic ضخمة
- integrations
- payment rules
- workflow معقد

فأنت غالبًا تحتاج:

- service
- action
- domain class

---

## 3. استخدم `$fillable` بوضوح

ودي من أهم best practices.

---

## 4. استخدم eager loading عند الحاجة

خصوصًا في القوائم.

---

## 5. استخدم scopes للاستعلامات المتكررة

عشان الكود يبقى نظيفًا ومقروءًا.

---

## 6. لا تكتب حسابات العرض في controller كل مرة

لو عندك قيمة محسوبة تتكرر، accessor قد يكون مناسبًا.

---

## 7. لا تخلط بين "منطق العرض" و"منطق البيانات"

الـ model تتعامل مع البيانات.
الـ view تعرض.
الـ controller تنسق.

---

# الجزء السادس عشر: أخطاء شائعة جدًا

## 1. `Add [field] to fillable property`

السبب:

الحقل ليس داخل `$fillable`.

---

## 2. `Call to undefined relationship`

السبب:

اسم العلاقة غير موجود أو مكتوب بشكل خاطئ.

---

## 3. `Trying to get property of non-object`

غالبًا لأن العلاقة `null`.

مثال:

```php
echo $product->category?->name ?? 'بدون فئة';
```

---

## 4. استخدام `all()` في مكان لا يناسب

قد يسبب مشاكل أداء.

---

## 5. Model مكتوب فيها كل شيء

دي مشكلة تصميم وليست error تقني.

---

# الجزء السابع عشر: أسئلة المبتدئ

## هل كل جدول لازم له Model؟

في أغلب مشاريع Laravel:

نعم غالبًا.

لكن مش شرط 100% لكل حالة نادرة.

---

## هل الـ Model هي نفسها الـ migration؟

لا.

- migration تصمم الجدول
- model تتعامل مع الجدول

---

## هل لازم اسم الـ Model يكون مفرد؟

نعم غالبًا هذا هو الصحيح في Laravel convention.

---

## هل ينفع أستخدم SQL مباشر بدل model؟

نعم.

لكن في أغلب الشغل الطبيعي داخل Laravel، Eloquent أوضح وأسهل.

---

## هل كل شيء لازم يتحط في model؟

لا.

الـ model لها دور، لكنها ليست مكان كل منطق المشروع.

---

# الجزء الثامن عشر: الملخص النهائي

## الـ Model هي

الكلاس التي تمثل جدولًا في قاعدة البيانات، وتسمح لك:

- بقراءة البيانات
- بإنشائها
- بتحديثها
- بحذفها
- بربطها بجداول أخرى
- وبناء استعلامات نظيفة عبر Eloquent

---

## الجملة الذهبية

لو عايز تختصر الدرس كله:

> الـ Model في Laravel هي التمثيل البرمجي للجدول داخل قاعدة البيانات، وEloquent هي الأداة التي تجعل التعامل مع هذا الجدول طبيعيًا وسهلًا ونظيفًا داخل الكود.

---

## الخطوة التالية

بعد ما فهمت الـ Models، فالدرس الطبيعي التالي هو:

## `10-laravel-relationships-guide.md`

لأن العلاقات هي التوسع الطبيعي لفهم الـ models في الحياة الحقيقية.

</div>
