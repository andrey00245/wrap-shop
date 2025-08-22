class FaqAccordion {
    constructor(container, options = {}) {
        this.container = typeof container === 'string' ? document.querySelector(container) : container;
        this.options = {
            apiUrl: '/api/faq',
            language: 'uk',
            animationDuration: 300,
            ...options
        };
        
        this.faqs = [];
        this.activeItem = null;
        
        this.init();
    }
    
    async init() {
        await this.loadFaqs();
        this.render();
        this.bindEvents();
    }
    
    async loadFaqs() {
        try {
            const response = await fetch(`${this.options.apiUrl}?lang=${this.options.language}`);
            const data = await response.json();
            
            if (data.success) {
                this.faqs = data.data;
            }
        } catch (error) {
            console.error('Error loading FAQ:', error);
        }
    }
    
    render() {
        if (!this.faqs.length) {
            this.container.innerHTML = '<p>FAQ не найден</p>';
            return;
        }
        
        const html = `
            <div class="faq-accordion">
                ${this.faqs.map((faq, index) => `
                    <div class="faq-item" data-faq-id="${faq.id}">
                        <div class="faq-question" data-index="${index}">
                            <h3 class="faq-question-text">${faq.question}</h3>
                            <span class="faq-toggle">
                                <svg class="faq-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                ${faq.answer}
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
        
        this.container.innerHTML = html;
    }
    
    bindEvents() {
        const questions = this.container.querySelectorAll('.faq-question');
        
        questions.forEach(question => {
            question.addEventListener('click', (e) => {
                const index = parseInt(question.dataset.index);
                this.toggleItem(index);
            });
        });
    }
    
    toggleItem(index) {
        const item = this.container.querySelector(`[data-index="${index}"]`).closest('.faq-item');
        const answer = item.querySelector('.faq-answer');
        const toggle = item.querySelector('.faq-toggle');
        
        if (this.activeItem === item) {
            // Закрываем текущий элемент
            this.closeItem(item, answer, toggle);
            this.activeItem = null;
        } else {
            // Закрываем предыдущий активный элемент
            if (this.activeItem) {
                const prevAnswer = this.activeItem.querySelector('.faq-answer');
                const prevToggle = this.activeItem.querySelector('.faq-toggle');
                this.closeItem(this.activeItem, prevAnswer, prevToggle);
            }
            
            // Открываем новый элемент
            this.openItem(item, answer, toggle);
            this.activeItem = item;
        }
    }
    
    openItem(item, answer, toggle) {
        item.classList.add('active');
        answer.style.maxHeight = answer.scrollHeight + 'px';
        toggle.style.transform = 'rotate(180deg)';
    }
    
    closeItem(item, answer, toggle) {
        item.classList.remove('active');
        answer.style.maxHeight = '0';
        toggle.style.transform = 'rotate(0deg)';
    }
    
    setLanguage(language) {
        this.options.language = language;
        this.loadFaqs().then(() => {
            this.render();
            this.bindEvents();
        });
    }
    
    refresh() {
        this.loadFaqs().then(() => {
            this.render();
            this.bindEvents();
        });
    }
}

// Экспорт для использования в других модулях
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FaqAccordion;
} else if (typeof window !== 'undefined') {
    window.FaqAccordion = FaqAccordion;
}