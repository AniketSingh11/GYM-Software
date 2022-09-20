<?php

namespace Aura\Intl;


interface TranslatorInterface
{
   
    public function translate($key, array $tokens_values = []);
}
