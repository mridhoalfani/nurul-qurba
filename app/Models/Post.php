<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory;
    use sluggable;

    protected $fillable = [
        'author_id',
        'category_id',
        'post_title',
        'post_slug',
        'post_content',
        'featured_image',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'post_slug' => [
                'source' => 'post_title'
            ]
        ];
    }

    public function scopeSearch($query,$term){
        $term = "%$term%";
        $query->where(function($query) use ($term){
            $query->where('post_title','like',$term);
        });
        // dd($query->toSql());
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,'category_id','id');
    }

    public function author(){
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
