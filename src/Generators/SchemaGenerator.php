<?php

namespace InfyOm\Generator\Generators;

class SchemaGenerator extends BaseGenerator
{
    private string $fileName;

    public function __construct()
    {
        parent::__construct();

        $this->path = config('laravel_generator.path.schema_files', resource_path('model_schemas/'));
        $this->fileName = $this->config->modelNames->name . '.json';
    }

    public function generate()
    {
        $fileFields = [];

        foreach ($this->config->fields as $field) {
            $fileFields[] = [
                'name'        => $field->name,
                'dbType'      => $field->dbType,
                'htmlType'    => $field->htmlType,
                'validations' => $field->validations,
                'searchable'  => $field->isSearchable,
                'fillable'    => $field->isFillable,
                'primary'     => $field->isPrimary,
                'inForm'      => $field->inForm,
                'inIndex'     => $field->inIndex,
                'inView'      => $field->inView,
            ];
        }

        foreach ($this->config->relations as $relation) {
            $fileFields[] = [
                'type'     => 'relation',
                'relation' => $relation->type . ',' . implode(',', $relation->inputs),
            ];
        }

        g_filesystem()->createFile($this->path . $this->fileName, json_encode($fileFields, JSON_PRETTY_PRINT));

        $this->config->commandComment(infy_nl() . 'Schema File created: ');
        $this->config->commandInfo($this->fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->config->commandComment('Schema File deleted: ' . $this->fileName);
        }
    }
}
