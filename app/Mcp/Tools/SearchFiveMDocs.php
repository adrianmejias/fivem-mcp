<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Search FiveM documentation for specific topics, concepts, or features. Returns relevant documentation URLs and summaries.')]
class SearchFiveMDocs extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $query = $request->get('query');
        $category = $request->get('category', 'all');

        $results = $this->searchDocs($query, $category);

        if (empty($results)) {
            return Response::text(sprintf('No documentation found for query: %s', $query));
        }

        return Response::text(
            view('mcp.search-results', [
                'query' => $query,
                'results' => $results,
            ])->render()
        );
    }

    /**
     * Search the FiveM documentation.
     */
    protected function searchDocs(string $query, string $category): array
    {
        $baseUrl = 'https://docs.fivem.net/docs/';

        // Common documentation pages organized by category
        $docs = [
            'scripting' => [
                [
                    'title' => 'Scripting Introduction',
                    'url' => sprintf('%sscripting-manual/introduction/', $baseUrl),
                    'description' => 'Learn the basics of FiveM scripting with Lua and JavaScript',
                    'keywords' => ['script', 'introduction', 'basics', 'getting started', 'lua', 'javascript'],
                ],
                [
                    'title' => 'Client Functions',
                    'url' => sprintf('%sscripting-reference/client-functions/', $baseUrl),
                    'description' => 'Client-side native functions for game interaction',
                    'keywords' => ['client', 'native', 'function', 'game', 'player'],
                ],
                [
                    'title' => 'Server Functions',
                    'url' => sprintf('%sscripting-reference/server-functions/', $baseUrl),
                    'description' => 'Server-side functions for player management and game logic',
                    'keywords' => ['server', 'function', 'player', 'management'],
                ],
                [
                    'title' => 'Resource Manifest',
                    'url' => sprintf('%sscripting-reference/resource-manifest/resource-manifest/', $baseUrl),
                    'description' => 'Configure your resource with fxmanifest.lua',
                    'keywords' => ['manifest', 'fxmanifest', 'resource', 'config', 'configuration'],
                ],
            ],
            'natives' => [
                [
                    'title' => 'Native Reference',
                    'url' => sprintf('%snatives/', $baseUrl),
                    'description' => 'Complete reference of GTA5 native functions',
                    'keywords' => ['native', 'reference', 'gta', 'function'],
                ],
                [
                    'title' => 'CFX Natives',
                    'url' => sprintf('%snatives/?n_CFX', $baseUrl),
                    'description' => 'FiveM-specific native functions (CFX namespace)',
                    'keywords' => ['cfx', 'native', 'fivem', 'custom'],
                ],
            ],
            'networking' => [
                [
                    'title' => 'Network Events',
                    'url' => sprintf('%sscripting-manual/networking/events/', $baseUrl),
                    'description' => 'Client-server communication using events',
                    'keywords' => ['event', 'network', 'trigger', 'client', 'server', 'communication'],
                ],
                [
                    'title' => 'State Bags',
                    'url' => sprintf('%sscripting-manual/networking/state-bags/', $baseUrl),
                    'description' => 'Synchronized state management across clients and server',
                    'keywords' => ['state', 'bag', 'sync', 'data', 'shared'],
                ],
            ],
            'resources' => [
                [
                    'title' => 'Creating Resources',
                    'url' => sprintf('%sscripting-manual/introduction/creating-your-first-script/', $baseUrl),
                    'description' => 'Step-by-step guide to creating your first FiveM resource',
                    'keywords' => ['create', 'resource', 'first', 'tutorial', 'guide'],
                ],
                [
                    'title' => 'Resource Structure',
                    'url' => sprintf('%sscripting-manual/introduction/about-resources/', $baseUrl),
                    'description' => 'Understanding FiveM resource folder structure and organization',
                    'keywords' => ['structure', 'folder', 'organization', 'resource', 'files'],
                ],
            ],
            'qbcore' => [
                [
                    'title' => 'QBCore Documentation',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/',
                    'description' => 'Official QBCore framework documentation and guides',
                    'keywords' => ['qbcore', 'framework', 'documentation', 'guide'],
                ],
                [
                    'title' => 'QBCore Getting Started',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/get-started',
                    'description' => 'Getting started with QBCore framework setup and basics',
                    'keywords' => ['qbcore', 'getting started', 'setup', 'installation', 'begin'],
                ],
                [
                    'title' => 'QBCore Callbacks & Exports',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/callbacks-exports',
                    'description' => 'Learn about QBCore callbacks and exports for inter-resource communication',
                    'keywords' => ['qbcore', 'callback', 'export', 'function', 'communication'],
                ],
                [
                    'title' => 'QBCore Items & Inventory',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/items',
                    'description' => 'Item system and inventory management in QBCore',
                    'keywords' => ['qbcore', 'items', 'inventory', 'item', 'database'],
                ],
                [
                    'title' => 'QBCore Jobs & Grades',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/jobs',
                    'description' => 'Job system and player grades in QBCore',
                    'keywords' => ['qbcore', 'job', 'grades', 'employment', 'boss'],
                ],
                [
                    'title' => 'QBCore Player Management',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/player',
                    'description' => 'Managing player data and interactions in QBCore',
                    'keywords' => ['qbcore', 'player', 'data', 'management', 'character'],
                ],
                [
                    'title' => 'QBCore Vehicles',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/vehicles',
                    'description' => 'Vehicle system and ownership in QBCore',
                    'keywords' => ['qbcore', 'vehicle', 'car', 'ownership', 'garage'],
                ],
                [
                    'title' => 'QBCore Events',
                    'url' => 'https://docs.qbcore.org/qbcore-documentation/core-concepts/events',
                    'description' => 'QBCore framework events for player and game actions',
                    'keywords' => ['qbcore', 'event', 'trigger', 'listener'],
                ],
            ],
        ];

        $results = [];
        $searchIn = $category === 'all' ? array_merge(...array_values($docs)) : ($docs[$category] ?? []);
        $queryLower = strtolower($query);

        foreach ($searchIn as $doc) {
            $matchScore = 0;

            // Check title
            if (str_contains(strtolower($doc['title']), $queryLower)) {
                $matchScore += 10;
            }

            // Check keywords
            foreach ($doc['keywords'] as $keyword) {
                if (str_contains(strtolower($keyword), $queryLower) || str_contains($queryLower, strtolower($keyword))) {
                    $matchScore += 5;
                }
            }

            // Check description
            if (str_contains(strtolower($doc['description']), $queryLower)) {
                $matchScore += 3;
            }

            if ($matchScore > 0) {
                $results[] = array_merge($doc, ['score' => $matchScore]);
            }
        }

        // Sort by score descending
        usort($results, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($results, 0, 5);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema
                ->string()
                ->description('The search query for FiveM documentation (e.g., "events", "natives", "manifest")')
                ->required(),
            'category' => $schema
                ->string()
                ->enum(['all', 'scripting', 'natives', 'networking', 'resources', 'qbcore'])
                ->description('Filter results by documentation category')
                ->default('all'),
        ];
    }
}
