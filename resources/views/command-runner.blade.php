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


            <!-- Оптимизация медиа -->
            <div class="command-card" style="border: 2px solid #17a2b8; background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);">
                <h3>🖼️ Оптимизация медиа файлов</h3>
                <p>Создание оптимизированных версий изображений для ускорения загрузки сайта</p>
                
                <div style="display:flex; gap:8px; justify-content: center; flex-wrap: wrap;">
                    <button class="btn btn-info" onclick="runGenerateConversions(this, false)">
                        🚀 Создать оптимизированные версии
                    </button>
                    <button class="btn btn-warning" onclick="runCleanupOldConversions(this, true)">
                        🔍 Анализ старых конверсий
                    </button>
                    <button class="btn btn-danger" onclick="runCleanupOldConversions(this, false)">
                        🗑️ Удалить старые конверсии
                    </button>
                </div>
                
                <div class="alert alert-success" id="conversions-success"></div>
                <div class="alert alert-error" id="conversions-error"></div>
            </div>

            <!-- Очистка неиспользуемых медиа -->
            <div class="command-card" style="border: 2px solid #dc3545; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);">
                <h3>🗑️ Очистка неиспользуемых медиа</h3>
                <p><strong>ВНИМАНИЕ!</strong> Удаляет медиа файлы, которые не привязаны к товарам (экономия ~6 ГБ)</p>
                
                <div style="display:flex; gap:8px; justify-content: center;">
                    <button class="btn btn-secondary" onclick="runAnalyzeUnusedMedia(this)">
                        🔍 Анализ (безопасно)
                    </button>
                    <button class="btn btn-danger" onclick="runCleanupUnusedMedia(this)">
                        🗑️ Удалить неиспользуемые
                    </button>
                </div>
                
                <div class="alert alert-success" id="unused-success"></div>
                <div class="alert alert-error" id="unused-error"></div>
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

        async function runGenerateConversions(btn, force = false) {
            if (!confirm('Создать оптимизированные версии изображений? Это может занять время!')) {
                return;
            }
            
            setLoading(btn, true);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/generate-conversions', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ force })
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

        async function runAnalyzeUnusedMedia(btn) {
            setLoading(btn, true);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/analyze-unused-media', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('unused', data.message, true);
                } else {
                    showAlert('unused', data.message, false);
                }
            } catch (error) {
                showAlert('unused', 'Ошибка соединения: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
        }

        async function runCleanupUnusedMedia(btn) {
            if (!confirm('⚠️ ВНИМАНИЕ! Это удалит неиспользуемые медиа файлы навсегда!\n\nВы создали бэкап базы данных?')) {
                return;
            }
            
            if (!confirm('Вы уверены, что хотите продолжить? Это действие необратимо!')) {
                return;
            }
            
            setLoading(btn, true);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/nova-vendor/command-runner/cleanup-unused-media', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert('unused', data.message, true);
                } else {
                    showAlert('unused', data.message, false);
                }
            } catch (error) {
                showAlert('unused', 'Ошибка соединения: ' + error.message, false);
            } finally {
                setLoading(btn, false);
            }
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

