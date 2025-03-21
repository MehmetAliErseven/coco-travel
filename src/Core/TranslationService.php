<?php

namespace App\Core;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Psr\Cache\CacheItemPoolInterface;

class TranslationService
{
    private Translator $translator;
    private string $currentLocale;
    private array $supportedLocales = ['en', 'th', 'tr', 'ru'];
    private string $fallbackLocale = 'en';
    private CacheItemPoolInterface $cache;
    
    public function __construct()
    {
        // Get locale from session or detect from browser
        $this->currentLocale = $this->determineLocale();
        
        // Set up cache adapter based on environment
        $this->setupCacheAdapter();
        
        // Create translator instance
        $this->translator = new Translator($this->currentLocale);
        $this->translator->setFallbackLocales([$this->fallbackLocale]);
        
        // Add array loader
        $this->translator->addLoader('array', new ArrayLoader());
        
        // Load translations
        $this->loadTranslations();
    }
    
    private function setupCacheAdapter(): void
    {
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] !== 'development') {
            $cacheDir = dirname(__DIR__, 2) . '/var/cache';
            
            try {
                // Ana dizin yoksa oluştur
                if (!is_dir($cacheDir)) {
                    // Önce daha kısıtlı izinlerle oluştur
                    mkdir($cacheDir, 0755, true);
                }

                // Cache dizininin yazılabilir olduğundan emin ol
                if (!is_writable($cacheDir)) {
                    // Web sunucusu kullanıcısına yazma izni ver
                    if (function_exists('posix_getuid')) {
                        // Linux/Unix sistemlerde
                        $webUser = posix_getpwuid(posix_getuid());
                        chown($cacheDir, $webUser['name']);
                    }
                    // Windows'ta farklı bir yaklaşım gerekebilir
                    chmod($cacheDir, 0755);
                }
                
                $this->cache = new FilesystemAdapter(
                    'translations',
                    86400,
                    $cacheDir
                );
            } catch (\Exception $e) {
                // Hata durumunda memory cache'e geri dön
                $this->cache = new ArrayAdapter();
            }
        } else {
            $this->cache = new ArrayAdapter();
        }
    }
    
    private function determineLocale(): string
    {
        // First priority: check session
        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $this->supportedLocales)) {
            return $_SESSION['lang'];
        }
        
        // Second priority: check cookie
        if (isset($_COOKIE['preferred_language']) && in_array($_COOKIE['preferred_language'], $this->supportedLocales)) {
            $_SESSION['lang'] = $_COOKIE['preferred_language'];
            return $_COOKIE['preferred_language'];
        }
        
        // Try to detect from browser
        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2);
        if (in_array($browserLang, $this->supportedLocales)) {
            $_SESSION['lang'] = $browserLang;
            return $browserLang;
        }
        
        // Fallback to default
        return $this->fallbackLocale;
    }
    
    private function loadTranslations(): void
    {
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
    
    private function loadMessageFile(string $filePath): array
    {
        if (file_exists($filePath)) {
            return include $filePath;
        }
        return [];
    }
    
    public function trans(string $id, array $parameters = []): string
    {
        // Daha anlaşılır bir cache key formatı kullan
        $cacheKey = "trans.{$this->currentLocale}." . str_replace([' ', '.'], '_', $id);
        
        if (!empty($parameters)) {
            $cacheKey .= '.' . implode('_', $parameters);
        }
        
        $cacheItem = $this->cache->getItem($cacheKey);
        
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        
        $translation = $this->translator->trans($id, $parameters, null, $this->currentLocale);
        $cacheItem->set($translation);
        $this->cache->save($cacheItem);
        
        return $translation;
    }
    
    public function clearCache(): bool
    {
        return $this->cache->clear();
    }
    
    public function getCurrentLocale(): string
    {
        return $this->currentLocale;
    }
    
    public function setLocale(string $locale): void
    {
        if (in_array($locale, $this->supportedLocales)) {
            $this->currentLocale = $locale;
            $_SESSION['lang'] = $locale;
            setcookie('preferred_language', $locale, time() + (86400 * 30), '/');
            $this->translator->setLocale($locale);
        }
    }
    
    public function getTranslator(): Translator
    {
        return $this->translator;
    }
}