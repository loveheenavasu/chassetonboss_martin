<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetGroupIdToDefautNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('rule_id')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('defaut_null', function (Blueprint $table) {
        /*if (Schema::hasColumn('emails', 'group_id'))
        {
            Schema::table('emails', function (Blueprint $table)
            {
                $table->dropColumn('group_id');
            });
        }*/
    
    });
    }
}
