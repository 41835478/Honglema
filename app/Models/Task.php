<?php
/**
 * Created by IntelliJ IDEA.
 * User: 王得屹
 * Date: 2016/6/12
 * Time: 12:19
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model{
    protected $table = 'tasks';

    public $timestamps = false;

    protected  $primaryKey = 'task_id';
    
    protected  $fillable = [
        'activity_id',
        'status'
    ];
}