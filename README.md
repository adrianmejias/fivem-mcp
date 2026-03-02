# FiveM MCP Server

A comprehensive Model Context Protocol (MCP) server for FiveM (GTA5) development, built with Laravel. This server provides AI assistants with powerful tools and resources to help develop FiveM resources more efficiently.

## Overview

The FiveM MCP Server enables seamless integration with AI coding assistants (Claude, ChatGPT, etc.) to provide:
- **Native function lookups** with client/server separation
- **Event documentation** for all frameworks
- **Framework support** for Standalone, ESX, QBCore, and COX
- **Resource generation** with customizable boilerplate
- **Documentation search** across FiveM, QBCore, and COX MySQL
- **Code snippets and best practices** for common patterns

### Live Site

A live instance is available at: **https://fivem-mcp.kingsoflossantos.com**

This site includes interactive documentation, tool descriptions, and integration guides.

## Quick Start

### Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/fivem-mcp.git
cd fivem-mcp

# Install dependencies
composer install
npm install

# Copy environment file and generate key
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Build frontend assets
npm run build
```

### Running the Server

**Live AI Assistants (Claude/Client):**
```json
{
  "servers": {
    "fivem": {
      "url": "https://fivem-mcp.kingsoflossantos.com/fivem",
      "type": "http"
    }
  }
}
```

**Local Testing:**
```bash
php artisan mcp:inspector fivem
```

**Start the MCP Server:**
```bash
php artisan mcp:start fivem
```

**For AI Assistants (Claude/Cline):**
Configure in your AI client settings:
```json
{
  "servers": {
    "fivem": {
      "command": "php",
      "args": ["/path/to/fivem-mcp/artisan", "mcp:start", "fivem"]
    }
  }
}
```

## Tools

The MCP server includes 15 powerful tools organized by framework and execution side:

### FiveM Core (5 tools)
- **SearchFiveMDocs** - Search FiveM, QBCore, and COX documentation
- **GetNativeClientFunction** - Look up client-side native functions (10 natives)
- **GetNativeServerFunction** - Look up server-side native functions (12 natives)
- **GetEventClientReference** - Client-side event reference (5 events)
- **GetEventServerReference** - Server-side event reference (4+ events)

### QBCore Framework (4 tools)
- **GetQBCoreServerFunction** - QBCore server functions (13+ functions)
- **GetQBCoreClientFunction** - QBCore client functions (6 functions)
- **GetQBCoreServerEventReference** - QBCore server events (6 events)
- **GetQBCoreClientEventReference** - QBCore client events (6 events)

### COX MySQL (2 tools)
- **GetCOXFunction** - OxMySQL database functions (8 functions)
- **GetCOXEventReference** - COX-specific events

### ox_inventory (2 tools)
- **GetInventoryServerFunction** - Server-side inventory management (21 functions)
- **GetInventoryClientFunction** - Client-side inventory functions (8 functions)

### Resource Generation (2 tools)
- **GenerateManifest** - Generate fxmanifest.lua with framework support
- **GenerateResourceBoilerplate** - Complete resource scaffolding

## Project Structure

```
app/Mcp/
├── Tools/
│   ├── COX/
│   │   ├── MySQL/
│   │   │   ├── GetCOXFunction.php
│   │   │   └── GetCOXEventReference.php
│   │   └── Inventory/
│   │       ├── Server/GetInventoryServerFunction.php
│   │       └── Client/GetInventoryClientFunction.php
│   ├── FiveM/
│   │   ├── Client/
│   │   │   ├── GetNativeClientFunction.php
│   │   │   └── GetEventClientReference.php
│   │   ├── Server/
│   │   │   ├── GetNativeServerFunction.php
│   │   │   └── GetEventServerReference.php
│   │   ├── GenerateManifest.php
│   │   ├── GenerateResourceBoilerplate.php
│   │   └── SearchFiveMDocs.php
│   └── QBCore/
│       ├── Server/
│       │   ├── GetQBCoreServerFunction.php
│       │   └── GetQBCoreServerEventReference.php
│       └── Client/
│           ├── GetQBCoreClientFunction.php
│           └── GetQBCoreClientEventReference.php
├── Resources/
│   ├── CodeSnippets.php
│   ├── BestPractices.php
│   ├── FrameworkComparison.php
│   └── DatabaseQueries.php
├── Prompts/
│   ├── CreateNewResource.php
│   ├── DebugIssues.php
│   ├── OptimizePerformance.php
│   ├── ConvertLanguage.php
│   └── AddFrameworkIntegration.php
└── Servers/
    └── FiveMServer.php
```

## Resources

MCP Resources provide read-only reference documentation:

1. **Code Snippets** - Common FiveM patterns in Lua and JavaScript
2. **Best Practices** - Performance optimization and security guidelines
3. **Framework Comparison** - Standalone vs ESX vs QBCore differences
4. **Database Queries** - COX MySQL examples and patterns

## Prompts

Interactive prompts to guide AI assistants:

1. **Create New Resource** - Scaffold a complete FiveM resource
2. **Debug Issues** - Analyze and fix FiveM errors
3. **Optimize Performance** - Improve resource efficiency
4. **Convert Language** - Translate between Lua and JavaScript
5. **Add Framework Integration** - Migrate between frameworks

## Testing

Run the test suite to ensure everything works:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/GetNativeClientFunctionToolTest.php

# Run with coverage report
php artisan test --coverage
```

**Current Test Status:** 82 tests passing ✅

## Code Quality

This project follows PSR-12 coding standards and uses Laravel Pint for automatic formatting:

```bash
# Format all code
vendor/bin/pint

# Check formatting without fixing
vendor/bin/pint --test
```

## Documentation

- [FiveM Documentation](https://docs.fivem.net/)
- [FiveM Natives Reference](https://docs.fivem.net/natives/)
- [QBCore Documentation](https://docs.qbcore.org/)
- [COX MySQL Documentation](https://coxdocs.dev/)
- [Laravel MCP Documentation](https://laravel.com/docs/mcp)
- [Model Context Protocol](https://modelcontextprotocol.io/)

## Architecture Notes

### Client/Server Separation
All tools are organized by **execution side** (Client or Server) to ensure AI assistants provide appropriate code examples. For example:
- `GetNativeClientFunction` only contains client-side natives
- `GetNativeServerFunction` only contains server-side natives
- Events are similarly separated to avoid confusion

### Framework Organization
Tools are grouped by framework (FiveM, QBCore, COX) to make discovery intuitive and keep documentation focused.

### Response Format
All tool responses use consistent formatting with:
- Function/event signatures
- Parameter descriptions
- Return types
- Lua and JavaScript code examples
- Complete usage patterns

## Contributing

To add new tools or improve existing ones:

1. Create a new tool class in `app/Mcp/Tools/{Framework}/{Side}/`
2. Implement the `Tool` interface
3. Add corresponding test file in `tests/Feature/`
4. Register in `app/Mcp/Servers/FiveMServer.php`
5. Run tests: `php artisan test`
6. Format code: `vendor/bin/pint`
7. Submit PR with description

## License

MIT License - See LICENSE file for details

## Support

For issues or questions:
- Review [FiveM docs](https://docs.fivem.net/)
- Open an issue on GitHub
