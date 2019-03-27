<?php

namespace Reactor\Hierarchy;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Kenarkose\Chronicle\RecordsActivity;

class NodeField extends Eloquent {

    use RecordsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label', 'description', 'position', 'type',
        'visible', 'indexed', 'search_priority', 'rules', 'default_value', 'options'];

    /**
     * Parent node type relation
     *
     * @return BelongsTo
     */
    public function nodeType()
    {
        return $this->belongsTo(NodeType::class);
    }

    /**
     * Getter for field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for field type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Checks if the node field is indexed
     *
     * @return bool
     */
    public function isIndexed()
    {
        return (bool)$this->getAttribute('indexed');
    }

    /**
     * Checks if a node is locked
     *
     * @return bool
     */
    public function isVisible()
    {
        return (bool)$this->getAttribute('visible');
    }

}