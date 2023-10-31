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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plateform_id')->nullable();
            $table->foreign('plateform_id')->references('id')->on('plateforms')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('package_name')->nullable()->unique();
            $table->string('logo')->nullable();
            $table->string('logo_type')->nullable();
            $table->enum('status',['suspended','NotPublish','Published'])->default('NotPublish')->comment('Status of Application.');
            $table->boolean('is_notified')->default(false);
            $table->boolean('is_trashed')->default(false);
            $table->dateTime('treshed_at')->nullable();
            $table->string('newpackagename')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
