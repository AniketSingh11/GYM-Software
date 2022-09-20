<?php

namespace Aura\Intl;


interface FormatterInterface
{
    
    public function format($locale, $string, array $tokens_values);
}
