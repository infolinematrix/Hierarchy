<?php

namespace Reactor\Hierarchy;


use Reactor\Hierarchy\Support\TokenManager;
use ReactorCMS\Entities\NodeMeta;

class NodeMetaRepository
{

    /**
     * @param array $key
     * @param array $value
     * @return null
     */
    public function getNode(array $key, array $value)
    {
        $node = null;

        $node = NodeMeta::whereIn('value', $value)->whereIn('key', $key)
            ->groupBy('node_id')
            ->get();

        if ($node) return $node;

        return $node;
    }


}