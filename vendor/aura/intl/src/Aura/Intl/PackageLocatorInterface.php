<?php

namespace Aura\Intl;


interface PackageLocatorInterface
{
    
    public function set($name, $locale, callable $spec);

    
    public function get($name, $locale);
}
