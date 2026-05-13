# Анализ структуры блога и план реализации

## 📋 Структура страниц

### 1. Главная страница блога (`/blog`)

#### Компоненты:
1. **Hero секция**
   - Breadcrumbs: "Головна / Блог"
   - Навигация по категориям (фильтры): "ВСІ", "ПЛІВКА ДЛЯ АВТО", "ШУМОІЗОЛЯЦІЯ...", "ДЕТЕЙЛІНГ", "ТЮНІНГ", "ЗАГАЛЬНІ ПОРАДИ..."
   - Активная категория подсвечивается

2. **Featured Article (Главная статья)**
   - Левая часть:
     * Желтый тег категории
     * Дата публикации: "Опубліковано: 01зч.07.2023"
     * Заголовок статьи
     * Краткое описание (description)
     * Автор: "Full Name"
     * Кнопка "ЧИТАТИ" с иконкой стрелки
   - Правая часть:
     * Большое изображение (hero image)

3. **Секция "ОСТАННІ ПУБЛІКАЦІЇ"**
   - Заголовок + кнопка "ВСІ ПУБЛІКАЦІЇ" справа
   - 3 карточки статей в ряд
   - Каждая карточка:
     * Изображение
     * Желтый тег категории
     * Дата
     * Заголовок
     * Краткое описание
     * Автор

4. **Секции по категориям** (для каждой категории)
   - Заголовок категории
   - Кнопка "БІЛЬШЕ ПУБЛІКАЦІЙ" справа
   - 2 карточки статей в ряд
   - Та же структура карточек

---

### 2. Детальная страница статьи (`/blog/{category}/{article}`)

#### Компоненты:
1. **Hero секция**
   - Большое изображение (full-width)

2. **Заголовок и метаданные**
   - Заголовок статьи
   - Дата публикации: "дата публікації: 26.02.2025"

3. **Таблица содержания (ЗМІСТ)**
   - Нумерованный список разделов статьи
   - Автоматически генерируется из заголовков H2, H3 в контенте

4. **Контент статьи**
   - Полный HTML контент (из CKEditor)
   - Подзаголовки (H2, H3)
   - Изображения внутри текста
   - Форматирование текста

5. **Секция товаров "КОЛЬОРОВІ ПЛІВКИ"**
   - Заголовок секции
   - 4 карточки продуктов в ряд
   - Каждая карточка:
     * Изображение продукта
     * Теги: "ХІТ ПРОДАЖУ", "АКЦІЯ"
     * Название продукта
     * Цена
     * Кнопка "КУПИТИ"
     * Иконка избранного (сердечко)

6. **Сайдбар справа: "ІНШІ СТАТТІ ЗА КАТЕГОРІЄЮ"**
   - 3 карточки статей из той же категории
   - Структура карточки:
     * Изображение
     * Желтый тег категории
     * Дата
     * Заголовок
     * Краткое описание
     * Иконка закладки

7. **Секция "СХОЖІ СТАТТІ ЗА КАТЕГОРІЄЮ"**
   - Заголовок
   - 3 карточки статей в ряд
   - Та же структура карточек

8. **Теги внизу страницы**
   - Прямоугольные кнопки-теги
   - Примеры: "Захисна плівка", "Кольорові захисні плівки", "Захисні покриття"

9. **Прогресс чтения и соц. иконки**
   - Индикатор прогресса: "25/100"
   - Иконки соц. сетей: Facebook, Instagram, X/Twitter, Share

---

## 🗄️ Структура базы данных

### Текущая структура (News):

```php
news:
- id
- read_time (json) - время чтения
- title (json) - заголовок
- slug (json) - URL slug
- description (json) - краткое описание
- is_active (boolean) - активна ли статья
- category_id (foreign) - категория
- timestamps
```

### Необходимые изменения:

#### 1. Добавить поля в таблицу `news`:

```php
Schema::table('news', function (Blueprint $table) {
    // Полный контент статьи (HTML из CKEditor)
    $table->json('content')->nullable()->after('description');
    
    // Дата публикации (для сортировки и отображения)
    $table->timestamp('published_at')->nullable()->after('is_active');
    
    // Автор статьи
    $table->string('author')->nullable()->after('published_at');
    
    // Главная статья (featured) - показывается в hero секции
    $table->boolean('is_featured')->default(false)->after('author');
    
    // Таблица содержания (можно генерировать автоматически, но лучше хранить)
    $table->json('table_of_contents')->nullable()->after('content');
    
    // Meta данные для SEO
    $table->json('meta_title')->nullable();
    $table->json('meta_description')->nullable();
    $table->json('meta_keywords')->nullable();
});
```

#### 2. Создать таблицу `news_tags` (теги):

```php
Schema::create('news_tags', function (Blueprint $table) {
    $table->id();
    $table->json('name'); // название тега (многоязычное)
    $table->json('slug')->nullable();
    $table->timestamps();
});
```

#### 3. Создать pivot таблицу `news_news_tag`:

```php
Schema::create('news_news_tag', function (Blueprint $table) {
    $table->id();
    $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
    $table->foreignId('news_tag_id')->constrained('news_tags')->onDelete('cascade');
    $table->timestamps();
    
    $table->unique(['news_id', 'news_tag_id']);
});
```

#### 4. Создать pivot таблицу `news_product` (связь статей с продуктами):

```php
Schema::create('news_product', function (Blueprint $table) {
    $table->id();
    $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    $table->integer('sort_order')->default(0); // порядок отображения
    $table->timestamps();
    
    $table->unique(['news_id', 'product_id']);
});
```

#### 5. Добавить поле в `news_categories` (если нужно):

```php
Schema::table('news_categories', function (Blueprint $table) {
    // Порядок отображения категорий
    $table->integer('sort_order')->default(0)->after('slug');
    
    // Описание категории (для SEO)
    $table->json('description')->nullable()->after('name');
});
```

---

## 📝 Обновление моделей

### News Model:

```php
// Добавить в fillable:
protected $fillable = [
    'read_time', 'title', 'slug', 'description', 'content',
    'is_active', 'category_id', 'published_at', 'author',
    'is_featured', 'table_of_contents', 'meta_title',
    'meta_description', 'meta_keywords'
];

// Добавить в casts:
protected $casts = [
    'read_time' => 'json',
    'title' => 'json',
    'slug' => 'json',
    'description' => 'json',
    'content' => 'json', // новый
    'published_at' => 'datetime', // новый
    'table_of_contents' => 'json', // новый
    'meta_title' => 'json', // новый
    'meta_description' => 'json', // новый
    'meta_keywords' => 'json', // новый
];

// Добавить в translatable:
protected $translatable = [
    'title', 'read_time', 'description', 'slug',
    'content', 'table_of_contents', 'meta_title',
    'meta_description', 'meta_keywords'
];

// Добавить relationships:
public function tags(): BelongsToMany
{
    return $this->belongsToMany(NewsTag::class, 'news_news_tag')
        ->withTimestamps();
}

public function products(): BelongsToMany
{
    return $this->belongsToMany(Product::class, 'news_product')
        ->withPivot('sort_order')
        ->orderBy('sort_order')
        ->withTimestamps();
}

// Scopes:
public function scopeFeatured(Builder $query): Builder
{
    return $query->where('is_featured', true);
}

public function scopePublished(Builder $query): Builder
{
    return $query->where('is_active', true)
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now());
}

public function scopeLatest(Builder $query, int $limit = 10): Builder
{
    return $query->published()
        ->orderBy('published_at', 'desc')
        ->limit($limit);
}
```

### Создать модель NewsTag:

```php
class NewsTag extends Model
{
    use HasFactory, HasTranslations;
    
    protected $translatable = ['name', 'slug'];
    
    protected $fillable = ['name', 'slug'];
    
    protected $casts = [
        'name' => 'json',
        'slug' => 'json',
    ];
    
    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'news_news_tag')
            ->withTimestamps();
    }
}
```

---

## 🎨 Структура для админки (Nova)

### News Resource - добавить поля:

```php
NovaTabTranslatable::make([
    Text::make('Назва', 'title'),
    Text::make('Час читання', 'read_time'),
    CkEditor::make('Короткий опис', 'description'),
    CkEditor::make('Повний контент', 'content') // новый
        ->stacked()
        ->fullWidth(),
    Text::make('Meta Title', 'meta_title'),
    Textarea::make('Meta Description', 'meta_description'),
    Textarea::make('Meta Keywords', 'meta_keywords'),
]),

DateTime::make('Дата публікації', 'published_at'),
Text::make('Автор', 'author'),
Boolean::make('Головна стаття', 'is_featured'),
Boolean::make('Активна', 'is_active'),

BelongsTo::make('Категорія', 'category', NewsCategory::class),

BelongsToMany::make('Теги', 'tags', NewsTag::class),
BelongsToMany::make('Продукти', 'products', Product::class)
    ->fields(function () {
        return [
            Number::make('Порядок', 'sort_order'),
        ];
    }),

Images::make('Головне зображення', 'main'),
Images::make('Галерея', 'gallery'), // если нужна галерея
```

---

## 🔄 Логика работы

### Главная страница блога:

1. **Featured Article:**
   - Берем статью где `is_featured = true` и `is_active = true`
   - Если нет featured, берем последнюю опубликованную

2. **Останні публікації:**
   - Берем 3 последние опубликованные статьи (кроме featured)

3. **Секции по категориям:**
   - Для каждой категории берем 2 последние статьи
   - Показываем только категории, у которых есть активные статьи

4. **Фильтрация по категориям:**
   - При клике на категорию фильтруем все секции
   - Или переходим на отдельную страницу категории

### Детальная страница:

1. **Таблица содержания:**
   - Генерируем автоматически из H2, H3 в контенте
   - Или храним в `table_of_contents` (JSON)

2. **Связанные продукты:**
   - Берем из связи `news_product`
   - Сортируем по `sort_order`

3. **Інші статті за категорією:**
   - Берем 3 статьи из той же категории (кроме текущей)

4. **Схожі статті:**
   - По тегам (если есть общие теги)
   - Или по категории (если нет тегов)

5. **Теги:**
   - Показываем все теги статьи внизу

---

## ✅ План реализации

1. ✅ Создать миграции для новых полей и таблиц
2. ✅ Обновить модели (News, создать NewsTag)
3. ✅ Обновить Nova ресурсы для удобного заполнения
4. ✅ Создать контроллер BlogController
5. ✅ Создать views для главной и детальной страниц
6. ✅ Добавить стили (SCSS)
7. ✅ Добавить JavaScript для интерактивности
8. ✅ Добавить переводы

---

## ❓ Вопросы для уточнения

1. **Featured Article:** Как выбирать? Вручную в админке или автоматически (последняя)?
2. **Таблица содержания:** Генерировать автоматически или заполнять вручную?
3. **Прогресс чтения:** Считать на фронте или хранить в БД?
4. **Секции по категориям:** Показывать все категории или только те, где есть статьи?
5. **Кнопка "БІЛЬШЕ ПУБЛІКАЦІЙ":** Куда ведет? На страницу категории или открывает все статьи?
6. **Связанные продукты:** Как выбирать? Вручную в админке или автоматически по категории/тегам?
