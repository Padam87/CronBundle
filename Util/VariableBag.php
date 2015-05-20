<?php

namespace Padam87\CronBundle\Util;

use Padam87\CronBundle\Annotation\Job;

class VariableBag implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $vars = [];

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @return Job
     */
    public function offsetGet($offset)
    {
        return $this->vars[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->vars[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->vars[$offset]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = '';
        foreach ($this->vars as $k => $v) {
            $string .= sprintf('%s=%s', $k, $v) . PHP_EOL;
        }

        return $string;
    }

    /**
     * @return Job[]
     */
    public function getVars()
    {
        return $this->vars;
    }
}
