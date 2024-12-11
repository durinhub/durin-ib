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
        \DB::statement("CREATE VIEW posts_por_dia AS
                        select count(*) as nr, 
                        date_format(p.created_at,'%Y-%m-%d') as dia 
                        from posts p
                        group by date_format(p.created_at, 'Y%M%DD')
                        order by p.created_at asc");

        // \DB::statement("CREATE VIEW posts_por_dia_board AS
        //                 select count(*) as nr, 
        //                 p.board, 
        //                 date_format(p.created_at,'%Y-%m-%d') as dia 
        //                 from posts p
        //                 group by date_format(p.created_at, 'Y%M%DD'),p.board
        //                 order by p.created_at asc");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW posts_por_dia");
        //\DB::statement("DROP VIEW posts_por_dia_board");
    }
};
