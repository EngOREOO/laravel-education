# Laravel MVC بالتفصيل الشامل

### محاضرة تجميع وربط نهائي لكل الأساسيات من أول الطلب لحد رجوع النتيجة للمستخدم

---

## مقدمة

إحنا الآن في نقطة مهمة جدًا في الخطة الدراسية.

أنت لم تعد تتعلم أجزاء منفصلة:

- route لوحدها
- controller لوحدها
- model لوحدها
- view لوحدها

أنت الآن لازم تبدأ تشوف Laravel كمنظومة كاملة.

وده هو الهدف الحقيقي من درس:

## MVC الشامل

هذا الدرس ليس مجرد تعريف نظري.

بل هو درس يجاوب على أسئلة مثل:

- الطلب بيمشي إزاي داخل Laravel؟
- route دورها فين وينتهي عند فين؟
- controller المفروض تعمل إيه وماتعملش إيه؟
- model مسؤولة عن إيه؟
- view تعرض إيه فقط؟
- ليه فصل المسؤوليات مهم؟
- إيه اللي يحصل لو خلطت كل حاجة ببعض؟

---

## أول سؤال: يعني إيه MVC أصلًا؟

MVC اختصار:

- Model
- View
- Controller

وهو pattern أو أسلوب تنظيم للكود.

الفكرة ليست أن الثلاث كلمات "مهمة" فقط.

الفكرة الحقيقية هي:

> بدل ما نرمي كل الكود في مكان واحد، نفصل المسؤوليات بحيث كل جزء يعمل وظيفة محددة وواضحة.

---

## لو ما استخدمناش MVC هيحصل إيه؟

تخيل عندك ملف واحد فيه:

- routes
- HTML
- SQL
- validation
- redirects
- business logic

بعد شهر واحد فقط:

- هتتوه
- هتبدأ التعديلات تكسر أشياء أخرى
- الصيانة هتبقى متعبة
- الكود يبقى صعب جدًا على أي حد جديد

إذًا MVC ليس رفاهية.

هو أسلوب بيساعدك تبني مشروع:

- مفهوم
- قابل للصيانة
- واضح
- قابل للتوسع

---

## المعنى العملي لكل جزء

### 1. Model

تمثل البيانات.

يعني:

- جدول في قاعدة البيانات
- علاقاته
- طرق التعامل معه
- بعض المنطق القريب من البيانات

---

### 2. View

تمثل ما يراه المستخدم.

يعني:

- HTML
- Blade
- عرض البيانات
- رسائل الأخطاء
- روابط الأزرار

---

### 3. Controller

تمثل المنسق.

يعني:

- تستقبل الطلب
- تعمل validation أو تستخدم Form Request
- تستدعي model أو service
- ترجع response

---

## الجملة الذهبية للمبتدئ

> Model = البيانات  
> View = العرض  
> Controller = التنسيق

---

# الجزء الأول: الصورة الكبيرة لدورة الطلب

## لما المستخدم يفتح صفحة، ماذا يحدث؟

خلينا نمشي خطوة خطوة.

مثال:

المستخدم فتح:

```text
/posts
```

Laravel تعمل تقريبًا هذا:

1. تستقبل الطلب.
2. تبحث عن route مناسبة.
3. route تحدد controller المناسبة.
4. controller تنفذ المنطق المطلوب.
5. controller تطلب البيانات من model.
6. model تجلب البيانات من database.
7. controller ترجع البيانات إلى view.
8. view تخرج HTML.
9. المتصفح يعرض الصفحة للمستخدم.

---

## الشكل المختصر

```text
User
↓
Route
↓
Controller
↓
Model
↓
Database
↓
Model
↓
Controller
↓
View
↓
HTML Response
↓
User
```

وده فعليًا قلب Laravel web apps.

---

# الجزء الثاني: فهم الـ Route داخل MVC

## route دورها إيه؟

route لا تحفظ في الداتابيز.
route لا تعرض HTML.
route لا تكتب business logic معقدة.

وظيفتها:

> تقول Laravel: لو الطلب ده جه، ابعته للمكان ده.

مثال:

```php
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
```

يعني:

- لو الطلب GET
- واللينك `/posts`
- روح نفذ `PostController@index`

---

## هل route جزء من MVC؟

تقنيًا MVC هي:

- model
- view
- controller

لكن في Laravel route هي البوابة التي توصل الطلب إلى الـ controller.

فهي ليست واحدًا من M أو V أو C،
لكنها بداية الرحلة.

---

# الجزء الثالث: فهم الـ Controller داخل MVC

## controller دورها الحقيقي

الـ controller لا يفترض أن تكون مكان كل شيء.

هي فقط:

- تفهم الطلب
- تنسق التنفيذ
- ترجع response

مثال:

```php
public function index(): View
{
    $posts = Post::latest()->paginate(10);

    return view('posts.index', compact('posts'));
}
```

### ما الذي فعلته controller هنا؟

- طلبت البيانات من model
- أعادت view

ولم:

- تكتب HTML
- تكتب SQL خام
- تضع منطقًا عشوائيًا ضخمًا

وده هو الشكل النظيف.

---

## controller في الحفظ

مثال:

```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
    ]);

    Post::create($validated);

    return redirect()->route('posts.index');
}
```

### هنا controller:

- استقبلت request
- تحققت من البيانات
- حفظت عبر model
- رجعت redirect

وده بالضبط تنسيق السيناريو.

---

## متى تكون controller سيئة؟

لما تتحول إلى مكان:

- validation ضخمة جدًا
- file handling معقد
- business rules كثيرة
- notifications
- payments
- reports
- كل شيء معًا

وقتها controller تصبح fat controller.

وده ضد clean code.

---

# الجزء الرابع: فهم الـ Model داخل MVC

## model دورها الحقيقي

الـ model تمثل الجدول والبيانات.

هي المكان الطبيعي لـ:

- العلاقات
- scopes
- casts
- fillable
- accessors / mutators البسيطة
- التعامل مع قاعدة البيانات عبر Eloquent

مثال:

```php
class Post extends Model
{
    protected $fillable = ['title', 'content', 'author'];
}
```

---

## model ليست controller

يعني لا نحشر داخل model كل business rules المعقدة بلا سبب.

لكن أيضًا لا نتركها فارغة دائمًا.

لازم تفهم التوازن:

- منطق قريب من البيانات → model
- تنسيق السيناريو → controller
- منطق domain معقد → service / action / domain class

---

## مثال علاقة داخل model

```php
class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
```

هذا مكانها الطبيعي.

---

# الجزء الخامس: فهم الـ View داخل MVC

## view دورها الحقيقي

الـ view تعرض البيانات.

يعني:

- HTML
- Blade directives
- loops
- conditions بسيطة
- forms
- session messages

لكن لا تضع فيها:

- database queries
- business logic ضخمة
- شغل ليس له علاقة بالعرض

---

## مثال view

```blade
@foreach ($posts as $post)
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->author }}</p>
@endforeach
```

دي view تقوم بدورها:

- تستقبل البيانات
- تعرضها

فقط.

---

# الجزء السادس: مثال عملي كامل يربط الثلاثة

## السيناريو

عايزين نعرض كل المقالات.

### route

```php
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
```

### controller

```php
use App\Models\Post;
use Illuminate\View\View;

public function index(): View
{
    $posts = Post::query()
        ->latest()
        ->paginate(10);

    return view('posts.index', compact('posts'));
}
```

### model

```php
class Post extends Model
{
    protected $fillable = ['title', 'content', 'author'];
}
```

### view

```blade
<h1>كل المقالات</h1>

@foreach ($posts as $post)
    <article>
        <h2>{{ $post->title }}</h2>
        <p>بواسطة: {{ $post->author }}</p>
    </article>
@endforeach
```

---

## ما الذي حدث هنا؟

### route

قالت:

> الطلب ده يروح لـ `index`

### controller

قالت:

> سأجلب المقالات وأرجع view

### model

قالت:

> أنا سأتعامل مع جدول posts

### view

قالت:

> سأعرض المقالات للمستخدم

وده MVC في أبسط صورة عملية.

---

# الجزء السابع: مثال CRUD كامل من منظور MVC

## Create

### route

```php
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
```

### controller

```php
public function create(): View
{
    return view('posts.create');
}

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

### view

```blade
<form action="{{ route('posts.store') }}" method="POST">
    @csrf

    <input type="text" name="title" value="{{ old('title') }}">
    <textarea name="content">{{ old('content') }}</textarea>
    <input type="text" name="author" value="{{ old('author') }}">

    <button type="submit">حفظ</button>
</form>
```

---

## Read

### list

```php
public function index(): View
{
    $posts = Post::latest()->paginate(10);

    return view('posts.index', compact('posts'));
}
```

### show one

```php
public function show(Post $post): View
{
    return view('posts.show', compact('post'));
}
```

---

## Update

### controller

```php
public function edit(Post $post): View
{
    return view('posts.edit', compact('post'));
}

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

## Delete

```php
public function destroy(Post $post): RedirectResponse
{
    $post->delete();

    return redirect()
        ->route('posts.index')
        ->with('success', 'تم حذف المقال بنجاح');
}
```

---

# الجزء الثامن: أين نضع الكود؟ سؤال مهم جدًا

## هل أضع الاستعلام في route؟

في المشاريع الصغيرة جدًا ممكن.

لكن في الشغل النظيف:

> لا

ضعه في controller أو service أو model scope حسب السياق.

---

## هل أضع HTML في controller؟

لا.

الـ HTML مكانها view.

---

## هل أضع relation methods في controller؟

لا.

مكانها الطبيعي model.

---

## هل أضع validation في view؟

لا.

التحقق يكون في:

- controller
- أو Form Request

لكن view تعرض errors فقط.

---

## هل أضع business logic معقدة في controller؟

يفضل لا.

لو logic كبيرة:

- services
- actions
- domain classes

قد تكون أنسب.

---

# الجزء التاسع: الأخطاء الشائعة عندما لا نفهم MVC

## 1. Route فيها كل شيء

مثال سيء:

```php
Route::post('/posts', function (Request $request) {
    $request->validate([...]);
    Post::create($request->all());
    return redirect('/posts');
});
```

ده يشتغل، لكنه ليس منهجًا نظيفًا للتطبيقات الجدية.

---

## 2. controller فيها HTML

مثال سيء:

```php
return "<h1>Hello</h1>";
```

مسموح تقنيًا.
لكن view أنسب بكثير.

---

## 3. view تعمل استعلامات

مثال سيء:

```blade
@php
    $posts = \App\Models\Post::all();
@endphp
```

هذا يخلط العرض مع البيانات.

---

## 4. model فارغة تمامًا رغم وجود علاقات ومنطق واضح

ده ليس خطأ تقنيًا،
لكن تفويت لميزة Eloquent.

---

## 5. controller ضخمة جدًا

يعني:

- validation
- رفع ملفات
- mail
- logs
- business rules
- formatting
- redirects

كلها في method واحدة.

وده ضد clean code.

---

# الجزء العاشر: Best Practices في MVC داخل Laravel

## 1. خليك واضحًا في المسؤوليات

- route توجه
- controller تنسق
- model تتعامل مع البيانات
- view تعرض

---

## 2. استخدم Resource Controllers عندما يكون عندك CRUD

ده يجعل الكود:

- أوضح
- أكثر standard
- أسهل للطلاب والفريق

---

## 3. استخدم Route Model Binding

بدل:

```php
Post::findOrFail($id)
```

في كل method، استخدم:

```php
public function show(Post $post)
```

---

## 4. استخدم Form Requests عندما تكبر validation

في البداية:

```php
$request->validate([...]);
```

مقبولة تعليميًا.

لاحقًا:

```php
StorePostRequest
UpdatePostRequest
```

أفضل.

---

## 5. استخدم eager loading عند الحاجة

خصوصًا في العلاقات داخل القوائم.

---

## 6. لا تستخدم `request()->all()` بعشوائية

دي من النقاط المهمة جدًا في المنهج.

الأفضل:

```php
$validated = $request->validate([...]);
Model::create($validated);
```

---

## 7. اجعل الـ views بسيطة

مش بسيطة بمعنى "فقيرة".

بل:

- منطق عرض واضح
- loops
- conditions بسيطة
- layout وpartials وcomponents عند الحاجة

---

# الجزء الحادي عشر: أسئلة المبتدئ

## هل MVC شيء خاص بـ Laravel؟

لا.

هو pattern معماري معروف،
لكن Laravel تطبقه بشكل واضح ومريح.

---

## هل controller لازم دائمًا ترجع view؟

لا.

ممكن ترجع:

- view
- redirect
- json
- file

على حسب السيناريو.

---

## هل model لازم تكون مرتبطة بجدول؟

في الغالب نعم في Laravel التقليدية.

---

## هل view تعرف model مباشرة؟

يفضل لا.

view يفترض أن تستقبل البيانات من controller.

---

## هل route جزء من MVC؟

مش واحد من M أو V أو C،
لكنها البوابة التي تربط الطلب بالـ controller.

---

## هل ينفع مشروع Laravel يشتغل بدون فهم MVC؟

ممكن تكتب كودًا "يشتغل".

لكن بناء مشروع منظم وقابل للصيانة بدون فهم MVC سيكون صعبًا جدًا.

---

# الجزء الثاني عشر: مثال مقارنة بين كود منظم وكود غير منظم

## مثال غير منظم

```php
Route::post('/posts', function (Request $request) {
    $request->validate([
        'title' => 'required',
        'content' => 'required',
    ]);

    $post = new Post();
    $post->title = $request->title;
    $post->content = $request->content;
    $post->author = $request->author;
    $post->save();

    return "<h1>Done</h1>";
});
```

### المشاكل

- route فيها منطق كثير
- لا يوجد controller
- لا يوجد view
- الرد غير منظم

---

## مثال منظم

### route

```php
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
```

### controller

```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'author' => ['required', 'string', 'max:100'],
    ]);

    Post::create($validated);

    return redirect()->route('posts.index');
}
```

### view

الفورم في ملف Blade مستقل.

### model

فيها `$fillable`.

وده شكل محترم.

---

# الجزء الثالث عشر: MVC و Clean Code

MVC تساعدك جدًا في clean code لأنها:

- تقلل التشتت
- تجعل مكان كل شيء معروفًا
- تمنع التكرار العشوائي
- تسهل المراجعة والاختبار

لكن MVC وحدها لا تكفي.

لأن ممكن تكتب:

- fat controllers
- messy views
- empty models

فلازم تستخدم MVC بفهم.

---

## القاعدة الذهبية هنا

> MVC ليست مجرد تقسيم ملفات، بل تقسيم مسؤوليات.

---

# الجزء الرابع عشر: خريطة ذهنية نهائية

لو سألت نفسك:

### "أنا أكتب هذا الكود فين؟"

اسأل:

#### هل الكود يحدد أين يذهب الطلب؟

إذًا:

- route

#### هل الكود ينسق السيناريو؟

إذًا:

- controller

#### هل الكود يخص البيانات والعلاقات؟

إذًا:

- model

#### هل الكود يخص العرض للمستخدم؟

إذًا:

- view

---

# الجزء الخامس عشر: الملخص النهائي

## MVC في Laravel باختصار

هو الطريقة التي تجعل التطبيق:

- منظمًا
- واضحًا
- قابلًا للصيانة
- قابلًا للتوسع

من خلال توزيع المسؤوليات بين:

- Model
- View
- Controller

مع دور route كبوابة أولى للطلب.

---

## الجملة الذهبية

لو عايز تحفظ الدرس كله في سطر واحد:

> Laravel MVC ليس مجرد أسماء لملفات، بل طريقة تفكير تنظّم مسار الطلب من المستخدم إلى قاعدة البيانات ثم العودة إلى واجهة العرض بشكل واضح ونظيف.

---

## بعد هذا الدرس

أنت الآن أنهيت مرحلة الأساسيات المترابطة.

والخطوة المنطقية التالية في الخطة ستكون الانتقال التدريجي إلى:

- التوثيق الرسمي
- أو الموضوعات الأعلى مثل auth / testing / advanced architecture

بحسب ترتيب المنهج الذي بنيناه.
