<?php
namespace Reactor\Hierarchy;
use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: dev
 * Date: 24/3/19
 * Time: 6:21 PM
 */
class MetaTable extends Model{

    protected $table = 'meta';
    public $timestamps = false;
    protected $fillable = ['metable_id', 'metable_type', 'key', 'type', 'value'];

}