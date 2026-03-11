<div dir="rtl">

# شرح Controllers في Laravel

### شرح تفصيلي جدًا للمبتدئ مع Clean Code و Best Practices وأسئلة شائعة

---

## قبل ما نبدأ

الدرس ده مهم جدًا لأن ناس كثيرة تحفظ:

- `index`
- `store`
- `update`
- `destroy`

لكن ما تفهمش:

- ليه الـ Controller موجود أصلًا
- ليه ما نكتبش كل شيء في الـ Route
- إيه الفرق بين الـ Controller والـ Model
- إيه الفرق بين الـ Controller والـ View
- إمتى أحط الكود هنا وإمتى أطلعه برا

فالهدف هنا مش مجرد حفظ أسماء methods.

الهدف إنك تطلع فاهم:

> الـ Controller دوره الحقيقي إيه، وإزاي تكتبه صح من أول مرة.

---

## السؤال الأول: يعني إيه Controller أصلًا؟

الـ Controller هو كلاس داخل Laravel وظيفته:

- يستقبل الطلب من الـ Route
- يفهم المطلوب
- ينسق تنفيذ المطلوب
- يرجع response مناسبة

يعني هو "منسق الحركة" بين أجزاء التطبيق.

لو عندك Route مثلًا:

```php
Route::get('/products', [ProductController::class, 'index']);
```

ده معناه:

- لو المستخدم فتح `/products`
- روح شغّل `ProductController`
- ونفذ method اسمها `index`

إذًا:

الـ Route لا تنفذ كل شيء بنفسها.

هي فقط تقول:

> الطلب ده يروح لمين؟

والـ Controller هو الذي يقول:

> طيب لما يوصل لي، هعمل إيه؟

---

## السؤال الثاني: ليه ما نكتبش كل الكود في الـ Route؟

ممكن تكتب كده:

```php
Route::get('/products', function () {
    $products = Product::latest()->get();
    return view('products.index', compact('products'));
});
```

وده شغال.

لكن لما المشروع يكبر ستبدأ المشاكل:

- الـ routes تبقى مليانة منطق
- صعب تدور على الكود
- صعب تعيد استخدام نفس السلوك
- صعب تختبر
- صعب تقسم الشغل

فبدل ما تخلي `routes/web.php` يتحول لمزبلة منطق، بنطلع الشغل إلى Controllers.

يعني:

```php
Route::get('/products', [ProductController::class, 'index']);
```

وفي `ProductController`:

```php
public function index()
{
    $products = Product::latest()->get();

    return view('products.index', compact('products'));
}
```

كده:

- الـ route أصبحت نظيفة
- المنطق أصبح في مكانه الطبيعي

---

## السؤال الثالث: إيه الفرق بين Route و Controller و Model و View؟

دي أهم نقطة في الدرس كله.

### 1. Route

تحدد:

- الطلب ده يروح فين

مثال:

```php
Route::get('/products', [ProductController::class, 'index']);
```

---

### 2. Controller

ينسق:

- هنجيب إيه
- هنحفظ إيه
- هنرجع إيه

مثال:

```php
public function index()
{
    $products = Product::latest()->get();

    return view('products.index', compact('products'));
}
```

---

### 3. Model

يتعامل مع البيانات نفسها.

مثال:

```php
Product::latest()->get();
Product::create($data);
$product->update($data);
$product->delete();
```

---

### 4. View

تعرض البيانات للمستخدم.

مثال:

```blade
@foreach ($products as $product)
    <h2>{{ $product->name }}</h2>
@endforeach
```

---

## الخلاصة الذهبية

- Route تقول: اذهب إلى هناك
- Controller يقول: نفذ هذا السيناريو
- Model تقول: أنا أتعامل مع البيانات
- View تقول: أنا أعرض النتيجة

لو فهمت السطر ده، فهمت العمود الفقري للـ MVC.

---

## السؤال الرابع: الـ Controller موجود فين؟

غالبًا داخل:

```text
app/Http/Controllers
```

مثلًا:

```text
app/Http/Controllers/ProductController.php
app/Http/Controllers/Auth/LoginController.php
app/Http/Controllers/Admin/UserController.php
```

---

## إنشاء Controller

### أبسط شكل

```bash
php artisan make:controller ProductController
```

Laravel يعمل ملف بالشكل التالي تقريبًا:

```php
<?php

namespace App\Http\Controllers;

class ProductController extends Controller
{
    //
}
```

---

## ما معنى `extends Controller`؟

لأن كل controller عندنا غالبًا ترث من كلاس أساسية اسمها:

```php
Controller
```

ودي موجودة عادة هنا:

```text
app/Http/Controllers/Controller.php
```

وده أمر طبيعي في Laravel.

يعني:

> ProductController هي Controller من Controllers المشروع.

---

## السؤال الخامس: إمتى أعمل Controller عادي وإمتى Resource Controller؟

### Controller عادي

لما يكون عندك شغل خاص أو بسيط.

مثال:

- صفحة dashboard
- resend email
- export report
- publish post

---

### Resource Controller

لما يكون عندك CRUD.

يعني:

- list
- create
- store
- show
- edit
- update
- destroy

وهنا الأفضل غالبًا:

```bash
php artisan make:controller ProductController --resource
```

---

## السؤال السادس: يعني إيه CRUD؟

CRUD اختصار:

- Create
- Read
- Update
- Delete

وأغلب resource controllers في Laravel مبنية على الفكرة دي.

---

# الجزء الأول: فهم الدوال الـ 7 واحدة واحدة ببطء

## 1. `index()`

### معناها

اعرض كل السجلات أو قائمة السجلات.

مثال:

```php
use App\Models\Product;
use Illuminate\View\View;

public function index(): View
{
    $products = Product::query()
        ->latest()
        ->paginate(10);

    return view('products.index', compact('products'));
}
```

### ما الذي يحدث هنا؟

1. دخل المستخدم صفحة المنتجات.
2. route نادت `index()`.
3. `index()` جابت المنتجات.
4. `index()` رجعت view.

### السؤال المتوقع

ليه استخدم `paginate()` بدل `get()`؟

لأن:

- `get()` تجيب كل السجلات
- `paginate()` تقسّمهم صفحات

وفي القوائم غالبًا `paginate()` أفضل.

---

## 2. `create()`

### معناها

اعرض صفحة إنشاء سجل جديد.

يعني:

> دي الصفحة التي فيها الفورم

هي لا تحفظ شيئًا.

مثال:

```php
use App\Models\Category;
use Illuminate\View\View;

public function create(): View
{
    $categories = Category::query()
        ->orderBy('name')
        ->get();

    return view('products.create', compact('categories'));
}
```

### السؤال المتوقع

ليه جبت categories هنا؟

لأن صفحة إنشاء المنتج قد تحتوي dropdown للفئات.

إذًا `create()` لا تقتصر على "عرض صفحة فاضية".
بل تجهز كل البيانات التي تحتاجها الصفحة.

---

## 3. `store()`

### معناها

استقبل بيانات الفورم واحفظ السجل الجديد.

مثال بسيط:

```php
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

public function store(Request $request): RedirectResponse
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

### ما الذي يحدث هنا؟

1. المستخدم ملأ الفورم.
2. الفورم عملت `POST`.
3. route نادت `store()`.
4. `store()` عملت validation.
5. لو validation نجحت، تحفظ البيانات.
6. تعمل redirect.

### السؤال المتوقع

ليه ما رجعناش view مباشرة؟

ينفع، لكن في عمليات الحفظ الأفضل غالبًا:

- تحفظ
- ثم redirect

عشان تتجنب مشكلة إعادة إرسال الفورم لو المستخدم عمل refresh.

---

## 4. `show()`

### معناها

اعرض سجل واحد فقط.

مثال:

```php
use App\Models\Product;
use Illuminate\View\View;

public function show(Product $product): View
{
    return view('products.show', compact('product'));
}
```

### السؤال المتوقع

منين جاب Laravel الـ product؟

من حاجة اسمها:

## Route Model Binding

يعني لو route فيها:

```php
Route::get('/products/{product}', [ProductController::class, 'show']);
```

Laravel تشوف `{product}` وتفهم:

> هات الـ Product المطابقة للرقم الموجود في الرابط

ولو لم تجدها:

> ترجع 404 تلقائيًا

---

## 5. `edit()`

### معناها

اعرض صفحة تعديل السجل.

يعني:

- هات السجل الحالي
- هات أي بيانات إضافية تحتاجها الصفحة
- اعرض form التعديل

مثال:

```php
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

public function edit(Product $product): View
{
    $categories = Category::query()->orderBy('name')->get();

    return view('products.edit', compact('product', 'categories'));
}
```

---

## 6. `update()`

### معناها

استقبل بيانات التعديل واحفظ التغييرات.

مثال:

```php
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

public function update(Request $request, Product $product): RedirectResponse
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

### السؤال المتوقع

إيه الفرق بين `store()` و `update()`؟

- `store()` تنشئ سجل جديد
- `update()` تعدل سجل موجود

---

## 7. `destroy()`

### معناها

احذف السجل.

مثال:

```php
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

public function destroy(Product $product): RedirectResponse
{
    $product->delete();

    return redirect()
        ->route('products.index')
        ->with('success', 'تم حذف المنتج بنجاح');
}
```

### السؤال المتوقع

هل الحذف لازم يكون من controller؟

التنسيق نعم.
لكن لو فيه منطق حذف معقد:

- حذف ملفات
- حذف علاقات
- تسجيل audit log

ممكن يخرج إلى service.

---

# الجزء الثاني: ربط الـ Controller بالـ Routes

## أبسط ربط

```php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
```

يعني:

- الرابط `/products`
- ينادي `ProductController`
- method اسمها `index`

---

## Resource Route

لو عندك CRUD كامل:

```php
Route::resource('products', ProductController::class);
```

دي تعمل لك كل الـ routes الأساسية.

---

## كيف أشوفهم؟

```bash
php artisan route:list
```

وهنا من أهم الأسئلة للمبتدئ:

### ليه الأمر ده مهم؟

لأنه يريك:

- route methods
- URI
- names
- actions

فلو route لا تعمل، أول شيء افعله:

```bash
php artisan route:list
```

---

## only و except

لو لا تحتاج كل CRUD:

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

# الجزء الثالث: السيناريو الحقيقي داخل التطبيق

## مثال 1: المستخدم يفتح صفحة المنتجات

### route

```php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
```

### controller

```php
public function index(): View
{
    $products = Product::latest()->paginate(10);

    return view('products.index', compact('products'));
}
```

### view

```blade
@foreach ($products as $product)
    <h2>{{ $product->name }}</h2>
@endforeach
```

### ماذا حدث؟

1. route استقبلت الطلب
2. controller جابت البيانات
3. view عرضت البيانات

---

## مثال 2: المستخدم ينشئ منتجًا جديدًا

### routes

```php
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
```

### controller

```php
public function create(): View
{
    return view('products.create');
}

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'price' => ['required', 'numeric', 'min:0'],
    ]);

    Product::create($validated);

    return redirect()->route('products.index');
}
```

### ماذا حدث؟

1. المستخدم فتح صفحة الإضافة
2. `create()` رجعت الفورم
3. المستخدم ملأ البيانات
4. الفورم أرسلت `POST`
5. `store()` تحققت من البيانات
6. `store()` حفظت المنتج
7. `store()` رجعت redirect

---

# الجزء الرابع: ما الذي يجب أن يوضع داخل Controller؟

## ضع داخل الـ Controller

- استقبال الطلب
- اختيار الـ validation
- استدعاء model أو service
- تحديد response

---

## لا تضع داخل الـ Controller

- business logic معقدة جدًا
- استعلامات متكررة في عشر أماكن
- شغل inventory / payment / billing طويل
- تنسيق HTML
- queries لا علاقة لها بهذا السيناريو

---

## الجملة المهمة جدًا

> الـ Controller ليس مكان "كل الكود".

هو مكان "تنسيق السيناريو".

---

# الجزء الخامس: Clean Code داخل Controllers

## مبدأ Thin Controller

يعني:

- method قصيرة نسبيًا
- الغرض واضح
- لا يوجد تكرار كثير
- لا يوجد منطق متوحش داخلها

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
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/products'), $filename);
        $product->image = $filename;
    }

    $product->save();

    Mail::to('admin@example.com')->send(new ProductCreatedMail($product));
    Log::info('created', ['id' => $product->id]);

    return redirect('/products');
}
```

### لماذا هذا سيء؟

لأن method واحدة صارت:

- تعمل validation
- تبني slug
- ترفع ملف
- تحفظ
- ترسل email
- تعمل log
- تعمل redirect

يعني أكثر من مسؤولية.

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

- validation خرجت إلى request class
- business logic خرجت إلى service
- controller بقي منسقًا فقط

---

# الجزء السادس: Form Requests

## السؤال: ليه أستخدم Form Request؟

لأن validation داخل controller تكبر بسرعة.

مثال:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpg,png|max:2048',
    ]);
}
```

هذا قد يكون مقبولًا في البداية.
لكن مع الوقت سيكبر جدًا.

لذلك:

```bash
php artisan make:request StoreProductRequest
```

ثم:

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

وفي controller:

```php
public function store(StoreProductRequest $request): RedirectResponse
{
    Product::create($request->validated());

    return redirect()->route('products.index');
}
```

### السؤال المتوقع

إيه فايدة `validated()`؟

إنها ترجع فقط البيانات التي نجحت في الـ validation.

---

# الجزء السابع: Route Model Binding

## السؤال: ليه أكتب `Product $product` بدل `$id`؟

لأن Laravel تقدر تجيب السجل بدلًا منك.

بدل:

```php
public function show(string $id): View
{
    $product = Product::findOrFail($id);

    return view('products.show', compact('product'));
}
```

اكتب:

```php
public function show(Product $product): View
{
    return view('products.show', compact('product'));
}
```

### الفائدة

- كود أقل
- أوضح
- 404 تلقائي لو غير موجود
- تكرار أقل

---

# الجزء الثامن: Services

## السؤال: إمتى أحتاج Service؟

لما method الـ controller تبدأ تكبر أكثر من اللازم.

مثال:

- إنشاء منتج مع صورة
- إرسال notification
- تسجيل log
- dispatch event
- مزامنة stock

كل ده لو اجتمع داخل `store()` فالكود سيتعبك.

هنا service مفيدة.

مثال:

```php
public function store(StoreProductRequest $request, ProductService $productService): RedirectResponse
{
    $productService->create($request->validated());

    return redirect()
        ->route('products.index')
        ->with('success', 'تمت إضافة المنتج بنجاح');
}
```

---

# الجزء التاسع: Web Controller vs API Controller

## Web Controller

غالبًا يرجع:

- view
- redirect
- flash message

مثال:

```php
public function index(): View
{
    $products = Product::latest()->paginate(10);

    return view('products.index', compact('products'));
}
```

---

## API Controller

غالبًا يرجع:

- JSON
- status codes

مثال:

```php
use Illuminate\Http\JsonResponse;

public function index(): JsonResponse
{
    $products = Product::latest()->paginate(10);

    return response()->json([
        'status' => true,
        'data' => $products,
    ]);
}
```

### السؤال المتوقع

هل ينفع أخلط الاتنين؟

نظريًا نعم.
عمليًا الأفضل الفصل.

---

# الجزء العاشر: Return Types و Type Hints

## لماذا نكتبها؟

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
- أوضح للمحرر IDE
- تقلل الالتباس

هي ليست إجبارية دائمًا، لكنها `best practice` ممتازة.

---

# الجزء الحادي عشر: Authorization داخل Controllers

## السؤال: الصلاحيات تتكتب فين؟

أحيانًا المستخدم يكون logged in، لكن ليس له صلاحية تعديل هذا السجل.

هنا نستخدم:

- policy
- authorize
- middleware

مثال:

```php
public function edit(Product $product): View
{
    $this->authorize('update', $product);

    return view('products.edit', compact('product'));
}
```

### السؤال المتوقع

ليه ما نعتمدش على إخفاء زرار التعديل فقط؟

لأن إخفاء الزر في الواجهة ليس حماية حقيقية.
الحماية الحقيقية تكون في السيرفر.

---

# الجزء الثاني عشر: أخطاء شائعة جدًا

## 1. `Target class does not exist`

غالبًا لأن:

- نسيت `use`
- namespace غلط
- اسم الملف أو class غلط

مثال صحيح:

```php
use App\Http\Controllers\ProductController;
```

---

## 2. `Too few arguments`

غالبًا لأن method تتوقع parameter والـ route لا ترسلها.

---

## 3. `Call to undefined method`

غالبًا route تشير إلى method غير موجودة.

---

## 4. `MassAssignmentException`

غالبًا لأنك تعمل:

```php
Product::create($data);
```

لكن model لا تسمح بهذه الحقول في `$fillable`.

---

## 5. controller صارت ضخمة

ده ليس error من Laravel.
لكن error في التصميم.

### الحل

- Form Requests
- Services
- Policies
- Resources

---

# الجزء الثالث عشر: مثال نظيف ومتكامل

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

# الجزء الرابع عشر: أسئلة قد تدور في بال المبتدئ

## هل كل route لازم تروح لـ controller؟

لا.

لكن الأفضل في المشاريع الحقيقية نعم غالبًا.

---

## هل كل controller لازم تكون resource controller؟

لا.

لو عندك task واحدة فقط، `invokable controller` ممتاز.

---

## هل controller تتعامل مع database مباشرة؟

غالبًا نعم عن طريق models.

لكن لو المنطق كبر، استخدم services.

---

## هل ينفع أكتب validation داخل controller؟

نعم.

لكن في الشغل النظيف، عندما تكبر القواعد استخدم `Form Request`.

---

## هل controller مسؤولة عن شكل الصفحة؟

لا.

الـ view مسؤولة عن الشكل.
الـ controller فقط تبعت البيانات.

---

## هل controller مسؤولة عن HTML؟

لا.

لو لقيت نفسك تكتب HTML داخل controller، فأنت في الغالب في المكان الخطأ.

---

## هل controller هي التي تحدد redirect ولا view؟

نعم.

هي التي تقرر شكل الـ response النهائي.

---

## هل من الطبيعي أن method في controller تبقى 100 سطر؟

ممكن، لكن غالبًا دي إشارة إنك محتاج تقسيم.

---

## لو method طويلة جدًا أعمل إيه؟

- اخرج validation إلى Form Request
- اخرج business logic إلى Service
- اخرج authorization إلى Policy
- اخرج transformations إلى Resource

---

# الجزء الخامس عشر: الملخص النهائي

## الـ Controller باختصار

هي الكلاس التي:

- تستقبل الطلب
- تنسق السيناريو
- تتعامل مع model أو service
- ترجع response

---

## لو عايز تحفظ الدرس كله في جملة واحدة

> الـ Controller في Laravel هي نقطة التنسيق بين الـ Route والـ Model والـ View: تستقبل الطلب، ترتب التنفيذ، ثم ترجع النتيجة، من غير ما تتحول إلى مكان لكل منطق التطبيق.

---

## الخطوة التالية

بعد ما فهمت Controllers بالشكل الصحيح، الدرس المنطقي التالي هو:

## `08-laravel-migration-deep-guide.md`

لأنك الآن جاهز تبدأ تفهم قاعدة البيانات والجداول وتنظيمها صح.

</div>
