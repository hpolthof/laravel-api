<?php namespace Hpolthof\LaravelAPI;

use Hpolthof\LaravelAPI\Contracts\ShouldMorphAPI;
use Hpolthof\LaravelAPI\Exceptions\BindingException;
use Illuminate\Database\Eloquent\Model;

class Binding
{
    protected $bindings;

    public function __construct()
    {
        $this->bindings = [];
    }

    /**
     * @param array $bindings
     * @return static
     */
    public static function create(array $bindings = [])
    {
        $binding = new static();
        foreach ($bindings as $key => $value) {
            $binding->addBinding($key, $value);
        }
        return $binding;
    }

    /**
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * @param string $name You can used dotted notation.
     * @param mixed $value
     * @return $this
     * @throws BindingException
     */
    public function addBinding($name, $value)
    {
        $renderValue = $value;

        if(is_object($value)) {
            if($this->isAPIModel($value)) {
                $renderValue = static::render($value);
            } elseif($value instanceof Model) {
                $renderValue = $value->toArray();
            } else {
                $renderValue = (array)$value;
            }
        }

        elseif(is_array($value)) {
            $renderValue = $value;
        }

        if(!array_has($this->bindings, $name)) {
            $this->bindings[$name] = $renderValue;
        } else {
            throw new BindingException('Binding already exists.');
        }

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function deleteBinding($name)
    {
        array_forget($this->bindings, $name);
        return $this;
    }

    /**
     * @param object $value
     * @return bool
     */
    private function isAPIModel($value)
    {
        // Check if the passed object is a subclass of Eloquent Model
        // and check if the object implements the ShouldMorphAPI.
        // This way we know how to proceed.

        $rc = new \ReflectionClass($value);
        $result = $rc->implementsInterface(ShouldMorphAPI::class) && $rc->isSubclassOf(Model::class);
        unset($rc);
        return $result;
    }

    /**
     * @return array
     */
    public static function render(ShouldMorphAPI $model)
    {
        $binding = $model->bindAPI();
        return $binding->getBindings();
    }
}