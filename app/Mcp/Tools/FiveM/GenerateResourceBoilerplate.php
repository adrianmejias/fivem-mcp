<?php

namespace App\Mcp\Tools\FiveM;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Generate boilerplate code for a FiveM resource including client, server, config, and manifest files.')]
class GenerateResourceBoilerplate extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $resourceName = $request->get('resource_name');
        $framework = $request->get('framework', 'standalone');
        $scriptType = $request->get('script_type', 'lua');
        $includeNUI = $request->get('include_nui', false);

        $files = $this->generateBoilerplate([
            'resource_name' => $resourceName,
            'framework' => $framework,
            'script_type' => $scriptType,
            'include_nui' => $includeNUI,
        ]);

        return Response::text(
            view('mcp.shared.resource-boilerplate', [
                'resourceName' => $resourceName,
                'files' => $files,
            ])->render()
        );
    }

    /**
     * Generate boilerplate files.
     */
    protected function generateBoilerplate(array $config): array
    {
        $files = [];
        $ext = $config['script_type'] === 'js' ? 'js' : 'lua';

        // Config file
        $files[sprintf('config.%s', $ext)] = $this->generateConfig($config);

        // Client file
        $files[sprintf('client/main.%s', $ext)] = $this->generateClient($config);

        // Server file
        $files[sprintf('server/main.%s', $ext)] = $this->generateServer($config);

        // NUI files if requested
        if ($config['include_nui']) {
            $files['html/index.html'] = $this->generateNUIHTML();
            $files['html/style.css'] = $this->generateNUICSS();
            $files[sprintf('html/script.%s', $ext)] = $this->generateNUIScript($config);
        }

        return $files;
    }

    /**
     * Generate config file.
     */
    protected function generateConfig(array $config): string
    {
        $template = $config['script_type'] === 'js' ? 'config-js' : 'config-lua';

        return view(sprintf('mcp.templates.%s', $template))->render();
    }

    /**
     * Generate client file.
     */
    protected function generateClient(array $config): string
    {
        $template = $config['script_type'] === 'js' ? 'client-js' : 'client-lua';

        return view(sprintf('mcp.templates.%s', $template), [
            'framework' => $config['framework'],
        ])->render();
    }

    /**
     * Generate server file.
     */
    protected function generateServer(array $config): string
    {
        $template = $config['script_type'] === 'js' ? 'server-js' : 'server-lua';

        return view(sprintf('mcp.templates.%s', $template), [
            'framework' => $config['framework'],
        ])->render();
    }

    /**
     * Generate NUI HTML.
     */
    protected function generateNUIHTML(): string
    {
        return view('mcp.templates.nui-html')->render();
    }

    /**
     * Generate NUI CSS.
     */
    protected function generateNUICSS(): string
    {
        return view('mcp.templates.nui-css')->render();
    }

    /**
     * Generate NUI script.
     */
    protected function generateNUIScript(array $config): string
    {
        $template = $config['script_type'] === 'js' ? 'nui-script-js' : 'nui-script-lua';

        return view(sprintf('mcp.templates.%s', $template))->render();
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'resource_name' => $schema
                ->string()
                ->description('The name of the resource to generate boilerplate for')
                ->required(),
            'framework' => $schema
                ->string()
                ->enum(['standalone', 'esx', 'qbcore'])
                ->description('Framework type (standalone, ESX, or QBCore)')
                ->default('standalone'),
            'script_type' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('Scripting language (Lua or JavaScript)')
                ->default('lua'),
            'include_nui' => $schema
                ->boolean()
                ->description('Include NUI (UI) files')
                ->default(false),
        ];
    }
}
