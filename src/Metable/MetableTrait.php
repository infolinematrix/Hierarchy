<?php

namespace Reactor\Hierarchy\Metable;

use Illuminate\Database\Eloquent\Builder;

trait MetableTrait
{
    protected $deleteKeys = [];
    protected $metaDatas = [];
    protected $metaTable;
    /**
     * @var array
     */
    protected $dataTypes = ['boolean', 'integer', 'double', 'string', 'array', 'object'];

    public static function bootMetableTrait()
    {
        static::saved(function ($entity) {
            $entity->updateOrCreateMetas();
            $entity->deleteMetas();
        });

        static::deleting(function (Metable $entity) {
            $entity->deleteAllMetas();
        });
    }

    /**
     * Set the polymorphic relation.
     *
     * @return mixed
     */
    public function metas()
    {
        return $this->morphMany(config('hierarchy.meta_model'), 'node');
    }

    public function updateOrCreateMetas()
    {
        if (count($this->metaDatas)) {
            foreach ($this->metaDatas as $value) {
                $this->metas()->updateOrCreate($value[0], $value[1]);
            }
        }
    }

    public function deleteMetas()
    {
        if (count($this->deleteKeys)) {
            $this->metas()->whereIn('key', $this->deleteKeys)->delete();
        }
    }

    public function deleteAllMetas()
    {
        $this->metas()->delete();
    }

    public function setMeta($key, $value = null, $delete = false)
    {
        $type = gettype($key);
        switch ($type) {
            case 'string':
                $this->setMetaSingle($key, $value, $delete);
                break;
            case 'array':
                $this->setMetaArray($key);
                break;
            default:
                # code...
                break;
        }
    }

    public function unsetMeta($keys)
    {
        $type = gettype($keys);
        switch ($type) {
            case 'string':
                $this->setMetaSingle($keys, null, true);
                break;
            case 'array':
                foreach ($keys as $key) {
                    $this->setMetaSingle($key, null, true);
                }
                break;
        }
    }

    public function setMetaSingle($key, $value = null, $delete = false)
    {
        $value_type = gettype($value);

        if ($value_type == 'array') $value = implode(',', $value);

        if ($delete) {
            $this->deleteKeys[] = $key;
        } else {
            $this->metaDatas[] = [
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $value_type,
                ],
            ];
        }
    }

    public function setMetaArray($metas)
    {
        if (is_array($metas)) {
            foreach ($metas as $key => $value) {
                $this->setMeta($key, $value);
            }
        }
    }

    public function getMeta($key = null)
    {
        if (is_null($key)) {
            return $this->metas;
        }

        if (is_string($key)) {
            return $this->metas->firstWhere('key', $key);
        }

        if (is_array($key)) {
            return $this->metas->whereIn('key', $key);
        }
    }

    public function getMetaValue($key)
    {
        return is_string($key) ? $this->getMeta($key)->value : null;
    }

    public function getFirstMeta()
    {
        return $this->metas->first();
    }

    public function hasMeta(string $key) : bool
    {
        return $this->getMetaCollection()->contains('key', $key);
    }

    public function scopeFindMetaValue(Builder $query, $value, $key =null)
    {

        
        /**
         * $query->where(function ($query) use ($value) {
         * $query->WhereRaw("FIND_IN_SET($value, (value))");
         * });*/

        if (gettype($value) == 'array') {
            foreach ($value as $v) {
                $query->whereHas('metas', function (Builder $query) use ($key, $v) {
                    $query->where(function ($query) use ($v) {
                        $query->where('type','array');
                        $query->WhereRaw("FIND_IN_SET($v, (value))");
                    });
                });
            }
        }else{
            $query->whereHas('metas', function (Builder $query) use ($key, $value) {
                //$query->where(config('hierarchy.table') . '.key', $key);
                $query->where(function ($query) use ($value) {
                    $query->WhereRaw("FIND_IN_SET($value, (value))");
                });
            });
        }




        return $query;
       /* $query->whereHas('metas', function (Builder $query) use ($key, $value) {
            //$query->where(config('hierarchy.table') . '.key', $key);
            //dd($query->get());

            if (gettype($value) == 'array') {

                foreach ($value as $t) {
                    dd($query->get());
                    $query->where(function ($query) use ($t) {
                        $query->WhereRaw("FIND_IN_SET($t, (value))");
                    });

                    dd($query);
                }
            } else {
                $query->where(function ($query) use ($value) {
                    $query->WhereRaw("FIND_IN_SET($value, (value))");
                });
            }

            /*$query->where(function ($query) use ($value) {
                $query->WhereRaw("FIND_IN_SET($value, (value))");
            });*/


        return $query;
    }

    /**
     * fetch all meta for the model, if necessary.
     *
     * In Laravel versions prior to 5.3, relations that are lazy loaded by the
     * `getRelationFromMethod()` method ( invoked by the `__get()` magic method)
     * are not passed through the `setRelation()` method, so we load the relation
     * manually.
     *
     * @return mixed
     */
    private function getMetaCollection()
    {

        if (!$this->relationLoaded(config('hierarchy.meta_model'))) {
            $this->setRelation('metas', $this->metas()->get());
        }

        return $this->getRelation('metas');
    }

}
