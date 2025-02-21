<?php

namespace App\Services\LexoRank;

use App\Models\SubMogouImage;
use App\Repo\Admin\SubMogouRepo\MogouPartitionFind;
use Illuminate\Support\Facades\Log;

class LexoRankHelperService {
    /**
    * Convert an integer to a base-26 string ( like LexoRank ) using lowercase letters.
    * For example, 0 -> 'a', 1 -> 'b', ..., 25 -> 'z', 26 -> 'aa', etc.
    *
    * @param int $n
    * @return string
    */

    public static function numberToLexoRank( int $n ): string {
        $result = '';
        do {
            $result = chr( ( $n % 26 ) + ord( 'a' ) ) . $result;
            $n = intdiv( $n, 26 ) - 1;
        }
        while ( $n >= 0 );

        return $result;
    }

    public static function resetLexoRanks(SubMogouImage $subMogouImageModel,int|string $subMogouId ): void {

        $images = $subMogouImageModel
        ->where( 'sub_mogou_id', $subMogouId )
        ->orderBy( 'position' ) // Order by current LexoRank value
        ->get();

        foreach ( $images as $index => $image ) {
            $newPosition = self::numberToLexoRank( $index );
            $image->position = $newPosition;
            $image->save();

            // Optional: Log the new position
            Log::channel('chapter_summary')->info( 'Reset LexoRank', [
                'id' => $image->id,
                'new_position' => $newPosition
            ] );
        }

    }
}
