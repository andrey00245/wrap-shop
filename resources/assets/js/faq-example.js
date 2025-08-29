// Пример использования FAQ аккордеона
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация FAQ аккордеона
    const faqContainer = document.getElementById('faq-container');
    
    if (faqContainer) {
        const faqAccordion = new FaqAccordion(faqContainer, {
            language: 'uk', // По умолчанию украинский
            apiUrl: '/api/faq'
        });
        
        // Пример переключения языков
        const languageSwitcher = document.getElementById('language-switcher');
        if (languageSwitcher) {
            languageSwitcher.addEventListener('change', function(e) {
                faqAccordion.setLanguage(e.target.value);
            });
        }
        
        // Пример обновления данных
        const refreshButton = document.getElementById('refresh-faq');
        if (refreshButton) {
            refreshButton.addEventListener('click', function() {
                faqAccordion.refresh();
            });
        }
    }
});

// Пример HTML разметки для использования:
/*
<div class="faq-section">
    <div class="faq-header">
        <h2>Часто задаваемые вопросы</h2>
        
        <div class="faq-controls">
            <select id="language-switcher">
                <option value="uk">Українська</option>
                <option value="en">English</option>
                <option value="ru">Русский</option>
            </select>
            
            <button id="refresh-faq" type="button">
                Обновить
            </button>
        </div>
    </div>
    
    <div id="faq-container">
        <!-- FAQ будет загружен сюда автоматически -->
    </div>
</div>
*/