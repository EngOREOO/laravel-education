<div dir="rtl">

# 📨 شرح Requests + Validation + Responses في Laravel

### الدليل التفصيلي جدًا للمبتدئين بعد درس الـ Routing

---

## 🎯 ليه الدرس ده مهم؟

في الدرس اللي فات فهمنا:

- يعني إيه Route
- إزاي الطلب يروح لـ Controller
- إزاي نحدد الرابط والـ method

لكن لسه فيه 3 أسئلة مهمة جدًا:

1. لما المستخدم يبعت بيانات في فورم، أنا أمسكها إزاي؟
2. أتأكد إزاي إن البيانات دي صح ومش ناقصة؟
3. بعد ما أخلص، أرجع للمستخدم إيه؟

هنا يدخلوا 3 أبطال مهمين جدًا:

- **Request**
- **Validation**
- **Response**

ودي تعتبر من أهم الدروس في Laravel كلها.

---

## 🧠 الصورة الكبيرة قبل التفاصيل

تخيل إن المستخدم ملي فورم "إضافة منتج" وضغط زر الحفظ.

الرحلة اللي بتحصل كده:

```text
المستخدم يملأ الفورم
↓
يرسل الطلب
↓
Laravel يستقبل Request
↓
Controller يمسك البيانات
↓
Validation تتأكد إن البيانات صح
↓
لو في خطأ يرجعله أخطاء
↓
لو كل شيء تمام يكمل الحفظ
↓
يرجع Response مناسب
```

يعني الدرس ده هو قلب التعامل مع الفورمز والبيانات.

---

# الجزء الأول: Request

## 🤔 يعني إيه Request؟

الـ **Request** هو كل اللي المستخدم بعته للسيرفر.

يعني مثلًا:

- البيانات اللي كتبها في الفورم
- الملفات اللي رفعها
- نوع الطلب `GET` أو `POST`
- الرابط الحالي
- الهيدر
- الـ IP
- الـ query string

ببساطة:

> الـ Request هو "الظرف" اللي جواه كل المعلومات اللي جاية من المستخدم.

---

## 🧪 مثال من الحياة

تخيل إن عندك استمارة ورقية.

المستخدم كتب فيها:

- الاسم: أحمد
- الإيميل: ahmed@test.com
- الباسورد: 123456

بعدها سلّمها للموظف.

في Laravel:

- الاستمارة = الفورم
- الموظف = Controller
- الورقة اللي وصلت = Request

---

## 📥 إزاي أستقبل الـ Request في Controller؟

عن طريق Dependency Injection.

يعني Laravel يجيبلك الـ Request جاهزة.

### مثال:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        return 'تم استلام الطلب';
    }
}
```

### شرح مهم جدًا

#### `use Illuminate\Http\Request;`

دي الكلاس اللي Laravel بيوفره لك للتعامل مع الطلب الحالي.

#### `store(Request $request)`

هنا Laravel تلقائيًا يحقن الـ Request في الميثود.

يعني أول ما الطلب يوصل للـ `store`:

- Laravel يجهز الـ request
- ويحطه في المتغير `$request`

---

## 🧾 مثال كامل من Route إلى Controller

### في `routes/web.php`

```php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/products', [ProductController::class, 'store'])->name('products.store');
```

### في الـ Controller

```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    return 'الطلب وصل';
}
```

### يعني لما المستخدم يبعت فورم على:

```text
POST /products
```

الميثود `store()` هيشتغل ويستقبل الطلب.

---

## 🧲 إزاي أجيب البيانات من الـ Request؟

### أشهر طريقة:

```php
$name = $request->input('name');
```

يعني:

> هات قيمة الحقل اللي اسمه `name`

---

## 🧪 مثال عملي

لو الفورم فيها:

```blade
<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <input type="text" name="name">
    <input type="number" name="price">
    <button type="submit">حفظ</button>
</form>
```

في الـ Controller:

```php
public function store(Request $request)
{
    $name = $request->input('name');
    $price = $request->input('price');

    return "اسم المنتج: $name - السعر: $price";
}
```

---

## 🔤 طرق مختلفة لجلب البيانات

### 1. `input()`

```php
$name = $request->input('name');
```

---

### 2. Property مباشرة

```php
$name = $request->name;
```

شغالة، لكن للمبتدئ:

> الأفضل استخدم `input()` لأنها أوضح

---

### 3. قيمة افتراضية لو الحقل مش موجود

```php
$name = $request->input('name', 'بدون اسم');
```

يعني لو `name` مش موجود:

النتيجة هتبقى:

```text
بدون اسم
```

---

### 4. جلب كل البيانات مرة واحدة

```php
$allData = $request->all();
```

دي بترجع كل المدخلات.

لكن:

> متستخدمهاش بعشوائية في الحفظ، خصوصًا قبل الـ validation

---

### 5. جلب جزء محدد فقط

```php
$data = $request->only(['name', 'price']);
```

أو:

```php
$data = $request->except(['_token']);
```

---

## 🧭 أعرف منين نوع الطلب؟

```php
$method = $request->method();
```

أو:

```php
if ($request->isMethod('post')) {
    // الطلب من نوع POST
}
```

---

## 🌐 أعرف الرابط الحالي؟

```php
$path = $request->path();
$url = $request->url();
$fullUrl = $request->fullUrl();
```

### الفرق:

- `path()` → الجزء الداخلي فقط مثل `products/5`
- `url()` → الرابط بدون query string
- `fullUrl()` → الرابط كامل ومعاه query string

---

## 📍 أجيب قيمة Route Parameter من الـ Request

لو عندك route كده:

```php
Route::get('/products/{id}', [ProductController::class, 'show']);
```

ففي الـ Controller:

```php
public function show(Request $request, $id)
{
    $routeId = $request->route('id');

    return "ID من الرابط: $routeId";
}
```

وأبسط كمان إنك تستقبله مباشرة:

```php
public function show(Request $request, $id)
{
    return "المنتج رقم $id";
}
```

---

## 📎 التعامل مع الـ Query String

مثال:

```text
/products?search=laptop&category=tech
```

في الـ Controller:

```php
$search = $request->query('search');
$category = $request->query('category');
```

أو:

```php
$search = $request->input('search');
```

لأن `input()` تقدر تجيب من الفورم أو query string.

---

## 📂 التعامل مع الملفات المرفوعة

لو عندك input file:

```blade
<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image">
    <button type="submit">رفع</button>
</form>
```

في الـ Controller:

```php
public function store(Request $request)
{
    $file = $request->file('image');

    return 'تم استلام الملف';
}
```

### أتأكد هل المستخدم رفع ملف أصلًا؟

```php
if ($request->hasFile('image')) {
    return 'في ملف';
}
```

---

## 💾 تخزين الملف

```php
$path = $request->file('image')->store('products');
```

يعني:

- خزن الملف
- داخل فولدر `products`
- وارجع المسار

ولو عايز على `public` disk:

```php
$path = $request->file('image')->store('products', 'public');
```

---

# الجزء الثاني: Validation

## 🤔 يعني إيه Validation؟

الـ Validation معناها:

> التأكد إن البيانات اللي المستخدم بعتها صحيحة قبل ما نكمل

يعني مثلًا:

- الاسم مطلوب
- الإيميل لازم يكون إيميل صحيح
- السعر لازم يكون رقم
- الصورة لازم تكون صورة فعلًا
- الباسورد لازم يبقى 8 حروف على الأقل

---

## 😨 ليه Validation مهمة جدًا؟

من غير Validation، المستخدم ممكن يبعت:

- اسم فاضي
- إيميل غلط
- سعر مكتوب فيه حروف
- ملف مش صورة
- بيانات ناقصة

وده ممكن يسبب:

- أخطاء في قاعدة البيانات
- بيانات مضروبة
- مشاكل في السيستم

فالـ validation مش رفاهية.

> دي حماية أساسية جدًا للتطبيق.

---

## ✅ أسهل طريقة للـ Validation

Laravel موفر طريقة سهلة جدًا:

```php
$validated = $request->validate([
    'name' => 'required',
    'price' => 'required|numeric',
]);
```

### معنى الكلام ده:

- `name` لازم يكون موجود
- `price` لازم يكون موجود ويكون رقم

---

## 🧠 ماذا يحدث لو الـ Validation فشلت؟

هنا Laravel بيكون ذكي جدًا.

لو validation فشلت في web request عادي:

- يرجع المستخدم للصفحة السابقة تلقائيًا
- يرسل الأخطاء في session
- يرجع البيانات القديمة عشان الفورم تتعمر تاني

يعني أنت مش محتاج تكتب كل ده يدوي.

---

## 🧪 مثال كامل جدًا

### الفورم

```blade
<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <div>
        <label>اسم المنتج</label>
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>السعر</label>
        <input type="text" name="price" value="{{ old('price') }}">
        @error('price')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">حفظ</button>
</form>
```

### الـ Controller

```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|min:3|max:100',
        'price' => 'required|numeric|min:1',
    ]);

    return 'البيانات سليمة';
}
```

---

## 🔍 شرح قواعد Validation المشهورة

### `required`

الحقل لازم يكون موجود ومش فاضي.

```php
'name' => 'required'
```

---

### `string`

لازم القيمة تكون نص.

```php
'name' => 'required|string'
```

---

### `numeric`

لازم تكون رقم.

```php
'price' => 'required|numeric'
```

---

### `integer`

لازم تكون عدد صحيح.

```php
'quantity' => 'required|integer'
```

---

### `email`

لازم تكون بصيغة إيميل.

```php
'email' => 'required|email'
```

---

### `min`

أقل قيمة أو أقل عدد حروف.

```php
'name' => 'required|min:3'
```

---

### `max`

أقصى قيمة أو أقصى عدد حروف.

```php
'name' => 'required|max:100'
```

---

### `unique`

لازم القيمة ما تكونش مكررة في جدول.

```php
'email' => 'required|email|unique:users'
```

يعني:

- روح جدول `users`
- شوف هل الإيميل موجود قبل كده ولا لا

---

### `nullable`

يعني الحقل اختياري.

```php
'description' => 'nullable|string'
```

لو المستخدم ما كتبش حاجة، عادي.

---

### `confirmed`

مشهورة جدًا في الباسورد.

```php
'password' => 'required|min:8|confirmed'
```

معناها لازم يكون عندك input تاني اسمه:

```text
password_confirmation
```

---

### `image`

لازم الملف يكون صورة.

```php
'image' => 'nullable|image'
```

---

### `mimes`

تحدد أنواع الملفات المسموحة.

```php
'file' => 'required|mimes:pdf,doc,docx'
```

---

## 🧩 القواعد كسلسلة أو كمصفوفة

### كسلسلة:

```php
'name' => 'required|string|max:255'
```

### كمصفوفة:

```php
'name' => ['required', 'string', 'max:255']
```

الطريقتين صح.

لما الدنيا تكبر، المصفوفة أريح.

---

## 🪤 عرض الأخطاء في Blade

### الطريقة الأولى: `@error`

```blade
@error('name')
    <div>{{ $message }}</div>
@enderror
```

### الطريقة الثانية: عرض كل الأخطاء

```blade
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
```

---

## 🔁 إرجاع القيم القديمة في الفورم

دي من أجمل الحاجات في Laravel.

لو الـ validation فشلت، المستخدم ما يضطرش يكتب كل حاجة من أول وجديد.

تستخدم:

```blade
value="{{ old('name') }}"
```

مثال:

```blade
<input type="text" name="name" value="{{ old('name') }}">
```

---

## 🧱 Validation للملفات

مثال كامل:

```php
$validated = $request->validate([
    'name' => 'required|string|max:100',
    'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
]);
```

### شرح `max:2048`

في الملفات، معناها:

> أقصى حجم 2048 كيلوبايت تقريبًا = 2MB

---

## 🧠 Validation في update

هنا المبتدئين بيتلخبطوا في `unique`.

مثال:

أنت بتعدل مستخدم موجود.

لو كتبت:

```php
'email' => 'required|email|unique:users'
```

هيقولك الإيميل مكرر، حتى لو هو نفس إيميل المستخدم الحالي.

لازم تستثني السجل الحالي.

مثال مبسط:

```php
'email' => 'required|email|unique:users,email,' . $id
```

ودي من التفاصيل المهمة جدًا.

---

## 🏗️ Form Request Validation

لما المشروع يكبر، مش حلو نخلي الـ validation كلها جوه الـ Controller.

Laravel بيوفر حاجة اسمها **Form Request**.

دي كلاس مخصوص للـ validation.

### بنعملها إزاي؟

```bash
php artisan make:request StoreProductRequest
```

هيتعمل ملف مثلًا هنا:

```text
app/Http/Requests/StoreProductRequest.php
```

---

## 🧪 مثال على Form Request

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ];
    }
}
```

### وبعدين في الـ Controller:

```php
use App\Http\Requests\StoreProductRequest;

public function store(StoreProductRequest $request)
{
    $validated = $request->validated();

    return 'البيانات سليمة';
}
```

### فايدة Form Request:

- الـ Controller يبقى أنضف
- الـ validation تبقى منظمة
- أسهل في الصيانة
- أحسن في المشاريع الكبيرة

---

## 🔐 `authorize()` يعني إيه؟

في Form Request هتلاقي:

```php
public function authorize(): bool
{
    return true;
}
```

دي بتحدد:

> هل المستخدم أصلًا مسموح له يعمل الطلب ده؟

لو رجعتها `false`:

Laravel هيرفض الطلب.

في البداية غالبًا خليك:

```php
return true;
```

ولما تدخل في الصلاحيات تستخدمها بشكل أذكى.

---

## 📦 البيانات الـ validated الجاهزة

بعد نجاح Form Request:

```php
$validated = $request->validated();
```

ودي أحسن من إنك تستخدم:

```php
$request->all()
```

لأنها بترجع فقط البيانات التي نجحت في الـ validation.

---

# الجزء الثالث: Response

## 🤔 يعني إيه Response؟

الـ Response هو:

> الحاجة اللي السيرفر بيرجعها للمستخدم بعد ما يخلص

يعني بعد ما Laravel استقبل الطلب وعمل الشغل المطلوب، لازم يرد.

الرد ده ممكن يكون:

- نص
- View
- Redirect
- JSON
- ملف للتحميل

---

## 🧪 أبسط Response

```php
return 'Hello World';
```

Laravel هيحولها تلقائيًا لـ HTTP response.

---

## 🖼️ Response من نوع View

```php
return view('products.index');
```

ولو عايز تبعت بيانات:

```php
return view('products.index', [
    'products' => $products,
]);
```

أو:

```php
return view('products.index', compact('products'));
```

---

## 🔁 Redirect Response

ده مهم جدًا بعد الحفظ والتعديل والحذف.

مثال:

```php
return redirect('/products');
```

يعني:

- بعد ما أخلص
- ابعت المستخدم لصفحة المنتجات

---

## 🏷️ Redirect إلى Named Route

وده الأفضل:

```php
return redirect()->route('products.index');
```

أو:

```php
return to_route('products.index');
```

ودي طريقة جميلة جدًا ومختصرة.

---

## ✅ Redirect مع رسالة نجاح

مثال مشهور جدًا:

```php
return redirect()
    ->route('products.index')
    ->with('success', 'تم إضافة المنتج بنجاح');
```

### دي راحت فين؟

بتتخزن مؤقتًا في session.

وفي Blade:

```blade
@if (session('success'))
    <div style="color: green">
        {{ session('success') }}
    </div>
@endif
```

---

## ⛔ Redirect مع errors أو old input

في حالات validation Laravel بيعمل ده تلقائي.

لكن لو إنت عايز بنفسك:

```php
return back()->withInput();
```

يعني:

- ارجع للصفحة اللي قبلها
- ومعاك البيانات القديمة

---

## 📦 JSON Response

مهم جدًا في الـ APIs.

```php
return response()->json([
    'status' => true,
    'message' => 'تم الحفظ بنجاح',
]);
```

ولو عايز status code:

```php
return response()->json([
    'message' => 'تم الإنشاء',
], 201);
```

---

## 📚 Laravel ممكن يحول Array إلى JSON تلقائي

```php
return [
    'name' => 'Laptop',
    'price' => 15000,
];
```

Laravel هيعتبر ده JSON response.

لكن الأفضل غالبًا في الشغل المنظم:

```php
return response()->json([...]);
```

عشان تبقى واضح.

---

## 📥 Download Response

لو عايز المستخدم يحمل ملف:

```php
return response()->download(storage_path('app/reports/report.pdf'));
```

---

## 👀 File Response

لو عايز تعرض الملف بدل ما يتحمل:

```php
return response()->file(storage_path('app/reports/report.pdf'));
```

---

## 🧱 Response مع Header

```php
return response('تم بنجاح', 200)
    ->header('Content-Type', 'text/plain');
```

دي أقل استخدامًا للمبتدئ في web apps العادية، لكنها مهمة تعرفها.

---

# الجزء الرابع: المثال العملي الكامل

## 🎯 سيناريو: إضافة منتج

عايزين نطبق الثلاثة مع بعض:

- Request
- Validation
- Response

---

## 1. الـ Route

```php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
```

---

## 2. الـ View

```blade
<h1>إضافة منتج</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li style="color:red">{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <div>
        <label>اسم المنتج</label>
        <input type="text" name="name" value="{{ old('name') }}">
    </div>

    <div>
        <label>السعر</label>
        <input type="text" name="price" value="{{ old('price') }}">
    </div>

    <div>
        <label>الوصف</label>
        <textarea name="description">{{ old('description') }}</textarea>
    </div>

    <button type="submit">حفظ</button>
</form>
```

---

## 3. الـ Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:1000',
        ]);

        // هنا مكان الحفظ في قاعدة البيانات
        // Product::create($validated);

        return redirect()
            ->route('products.create')
            ->with('success', 'تم حفظ المنتج بنجاح');
    }
}
```

---

## 4. ماذا يحدث لو البيانات غلط؟

مثال:

- الاسم فاضي
- السعر مش رقم

Laravel هيعمل تلقائي:

1. يرجع المستخدم لنفس الصفحة
2. ينقل الأخطاء إلى `$errors`
3. يرجع القيم السابقة إلى `old()`

وده من الأسباب اللي بتخلي Laravel مريح جدًا في التعامل مع الفورمز.

---

## 5. ماذا يحدث لو البيانات صحيحة؟

Laravel هيكمل عادي:

- ياخد `$validated`
- تحفظها في الداتابيز
- ترجع redirect أو view أو json

---

# الجزء الخامس: API mindset

## لو أنا بعمل API مش صفحات Blade؟

هنا الفرق المهم:

في web app التقليدي:

- validation fail → redirect back

في API / Ajax:

- validation fail → JSON errors
- status code = `422`

مثال response:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": [
      "The name field is required."
    ]
  }
}
```

يعني نفس `validate()` تشتغل، لكن الاستجابة تختلف حسب نوع الطلب.

---

# الجزء السادس: أخطاء شائعة جدًا

## الخطأ 1: صفحة بيضاء أو خطأ 419

غالبًا نسيت:

```blade
@csrf
```

في الفورم.

---

## الخطأ 2: Validation لا تعمل كما تتوقع

غالبًا السبب:

- اسم الـ input في الفورم مختلف
- اسم الحقل في الـ validation مختلف

مثال:

الفورم:

```blade
<input name="product_name">
```

لكن الـ validation:

```php
'name' => 'required'
```

هنا Laravel بيدور على `name` ومش هيلاقيها.

---

## الخطأ 3: البيانات لا ترجع في `old()`

غالبًا:

- الطلب ليس داخل `web` middleware
- أو أنت لم ترجع back بشكل صحيح

---

## الخطأ 4: الملف لا يصل

غالبًا نسيت في الفورم:

```blade
enctype="multipart/form-data"
```

---

## الخطأ 5: استخدمت `$request->all()` وحفظت كل شيء

وده ممكن يدخل:

- `_token`
- `_method`
- بيانات ما كنتش تقصدها

فالأفضل:

- `validated()`
- أو `only([...])`

---

# الجزء السابع: أفضل الممارسات

## 1. لا تحفظ قبل الـ validation

غلط:

```php
Product::create($request->all());
```

وبعدين تفكر تعمل validation.

الصح:

```php
$validated = $request->validate([...]);
Product::create($validated);
```

---

## 2. استخدم Form Request لما الكود يكبر

لو عندك create وupdate وكل واحدة فيها rules كثيرة:

انقلها لـ Form Request.

---

## 3. استخدم رسائل نجاح واضحة

مثلًا:

```php
->with('success', 'تم حفظ المنتج بنجاح')
```

عشان المستخدم يفهم اللي حصل.

---

## 4. في الـ API كن واضحًا في الـ JSON

مثال:

```php
return response()->json([
    'status' => true,
    'data' => $product,
    'message' => 'تم الحفظ بنجاح',
], 201);
```

---

## 5. لا تعتمد على input names عشوائيًا

خلي أسماء الحقول:

- ثابتة
- مفهومة
- متطابقة مع الـ validation

---

# الجزء الثامن: تلخيص سريع جدًا

## Request

هو كل ما أرسله المستخدم.

أشهر ما نستخدمه:

- `$request->input('name')`
- `$request->all()`
- `$request->only([...])`
- `$request->file('image')`
- `$request->hasFile('image')`

---

## Validation

هي التأكد أن البيانات صحيحة قبل التنفيذ.

أشهر ما نستخدمه:

```php
$request->validate([
    'name' => 'required|string',
    'price' => 'required|numeric',
]);
```

---

## Response

هو الرد الذي يرجع للمستخدم.

أشهر الأنواع:

- `return view(...)`
- `return redirect(...)`
- `return to_route(...)`
- `return response()->json(...)`
- `return response()->download(...)`

---

## 🧠 الجملة الذهبية

لو عايز تحفظ الدرس كله في سطر واحد:

> الـ Route يوصلك للـ Controller، والـ Controller يمسك الـ Request، ويعمل Validation للبيانات، ثم يرجع Response مناسب.

---

## 🚀 الدرس اللي بعده منطقيًا

بعد ما فهمت:

- Routing
- Request
- Validation
- Response

فالخطوة الطبيعية التالية هي:

### `Views + Blade`

لأنك دلوقتي عرفت:

- إزاي تستقبل الطلب
- وإزاي تتعامل مع الفورم

وفاضل تتقن بناء الصفحات نفسها بشكل منظم.

</div>
