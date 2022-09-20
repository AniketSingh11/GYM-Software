<?php

namespace Aura\Intl;


class PackageFactory
{
    public function newInstance(array $info)
    {
        $package = new Package;
        if (isset($info['fallback'])) {
            $package->setFallback($info['fallback']);
        }
        if (isset($info['formatter'])) {
            $package->setFormatter($info['formatter']);
        }
        if (isset($info['messages'])) {
            $package->setMessages($info['messages']);
        }
        return $package;
    }
}
