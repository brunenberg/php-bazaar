<?php
    namespace App\Models;
    
    class Listing {
        public static function all() {
            return [[
                'id' => 1,
                'title' => 'Learn React',
            ], [
                'id' => 2,
                'title' => 'Learn Vue',
            ]];
        }

        public static function find($id) {
            return collect(self::all())->firstWhere('id', $id);
        }
    }


?>