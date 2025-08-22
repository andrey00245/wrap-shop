# Инструкции по установке FAQ системы

## Предварительные требования

Для работы FAQ системы необходимо:

1. **PHP 8.0+** с расширениями:
   - PDO
   - MySQL/PostgreSQL
   - JSON
   - cURL

2. **Composer** для управления зависимостями PHP

3. **Node.js** и **Yarn/NPM** для фронтенд зависимостей

## Установка PHP

### Ubuntu/Debian:
```bash
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-mysql php8.1-pdo php8.1-json php8.1-curl php8.1-mbstring php8.1-xml php8.1-zip
```

### CentOS/RHEL:
```bash
sudo yum install epel-release
sudo yum install php php-cli php-mysql php-pdo php-json php-curl php-mbstring php-xml php-zip
```

### macOS (с Homebrew):
```bash
brew install php
```

## Установка Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## Установка Node.js и Yarn

```bash
# Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Yarn
npm install -g yarn
```

## Настройка проекта

### 1. Установка PHP зависимостей
```bash
composer install
```

### 2. Установка Node.js зависимостей
```bash
yarn install
# или
npm install
```

### 3. Настройка окружения
```bash
cp .env.example .env
# Отредактируйте .env файл с вашими настройками БД
```

### 4. Генерация ключа приложения
```bash
php artisan key:generate
```

### 5. Запуск миграций
```bash
php artisan migrate
```

### 6. Сборка фронтенда
```bash
yarn dev
# или
npm run dev
```

## Проверка установки

После установки проверьте:

1. **PHP версия**: `php -v`
2. **Composer**: `composer -V`
3. **Node.js**: `node -v`
4. **Yarn**: `yarn -v`

## Структура созданных файлов

```
app/
├── Models/
│   ├── Faq.php                    # Модель FAQ
│   └── FaqTranslation.php         # Модель переводов
├── Nova/
│   ├── Faq.php                    # Nova ресурс FAQ
│   └── FaqTranslation.php         # Nova ресурс переводов
└── Http/Controllers/Api/
    └── FaqController.php          # API контроллер

database/migrations/
├── create_faqs_table.php          # Миграция таблицы FAQ
└── create_faq_translations_table.php # Миграция переводов

resources/
├── assets/js/
│   ├── faq-accordion.js           # Основной JS компонент
│   └── faq-example.js             # Пример использования
└── assets/scss/
    └── _faq-accordion.scss        # Стили аккордеона

routes/
└── api.php                        # API маршруты

FAQ_README.md                      # Документация по использованию
```

## Следующие шаги

После успешной установки:

1. Добавьте Nova ресурсы в `app/Providers/NovaServiceProvider.php`
2. Подключите стили и скрипты в ваши основные файлы
3. Создайте несколько FAQ через админку
4. Протестируйте API endpoint `/api/faq`
5. Интегрируйте компонент в ваш фронтенд

## Возможные проблемы

### Ошибка "php: command not found"
- Убедитесь, что PHP установлен и добавлен в PATH
- Попробуйте перезапустить терминал

### Ошибки Composer
- Проверьте версию PHP (должна быть 8.0+)
- Убедитесь, что все необходимые расширения установлены

### Ошибки миграций
- Проверьте настройки базы данных в `.env`
- Убедитесь, что база данных существует и доступна

## Поддержка

При возникновении проблем:
1. Проверьте логи Laravel в `storage/logs/`
2. Убедитесь, что все зависимости установлены
3. Проверьте права доступа к папкам `storage/` и `bootstrap/cache/`