<?php

namespace App\Core;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;

class TranslationService
{
    private Translator $translator;
    private string $currentLocale;
    private array $supportedLocales = ['en', 'th', 'tr'];
    private string $fallbackLocale = 'th';
    
    public function __construct()
    {
        // Get locale from session or detect from browser
        $this->currentLocale = $this->determineLocale();
        
        // Create translator instance
        $this->translator = new Translator($this->currentLocale);
        $this->translator->setFallbackLocales([$this->fallbackLocale]);
        
        // Add array loader
        $this->translator->addLoader('array', new ArrayLoader());
        
        // Load translations
        $this->loadTranslations();
    }
    
    /**
     * Determine the locale to use
     */
    private function determineLocale(): string
    {
        // Check if we have a locale in the session
        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $this->supportedLocales)) {
            return $_SESSION['lang'];
        }
        
        // Try to detect from browser
        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2);
        if (in_array($browserLang, $this->supportedLocales)) {
            // Save it to the session for future requests
            $_SESSION['lang'] = $browserLang;
            return $browserLang;
        }
        
        // Fallback to default
        return $this->fallbackLocale;
    }
    
    /**
     * Load all translations for current locale
     */
    private function loadTranslations(): void
    {
        // Path to translation files
        $baseDir = dirname(__DIR__) . '/Translations/';
        
        // Load English translations (always load as they may be used as fallback)
        $enMessages = $this->loadMessageFile($baseDir . 'en/messages.php');
        $this->translator->addResource('array', $enMessages, 'en');
        
        // Load current locale if not English
        if ($this->currentLocale !== 'en') {
            $localeMessages = $this->loadMessageFile($baseDir . $this->currentLocale . '/messages.php');
            $this->translator->addResource('array', $localeMessages, $this->currentLocale);
        }
    }
    
    /**
     * Load a translation file
     */
    private function loadMessageFile(string $filePath): array
    {
        if (file_exists($filePath)) {
            return include $filePath;
        }
        
        return [];
    }
    
    /**
     * Translate a string
     */
    public function trans(string $id, array $parameters = []): string
    {
        return $this->translator->trans($id, $parameters, null, $this->currentLocale);
    }
    
    /**
     * Get current locale
     */
    public function getCurrentLocale(): string
    {
        return $this->currentLocale;
    }
    
    /**
     * Set locale
     */
    public function setLocale(string $locale): void
    {
        if (in_array($locale, $this->supportedLocales)) {
            $this->currentLocale = $locale;
            $_SESSION['lang'] = $locale;
            $this->translator->setLocale($locale);
        }
    }
    
    /**
     * Get the translator instance
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }
}