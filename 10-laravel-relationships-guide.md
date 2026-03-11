<div dir="rtl">

# شرح العلاقات بين الجداول في Laravel

### محاضرة تفصيلية جدًا جدًا للمبتدئ عن Eloquent Relationships من الصفر حتى الفهم الحقيقي

---

## قبل ما نبدأ

أنت فهمت في الدرس اللي فات:

- يعني إيه Model
- إزاي الـ model تمثل جدول
- إزاي نقرأ وننشئ ونحدّث ونحذف

لكن الحياة الحقيقية لا يوجد فيها جدول يعيش وحده.

في أي تطبيق تقريبًا ستجد:

- مستخدم له طلبات
- مقال له تعليقات
- منتج ينتمي إلى فئة
- طالب يدرس مواد
- مستخدم له بروفايل

وهنا تظهر:

## العلاقات

---

## السؤال الأول: يعني إيه Relationship أصلًا؟

الـ Relationship هي العلاقة المنطقية بين جدول وجدول.

يعني:

- هذا السجل مرتبط بهذا السجل
- هذا الكيان يتبع هذا الكيان
- هذا الكيان له مجموعة من الكيانات الأخرى

بمعنى أبسط:

> العلاقة هي الطريقة التي نربط بها البيانات ببعض بدل ما تبقى كل البيانات منفصلة عن بعضها.

---

## ليه العلاقات مهمة جدًا؟

لأن من غير العلاقات:

- البيانات هتكون مكررة
- الجداول هتكون غير منظمة
- الاستعلامات هتبقى أصعب
- المنطق هيبقى أقل وضوحًا

مثال:

لو عندك جدول posts وجدول users،
هل الأفضل أن نكرر اسم المستخدم داخل كل post؟

لا.

الأفضل نربط post بالمستخدم.

يعني:

- post فيها `user_id`
- ثم نعرف أن المقال يعود لمستخدم معين

وده هو معنى العلاقة.

---

## السؤال الثاني: العلاقة بتتكتب فين؟

العلاقة لها جزآن:

### 1. جزء في قاعدة البيانات

يعني:

- foreign key
- pivot table
- constraints

وده يتكتب في:

- migrations

---

### 2. جزء في الـ Model

وده يتكتب في:

- Eloquent models

مثل:

- `belongsTo`
- `hasMany`
- `hasOne`
- `belongsToMany`

يعني:

> migration تبني الربط في قاعدة البيانات
> وmodel تشرح Laravel كيف تستخدم هذا الربط في الكود

---

## السؤال الثالث: ما أكثر أنواع العلاقات شيوعًا؟

في Laravel، أهم الأنواع التي تحتاجها فعليًا في البداية:

1. One to One
2. One to Many
3. Many to Many
4. Has Many Through
5. Polymorphic

لكن لو أنت مبتدئ:

الأهم جدًا أن تتقن أول 3 علاقات بإحكام.

---

# الجزء الأول: One to One

## يعني إيه One to One؟

يعني:

> سجل واحد يقابله سجل واحد فقط

### مثال من الحياة

- شخص واحد له جواز سفر واحد
- مستخدم واحد له بروفايل واحد
- طالب واحد له ملف شخصي واحد

---

## المثال البرمجي الأشهر: User و Profile

### الفكرة

- كل مستخدم له profile واحدة
- وكل profile تخص مستخدمًا واحدًا فقط

---

## السؤال: أين نضع المفتاح الأجنبي؟

القاعدة المهمة جدًا:

> المفتاح الأجنبي يوضع في الجدول التابع

في مثالنا:

- `users` هو الجدول الأساسي
- `profiles` هو الجدول التابع

إذًا:

جدول `profiles` يحتوي:

```text
user_id
```

---

## شكل الجداول

### users

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamps();
});
```

### profiles

```php
Schema::create('profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
        ->constrained()
        ->onDelete('cascade');
    $table->text('bio')->nullable();
    $table->string('avatar')->nullable();
    $table->string('phone')->nullable();
    $table->timestamps();
});
```

---

## كتابة العلاقة في الـ Models

### داخل User

```php
public function profile()
{
    return $this->hasOne(Profile::class);
}
```

### داخل Profile

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

---

## السؤال: لماذا `hasOne` هنا و`belongsTo` هناك؟

دي من أهم النقاط.

### في `User`

المستخدم "عنده" profile:

```php
hasOne
```

### في `Profile`

الـ profile "تنتمي إلى" user:

```php
belongsTo
```

إذًا:

- الجدول الأساسي يقول `hasOne`
- الجدول الذي يحمل المفتاح الأجنبي يقول `belongsTo`

---

## الاستخدام

```php
$user = User::find(1);

echo $user->name;
echo $user->profile->bio;
echo $user->profile->phone;
```

وعكسها:

```php
$profile = Profile::find(1);

echo $profile->user->name;
echo $profile->user->email;
```

---

## إنشاء profile لمستخدم

```php
$user = User::find(1);

$user->profile()->create([
    'bio' => 'Laravel Developer',
    'phone' => '01000000000',
]);
```

### السؤال: لماذا استخدمنا `profile()` وليس `profile`؟

لأن:

- `profile()` method relationship
- `profile` property للوصول للنتيجة

في الإنشاء والحفظ نستخدم method:

```php
$user->profile()->create(...)
```

وفي القراءة نستخدم property:

```php
$user->profile
```

---

# الجزء الثاني: One to Many

## يعني إيه One to Many؟

يعني:

> سجل واحد يقابله عدة سجلات

### مثال من الحياة

- مستخدم واحد له مقالات كثيرة
- فئة واحدة لها منتجات كثيرة
- مقال واحد له تعليقات كثيرة

---

## المثال الأشهر: User و Posts

### الفكرة

- المستخدم الواحد يكتب مقالات كثيرة
- كل مقال يتبع مستخدمًا واحدًا فقط

---

## أين نضع المفتاح الأجنبي؟

في الجدول التابع.

يعني:

جدول `posts` يحتوي:

```text
user_id
```

---

## الجداول

### users

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

### posts

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
        ->constrained()
        ->onDelete('cascade');
    $table->string('title');
    $table->text('content');
    $table->timestamps();
});
```

---

## العلاقات في الـ Models

### داخل User

```php
public function posts()
{
    return $this->hasMany(Post::class);
}
```

### داخل Post

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

---

## السؤال: لماذا `posts()` جمع و`user()` مفرد؟

لأن:

- المستخدم له عدة posts → جمع
- المقال ينتمي إلى user واحدة → مفرد

وده ليس مجرد شكل لغوي.

ده مهم جدًا لفهم نوع العلاقة.

---

## الاستخدام

```php
$user = User::find(1);

foreach ($user->posts as $post) {
    echo $post->title;
}
```

وعكسها:

```php
$post = Post::find(1);

echo $post->user->name;
```

---

## إنشاء post لمستخدم

```php
$user = User::find(1);

$user->posts()->create([
    'title' => 'أول مقال',
    'content' => 'محتوى المقال',
]);
```

---

## السؤال: لماذا دي أفضل من إني أكتب `user_id` يدويًا؟

لأن:

```php
$user->posts()->create([...])
```

تجعل العلاقة أوضح.

وغالبًا Laravel تضبط `user_id` تلقائيًا.

وده أنظف من:

```php
Post::create([
    'user_id' => $user->id,
    'title' => '...',
]);
```

رغم أن الثانية صحيحة أيضًا.

---

## مثال مهم جدًا: Post و Comments

### داخل Post

```php
public function comments()
{
    return $this->hasMany(Comment::class);
}
```

### داخل Comment

```php
public function post()
{
    return $this->belongsTo(Post::class);
}
```

### الاستخدام

```php
$post = Post::find(1);

foreach ($post->comments as $comment) {
    echo $comment->content;
}
```

---

# الجزء الثالث: Many to Many

## يعني إيه Many to Many؟

يعني:

> عدة سجلات من الجدول الأول ترتبط بعدة سجلات من الجدول الثاني

### مثال من الحياة

- طالب يدرس مواد كثيرة
- المادة يدرسها طلاب كثيرون

أو:

- مستخدم له أدوار كثيرة
- الدور له مستخدمون كثيرون

أو:

- منتج له وسوم كثيرة
- الوسم له منتجات كثيرة

---

## السؤال: لماذا لا يكفي foreign key واحد هنا؟

لأننا عندنا "كثير إلى كثير".

لو وضعت:

```text
course_id
```

داخل students فقط،
فلن تستطيع تمثيل أن الطالب يدرس أكثر من مادة.

ولو وضعت:

```text
student_id
```

داخل courses فقط،
فلن تستطيع تمثيل أن المادة يدرسها أكثر من طالب.

إذًا نحتاج:

## Pivot Table

---

## ما هي Pivot Table؟

هي جدول وسيط يحتوي على مفاتيح الجدولين.

مثال:

```text
course_student
```

وفيه:

- `student_id`
- `course_id`

وقد يحتوي بيانات إضافية مثل:

- grade
- enrolled_at

---

## مثال كامل: Students و Courses

### students

```php
Schema::create('students', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

### courses

```php
Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

### pivot table

```php
Schema::create('course_student', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')
        ->constrained()
        ->onDelete('cascade');
    $table->foreignId('course_id')
        ->constrained()
        ->onDelete('cascade');
    $table->integer('grade')->nullable();
    $table->timestamps();
});
```

---

## السؤال: لماذا اسم الجدول `course_student`؟

لأن Laravel convention غالبًا:

- اسمَي الجدولين بالمفرد
- مرتبين أبجديًا

لكن لو استخدمت اسمًا مختلفًا تقدر تحدده يدويًا.

---

## العلاقات في الـ Models

### Student

```php
public function courses()
{
    return $this->belongsToMany(Course::class);
}
```

### Course

```php
public function students()
{
    return $this->belongsToMany(Student::class);
}
```

---

## الاستخدام

```php
$student = Student::find(1);

foreach ($student->courses as $course) {
    echo $course->name;
}
```

وعكسها:

```php
$course = Course::find(1);

foreach ($course->students as $student) {
    echo $student->name;
}
```

---

## attach

```php
$student->courses()->attach(1);
```

يعني:

سجل الطالب في المادة رقم 1.

---

## attach أكثر من واحد

```php
$student->courses()->attach([1, 2, 3]);
```

---

## detach

```php
$student->courses()->detach(1);
```

يعني:

ألغِ الربط بين الطالب وهذه المادة.

---

## sync

```php
$student->courses()->sync([1, 2, 3]);
```

يعني:

خلي المواد الحالية لهذا الطالب هي فقط:

- 1
- 2
- 3

أي شيء غيرها يُزال.

---

## بيانات إضافية في pivot

لو عندك `grade` في الجدول الوسيط:

### model

```php
public function courses()
{
    return $this->belongsToMany(Course::class)
        ->withPivot('grade')
        ->withTimestamps();
}
```

### الاستخدام

```php
foreach ($student->courses as $course) {
    echo $course->name;
    echo $course->pivot->grade;
}
```

---

## attach مع بيانات إضافية

```php
$student->courses()->attach(1, ['grade' => 95]);
```

---

# الجزء الرابع: Has Many Through

## يعني إيه؟

علاقة تجيب لك سجلات "عبر" جدول وسيط، لكن من غير ما تتعامل أنت مع الجدول الوسيط مباشرة كل مرة.

### مثال

- دولة لها مستخدمون كثيرون
- المستخدمون لهم مقالات كثيرة

فنريد:

> كل مقالات الدولة

---

## الجداول

### countries

```php
Schema::create('countries', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

### users

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('country_id')->constrained();
    $table->string('name');
    $table->timestamps();
});
```

### posts

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('title');
    $table->timestamps();
});
```

---

## داخل Country

```php
public function users()
{
    return $this->hasMany(User::class);
}

public function posts()
{
    return $this->hasManyThrough(Post::class, User::class);
}
```

---

## الاستخدام

```php
$country = Country::find(1);

foreach ($country->posts as $post) {
    echo $post->title;
}
```

### السؤال: متى أستخدمها؟

لما تكون العلاقة:

- واضحة
- ثابتة
- ومتكررة الاستخدام

---

# الجزء الخامس: Polymorphic Relationships

## لماذا نحتاجها؟

تخيل إن عندك تعليقات.

والتعليق ممكن يكون على:

- post
- video
- image

هل تعمل:

- `post_comments`
- `video_comments`
- `image_comments`

غالبًا لأ.

هنا polymorphic ممتازة.

---

## الفكرة

نعمل جدول comments واحد،
ونخزن فيه:

- `commentable_id`
- `commentable_type`

---

## migration

```php
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->text('content');
    $table->morphs('commentable');
    $table->timestamps();
});
```

`morphs('commentable')` تعمل غالبًا:

- `commentable_id`
- `commentable_type`

---

## Comment model

```php
public function commentable()
{
    return $this->morphTo();
}
```

### Post model

```php
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

### Video model

```php
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

---

## الاستخدام

```php
$post = Post::find(1);

$post->comments()->create([
    'content' => 'تعليق ممتاز',
]);
```

أو:

```php
$comment = Comment::find(1);

echo $comment->commentable_type;
echo $comment->commentable->id;
```

---

# الجزء السادس: Eager Loading مع العلاقات

## المشكلة الشهيرة: N+1

مثال:

```php
$posts = Post::all();

foreach ($posts as $post) {
    echo $post->user->name;
}
```

ده غالبًا يسبب استعلامات كثيرة.

---

## الحل

```php
$posts = Post::with('user')->get();
```

أو:

```php
$posts = Post::with(['user', 'comments'])->get();
```

---

## قاعدة عملية

لو تعرف أنك ستحتاج العلاقة:

> اعمل eager loading من البداية

---

# الجزء السابع: العد والفلترة بدون تحميل كل شيء

## مثال سيء

```php
$commentsCount = $post->comments->count();
```

ده يحمل كل التعليقات ثم يعدها.

---

## الأفضل

```php
$commentsCount = $post->comments()->count();
```

---

## مثال آخر

```php
$approvedCommentsCount = $post->comments()
    ->where('is_approved', true)
    ->count();
```

---

# الجزء الثامن: متى أستخدم أي نوع علاقة؟

## استخدم One to One عندما

- كل سجل يقابله سجل واحد فقط
- مثال: user/profile

---

## استخدم One to Many عندما

- سجل واحد له عدة سجلات
- مثال: user/posts

---

## استخدم Many to Many عندما

- كل طرف يرتبط بعدة عناصر من الطرف الآخر
- مثال: students/courses

---

## استخدم Has Many Through عندما

- تريد الوصول لسجلات عبر جدول وسيط منطقي
- مثال: country/posts عبر users

---

## استخدم Polymorphic عندما

- نفس الكيان يتبع أكثر من نوع model
- مثال: comments على post أو video أو image

---

# الجزء التاسع: Best Practices مهمة جدًا

## 1. اسم العلاقة يجب أن يعبّر عن نتيجتها

صح:

```php
public function posts()
public function profile()
public function user()
```

غلط:

```php
public function postData()
public function myUserThing()
```

---

## 2. الجمع والمفرد مهمان

- `hasMany` → جمع
- `belongsTo` → مفرد
- `hasOne` → مفرد

---

## 3. استخدم eager loading

خصوصًا في:

- القوائم
- dashboards
- صفحات الإدارة

---

## 4. لا تتعامل مع العلاقة كأنها مضمونة دائمًا

أحيانًا تكون `null`.

مثال:

```php
echo $post->user?->name ?? 'غير معروف';
```

---

## 5. لا تكرر منطق العلاقات في كل controller

العلاقة مكانها الطبيعي في الـ model.

---

## 6. افهم قاعدة المفتاح الأجنبي

الجدول الذي "ينتمي إلى" غيره هو الذي يحمل المفتاح الأجنبي غالبًا.

---

## 7. لا تستخدم polymorphic بلا داعٍ

هي مفيدة جدًا، لكن ليست أول حل لكل شيء.

---

# الجزء العاشر: أخطاء شائعة جدًا

## 1. `Trying to get property of non-object`

السبب:

العلاقة رجعت `null`.

مثال:

```php
echo $post->user->name;
```

لو `user` غير موجودة سيحدث الخطأ.

### الحل

```php
echo $post->user?->name ?? 'غير معروف';
```

---

## 2. `Call to undefined relationship`

السبب:

- اسم العلاقة غير موجود
- أو كتبته غلط

---

## 3. العلاقة لا تعمل

راجع:

1. هل المفتاح الأجنبي موجود؟
2. هل اسمه صحيح؟
3. هل العلاقة مكتوبة بالنوع الصحيح؟
4. هل migration اتنفذت؟

---

## 4. pivot table لا تعمل كما تتوقع

راجع:

- اسم الجدول الوسيط
- أسماء الأعمدة
- `belongsToMany`
- `withPivot`

---

# الجزء الحادي عشر: أسئلة المبتدئ

## هل لازم كل علاقة تكون في الاتجاهين؟

ليس شرطًا تقنيًا دائمًا.

لكن غالبًا الأفضل نعم إذا كنت تحتاج استخدامها من الجهتين.

---

## هل أكتب العلاقة في migration ولا model؟

في الاثنين لكن بشكل مختلف:

- migration تبني foreign key
- model تعرف Laravel كيف تستخدمها

---

## هل ينفع أجيب العلاقة بدون eager loading؟

نعم.

لكن قد يكون أبطأ في بعض السيناريوهات.

---

## هل `belongsToMany` تغنيني عن pivot table؟

لا.

هي تعتمد عليها أصلًا.

---

## لو عندي `user_id` في posts، مين يقول `belongsTo` ومين يقول `hasMany`؟

- `Post` تقول `belongsTo(User::class)`
- `User` تقول `hasMany(Post::class)`

لأن post تحمل `user_id`.

---

# الجزء الثاني عشر: مثال شامل سريع

## User

```php
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

## Post

```php
class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
```

## Student

```php
class Student extends Model
{
    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('grade')
            ->withTimestamps();
    }
}
```

---

# الجزء الثالث عشر: الملخص النهائي

## العلاقات في Laravel هي

الطريقة التي تربط بها الـ models والجداول ببعض داخل التطبيق، بحيث تصبح البيانات مترابطة ومنطقية وسهلة الوصول.

---

## الجملة الذهبية

لو عايز تختصر الدرس كله:

> العلاقة في Laravel ليست مجرد دالة داخل الـ model، بل هي وصف منطقي لكيفية ارتباط البيانات ببعض داخل قاعدة البيانات وداخل الكود معًا.

---

## الخطوة التالية

بعد ما فهمت الـ Relationships، الدرس الطبيعي التالي هو:

## `11-laravel-crud-blog-guide.md`

لأنك أصبحت جاهزًا الآن لربط:

- migrations
- models
- relationships
- controllers
- routes
- views

في مشروع عملي كامل.

</div>
