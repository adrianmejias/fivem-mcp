# FiveM MCP

This repository contains a custom implementation of a Modular Command Protocol (MCP) server for FiveM, built using Laravel. The MCP server provides an interface for handling various commands and events related to FiveM development, allowing developers to easily access information about native functions, events, and more.

## VSCode Extension

A custom VSCode extension is included in this repository, which allows developers to interact with the MCP server directly from their code editor. The extension provides features such as command execution, event handling, and more.

`.vscode/settings.json`

```json
{
	"servers": {
		"fivem": {
			"url": "https://fivem-mcp.kingsoflossantos.com/fivem",
			"type": "http"
		}
	},
	"inputs": []
}
```
