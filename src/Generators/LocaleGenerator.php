<?php

namespace InfyOm\Generator\Generators;

use Illuminate\Support\Str;
use Symfony\Component\VarExporter\VarExporter;

class LocaleGenerator extends BaseGenerator
{
    private string $fileName;

    public function __construct()
    {
        parent::__construct();

        $this->path = lang_path('en/models/');
        $this->fileName = $this->config->modelNames->snakePlural . '.php';
    }

    public function generate()
    {
        $locales = [
            'singular' => $this->config->modelNames->human,
            'plural'   => $this->config->modelNames->humanPlural,
            'fields'   => [],
        ];

        foreach ($this->config->fields as $field) {
            $locales['fields'][$field->name] = Str::title(str_replace('_', ' ', $field->name));
        }

        $locales = VarExporter::export($locales);
        $end = ';' . infy_nl();
        $content = "<?php\n\nreturn " . $locales . $end;

        g_filesystem()->createFile($this->path . $this->fileName, $content);

        $this->config->commandComment(infy_nl() . 'Model Locale File created: ');
        $this->config->commandInfo($this->fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->config->commandComment('Model Locale File deleted: ' . $this->fileName);
        }
    }
}
