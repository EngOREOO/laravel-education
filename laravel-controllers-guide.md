<div dir="rtl">

# 🎮 شرح Controllers في لارافيل - الدليل الشامل

### فهم المتحكمات (Controllers) من الصفر للاحتراف

---

## 🤔 يعني إيه Controller؟

**تخيل معايا مطعم:**

```
العميل (User)          →  "عايز برجر"
الويتر (Controller)    →  "حاضر، هجيبلك الطلب"
المطبخ (Model)         →  يحضر الطلب
الويتر (Controller)    →  يجيب الطلب للعميل
```

**الـ Controller = المدير اللي بينظم كل حاجة!**

---

## 📚 دور الـ Controller

الـ Controller مسؤول عن:

✅ **استقبال الطلب** من المستخدم (Request)  
✅ **التحقق من البيانات** (Validation)  
✅ **التعامل مع الـ Model** (جيب/احفظ/عدّل البيانات)  
✅ **إرجاع النتيجة** للمستخدم (Response)  

---

## 🎯 تشبيه كامل: المطعم

| المطعم | لارافيل |
|--------|---------|
| العميل | المستخدم (User) |
| القائمة | الـ Routes |
| الويتر | الـ Controller |
| المطبخ | الـ Model |
| الطبق | الـ View |

**السيناريو:**
1. العميل يطلب من القائمة (Route)
2. الويتر ياخد الطلب (Controller)
3. الويتر يروح للمطبخ (Model)
4. المطبخ يحضّر الطلب (Database)
5. الويتر يجيب الطلب (Controller)
6. العميل ياكل (View/Response)

---

## 🏗️ إنشاء Controller

### الطريقة الأولى: Controller عادي

```bash
php artisan make:controller ProductController
```

**النتيجة:**  
ملف في `app/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // دوالك هنا
}
```

---

### الطريقة الثانية: Resource Controller

```bash
php artisan make:controller ProductController --resource
```

**النتيجة:**  
Controller مع 7 دوال جاهزة للـ CRUD!

```php
class ProductController extends Controller
{
    public function index()      // عرض كل المنتجات
    public function create()     // صفحة إضافة منتج
    public function store()      // حفظ منتج جديد
    public function show()       // عرض منتج واحد
    public function edit()       // صفحة تعديل منتج
    public function update()     // حفظ التعديلات
    public function destroy()    // حذف منتج
}
```

---

### الطريقة الثالثة: API Resource Controller

```bash
php artisan make:controller Api/ProductController --api
```

**الفرق:**  
بدون `create()` و `edit()` (لأن الـ API مش محتاجهم)

---

### الطريقة الرابعة: Single Action Controller

```bash
php artisan make:controller SendEmailController --invokable
```

```php
class SendEmailController extends Controller
{
    public function __invoke()
    {
        // دالة واحدة بس
    }
}
```

---

## 📂 بنية الـ Controller

### Controller بسيط:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // جيب كل المنتجات
        $products = Product::all();
        
        // ابعتهم للـ View
        return view('products.index', compact('products'));
    }
}
```

---

## 🎯 الدوال الـ 7 في Resource Controller

### 1️⃣ `index()` - عرض كل السجلات

**الغرض:** صفحة تعرض قائمة بكل المنتجات

```php
public function index()
{
    // جيب كل المنتجات مع الفئات
    $products = Product::with('category')
                       ->latest()
                       ->paginate(10);
    
    return view('products.index', compact('products'));
}
```

**الـ Route:**
```
GET /products
```

---

### 2️⃣ `create()` - صفحة إضافة سجل جديد

**الغرض:** صفحة فيها فورم لإضافة منتج

```php
public function create()
{
    // جيب الفئات عشان القائمة المنسدلة
    $categories = Category::all();
    
    return view('products.create', compact('categories'));
}
```

**الـ Route:**
```
GET /products/create
```

---

### 3️⃣ `store()` - حفظ السجل الجديد

**الغرض:** حفظ البيانات اللي جاية من الفورم

```php
public function store(Request $request)
{
    // 1. تحقق من البيانات
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id'
    ]);
    
    // 2. احفظ المنتج
    $product = Product::create($validated);
    
    // 3. ارجع مع رسالة نجاح
    return redirect()->route('products.index')
                     ->with('success', 'المنتج تم إضافته بنجاح!');
}
```

**الـ Route:**
```
POST /products
```

---

### 4️⃣ `show($id)` - عرض سجل واحد

**الغرض:** صفحة تفاصيل منتج معين

```php
public function show($id)
{
    // جيب المنتج مع علاقاته
    $product = Product::with(['category', 'comments'])
                      ->findOrFail($id);
    
    return view('products.show', compact('product'));
}
```

**أو باستخدام Route Model Binding:**
```php
public function show(Product $product)
{
    // Laravel بيجيب المنتج تلقائياً!
    return view('products.show', compact('product'));
}
```

**الـ Route:**
```
GET /products/{id}
```

---

### 5️⃣ `edit($id)` - صفحة تعديل سجل

**الغرض:** صفحة فورم لتعديل المنتج

```php
public function edit($id)
{
    $product = Product::findOrFail($id);
    $categories = Category::all();
    
    return view('products.edit', compact('product', 'categories'));
}
```

**أو:**
```php
public function edit(Product $product)
{
    $categories = Category::all();
    return view('products.edit', compact('product', 'categories'));
}
```

**الـ Route:**
```
GET /products/{id}/edit
```

---

### 6️⃣ `update($id)` - حفظ التعديلات

**الغرض:** تحديث بيانات المنتج

```php
public function update(Request $request, $id)
{
    // 1. جيب المنتج
    $product = Product::findOrFail($id);
    
    // 2. تحقق من البيانات
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id'
    ]);
    
    // 3. حدّث المنتج
    $product->update($validated);
    
    // 4. ارجع مع رسالة
    return redirect()->route('products.index')
                     ->with('success', 'المنتج تم تعديله بنجاح!');
}
```

**أو:**
```php
public function update(Request $request, Product $product)
{
    $validated = $request->validate([...]);
    $product->update($validated);
    
    return redirect()->route('products.index')
                     ->with('success', 'تم التعديل!');
}
```

**الـ Route:**
```
PUT/PATCH /products/{id}
```

---

### 7️⃣ `destroy($id)` - حذف سجل

**الغرض:** حذف المنتج

```php
public function destroy($id)
{
    $product = Product::findOrFail($id);
    
    // امسح الصورة لو موجودة
    if ($product->image) {
        Storage::delete($product->image);
    }
    
    $product->delete();
    
    return redirect()->route('products.index')
                     ->with('success', 'المنتج تم حذفه بنجاح!');
}
```

**أو:**
```php
public function destroy(Product $product)
{
    if ($product->image) {
        Storage::delete($product->image);
    }
    
    $product->delete();
    
    return redirect()->route('products.index')
                     ->with('success', 'تم الحذف!');
}
```

**الـ Route:**
```
DELETE /products/{id}
```

---

## 🔗 ربط الـ Controller بالـ Routes

### الطريقة الأولى: Route Resource

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

**دي سطر واحد بيعمل 7 Routes!**

---

### شوف الـ Routes اللي اتعملت:

```bash
php artisan route:list
```

**النتيجة:**
```
GET       /products              products.index
GET       /products/create       products.create
POST      /products              products.store
GET       /products/{id}         products.show
GET       /products/{id}/edit    products.edit
PUT/PATCH /products/{id}         products.update
DELETE    /products/{id}         products.destroy
```

---

### الطريقة الثانية: Route منفردة

```php
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
```

---

### تحديد Routes معينة من الـ Resource:

```php
// استخدم index و show بس
Route::resource('products', ProductController::class)
     ->only(['index', 'show']);

// استخدم كل حاجة إلا destroy
Route::resource('products', ProductController::class)
     ->except(['destroy']);
```

---

## 📝 أمثلة عملية كاملة

### مثال 1: Controller منتجات كامل

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * عرض كل المنتجات
     */
    public function index()
    {
        $products = Product::with('category')
                           ->latest()
                           ->paginate(12);
        
        return view('products.index', compact('products'));
    }

    /**
     * صفحة إضافة منتج جديد
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // رفع الصورة
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                                          ->store('products', 'public');
        }
        
        // حفظ المنتج
        Product::create($validated);
        
        return redirect()->route('products.index')
                         ->with('success', 'تم إضافة المنتج بنجاح!');
    }

    /**
     * عرض منتج معين
     */
    public function show(Product $product)
    {
        // جيب المنتج مع علاقاته
        $product->load(['category', 'comments.user']);
        
        return view('products.show', compact('product'));
    }

    /**
     * صفحة تعديل منتج
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * تحديث المنتج
     */
    public function update(Request $request, Product $product)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // لو فيه صورة جديدة
        if ($request->hasFile('image')) {
            // امسح الصورة القديمة
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            // احفظ الجديدة
            $validated['image'] = $request->file('image')
                                          ->store('products', 'public');
        }
        
        // حدّث المنتج
        $product->update($validated);
        
        return redirect()->route('products.index')
                         ->with('success', 'تم تعديل المنتج بنجاح!');
    }

    /**
     * حذف منتج
     */
    public function destroy(Product $product)
    {
        // امسح الصورة
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        // امسح المنتج
        $product->delete();
        
        return redirect()->route('products.index')
                         ->with('success', 'تم حذف المنتج بنجاح!');
    }
}
```

---

### مثال 2: API Controller

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * جيب كل المنتجات
     */
    public function index()
    {
        $products = Product::with('category')->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        $product = Product::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج',
            'data' => $product
        ], 201);
    }

    /**
     * عرض منتج معين
     */
    public function show(Product $product)
    {
        $product->load('category');
        
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * تحديث منتج
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        $product->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'تم التحديث',
            'data' => $product
        ]);
    }

    /**
     * حذف منتج
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تم الحذف'
        ]);
    }
}
```

---

## 🎨 ميزات متقدمة في Controller

### 1️⃣ Route Model Binding

**بدل:**
```php
public function show($id)
{
    $product = Product::findOrFail($id);
    return view('products.show', compact('product'));
}
```

**استخدم:**
```php
public function show(Product $product)
{
    // Laravel بيجيب المنتج تلقائياً!
    return view('products.show', compact('product'));
}
```

---

### 2️⃣ Dependency Injection

**حقن التبعيات:**

```php
use App\Services\ProductService;

public function index(ProductService $productService)
{
    $products = $productService->getAllProducts();
    return view('products.index', compact('products'));
}
```

---

### 3️⃣ Form Request Validation

**إنشاء Form Request:**
```bash
php artisan make:request StoreProductRequest
```

**في الـ Request:**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'اسم المنتج مطلوب',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر لازم يكون رقم'
        ];
    }
}
```

**في الـ Controller:**
```php
public function store(StoreProductRequest $request)
{
    // البيانات متحققة تلقائياً!
    $validated = $request->validated();
    
    Product::create($validated);
    
    return redirect()->route('products.index')
                     ->with('success', 'تم الإضافة!');
}
```

---

### 4️⃣ Middleware في Controller

**تطبيق Middleware على كل الدوال:**
```php
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
```

**تطبيق على دوال معينة:**
```php
public function __construct()
{
    $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    
    // أو
    $this->middleware('auth')->except(['index', 'show']);
}
```

---

### 5️⃣ Authorization (التحقق من الصلاحيات)

```php
public function edit(Product $product)
{
    // تأكد إن المستخدم يقدر يعدّل
    $this->authorize('update', $product);
    
    $categories = Category::all();
    return view('products.edit', compact('product', 'categories'));
}

public function destroy(Product $product)
{
    $this->authorize('delete', $product);
    
    $product->delete();
    
    return redirect()->route('products.index')
                     ->with('success', 'تم الحذف!');
}
```

---

## 🔄 أنماط الـ Response

### 1️⃣ View Response

```php
return view('products.index', compact('products'));
```

---

### 2️⃣ Redirect Response

```php
// بسيط
return redirect('/products');

// مع Route name
return redirect()->route('products.index');

// مع رسالة
return redirect()->route('products.index')
                 ->with('success', 'تمت العملية!');

// مع أخطاء
return redirect()->back()->withErrors(['name' => 'الاسم مطلوب']);

// مع البيانات القديمة
return redirect()->back()->withInput();
```

---

### 3️⃣ JSON Response

```php
return response()->json([
    'success' => true,
    'data' => $products
]);

// مع Status Code
return response()->json([
    'success' => false,
    'message' => 'المنتج غير موجود'
], 404);
```

---

### 4️⃣ Download Response

```php
return response()->download($pathToFile);

// مع اسم مخصص
return response()->download($pathToFile, 'filename.pdf');
```

---

### 5️⃣ File Response

```php
return response()->file($pathToFile);
```

---

## 🎯 تنظيم الـ Controllers

### البنية الموصى بها:

```
app/Http/Controllers/
├── Admin/
│   ├── ProductController.php
│   └── UserController.php
├── Api/
│   ├── V1/
│   │   └── ProductController.php
│   └── V2/
│       └── ProductController.php
├── Auth/
│   ├── LoginController.php
│   └── RegisterController.php
└── ProductController.php
```

---

### Controller في مجلد فرعي:

```bash
php artisan make:controller Admin/ProductController --resource
```

```php
namespace App\Http\Controllers\Admin;

class ProductController extends Controller
{
    // ...
}
```

**في الـ Routes:**
```php
Route::namespace('Admin')->group(function () {
    Route::resource('products', ProductController::class);
});
```

---

## 💡 أفضل الممارسات

### 1️⃣ Keep Controllers Thin

**❌ سيء - Controller سمين:**
```php
public function store(Request $request)
{
    $request->validate([...]);
    
    $product = new Product();
    $product->name = $request->name;
    $product->price = $request->price;
    
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);
        $product->image = $imageName;
    }
    
    $product->save();
    
    // إرسال إيميل
    Mail::to('admin@example.com')->send(...);
    
    // تسجيل في Log
    Log::info('Product created: ' . $product->id);
    
    return redirect()->route('products.index');
}
```

**✅ جيد - Controller نحيف:**
```php
public function store(StoreProductRequest $request, ProductService $service)
{
    $product = $service->createProduct($request->validated());
    
    return redirect()->route('products.index')
                     ->with('success', 'تم الإضافة!');
}
```

---

### 2️⃣ استخدم Form Requests

**بدل:**
```php
public function store(Request $request)
{
    $request->validate([...]);
    // ...
}
```

**استخدم:**
```php
public function store(StoreProductRequest $request)
{
    // التحقق تم تلقائياً!
    // ...
}
```

---

### 3️⃣ استخدم Services للمنطق المعقد

```php
// app/Services/ProductService.php
class ProductService
{
    public function createProduct(array $data)
    {
        // منطق معقد هنا
        $product = Product::create($data);
        
        event(new ProductCreated($product));
        
        return $product;
    }
}
```

---

### 4️⃣ استخدم Resource Collections للـ API

```bash
php artisan make:resource ProductResource
```

```php
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price_formatted,
            'category' => $this->category->name
        ];
    }
}
```

**في الـ Controller:**
```php
public function index()
{
    $products = Product::paginate(15);
    return ProductResource::collection($products);
}
```

---

## 🐛 أخطاء شائعة وحلولها

### الخطأ 1: "Target class does not exist"

**السبب:** مش مستورد الـ Controller في الـ Routes

**الحل:**
```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

---

### الخطأ 2: "Too few arguments"

**السبب:** نسيت تمرر parameter للدالة

**الحل:**
```php
// ✅ صح
public function show(Product $product)

// ❌ غلط
public function show()
```

---

### الخطأ 3: "Call to undefined method"

**السبب:** اسم الدالة غلط في الـ Route

**الحل:**
```php
Route::get('/products', [ProductController::class, 'index']); // تأكد من الاسم
```

---

## 📊 ملخص سريع

**الـ Controller هو:**
- ✅ المدير اللي بينظم كل حاجة
- ✅ بيستقبل الطلب من المستخدم
- ✅ بيتعامل مع الـ Model
- ✅ بيرجع Response

**الدوال الـ 7:**
```php
index()    → عرض القائمة
create()   → صفحة الإضافة
store()    → حفظ جديد
show()     → عرض واحد
edit()     → صفحة التعديل
update()   → حفظ التعديل
destroy()  → حذف
```

**أفضل الممارسات:**
- ✅ Controller نحيف
- ✅ استخدم Form Requests
- ✅ استخدم Services
- ✅ استخدم Route Model Binding

---

**مبروك! 🎉 دلوقتي فاهم الـ Controllers!**

صُنع بحب ❤️ لكل مطور لارافيل

</div>
