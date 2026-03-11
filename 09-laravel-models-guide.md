<div dir="rtl">

# 🎭 شرح Models في لارافيل - الدليل الشامل

### فهم الـ Model وعلاقته بالداتابيز من الصفر

---

## 🤔 يعني إيه Model؟

**تخيل معايا:**

الداتابيز = مخزن كبير فيه أرفف كتيرة (جداول)  
كل رف فيه صناديق (صفوف)  
كل صندوق فيه حاجات (أعمدة)

**الـ Model = الموظف اللي بيتعامل مع المخزن!**

```
أنت: "عايز أجيب كل المنتجات"
Model: "حاضر، هروح للمخزن وأجيبهم"
Model: يروح للداتابيز، يجيب البيانات، يرجعها لك

أنت: "عايز أضيف منتج جديد"
Model: "حاضر، هحفظه في المخزن"
Model: ياخد البيانات، يحفظها في الداتابيز
```

---

## 📚 التشبيه الكامل

| المفهوم | في الحياة | في لارافيل |
|---------|-----------|------------|
| المخزن | الداتابيز | Database |
| الرف | الجدول | Table |
| الصندوق | الصف | Row/Record |
| المحتويات | البيانات | Columns/Data |
| الموظف | الـ Model | Eloquent Model |

---

## 🏗️ إنشاء Model

### الطريقة الأولى: Model فقط

```bash
php artisan make:model Product
```

**النتيجة:**  
ملف واحد في `app/Models/Product.php`

---

### الطريقة الثانية: Model + Migration

```bash
php artisan make:model Product -m
```

**النتيجة:**
- Model في `app/Models/Product.php`
- Migration في `database/migrations/xxxx_create_products_table.php`

---

### الطريقة الثالثة: كل حاجة مرة واحدة! 🚀

```bash
php artisan make:model Product -mcr
```

**الحروف:**
- `m` = Migration
- `c` = Controller
- `r` = Resource Controller

**النتيجة:**
- ✅ Model
- ✅ Migration
- ✅ Controller (مع كل الدوال الجاهزة)

---

### الطريقة الرابعة: الشاملة

```bash
php artisan make:model Product -a
```

**بيعمل:**
- Model
- Migration
- Controller
- Factory (لإنشاء بيانات وهمية)
- Seeder (لملء الداتابيز)
- Policy (للصلاحيات)
- FormRequest (للتحقق من البيانات)

---

## 📂 بنية الـ Model الأساسية

### Model بسيط:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // هنا هنكتب الإعدادات والعلاقات
}
```

**ملاحظات:**
- اسم الـ Model **مفرد** وبحرف كبير: `Product`
- اسم الجدول **جمع** وصغير: `products`
- Laravel بيربط بينهم تلقائياً!

---

## ⚙️ خصائص الـ Model المهمة

### 1️⃣ اسم الجدول `$table`

**لو اسم الجدول مختلف:**

```php
class Product extends Model
{
    // لو الجدول اسمه "items" مش "products"
    protected $table = 'items';
}
```

---

### 2️⃣ المفتاح الأساسي `$primaryKey`

**لو المفتاح مش "id":**

```php
class Product extends Model
{
    // لو المفتاح اسمه "product_id"
    protected $primaryKey = 'product_id';
}
```

**لو المفتاح مش رقم:**

```php
class Product extends Model
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
}
```

---

### 3️⃣ الحقول المسموح ملؤها `$fillable`

**للحماية من Mass Assignment:**

```php
class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'category_id'
    ];
}
```

**معناها:**  
الحقول دي بس اللي مسموح نحفظها مباشرة

**مثال:**
```php
// ✅ هيشتغل
Product::create([
    'name' => 'لابتوب',
    'price' => 15000
]);

// ❌ مش هيشتغل (لو password مش في fillable)
Product::create([
    'name' => 'لابتوب',
    'admin_password' => 'secret'  // للحماية!
]);
```

---

### 4️⃣ الحقول المحمية `$guarded`

**عكس fillable (كل حاجة مسموحة إلا دي):**

```php
class Product extends Model
{
    // كل الحقول مسموحة إلا دي
    protected $guarded = ['id', 'admin_only_field'];
}
```

**أو تسمح بكل حاجة (مش آمن!):**
```php
protected $guarded = [];
```

---

### 5️⃣ الحقول المخفية `$hidden`

**حقول متظهرش في JSON:**

```php
class User extends Model
{
    protected $hidden = [
        'password',
        'remember_token',
        'secret_key'
    ];
}
```

**مثال:**
```php
$user = User::find(1);
return $user->toArray();

// النتيجة (بدون password):
[
    'id' => 1,
    'name' => 'محمد',
    'email' => 'mohamed@example.com'
    // password مش موجود!
]
```

---

### 6️⃣ الحقول الظاهرة `$visible`

**عكس hidden (بس دول اللي يظهروا):**

```php
class User extends Model
{
    protected $visible = ['name', 'email'];
}
```

---

### 7️⃣ التواريخ `$dates` و `$casts`

**تحويل التواريخ تلقائياً:**

```php
class Product extends Model
{
    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'metadata' => 'array'
    ];
}
```

**الاستخدام:**
```php
$product = Product::find(1);

// تلقائياً يتحول لـ Carbon date
echo $product->published_at->format('Y-m-d');

// boolean
if ($product->is_active) {
    echo "المنتج نشط";
}

// array تلقائياً
$product->metadata = ['color' => 'red', 'size' => 'large'];
$product->save();
```

---

### 8️⃣ تعطيل Timestamps

**لو مش عايز created_at و updated_at:**

```php
class Product extends Model
{
    public $timestamps = false;
}
```

---

## 🎯 العمليات الأساسية (CRUD)

### 1️⃣ Create - إنشاء سجل جديد

#### الطريقة الأولى: `create()`

```php
$product = Product::create([
    'name' => 'لابتوب Dell',
    'price' => 15000,
    'quantity' => 10
]);

echo $product->id;  // بيرجع الـ Model مع الـ id
```

---

#### الطريقة الثانية: `new` + `save()`

```php
$product = new Product();
$product->name = 'لابتوب HP';
$product->price = 12000;
$product->quantity = 5;
$product->save();

echo $product->id;
```

---

#### الطريقة الثالثة: `firstOrCreate()`

```php
// لو موجود يجيبه، لو مش موجود يعمله
$product = Product::firstOrCreate(
    ['name' => 'لابتوب Lenovo'],  // شرط البحث
    ['price' => 13000, 'quantity' => 3]  // البيانات لو هيتعمل
);
```

---

#### الطريقة الرابعة: `updateOrCreate()`

```php
// لو موجود يحدثه، لو مش موجود يعمله
$product = Product::updateOrCreate(
    ['name' => 'لابتوب Dell'],
    ['price' => 16000, 'quantity' => 8]
);
```

---

### 2️⃣ Read - قراءة البيانات

#### جيب كل السجلات:

```php
$products = Product::all();

foreach ($products as $product) {
    echo $product->name;
}
```

---

#### جيب سجل واحد بالـ ID:

```php
$product = Product::find(1);

if ($product) {
    echo $product->name;
} else {
    echo "مش لاقي المنتج";
}
```

---

#### جيب أو ارمي Exception:

```php
$product = Product::findOrFail(1);
// لو ملقاش، يظهر صفحة 404 تلقائياً
```

---

#### جيب أول سجل:

```php
$product = Product::first();
```

---

#### جيب سجل بشرط معين:

```php
$product = Product::where('name', 'لابتوب Dell')->first();
```

---

#### جيب بشروط متعددة:

```php
$products = Product::where('price', '>', 10000)
                   ->where('quantity', '>', 0)
                   ->get();
```

---

#### جيب مع ترتيب:

```php
// من الأرخص للأغلى
$products = Product::orderBy('price', 'asc')->get();

// من الأحدث للأقدم
$products = Product::latest()->get();

// من الأقدم للأحدث
$products = Product::oldest()->get();
```

---

#### جيب عدد محدود:

```php
// أول 10 منتجات
$products = Product::take(10)->get();

// أول 5 منتجات الأغلى
$products = Product::orderBy('price', 'desc')->limit(5)->get();
```

---

#### Pagination (ترقيم الصفحات):

```php
// 15 منتج في الصفحة
$products = Product::paginate(15);

// في الـ Blade
{{ $products->links() }}
```

---

### 3️⃣ Update - تحديث البيانات

#### الطريقة الأولى: `update()`

```php
$product = Product::find(1);
$product->update([
    'price' => 17000,
    'quantity' => 12
]);
```

---

#### الطريقة الثانية: تغيير + `save()`

```php
$product = Product::find(1);
$product->price = 17000;
$product->quantity = 12;
$product->save();
```

---

#### تحديث مجموعة:

```php
// زوّد السعر 10% لكل المنتجات
Product::where('price', '>', 10000)
       ->update(['price' => DB::raw('price * 1.1')]);
```

---

#### `increment()` و `decrement()`:

```php
$product = Product::find(1);

// زود الكمية
$product->increment('quantity');
$product->increment('quantity', 5);  // زود 5

// قلل الكمية
$product->decrement('quantity');
$product->decrement('quantity', 3);  // قلل 3
```

---

### 4️⃣ Delete - حذف البيانات

#### حذف سجل واحد:

```php
$product = Product::find(1);
$product->delete();
```

---

#### حذف بالـ ID مباشرة:

```php
Product::destroy(1);

// حذف أكتر من واحد
Product::destroy([1, 2, 3]);
Product::destroy(1, 2, 3);
```

---

#### حذف بشرط:

```php
Product::where('quantity', 0)->delete();
```

---

#### Soft Delete (حذف وهمي):

**في الـ Model:**
```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
}
```

**في الـ Migration:**
```php
$table->softDeletes();
```

**الاستخدام:**
```php
$product = Product::find(1);
$product->delete();  // مش بيتمسح، بس بيتعلّم محذوف

// جيب المحذوفات
$deletedProducts = Product::onlyTrashed()->get();

// جيب كل حاجة (عادي ومحذوف)
$allProducts = Product::withTrashed()->get();

// استرجاع المحذوف
$product->restore();

// حذف نهائي
$product->forceDelete();
```

---

## 🔍 استعلامات متقدمة (Query Scopes)

### Local Scopes - استعلامات مخصصة

**تعريف الـ Scope:**
```php
class Product extends Model
{
    // منتجات متاحة
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
    
    // منتجات نشطة
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // منتجات غالية (أكتر من سعر معين)
    public function scopeExpensive($query, $price = 10000)
    {
        return $query->where('price', '>', $price);
    }
}
```

**الاستخدام:**
```php
// جيب المنتجات المتاحة
$products = Product::available()->get();

// جيب المنتجات النشطة والمتاحة
$products = Product::active()->available()->get();

// جيب المنتجات الغالية
$products = Product::expensive(15000)->get();
```

---

### Global Scopes - استعلامات عامة

**مثال: إخفاء المنتجات المحذوفة تلقائياً:**

```php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_active', true);
    }
}
```

**تطبيقه في الـ Model:**
```php
use App\Models\Scopes\ActiveScope;

class Product extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ActiveScope);
    }
}
```

**النتيجة:**
```php
// تلقائياً بيجيب النشطة بس
$products = Product::all();

// لو عايز كل حاجة
$products = Product::withoutGlobalScope(ActiveScope::class)->get();
```

---

## 🎨 Accessors & Mutators (Getters & Setters)

### Accessors - تعديل قيمة عند القراءة

**مثال: تحويل الاسم لحروف كبيرة:**

```php
class Product extends Model
{
    // get + اسم الحقل + Attribute
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }
    
    // عرض السعر بالعملة
    public function getPriceFormattedAttribute()
    {
        return number_format($this->price, 2) . ' جنيه';
    }
}
```

**الاستخدام:**
```php
$product = Product::find(1);

echo $product->name;              // LAPTOP DELL (حروف كبيرة)
echo $product->price_formatted;   // 15,000.00 جنيه
```

---

### Mutators - تعديل قيمة عند الحفظ

**مثال: تحويل الاسم لحروف صغيرة قبل الحفظ:**

```php
class Product extends Model
{
    // set + اسم الحقل + Attribute
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
    
    // تشفير الباسورد تلقائياً
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
```

**الاستخدام:**
```php
$product = new Product();
$product->name = 'LAPTOP DELL';  // هيتحفظ: laptop dell
$product->save();
```

---

## 🔗 استخدام العلاقات في الـ Model

### مثال: منتج وفئته

```php
class Product extends Model
{
    protected $fillable = ['name', 'price', 'category_id'];
    
    // علاقة مع الفئة
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // علاقة مع التعليقات
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // علاقة مع الوسوم
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
```

**الاستخدام:**
```php
$product = Product::find(1);

// الفئة
echo $product->category->name;

// التعليقات
foreach ($product->comments as $comment) {
    echo $comment->content;
}

// الوسوم
foreach ($product->tags as $tag) {
    echo $tag->name;
}
```

---

## 📊 تحميل العلاقات (Eager Loading)

### مشكلة N+1:

```php
// ❌ بطيء - بيعمل استعلام لكل منتج!
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name;  // استعلام جديد!
}
```

---

### الحل - Eager Loading:

```php
// ✅ سريع - استعلام واحد
$products = Product::with('category')->get();
foreach ($products as $product) {
    echo $product->category->name;
}
```

---

### تحميل أكتر من علاقة:

```php
$products = Product::with(['category', 'comments', 'tags'])->get();
```

---

### تحميل علاقات متداخلة:

```php
// جيب المنتج مع الفئة ومع المنتجات التانية في نفس الفئة
$product = Product::with('category.products')->find(1);
```

---

### تحميل شرطي:

```php
$products = Product::with(['comments' => function($query) {
    $query->where('is_approved', true)
          ->orderBy('created_at', 'desc');
}])->get();
```

---

## 🎯 Events في الـ Model

### الأحداث المتاحة:

```php
class Product extends Model
{
    protected static function booted()
    {
        // قبل الإنشاء
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
        
        // بعد الإنشاء
        static::created(function ($product) {
            // أرسل إيميل للأدمن
            Mail::to('admin@example.com')->send(new ProductCreated($product));
        });
        
        // قبل التحديث
        static::updating(function ($product) {
            // سجل التغييرات
        });
        
        // بعد التحديث
        static::updated(function ($product) {
            Cache::forget('product_' . $product->id);
        });
        
        // قبل الحذف
        static::deleting(function ($product) {
            // امسح الصورة
            Storage::delete($product->image);
        });
        
        // بعد الحذف
        static::deleted(function ($product) {
            // سجل العملية
        });
    }
}
```

---

## 🔧 أمثلة عملية كاملة

### مثال 1: Model منتج كامل

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;
    
    // الجدول (اختياري - Laravel بيعرفه تلقائياً)
    protected $table = 'products';
    
    // الحقول المسموح ملؤها
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'quantity',
        'sku',
        'image',
        'is_active',
        'category_id'
    ];
    
    // الحقول المخفية
    protected $hidden = [
        'admin_notes'
    ];
    
    // تحويل الأنواع
    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'published_at' => 'datetime'
    ];
    
    // ===== العلاقات =====
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    
    // ===== Scopes =====
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
    
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    // ===== Accessors =====
    
    public function getPriceFormattedAttribute()
    {
        return number_format($this->price, 2) . ' ج.م';
    }
    
    public function getDiscountPercentAttribute()
    {
        if ($this->discount_price) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }
    
    public function getIsOnSaleAttribute()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }
    
    // ===== Mutators =====
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
        $this->attributes['slug'] = Str::slug($value);
    }
    
    // ===== Events =====
    
    protected static function booted()
    {
        static::creating(function ($product) {
            if (!$product->sku) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });
        
        static::deleting(function ($product) {
            // امسح الصورة
            if ($product->image) {
                Storage::delete($product->image);
            }
        });
    }
}
```

---

### الاستخدام:

```php
// إنشاء منتج جديد
$product = Product::create([
    'name' => 'لابتوب Dell XPS 15',
    'description' => 'لابتوب قوي للمطورين',
    'price' => 45000,
    'discount_price' => 40000,
    'quantity' => 10,
    'category_id' => 1
]);

// الـ SKU والـ slug اتعملوا تلقائياً!

// جيب المنتجات النشطة والمتاحة
$products = Product::active()->available()->get();

// جيب منتج مع علاقاته
$product = Product::with(['category', 'comments', 'tags'])->find(1);

// اعرض البيانات
echo $product->name;                  // اسم المنتج
echo $product->price_formatted;       // 45,000.00 ج.م
echo $product->discount_percent;      // 11
echo $product->is_on_sale;            // true
echo $product->category->name;        // إلكترونيات
```

---

## 💡 نصائح مهمة

### 1️⃣ استخدم Resource Controllers

```bash
php artisan make:model Product -mcr
```

---

### 2️⃣ استخدم Eager Loading دايماً

```php
// ✅ صح
Product::with('category')->get();

// ❌ غلط
Product::all(); // ثم تستدعي category لكل منتج
```

---

### 3️⃣ استخدم Scopes للاستعلامات المتكررة

```php
// بدل ما تكرر الكود ده:
Product::where('is_active', true)->where('quantity', '>', 0)->get();

// اعمل scope:
Product::active()->available()->get();
```

---

### 4️⃣ استخدم Accessors للحسابات

```php
// بدل:
$discountPercent = (($product->price - $product->discount_price) / $product->price) * 100;

// استخدم:
$product->discount_percent;
```

---

### 5️⃣ استخدم Events للعمليات الجانبية

```php
// بدل ما تكتب في الـ Controller:
$product->delete();
Storage::delete($product->image);

// اكتب في الـ Model:
static::deleting(function ($product) {
    Storage::delete($product->image);
});
```

---

## 🐛 أخطاء شائعة وحلولها

### الخطأ 1: "Add [name] to fillable property"

**السبب:** الحقل مش في `$fillable`

**الحل:**
```php
protected $fillable = ['name', 'price', ...];
```

---

### الخطأ 2: "Call to undefined relationship"

**السبب:** العلاقة مش موجودة أو مكتوبة غلط

**الحل:**
```php
public function category()  // تأكد من الاسم
{
    return $this->belongsTo(Category::class);
}
```

---

### الخطأ 3: "Trying to get property of non-object"

**السبب:** العلاقة null

**الحل:**
```php
echo $product->category?->name ?? 'بدون فئة';
```

---

## 📚 ملخص سريع

**الـ Model هو:**
- ✅ الوسيط بين الكود والداتابيز
- ✅ بيسهل التعامل مع البيانات
- ✅ بيوفر دوال جاهزة للـ CRUD
- ✅ بيدير العلاقات بين الجداول

**أهم الدوال:**
```php
// إنشاء
Product::create([...]);

// قراءة
Product::all();
Product::find(1);
Product::where('price', '>', 1000)->get();

// تحديث
$product->update([...]);

// حذف
$product->delete();
```

---

**مبروك! 🎉 دلوقتي فاهم الـ Models كويس!**

صُنع بحب ❤️ لكل مطور لارافيل

</div>
