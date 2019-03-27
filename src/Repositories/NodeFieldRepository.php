<?php

namespace Reactor\Hierarchy\Repositories;


class NodeFieldRepository extends Repository {

    /**
     * Creates a node field
     *
     * @param int $id
     * @param array $attributes
     * @return NodeFieldContract
     */
    public function create($id, array $attributes)
    {
        $typeModelName = $this->getTypeModelName();

        $nodeType = $typeModelName::findOrFail($id);

        $nodeField = $nodeType->addField($attributes);

        $this->builderService->buildField(
            $nodeField->getName(),
            $nodeField->getType(),
            $nodeField->isIndexed(),
            $nodeType->getName(),
            $nodeType
        );

        return $nodeField;
    }

    /**
     * Destroys a node field
     *
     * @param int $id
     * @return NodeFieldContract
     */
    public function destroy($id)
    {
        $modelName = $this->getModelName();

        $nodeField = $modelName::findOrFail($id);
        $nodeField->delete();

        $this->builderService->destroyField(
            $nodeField->getName(),
            $nodeField->nodeType->getName(),
            $nodeField->nodeType
        );

        return $nodeField;
    }

    /**
     * Getter for node type class name
     */
    public function getModelName()
    {
        return config('hierarchy.nodefield_model', 'Reactor\Hierarchy\NodeField');
    }

    /**
     * Getter for node type class name
     */
    public function getTypeModelName()
    {
        return config('hierarchy.nodetype_model', 'Reactor\Hierarchy\NodeType');
    }

}