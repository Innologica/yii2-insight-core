<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 16.08.2016
 * Time: 10:57 ч.
 */

namespace insight\core\rest;

use yii\base\Component;
use yii\base\InvalidConfigException;

class ComponentRestController extends Controller
{
    public $component;

    /**
     * @var \ReflectionClass
     */
    protected $reflection;

    public function init()
    {
        parent::init();
        $this->reflection = new \ReflectionClass($this->component);
        if(!$this->reflection->isSubclassOf(Component::className()))
            throw new InvalidConfigException('$component is not set to a componet class.');
    }

    public function createAction($id)
    {
        $action = parent::createAction($id);

        if ($id === '') {
            $id = $this->defaultAction;
        }

        if(!isset($action)) {
            if($this->reflection->hasMethod($id)) {
                $action = new ComponentAction($id, $this, [
                    'component' => $this->component,
                    'method' => $this->reflection->getMethod($id),
                ]);
            }
        }
        return $action;
    }
}