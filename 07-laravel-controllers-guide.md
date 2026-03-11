<div dir="rtl">

# شرح Controllers في Laravel

### دليل عملي مفصل للمبتدئ مع Clean Code و Best Practices

---

## مقدمة

بعد ما فهمت:

- الـ Route
- الـ Request
- الـ Validation
- الـ Response
- الـ Views و Blade

فأنت محتاج الآن تفهم الجزء الذي يربط كل ده ببعض:

## الـ Controller

الـ Controller هو المكان الذي يستقبل الطلب من الـ Route، ينسق الشغل، ويتأكد أن الرد النهائي يطلع بشكل صحيح.

بصيغة أبسط:

> الـ Route يحدد "فين نروح"، والـ Controller يحدد "نعمل إيه".

---

## يعني إيه Controller؟

تخيل إن عندك مطعم:

- العميل = المستخدم
- المنيو = الـ Routes
- الويتر = الـ Controller
- المطبخ = الـ Models / Services / Database
- الطبق النهائي = الـ Response

العميل لا يدخل المطبخ مباشرة.
هو يكلم الويتر.
والويتر هو الذي:

- يستقبل الطلب
- يفهم المطلوب
- يمرر التنفيذ للجهة المناسبة
- يرجع النتيجة

وده بالضبط دور الـ Controller.

---

## الوظيفة الحقيقية للـ Controller

الـ Controller ليس مكانًا لكتابة كل شيء.

وظيفته الأساسية:

1. يستقبل الطلب.
2. يستدعي الـ validation المناسبة.
3. يستدعي الـ model أو service أو action المناسبة.
4. يحدد شكل الـ response.

يعني:

> الـ Controller ينسق ولا يتورط.

وده أول مبدأ مهم جدًا في `clean code`.

---

## الـ Controller في دورة الطلب

خلينا نمشي على السيناريو الطبيعي:

```text
المستخدم يفتح /products
↓
Laravel يلاقي route مناسبة
↓
route تنادي ProductController@index
↓
controller يجيب البيانات
↓
controller يرسلها إلى view
↓
المستخدم يشوف الصفحة
```

أو في حالة الحفظ:

```text
المستخدم يرسل form
↓
route تنادي ProductController@store
↓
controller يعمل validation
↓
controller يحفظ البيانات
↓
controller يعمل redirect برسالة نجاح
```

---

## إنشاء Controller

### Controller عادي

```bash
php artisan make:controller ProductController
```

ده يعمل ملف هنا:

```text
app/Http/Controllers/ProductController.php
```

وشكله الأساسي يكون قريب من:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
}
```

---

## Resource Controller

لو عندك CRUD، الأفضل غالبًا تستخدم:

```bash
php artisan make:controller ProductController --resource
```

Laravel يجهز لك الدوال الأساسية:

```php
public function index() {}
public function create() {}
public function store(Request $request) {}
public function show(string $id) {}
public function edit(string $id) {}
public function update(Request $request, string $id) {}
public function destroy(string $id) {}
```

ودي ليست مجرد أسماء.
دي تمثل دورة CRUD الكاملة.

---

## API Controller

لو أنت تبني API فقط:

```bash
php artisan make:controller Api/ProductController --api
```

هنا Laravel لا يضع:

- `create()`
- `edit()`

لأن الـ API لا تعرض صفحات form غالبًا.

---

## Single Action Controller

لو عندك مهمة واحدة فقط:

```bash
php artisan make:controller PublishPostController --invokable
```

وساعتها يكون عندك method واحدة فقط:

```php
public function __invoke()
{
    //
}
```

وده ممتاز للعمليات الواضحة جدًا مثل:

- publish
- resend email
- approve request
- export report

---

## أين نستخدم Controller وأين لا؟

استخدم Controller لما:

- عندك route تحتاج منطق واضح
- تريد تنظيم التطبيق
- عندك CRUD
- تحتاج validation + response + binding

لا تضع المنطق في:

- route closures الكثيرة
- views
- model بشكل عشوائي

المشروع الصغير قد يحتمل closures.
لكن المشروع النظيف الحقيقي يحتاج Controllers واضحة.

---

# الجزء الأول: الدوال الأساسية في Resource Controller

## 1. `index()`

### وظيفتها

عرض قائمة من السجلات.

### مثال

```php
use App\Models\Product;

public function index()
{
    $products = Product::latest()->paginate(10);

    return view('products.index', compact('products'));
}
```

### معناها

- هات المنتجات
- رتبها من الأحدث
- قسمها صفحات
- اعرضها في view

### route المتوقعة

```text
GET /products
```

---

## 2. `create()`

### وظيفتها

عرض صفحة إنشاء سجل جديد.

### مثال

```php
use App\Models\Category;

public function create()
{
    $categories = Category::orderBy('name')->get();

    return view('products.create', compact('categories'));
}
```

### ملاحظات

الدالة دي لا تحفظ شيئًا.
هي فقط تعرض form.

### route المتوقعة

```text
GET /products/create
```

---

## 3. `store()`

### وظيفتها

استقبال البيانات القادمة من الفورم وحفظها.

### مثال بسيط

```php
use App\Models\Product;
use Illuminate\Http\Request;

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'price' => ['required', 'numeric', 'min:0'],
    ]);

    Product::create($validated);

    return redirect()
        ->route('products.index')
        ->with('success', 'تمت إضافة المنتج بنجاح');
}
```

### route المتوقعة

```text
POST /products
```

---

## 4. `show()`

### وظيفتها

عرض سجل واحد فقط.

### مثال تقليدي

```php
use App\Models\Product;

public function show(string $id)
{
    $product = Product::findOrFail($id);

    return view('products.show', compact('product'));
}
```

### الأفضل

استخدم Route Model Binding:

```php
use App\Models\Product;

public function show(Product $product)
{
    return view('products.show', compact('product'));
}
```

### route المتوقعة

```text
GET /products/{product}
```

---

## 5. `edit()`

### وظيفتها

عرض صفحة تعديل سجل موجود.

### مثال

```php
use App\Models\Category;
use App\Models\Product;

public function edit(Product $product)
{
    $categories = Category::orderBy('name')->get();

    return view('products.edit', compact('product', 'categories'));
}
```

### route المتوقعة

```text
GET /products/{product}/edit
```

---

## 6. `update()`

### وظيفتها

استقبال البيانات المعدلة وتحديث السجل.

### مثال

```php
use App\Models\Product;
use Illuminate\Http\Request;

public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'price' => ['required', 'numeric', 'min:0'],
    ]);

    $product->update($validated);

    return redirect()
        ->route('products.index')
        ->with('success', 'تم تعديل المنتج بنجاح');
}
```

### route المتوقعة

```text
PUT/PATCH /products/{product}
```

---

## 7. `destroy()`

### وظيفتها

حذف السجل.

### مثال

```php
use App\Models\Product;

public function destroy(Product $product)
{
    $product->delete();

    return redirect()
        ->route('products.index')
        ->with('success', 'تم حذف المنتج بنجاح');
}
```

### route المتوقعة

```text
DELETE /products/{product}
```

---

# الجزء الثاني: ربط الـ Controller بالـ Routes

## Resource Route

أفضل طريقة غالبًا في CRUD:

```php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class);
```

السطر ده يعمل لك 7 routes دفعة واحدة.

---

## كيف أعرف ما الذي تم إنشاؤه؟

```bash
php artisan route:list
```

وغالبًا ستجد شيئًا مثل:

```text
GET|HEAD    products ............ products.index
GET|HEAD    products/create ..... products.create
POST        products ............ products.store
GET|HEAD    products/{product} .. products.show
GET|HEAD    products/{product}/edit products.edit
PUT|PATCH   products/{product} .. products.update
DELETE      products/{product} .. products.destroy
```

---

## only و except

لو لا تريد كل الدوال:

```php
Route::resource('products', ProductController::class)
    ->only(['index', 'show']);
```

أو:

```php
Route::resource('products', ProductController::class)
    ->except(['destroy']);
```

---

## Route منفردة

لو عندك شيء خاص:

```php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
```

وده عادي جدًا.
ليس شرطًا أن تستخدم `resource` دائمًا.

---

# الجزء الثالث: مثال عملي نظيف

خلينا نكتب مثالًا محترمًا لنظام منتجات.

## Controller مرتب

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'تمت إضافة المنتج بنجاح');
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'تم تعديل المنتج بنجاح');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}
```

---

## لماذا هذا المثال نظيف؟

لأنه:

- استخدم `Form Request` بدل validation داخل كل method
- استخدم `Route Model Binding`
- أرجع types واضحة مثل `View` و `RedirectResponse`
- استخدم query builder chain بشكل منظم
- لم يحشر منطقًا معقدًا داخل الـ controller

وده هو الاتجاه الصحيح في `clean code`.

---

# الجزء الرابع: Clean Code داخل Controllers

## القاعدة الذهبية

> الـ Controller يجب أن يكون Thin Controller

يعني:

- منسق
- واضح
- قصير نسبيًا
- لا يحمل business logic كبيرة

---

## ماذا نضع داخل الـ Controller؟

ضع داخله:

- استقبال الطلب
- اختيار الـ validation
- استدعاء service أو model
- تحديد شكل الرد

---

## ماذا لا نضع داخل الـ Controller؟

لا تضع داخله:

- منطق business ضخم
- عمليات معقدة جدًا على الملفات
- حسابات طويلة
- أكثر من مسؤولية
- queries ضخمة مكررة في عدة methods

---

## مثال سيء

```php
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->slug = strtolower(str_replace(' ', '-', $request->name));
    $product->price = $request->price;

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $name = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/products'), $name);
        $product->image = $name;
    }

    $product->save();

    Mail::to('admin@example.com')->send(new ProductCreatedMail($product));
    Log::info('Product created', ['id' => $product->id]);

    return redirect('/products');
}
```

### المشكلة هنا

الميثود دي أصبحت مسؤولة عن:

- validation
- بناء البيانات
- رفع الملف
- الحفظ
- إرسال البريد
- logging
- redirect

وده كثير جدًا على method واحدة.

---

## مثال أفضل

```php
public function store(StoreProductRequest $request, ProductService $productService): RedirectResponse
{
    $productService->create($request->validated());

    return redirect()
        ->route('products.index')
        ->with('success', 'تمت إضافة المنتج بنجاح');
}
```

### لماذا هذا أفضل؟

لأن:

- الـ validation خرجت إلى `StoreProductRequest`
- منطق الإنشاء خرج إلى `ProductService`
- الـ controller بقي مسؤولًا فقط عن التنسيق

---

# الجزء الخامس: Best Practices مهمة جدًا

## 1. استخدم Form Requests

بدل:

```php
public function store(Request $request)
{
    $request->validate([...]);
}
```

الأفضل:

```php
public function store(StoreProductRequest $request)
{
    $data = $request->validated();
}
```

### الفائدة

- الكود أنظف
- الـ rules معزولة
- أسهل في الصيانة
- أفضل في الاختبار

---

## 2. استخدم Route Model Binding

بدل:

```php
public function show(string $id)
{
    $product = Product::findOrFail($id);
}
```

الأفضل:

```php
public function show(Product $product)
{
    //
}
```

### الفائدة

- أقل كود
- أوضح
- أقل تكرار
- أخطاء أقل

---

## 3. سمِّ methods بشكل طبيعي

داخل resource controller استخدم الأسماء القياسية:

- `index`
- `create`
- `store`
- `show`
- `edit`
- `update`
- `destroy`

ولو method خاصة:

- سمِّها على حسب الفعل بوضوح
- مثال: `publish`, `archive`, `approve`

---

## 4. لا تكرر queries إن أمكن

لو نفس الاستعلام يتكرر كثيرًا:

- انقله إلى scope
- أو service
- أو repository لو المشروع يستخدم هذا النمط

---

## 5. لا تخلط Web Responses مع API Responses

Controller الويب:

```php
return view(...);
return redirect(...);
```

Controller الـ API:

```php
return response()->json(...);
```

لا تخلط الاثنين في controller واحد إلا لو عندك سبب قوي.

---

## 6. استخدم type hints و return types

مثال:

```php
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

public function index(): View
{
    //
}

public function store(StoreProductRequest $request): RedirectResponse
{
    //
}
```

### الفائدة

- أوضح للقارئ
- أفضل للأدوات
- يقلل الالتباس

---

## 7. اعمل eager loading عند الحاجة

بدل:

```php
$products = Product::all();
```

لما تحتاج العلاقات:

```php
$products = Product::with('category')->get();
```

عشان تتجنب مشكلة `N+1`.

---

## 8. استخدم pagination في القوائم

بدل:

```php
$products = Product::all();
```

الأفضل غالبًا:

```php
$products = Product::latest()->paginate(10);
```

خصوصًا في الصفحات العامة ولوحة التحكم.

---

## 9. لا تضع authorization في الـ view فقط

التحكم الحقيقي يجب أن يكون داخل:

- controller
- policy
- middleware

مثال:

```php
public function update(UpdateProductRequest $request, Product $product): RedirectResponse
{
    $this->authorize('update', $product);

    $product->update($request->validated());

    return redirect()->route('products.index');
}
```

---

## 10. استخدم services عندما يكبر المنطق

لو عملية الإنشاء أو التحديث تتضمن:

- رفع ملفات
- إرسال إيميلات
- events
- inventory logic
- payment logic

فغالبًا الأفضل إخراجها إلى Service.

---

# الجزء السادس: Form Requests

## لماذا مهمة؟

لأن validation المكتوبة مباشرة داخل controller تكبر بسرعة.

### إنشاء Form Request

```bash
php artisan make:request StoreProductRequest
php artisan make:request UpdateProductRequest
```

### مثال

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
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
```

### داخل controller

```php
public function store(StoreProductRequest $request): RedirectResponse
{
    Product::create($request->validated());

    return redirect()
        ->route('products.index')
        ->with('success', 'تمت إضافة المنتج بنجاح');
}
```

---

# الجزء السابع: Route Model Binding

## المثال التقليدي

```php
public function edit(string $id): View
{
    $product = Product::findOrFail($id);

    return view('products.edit', compact('product'));
}
```

## المثال الأفضل

```php
public function edit(Product $product): View
{
    return view('products.edit', compact('product'));
}
```

### لماذا الأفضل؟

- أوضح
- أقصر
- Laravel تعمل `404` تلقائيًا لو السجل غير موجود

---

# الجزء الثامن: Responses داخل Controllers

## 1. View Response

```php
return view('products.index', compact('products'));
```

---

## 2. Redirect Response

```php
return redirect()->route('products.index');
```

مع رسالة:

```php
return redirect()
    ->route('products.index')
    ->with('success', 'تمت العملية بنجاح');
```

---

## 3. JSON Response

```php
return response()->json([
    'status' => true,
    'data' => $products,
]);
```

---

## 4. Download / File Response

```php
return response()->download($path);
```

أو:

```php
return response()->file($path);
```

---

# الجزء التاسع: API Controller بشكل نظيف

مثال مبسط:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->latest()
            ->paginate(15);

        return response()->json([
            'status' => true,
            'data' => $products,
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'تمت إضافة المنتج بنجاح',
            'data' => $product,
        ], 201);
    }
}
```

---

# الجزء العاشر: تنظيم الملفات

## Controller عادي

```text
app/Http/Controllers/ProductController.php
```

## داخل مجلد فرعي

```text
app/Http/Controllers/Admin/ProductController.php
app/Http/Controllers/Api/ProductController.php
```

### إنشاء Controller داخل مجلد

```bash
php artisan make:controller Admin/ProductController --resource
```

---

## تنظيم مقترح

```text
app/Http/Controllers/
├── Admin/
├── Api/
├── Auth/
└── ProductController.php
```

لو المشروع بسيط لا تعقد التنظيم.
لكن لو فيه لوحات متعددة أو API، التنظيم بالمجلدات مهم.

---

# الجزء الحادي عشر: أخطاء شائعة

## 1. `Target class does not exist`

السبب غالبًا:

- نسيت import للـ controller في routes
- namespace غلط
- اسم الملف أو الكلاس غير مطابق

### الحل

```php
use App\Http\Controllers\ProductController;
```

---

## 2. `Too few arguments`

السبب:

- method تتوقع parameter
- والـ route لا ترسلها

مثال غلط:

```php
public function show(Product $product, string $status)
{
    //
}
```

بدون route توفر `status`.

---

## 3. `Call to undefined method`

السبب:

- route تشير إلى method غير موجودة

مثال:

```php
Route::get('/products', [ProductController::class, 'listing']);
```

بينما الكلاس فيه `index` فقط.

---

## 4. Controller متضخم جدًا

أشهر مشكلة في المشاريع المتوسطة.

### الحل

- Form Requests
- Services
- Policies
- Actions

---

# الجزء الثاني عشر: متى أستخدم Service؟

لو method الـ controller فيها:

- أكثر من 20-30 سطر منطق فعلي
- دمج أكثر من model
- شغل ملفات
- إرسال notifications
- شغل inventory أو payment

فغالبًا هذا ليس مكانها.

مثال:

```php
public function store(StoreOrderRequest $request, OrderService $orderService): RedirectResponse
{
    $order = $orderService->create($request->validated(), $request->user());

    return redirect()
        ->route('orders.show', $order)
        ->with('success', 'تم إنشاء الطلب بنجاح');
}
```

---

# الجزء الثالث عشر: مثال View Controller و API Controller

## Web Controller

يركز على:

- views
- redirects
- flash messages

## API Controller

يركز على:

- JSON
- status codes
- resources
- pagination payloads

لا تكتب API controller وكأنه web controller.

---

# الجزء الرابع عشر: ملخص عملي

## Controller جيد يعني:

- واضح
- قصير نسبيًا
- مسؤول عن التنسيق فقط
- لا يحمل business logic ضخمة
- يعتمد على Form Requests
- يستخدم Route Model Binding
- يرجع response مناسبة

---

## الدوال الأساسية

```php
index()   // list
create()  // show create form
store()   // save new record
show()    // show one record
edit()    // show edit form
update()  // save changes
destroy() // delete
```

---

## الجملة الذهبية

لو عايز تحفظ الدرس كله في سطر واحد:

> الـ Controller هو منسق الطلب داخل Laravel: يستقبل الـ request، يستدعي المنطق المناسب، ثم يرجع response صحيحة، من غير ما يتحول إلى مكان لكل شيء في التطبيق.

---

## الخطوة التالية

بعد ما تفهم Controllers بشكل صحيح، الدرس الطبيعي التالي هو:

## `08-laravel-migration-deep-guide.md`

لأنك بعد ما فهمت طبقة HTTP وMVC، لازم تدخل الآن على بناء قاعدة البيانات بشكل صحيح ومنظم.

</div>
