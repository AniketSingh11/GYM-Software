<?php

namespace Aura\Intl;


class TranslatorLocator
{
   
    protected $registry;

   
    protected $locale;

    
    protected $factory;

    
    protected $packages;

    
    protected $formatters;

    
    public function __construct(
        PackageLocator $packages,
        FormatterLocator $formatters,
        TranslatorFactory $factory,
        $locale
    ) {
        $this->packages = $packages;
        $this->factory = $factory;
        $this->formatters = $formatters;
        $this->setLocale($locale);
    }

    /**
     * 
     * Sets the default locale code.
     * 
     * @param string $locale The new locale code.
     * 
     * @return void
     * 
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * 
     * Returns the default locale code.
     * 
     * @return string
     * 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * 
     * The TranslatorFactory object
     * 
     * @return TranslatorFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * 
     * An object of type PackagesInterface
     * 
     * @return PackagesInterface
     * 
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * 
     * object of type FormatterLocator
     * 
     * @return FormatterLocator
     * 
     */
    public function getFormatters()
    {
        return $this->formatters;
    }

    /**
     * 
     * Gets a translator from the registry by package for a locale.
     * 
     * @param string $name The translator package to retrieve.
     * 
     * @param string $locale The locale to use; if empty, uses the default
     * locale.
     * 
     * @return TranslatorInterface A translator object.
     * 
     */
    public function get($name, $locale = null)
    {
        if (! $name) {
            return null;
        }

        if (! $locale) {
            $locale = $this->getLocale();
        }

        if (! isset($this->registry[$name][$locale])) {

            // get the package descriptor
            $package = $this->packages->get($name, $locale);

            // build a translator; note the recursive nature of the
            // 'fallback' param at the very end.
            $translator = $this->factory->newInstance(
                $locale,
                $package->getMessages(),
                $this->formatters->get($package->getFormatter()),
                $this->get($package->getFallback(), $locale)
            );

            // retain in the registry
            $this->registry[$name][$locale] = $translator;
        }

        return $this->registry[$name][$locale];
    }
}
