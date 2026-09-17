<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Команди</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f7fafc;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 2rem;
        }

        .command-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .command-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .command-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .command-card p {
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #cbd5e0;
            border-radius: 0.375rem;
            transition: border-color 0.15s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.15s;
            width: 100%;
        }

        .btn-primary {
            background: #4299e1;
            color: white;
        }

        .btn-primary:hover {
            background: #3182ce;
        }

        .btn-primary:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
        }

        .btn-danger {
            background: #f56565;
            color: white;
        }

        .btn-danger:hover {
            background: #e53e3e;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
            font-size: 0.875rem;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8181;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .form-group select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #cbd5e0;
            border-radius: 0.375rem;
            background: #fff;
        }

        .progress-wrap {
            margin-top: 1rem;
            display: none;
        }

        .progress-wrap.show {
            display: block;
        }

        .progress-label {
            font-size: 0.875rem;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #4299e1, #48bb78);
            transition: width 0.3s ease;
        }

        .translate-log {
            margin-top: 0.75rem;
            max-height: 160px;
            overflow: auto;
            font-size: 0.75rem;
            color: #4a5568;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            display: none;
        }

        .translate-log.show {
            display: block;
        }

        .btn-row {
            display: flex;
            gap: 8px;
        }

        .btn-row .btn {
            width: auto;
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚡ Команди</h1>

        <div class="command-grid">
            <!-- Вебхуки (окремий блок) -->
            <div class="command-card">
                <h3>🔔 Вебхуки (МойСклад)</h3>
                <p>Перевірка доступності та відправка тестового вебхука на ваш URL</p>

                <div class="form-group">
                    <label for="webhook-url">URL вебхука:</label>
                    <input type="text" id="webhook-url" value="/webhook/moysklad" placeholder="https://example.com/webhook/moysklad">
                </div>

                <div class="form-group">
                    <label for="webhook-payload">Тестовий payload (JSON, необовʼязково):</label>
                    <input type="text" id="webhook-payload" placeholder='{"event":"test"}'>
                </div>

                <div style="display:flex; gap:8px;">
                    <button class="btn btn-primary" onclick="runWebhookCheck(this)">Перевірити доступність</button>
                    <button class="btn btn-success" onclick="runWebhookTest(this)">Надіслати тестовий</button>
                    <button class="btn btn-danger" onclick="runWebhookCreate(this)">Створити вебхуки</button>
                </div>

                <div class="alert alert-success" id="webhook-success"></div>
                <div class="alert alert-error" id="webhook-error"></div>
            </div>
            <!-- Генерація sitemap -->
            <div class="command-card">
                <h3>📄 Генерація Sitemap</h3>
                <p>Створює файл sitemap.xml з усіма доступними сторінками сайту (товари, категорії, новини тощо)</p>

                <button class="btn btn-primary" onclick="runSitemap(this)">
                    Згенерувати sitemap.xml
                </button>

                <div class="alert alert-success" id="sitemap-success"></div>
                <div class="alert alert-error" id="sitemap-error"></div>
            </div>

            <!-- Ціни та залишки з МС -->
            <div class="command-card" style="border: 2px solid #0d6efd;">
                <h3>📦 Ціни та залишки з МойСклад</h3>
                <p>Оновлює ціни та залишки товарів з МойСклад (виконується у фоні).</p>
                <button class="btn btn-primary" onclick="runSyncPricesAndStock(this)">
                    Оновити ціни та залишки з МС
                </button>
                <div class="alert alert-success" id="sync-prices-stock-success"></div>
                <div class="alert alert-error" id="sync-prices-stock-error"></div>
            </div>

            <!-- Категорії товарів з МС (лише category_id) -->
            <div class="command-card" style="border: 2px solid #6f42c1;">
                <h3>📁 Категорії товарів з МойСклад</h3>
                <p>Оновлює лише <strong>category_id</strong> з атрибута «Категорія сайту» (один запит GET на товар). Повний синк картки не запускається.</p>
                <p style="font-size:0.8rem;color:#718096;">Після натискання джоби ставляться <strong>одразу</strong> (не чекають cron). Таблиця <code>jobs</code> — лише при <code>QUEUE_CONNECTION=database</code>; при <code>sync</code> виконання в тому ж запиті. Частота до API — <code>MoySkladRemapHttp</code>, <code>MOY_SKLAD_REMAP_DELAY_MS</code>.</p>
                <div class="form-group">
                    <label for="sync-cat-chunk">Розмір батчу / chunk (1–500, за замовчуванням як SCHEDULE_PRICE_SYNC_BATCH):</label>
                    <input type="number" id="sync-cat-chunk" value="100" min="1" max="500" step="1">
                </div>
                <div class="form-group">
                    <label for="sync-cat-limit">Ліміт товарів (0 = усі):</label>
                    <input type="number" id="sync-cat-limit" value="0" min="0" step="1">
                </div>
                <button class="btn btn-primary" onclick="runSyncProductCategories(this)">
                    Оновити категорії з МС
                </button>
                <div class="alert alert-success" id="sync-product-categories-success"></div>
                <div class="alert alert-error" id="sync-product-categories-error"></div>
            </div>

            <!-- Оновлення товарів (закоментовано - потребує queue worker) -->
            <!--
            <div class="command-card">
                <h3>🔄 Оновлення товарів</h3>
                <p>Запускає синхронізацію товарів з МойСклад у фонових джобах (по 500 товарів)</p>

                <div class="form-group">
                    <label for="products-start">Початок (offset):</label>
                    <input type="number" id="products-start" value="0" min="0" step="1">
                </div>

                <div class="form-group">
                    <label for="products-end">Кінець (offset):</label>
                    <input type="number" id="products-end" value="1000" min="1" step="1">
                </div>

                <button class="btn btn-success" onclick="runUpdateProducts(this)">
                    Оновити товари
                </button>

                <div class="alert alert-success" id="products-success"></div>
                <div class="alert alert-error" id="products-error"></div>
            </div>
            -->

            <!-- Очищення кешу -->
            <div class="command-card">
                <h3>🗑️ Очищення кешу</h3>
                <p>Очищує кеш додатку (config, views, optimize). Корисно після змін у коді або конфігурації</p>

                <button class="btn btn-danger" onclick="runClearCache(this)">
                    Очистити кеш
                </button>

                <div class="alert alert-success" id="cache-success"></div>
                <div class="alert alert-error" id="cache-error"></div>
            </div>

            <!-- Storage Link -->
            <div class="command-card" style="border: 2px solid #28a745; background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                <h3>🔗 Storage Link</h3>
                <p>Создание символической ссылки для доступа к файлам из storage/app/public</p>

                <div style="display:flex; gap:8px; justify-content: center; flex-wrap: wrap;">
                    <button class="btn btn-success" onclick="runStorageLink(this, false)">
                        🔗 Создать ссылку
                    </button>
                    <button class="btn btn-warning" onclick="runStorageLink(this, true)">
                        🔄 Пересоздать ссылку
                    </button>
                    <button class="btn btn-warning" onclick="runCleanupOldConversions(this, true)">
                        🔍 Анализ старых конверсий
                    </button>
                    <button class="btn btn-danger" onclick="runCleanupOldConversions(this, false)">
                        🗑️ Удалить старые конверсии
                    </button>
                </div>

                <div class="alert alert-success" id="storage-link-success"></div>
                <div class="alert alert-error" id="storage-link-error"></div>
            </div>

            <!-- Bulk GPT переклад -->
            <div class="command-card" style="border: 2px solid #ed8936; grid-column: 1 / -1;">
                <h3>🌐 Bulk-переклад (GPT)</h3>
                <p>Поступовий переклад лише <strong>неперекладених</strong> записів через OpenAI. Батчами, з progress bar. Для «Усе» — порядок: товари → атрибути → категорії.</p>

                <div class="form-group">
                    <label for="translate-type">Тип:</label>
                    <select id="translate-type">
                        <option value="products">Товари</option>
                        <option value="attributes">Атрибути</option>
                        <option value="categories">Категорії</option>
                        <option value="all">Усе (products → attributes → categories)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="translate-batch-size">Розмір батчу (1–20):</label>
                    <input type="number" id="translate-batch-size" value="5" min="1" max="20" step="1">
                </div>

                <div class="btn-row">
                    <button class="btn btn-primary" id="translate-start-btn" onclick="startBulkTranslate(this)">
                        Почати переклад
                    </button>
                    <button class="btn btn-danger" id="translate-stop-btn" onclick="stopBulkTranslate(this)" disabled>
                        Зупинити
                    </button>
                    <button class="btn btn-danger" id="translate-clear-btn" onclick="clearBulkTranslateLog()" style="background:#718096;">
                        Очистити лог
                    </button>
                </div>

                <div class="progress-wrap" id="translate-progress-wrap">
                    <div class="progress-label" id="translate-progress-label">Очікування...</div>
                    <div class="progress-bar">
                        <div class="progress-bar-fill" id="translate-progress-fill"></div>
                    </div>
                </div>

                <div class="translate-log" id="translate-log"></div>

                <div class="alert alert-success" id="translate-success"></div>
                <div class="alert alert-error" id="translate-error"></div>
            </div>

            <!-- Custom Artisan Command -->
            <div class="command-card" style="border: 2px solid #6f42c1; background: linear-gradient(135deg, #e2d9f3 0%, #d1c4e9 100%);">
                <h3>⚡ Произвольная Artisan команда</h3>
                <p>Выполнить любую разрешенную Artisan команду с параметрами</p>

                <div class="form-group">
                    <label for="custom-command">Команда (например: cache:clear, migrate, media:generate-sync --force):</label>
                    <input type="text" id="custom-command" placeholder="cache:clear" style="width: 100%; padding: 8px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="display:flex; gap:8px; justify-content: center;">
                    <button class="btn btn-primary" onclick="runCustomCommand(this)">
                        🚀 Выполнить команду
                    </button>
                </div>

                <div class="alert alert-success" id="custom-command-success"></div>
                <div class="alert alert-error" id="custom-command-error"></div>
                <pre id="custom-command-output" style="margin-top:10px; max-height: 400px; overflow: auto; background:#111; color:#0f0; padding:10px; border-radius:6px; resize: vertical; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; display: none;"></pre>
            </div>

        </div>
    </div>

    <script>
        // Command Runner v2.0 - Media Optimization
        function showAlert(id, message, isSuccess = true) {
            const successEl = document.getElementById(id + '-success');
            const errorEl = document.getElementById(id + '-error');

            successEl.classList.remove('show');
            errorEl.classList.remove('show');

            if (isSuccess) {
                successEl.textContent = message;
                successEl.classList.add('show');
            } else {
                errorEl.textContent = message;
                errorEl.classList.add('show');
            }
        }

        function setLoading(btn, isLoading) {
            if (isLoading) {
                btn.disabled = true;
                const spinner = document.createElement('span');
                spinner.className = 'spinner';
                btn.insertBefore(spinner, btn.firstChild);
            } else {
                btn.disabled = false;
                const spinner = btn.querySelector('.spinner');
                if (spinner) spinner.remove();
            }
        }

        async function runSitemap(btn) {
            setLoading(btn, true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/sitemap', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('sitemap', data.message, true);
                } else {
                    showAlert('sitemap', data.message, false);
                }
            } catch (error) {
                showAlert('sitemap', 'Помилка з\'єднання: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        async function runSyncProductCategories(btn) {
            const chunk = Math.min(500, Math.max(1, parseInt(document.getElementById('sync-cat-chunk').value, 10) || 100));
            const limit = Math.max(0, parseInt(document.getElementById('sync-cat-limit').value, 10) || 0);
            const msg = limit === 0
                ? 'Оновити категорії для всіх товарів з МС? Запуститься ланцюжок джоб (потрібен queue:work).'
                : ('Оновити категорії лише для ' + limit + ' товарів?');
            if (!confirm(msg)) {
                return;
            }
            setLoading(btn, true);
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/sync-product-categories', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ chunk, limit })
                });
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    showAlert('sync-product-categories', 'Сервер повернув не JSON. Спробуйте з консолі: php artisan products:sync-categories-from-moysklad', false);
                    return;
                }
                if (data.success) {
                    showAlert('sync-product-categories', data.message + (data.output ? '\n' + data.output : ''), true);
                } else {
                    showAlert('sync-product-categories', data.message || 'Помилка', false);
                }
            } catch (error) {
                showAlert('sync-product-categories', 'Помилка: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        async function runSyncPricesAndStock(btn) {
            if (!confirm('Оновити ціни та залишки для всіх товарів з МС? Може зайняти кілька хвилин.')) {
                return;
            }
            setLoading(btn, true);
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/sync-prices-stock', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ chunk: 100, limit: 0 })
                });
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    showAlert('sync-prices-stock', 'Сервер повернув не JSON (таймаут або помилка). Запустіть з консолі: php artisan products:sync-prices-and-stock', false);
                    return;
                }
                if (data.success) {
                    showAlert('sync-prices-stock', data.message + (data.output ? '\n' + data.output : ''), true);
                } else {
                    showAlert('sync-prices-stock', data.message || 'Помилка', false);
                }
            } catch (error) {
                showAlert('sync-prices-stock', 'Помилка: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        async function runWebhookCheck(btn) {
            setLoading(btn, true);
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const url = document.getElementById('webhook-url').value || '/webhook/moysklad';
                const res = await fetch('/nova-vendor/command-runner/webhook/check', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url })
                });
                const data = await res.json();
                if (data.success) {
                    showAlert('webhook', `OK (${data.status}) за ${data.timeMs} мс: ${data.url}`, true);
                } else {
                    showAlert('webhook', data.message, false);
                }
            } catch (e) {
                showAlert('webhook', 'Помилка: ' + e.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        async function runWebhookTest(btn) {
            setLoading(btn, true);
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const url = document.getElementById('webhook-url').value || '/webhook/moysklad';
                let payloadText = document.getElementById('webhook-payload').value.trim();
                let payload = {};
                if (payloadText) {
                    try { payload = JSON.parse(payloadText); } catch (_) { payload = { raw: payloadText }; }
                }
                const res = await fetch('/nova-vendor/command-runner/webhook/test', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url, payload })
                });
                const data = await res.json();
                if (data.success) {
                    showAlert('webhook', `Надіслано (${data.status}) за ${data.timeMs} мс`, true);
                } else {
                    showAlert('webhook', data.message, false);
                }
            } catch (e) {
                showAlert('webhook', 'Помилка: ' + e.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        async function runWebhookCreate(btn) {
            if (!confirm('Створити вебхуки в МойСклад?')) return;
            setLoading(btn, true);
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const url = document.getElementById('webhook-url').value || '/webhook/moysklad';
                const token = prompt('Введіть токен MOYSKLAD_TOKEN (залиште порожнім для .env):', '');
                const res = await fetch('/nova-vendor/command-runner/webhook/create', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url, token })
                });
                const data = await res.json();
                if (data.success) {
                    showAlert('webhook', 'Вебхуки створено', true);
                } else {
                    showAlert('webhook', data.message || 'Помилка створення', false);
                }
            } catch (e) {
                showAlert('webhook', 'Помилка: ' + e.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        // Закоментовано - потребує queue worker
        /*
        async function runUpdateProducts(btn) {
            const start = parseInt(document.getElementById('products-start').value);
            const end = parseInt(document.getElementById('products-end').value);

            if (start < 0 || end <= start) {
                showAlert('products', 'Некоректні значення діапазону', false);
                return;
            }

            setLoading(btn, true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/products', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ start, end })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('products', data.message, true);
                } else {
                    showAlert('products', data.message, false);
                }
            } catch (error) {
                showAlert('products', 'Помилка з\'єднання: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }
        */

        async function runClearCache(btn) {
            if (!confirm('Ви впевнені, що хочете очистити кеш?')) {
                return;
            }

            setLoading(btn, true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/cache', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('cache', data.message, true);
                } else {
                    showAlert('cache', data.message, false);
                }
            } catch (error) {
                showAlert('cache', 'Помилка з\'єднання: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        async function runStorageLink(btn, force = false) {
            const action = force ? 'пересоздать' : 'создать';
            if (!confirm(`Вы уверены, что хотите ${action} storage link?`)) {
                return;
            }

            setLoading(btn, true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/storage-link', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        force: force
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('storage-link', result.message, true);
                    if (result.details) {
                        console.log('Storage Link Details:', result.details);
                    }
                } else {
                    showAlert('storage-link', result.message || 'Ошибка создания storage link', false);
                }
            } catch (error) {
                console.error('Storage Link Error:', error);
                showAlert('storage-link', 'Ошибка: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }






        async function runCustomCommand(btn) {
            const command = document.getElementById('custom-command').value.trim();

            if (!command) {
                showAlert('custom-command', 'Введите команду для выполнения', false);
                return;
            }

            if (!confirm(`Выполнить команду: "${command}"?`)) {
                return;
            }

            setLoading(btn, true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/custom-command', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ command })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('custom-command', data.message, true);
                    if (data.output) {
                        document.getElementById('custom-command-output').textContent = data.output;
                        document.getElementById('custom-command-output').style.display = 'block';
                    }
                } else {
                    showAlert('custom-command', data.message, false);
                    if (data.output) {
                        document.getElementById('custom-command-output').textContent = data.output;
                        document.getElementById('custom-command-output').style.display = 'block';
                    }
                }
            } catch (error) {
                showAlert('custom-command', 'Ошибка соединения: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        const translateState = {
            running: false,
            stopRequested: false,
            stats: { products: 0, attributes: 0, categories: 0 },
            doneByType: { products: 0, attributes: 0, categories: 0 },
            cursors: { products: 0, attributes: 0, categories: 0 },
            stageIndex: 0,
            stages: [],
        };

        const translateTypeLabels = {
            products: 'товари',
            attributes: 'атрибути',
            categories: 'категорії',
        };

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function appendTranslateLog(message) {
            const log = document.getElementById('translate-log');
            log.classList.add('show');
            const line = document.createElement('div');
            line.textContent = message;
            log.appendChild(line);
            log.scrollTop = log.scrollHeight;
        }

        function updateTranslateProgress() {
            const total = translateState.stages.reduce((sum, type) => sum + (translateState.stats[type] || 0), 0);
            const done = translateState.stages.reduce((sum, type) => sum + (translateState.doneByType[type] || 0), 0);
            const percent = total > 0 ? Math.min(100, Math.round((done / total) * 100)) : 0;
            const currentStage = translateState.stages[translateState.stageIndex] || translateState.stages[translateState.stages.length - 1];
            const stageLabel = translateTypeLabels[currentStage] || '';
            const stageDone = translateState.doneByType[currentStage] || 0;
            const stageTotal = translateState.stats[currentStage] || 0;

            document.getElementById('translate-progress-wrap').classList.add('show');
            document.getElementById('translate-progress-fill').style.width = percent + '%';
            document.getElementById('translate-progress-label').textContent =
                `Етап: ${stageLabel} — ${stageDone}/${stageTotal} · Загалом: ${done}/${total} (${percent}%)`;
        }

        async function fetchTranslateStats() {
            const response = await fetch('/nova-vendor/command-runner/translate/stats', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Не вдалося отримати статистику');
            }

            return data.stats;
        }

        async function runTranslateBatch(type, batchSize, afterId) {
            const response = await fetch('/nova-vendor/command-runner/translate/batch', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    type,
                    batch_size: batchSize,
                    after_id: afterId,
                }),
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Помилка батчу');
            }

            return data;
        }

        async function startBulkTranslate(btn) {
            const selectedType = document.getElementById('translate-type').value;
            const batchSize = Math.min(20, Math.max(1, parseInt(document.getElementById('translate-batch-size').value, 10) || 5));

            if (!confirm('Запустити bulk-переклад через GPT? Це може зайняти багато часу та використати OpenAI API.')) {
                return;
            }

            translateState.running = true;
            translateState.stopRequested = false;
            translateState.stageIndex = 0;
            translateState.doneByType = { products: 0, attributes: 0, categories: 0 };
            translateState.cursors = { products: 0, attributes: 0, categories: 0 };
            translateState.stages = selectedType === 'all'
                ? ['products', 'attributes', 'categories']
                : [selectedType];

            document.getElementById('translate-log').innerHTML = '';
            document.getElementById('translate-log').classList.add('show');
            document.getElementById('translate-start-btn').disabled = true;
            document.getElementById('translate-stop-btn').disabled = false;
            setLoading(btn, true);

            try {
                showAlert('translate', '', true);
                document.getElementById('translate-success').classList.remove('show');
                document.getElementById('translate-error').classList.remove('show');

                appendTranslateLog('Завантаження статистики...');
                translateState.stats = await fetchTranslateStats();

                const totalPlanned = translateState.stages.reduce((sum, type) => sum + (translateState.stats[type] || 0), 0);
                appendTranslateLog(`Знайдено неперекладених: товари ${translateState.stats.products}, атрибути ${translateState.stats.attributes}, категорії ${translateState.stats.categories}`);

                if (totalPlanned === 0) {
                    showAlert('translate', 'Немає неперекладених записів для обраного типу.', true);
                    return;
                }

                updateTranslateProgress();

                while (translateState.stageIndex < translateState.stages.length) {
                    if (translateState.stopRequested) {
                        appendTranslateLog('Зупинено користувачем.');
                        showAlert('translate', 'Переклад зупинено.', true);
                        break;
                    }

                    const type = translateState.stages[translateState.stageIndex];

                    if ((translateState.stats[type] || 0) === 0) {
                        translateState.stageIndex++;
                        continue;
                    }

                    let afterId = translateState.cursors[type] || 0;
                    let stageDone = false;

                    while (!stageDone) {
                        if (translateState.stopRequested) {
                            appendTranslateLog('Зупинено користувачем.');
                            showAlert('translate', 'Переклад зупинено.', true);
                            return;
                        }

                        appendTranslateLog(`Батч ${translateTypeLabels[type]} (after_id=${afterId})...`);
                        const result = await runTranslateBatch(type, batchSize, afterId);

                        afterId = result.next_after_id;
                        translateState.cursors[type] = afterId;

                        (result.items || []).forEach((item) => {
                            const status = item.status === 'translated' ? '✓' : (item.status === 'error' ? '✗' : '·');
                            appendTranslateLog(`${status} #${item.id}: ${item.label}`);
                        });

                        (result.errors || []).forEach((err) => {
                            appendTranslateLog(`✗ Помилка #${err.id}: ${err.message}`);
                        });

                        translateState.doneByType[type] += result.translated || 0;
                        updateTranslateProgress();

                        if (result.done) {
                            stageDone = true;
                        }
                    }

                    translateState.stageIndex++;
                }

                if (!translateState.stopRequested) {
                    showAlert('translate', 'Bulk-переклад завершено.', true);
                    appendTranslateLog('Готово.');
                }
            } catch (error) {
                showAlert('translate', 'Помилка: ' + error.message, false);
                appendTranslateLog('Помилка: ' + error.message);
            } finally {
                translateState.running = false;
                document.getElementById('translate-start-btn').disabled = false;
                document.getElementById('translate-stop-btn').disabled = true;
                setLoading(btn, false);
            }
        }

        function stopBulkTranslate(btn) {
            if (!translateState.running) {
                return;
            }

            translateState.stopRequested = true;
            btn.disabled = true;
            appendTranslateLog('Запит на зупинку...');
        }

        function clearBulkTranslateLog() {
            const log = document.getElementById('translate-log');
            log.innerHTML = '';
            log.classList.remove('show');

            document.getElementById('translate-progress-wrap').classList.remove('show');
            document.getElementById('translate-progress-fill').style.width = '0%';
            document.getElementById('translate-progress-label').textContent = 'Очікування...';

            document.getElementById('translate-success').classList.remove('show');
            document.getElementById('translate-error').classList.remove('show');
            document.getElementById('translate-success').textContent = '';
            document.getElementById('translate-error').textContent = '';
        }

        async function runCleanupOldConversions(btn, dryRun = true) {
            const action = dryRun ? 'анализ' : 'удаление';
            const confirmText = dryRun 
                ? 'Показать старые конверсии для удаления?'
                : '⚠️ ВНИМАНИЕ! Это удалит старые конверсии навсегда!\n\nВы уверены?';
                
            if (!confirm(confirmText)) {
                return;
            }
            
            setLoading(btn, true);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/cleanup-old-conversions', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ dry_run: dryRun })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('conversions', data.message, true);
                } else {
                    showAlert('conversions', data.message, false);
                }
            } catch (error) {
                showAlert('conversions', 'Ошибка соединения: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }
    </script>
</body>
</html>

