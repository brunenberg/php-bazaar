<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    
    class Listing extends Model {
        
        protected $fillable = ['title', 'description', 'bidding_allowed', 'image', 'company_id', 'type'];
        protected $table = 'listings';

        public function users()
        {
            return $this->belongsToMany(User::class, 'user_favorites')->withPivot('user_id', 'listing_id');
        }

        public function company()
        {
            return $this->belongsTo('App\Models\Company');
        }


        public function reviews()
        {
            return $this->belongsToMany(User::class, 'listing_review')->withPivot('user_id', 'listing_id', 'review', 'rating');
        }

        public function bids()
        {
            return $this->hasMany(Bid::class);
        }

        
        public function bought()
        {
            return $this->belongsToMany(User::class, 'user_bought')->withPivot('user_id', 'listing_id');
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }

?>