<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get information about COX MySQL events and callbacks.')]
class GetCOXEventReference extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $eventName = $request->get('event_name');
        $eventType = $request->get('event_type', 'all');
        $language = $request->get('language', 'lua');

        if ($eventName) {
            return $this->getEventInfo($eventName, $language);
        }

        return $this->listEvents($eventType, $language);
    }

    /**
     * Get specific event information.
     */
    protected function getEventInfo(string $eventName, string $language): Response
    {
        $events = $this->getCOXEventsDatabase();

        foreach ($events as $event) {
            if (strtolower($event['name']) === strtolower($eventName)) {
                return Response::text($this->formatEventInfo($event, $language));
            }
        }

        return Response::text(sprintf("COX event '%s' not found in the database.", $eventName));
    }

    /**
     * List events by type.
     */
    protected function listEvents(string $type, string $language): Response
    {
        $events = $this->getCOXEventsDatabase();

        if ($type !== 'all') {
            $events = array_filter($events, fn ($e) => $e['type'] === $type);
        }

        $eventsByType = [];
        foreach ($events as $event) {
            $eventsByType[$event['type']][] = $event;
        }

        return Response::text(
            view('mcp.cox-event-list', [
                'eventsByType' => $eventsByType,
            ])->render()
        );
    }

    /**
     * Format event information.
     */
    protected function formatEventInfo(array $event, string $language): string
    {
        return view('mcp.cox-event-reference', [
            'event' => $event,
            'language' => $language,
        ])->render();
    }

    /**
     * Get COX events database.
     */
    protected function getCOXEventsDatabase(): array
    {
        return [
            // Query Events
            [
                'name' => 'coxMySQL:queryCompleted',
                'type' => 'query',
                'description' => 'Triggered when a query completes (success or failure)',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'The SQL query that was executed'],
                    ['name' => 'result', 'type' => 'table|boolean', 'description' => 'Query result or false if failed'],
                ],
                'lua_example' => "RegisterNetEvent('coxMySQL:queryCompleted', function(sql, result)\n    if result then\n        print('Query succeeded')\n    else\n        print('Query failed: ' .. sql)\n    end\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:queryCompleted', (sql, result) => {\n    if (result) {\n        console.log('Query succeeded');\n    } else {\n        console.log('Query failed: ' + sql);\n    }\n});",
            ],
            [
                'name' => 'coxMySQL:insertCompleted',
                'type' => 'query',
                'description' => 'Triggered when an insert query completes',
                'parameters' => [
                    ['name' => 'insertId', 'type' => 'int', 'description' => 'ID of the inserted row'],
                    ['name' => 'affectedRows', 'type' => 'int', 'description' => 'Number of rows affected'],
                ],
                'lua_example' => "RegisterNetEvent('coxMySQL:insertCompleted', function(insertId, affectedRows)\n    print('Inserted ID: ' .. insertId .. ', Rows affected: ' .. affectedRows)\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:insertCompleted', (insertId, affectedRows) => {\n    console.log(`Inserted ID: \${insertId}, Rows affected: \${affectedRows}`);\n});",
            ],

            // Connection Events
            [
                'name' => 'coxMySQL:connected',
                'type' => 'connection',
                'description' => 'Triggered when the database connection is established',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('coxMySQL:connected', function()\n    print('Database connected successfully!')\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:connected', () => {\n    console.log('Database connected successfully!');\n});",
            ],
            [
                'name' => 'coxMySQL:disconnected',
                'type' => 'connection',
                'description' => 'Triggered when the database connection closes',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('coxMySQL:disconnected', function()\n    print('Database disconnected')\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:disconnected', () => {\n    console.log('Database disconnected');\n});",
            ],
            [
                'name' => 'coxMySQL:connectionError',
                'type' => 'connection',
                'description' => 'Triggered when a database connection error occurs',
                'parameters' => [
                    ['name' => 'error', 'type' => 'string', 'description' => 'Error message'],
                ],
                'lua_example' => "RegisterNetEvent('coxMySQL:connectionError', function(error)\n    print('Connection error: ' .. error)\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:connectionError', (error) => {\n    console.log('Connection error: ' + error);\n});",
            ],

            // Transaction Events
            [
                'name' => 'coxMySQL:transactionBegun',
                'type' => 'transaction',
                'description' => 'Triggered when a database transaction begins',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('coxMySQL:transactionBegun', function()\n    print('Transaction started')\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:transactionBegun', () => {\n    console.log('Transaction started');\n});",
            ],
            [
                'name' => 'coxMySQL:transactionCommitted',
                'type' => 'transaction',
                'description' => 'Triggered when a transaction is successfully committed',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('coxMySQL:transactionCommitted', function()\n    print('Transaction committed successfully')\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:transactionCommitted', () => {\n    console.log('Transaction committed successfully');\n});",
            ],
            [
                'name' => 'coxMySQL:transactionRolledBack',
                'type' => 'transaction',
                'description' => 'Triggered when a transaction is rolled back',
                'parameters' => [],
                'lua_example' => "RegisterNetEvent('coxMySQL:transactionRolledBack', function()\n    print('Transaction rolled back')\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:transactionRolledBack', () => {\n    console.log('Transaction rolled back');\n});",
            ],

            // Error Events
            [
                'name' => 'coxMySQL:queryError',
                'type' => 'error',
                'description' => 'Triggered when a query execution fails',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'The SQL that failed'],
                    ['name' => 'errorMessage', 'type' => 'string', 'description' => 'MySQL error message'],
                ],
                'lua_example' => "RegisterNetEvent('coxMySQL:queryError', function(sql, errorMessage)\n    print('Query error: ' .. errorMessage)\n    print('Failed query: ' .. sql)\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:queryError', (sql, errorMessage) => {\n    console.log('Query error: ' + errorMessage);\n    console.log('Failed query: ' + sql);\n});",
            ],
            [
                'name' => 'coxMySQL:slowQuery',
                'type' => 'error',
                'description' => 'Triggered when a query exceeds the configured timeout',
                'parameters' => [
                    ['name' => 'sql', 'type' => 'string', 'description' => 'The slow SQL query'],
                    ['name' => 'duration', 'type' => 'float', 'description' => 'Query duration in milliseconds'],
                ],
                'lua_example' => "RegisterNetEvent('coxMySQL:slowQuery', function(sql, duration)\n    print('Slow query (' .. duration .. 'ms): ' .. sql)\nend)",
                'js_example' => "RegisterNetEvent('coxMySQL:slowQuery', (sql, duration) => {\n    console.log(`Slow query (\${duration}ms): \${sql}`);\n});",
            ],
        ];
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
                ->description('Specific COX event name to look up (optional, leave empty to list all events)'),
            'event_type' => $schema
                ->string()
                ->enum(['all', 'query', 'connection', 'transaction', 'error'])
                ->description('Filter events by type')
                ->default('all'),
            'language' => $schema
                ->string()
                ->enum(['lua', 'js'])
                ->description('The scripting language for code examples')
                ->default('lua'),
        ];
    }
}
