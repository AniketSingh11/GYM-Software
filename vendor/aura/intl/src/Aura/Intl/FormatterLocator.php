<?php

namespace Aura\Intl;


class FormatterLocator
{
    
    protected $registry;

    
    protected $converted = [];
    
    
    public function __construct(array $registry = [])
    {
        foreach ($registry as $name => $spec) {
            $this->set($name, $spec);
        }
    }

    /**
     * 
     * Sets a formatter into the registry by name.
     * 
     * @param string $name The formatter name.
     * 
     * @param callable $spec A callable that returns a formatter object.
     * 
     * @return void
     * 
     */
    public function set($name, $spec)
    {
        $this->registry[$name] = $spec;
        $this->converted[$name] = false;
    }

    /**
     * 
     * Gets a formatter from the registry by name.
     * 
     * @param string $name The formatter to retrieve.
     * 
     * @return FormatterInterface A formatter object.
     * 
     */
    public function get($name)
    {
        if (! isset($this->registry[$name])) {
            throw new Exception\FormatterNotMapped($name);
        }

        if (! $this->converted[$name]) {
            $func = $this->registry[$name];
            $this->registry[$name] = $func();
            $this->converted[$name] = true;
        }

        return $this->registry[$name];
    }
}
