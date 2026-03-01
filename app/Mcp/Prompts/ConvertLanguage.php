<?php

namespace App\Mcp\Prompts;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Prompt;

#[Description('Convert code between Lua and JavaScript')]
class ConvertLanguage extends Prompt
{
    public function handle(Request $request): Response
    {
        $code = $request->get('code', '');
        $from = $request->get('from', 'lua');
        $to = $request->get('to', 'js');

        $parts = [sprintf("Convert this FiveM code from %s to %s:\n\n", $from, $to)];

        if ($code) {
            $parts[] = sprintf("```%s\n%s\n```\n\n", $from, $code);
        }

        $parts[] = sprintf(
            "%s\n%s\n%s\n%s\n%s\n%s\n%s\n\n%s\n%s\n%s\n%s\n%s\n%s",
            "Please:",
            "1. Convert the code while maintaining functionality",
            "2. Use idiomatic patterns for the target language",
            "3. Update native function calls to match language syntax",
            "4. Adjust event handlers and callbacks",
            "5. Explain major differences in the conversion",
            "6. Ensure FiveM compatibility in the target language",
            "Key differences to consider:",
            "- Array/table indexing (0 vs 1-based)",
            "- String formatting",
            "- Event registration syntax",
            "- Async patterns",
            "- Native return values"
        );

        return Response::prompt(implode('', $parts));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'code' => $schema->string()
                ->description('Code to convert')
                ->required(),
            'from' => $schema->enum(['lua', 'js'])
                ->description('Source language')
                ->default('lua'),
            'to' => $schema->enum(['lua', 'js'])
                ->description('Target language')
                ->default('js'),
        ];
    }
}
