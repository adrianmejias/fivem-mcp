<?php

namespace App\Mcp\Tools\COX\Fuel\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up ox_fuel client functions by name. Returns function signature, parameters, return type, and usage examples for vehicle fuel management.')]
class GetOxFuelClientFunction extends Tool
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
            return Response::text(sprintf("ox_fuel client function '%s' not found. Check ox_fuel documentation at https://coxdocs.dev/ox_fuel", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find an ox_fuel client function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'setMoneyCheck' => [
                'namespace' => 'Client',
                'description' => 'Set a custom function to check if a player has enough money to pay for fuel',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Function(amount: number) -> boolean that returns true if player can afford fuel'],
                ],
                'returns' => ['type' => 'void', 'description' => 'No return value'],
                'lua_example' => "exports.ox_fuel:setMoneyCheck(function(amount)\n    local playerMoney = exports.framework:GetPlayerMoney()\n    return playerMoney >= amount\nend)",
                'js_example' => "exports.ox_fuel.setMoneyCheck((amount) => {\n    const playerMoney = exports.framework.GetPlayerMoney();\n    return playerMoney >= amount;\n});",
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
                ->description('The name of the ox_fuel client function to look up (e.g., "setMoneyCheck")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
