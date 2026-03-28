<?php

namespace App\Mcp\Tools\QBCore;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Look up QBCore configuration options and settings. Returns information about core config, player defaults, server settings, and framework options.')]
class GetQBCoreConfig extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $configType = $request->get('config_type');
        $language = $request->get('language', 'lua');

        $config = $this->findConfigType($configType);

        if (! $config) {
            return Response::text(sprintf("QBCore config '%s' not found. Available types: Player, Framework, Database, Callbacks, Database, Features", $configType));
        }

        $output = $this->formatConfigInfo($config, $language);

        return Response::text($output);
    }

    /**
     * Find a QBCore config type.
     */
    protected function findConfigType(string $name): ?array
    {
        $configs = [
            'Player' => [
                'category' => 'Player',
                'description' => 'Default player configuration including character generation and initial data setup.',
                'location' => 'qb-core/config.lua',
                'config_options' => [
                    ['name' => 'PlayerDefaults', 'type' => 'table', 'description' => 'Default values for new player characters (money, metadata, inventory)'],
                    ['name' => 'Metadata', 'type' => 'table', 'description' => 'Default metadata values like hunger, thirst, stress, temperature'],
                    ['name' => 'Money', 'type' => 'table', 'description' => 'Default starting money (cash, bank, crypto)'],
                    ['name' => 'Licenses', 'type' => 'table', 'description' => 'Default licenses a player starts with'],
                ],
                'lua_example' => "local QBConfig = exports['qb-core']:GetCoreObject().Config\nif QBConfig.PlayerDefaults then\n    print('Default metadata: ' .. json.encode(QBConfig.PlayerDefaults.metadata))\nend",
                'js_example' => "const QBConfig = exports['qb-core']:GetCoreObject().Config;\nif (QBConfig.PlayerDefaults) {\n    console.log('Default metadata: ' + JSON.stringify(QBConfig.PlayerDefaults.metadata));\n}",
            ],
            'Framework' => [
                'category' => 'Framework',
                'description' => 'Core framework settings including server name, version, and general options.',
                'location' => 'qb-core/config.lua',
                'config_options' => [
                    ['name' => 'ServerName', 'type' => 'string', 'description' => 'Name of your QBCore server'],
                    ['name' => 'Version', 'type' => 'string', 'description' => 'QBCore framework version'],
                    ['name' => 'Timezone', 'type' => 'string', 'description' => 'Server timezone (e.g., "America/New_York")'],
                    ['name' => 'Debug', 'type' => 'boolean', 'description' => 'Enable debug logging'],
                ],
                'lua_example' => "local QBConfig = exports['qb-core']:GetCoreObject().Config\nprint('Server Name: ' .. QBConfig.ServerName)\nprint('Version: ' .. QBConfig.Version)\nprint('Debug Mode: ' .. tostring(QBConfig.Debug))",
                'js_example' => "const QBConfig = exports['qb-core']:GetCoreObject().Config;\nconsole.log(`Server Name: \${QBConfig.ServerName}`);\nconsole.log(`Version: \${QBConfig.Version}`);\nconsole.log(`Debug Mode: \${QBConfig.Debug}`);",
            ],
            'Database' => [
                'category' => 'Database',
                'description' => 'Database configuration and connection settings.',
                'location' => 'qb-core/config.lua',
                'config_options' => [
                    ['name' => 'Backend', 'type' => 'string', 'description' => 'Database backend (ox_mysql, mysql-async, oxmysql)'],
                    ['name' => 'Host', 'type' => 'string', 'description' => 'Database server address'],
                    ['name' => 'Port', 'type' => 'number', 'description' => 'Database server port'],
                    ['name' => 'Username', 'type' => 'string', 'description' => 'Database username'],
                    ['name' => 'Password', 'type' => 'string', 'description' => 'Database password (set in .env or server.cfg)'],
                    ['name' => 'Database', 'type' => 'string', 'description' => 'Database name'],
                ],
                'lua_example' => "-- Database configuration is typically in environment variables\n-- Access through resource config if exposed\nprint('Database configuration is set in server.cfg or environment variables')",
                'js_example' => "// Database configuration is typically in environment variables\n// Access through resource config if exposed\nconsole.log('Database configuration is set in server.cfg or environment variables');",
            ],
            'Callbacks' => [
                'category' => 'Callbacks',
                'description' => 'Server-side callbacks that resources can register to respond to client requests.',
                'location' => 'qb-core/server/callbacks.lua',
                'config_options' => [
                    ['name' => 'RegisterCallback', 'type' => 'function', 'description' => 'Register a server callback that client scripts can call'],
                    ['name' => 'TriggerCallback', 'type' => 'function', 'description' => 'Trigger a callback from client to server with response'],
                ],
                'lua_example' => "-- Server-side callback registration\nQBCore.Functions.CreateCallback('myResource:getData', function(source, cb, data)\n    -- Process data from client\n    cb({success = true, message = 'Data received'})\nend)\n\n-- Client-side callback usage\nQBCore.Functions.TriggerCallback('myResource:getData', function(result)\n    print('Server response: ' .. result.message)\nend, myData)",
                'js_example' => "// Server-side callback registration\nQBCore.Functions.CreateCallback('myResource:getData', (source, cb, data) => {\n    // Process data from client\n    cb({success: true, message: 'Data received'});\n});\n\n// Client-side callback usage\nQBCore.Functions.TriggerCallback('myResource:getData', (result) => {\n    console.log(`Server response: \${result.message}`);\n}, myData);",
            ],
            'Features' => [
                'category' => 'Features',
                'description' => 'Framework feature flags and optional feature configurations.',
                'location' => 'qb-core/config.lua',
                'config_options' => [
                    ['name' => 'Encryption', 'type' => 'boolean', 'description' => 'Enable data encryption'],
                    ['name' => 'Logging', 'type' => 'boolean', 'description' => 'Enable comprehensive logging'],
                    ['name' => 'PerformanceMonitor', 'type' => 'boolean', 'description' => 'Monitor resource performance'],
                    ['name' => 'AntiCheat', 'type' => 'boolean', 'description' => 'Enable anti-cheat systems'],
                ],
                'lua_example' => "local QBConfig = exports['qb-core']:GetCoreObject().Config\nif QBConfig.Encryption then\n    print('Data encryption is enabled')\nend\nif QBConfig.AntiCheat then\n    print('Anti-cheat is enabled')\nend",
                'js_example' => "const QBConfig = exports['qb-core']:GetCoreObject().Config;\nif (QBConfig.Encryption) {\n    console.log('Data encryption is enabled');\n}\nif (QBConfig.AntiCheat) {\n    console.log('Anti-cheat is enabled');\n}",
            ],
            'RepTypes' => [
                'category' => 'RepTypes',
                'description' => 'Reputation types available for player progression. Defined in config for use with AddRep/RemoveRep.',
                'location' => 'qb-core/config.lua',
                'config_options' => [
                    ['name' => 'Examples', 'type' => 'table', 'description' => 'Common rep types: selling, heist, cartheft, etc.'],
                    ['name' => 'Custom', 'type' => 'string', 'description' => 'Add custom rep types in config for your resources'],
                ],
                'lua_example' => "-- Add rep to player (types defined in config)\nPlayer.Functions.AddRep('selling', 10)\nPlayer.Functions.AddRep('heist', 5)\nPlayer.Functions.AddRep('cartheft', 15)\n\n-- Get current rep\nlocal sellingRep = Player.Functions.GetRep('selling')\nprint('Selling Rep: ' .. sellingRep)",
                'js_example' => "// Add rep to player (types defined in config)\nPlayer.Functions.AddRep('selling', 10);\nPlayer.Functions.AddRep('heist', 5);\nPlayer.Functions.AddRep('cartheft', 15);\n\n// Get current rep\nconst sellingRep = Player.Functions.GetRep('selling');\nconsole.log(`Selling Rep: \${sellingRep}`);",
            ],
        ];

        $nameLower = strtolower($name);

        foreach ($configs as $configName => $configData) {
            if (strtolower($configName) === $nameLower) {
                return $configData;
            }
        }

        return null;
    }

    /**
     * Format config info.
     */
    protected function formatConfigInfo(array $config, string $language): string
    {
        return view('mcp.qbcore.qbcore-config', [
            'config' => $config,
            'language' => $language,
        ])->render();
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'config_type' => $schema
                ->string()
                ->description('The type of QBCore configuration to look up (Player, Framework, Database, Callbacks, Features, RepTypes)')
                ->enum(['Player', 'Framework', 'Database', 'Callbacks', 'Features', 'RepTypes'])
                ->required(),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
