<?php
/**
 * teqneers/ext-direct
 *
 * @category   TQ
 * @package    TQ\ExtDirect
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\ExtDirect\Annotation;


/**
 * Class Result
 *
 * @package TQ\ExtDirect\Annotation
 *
 * @Annotation
 * @Target("METHOD")
 */
class Result
{
    /**
     * @var array<string>
     */
    public $groups = [];

    /**
     * @var array<string>
     */
    public $attributes = [];

    /**
     * @var int|null
     */
    public $version = null;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            if (is_array($data['value'])) {
                $data['groups'] = array_shift($data['value']);
                if (!empty($data['value'])) {
                    $data['constraints'] = array_shift($data['value']);
                }
                if (!empty($data['value'])) {
                    $data['attributes'] = array_shift($data['value']);
                }
                if (!empty($data['value'])) {
                    $data['version'] = array_shift($data['value']);
                }
            } else {
                $data['groups'] = $data['value'];
            }
            unset($data['value']);
        }

        foreach ($data as $k => $v) {
            if ($k == 'groups' && !is_array($v)) {
                $v = array($v);
            } elseif ($k == 'attributes' && !is_array($v)) {
                $v = array($v);
            }

            $this->{$k} = $v;
        }
    }
}
