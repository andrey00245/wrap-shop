<div class="nova-tool">
    <div class="nova-card">
        <div class="nova-card-header">
            <h1 class="nova-card-title">
                <svg class="nova-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Генератор фідів
            </h1>
            <p class="nova-card-description">Створіть XML фід для ремаркетингу товарів</p>
        </div>

        <div class="nova-card-content">
            <div class="nova-form-group">
                <label class="nova-form-label">Оберіть категорію</label>
                <select id="category-select" class="nova-form-select">
                    <option value="">Всі категорії</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @foreach($category->children as $child)
                            <option value="{{ $child->id }}">&nbsp;&nbsp;{{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="nova-button-group">
                <button id="generate-feed-btn" class="nova-button nova-button-primary">
                    <svg class="nova-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Згенерувати фід
                </button>
            </div>

            <div class="nova-divider"></div>

            <div class="nova-links-section">
                <h3 class="nova-links-title">Готові фіди</h3>
                <div class="nova-links">
                    <a href="{{ route('feed.remarketing.all') }}" target="_blank" class="nova-link">
                        <svg class="nova-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Фід для всіх категорій
                    </a>
                    <a href="#" id="category-feed-link" target="_blank" class="nova-link hidden">
                        <svg class="nova-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Фід для обраної категорії
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nova-tool {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .nova-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .nova-card-header {
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        
        .nova-card-title {
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 8px 0;
        }
        
        .nova-card-description {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }
        
        .nova-card-content {
            padding: 24px;
        }
        
        .nova-form-group {
            margin-bottom: 24px;
        }
        
        .nova-form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .nova-form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
            transition: border-color 0.2s;
        }
        
        .nova-form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .nova-button-group {
            margin-bottom: 24px;
        }
        
        .nova-button {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .nova-button-primary {
            background: #3b82f6;
            color: #fff;
        }
        
        .nova-button-primary:hover {
            background: #2563eb;
        }
        
        .nova-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }
        
        .nova-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 24px 0;
        }
        
        .nova-links-section {
            margin-top: 24px;
        }
        
        .nova-links-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 16px 0;
        }
        
        .nova-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .nova-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .nova-link:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #1f2937;
        }
        
        .hidden {
            display: none !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category-select');
            const generateBtn = document.getElementById('generate-feed-btn');
            const categoryFeedLink = document.getElementById('category-feed-link');

            // Генерація фіду
            if (generateBtn) {
                generateBtn.addEventListener('click', function() {
                    const categoryId = categorySelect.value;
                    let url = '{{ route("feed.remarketing.all") }}';
                    
                    if (categoryId) {
                        url = '{{ route("feed.remarketing.category") }}?category_id=' + categoryId;
                    }
                    
                    window.open(url, '_blank');
                });
            }

            // Оновлення посилання при зміні категорії
            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    const categoryId = this.value;
                    
                    if (categoryId) {
                        const url = '{{ route("feed.remarketing.category") }}?category_id=' + categoryId;
                        categoryFeedLink.href = url;
                        categoryFeedLink.classList.remove('hidden');
                    } else {
                        categoryFeedLink.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</div>
