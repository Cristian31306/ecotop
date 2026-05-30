<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Ecosystem;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar contenido existente
        $ecosystems = DB::table('ecosystems')->get();
        foreach ($ecosystems as $eco) {
            $content = $eco->content;
            if (is_string($content) && !empty($content) && !str_starts_with(trim($content), '[')) {
                $cards = preg_split('/<hr\s*\/?>/i', $content);
                $cards = array_filter($cards, fn($card) => trim($card) !== '');
                
                $jsonCards = array_map(function($card) {
                    return [
                        'text' => trim($card),
                        'image' => null
                    ];
                }, $cards);

                DB::table('ecosystems')->where('id', $eco->id)->update([
                    'content' => json_encode(array_values($jsonCards))
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a un solo string con <hr>
        $ecosystems = DB::table('ecosystems')->get();
        foreach ($ecosystems as $eco) {
            $content = json_decode($eco->content, true);
            if (is_array($content)) {
                $html = collect($content)->pluck('text')->implode('<hr>');
                DB::table('ecosystems')->where('id', $eco->id)->update([
                    'content' => $html
                ]);
            }
        }
    }
};
