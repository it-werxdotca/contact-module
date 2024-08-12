<?php

namespace Modules\Contact\App\Providers;

use Illuminate\Support\ServiceProvider;
use Qirolab\Theme\Theme;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        try {
            // Set the active theme from the config file
            $activeTheme = config('theme.active', 'default');
            // Set the active theme
            Theme::set($activeTheme);
            // Register theme views
            $themePath = public_path("themes/{$activeTheme}/views");
            if (is_dir($themePath)) {
                View::addNamespace($activeTheme, $themePath);
               // Log::info("AppServiceProvider: Theme view path registered for {$activeTheme} at {$themePath}");
            } else {
                Log::warning("AppServiceProvider: Theme view path does not exist for {$activeTheme} at {$themePath}");
            }

            // Register module views
            $moduleViewsPath = $this->getModuleViewPath($activeTheme);

            if ($moduleViewsPath) {
                View::addNamespace('Modules', $moduleViewsPath);
                //Log::info("AppServiceProvider: Module view path registered at {$moduleViewsPath}");
            } else {
                Log::warning("AppServiceProvider: No valid module view path found.");
            }

        } catch (\Exception $e) {
            Log::error("AppServiceProvider: Error - " . $e->getMessage());
        }
    }

    public function register() {
        // Register any application services
    }

    protected function getModuleViewPath($activeTheme)
    {
        // Initialize the variable for the views path
        $viewsPath = null;

        try {
            // Ensure the modules status file exists and is readable
            $modulesStatusPath = base_path('modules_statuses.json');
            if (!File::exists($modulesStatusPath) || !File::isReadable($modulesStatusPath)) {
                Log::warning("AppServiceProvider: Modules statuses file is missing or not readable at {$modulesStatusPath}");
                return null;
            }

            // Load active modules from the JSON file
            $activeModules = json_decode(File::get($modulesStatusPath), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("AppServiceProvider: Error decoding JSON from modules statuses file - " . json_last_error_msg());
                return null;
            }

            // Retrieve all modules
            $allModules = Module::all();
            $activeModulesList = collect($allModules)->filter(function ($module) use ($activeModules) {
                return in_array($module->getName(), $activeModules);
            });

            if ($activeModulesList->isEmpty()) {
                Log::warning("AppServiceProvider: No active modules found.");
                return null;
            }

            // Check each module's views path
            foreach ($activeModulesList as $module) {
                $viewsPath = $module->getPath() . '/resources/views'; // Fixed path

                if (is_dir($viewsPath)) {
                    //Log::info("AppServiceProvider: Module view path found for {$module->getName()} at {$viewsPath}");
                    return $viewsPath;
                } else {
                    Log::warning("AppServiceProvider: Module view path does not exist for {$module->getName()} at {$viewsPath}");
                }
            }

        } catch (\Exception $e) {
            Log::error("AppServiceProvider: Error checking module view paths - " . $e->getMessage());
        }

        return $viewsPath; // Return null if no valid views path is found
    }
}
