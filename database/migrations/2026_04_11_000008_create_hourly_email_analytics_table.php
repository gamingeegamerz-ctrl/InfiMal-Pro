<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hourly_email_analytics')) {
            return;
        }

        Schema::create('hourly_email_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->unsignedBigInteger('smtp_id')->nullable();
            $table->dateTime('bucket_hour');
            $table->unsignedInteger('sent')->default(0);
            $table->unsignedInteger('delivered')->default(0);
            $table->unsignedInteger('bounced')->default(0);
            $table->unsignedInteger('complaints')->default(0);
            $table->unsignedInteger('clicked')->default(0);
            $table->unsignedInteger('replied')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'campaign_id', 'smtp_id', 'bucket_hour'], 'hourly_email_analytics_unique');
            $table->index(['user_id', 'bucket_hour'], 'hourly_email_analytics_user_hour_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hourly_email_analytics');
    }
};
