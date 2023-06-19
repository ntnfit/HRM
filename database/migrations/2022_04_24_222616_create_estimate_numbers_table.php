<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER id_estimate
        ON estimates
        after INSERT
        AS
        BEGIN
            INSERT INTO estimates (id) VALUES (0);
            SET NOCOUNT ON;
            DECLARE @lastInsertId INT;
            SELECT @lastInsertId = SCOPE_IDENTITY();
            UPDATE estimates
            SET estimate_number = CONCAT(\'"EST_"\', RIGHT(\'00000\' + CAST(@lastInsertId AS VARCHAR(5)), 5))
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
        DB::unprepared('DROP TRIGGER "id_estimate"');
    }
};
