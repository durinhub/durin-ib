<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \DB::statement("CREATE VIEW posts_por_dia_board AS
                        WITH recursive Date_Ranges AS ( -- Date_ranges cria um range de datas para obter 0 posts em dias que não houveram posts
                            SELECT '2024-11-20' AS dia -- 20/nov o primeiro dia do chan
                            UNION ALL
                            SELECT dia + INTERVAL 1 DAY
                            FROM Date_Ranges
                            WHERE dia <= NOW() - INTERVAL 1 DAY -- day < now, ou seja, dia até hoje
                        )
                        SELECT 
                            dr.dia,
                            COALESCE(COUNT(p.id), 0) AS nr,
                            b.sigla
                        FROM Date_Ranges dr
                        CROSS JOIN (SELECT DISTINCT board as sigla FROM posts) b
                        LEFT JOIN posts p
                            ON DATE_FORMAT(p.created_at, '%Y-%m-%d') = dr.dia
                            AND p.board = b.sigla
                        GROUP BY dr.dia, b.sigla
                        ORDER BY dr.dia ASC, b.sigla");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW posts_por_dia_board");
    }
};
