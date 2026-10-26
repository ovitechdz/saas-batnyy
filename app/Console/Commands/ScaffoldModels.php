<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ScaffoldModels extends Command
{
    protected $signature = 'app:scaffold-models';
    protected $description = 'Automatically injects $fillable into all Models based on existing database tables.';

    public function handle()
    {
        $modelsPath = app_path('Models');
        $files = File::files($modelsPath);

        foreach ($files as $file) {
            $className = 'App\\Models\\' . $file->getFilenameWithoutExtension();
            if (!class_exists($className)) {
                continue;
            }

            try {
                $modelInstance = new $className();
                $table = $modelInstance->getTable();

                if (Schema::hasTable($table)) {
                    $columns = Schema::getColumnListing($table);
                    $fillables = array_filter($columns, function($col) {
                        return !in_array($col, ['id', 'created_at', 'updated_at', 'deleted_at']);
                    });

                    $content = File::get($file->getPathname());
                    
                    // If the file is very small or doesn't have $fillable, add it
                    if (!str_contains($content, 'protected $fillable')) {
                        $fillableString = "    protected \$fillable = [\n";
                        foreach ($fillables as $f) {
                            $fillableString .= "        '{$f}',\n";
                        }
                        $fillableString .= "    ];\n";

                        // Check if it has HasFactory, HasUuids etc on the class opening
                        if (preg_match('/class [A-Za-z0-9_]+ extends Model\s*\{/', $content, $matches)) {
                            $replacement = $matches[0] . "\n" . $fillableString;
                            $content = str_replace($matches[0], $replacement, $content);
                            File::put($file->getPathname(), $content);
                            $this->info("Scaffolded fillable for {$className}");
                        }
                    }
                } else {
                    $this->warn("Table '{$table}' not found for {$className}.");
                }
            } catch (\Throwable $e) {
                $this->error("Failed to process {$className}: " . $e->getMessage());
            }
        }
        
        $this->info('Done scaffolding models.');
    }
}
