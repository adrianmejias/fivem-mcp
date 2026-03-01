<?php

namespace App\Mcp\Tools\QBCore\Client;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('QBCore event reference for client-side events. Includes built-in core events and framework-specific events for client scripts.')]
class GetQBCoreClientEventReference extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $eventName = $request->get('event_name', '');

        $events = $this->getClientEvents();

        if ($eventName) {
            $event = collect($events)->firstWhere('name', $eventName);
            if ($event) {
                return Response::text($this->formatEvent($event));
            }

            return Response::text("Client-side event '$eventName' not found.");
        }

        return Response::text($this->formatEventList($events, 'QBCore Client Events'));
    }

    /**
     * Get all client-side events.
     */
    protected function getClientEvents(): array
    {
        return [
            [
                'name' => 'QBCore:Client:OnPlayerLoad',
                'side' => 'client',
                'description' => 'Fired when the player loads on the client (character selected)',
                'parameters' => ['PlayerData' => 'The player data table'],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoad')\nAddEventHandler('QBCore:Client:OnPlayerLoad', function()\n    local playerData = QBCore.Functions.GetPlayerData()\n    print('Player loaded: ' .. playerData.charinfo.firstname)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnPlayerLoad');\nAddEventHandler('QBCore:Client:OnPlayerLoad', function() {\n    const playerData = QBCore.Functions.GetPlayerData();\n    console.log(`Player loaded: \${playerData.charinfo.firstname}`);\n});",
            ],
            [
                'name' => 'QBCore:Client:OnPlayerUnload',
                'side' => 'client',
                'description' => 'Fired when the player unloads (leaving character/logging out)',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnPlayerUnload')\nAddEventHandler('QBCore:Client:OnPlayerUnload', function()\n    print('Player unloaded')\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnPlayerUnload');\nAddEventHandler('QBCore:Client:OnPlayerUnload', function() {\n    console.log('Player unloaded');\n});",
            ],
            [
                'name' => 'QBCore:Client:OnJobUpdate',
                'side' => 'client',
                'description' => 'Fired when the player\'s job is updated locally',
                'parameters' => ['JobInfo' => 'The job table with name, grade, etc'],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnJobUpdate')\nAddEventHandler('QBCore:Client:OnJobUpdate', function(JobInfo)\n    print('Job updated: ' .. JobInfo.name)\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnJobUpdate');\nAddEventHandler('QBCore:Client:OnJobUpdate', function(JobInfo) {\n    console.log(`Job updated: \${JobInfo.name}`);\n});",
            ],
            [
                'name' => 'QBCore:Client:OnMoneyChange',
                'side' => 'client',
                'description' => 'Fired when the player\'s money amount changes',
                'parameters' => ['MoneyType' => 'Type of money changed', 'Amount' => 'New amount', 'ChangedAmount' => 'Amount changed'],
                'lua_example' => "AddEventHandler('QBCore:Client:OnMoneyChange', function(MoneyType, Amount, ChangedAmount)\n    print('Money changed: ' .. ChangedAmount .. ' (' .. MoneyType .. ')')\nend)",
                'js_example' => "AddEventHandler('QBCore:Client:OnMoneyChange', function(MoneyType, Amount, ChangedAmount) {\n    console.log(`Money changed: \${ChangedAmount} (\${MoneyType})`);\n});",
            ],
            [
                'name' => 'QBCore:Client:OnItemUpdate',
                'side' => 'client',
                'description' => 'Fired when an item in the player\'s inventory updates',
                'parameters' => ['inventory' => 'Updated inventory table'],
                'lua_example' => "RegisterNetEvent('QBCore:Client:OnItemUpdate')\nAddEventHandler('QBCore:Client:OnItemUpdate', function(inventory)\n    print('Inventory updated')\nend)",
                'js_example' => "RegisterNetEvent('QBCore:Client:OnItemUpdate');\nAddEventHandler('QBCore:Client:OnItemUpdate', function(inventory) {\n    console.log('Inventory updated');\n});",
            ],
            [
                'name' => 'QBCore:Notify',
                'side' => 'client',
                'description' => 'Send a notification to the player client',
                'parameters' => ['message' => 'Notification message', 'type' => 'Type (success, error, info)', 'duration' => 'Duration in milliseconds'],
                'lua_example' => "TriggerEvent('QBCore:Notify', 'Hello World!', 'success')\n-- Server trigger:\n-- TriggerClientEvent('QBCore:Notify', source, 'Hello World!', 'success')",
                'js_example' => "TriggerEvent('QBCore:Notify', 'Hello World!', 'success');\n// Server trigger:\n// TriggerClientEvent('QBCore:Notify', global.source, 'Hello World!', 'success');",
            ],
        ];
    }

    /**
     * Format a single event.
     */
    protected function formatEvent(array $event): string
    {
        return <<<EOT
**Event: {$event['name']}**

**Side:** Client

**Description:** {$event['description']}

**Parameters:**
{$this->formatParameters($event['parameters'])}

**Lua Example:**
\`\`\`lua
{$event['lua_example']}
\`\`\`

**JavaScript Example:**
\`\`\`javascript
{$event['js_example']}
\`\`\`
EOT;
    }

    /**
     * Format event list.
     */
    protected function formatEventList(array $events, string $title): string
    {
        $list = "# {$title}\n\n";

        foreach ($events as $event) {
            $list .= "- **{$event['name']}**: {$event['description']}\n";
        }

        return $list;
    }

    /**
     * Format parameters list.
     */
    protected function formatParameters(array $parameters): string
    {
        $formatted = '';
        foreach ($parameters as $name => $description) {
            $formatted .= "- `{$name}`: {$description}\n";
        }

        return $formatted;
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'event_name' => $schema
                ->string()
                ->description('Specific event name to look up, or leave empty to list all client events')
                ->default(''),
        ];
    }
}
