<?php

namespace App\Mcp\Tools\COX\Fuel\Server;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_fuel server functions by name. Returns function signature, parameters, return type, and usage examples for server-side fuel management.')]
class GetOxFuelServerFunction extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $functionName = $request->get('function_name');
        $language = $request->get('language', 'lua');

        $function = $this->findFunction($functionName);

        if (! $function) {
            return Response::text(sprintf("ox_fuel server function '%s' not found. Check ox_fuel documentation at https://coxdocs.dev/ox_fuel", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_fuel server function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'setPaymentMethod' => [
                'namespace' => 'Server',
                'description' => 'Set a custom payment method for fuel purchases',
                'side' => 'server',
                'parameters' => [
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Function(source: number, amount: number) -> boolean that handles payment and returns true if successful'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports.ox_fuel:setPaymentMethod(function(source, amount)\n    local success = exports.framework:RemovePlayerMoney(source, amount)\n    if success then\n        print('Player '..source..' paid $'..amount..' for fuel')\n    end\n    return success\nend)",
                'js_example' => "exports.ox_fuel.setPaymentMethod((source, amount) => {\n    const success = exports.framework.RemovePlayerMoney(source, amount);\n    if (success) {\n        console.log(`Player \${source} paid $\${amount} for fuel`);\n    }\n    return success;\n});",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($functions as $functionName => $functionData) {
            if (strtolower($functionName) === $nameLower) {
                return array_merge(['name' => $functionName], $functionData);
            }
        }

        return null;
    }

    /**
     * Format function info.
     */
    protected function formatFunctionInfo(array $function, string $language): string
    {
        return view('mcp.cox.cox-function', [
            'function' => $function,
            'language' => $language,
        ])->render();
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'function_name' => $schema
                ->string()
                ->description('The name of the ox_fuel server function to look up (e.g., "setPaymentMethod")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
