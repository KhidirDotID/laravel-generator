<?php

namespace InfyOm\Generator\Generators\Scaffold;

use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Generators\ModelGenerator;

class RequestGenerator extends BaseGenerator
{
    private string $createFileName;

    private string $updateFileName;

    public function __construct()
    {
        parent::__construct();

        $this->path = $this->config->paths->request;
        $this->createFileName = 'Create' . $this->config->modelNames->name . 'Request.php';
        $this->updateFileName = 'Update' . $this->config->modelNames->name . 'Request.php';
    }

    public function generate()
    {
        $this->generateCreateRequest();
        $this->generateUpdateRequest();
    }

    protected function generateCreateRequest()
    {
        $modelGenerator = new ModelGenerator();
        $rules = implode(',' . infy_nl_tab(1, 3), $modelGenerator->generateRules());

        $data = $this->variables();
        $data['rules'] = $rules;
        $templateData = view('laravel-generator::scaffold.request.create', $data)->render();

        g_filesystem()->createFile($this->path . $this->createFileName, $templateData);

        $this->config->commandComment(infy_nl() . 'Create Request created: ');
        $this->config->commandInfo($this->createFileName);
    }

    protected function generateUpdateRequest()
    {
        $modelGenerator = new ModelGenerator();
        $rules = implode(',' . infy_nl_tab(1, 3), $modelGenerator->generateRules());
        $uniqueRules = $modelGenerator->generateUniqueRules();

        $data['rules'] = $rules;
        $data['uniqueRules'] = $uniqueRules;
        $templateData = view('laravel-generator::scaffold.request.update', $data)->render();

        g_filesystem()->createFile($this->path . $this->updateFileName, $templateData);

        $this->config->commandComment(infy_nl() . 'Update Request created: ');
        $this->config->commandInfo($this->updateFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->createFileName)) {
            $this->config->commandComment('Create Request file deleted: ' . $this->createFileName);
        }

        if ($this->rollbackFile($this->path, $this->updateFileName)) {
            $this->config->commandComment('Update Request file deleted: ' . $this->updateFileName);
        }
    }
}
