<div dir="rtl">

# 📝 شرح CRUD للمبتدئين - نظام مدونة بسيط

### دليل عملي خطوة بخطوة لعمل نظام مقالات كامل

---

## 🤔 إيه هو CRUD؟

**CRUD** هي اختصار لـ 4 عمليات أساسية في أي نظام:

- **C** = Create (إنشاء) - إضافة مقال جديد
- **R** = Read (قراءة) - عرض المقالات
- **U** = Update (تحديث) - تعديل مقال موجود
- **D** = Delete (حذف) - مسح مقال

**مثال من الحياة:** تخيل دفتر ملاحظات:
- تكتب ملاحظة جديدة → Create
- تقرأ الملاحظات → Read
- تعدل على ملاحظة → Update
- تمسح ملاحظة → Delete

---

## 🎯 هنعمل إيه؟

هنعمل نظام مدونة بسيط يقدر يعمل:
- ✅ إضافة مقالات جديدة
- ✅ عرض كل المقالات
- ✅ عرض مقال واحد بالتفصيل
- ✅ تعديل المقالات
- ✅ حذف المقالات

---

## 📋 الخطوة الأولى: إنشاء الداتابيز والجدول

### 1️⃣ إنشاء الـ Migration

الـ Migration ده زي "وصفة" بتقول لـ Laravel يعمل جدول معين في الداتابيز

اكتب الأمر ده في الترمنال:

```bash
php artisan make:migration create_posts_table
```

**شرح الأمر:**
- `make:migration` - اعمل ملف migration جديد
- `create_posts_table` - اسم الـ migration (لازم يكون واضح)

---

### 2️⃣ تصميم الجدول

روح على الملف اللي اتعمل في المسار ده:
```
database/migrations/xxxx_xx_xx_create_posts_table.php
```

هتلاقي دالة اسمها `up()` - دي بتحدد شكل الجدول:

```php
public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();                      // رقم تلقائي للمقال
        $table->string('title');           // عنوان المقال
        $table->text('content');           // محتوى المقال
        $table->string('author');          // اسم الكاتب
        $table->timestamps();              // وقت الإنشاء والتعديل
    });
}
```

**شرح الأعمدة:**
- `id()` - رقم تلقائي بيزيد لوحده (1, 2, 3...)
- `string()` - نص قصير (للعناوين مثلاً)
- `text()` - نص طويل (للمحتوى)
- `timestamps()` - بيضيف عمودين: created_at و updated_at

---

### 3️⃣ تنفيذ الـ Migration

دلوقتي نحول الوصفة دي لجدول حقيقي:

```bash
php artisan migrate
```

**النتيجة:** هيتعمل جدول اسمه `posts` في الداتابيز! ✅

---

## 🏗️ الخطوة الثانية: إنشاء الـ Model

الـ Model ده زي "الوسيط" بين الكود والداتابيز

```bash
php artisan make:model Post
```

**ملحوظة:** اسم الـ Model بيكون مفرد (Post) والجدول بيكون جمع (posts)

---

### تجهيز الـ Model

روح على الملف:
```
app/Models/Post.php
```

ضيف الكود ده:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // الحقول اللي ممكن نملاها
    protected $fillable = [
        'title',
        'content', 
        'author'
    ];
}
```

**شرح `$fillable`:**
- دي قائمة بالحقول اللي مسموح نحفظها مباشرة
- للحماية من حفظ حقول مش مفروض نحفظها

---

## 🎮 الخطوة الثالثة: إنشاء الـ Controller

الـ Controller ده المتحكم - بيستقبل الطلبات وينفذها

```bash
php artisan make:controller PostController --resource
```

**شرح:**
- `PostController` - اسم الـ Controller
- `--resource` - يعمل كل الدوال الجاهزة للـ CRUD

---

### دوال الـ Controller الجاهزة

روح على الملف:
```
app/Http/Controllers/PostController.php
```

هتلاقي 7 دوال جاهزة، هنستخدم 5 منهم:

| الدالة | الوظيفة |
|-------|---------|
| `index()` | عرض كل المقالات |
| `create()` | صفحة إضافة مقال جديد |
| `store()` | حفظ المقال الجديد |
| `show()` | عرض مقال واحد |
| `edit()` | صفحة تعديل مقال |
| `update()` | حفظ التعديلات |
| `destroy()` | حذف مقال |

---

## 🛣️ الخطوة الرابعة: تحديد الـ Routes

الـ Routes دي زي "خريطة" بتقول لارافيل: لما حد يدخل على لينك معين، يروح فين؟

روح على الملف:
```
routes/web.php
```

ضيف السطر ده:

```php
use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class);
```

**✨ السطر الواحد ده عمل 7 Routes جاهزة!**

---

### الـ Routes اللي اتعملت:

| الأمر HTTP | اللينك | الدالة | الوظيفة |
|-----------|--------|--------|---------|
| GET | `/posts` | index | عرض كل المقالات |
| GET | `/posts/create` | create | فورم إضافة مقال |
| POST | `/posts` | store | حفظ مقال جديد |
| GET | `/posts/{id}` | show | عرض مقال محدد |
| GET | `/posts/{id}/edit` | edit | فورم تعديل مقال |
| PUT/PATCH | `/posts/{id}` | update | حفظ التعديل |
| DELETE | `/posts/{id}` | destroy | حذف مقال |

لو عايز تشوف كل الـ Routes:
```bash
php artisan route:list
```

---

## 💻 الخطوة الخامسة: كتابة الكود

### 1️⃣ دالة index - عرض كل المقالات

```php
public function index()
{
    // جيب كل المقالات من الداتابيز
    $posts = Post::all();
    
    // ابعتهم لصفحة العرض
    return view('posts.index', compact('posts'));
}
```

**شرح سطر سطر:**
- `Post::all()` - جيب كل المقالات
- `compact('posts')` - ابعت المتغير للصفحة
- `posts.index` - اسم ملف الـ View

---

### 2️⃣ دالة create - صفحة إضافة مقال

```php
public function create()
{
    // بس افتح صفحة الفورم
    return view('posts.create');
}
```

---

### 3️⃣ دالة store - حفظ المقال الجديد

```php
public function store(Request $request)
{
    // تحقق من البيانات
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'author' => 'required|max:100'
    ]);
    
    // احفظ المقال الجديد
    Post::create([
        'title' => $request->title,
        'content' => $request->content,
        'author' => $request->author
    ]);

     // ممكن نستغني عن كل الأسطر دي ونعمل ابديت بسطر واحد بس
    $blog = Blog::create($request->all());
    // هنا خدنا كل الداتا الي جوا الريكويست وقلنا ل لارافيل خزنهالي كلها بدل م اعمل 4 او 5 اسطر زي مهو موجود فوق كده
    
    
    // ارجع لصفحة المقالات مع رسالة نجاح
    return redirect()->route('posts.index')
        ->with('success', 'المقال اتضاف بنجاح!');
}
```

**شرح `validate`:**
- `required` - لازم يتملى
- `max:255` - أقصى طول 255 حرف
- لو البيانات غلط، بيرجع تلقائي للصفحة مع رسائل الخطأ

---

### 4️⃣ دالة show - عرض مقال واحد

```php
public function show($id)
{
    // جيب المقال بالـ id المحدد
    $post = Post::findOrFail($id);
    
    // اعرضه في صفحة
    return view('posts.show', compact('post'));
}
```

**شرح `findOrFail`:**
- لو لقى المقال، يجيبه
- لو ملقاش، يظهر صفحة 404

---

### 5️⃣ دالة edit - صفحة التعديل

```php
public function edit($id)
{
    // جيب المقال
    $post = Post::findOrFail($id);
    
    // افتح صفحة التعديل
    return view('posts.edit', compact('post'));
}
```

---

### 6️⃣ دالة update - حفظ التعديلات

```php
public function update(Request $request, $id)
{
    // تحقق من البيانات
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'author' => 'required|max:100'
    ]);
    
    // جيب المقال وحدّثه
    $post = Post::findOrFail($id);
    $post->update([
        'title' => $request->title,
        'content' => $request->content,
        'author' => $request->author
    ]);

    // ممكن نستغني عن كل الأسطر دي ونعمل ابديت بسطر واحد بس
    $blog->update($request->all());
    // هنا خدنا كل الداتا الي جوا الريكويست وقلنا ل لارافيل حدثيها كلها بدل م اعمل 4 او 5 اسطر زي مهو موجود فوق كده
    
    // ارجع مع رسالة نجاح
    return redirect()->route('posts.index')
        ->with('success', 'المقال اتعدّل بنجاح!');
}
```

---

### 7️⃣ دالة destroy - حذف المقال

```php
public function destroy($id)
{
    // جيب المقال وامسحه 1
    $post = Post::findOrFail($id);
    $post->delete();

    // نقدر نعملها ف خطوه واحده بس لو عملنا كده 2
    // سطر واحد بيقوم بالي بيعمله السطرين 
    $post = Post::findOrFail($id)->delete();
    // ارجع مع رسالة نجاح
    return redirect()->route('posts.index')
        ->with('success', 'المقال اتمسح بنجاح!');
}
```

---

## 🎨 الخطوة السادسة: إنشاء صفحات العرض (Views)

### بنية المجلدات

اعمل مجلد جديد:
```
resources/views/posts/
```

فيه هنعمل 4 ملفات:
- `index.blade.php` - صفحة عرض كل المقالات
- `create.blade.php` - صفحة إضافة مقال
- `show.blade.php` - صفحة عرض مقال واحد
- `edit.blade.php` - صفحة تعديل مقال

---

### 1️⃣ ملف index.blade.php

```html
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كل المقالات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .post-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .post-title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .post-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .post-actions {
            display: flex;
            gap: 10px;
        }
        .btn-edit {
            background: #ffc107;
            color: black;
        }
        .btn-delete {
            background: #dc3545;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 كل المقالات</h1>
        <a href="{{ route('posts.create') }}" class="btn">+ إضافة مقال جديد</a>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if($posts->count() > 0)
        @foreach($posts as $post)
            <div class="post-card">
                <h2 class="post-title">{{ $post->title }}</h2>
                <div class="post-meta">
                    بواسطة: {{ $post->author }} | 
                    {{ $post->created_at->format('Y-m-d') }}
                </div>
                <p>{{ Str::limit($post->content, 150) }}</p>
                
                <div class="post-actions">
                    <a href="{{ route('posts.show', $post->id) }}" class="btn">
                        عرض المقال
                    </a>
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-edit">
                        تعديل
                    </a>
                    <form action="{{ route('posts.destroy', $post->id) }}" 
                          method="POST" 
                          style="display: inline;"
                          onsubmit="return confirm('متأكد من الحذف؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">حذف</button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <p>مفيش مقالات حالياً. ابدأ بإضافة مقال جديد!</p>
    @endif
</body>
</html>
```

**شرح الكود:**
- `@if(session('success'))` - لو فيه رسالة نجاح، اعرضها
- `@foreach($posts as $post)` - اعمل Loop على كل المقالات
- `Str::limit($post->content, 150)` - اعرض أول 150 حرف بس
- `@csrf` - توكن أمان (ضروري!)
- `@method('DELETE')` - عشان نبعت DELETE request

---

### 2️⃣ ملف create.blade.php

```html
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مقال جديد</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        .btn {
            padding: 12px 30px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background: #218838;
        }
        .btn-back {
            background: #6c757d;
            margin-left: 10px;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>✍️ إضافة مقال جديد</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title">عنوان المقال *</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   value="{{ old('title') }}"
                   placeholder="اكتب عنوان المقال هنا...">
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="author">اسم الكاتب *</label>
            <input type="text" 
                   name="author" 
                   id="author" 
                   value="{{ old('author') }}"
                   placeholder="اكتب اسمك هنا...">
            @error('author')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">محتوى المقال *</label>
            <textarea name="content" 
                      id="content" 
                      placeholder="اكتب محتوى المقال هنا...">{{ old('content') }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn">حفظ المقال</button>
        <a href="{{ route('posts.index') }}" class="btn btn-back">رجوع</a>
    </form>
</body>
</html>
```

**شرح الكود:**
- `old('title')` - لو فيه خطأ، يحتفظ بالبيانات اللي كتبتها
- `@error('title')` - لو فيه خطأ في الحقل، اعرضه
- `@csrf` - توكن الأمان (لازم يكون في كل Form)

---

### 3️⃣ ملف show.blade.php

```html
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.8;
        }
        .post-header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .post-title {
            font-size: 36px;
            margin-bottom: 15px;
        }
        .post-meta {
            color: #666;
            font-size: 16px;
        }
        .post-content {
            font-size: 18px;
            margin-bottom: 40px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .btn-back {
            background: #6c757d;
            color: white;
        }
        .btn-edit {
            background: #ffc107;
            color: black;
        }
    </style>
</head>
<body>
    <div class="post-header">
        <h1 class="post-title">{{ $post->title }}</h1>
        <div class="post-meta">
            بواسطة: <strong>{{ $post->author }}</strong> | 
            تاريخ النشر: {{ $post->created_at->format('d/m/Y') }}
        </div>
    </div>

    <div class="post-content">
        {!! nl2br(e($post->content)) !!}
    </div>

    <a href="{{ route('posts.index') }}" class="btn btn-back">رجوع للمقالات</a>
    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-edit">تعديل المقال</a>
</body>
</html>
```

**شرح `nl2br(e($post->content))`:**
- `e()` - بيحمي من XSS attacks
- `nl2br()` - بيحول الأسطر الجديدة لـ `<br>`

---

### 4️⃣ ملف edit.blade.php

```html
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المقال</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        .btn {
            padding: 12px 30px;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-save {
            background: #28a745;
        }
        .btn-save:hover {
            background: #218838;
        }
        .btn-back {
            background: #6c757d;
            margin-left: 10px;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>✏️ تعديل المقال</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title">عنوان المقال *</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   value="{{ old('title', $post->title) }}">
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="author">اسم الكاتب *</label>
            <input type="text" 
                   name="author" 
                   id="author" 
                   value="{{ old('author', $post->author) }}">
            @error('author')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">محتوى المقال *</label>
            <textarea name="content" 
                      id="content">{{ old('content', $post->content) }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-save">حفظ التعديلات</button>
        <a href="{{ route('posts.index') }}" class="btn btn-back">إلغاء</a>
    </form>
</body>
</html>
```

**الفرق عن create:**
- `@method('PUT')` - عشان نبعت PUT request
- `old('title', $post->title)` - لو فيه خطأ يعرض اللي كتبته، لو لا يعرض القيمة القديمة

---

## 🎯 تجربة النظام

### 1️⃣ شغّل السيرفر

```bash
php artisan serve
```

### 2️⃣ افتح المتصفح

```
http://127.0.0.1:8000/posts
```

### 3️⃣ جرب العمليات:

✅ **إضافة مقال:**
- اضغط "إضافة مقال جديد"
- املا البيانات
- اضغط "حفظ المقال"

✅ **عرض المقالات:**
- هتشوف كل المقالات في الصفحة الرئيسية

✅ **عرض مقال واحد:**
- اضغط "عرض المقال"

✅ **تعديل مقال:**
- اضغط "تعديل"
- عدّل البيانات
- اضغط "حفظ التعديلات"

✅ **حذف مقال:**
- اضغط "حذف"
- وافق على الحذف

---

## 🎨 تحسينات إضافية (اختياري)

### إضافة Pagination (ترقيم الصفحات)

لو عندك مقالات كتير، غيّر دالة `index`:

```php
public function index()
{
    // بدل all() استخدم paginate
    $posts = Post::latest()->paginate(10);
    
    return view('posts.index', compact('posts'));
}
```

وفي ملف `index.blade.php` ضيف في الآخر:

```html
{{ $posts->links() }}
```

---

### إضافة البحث

في دالة `index`:

```php
public function index(Request $request)
{
    $query = Post::query();
    
    if ($request->has('search')) {
        $search = $request->search;
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
    }
    
    $posts = $query->latest()->paginate(10);
    
    return view('posts.index', compact('posts'));
}
```

وفي `index.blade.php` ضيف فورم بحث:

```html
<form action="{{ route('posts.index') }}" method="GET">
    <input type="text" name="search" placeholder="ابحث عن مقال...">
    <button type="submit">بحث</button>
</form>
```

---

## 🐛 حل المشاكل الشائعة

### المشكلة: "Route [posts.index] not defined"
**الحل:** تأكد إنك ضفت الـ Route في `web.php`

### المشكلة: "Class 'Post' not found"
**الحل:** تأكد إنك عملت `use App\Models\Post;` في الـ Controller

### المشكلة: "Column not found"
**الحل:** تأكد إنك عملت migrate:
```bash
php artisan migrate:fresh
```

### المشكلة: "Mass assignment exception"
**الحل:** تأكد من وجود `$fillable` في الـ Model

---

## 📊 ملخص سريع

### الملفات المهمة:

```
📁 المشروع
├── 📁 app/
│   ├── 📁 Http/Controllers/
│   │   └── PostController.php (المتحكم)
│   └── 📁 Models/
│       └── Post.php (الموديل)
├── 📁 database/migrations/
│   └── xxxx_create_posts_table.php (تصميم الجدول)
├── 📁 resources/views/posts/
│   ├── index.blade.php (عرض كل المقالات)
│   ├── create.blade.php (إضافة مقال)
│   ├── show.blade.php (عرض مقال واحد)
│   └── edit.blade.php (تعديل مقال)
└── 📁 routes/
    └── web.php (الروابط)
```

---

## 🎓 مفاهيم مهمة تعلمتها

✅ **Migration** - تصميم الجداول  
✅ **Model** - التعامل مع الداتابيز  
✅ **Controller** - المنطق والتحكم  
✅ **Routes** - تحديد المسارات  
✅ **Views** - صفحات العرض  
✅ **Validation** - التحقق من البيانات  
✅ **CRUD Operations** - العمليات الأساسية  

---

## 🚀 الخطوات التالية

بعد ما تتقن النظام ده، تقدر تضيف:
- 🔐 نظام تسجيل دخول (Auth)
- 📷 رفع صور للمقالات
- 🏷️ تصنيفات وتاجات
- 💬 نظام تعليقات
- ⭐ تقييمات المقالات
- 🔍 بحث متقدم

---

**مبروك! 🎉 دلوقتي عندك نظام CRUD كامل شغال!**

صُنع بحب ❤️ لكل مطور مبتدئ في رحلة Laravel

</div>
