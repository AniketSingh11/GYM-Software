<?php

namespace Aura\Intl;


class PackageLocator implements PackageLocatorInterface
{
    
    protected $registry = [];

    
    protected $converted = [];
    
    public function __construct(array $registry = [])
    {
        foreach ($registry as $name => $locales) {
            foreach ($locales as $locale => $spec) {
                $this->set($name, $locale, $spec);
            }
        }
    }

    
    public function set($name, $locale, callable $spec)
    {
        $this->registry[$name][$locale] = $spec;
        $this->converted[$name][$locale] = false;
    }

    /**
     * 
     * Gets a Package object.
     * 
     * @param string $name The package name.
     * 
     * @param string $locale The locale for the package.
     * 
     * @return Package
     * 
     */
    public function get($name, $locale)
    {
        if (! isset($this->registry[$name][$locale])) {
            throw new Exception("Package '$name' with locale '$locale' is not registered.");
        }

        if (! $this->converted[$name][$locale]) {
            $func = $this->registry[$name][$locale];
            $this->registry[$name][$locale] = $func();
            $this->converted[$name][$locale] = true;
        }

        return $this->registry[$name][$locale];
    }
}
