<?php

namespace Reactor\Hierarchy;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Model;
use Reactor\Hierarchy\Contract\NodeSourceContract;


class NodeSource extends Eloquent implements NodeSourceContract {

    //use CacheQueryBuilder;
    /**
     * The fillable fields for the model.
     */
    protected $fillable = ['title', 'node_name',
        'meta_title', 'meta_keywords', 'meta_description', 'meta_author', 'meta_image'];

    protected $baseAttributesAndRelations = [
        'id', 'node_id', 'title', 'node_name', 'locale', 'source_type',
        'meta_title', 'meta_keywords', 'meta_description', 'meta_author', 'meta_image',
        'source', 'node'];

    /*
     * Timestamps for the model.
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['source'];

    /**
     * Temporary source model
     *
     * @var Model
     */
    protected $tempSource;

    /**
     * Boot the model
     */
    public static function boot()
    {
        NodeSource::saving(function (NodeSourceContract $nodeSource)
        {
            if (empty($nodeSource->getNodeName()) || is_null($nodeSource->getNodeName()))
            {
                $nodeSource->setNodeNameFromTitle();
            }
        });
    }

    /**
     * Node relationship
     *
     * @return BelongsTo
     */
    public function node()
    {
        return $this->belongsTo($this->getNodeModelName());
    }

    /**
     * Source relationship
     *
     * @return MorphOne
     */
    public function source()
    {
        return $this->hasOne(
            $this->getSourceModelName(),
            'id', 'id'
        );
    }

    /**
     * Returns the source model name
     *
     * @param string|null $type
     * @return string
     */
    public function getSourceModelName($type = null)
    {

        $bag = hierarchy_bag('nodetype');

        if (!$this->source_type && $bag)
        {
            $this->source_type = $bag->first()->name;
        }

        $type = $type ?: $this->source_type;

        return source_model_name($type, true);
    }

    /**
     * Create new node source with locale and type
     *
     * @param string $locale
     * @param string $type
     * @return NodeSource
     */
    public static function newWithType($locale, $type)
    {
        $nodeSource = new static();
        $sourceModel = $nodeSource->getNewSourceModel($type);

        // We temporarily cache the model since Eloquent does not
        // have a way to attach hasOne relations without saving them.
        // So we are providing a customized way to emulate that.
        // We will always access the source model through getSource() method.
        $nodeSource->setTemporarySource($sourceModel);

        // We first fill special attributes.
        // These have to be done after we have added the temporary source
        // since the setAttribute will look at the temporary source as well.
        $nodeSource->setAttribute('locale', $locale);
        $nodeSource->setAttribute('source_type', $type);

        return $nodeSource;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getNewSourceModel($type)
    {

        $sourceModelName = $this->getSourceModelName($type);

        return new $sourceModelName();
    }

    /**
     * Setter for temporary source
     *
     * @param Model $source
     */
    public function setTemporarySource(Model $source)
    {
        $this->tempSource = $source;
    }

    /**
     * Flush temporary source
     */
    public function flushTemporarySource()
    {
        $this->tempSource = null;
    }

    /**
     * Getter for source data
     *
     * @return mixed
     */
    public function getSource()
    {

        if ( ! is_null($this->tempSource))
        {
            return $this->tempSource;
        }

        if ( ! $this->isSourceRelationLoaded())
        {
            $this->load('source');
        }

        return $this->relations['source'];
    }

    /**
     * Checks if the source relation is loaded
     *
     * @return bool
     */
    protected function isSourceRelationLoaded()
    {
        return isset($this->relations['source']);
    }


    /**
     * Save the model to the database.
     * (and its source)
     *
     * @param array $options
     * @return bool
     */

    public function save(array $options = [])
    {

        // We are making a somewhat identical save method overload
        // as in dimsav/laravel-translatable, since the functionality
        // required is almost the same.
        if ($this->exists)
        {
            if (count($this->getDirty()) > 0)
            {

                if (parent::save($options))
                {
                    return $this->saveSource();
                }

                return false;
            } else
            {

                if ($saved = $this->saveSource())
                {
                    $this->fireModelEvent('saved', false);
                }

                return $saved;
            }
        } elseif (parent::save($options))
        {
            return $this->saveSource();
        }

        return false;
    }

    /**
     * Saves the source model
     *
     * @return bool
     */
    protected function saveSource()
    {

        // This part is only for the first save
        // as we temporarily keep the source model before
        // the parent saves.
        if ( ! is_null($this->tempSource))
        {
            $saved = $this->source()->save($this->tempSource);

            // Reload the relation
            $this->load('source');

            $this->flushTemporarySource();

            return ! is_null($saved);
        }
        return $this->source->save();
    }

    /**
     * Gets a model attribute
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {

        if ($this->isBaseAttribute($key))
        {
            return parent::getAttribute($key);
        } else
        {

            return $this->getSource()->getAttribute($key);
        }
    }


    /**
     * Gets a model attribute
     *
     * @param string $key
     * @return mixed
     */
    public function getUnmutatedAttribute($key)
    {
        if ($this->isBaseAttribute($key))
        {
            return parent::getAttribute($key);
        } else
        {
            return $this->getSource()->getAttributeFromArray($key);
        }
    }

    /**
     * Sets a model attribute
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value)
    {

        if ($this->isBaseAttribute($key))
        {
            return parent::setAttribute($key, $value);
        } else
        {
            return $this->getSource()->setAttribute($key, $value);
        }
    }

    /**
     * Checks if the given key is a base model field
     * (we will always know these fields as the migration is not dynamic)
     *
     * @param string $key
     * @return bool
     */
    protected function isBaseAttribute($key)
    {
        return in_array($key, $this->baseAttributesAndRelations);
    }

    /**
     * Determine if the model or given attribute(s) have been modified.
     *
     * @param  array|string|null $attributes
     * @return bool
     */
    /*public function isDirty($attributes = null)
    {

        return parent::isDirty($attributes) or
        $this->getSource()->isDirty($attributes);
    }*/

    /**
     * Getter for node name
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }

    /**
     * Getter for node name
     *
     * @return string
     */
    public function getNodeName()
    {
        return $this->getAttribute('node_name');
    }

    /**
     * Sets the node name
     *
     * @param string
     * @return void
     */
    public function setNodeName($name)
    {
        $this->attributes['node_name'] = str_slug($name);
    }

    /**
     * Node Name mutator
     *
     * @param string $value
     */
    public function setNodeNameAttribute($value)
    {
        $this->setNodeName($value);
    }

    /**
     * Set node name from title
     *
     * @return void
     */
    public function setNodeNameFromTitle()
    {
        $this->setNodeName(
            $this->getTitle()
        );
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), $this->source->toArray());
    }

    /**
     * Getter for node model name
     *
     * @return string
     */
    protected function getNodeModelName()
    {
        return $this->nodeModelName ?: 'Reactor\Hierarchy\Node';
    }

    /**
     * Sets the extension node id
     *
     * @param bool $save
     * @param int $id
     */
    public function setExtensionNodeId($id, $save = true)
    {
        $source = $this->getSource()->setNodeId($id);
        if ($save)
        {
            $source->save();
        }
    }

}
