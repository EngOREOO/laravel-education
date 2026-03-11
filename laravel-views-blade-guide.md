<div dir="rtl">

# 🎨 شرح Views و Blade في Laravel - الدليل التفصيلي جدًا للمبتدئين

### فهم الصفحات في Laravel من الصفر وبأمثلة بسيطة وواضحة جدًا

---

## 🎯 الدرس ده بيجاوب على سؤال مهم جدًا

إحنا فهمنا قبل كده:

- الـ Route بتوصل الطلب فين
- الـ Controller بيمسك الطلب إزاي
- الـ Request و Validation و Response شغالين إزاي

لكن لسه سؤال مهم:

> الصفحة نفسها بتتكتب فين؟

يعني:

- HTML بيتحط فين؟
- البيانات اللي جاية من Controller بتظهر إزاي؟
- ليه Laravel بيستخدم Blade؟
- إزاي نعمل layout مرة واحدة بدل ما نكرر navbar وfooter في كل صفحة؟

الإجابة كلها هنا في الدرس ده.

---

## 🧠 يعني إيه View أصلًا؟

الـ **View** هي الصفحة أو الجزء المرئي اللي المستخدم بيشوفه.

يعني مثلًا:

- الصفحة الرئيسية
- صفحة تسجيل الدخول
- صفحة عرض المنتجات
- صفحة إضافة منتج
- صفحة تعديل طالب

كل دي Views.

بمعنى بسيط جدًا:

> الـ View هي "وش التطبيق" اللي المستخدم بيتعامل معاه.

---

## 🧩 View مكانها في MVC

فاكر MVC؟

- Model = البيانات
- Controller = المنطق
- View = الشكل والواجهة

يعني الـ Controller يجيب البيانات،
والـ View تعرضها.

مثال:

```text
Controller يجيب المنتجات من الداتابيز
↓
يبعتها للـ View
↓
View تعرض المنتجات للمستخدم
```

فالـ View المفروض:

- تعرض البيانات
- تنسق الصفحة

لكن ما تكونش مليانة business logic معقد.

---

## 📂 فين الـ Views في Laravel؟

عادة داخل:

```text
resources/views
```

مثال:

```text
resources/views/welcome.blade.php
resources/views/products/index.blade.php
resources/views/products/create.blade.php
resources/views/layouts/app.blade.php
```

---

## 🤔 ليه الامتداد `.blade.php`؟

لأن Laravel بيستخدم محرك Templates اسمه:

## `Blade`

وده الـ templating engine الرسمي في Laravel.

يعني الملف:

```text
welcome.blade.php
```

معناه:

- ده ملف PHP
- لكنه مكتوب باستخدام Blade syntax

Blade بتخليك تكتب:

- HTML عادي
- مع أوامر سهلة زي:
  - `{{ }}`
  - `@if`
  - `@foreach`
  - `@extends`
  - `@section`

---

## 🧪 أول View في حياتك

### اعمل ملف:

```text
resources/views/hello.blade.php
```

واكتب فيه:

```blade
<h1>أهلا بك في Laravel</h1>
<p>دي أول View ليا</p>
```

### وبعدها في `routes/web.php`

```php
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return view('hello');
});
```

### لما تفتح:

```text
/hello
```

Laravel هيروح يجيب:

```text
resources/views/hello.blade.php
```

ويعرضه في المتصفح.

---

## 🧠 يعني إيه `view('hello')`؟

ده معناه:

> رجّع الـ View اللي اسمها `hello`

Laravel تلقائيًا بيفهم إن:

```php
view('hello')
```

تقصد:

```text
resources/views/hello.blade.php
```

---

## 📁 Views داخل فولدرات

لو عندك ملف هنا:

```text
resources/views/products/index.blade.php
```

فبتنادي عليه بالشكل ده:

```php
return view('products.index');
```

لاحظ:

- `/` اتحولت لـ `.`
- ومش بنكتب `.blade.php`

---

## 🧪 مثال

### الملف:

```text
resources/views/admin/dashboard.blade.php
```

### الاستدعاء:

```php
return view('admin.dashboard');
```

---

## 📦 إزاي أبعث بيانات من Controller إلى View؟

دي واحدة من أهم الحاجات.

مثال:

```php
public function index()
{
    $name = 'Ahmed';

    return view('welcome', [
        'name' => $name,
    ]);
}
```

### هنا إيه اللي حصل؟

إحنا بعتنا للـ View متغير اسمه:

```php
name
```

وقيمته:

```php
Ahmed
```

---

## 🖥️ إزاي أستخدم المتغير داخل الـ View؟

في الملف:

```blade
<h1>أهلا يا {{ $name }}</h1>
```

### النتيجة:

```text
أهلا يا Ahmed
```

---

## 🔤 يعني إيه `{{ $name }}`؟

دي أشهر صيغة في Blade.

اسمها:

> Echo / Display Data

يعني:

> اعرض قيمة المتغير ده داخل الصفحة

---

## 🔐 Blade بتحميني من XSS بشكل تلقائي

لما تكتب:

```blade
{{ $name }}
```

Laravel بيعمل Escape تلقائي للـ HTML.

يعني لو المستخدم كتب:

```html
<script>alert('hack')</script>
```

Blade مش هتنفذه كسكربت، بل هتعرضه كنص.

وده مهم جدًا للأمان.

---

## ⚠️ عرض HTML بدون Escape

في حالات نادرة جدًا، لو أنت متأكد إن المحتوى آمن:

```blade
{!! $content !!}
```

لكن خلي بالك جدًا:

> دي خطيرة لو المحتوى جاي من المستخدم

لأنها ممكن تفتح باب XSS.

فالقاعدة:

- استخدم `{{ }}` في الأغلب
- واستخدم `{!! !!}` فقط عند الضرورة القصوى

---

## 🧰 طرق مختلفة لتمرير البيانات للـ View

### الطريقة الأولى: Array

```php
return view('welcome', [
    'name' => 'Ahmed',
    'age' => 22,
]);
```

---

### الطريقة الثانية: `compact`

```php
$name = 'Ahmed';
$age = 22;

return view('welcome', compact('name', 'age'));
```

دي شائعة جدًا.

---

### الطريقة الثالثة: `with`

```php
return view('welcome')
    ->with('name', 'Ahmed')
    ->with('age', 22);
```

شغالة، لكن غالبًا:

> `compact()` أو الـ array أوضح للمبتدئ

---

## 🧪 مثال واقعي: عرض بيانات منتج

### في الـ Controller

```php
public function show()
{
    $product = [
        'name' => 'Laptop',
        'price' => 15000,
        'description' => 'جهاز ممتاز للشغل',
    ];

    return view('products.show', compact('product'));
}
```

### في `resources/views/products/show.blade.php`

```blade
<h1>{{ $product['name'] }}</h1>
<p>السعر: {{ $product['price'] }}</p>
<p>{{ $product['description'] }}</p>
```

---

# الجزء الثاني: Blade Basics

## 🧠 Blade إيه بالضبط؟

Blade هو لغة Templates بسيطة فوق HTML وPHP.

يعني بدل ما تكتب PHP تقليدية كتير زي:

```php
<?php if ($user): ?>
    <h1><?php echo $user->name; ?></h1>
<?php endif; ?>
```

Blade بتخليك تكتبها بشكل أنضف:

```blade
@if ($user)
    <h1>{{ $user->name }}</h1>
@endif
```

فهي:

- أنضف
- أوضح
- أسهل في القراءة
- مناسبة جدًا مع Laravel

---

## ✅ أشهر Blade Directives

### `{{ }}` لعرض البيانات

```blade
{{ $name }}
```

---

### `@if`

```blade
@if ($age >= 18)
    <p>أنت بالغ</p>
@endif
```

---

### `@if / @else`

```blade
@if ($isAdmin)
    <p>أنت أدمن</p>
@else
    <p>أنت مستخدم عادي</p>
@endif
```

---

### `@elseif`

```blade
@if ($degree >= 90)
    <p>امتياز</p>
@elseif ($degree >= 75)
    <p>جيد جدًا</p>
@elseif ($degree >= 50)
    <p>مقبول</p>
@else
    <p>راسب</p>
@endif
```

---

### `@isset`

```blade
@isset($product)
    <p>المنتج موجود</p>
@endisset
```

---

### `@empty`

```blade
@empty($products)
    <p>لا توجد منتجات</p>
@endempty
```

---

## 🔁 الحلقات في Blade

### `@foreach`

دي الأشهر والأهم.

```blade
@foreach ($products as $product)
    <p>{{ $product }}</p>
@endforeach
```

---

## 🧪 مثال عملي

### في الـ Controller

```php
public function index()
{
    $products = ['Laptop', 'Mouse', 'Keyboard'];

    return view('products.index', compact('products'));
}
```

### في الـ View

```blade
<h1>المنتجات</h1>

@foreach ($products as $product)
    <p>{{ $product }}</p>
@endforeach
```

---

### `@forelse`

دي ممتازة جدًا لما تكون عايز تتعامل مع القائمة الفاضية.

```blade
@forelse ($products as $product)
    <p>{{ $product }}</p>
@empty
    <p>لا توجد منتجات حاليًا</p>
@endforelse
```

دي أحسن من:

- تعمل `if`
- وبعدين `foreach`

---

### `@for`

```blade
@for ($i = 1; $i <= 5; $i++)
    <p>رقم {{ $i }}</p>
@endfor
```

---

### `@while`

```blade
@php $i = 1; @endphp

@while ($i <= 3)
    <p>{{ $i }}</p>
    @php $i++; @endphp
@endwhile
```

أقل استخدامًا، لكن موجودة.

---

## 🪄 متغير `$loop`

داخل `@foreach`، Blade بتوفر متغير اسمه `$loop`.

مثال:

```blade
@foreach ($products as $product)
    <p>{{ $loop->iteration }} - {{ $product }}</p>
@endforeach
```

### النتيجة:

```text
1 - Laptop
2 - Mouse
3 - Keyboard
```

### من أشهر خصائصه:

- `$loop->iteration` رقم الدورة يبدأ من 1
- `$loop->index` رقم الدورة يبدأ من 0
- `$loop->first` هل دي أول دورة؟
- `$loop->last` هل دي آخر دورة؟

---

## 🧪 مثال مفيد

```blade
@foreach ($products as $product)
    <div>
        @if ($loop->first)
            <strong>أول منتج:</strong>
        @endif

        {{ $product }}

        @if ($loop->last)
            <strong> - آخر منتج</strong>
        @endif
    </div>
@endforeach
```

---

# الجزء الثالث: Layouts

## 😫 المشكلة التي تظهر بسرعة

لو عندك 10 صفحات، وكل صفحة فيها:

- navbar
- footer
- title
- نفس ملفات CSS

هل هتكررهم في كل صفحة؟

لو عملت كده:

- الكود هيكبر
- التعديل هيبقى متعب
- لو غيرت الـ navbar هتعدلها 10 مرات

هنا يأتي دور:

## `Layout`

---

## 🧠 يعني إيه Layout؟

الـ Layout هو:

> قالب رئيسي مشترك بين أكثر من صفحة

مثال:

- الهيدر ثابت
- الفوتر ثابت
- الـ content فقط هو اللي بيتغير

---

## 📁 مثال على تنظيم الـ Layouts

```text
resources/views/layouts/app.blade.php
resources/views/products/index.blade.php
resources/views/products/create.blade.php
resources/views/products/show.blade.php
```

---

## 🏗️ إنشاء Layout رئيسي

### ملف:

```text
resources/views/layouts/app.blade.php
```

### محتواه:

```blade
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'موقعي')</title>
</head>
<body>
    <header>
        <h1>موقعي</h1>
        <nav>
            <a href="/">الرئيسية</a>
            <a href="/products">المنتجات</a>
            <a href="/contact">تواصل معنا</a>
        </nav>
        <hr>
    </header>

    <main>
        @yield('content')
    </main>

    <hr>

    <footer>
        <p>جميع الحقوق محفوظة</p>
    </footer>
</body>
</html>
```

---

## 🧠 يعني إيه `@yield`؟

دي معناها:

> حط هنا محتوى سيأتي من صفحة أخرى

مثلًا:

```blade
@yield('content')
```

يعني:

الصفحة الفرعية هتملأ الجزء ده.

---

## 🪄 استخدام Layout في صفحة أخرى

### ملف:

```text
resources/views/products/index.blade.php
```

### محتواه:

```blade
@extends('layouts.app')

@section('title', 'صفحة المنتجات')

@section('content')
    <h2>كل المنتجات</h2>
    <p>دي صفحة المنتجات</p>
@endsection
```

---

## 🧠 شرح `@extends`

```blade
@extends('layouts.app')
```

يعني:

> الصفحة دي مبنية فوق layout اسمه `layouts.app`

يعني Laravel يروح يجيب:

```text
resources/views/layouts/app.blade.php
```

---

## 🧠 شرح `@section`

```blade
@section('content')
    ...
@endsection
```

يعني:

> الجزء ده هيتحط مكان `@yield('content')`

---

## 🧪 مثال كامل

### Layout

```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Laravel App')</title>
</head>
<body>
    <h1>الهيدر</h1>

    @yield('content')

    <p>الفوتر</p>
</body>
</html>
```

### Child View

```blade
@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    <h2>أهلا بك</h2>
    <p>دي الصفحة الرئيسية</p>
@endsection
```

### النتيجة النهائية في المتصفح

```html
<html>
<head>
    <title>الرئيسية</title>
</head>
<body>
    <h1>الهيدر</h1>

    <h2>أهلا بك</h2>
    <p>دي الصفحة الرئيسية</p>

    <p>الفوتر</p>
</body>
</html>
```

---

## 🧱 أكثر من Section

في الـ layout:

```blade
<title>@yield('title')</title>

<body>
    @yield('header')
    @yield('content')
    @yield('footer')
</body>
```

وفي الصفحة:

```blade
@extends('layouts.app')

@section('title', 'منتجات')

@section('header')
    <h1>عنوان خاص بالصفحة</h1>
@endsection

@section('content')
    <p>محتوى الصفحة</p>
@endsection

@section('footer')
    <p>فوتر خاص بالصفحة</p>
@endsection
```

---

# الجزء الرابع: Blade مع الفورمز

## 🧾 الفورم في Blade

مثال:

```blade
<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <input type="text" name="name" value="{{ old('name') }}">

    <button type="submit">حفظ</button>
</form>
```

---

## 🔐 `@csrf`

مهمة جدًا.

أي فورم بيرسل:

- POST
- PUT
- PATCH
- DELETE

لازم غالبًا يكون فيها:

```blade
@csrf
```

عشان Laravel يعرف إن الطلب شرعي ومش هجوم.

---

## 🪄 `@method`

HTML form الطبيعي لا يدعم:

- PUT
- PATCH
- DELETE

لذلك Laravel يوفر:

```blade
@method('PUT')
```

مثال تعديل:

```blade
<form method="POST" action="{{ route('products.update', $product->id) }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ old('name', $product->name) }}">
    <button type="submit">تعديل</button>
</form>
```

---

## ❌ عرض أخطاء الـ Validation

### لكل حقل

```blade
<input type="text" name="name" value="{{ old('name') }}">

@error('name')
    <div style="color:red">{{ $message }}</div>
@enderror
```

---

### كل الأخطاء مرة واحدة

```blade
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li style="color:red">{{ $error }}</li>
        @endforeach
    </ul>
@endif
```

---

## 🔁 `old()`

دي ترجع القيمة اللي المستخدم كتبها قبل ما الـ validation تفشل.

```blade
<input type="text" name="name" value="{{ old('name') }}">
```

ولو في edit form:

```blade
<input type="text" name="name" value="{{ old('name', $product->name) }}">
```

يعني:

- لو المستخدم كتب قيمة ورجع بخطأ، هات القيمة القديمة
- غير كده هات القيمة الأصلية من المنتج

---

# الجزء الخامس: Include و Partial Views

## 🤔 ليه نستخدم `@include`؟

أحيانًا عندك جزء صغير بيتكرر:

- navbar
- sidebar
- alert box
- form fields

بدل ما تكرره، خليه في ملف مستقل.

---

## 🧪 مثال

### ملف:

```text
resources/views/partials/navbar.blade.php
```

### محتواه:

```blade
<nav>
    <a href="/">الرئيسية</a>
    <a href="/products">المنتجات</a>
</nav>
```

### وفي الـ Layout:

```blade
@include('partials.navbar')
```

---

## ✅ فايدة `@include`

- تقليل التكرار
- سهولة التعديل
- الكود يبقى أنظف

---

# الجزء السادس: Components

## 🤔 يعني إيه Component؟

الـ Component هو جزء واجهة قابل لإعادة الاستخدام.

مثلًا:

- زرار
- alert box
- card
- modal
- input field

يعني بدل ما تكرر نفس HTML كتير، تعمله component.

---

## 🧱 مثال بسيط جدًا على Component

أنشئ component:

```bash
php artisan make:component Alert
```

Laravel هيعمل غالبًا:

```text
app/View/Components/Alert.php
resources/views/components/alert.blade.php
```

---

## ملف الـ View الخاص بالـ Component

```blade
<div style="padding:10px; border:1px solid #ccc;">
    {{ $slot }}
</div>
```

### استخدامه:

```blade
<x-alert>
    تم حفظ البيانات بنجاح
</x-alert>
```

### النتيجة:

هيعرض المحتوى داخل component alert.

---

## 🧠 يعني إيه `$slot`؟

ده المكان اللي بيتحط فيه المحتوى المكتوب بين وسمَي component.

مثال:

```blade
<x-alert>
    رسالة مهمة
</x-alert>
```

`رسالة مهمة` هتروح داخل:

```blade
{{ $slot }}
```

---

## 📦 Component بخصائص

### داخل component:

```blade
<div>
    <strong>{{ $title }}</strong>
    <p>{{ $slot }}</p>
</div>
```

### الاستخدام:

```blade
<x-alert title="تنبيه">
    المنتج غير متوفر
</x-alert>
```

---

# الجزء السابع: Session Messages داخل Blade

## ✅ عرض رسالة نجاح

في الـ Controller:

```php
return redirect()->route('products.index')
    ->with('success', 'تم الحفظ بنجاح');
```

في الـ View:

```blade
@if (session('success'))
    <div style="color: green">
        {{ session('success') }}
    </div>
@endif
```

---

## ❌ عرض رسالة خطأ عامة

```blade
@if (session('error'))
    <div style="color: red">
        {{ session('error') }}
    </div>
@endif
```

---

# الجزء الثامن: مثال عملي متكامل

## 🎯 سيناريو: صفحة عرض منتجات

### Route

```php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
```

### Controller

```php
public function index()
{
    $products = [
        ['name' => 'Laptop', 'price' => 15000],
        ['name' => 'Mouse', 'price' => 300],
        ['name' => 'Keyboard', 'price' => 700],
    ];

    return view('products.index', compact('products'));
}
```

### Layout

```blade
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'متجري')</title>
</head>
<body>
    <h1>متجري</h1>
    <hr>

    @yield('content')

    <hr>
    <p>Footer</p>
</body>
</html>
```

### View

```blade
@extends('layouts.app')

@section('title', 'كل المنتجات')

@section('content')
    <h2>المنتجات</h2>

    @forelse ($products as $product)
        <div style="margin-bottom: 10px;">
            <strong>{{ $product['name'] }}</strong>
            <p>السعر: {{ $product['price'] }}</p>
        </div>
    @empty
        <p>لا توجد منتجات حاليًا</p>
    @endforelse
@endsection
```

---

## 🧠 إيه اللي حصل هنا؟

1. الـ Route استقبل الطلب
2. الـ Controller جهز البيانات
3. الـ Controller أرسل البيانات إلى View
4. الـ View استخدمت Blade لعرض البيانات
5. الـ Layout لفّ الصفحة كلها في شكل واحد موحد

وده بالضبط الشكل الحقيقي الطبيعي لأي تطبيق Laravel.

---

# الجزء التاسع: أخطاء شائعة جدًا

## الخطأ 1: View [something] not found

يعني Laravel مش لاقي ملف الـ view.

### الأسباب:

- الاسم غلط
- الفولدر غلط
- نسيت `.blade.php`
- كتبت `products/index` بدل `products.index`

---

## الخطأ 2: Undefined variable

يعني الـ view بتحاول تستخدم متغير ما اتبعتش من الـ Controller.

مثال:

في الـ view:

```blade
{{ $products }}
```

لكن في الـ Controller ما بعتش `products`.

---

## الخطأ 3: الصفحة تعرض HTML غريب أو PHP ظاهر

غالبًا:

- نسيت تستخدم Blade syntax صح
- أو الملف مش `.blade.php`

---

## الخطأ 4: Layout لا يطبق

غالبًا:

- نسيت `@extends`
- أو اسم الـ layout غلط
- أو نسيت `@section('content')`

---

## الخطأ 5: البيانات لا تظهر بعد validation fail

غالبًا:

- نسيت `old()`
- أو نسيت `@error`

---

# الجزء العاشر: أفضل الممارسات

## 1. خليك منظم في أسماء الـ Views

مثلًا:

```text
products/index.blade.php
products/create.blade.php
products/edit.blade.php
products/show.blade.php
```

ده أفضل من عشوائية الملفات.

---

## 2. استخدم Layout موحد

أي مشروع حقيقي لازم يكون فيه layout رئيسي على الأقل.

---

## 3. لا تضع business logic معقدة داخل View

الـ View للعرض، مش لاتخاذ قرارات معقدة جدًا.

يعني:

- تنسيقات بسيطة؟ عادي
- loops؟ عادي
- ifs بسيطة؟ عادي
- استعلامات داتابيز جوه الـ view؟ غلط

---

## 4. استخدم Components وIncludes عند التكرار

لو نفس الجزء بيتكرر أكثر من مرة:

- اعمله include
- أو component

---

## 5. استخدم `@forelse` بدل `@foreach` + `if`

في حالات القوائم، ده غالبًا أنظف.

---

# الجزء الحادي عشر: ملخص سريع جدًا

## الـ View

هي الصفحة التي يراها المستخدم.

مكانها غالبًا:

```text
resources/views
```

---

## Blade

هي لغة القوالب الخاصة بـ Laravel.

أشهر ما فيها:

- `{{ }}`
- `@if`
- `@foreach`
- `@forelse`
- `@extends`
- `@section`
- `@yield`
- `@include`
- `@csrf`
- `@method`
- `@error`

---

## Layout

هو القالب الرئيسي المشترك بين الصفحات.

---

## Components

هي أجزاء واجهة قابلة لإعادة الاستخدام.

---

## الجملة الذهبية

لو عايز تختصر الدرس كله:

> الـ Controller يجهز البيانات، والـ View تعرضها، وBlade هي اللغة اللي تساعدنا نكتب الصفحات بشكل نظيف ومنظم داخل Laravel.

---

## 🚀 الدرس اللي بعده منطقيًا

بعد `Views + Blade`، الخطوة الطبيعية جدًا هي:

### `Database + Migrations`

لأنك دلوقتي بقيت فاهم:

- Route
- Controller
- Request
- Validation
- Response
- View

وفاضل تدخل فعليًا على تخزين البيانات بشكل منظم في قاعدة البيانات.

</div>
