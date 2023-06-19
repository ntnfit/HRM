<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateIdTblsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER id_store
        ON users
        after INSERT
        AS
        BEGIN
            INSERT INTO sequence_tbls (id) VALUES (0);
            SET NOCOUNT ON;
            DECLARE @lastInsertId INT;
            SELECT @lastInsertId = SCOPE_IDENTITY();
            UPDATE users
            SET user_id = CONCAT(\'KH_\', RIGHT(\'00000\' + CAST(@lastInsertId AS VARCHAR(5)), 5))
            WHERE id = @lastInsertId;
        END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       DB::unprepared('DROP TRIGGER "id_store"');
    }
}
