<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;    // dùng cái này mới soft detete nếu k s xóa cứng luôn
    // Add [name] to fillable property to allow mass assignment on [App\Category]
    // Được phép thêm các trường dữ liệu này trong database
    protected $fillable = ['name','parent_id','slug'];
}
