<?php

namespace Aura\Intl;


class TranslatorFactory
{
   
    protected $class = 'Aura\Intl\Translator';

    
    public function newInstance(
        $locale,
        array $messages,
        FormatterInterface $formatter,
        TranslatorInterface $fallback = null
    ) {
        $class = $this->class;
        return new $class($locale, $messages, $formatter, $fallback);
    }
}
