# Google Stitch MCP Integration Guide

This guide walks you through connecting your **Google Stitch** designs directly to this workspace using the **Model Context Protocol (MCP)**. This allows your coding agent to view, fetch, and implement UI designs and code directly from your Stitch projects.

---

## 1. Prerequisites

Before starting, make sure you have:

- **Node.js (v18+)** installed on your machine
- A **Google Cloud account** with a project created
- A project designed inside the [Google Stitch platform](https://stitch.withgoogle.com)

---

## 2. Interactive Setup (Easiest Method)

Run the official initialization script in your terminal. This wizard automatically handles authentication and setup:

```bash
npx @_davideast/stitch-mcp init
```

The wizard will guide you through:
1. **Google Cloud login**: Authenticating via your Google account in the browser
2. **Project selection**: Selecting the Google Cloud project hosting your Stitch environment
3. **Client configuration**: Linking it directly to your target editor (Cursor, Claude Desktop, VS Code, etc.)

---

## 3. Manual IDE Integration

If you prefer to configure it manually or your IDE requires it, add this JSON snippet to your IDE's MCP config file (e.g. `claude_desktop_config.json`, Cursor Settings > MCP, or VS Code `.vscode/mcp.json`):

```json
{
  "mcpServers": {
    "stitch": {
      "command": "npx",
      "args": ["@_davideast/stitch-mcp", "proxy"]
    }
  }
}
```

---

## 4. Configuration Paths for Common IDEs

### Claude Desktop
Open `~/AppData/Roaming/Claude/claude_desktop_config.json` (on Windows) or `~/Library/Application Support/Claude/claude_desktop_config.json` (on macOS) and paste the manual configuration.

### Cursor IDE
1. Go to **Settings > Cursor Settings > Features > MCP**.
2. Click **+ Add New MCP Server**.
3. Fill in the details:
   - **Name**: `stitch`
   - **Type**: `command`
   - **Command**: `npx @_davideast/stitch-mcp proxy`

---

## 5. How Your AI Agent Uses Google Stitch MCP

Once active, your AI assistant will gain access to these tools automatically:

- `list_projects`: Lists your active projects on Google Stitch
- `list_screens`: Retrieves the names of screens designed in a project
- `get_screen_code`: Retrieves raw HTML/CSS/React code for a screen
- `extract_design_context`: Pulls theme tokens (colors, sizes, typography) to maintain design system alignment
