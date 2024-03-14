<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    
    class Listing extends Model {
        
        protected $fillable = ['title', 'description', 'tags'];
        protected $table = 'listings';

        public function users()
        {
            return $this->belongsToMany(User::class, 'user_favorites')->withPivot('user_id', 'listing_id');
        }

    }

?>