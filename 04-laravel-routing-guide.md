<div dir="rtl">

# 🛣️ شرح Routing في Laravel - الدليل التفصيلي جدًا للمبتدئين

### فهم الـ Routes من الصفر وكأنك أول مرة تشوف Laravel

---

## 🤔 يعني إيه Route أصلًا؟

تعالى نتخيل الموضوع بشكل بسيط جدًا:

أنت عندك موقع.

المستخدم فتح المتصفح وكتب:

```text
http://127.0.0.1:8000/products
```

السؤال هنا:

**لما المستخدم يطلب الرابط ده، Laravel يعمل إيه؟**

هنا ييجي دور الـ **Route**.

الـ Route ببساطة هو:

> "قاعدة" بتقول لـ Laravel:
> لو حد طلب الرابط ده، نفذ الكود ده.

يعني الـ Route عامل زي موظف استقبال:

- لو الزائر طلب `/`
  → ودّيه للصفحة الرئيسية
- لو الزائر طلب `/about`
  → ودّيه لصفحة "من نحن"
- لو الزائر طلب `/products`
  → هاتله كل المنتجات
- لو الزائر طلب `/products/5`
  → هاتله المنتج رقم 5

---

## 🎯 الـ Route بيحل إيه؟

من غير Routing، Laravel مش هيعرف:

- الرابط ده يتعامل معاه إزاي
- أي Controller يشتغل
- أي View تتعرض
- هل الطلب ده GET ولا POST

فالـ Routing هو أول باب بيدخله أي Request داخل التطبيق.

بمعنى أدق:

```text
المستخدم يطلب URL
↓
Laravel يشوف الـ Route المناسب
↓
يشغل Closure أو Controller
↓
يرجع Response
```

---

## 📦 فين بنكتب الـ Routes؟

في Laravel، الـ Routes غالبًا بتكون داخل فولدر:

```text
routes/
```

وأشهر ملفين:

### `routes/web.php`

ده خاص بالصفحات العادية بتاعة الموقع.

مثال:
- الصفحة الرئيسية
- صفحة المنتجات
- صفحة تسجيل الدخول
- لوحة التحكم

الملف ده شغال مع:
- sessions
- cookies
- CSRF protection

يعني مناسب جدًا لأي Web App عادي.

---

### `routes/api.php`

ده خاص بالـ API.

يعني لو عندك تطبيق موبايل أو frontend منفصل وعايز يرجع JSON.

الـ routes هنا غالبًا:
- مش بتستخدم session
- غالبًا بترجع JSON
- بيكون عليها prefix تلقائي اسمه `/api`

يعني لو كتبت route بالشكل ده في `api.php`:

```php
Route::get('/products', function () {
    return ['name' => 'Laptop'];
});
```

الرابط الحقيقي هيكون:

```text
/api/products
```

---

## 🧠 أول مثال Route في حياتك

افتح ملف:

```text
routes/web.php
```

واكتب:

```php
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello World';
});
```

### شرح الكود كلمة كلمة

#### `Route`

ده الـ Facade اللي Laravel بيوفره علشان تسجل Routes.

#### `get`

معناها:

> شغّل الـ route ده لما ييجي Request من نوع GET

وده النوع اللي بيستخدمه المتصفح غالبًا لما تفتح صفحة.

#### `'/hello'`

ده الـ URI أو المسار.

يعني لو المستخدم دخل:

```text
http://127.0.0.1:8000/hello
```

Laravel هيمسك الـ route ده.

#### `function () { return 'Hello World'; }`

دي Closure.

يعني كود مباشر يتنفذ لما route ده يتطلب.

---

## 🌐 يعني إيه GET و POST و PUT و DELETE؟

دي اسمها **HTTP Methods**.

كل request جاي للموقع بيبقى له "نوع".

### أشهر الأنواع:

#### `GET`

للجلب أو العرض.

مثال:
- عرض الصفحة الرئيسية
- عرض المنتجات
- عرض صفحة إضافة منتج

```php
Route::get('/products', function () {
    return 'كل المنتجات';
});
```

---

#### `POST`

لإرسال بيانات جديدة.

مثال:
- حفظ مستخدم جديد
- حفظ منتج جديد
- إرسال فورم تسجيل

```php
Route::post('/products', function () {
    return 'تم حفظ المنتج';
});
```

---

#### `PUT`

لتحديث سجل كامل موجود.

مثال:

```php
Route::put('/products/{id}', function ($id) {
    return "تم تحديث المنتج رقم $id";
});
```

---

#### `PATCH`

لتحديث جزئي.

Laravel بيدعمه زي `PUT` في أغلب الشغل اليومي.

---

#### `DELETE`

لحذف سجل.

```php
Route::delete('/products/{id}', function ($id) {
    return "تم حذف المنتج رقم $id";
});
```

---

## 📋 كل أنواع الـ Route Methods المشهورة

```php
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);
```

وفي كمان:

### `match`

لو عايز نفس الرابط يشتغل مع أكتر من method:

```php
Route::match(['get', 'post'], '/contact', function () {
    return 'صفحة أو إرسال فورم التواصل';
});
```

### `any`

يعني أي method.

```php
Route::any('/test', function () {
    return 'أي نوع request هيوصل هنا';
});
```

لكن للمبتدئين:

> الأفضل تكتب كل Route بنوعه الصحيح بدل `any`

عشان الكود يبقى واضح.

---

## 🧱 Route باستخدام Closure ولا Controller؟

عندك طريقتين أساسيتين:

### الطريقة الأولى: Closure

```php
Route::get('/about', function () {
    return view('about');
});
```

دي مناسبة للحاجات البسيطة جدًا.

---

### الطريقة الثانية: Controller

وده الأفضل في المشاريع الحقيقية.

```php
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index']);
```

### شرح السطر ده

Laravel هنا بيقول:

- لو حد فتح `/products`
- شغّل `ProductController`
- ونفذ منه method اسمها `index`

يعني:

```text
/products
↓
ProductController@index
```

ودي الطريقة الطبيعية اللي هتمشي عليها في أي مشروع حقيقي.

---

## 🧩 ربط Routing بالـ MVC

عشان الصورة تكتمل:

### السيناريو:

المستخدم فتح:

```text
/products
```

Laravel يعمل إيه؟

1. يروح يبص في `routes/web.php`
2. يلاقي إن `/products` مربوطة بـ `ProductController@index`
3. يشغل الـ Controller
4. الـ Controller يجيب البيانات من Model
5. الـ Controller يرجع View
6. المستخدم يشوف الصفحة

يعني:

```text
Route
↓
Controller
↓
Model
↓
View
```

فالـ Route هو أول نقطة اتصال في السلسلة دي.

---

## 📝 مثال عملي بسيط جدًا

### في `routes/web.php`

```php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'الصفحة الرئيسية';
});

Route::get('/about', function () {
    return 'من نحن';
});

Route::get('/contact', function () {
    return 'تواصل معنا';
});
```

### النتيجة

- `/` → الصفحة الرئيسية
- `/about` → من نحن
- `/contact` → تواصل معنا

---

## 🎨 Route يرجع View مباشرة

أحيانًا انت مش محتاج Controller أصلًا.

لو كل اللي عايزه إن route يفتح View معينة، ممكن تكتب:

```php
Route::view('/welcome', 'welcome');
```

يعني:

- لما حد يفتح `/welcome`
- رجّع View اسمها `welcome`

وده اختصار بدل:

```php
Route::get('/welcome', function () {
    return view('welcome');
});
```

ولو عايز تبعت بيانات:

```php
Route::view('/welcome', 'welcome', [
    'name' => 'Ahmed',
]);
```

---

## 🔁 Route يعمل Redirect

بدل ما تكتب function كاملة:

```php
Route::get('/home', function () {
    return redirect('/');
});
```

تقدر تختصرها:

```php
Route::redirect('/home', '/');
```

يعني:

- أي حد يدخل `/home`
- يتحول تلقائيًا إلى `/`

ولو عايز Redirect دائم:

```php
Route::permanentRedirect('/old-page', '/new-page');
```

---

## 📌 Route Parameters

دي من أهم الحاجات جدًا.

أحيانًا الرابط بيكون فيه جزء متغير.

مثال:

```text
/products/5
/products/10
/products/100
```

في كل مرة الرقم بيتغير.

الرقم ده اسمه **Parameter**.

### مثال:

```php
Route::get('/products/{id}', function ($id) {
    return "أنت طلبت المنتج رقم $id";
});
```

### لو المستخدم فتح:

```text
/products/7
```

النتيجة:

```text
أنت طلبت المنتج رقم 7
```

### يعني إيه `{id}`؟

ده مكان متغير.

Laravel بياخد القيمة من الرابط ويحطها في المتغير `$id`.

---

## 📍 Required Parameters

يعني parameter لازم يكون موجود.

```php
Route::get('/users/{id}', function ($id) {
    return "User ID: $id";
});
```

الرابط ده لازم يبقى فيه ID.

يعني:

- `/users/3` ✅
- `/users/15` ✅
- `/users` ❌

---

## 📍 Optional Parameters

أحيانًا عايز parameter يكون اختياري.

```php
Route::get('/students/{name?}', function ($name = 'ضيف') {
    return "أهلا يا $name";
});
```

### النتيجة:

- `/students` → أهلا يا ضيف
- `/students/Ahmed` → أهلا يا Ahmed

### ملاحظتين مهمين:

#### 1. لازم تحط `?` في `{name?}`

علشان Laravel يفهم إنه optional.

#### 2. لازم تدي Default Value في function

```php
$name = 'ضيف'
```

علشان لو المستخدم ما بعتش القيمة.

---

## 🔒 تحديد شكل الـ Parameters

أحيانًا عايز تقول:

> أنا عايز الـ ID يبقى أرقام فقط

Laravel بيدعم constraints.

```php
Route::get('/products/{id}', function ($id) {
    return "المنتج رقم $id";
})->where('id', '[0-9]+');
```

يعني:

- `/products/5` ✅
- `/products/999` ✅
- `/products/ahmed` ❌

---

## 🏷️ Named Routes

دي من أهم الحاجات في Laravel.

بدل ما تفضل تعتمد على الرابط نفسه، تقدر تدي للـ route اسم.

مثال:

```php
Route::get('/products', function () {
    return 'كل المنتجات';
})->name('products.index');
```

هنا اسم الـ route بقى:

```text
products.index
```

---

## ليه Named Routes مهمة؟

لأنك بدل ما تكتب الرابط يدوي:

```php
href="/products"
```

تكتب:

```php
route('products.index')
```

ولو غيرت URL بعدين من:

```text
/products
```

إلى:

```text
/items
```

مش هتحتاج تغيّر كل اللينكات في المشروع.

هتغير الـ Route مرة واحدة فقط.

---

## 🧪 مثال عملي على Named Route

```php
Route::get('/products', function () {
    return 'صفحة المنتجات';
})->name('products.index');
```

وفي Blade:

```blade
<a href="{{ route('products.index') }}">المنتجات</a>
```

Laravel هيحوّل الاسم لرابط حقيقي تلقائيًا.

---

## 🔗 Named Route مع Parameters

```php
Route::get('/products/{id}', function ($id) {
    return "تفاصيل المنتج $id";
})->name('products.show');
```

ولما تيجي تولد الرابط:

```php
route('products.show', ['id' => 5]);
```

النتيجة:

```text
/products/5
```

وفي Blade:

```blade
<a href="{{ route('products.show', ['id' => 5]) }}">
    عرض المنتج
</a>
```

---

## 🎮 ربط Route بالـ Controller

خلينا نعملها بالطريقة الصحيحة.

### أولًا: أنشئ Controller

```bash
php artisan make:controller ProductController
```

### هيعمل ملف:

```text
app/Http/Controllers/ProductController.php
```

### اكتب فيه:

```php
<?php

namespace App\Http\Controllers;

class ProductController extends Controller
{
    public function index()
    {
        return 'كل المنتجات';
    }

    public function show($id)
    {
        return "تفاصيل المنتج رقم $id";
    }
}
```

### وبعدها في `routes/web.php`

```php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
```

### النتيجة:

- `/products` → ينفذ `index()`
- `/products/4` → ينفذ `show(4)`

---

## 🧰 Resource Routes

دي من أحسن الحاجات في Laravel.

لو عندك CRUD:

- عرض كل السجلات
- صفحة إضافة
- حفظ جديد
- عرض عنصر واحد
- صفحة تعديل
- تحديث
- حذف

Laravel يقدر يعملهم كلهم بسطر واحد.

### مثال:

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

السطر ده لوحده بيعمل Routes كتير جدًا.

---

## 📋 الـ Routes اللي `resource` بيعملها

| Method | URL | Method in Controller | اسم الـ Route |
|-------|-----|----------------------|---------------|
| GET | `/products` | `index` | `products.index` |
| GET | `/products/create` | `create` | `products.create` |
| POST | `/products` | `store` | `products.store` |
| GET | `/products/{product}` | `show` | `products.show` |
| GET | `/products/{product}/edit` | `edit` | `products.edit` |
| PUT/PATCH | `/products/{product}` | `update` | `products.update` |
| DELETE | `/products/{product}` | `destroy` | `products.destroy` |

---

## 🧠 يعني إيه الكلام ده عمليًا؟

لو أنت بتبني نظام منتجات، بدل ما تكتب:

```php
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/create', [ProductController::class, 'create']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
```

تكتب بس:

```php
Route::resource('products', ProductController::class);
```

---

## 🏗️ Resource Controller كامل

اعمله بالأمر ده:

```bash
php artisan make:controller ProductController --resource
```

Laravel هيجهزهولك بالدوال الأساسية:

```php
public function index() {}
public function create() {}
public function store(Request $request) {}
public function show(string $id) {}
public function edit(string $id) {}
public function update(Request $request, string $id) {}
public function destroy(string $id) {}
```

---

## 🧪 مثال عملي متكامل

### `routes/web.php`

```php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class);
```

### `ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return 'عرض كل المنتجات';
    }

    public function create()
    {
        return 'صفحة إضافة منتج';
    }

    public function store(Request $request)
    {
        return 'تم حفظ المنتج';
    }

    public function show(string $id)
    {
        return "عرض المنتج رقم $id";
    }

    public function edit(string $id)
    {
        return "صفحة تعديل المنتج رقم $id";
    }

    public function update(Request $request, string $id)
    {
        return "تم تحديث المنتج رقم $id";
    }

    public function destroy(string $id)
    {
        return "تم حذف المنتج رقم $id";
    }
}
```

---

## 🗂️ Route Groups

أحيانًا بيكون عندك مجموعة routes بينهم شيء مشترك.

مثال:

- كلهم خاصين بالأدمن
- كلهم يبدأوا بـ `/admin`
- كلهم محتاجين middleware معين
- كلهم أسماؤهم تبدأ بـ `admin.`

بدل ما تكرر ده كل مرة، تستخدم Group.

---

## 📍 Prefix

```php
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return 'لوحة التحكم';
    });

    Route::get('/products', function () {
        return 'منتجات الأدمن';
    });
});
```

### النتيجة:

- `/admin/dashboard`
- `/admin/products`

---

## 🏷️ Name Prefix

```php
Route::name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return 'لوحة التحكم';
    })->name('dashboard');
});
```

اسم الـ route النهائي:

```text
admin.dashboard
```

---

## 🔐 Middleware Group

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard';
    });

    Route::get('/profile', function () {
        return 'Profile';
    });
});
```

يعني:

- الـ routes دي مش هتشتغل إلا لو المستخدم عامل login

---

## 🧪 كلهم مع بعض

وده شكل احترافي جدًا:

```php
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/dashboard', function () {
            return 'لوحة تحكم الأدمن';
        })->name('dashboard');

        Route::get('/products', function () {
            return 'إدارة المنتجات';
        })->name('products.index');
    });
```

### النتيجة:

- URL:
  - `/admin/dashboard`
  - `/admin/products`

- Route Names:
  - `admin.dashboard`
  - `admin.products.index`

---

## 🎯 Route Model Binding

دي من أجمل مميزات Laravel.

بدل ما تبعت ID وتروح تدور بنفسك على الـ Model:

### الطريقة التقليدية:

```php
Route::get('/products/{id}', function ($id) {
    $product = Product::findOrFail($id);
    return $product;
});
```

Laravel يقدر يعمل ده مكانك.

### الطريقة الأفضل:

```php
use App\Models\Product;

Route::get('/products/{product}', function (Product $product) {
    return $product;
});
```

### إيه اللي حصل هنا؟

Laravel شاف:

- في الـ route parameter اسمه `{product}`
- وفي الـ function parameter اسمه `Product $product`

فهم تلقائيًا:

> هات الـ Product اللي الـ id بتاعه جاي في الرابط

يعني لو المستخدم فتح:

```text
/products/8
```

Laravel كأنه عمل:

```php
Product::findOrFail(8)
```

بشكل تلقائي.

---

## ⚠️ شرط مهم في Route Model Binding

اسم الـ parameter في الـ route لازم يطابق اسم المتغير.

صح:

```php
Route::get('/products/{product}', function (Product $product) {
    return $product->name;
});
```

غلط:

```php
Route::get('/products/{id}', function (Product $product) {
    return $product->name;
});
```

في الحالة دي Laravel مش هيفهم الربط التلقائي بشكل صحيح.

---

## 🧭 Route Model Binding مع Controller

```php
use App\Http\Controllers\ProductController;

Route::get('/products/{product}', [ProductController::class, 'show']);
```

وفي الـ Controller:

```php
use App\Models\Product;

public function show(Product $product)
{
    return view('products.show', compact('product'));
}
```

هنا أنت استلمت الـ model جاهز، من غير ما تكتب `findOrFail`.

---

## 🧱 Fallback Route

ده route بيتنفذ لو ولا route من الموجودين طابق الطلب.

يعني بدل صفحة 404 الافتراضية، ممكن تعمل واحدة من عندك.

```php
Route::fallback(function () {
    return 'الصفحة غير موجودة';
});
```

غالبًا بتحطه في آخر `web.php`.

---

## 🪪 Form Method Spoofing

دي نقطة بتتلخبط على المبتدئين.

HTML form الطبيعي بيدعم:

- GET
- POST

بس ما بيدعمش:

- PUT
- PATCH
- DELETE

فلو عايز تبعت request من نوع `PUT` أو `DELETE` من form، Laravel بيوفرلك حيلة اسمها **method spoofing**.

### مثال تعديل منتج:

```blade
<form method="POST" action="{{ route('products.update', $product->id) }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $product->name }}">
    <button type="submit">تعديل</button>
</form>
```

### إيه اللي حصل؟

- الفورم فعليًا بيبعت `POST`
- لكن `@method('PUT')` بتقول لـ Laravel:
  - تعامل مع الطلب ده على إنه `PUT`

---

## 🔐 CSRF مع Routes

أي form في `web.php` بيبعت:

- POST
- PUT
- PATCH
- DELETE

لازم تحط فيه:

```blade
@csrf
```

مثال:

```blade
<form method="POST" action="/products">
    @csrf
    <input type="text" name="name">
    <button type="submit">حفظ</button>
</form>
```

من غير `@csrf` Laravel غالبًا هيرفض الطلب.

---

## 🔗 توليد الروابط في Laravel

بدل ما تكتب روابط يدويًا، Laravel بيديك Helpers.

### `url()`

```php
echo url('/products');
```

النتيجة مثلًا:

```text
http://127.0.0.1:8000/products
```

---

### `route()`

ودي الأفضل غالبًا لما يكون عندك named routes.

```php
echo route('products.index');
```

ولو route فيها parameter:

```php
echo route('products.show', ['id' => 5]);
```

---

## 🧪 مثال واقعي في Blade

```blade
<a href="{{ route('products.index') }}">كل المنتجات</a>

<a href="{{ route('products.create') }}">إضافة منتج</a>

<a href="{{ route('products.show', ['product' => 1]) }}">عرض المنتج</a>
```

---

## 🛠️ كيف أشوف كل الـ Routes اللي عندي؟

من أهم الأوامر في Laravel:

```bash
php artisan route:list
```

الأمر ده بيطلعلك:

- method
- URI
- route name
- action
- middleware

وده مهم جدًا وقت debugging.

---

## ✅ مثال على ناتج `route:list`

غالبًا هتشوف حاجة شبه كده:

```text
GET|HEAD   products ............... products.index   ProductController@index
POST       products ............... products.store   ProductController@store
GET|HEAD   products/create ........ products.create  ProductController@create
GET|HEAD   products/{product} ..... products.show    ProductController@show
PUT|PATCH  products/{product} ..... products.update  ProductController@update
DELETE     products/{product} ..... products.destroy ProductController@destroy
```

لو route مش شغالة، أول حاجة اعملها:

```bash
php artisan route:list
```

---

## 🐛 أخطاء شائعة جدًا

### الخطأ 1: Route not defined

يعني أنت ناديت على route name مش موجود.

مثال:

```blade
route('products.index')
```

لكن route دي مش متسجلة أو اسمها مختلف.

### الحل:

- راجع اسم الـ route
- استخدم `php artisan route:list`

---

### الخطأ 2: 404 Not Found

يعني Laravel ملقاش route مناسبة.

### الأسباب المحتملة:

- الرابط غلط
- method غلط
- route اتحطت في ملف غير مناسب
- parameter ناقص

---

### الخطأ 3: Method Not Allowed

يعني route موجودة، لكن بنوع request مختلف.

مثال:

أنت مسجل:

```php
Route::post('/products', ...);
```

لكن فتحت الرابط في المتصفح مباشرة.

المتصفح غالبًا بيبعت GET، فLaravel يقولك:

> لا، أنا عندي `/products` لكن لـ POST فقط

---

### الخطأ 4: Missing required parameter

مثال:

```php
route('products.show')
```

مع إن الـ route محتاجة `id` أو `product`.

### الحل:

ابعت الـ parameter المطلوبة:

```php
route('products.show', ['product' => 5])
```

---

## 💡 أفضل الممارسات

### 1. استخدم Controllers بدل Closures في المشاريع الحقيقية

الـ closure مناسبة للتجربة أو الصفحات البسيطة.

لكن في المشروع الحقيقي:

> خليك في Controllers

---

### 2. استخدم Named Routes دائمًا

بدل:

```blade
href="/products"
```

استخدم:

```blade
href="{{ route('products.index') }}"
```

---

### 3. استخدم Resource Routes للـ CRUD

بدل كتابة 7 routes بإيدك.

---

### 4. رتّب الـ Routes في Groups

خصوصًا لما يكون عندك:

- admin routes
- auth routes
- dashboard routes

---

### 5. استخدم Route Model Binding

لأنه:

- أنظف
- أوضح
- أقل كود
- أقل احتمالات غلط

---

## 🎓 مشروع تدريبي صغير

اعمل routes لنظام طلاب بالشكل ده:

### المطلوب:

- `/students` → عرض كل الطلاب
- `/students/create` → صفحة إضافة طالب
- `POST /students` → حفظ طالب جديد
- `/students/{student}` → عرض طالب واحد
- `/students/{student}/edit` → صفحة تعديل طالب
- `PUT /students/{student}` → تحديث الطالب
- `DELETE /students/{student}` → حذف الطالب

### المطلوب منك:

1. اعمل `StudentController` من نوع resource
2. سجل `Route::resource('students', StudentController::class);`
3. اعمل `php artisan route:list`
4. حاول تفهم كل route اتعملت

---

## 📊 ملخص سريع جدًا

### الـ Route يعني:

قاعدة بتقول:

> لو المستخدم طلب الرابط ده، نفذ الكود ده

### أهم ما تعلمناه:

- `routes/web.php` للويب
- `routes/api.php` للـ API
- `Route::get`, `post`, `put`, `patch`, `delete`
- route parameters
- named routes
- resource routes
- route groups
- route model binding
- route:list

---

## 🧠 احفظ الجملة دي

لو سألك حد:

**الـ Routing في Laravel يعني إيه؟**

قول:

> الـ Routing هو النظام اللي بيحدد Laravel هيعمل إيه لما يوصله طلب على رابط معين وبـ HTTP method معينة، وبيوجّه الطلب ده إلى Closure أو Controller مناسب.

---

## 🚀 الخطوة اللي بعد الدرس ده

بعد ما تفهم الـ Routing كويس، الدرس الطبيعي اللي بعده هو:

### `Requests + Validation + Responses`

لأنك بعد ما عرفت الطلب بيوصل لفين، لازم تفهم:

- إزاي تمسك البيانات اللي جاية من المستخدم
- إزاي تتحقق منها
- إزاي ترجع response صح

</div>
