<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private string $table = 'basic_cloud_storage';

    /**
     * 执行迁移
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable($this->table)) {
            //备份表
            Schema::rename($this->table, 'backup_' . $this->table . '_' .date('YmdHis'));
            //删除表
            Schema::dropIfExists($this->table);
        }
        //创建表
        Schema::create($this->table, function (Blueprint $table) {
            $table->comment('存储表');
            $table->increments('id');

            $table->string('title', 30)->default('')->comment('名称');
            $table->string('driver', 35)->default('local')->comment('驱动（local：本地存储 oss：阿里云 cos：腾讯云 kodo：七牛云）');
            $table->json('config')->nullable()->comment('配置信息');
            $table->unsignedBigInteger('file_size')->default(10)->comment('文件大小：默认限制10MB，-1不限制，单位MB');
            $table->text('format')->nullable()->comment('文件格式：默认*');
            $table->string('description', 255)->default('')->comment('描述');
            $table->unsignedBigInteger('sort')->default(1000)->comment('排序');
            $table->tinyInteger('is_default')->default(0)->comment('是否为默认存储：1：是 0：否');
            $table->tinyInteger('enabled')->default(1)->comment('状态（1：启用；0：禁用）');
            $table->string('extension', 100)->default('')->comment('扩展名');
            $table->unsignedBigInteger('created_user')->default(0)->comment('创建人');
            $table->unsignedBigInteger('updated_user')->default(0)->comment('更新人');
            $table->unsignedBigInteger('deleted_user')->default(0)->comment('删除人');
            $table->string('module',50)->nullable()->comment('模块');
            $table->biginteger('mer_id')->nullable()->comment('商户id');

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * 迁移回滚
     * Reverse the migrations.
     */
    public function down(): void
    {
        //删除 reverse
        Schema::dropIfExists($this->table);
        //Schema::dropIfExists('basic_cloud_cache_locks');
    }

};
