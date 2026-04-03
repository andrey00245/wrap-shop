# Аналіз поточної SEO-фільтрів та план рефакторингу

## Поточна реалізація (що є зараз)

### 1. База даних
- **Таблиця:** `seo_filter_pages`
- **Поля:** `id`, `path` (unique), `category_id`, `filter_data` (JSON), `meta_title` (JSON), `meta_description` (JSON), `seo_text` (JSON), `is_active`, `timestamps`
- **Проблема:** `filter_data` — це ручний JSON виду `{"field_name": ["значення"]}`. Контент-менеджер має знати `field_name` атрибутів і формат.

### 2. Маршрутизація
- **Один маршрут:** `Route::get('/{path}', ...)->where('path', '.*')`
- **Логіка:** Увесь path (напр. `plivki`, `plivki/pidkategoria`, `plivki/brand-3m`) передається в `categoryOrSeoFilter($path)`:
  1. Спочатку шукається запис у `seo_filter_pages` по `path`.
  2. Якщо знайдено — показується категорія з `filter_data`, meta з запису.
  3. Якщо ні — path розбивається на 1–3 сегменти і розпізнається як категорія/підкатегорія/під-підкатегорія по slug.

### 3. Контролер (ProductController)
- `categoryOrSeoFilter(string $path)` — вхід для всього path.
- `resolvePathToCategories(string $path)` — перетворення path на категорії по slug (JSON_EXTRACT по slug).
- `renderCategoryListing(..., ?SeoFilterPage $seoFilterPage)` — спільна віддача списку; якщо є `$seoFilterPage`, то `request()->merge($seoFilterPage->filter_data)` і підставляються meta/seo_text з запису.
- **Проблема:** Фільтри застосовуються через merge у request; для цього потрібен коректний JSON у `filter_data` (ключ = field_name).

### 4. Nova (адмінка)
- Поля: ЧПУ шлях (ручний ввід), Категорія (BelongsTo), **Дані фільтрів (JSON)** (Textarea з ручним JSON), Meta title/description, SEO текст, Активна.
- **Проблема:** Контент-менеджер має вручну писати JSON і знати field_name.

### 5. View (products/index)
- Отримує `seoFilterPage`; якщо є — title/description з нього, внизу сторінки виводиться його `seo_text`.
- Решта (фільтри, пагінація, breadcrumbs) — без змін.

### 6. Query-фільтри (існуючі)
- Параметри типу `?min_price=420&max_price=6132&main_shade[]=чорний` обробляються в `renderCategoryListing` через `request()->except(['page', 'sort_by', ..., 'path'])` → `$selectedFilterValues`. Це **не прибирати** — має продовжувати працювати.

---

## Що потрібно прибрати / змінити

| Компонент | Дія |
|-----------|-----|
| **Migration** | Нова міграція: змінити структуру `seo_filter_pages` — прибрати `path` і `filter_data`; додати `attribute_id`, `filter_value` (string), `slug` (unique у межах category_id). Зберегти `category_id`, meta_title, meta_description, seo_text, is_active. |
| **Model SeoFilterPage** | Прибрати `path`, `filter_data`, `normalizePath`. Додати `attribute_id`, `filter_value`, `slug`, зв’язок `attribute()`. Метод-хелпер для побудови `selectedFilterValues` з одного фільтра: `[ attribute.field_name => [ filter_value ] ]`. |
| **Routing** | Залишити один маршрут `/{path}` з `.*` **або** розбити на два: `/{category_slug}` і `/{category_slug}/{filter_slug}`. У другому варіанті не покриваються шляхи з підкатегоріями (plivki/pidkategoria/pidpidkategoria) без додаткової логіки, тому доцільно **залишити один path** і в логіці визначати: останній сегмент — це slug SEO-сторінки для поточної категорії, чи наступний рівень категорії. |
| **ProductController** | 1) Для path з 2+ сегментами: спочатку визначити категорію (і опційно subcategory) по всіх сегментах крім останнього; для **останнього сегмента** спочатку шукати `SeoFilterPage` по `category_id` (поточна категорія) + `slug`. Якщо знайдено — застосувати один фільтр з запису (attribute + filter_value), підставити meta. 2) Якщо SEO не знайдено — останній сегмент трактувати як підкатегорію (як зараз). 3) **Не чіпати** логіку `selectedFilterValues` з request — query-фільтри лишаються. |
| **Nova SeoFilterPage** | Прибрати поле «Дані фільтрів (JSON)» і ручний «ЧПУ шлях». Додати: Category (select), Filter attribute (select з attributes), Filter value (текст або select з значеннями атрибута), Slug (текст, автогенерація з атрибута+значення, напр. `kolir-chornyj`), Meta title, Meta description, SEO text, Active. |
| **View** | Зміни мінімальні: як і раніше приймає `seoFilterPage` і використовує його meta/seo_text. Додати: для сторінок з query-фільтрами без відповідного SEO — `<meta name="robots" content="noindex,follow">`; для SEO-сторінок — `index,follow` і canonical на URL виду `/{category_slug}/{filter_slug}`. |

---

## Нова структура таблиці (підсумок)

```text
seo_filter_pages
  id
  category_id (FK → categories)
  attribute_id (FK → attributes)
  filter_value (string) — значення фільтра як у products_attributes (напр. "Чорний")
  slug (string) — напр. kolir-chornyj, unique в межах (category_id) або глобально
  meta_title (json, nullable)
  meta_description (json, nullable)
  seo_text (json, nullable)
  is_active (boolean)
  timestamps
```

Унікальність: `(category_id, slug)` або просто `slug` global — залежить від того, чи можуть бути однакові slug в різних категоріях. За ТЗ достатньо один фільтр на одну SEO-сторінку, тому логічно unique `(category_id, slug)`.

---

## Що залишається без змін

- Query-фільтри: `?min_price=420&max_price=6132&main_shade[]=чорний` — без змін.
- Відображення списку товарів, фільтри в сайдбарі, пагінація, сортування.
- Розпізнавання категорій по slug (1–3 сегменти) — залишається; додається лише пріоритет: якщо останній сегмент збігається з `seo_filter_pages.slug` для поточної категорії, то це SEO-сторінка, інакше — підкатегорія.
- Мова (локалізація) meta та seo_text — лишається (json/translatable).

Цей файл можна використати як орієнтир перед реалізацією кроків з твого списку (міграція, маршрути, контролер, Nova, canonical, noindex).
