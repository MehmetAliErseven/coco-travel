export default class TranslationService {
    constructor() {
        this.translations = new Map();
        this.currentLanguage = document.documentElement.lang || 'en';
    }

    async loadTranslations(keys) {
        if (!keys || keys.length === 0) return;
        
        try {
            const response = await fetch(`${window.basePath}/api/translations?keys=${keys.join(',')}`);
            const data = await response.json();
            
            if (data.success) {
                Object.entries(data.translations).forEach(([key, value]) => {
                    this.translations.set(key, value);
                });
            }
        } catch (error) {
            console.error('Failed to load translations:', error);
        }
    }

    translate(key, fallback = key) {
        return this.translations.get(key) || fallback;
    }
}