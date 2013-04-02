<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RBAuthInit extends Migration
{
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->timestamps();
        });

        Schema::create('roles', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('role', 100)->unique();
            $table->tinyInteger('priority');
            $table->timestamps();
        });

        Schema::create('permissions', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('permission', 255)->unique();
            $table->timestamps();
        });

        Schema::create('access', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('permission_id')->unsigned()->index();
            $table->boolean('status')->default(1);
            $table->integer('accessible_id');
            $table->string('accessible_type', 255);
            $table->timestamps();
            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('users_roles', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('role_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        $this->seed();
    }

    public function down()
    {
        Schema::drop('users_roles');
        Schema::drop('access');
        Schema::drop('permissions');
        Schema::drop('roles');
        Schema::drop('users');
    }

    protected function seed()
    {
        DB::table('roles')->insert(array(
            'role'      => 'admin',
            'priority'      => 0,
            'created_at'    => new DateTime(),
            'updated_at'    => new DateTime()
        ));
        DB::table('roles')->insert(array(
            'role'      => 'user',
            'priority'      => 1,
            'created_at'    => new DateTime(),
            'updated_at'    => new DateTime()
        ));
        DB::table('roles')->insert(array(
            'role'          => 'guest',
            'priority'      => 2,
            'created_at'    => new DateTime(),
            'updated_at'    => new DateTime()
        ));

        DB::table('permissions')->insert(array(
            'permission'    => 'root',
            'created_at'    => new DateTime(),
            'updated_at'    => new DateTime()
        ));

        DB::table('access')->insert(array(
            'permission_id'         => 1,
            'status'                => 1,
            'accessible_id'         => 3,
            'accessible_type'       => 'Krucas\RBAuth\Implementations\Eloquent\Role',
            'created_at'    => new DateTime(),
            'updated_at'    => new DateTime()
        ));

        DB::table('users')->insert(array(
            'email'         => 'admin@admin.com',
            'password'      => Hash::make('admin'),
            'created_at'    => new DateTime(),
            'updated_at'    => new DateTime()
        ));

        DB::table('users_roles')->insert(array(
            'user_id'       => 1,
            'role_id'       => 3,
            'created_at'    => new DateTime(),
            'updated_at'    => new DateTime()
        ));
    }
}