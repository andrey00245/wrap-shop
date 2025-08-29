# FAQ Аккордеон Система

Динамическая система FAQ с аккордеоном, поддерживающая 3 языка (украинский, английский, русский) и управление через Laravel Nova админку.

## Возможности

- ✅ Динамическое добавление FAQ через админку
- ✅ Поддержка 3 языков (UK, EN, RU)
- ✅ Красивый аккордеон интерфейс
- ✅ Адаптивный дизайн
- ✅ Анимации и переходы
- ✅ API для получения данных
- ✅ Управление через Laravel Nova

## Установка

### 1. Запуск миграций

```bash
php artisan migrate
```

### 2. Добавление в Nova

Добавьте ресурсы в `app/Providers/NovaServiceProvider.php`:

```php
use App\Nova\Faq;
use App\Nova\FaqTranslation;

public function resources()
{
    Nova::resources([
        // ... другие ресурсы
        Faq::class,
        FaqTranslation::class,
    ]);
}
```

### 3. Подключение стилей и скриптов

Добавьте в ваш основной SCSS файл:

```scss
@import 'faq-accordion';
```

Добавьте в ваш основной JS файл:

```javascript
import './faq-accordion.js';
```

## Использование

### HTML разметка

```html
<div class="faq-section">
    <div class="faq-header">
        <h2>Часто задаваемые вопросы</h2>
        
        <div class="faq-controls">
            <select id="language-switcher">
                <option value="uk">Українська</option>
                <option value="en">English</option>
                <option value="ru">Русский</option>
            </select>
        </div>
    </div>
    
    <div id="faq-container">
        <!-- FAQ будет загружен сюда автоматически -->
    </div>
</div>
```

### JavaScript инициализация

```javascript
const faqAccordion = new FaqAccordion('#faq-container', {
    language: 'uk', // По умолчанию украинский
    apiUrl: '/api/faq'
});

// Переключение языка
document.getElementById('language-switcher').addEventListener('change', function(e) {
    faqAccordion.setLanguage(e.target.value);
});
```

## API

### GET /api/faq

Получение списка FAQ для указанного языка.

**Параметры:**
- `lang` - язык (uk, en, ru), по умолчанию uk

**Пример ответа:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "question": "Как работает система?",
            "answer": "Система работает следующим образом...",
            "order": 1
        }
    ]
}
```

## Админка

### Создание FAQ

1. Перейдите в Nova админку
2. Создайте новый FAQ в разделе "FAQ"
3. Укажите порядок и активность
4. Создайте переводы для каждого языка в разделе "Переводы FAQ"

### Структура данных

- **FAQ** - основная модель с порядком, активностью и переводами
- **Переводы** - хранятся в JSON полях `question` и `answer` для каждого языка

## Кастомизация

### Стили

Основные стили находятся в `resources/assets/scss/_faq-accordion.scss`. Вы можете:

- Изменить цвета и шрифты
- Настроить анимации
- Добавить дополнительные эффекты

### JavaScript

Основная логика в `resources/assets/js/faq-accordion.js`. Возможности:

- Изменение API endpoints
- Добавление дополнительных событий
- Кастомизация анимаций

## Особенности

- **Автоматическое закрытие** - при открытии нового элемента предыдущий автоматически закрывается
- **Плавные анимации** - все переходы анимированы
- **Адаптивность** - корректно работает на всех устройствах
- **Темная тема** - автоматически поддерживает системные настройки
- **SEO-friendly** - семантическая HTML разметка

## Поддержка

При возникновении проблем:

1. Проверьте консоль браузера на ошибки
2. Убедитесь, что API endpoint доступен
3. Проверьте, что миграции выполнены
4. Убедитесь, что Nova ресурсы добавлены

## Лицензия

MIT License