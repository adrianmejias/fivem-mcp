<?php

namespace App\Mcp\Tools\QBCore\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up QBCore client-side functions by name. Returns function signature, parameters, return type, and usage examples.')]
class GetQBCoreClientFunction extends Tool
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
            return Response::text(sprintf("QBCore client function '%s' not found. Check QBCore documentation at https://docs.qbcore.org/", $functionName));
        }

        $output = $this->formatFunctionInfo($function, $language);

        return Response::text($output);
    }

    /**
     * Find a QBCore client function.
     */
    protected function findFunction(string $name): ?array
    {
        $functions = [
            'QBCore.Functions.GetPlayerData' => [
                'namespace' => 'Core',
                'description' => 'Get the local player data (client-side only). Contains all player information.',
                'side' => 'client',
                'parameters' => [],
                'returns' => [
                    'type' => 'table',
                    'description' => 'Player data table with keys: source, charinfo, job, gang, money, items, inventory, licenses, etc.',
                ],
                'lua_example' => "local playerData = QBCore.Functions.GetPlayerData()\nif playerData then\n    print('Name: ' .. playerData.charinfo.firstname .. ' ' .. playerData.charinfo.lastname)\n    print('Job: ' .. playerData.job.name)\nend",
                'js_example' => "const playerData = QBCore.Functions.GetPlayerData();\nif (playerData) {\n    console.log(`Name: \${playerData.charinfo.firstname} \${playerData.charinfo.lastname}`);\n    console.log(`Job: \${playerData.job.name}`);\n}",
            ],
            'QBCore.Functions.TriggerCallback' => [
                'namespace' => 'Core',
                'description' => 'Call a server-side callback from the client and wait for response (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'The callback name'],
                    ['name' => 'function', 'type' => 'function', 'description' => 'Callback function to execute with result'],
                    ['name' => '...', 'type' => 'any', 'description' => 'Arguments to pass to server callback'],
                ],
                'returns' => ['type' => 'void', 'description' => 'Result passed to callback function'],
                'lua_example' => "QBCore.Functions.TriggerCallback('myResource:getPlayerInfo', function(result)\n    if result then\n        print('Got result: ' .. result.name)\n    end\nend, playerId)",
                'js_example' => "QBCore.Functions.TriggerCallback('myResource:getPlayerInfo', function(result) {\n    if (result) {\n        console.log(`Got result: \${result.name}`);\n    }\n}, playerId);",
            ],
            'QBCore.Commands.Add' => [
                'namespace' => 'Client',
                'description' => 'Register a client-side command (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'name', 'type' => 'string', 'description' => 'Command name (without slash)'],
                    ['name' => 'help', 'type' => 'string', 'description' => 'Help text for command'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Function to execute when command runs'],
                    ['name' => 'restricted', 'type' => 'boolean|nil', 'description' => 'Whether command requires admin'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "QBCore.Commands.Add('testcmd', 'Test Command', {}, false, function(args)\n    print('Command executed!')\nend)",
                'js_example' => "QBCore.Commands.Add('testcmd', 'Test Command', {}, false, function(args) {\n    console.log('Command executed!');\n});",
            ],
            'QBCore.UI.DrawText3D' => [
                'namespace' => 'UI',
                'description' => 'Draw 3D text in the game world (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'x', 'type' => 'number', 'description' => 'World X coordinate'],
                    ['name' => 'y', 'type' => 'number', 'description' => 'World Y coordinate'],
                    ['name' => 'z', 'type' => 'number', 'description' => 'World Z coordinate'],
                    ['name' => 'text', 'type' => 'string', 'description' => 'Text to display'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "QBCore.UI.DrawText3D(100.0, 200.0, 50.0, 'Press [E] to interact')",
                'js_example' => "QBCore.UI.DrawText3D(100.0, 200.0, 50.0, 'Press [E] to interact');",
            ],
            'TriggerEvent' => [
                'namespace' => 'FiveM Core',
                'description' => 'Trigger a local client-side event (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'The event name to trigger'],
                    ['name' => '...', 'type' => 'any', 'description' => 'Arguments to pass to event listeners'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "TriggerEvent('playerLoaded', playerData)\n-- Listen with:\n-- RegisterNetEvent('playerLoaded', function(playerData)\n--     print('Player loaded!')\n-- end)",
                'js_example' => "TriggerEvent('playerLoaded', playerData);\n// Listen with:\n// on('playerLoaded', (playerData) => {\n//     console.log('Player loaded!');\n// });",
            ],
            'RegisterNetEvent' => [
                'namespace' => 'FiveM Core',
                'description' => 'Register an event listener from the server (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'Event name to listen for'],
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Function to call when event fires'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoad')\nAddEventHandler('QBCore:Client:OnPlayerLoad', function()\n    local playerData = QBCore.Functions.GetPlayerData()\n    print('Player loaded: ' .. playerData.charinfo.firstname)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoad');\nAddEventHandler('QBCore:Client:OnPlayerLoad', function() {\n    const playerData = QBCore.Functions.GetPlayerData();\n    console.log(`Player loaded: \${playerData.charinfo.firstname}`);\n});",
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
        return view('mcp.qbcore-function', [
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
                ->description('The name of the QBCore client function to look up (e.g., "QBCore.Functions.GetPlayerData", "QBCore.Commands.Add")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
