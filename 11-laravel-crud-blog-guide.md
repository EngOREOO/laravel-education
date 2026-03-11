<div dir="rtl">

# مشروع CRUD كامل في Laravel

### محاضرة عملية تفصيلية جدًا جدًا تربط كل ما سبق في مشروع مدونة بسيط بشكل نظيف واحترافي

---

## مقدمة

إحنا وصلنا الآن لمرحلة مهمة جدًا.

أنت درست:

- setup
- env
- MVC
- routing
- requests / validation / responses
- views / blade
- controllers
- migrations
- models
- relationships

لكن لسه فيه شيء مهم:

> إزاي كل الحاجات دي تشتغل مع بعض في مشروع حقيقي؟

الإجابة:

## مشروع CRUD كامل

وده من أهم الدروس في الرحلة كلها.

لأن الطالب هنا يبدأ يشوف:

- الـ migration شغالة ليه
- الـ model نحتاجها فين
- الـ controller بتنسق إزاي
- الـ routes تربط إيه بإيه
- الـ views تعرض إزاي
- الـ validation تحمي النظام إزاي

---

## يعني إيه CRUD؟

CRUD اختصار:

- Create
- Read
- Update
- Delete

يعني الأربع عمليات الأساسية في أي نظام بيانات تقريبًا.

مثال المقالات:

- تضيف مقال → Create
- تعرض المقالات → Read
- تعدل مقال → Update
- تحذف مقال → Delete

---

## ليه درس CRUD مهم جدًا؟

لأنه أول مشروع متكامل يربط كل الدروس السابقة ببعض.

لو الطالب فهم CRUD كويس، يبدأ بعد كده يفهم:

- auth
- dashboards
- admin panels
- APIs
- e-commerce

بثقة أعلى.

---

## المشروع اللي هنعمله

هنعمل:

## نظام مقالات بسيط

كل مقال فيه:

- عنوان
- محتوى
- اسم الكاتب

وهنعمل فيه:

1. عرض كل المقالات
2. عرض مقال واحد
3. إضافة مقال جديد
4. تعديل مقال
5. حذف مقال

---

## مهم جدًا قبل البدء

الهدف هنا ليس فقط "تشغيل الكود".

الهدف إننا نبني المشروع:

- بشكل منطقي
- بشكل نظيف
- مع best practices
- من غير shortcuts سيئة

يعني:

- لا نستخدم `request()->all()` بعشوائية
- نستخدم validation واضحة
- نستخدم naming واضح
- نبني flow نظيف

---

# الجزء الأول: تخطيط المشروع قبل كتابة الكود

## ما الذي نحتاجه؟

في CRUD طبيعي للمقالات نحتاج:

### 1. جدول في قاعدة البيانات

اسمه مثلًا:

```text
posts
```

---

### 2. Model

تمثل الجدول:

```text
Post
```

---

### 3. Controller

تدير السيناريوهات:

```text
PostController
```

---

### 4. Routes

تربط URLs بالـ controller methods

---

### 5. Views

لعرض:

- القائمة
- صفحة الإنشاء
- صفحة العرض
- صفحة التعديل

---

## شكل الـ flow النهائي

```text
User opens /posts
↓
Route goes to PostController@index
↓
Controller gets posts from Post model
↓
Controller returns posts.index view
↓
User sees all posts
```

وفي الحفظ:

```text
User submits create form
↓
Route goes to PostController@store
↓
Controller validates input
↓
Controller creates new post
↓
Controller redirects with success message
```

---

# الجزء الثاني: إنشاء الـ Migration

## الأمر

```bash
php artisan make:migration create_posts_table
```

هيعمل ملف داخل:

```text
database/migrations
```

---

## كود الـ Migration

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('author', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

---

## شرح الجدول

### `id()`

رقم المقال.

---

### `title`

عنوان المقال.

استخدمنا `string` لأنه نص قصير نسبيًا.

---

### `content`

محتوى المقال.

استخدمنا `text` لأنه طويل.

---

### `author`

اسم الكاتب.

استخدمنا `string(100)` لأن الاسم غالبًا لن يحتاج طولًا ضخمًا.

---

### `timestamps()`

تعطي:

- `created_at`
- `updated_at`

وده مهم جدًا في أي CRUD تقريبًا.

---

## تنفيذ الـ Migration

```bash
php artisan migrate
```

### السؤال المتوقع

إيه اللي حصل فعلًا؟

Laravel راحت لقاعدة البيانات،
وأنشأت جدول `posts` بنفس الشكل اللي كتبناه.

---

# الجزء الثالث: إنشاء الـ Model

## الأمر

```bash
php artisan make:model Post
```

أو من البداية كان ممكن:

```bash
php artisan make:model Post -m
```

---

## شكل الـ Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author',
    ];
}
```

---

## السؤال: ليه كتبنا `$fillable`؟

عشان نسمح فقط بهذه الحقول في:

```php
Post::create([...]);
```

وده مهم جدًا للحماية من:

## Mass Assignment

---

## السؤال: هل هذا يكفي للـ Model؟

نعم في هذا المشروع البسيط.

لأننا لا نحتاج الآن:

- relations
- casts معقدة
- scopes خاصة

لكن لو المشروع كبر، سنضيف هذه الأشياء لاحقًا.

---

# الجزء الرابع: إنشاء الـ Controller

## الأمر

```bash
php artisan make:controller PostController --resource
```

ده يجهز لك controller فيها الدوال الأساسية للـ CRUD.

---

## ما الدوال التي سنستخدمها؟

- `index`
- `create`
- `store`
- `show`
- `edit`
- `update`
- `destroy`

---

## لماذا `--resource` ممتاز هنا؟

لأن مشروعنا CRUD كامل.

وLaravel أصلًا لديها convention جاهزة لهذا النوع من المشاريع.

---

# الجزء الخامس: تسجيل الـ Routes

## داخل `routes/web.php`

```php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::resource('posts', PostController::class);
```

---

## السؤال: ماذا فعل هذا السطر؟

أنشأ لك routes مثل:

- `GET /posts`
- `GET /posts/create`
- `POST /posts`
- `GET /posts/{post}`
- `GET /posts/{post}/edit`
- `PUT/PATCH /posts/{post}`
- `DELETE /posts/{post}`

---

## راجعها بالأمر

```bash
php artisan route:list
```

وده مهم جدًا لأي طالب Laravel.

---

# الجزء السادس: كتابة الـ Controller خطوة خطوة

## الشكل النظيف الذي سنبني عليه

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        //
    }

    public function create(): View
    {
        //
    }

    public function store(Request $request): RedirectResponse
    {
        //
    }

    public function show(Post $post): View
    {
        //
    }

    public function edit(Post $post): View
    {
        //
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        //
    }

    public function destroy(Post $post): RedirectResponse
    {
        //
    }
}
```

---

## 1. `index()` - عرض كل المقالات

```php
public function index(): View
{
    $posts = Post::query()
        ->latest()
        ->paginate(10);

    return view('posts.index', compact('posts'));
}
```

### ما الذي يحدث هنا؟

1. نجيب المقالات
2. نرتبها من الأحدث
3. نقسمها صفحات
4. نرسلها إلى view

### السؤال: لماذا `paginate(10)` أفضل من `all()`؟

لأن:

- `all()` تجيب كل شيء دفعة واحدة
- `paginate()` أنسب للقوائم

وده cleaner وأقرب للشغل الحقيقي.

---

## 2. `create()` - صفحة إضافة مقال

```php
public function create(): View
{
    return view('posts.create');
}
```

### السؤال: ليه create لا تحفظ؟

لأن وظيفتها فقط:

> عرض الفورم

أما الحفظ نفسه فيكون في:

> `store()`

---

## 3. `store()` - حفظ المقال الجديد

```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'author' => ['required', 'string', 'max:100'],
    ]);

    Post::create($validated);

    return redirect()
        ->route('posts.index')
        ->with('success', 'تمت إضافة المقال بنجاح');
}
```

---

## لماذا هذا الشكل جيد؟

لأننا:

- عملنا validation واضحة
- استخدمنا `$validated`
- استخدمنا `Post::create($validated)`
- عملنا redirect مع message

---

## مهم جدًا: لا تعمل هذا

```php
Post::create($request->all());
```

### لماذا؟

لأنها:

- أقل أمانًا
- أقل وضوحًا
- تخلي الطالب يتعود على pattern سيئ

إحنا هنا نبني منهج clean code، فنتجنبها.

---

## 4. `show()` - عرض مقال واحد

```php
public function show(Post $post): View
{
    return view('posts.show', compact('post'));
}
```

### السؤال: من أين جاء `$post`؟

من:

## Route Model Binding

لأن route resource تستخدم:

```text
/posts/{post}
```

فLaravel تجيب الـ post تلقائيًا.

---

## 5. `edit()` - صفحة تعديل المقال

```php
public function edit(Post $post): View
{
    return view('posts.edit', compact('post'));
}
```

هنا:

- نجيب المقال تلقائيًا
- نفتح صفحة التعديل

---

## 6. `update()` - حفظ التعديل

```php
public function update(Request $request, Post $post): RedirectResponse
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'author' => ['required', 'string', 'max:100'],
    ]);

    $post->update($validated);

    return redirect()
        ->route('posts.index')
        ->with('success', 'تم تعديل المقال بنجاح');
}
```

---

## 7. `destroy()` - حذف المقال

```php
public function destroy(Post $post): RedirectResponse
{
    $post->delete();

    return redirect()
        ->route('posts.index')
        ->with('success', 'تم حذف المقال بنجاح');
}
```

### السؤال: هل ينفع أكتب:

```php
Post::findOrFail($id)->delete();
```

نعم.

لكن باستخدام Route Model Binding الشكل الحالي:

- أوضح
- أنظف
- أقل تكرارًا

---

# الجزء السابع: إنشاء الـ Views

## بنية الملفات

نعمل مجلد:

```text
resources/views/posts
```

وفيه:

- `index.blade.php`
- `create.blade.php`
- `show.blade.php`
- `edit.blade.php`

---

## 1. `index.blade.php`

```blade
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل المقالات</title>
</head>
<body>
    <h1>كل المقالات</h1>

    @if (session('success'))
        <div style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('posts.create') }}">إضافة مقال جديد</a>

    <hr>

    @forelse ($posts as $post)
        <article style="margin-bottom: 20px;">
            <h2>{{ $post->title }}</h2>
            <p>بواسطة: {{ $post->author }}</p>
            <p>{{ \Illuminate\Support\Str::limit($post->content, 150) }}</p>

            <a href="{{ route('posts.show', $post) }}">عرض</a>
            <a href="{{ route('posts.edit', $post) }}">تعديل</a>

            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                    حذف
                </button>
            </form>
        </article>
        <hr>
    @empty
        <p>لا توجد مقالات حاليًا.</p>
    @endforelse

    {{ $posts->links() }}
</body>
</html>
```

---

## شرح مهم جدًا

### `@forelse`

ممتازة لأنها:

- تعرض البيانات لو موجودة
- وتعطي رسالة واضحة لو القائمة فارغة

---

### `route('posts.show', $post)`

Laravel تستخدم الـ model نفسها وتستخرج الـ id تلقائيًا غالبًا.

---

### `@csrf`

ضرورية في أي form ترسل بيانات.

---

### `@method('DELETE')`

لأن HTML form الطبيعي لا يدعم DELETE،
فنستخدم method spoofing.

---

## 2. `create.blade.php`

```blade
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مقال</title>
</head>
<body>
    <h1>إضافة مقال جديد</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <div>
            <label for="title">العنوان</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}">
            @error('title')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="author">الكاتب</label>
            <input type="text" name="author" id="author" value="{{ old('author') }}">
            @error('author')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="content">المحتوى</label>
            <textarea name="content" id="content">{{ old('content') }}</textarea>
            @error('content')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">حفظ المقال</button>
    </form>

    <a href="{{ route('posts.index') }}">رجوع</a>
</body>
</html>
```

---

## السؤال: لماذا `old()` مهمة؟

لو validation فشلت:

- الصفحة ترجع
- البيانات القديمة تبقى موجودة

وده يحسن تجربة المستخدم جدًا.

---

## 3. `show.blade.php`

```blade
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
</head>
<body>
    <h1>{{ $post->title }}</h1>
    <p>بواسطة: {{ $post->author }}</p>
    <p>تاريخ الإنشاء: {{ $post->created_at->format('Y-m-d') }}</p>

    <hr>

    <div>
        {!! nl2br(e($post->content)) !!}
    </div>

    <hr>

    <a href="{{ route('posts.index') }}">رجوع</a>
    <a href="{{ route('posts.edit', $post) }}">تعديل</a>
</body>
</html>
```

---

## السؤال: لماذا استخدمنا

```blade
{!! nl2br(e($post->content)) !!}
```

لأن:

- `e()` للحماية من XSS
- `nl2br()` لتحويل الأسطر الجديدة إلى `<br>`

وده أنظف من طباعة النص الخام لو أردت الحفاظ على شكل الفقرات البسيط.

---

## 4. `edit.blade.php`

```blade
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المقال</title>
</head>
<body>
    <h1>تعديل المقال</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="title">العنوان</label>
            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}">
            @error('title')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="author">الكاتب</label>
            <input type="text" name="author" id="author" value="{{ old('author', $post->author) }}">
            @error('author')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="content">المحتوى</label>
            <textarea name="content" id="content">{{ old('content', $post->content) }}</textarea>
            @error('content')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">حفظ التعديلات</button>
    </form>

    <a href="{{ route('posts.index') }}">رجوع</a>
</body>
</html>
```

---

## السؤال: ليه استخدمنا `old('title', $post->title)`؟

لأننا نريد:

- لو validation فشلت → أعرض القيمة التي كتبها المستخدم
- غير ذلك → أعرض القيمة الأصلية من المقال

وده من أهم patterns في صفحات التعديل.

---

# الجزء الثامن: ماذا يحدث عند كل عملية؟

## Create

1. المستخدم يفتح `/posts/create`
2. route تنادي `create()`
3. controller تعرض الفورم
4. المستخدم يرسل الفورم
5. route تنادي `store()`
6. validation تعمل
7. `Post::create(...)`
8. redirect إلى القائمة

---

## Read

### عرض الكل

1. المستخدم يفتح `/posts`
2. route تنادي `index()`
3. controller تجيب المقالات
4. controller ترجع `posts.index`

### عرض واحد

1. المستخدم يفتح `/posts/1`
2. route model binding تجيب المقال
3. controller ترجع `posts.show`

---

## Update

1. المستخدم يفتح `/posts/{post}/edit`
2. controller تعرض الفورم
3. المستخدم يعدل
4. يرسل `PUT/PATCH`
5. validation تعمل
6. `$post->update(...)`
7. redirect

---

## Delete

1. المستخدم يضغط حذف
2. form ترسل `DELETE`
3. controller تنفذ `$post->delete()`
4. redirect مع message

---

# الجزء التاسع: Clean Code و Best Practices في مشروع CRUD

## 1. استخدم Route Model Binding

بدل:

```php
public function show($id)
{
    $post = Post::findOrFail($id);
}
```

الأفضل:

```php
public function show(Post $post)
{
    //
}
```

---

## 2. لا تستخدم `request->all()` بعشوائية

غلط:

```php
Post::create($request->all());
```

الأفضل:

```php
$validated = $request->validate([...]);
Post::create($validated);
```

---

## 3. استخدم `latest()->paginate(...)` في القوائم

ده أقرب للشغل الحقيقي من `all()`.

---

## 4. لا تخلط المنطق مع الـ view

الـ view تعرض.

الـ controller تنسق.

الـ model تتعامل مع البيانات.

---

## 5. الرسائل مهمة

مثل:

```php
->with('success', 'تم حذف المقال بنجاح');
```

دي جزء مهم من UX.

---

## 6. validation يجب أن تكون واضحة

لا تترك حقولًا مهمة بدون تحقق.

---

## 7. مع تطور المشروع استخدم Form Requests

في المشروع التعليمي هنا استخدمنا validation داخل controller لسهولة الفهم.

لكن لاحقًا الأفضل:

- `StorePostRequest`
- `UpdatePostRequest`

---

# الجزء العاشر: تحسينات تدريجية بعد إنهاء CRUD الأساسي

بعد ما CRUD الأساسي يشتغل، نقدر نطوره.

## 1. Pagination

إحنا أضفناها بالفعل في `index`.

---

## 2. بحث

```php
public function index(Request $request): View
{
    $query = Post::query();

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where('title', 'like', "%{$search}%")
            ->orWhere('content', 'like', "%{$search}%")
            ->orWhere('author', 'like', "%{$search}%");
    }

    $posts = $query->latest()->paginate(10);

    return view('posts.index', compact('posts'));
}
```

---

## 3. استخدام Form Requests

مثال:

```bash
php artisan make:request StorePostRequest
php artisan make:request UpdatePostRequest
```

ثم:

```php
public function store(StorePostRequest $request): RedirectResponse
{
    Post::create($request->validated());

    return redirect()->route('posts.index');
}
```

---

## 4. استخدام Layout بدل تكرار HTML

في مشروع تدريبي أول، أحيانًا كتابة الصفحات مستقلة مفيد للفهم.

لكن في الشغل الأفضل:

- layout رئيسية
- child views

---

# الجزء الحادي عشر: أخطاء شائعة جدًا

## 1. `Route [posts.index] not defined`

راجع:

- هل كتبت `Route::resource('posts', PostController::class)`؟
- هل route name صحيحة؟

---

## 2. `Class "App\Models\Post" not found`

راجع:

- هل أنشأت model؟
- هل عملت import صح؟

---

## 3. `MassAssignmentException`

راجع:

- هل أضفت الحقول إلى `$fillable`؟

---

## 4. validation fail ولا ترى الأخطاء

راجع:

- `@error`
- `old()`
- `@csrf`

---

## 5. الصفحة لا تتحدث بعد التعديل

راجع:

- هل form ترسل `@method('PUT')`؟
- هل route `update` موجودة؟

---

## 6. زر الحذف لا يعمل

راجع:

- `@csrf`
- `@method('DELETE')`
- route resource

---

# الجزء الثاني عشر: أسئلة المبتدئ

## هل CRUD لازم دائمًا يكون resource controller؟

ليس شرطًا.

لكن غالبًا نعم، لأنه أوضح وأنسب لهذا النوع.

---

## هل أقدر أعمل CRUD بدون controller؟

تقنيًا ممكن عبر closures.

لكن تعليميًا وعمليًا:

> الأفضل جدًا استخدام controller

---

## هل لازم كل صفحة يكون لها view منفصلة؟

في هذا المشروع نعم.

وده أوضح للمبتدئ.

---

## هل لازم أستخدم model في كل خطوة؟

في CRUD نعم تقريبًا، لأنها جوهر التعامل مع البيانات.

---

## هل ينفع أستخدم نفس validation في store وupdate؟

نعم.

لكن في المشاريع الأكبر ستفصلها غالبًا في Form Requests.

---

# الجزء الثالث عشر: الملخص النهائي

## هذا المشروع علمك

- كيف تبدأ من migration
- ثم model
- ثم controller
- ثم routes
- ثم views
- ثم validation
- ثم CRUD flow كامل

---

## الجملة الذهبية

لو عايز تختصر الدرس كله:

> مشروع CRUD في Laravel هو أول تطبيق عملي يربط كل عناصر الفريم وورك معًا: قاعدة البيانات، الـ models، الـ controllers، الـ routes، والـ views في سيناريو واقعي ومفهوم.

---

## الخطوة التالية

بعد هذا المشروع، تبقى جاهز جدًا للدخول في:

## `12-laravel-mvc-detailed-guide.md`

أو نعتبره درس مراجعة شاملة وتجميع وربط نهائي لكل ما سبق قبل الانتقال للموضوعات الأعلى من الأساسيات.

</div>
