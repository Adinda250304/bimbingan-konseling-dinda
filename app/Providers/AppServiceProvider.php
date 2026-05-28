<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale('id');

        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                \Illuminate\Support\Facades\Schema::create('notifications', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->string('title');
                    $table->text('message');
                    $table->boolean('is_read')->default(false);
                    $table->timestamps();

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('konselings')) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('konselings', 'tempat')) {
                    \Illuminate\Support\Facades\Schema::table('konselings', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->string('tempat')->nullable()->after('jam_konseling');
                    });
                }
            }
        } catch (\Exception $e) {
            // Ignore database connection issues during CLI commands
        }
    }
}
