<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE transaction_items DROP FOREIGN KEY transaction_items_menu_id_foreign');
            DB::statement('ALTER TABLE transaction_items MODIFY menu_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE transaction_items ADD CONSTRAINT transaction_items_menu_id_foreign FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE SET NULL');

            return;
        }

        DB::statement('ALTER TABLE transaction_items ALTER COLUMN menu_id DROP NOT NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::table('transaction_items')->whereNull('menu_id')->delete();
            DB::statement('ALTER TABLE transaction_items DROP FOREIGN KEY transaction_items_menu_id_foreign');
            DB::statement('ALTER TABLE transaction_items MODIFY menu_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE transaction_items ADD CONSTRAINT transaction_items_menu_id_foreign FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE RESTRICT');
        }
    }
};
