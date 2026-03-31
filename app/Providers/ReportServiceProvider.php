<?php

namespace App\Providers;

use App\Services\ReportExporters\ExcelExporter;
use App\Services\ReportExporters\PdfExporter;
use App\Services\ReportExporters\WordExporter;
use App\Services\ReportService;
use Illuminate\Support\ServiceProvider;

class ReportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ReportService::class, function ($app) {
            // An array of exporter strategies
            $exporters = [
                'pdf'   => $app->make(PdfExporter::class),
                'excel' => $app->make(ExcelExporter::class),
                'word'  => $app->make(WordExporter::class),
            ];

            return new ReportService($exporters);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
