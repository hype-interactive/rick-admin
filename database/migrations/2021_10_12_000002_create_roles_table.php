<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'roles';

    /**
     * Run the migrations.
     * @table roles
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->json('permissions')->nullable()->default(null);

            $table->unique(["slug"], 'roles_slug_unique');
            $table->nullableTimestamps();
        });

        // Insert some admin role and permissions   
        DB::table($this->tableName)->insert([
            ['slug'=> "admin", 'name'=>'admin','permissions'=> '{
                "platform.index": "1",
                "platform.systems.roles": "0",
                "platform.systems.users": "0",
                "platform.systems.attachment": "1"
            }']
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
