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
            'TriggerServerEvent' => [
                'namespace' => 'FiveM Core',
                'description' => 'Trigger a server-side event from the client (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'eventName', 'type' => 'string', 'description' => 'Event name to trigger on server'],
                    ['name' => '...', 'type' => 'any', 'description' => 'Arguments to pass to server event'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "TriggerServerEvent('myResource:serverEvent', 'arg1', 'arg2')\n-- Received on server with:\n-- RegisterNetEvent('myResource:serverEvent')\n-- AddEventHandler('myResource:serverEvent', function(arg1, arg2) end)",
                'js_example' => "TriggerServerEvent('myResource:serverEvent', 'arg1', 'arg2');\n// Received on server with:\n// on('myResource:serverEvent', (arg1, arg2) => {})",
            ],
            'Citizen.Wait' => [
                'namespace' => 'FiveM Core',
                'description' => 'Wait/sleep for a specified duration in milliseconds (client-side)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'ms', 'type' => 'number', 'description' => 'Duration in milliseconds'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "print('Before wait')\nCitizen.Wait(5000)  -- Wait 5 seconds\nprint('After wait')",
                'js_example' => "console.log('Before wait');\nawait Citizen.Wait(5000);  // Wait 5 seconds\nconsole.log('After wait');",
            ],
            'Citizen.CreateThread' => [
                'namespace' => 'FiveM Core',
                'description' => 'Create an async thread (client-side)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'callback', 'type' => 'function', 'description' => 'Function to execute as thread'],
                ],
                'returns' => ['type' => 'number', 'description' => 'Thread ID'],
                'lua_example' => "Citizen.CreateThread(function()\n    while true do\n        Citizen.Wait(1000)  -- Repeat every second\n        print('This runs repeatedly')\n    end\nend)",
                'js_example' => "Citizen.CreateThread(async () => {\n    while (true) {\n        await Citizen.Wait(1000);  // Repeat every second\n        console.log('This runs repeatedly');\n    }\n});",
            ],
            'PlayerPedId' => [
                'namespace' => 'FiveM Core',
                'description' => 'Get the local player ped entity (client-side only)',
                'side' => 'client',
                'parameters' => [],
                'returns' => ['type' => 'number', 'description' => 'Player ped entity ID'],
                'lua_example' => "local playerPed = PlayerPedId()\nprint('My ped ID: ' .. playerPed)",
                'js_example' => "const playerPed = PlayerPedId();\nconsole.log(`My ped ID: \${playerPed}`);",
            ],
            'GetEntityCoords' => [
                'namespace' => 'FiveM Core',
                'description' => 'Get the coordinates of an entity (client-side)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'entity', 'type' => 'number', 'description' => 'Entity ID'],
                    ['name' => 'alive', 'type' => 'boolean|nil', 'description' => 'Whether to get alive coords (default true)'],
                ],
                'returns' => ['type' => 'vector3', 'description' => 'X, Y, Z coordinates'],
                'lua_example' => "local playerPed = PlayerPedId()\nlocal playerCoords = GetEntityCoords(playerPed)\nprint('Player at: ' .. playerCoords.x .. ', ' .. playerCoords.y .. ', ' .. playerCoords.z)",
                'js_example' => "const playerPed = PlayerPedId();\nconst playerCoords = GetEntityCoords(playerPed);\nconsole.log(`Player at: \${playerCoords.x}, \${playerCoords.y}, \${playerCoords.z}`);",
            ],
            'SetEntityCoords' => [
                'namespace' => 'FiveM Core',
                'description' => 'Set the coordinates of an entity (client-side)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'entity', 'type' => 'number', 'description' => 'Entity ID'],
                    ['name' => 'x', 'type' => 'number', 'description' => 'X coordinate'],
                    ['name' => 'y', 'type' => 'number', 'description' => 'Y coordinate'],
                    ['name' => 'z', 'type' => 'number', 'description' => 'Z coordinate'],
                    ['name' => 'alive', 'type' => 'boolean|nil', 'description' => 'Whether alive (default true)'],
                    ['name' => 'animate', 'type' => 'boolean|nil', 'description' => 'Whether to animate movement (default false)'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "local playerPed = PlayerPedId()\nSetEntityCoords(playerPed, 100.0, 200.0, 50.0, false, false, false, false)\nprint('Teleported!')",
                'js_example' => "const playerPed = PlayerPedId();\nSetEntityCoords(playerPed, 100.0, 200.0, 50.0, false, false, false, false);\nconsole.log('Teleported!');",
            ],
            'QBCore.Functions.Notify' => [
                'namespace' => 'UI',
                'description' => 'Send a notification to the local player on client (client-side only)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'text', 'type' => 'string', 'description' => 'Notification text'],
                    ['name' => 'type', 'type' => 'string', 'description' => 'Notification type: error, success, primary, warning'],
                    ['name' => 'duration', 'type' => 'number|nil', 'description' => 'Duration in milliseconds (default 5000)'],
                ],
                'returns' => ['type' => 'void', 'description' => ''],
                'lua_example' => "QBCore.Functions.Notify('Welcome to the server!', 'success', 5000)",
                'js_example' => "QBCore.Functions.Notify('Welcome to the server!', 'success', 5000);",
            ],
            'QBCore.Functions.GetResourceMeta' => [
                'namespace' => 'Core',
                'description' => 'Get a resource metadata value (client-side)',
                'side' => 'client',
                'parameters' => [
                    ['name' => 'key', 'type' => 'string', 'description' => 'Metadata key to retrieve'],
                ],
                'returns' => ['type' => 'any', 'description' => 'The metadata value or nil'],
                'lua_example' => "local version = QBCore.Functions.GetResourceMeta('version')\nif version then\n    print('QBCore Version: ' .. version)\nend",
                'js_example' => "const version = QBCore.Functions.GetResourceMeta('version');\nif (version) {\n    console.log(`QBCore Version: \${version}`);\n}",
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
        return view('mcp.qbcore.qbcore-function', [
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
                ->description('The name of the QBCore client function to look up (e.g., "QBCore.Functions.GetPlayerData", "QBCore.Commands.Add", "TriggerServerEvent", "Citizen.Wait", "PlayerPedId")')
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
