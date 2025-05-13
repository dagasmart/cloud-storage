<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private string $table = 'basic_cloud_resource';

    /**
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
            $table->comment('云资源表');
            $table->ulid('id')->primary();

            $table->string('title')->default('')->comment('名称');
            $table->unsignedBigInteger('size')->default(0)->comment('大小（字节）');
            $table->text('url')->nullable()->comment('资料URL');
            $table->string('extension', 100)->default('')->comment('扩展名');
            $table->unsignedTinyInteger('is_type')->index()->default(0)->comment('类型（1：图片；2：文档；3：视频；4：音频；5：其他；）');
            $table->unsignedInteger('storage_id')->index()->default(0)->comment('存储ID');
            $table->unsignedBigInteger('created_user')->default(0)->comment('创建人');
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
    }
};
