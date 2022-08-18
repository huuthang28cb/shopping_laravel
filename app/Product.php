<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    // một nhiều
    public function images(){
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    // nhiều nhiều: https://laravel.com/docs/5.1/eloquent-relationships#many-to-many
    public function tags(){
        return $this
            ->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id')
            ->withTimestamps();
    }

    // Mối quan hệ một nhiều giữa product và category
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Tạo mối quan hệ với bảng image product
    // Một sản phẩm thì có nhiều ảnh chi tiết
    public function productImages(){
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
