<?php

namespace QuizAd\Container;

/**
 * Custom implementation of a DI (Dependency Injection) container.
 */
class Container
{
    private $dependencies = array();

    /**
     * Setup dependency.
     *
     * @param string   $name
     * @param callable $callback - function(Container $container) - takes $this as argument
     */
    public function set($name, $callback)
    {
        $this->dependencies[$name] = $callback;
    }

    /**
     * Extract dependency by name.
     *
     * @param string $name - name of dependency
     * @return mixed
     * @throws DependencyNotFoundException - in case it was not set up
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->dependencies))
        {
            throw new DependencyNotFoundException($name.' key not found');
        }
        return $this->dependencies[$name]($this);
    }

}