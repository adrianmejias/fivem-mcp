# FiveM Framework Comparison

## Overview

| Feature | Standalone | ESX | QBCore |
|---------|-----------|-----|--------|
| **Complexity** | Low | Medium | Medium-High |
| **Player System** | None | Built-in | Built-in |
| **Inventory** | None | Basic | Advanced |
| **Database** | Optional | MySQL | MySQL/oxmysql |
| **Jobs** | Manual | Built-in | Built-in |
| **Community** | N/A | Large | Growing |
| **Learning Curve** | Easy | Moderate | Moderate-Hard |

## Standalone

### When to Use
- Simple resources (maps, MLOs, vehicles)
- Custom systems from scratch
- Learning FiveM basics
- No framework dependencies needed

### Pros
- Complete control
- No framework overhead
- Lightweight
- No version conflicts

### Cons
- Must build everything yourself
- No standardized player system
- Harder to integrate with other resources
- More initial work

### Example Code
```lua
-- No framework, direct natives
RegisterNetEvent('myresource:doSomething', function(data)
    local ped = GetPlayerPed(source)
    local coords = GetEntityCoords(ped)

    TriggerClientEvent('myresource:response', source, {
        success = true,
        coords = coords
    })
end)
```

## ESX (es_extended)

### When to Use
- Building roleplay servers
- Need player accounts/jobs/inventory
- Want established ecosystem
- Prefer simpler structure

### Pros
- Huge community and resources
- Lots of free scripts available
- Well-documented
- Mature and stable
- Simpler than QBCore

### Cons
- Older codebase
- Some performance issues
- Less modern features
- Can feel outdated

### Core Features
- Player accounts and identifiers
- Job system
- Basic inventory
- Money (cash, bank, black money)
- Vehicle ownership
- Society system (businesses)

### Example Code
```lua
-- Server-side ESX
ESX = exports['es_extended']:getSharedObject()

RegisterNetEvent('myresource:buyItem', function(itemName, amount)
    local xPlayer = ESX.GetPlayerFromId(source)

    if xPlayer.getMoney() >= price then
        xPlayer.removeMoney(price)
        xPlayer.addInventoryItem(itemName, amount)
    end
end)

-- Client-side ESX
ESX = exports['es_extended']:getSharedObject()

RegisterNetEvent('esx:playerLoaded', function(playerData)
    PlayerData = playerData
end)
```

### Common ESX Functions
```lua
-- Server-side
xPlayer.getMoney()
xPlayer.addMoney(amount)
xPlayer.removeMoney(amount)
xPlayer.getAccount(account).money
xPlayer.addAccountMoney(account, amount)
xPlayer.removeAccountMoney(account, amount)
xPlayer.getInventoryItem(item)
xPlayer.addInventoryItem(item, count)
xPlayer.removeInventoryItem(item, count)
xPlayer.getJob()
xPlayer.setJob(job, grade)

-- Client-side
ESX.ShowNotification(msg)
ESX.UI.Menu.Open(type, namespace, name, data, submit, cancel)
ESX.Game.SpawnVehicle(model, coords, heading, cb)
ESX.Game.DeleteVehicle(vehicle)
```

## QBCore (qb-core)

### When to Use
- Modern roleplay servers
- Need advanced features
- Want active development
- Prefer modular design

### Pros
- Modern codebase
- Active development
- Advanced inventory
- Better performance optimization
- More features out of box
- Flexible metadata system

### Cons
- Steeper learning curve
- Smaller community than ESX
- Fewer free resources
- More complex structure
- Breaking changes in updates

### Core Features
- Advanced player management
- Comprehensive inventory system
- Metadata system
- Gang system
- Crafting system
- Housing system
- Vehicle keys
- Stash system

### Example Code
```lua
-- Server-side QBCore
local QBCore = exports['qb-core']:GetCoreObject()

RegisterNetEvent('myresource:buyItem', function(itemName, amount)
    local Player = QBCore.Functions.GetPlayer(source)

    if Player.Functions.RemoveMoney('cash', price) then
        Player.Functions.AddItem(itemName, amount)
    end
end)

-- Client-side QBCore
local QBCore = exports['qb-core']:GetCoreObject()

RegisterNetEvent('QBCore:Client:OnPlayerLoaded', function()
    PlayerData = QBCore.Functions.GetPlayerData()
end)
```

### Common QBCore Functions
```lua
-- Server-side
Player.Functions.AddMoney(type, amount)
Player.Functions.RemoveMoney(type, amount)
Player.Functions.GetMoney(type)
Player.Functions.AddItem(item, amount, slot, info)
Player.Functions.RemoveItem(item, amount, slot)
Player.Functions.GetItemByName(item)
Player.Functions.SetJob(job, grade)
Player.Functions.SetGang(gang, grade)
Player.Functions.SetMetaData(key, val)
Player.Functions.GetMetaData(key)

-- Client-side
QBCore.Functions.Notify(text, type, length)
QBCore.Functions.DrawText(text, position)
QBCore.Functions.SpawnVehicle(model, cb, coords, isnetworked)
QBCore.Functions.DeleteVehicle(vehicle)
QBCore.Functions.GetPlayerData()
```

## Migration Guide

### Standalone to ESX
1. Add `@es_extended/locale.lua` to fxmanifest
2. Add ESX dependency
3. Replace custom player system with ESX.GetPlayerFromId()
4. Convert money/inventory to ESX functions

### Standalone to QBCore
1. Add QB dependency to fxmanifest
2. Replace player system with QBCore.Functions.GetPlayer()
3. Convert all money/inventory operations
4. Update notifications to QBCore.Functions.Notify()

### ESX to QBCore
Common conversions:

| ESX | QBCore |
|-----|--------|
| `ESX.GetPlayerFromId(source)` | `QBCore.Functions.GetPlayer(source)` |
| `xPlayer.getMoney()` | `Player.Functions.GetMoney('cash')` |
| `xPlayer.addMoney(amount)` | `Player.Functions.AddMoney('cash', amount)` |
| `xPlayer.getInventoryItem(item)` | `Player.Functions.GetItemByName(item)` |
| `ESX.ShowNotification(msg)` | `QBCore.Functions.Notify(msg)` |
| `xPlayer.getJob()` | `Player.PlayerData.job` |
| `xPlayer.identifier` | `Player.PlayerData.citizenid` |

## Which Should You Choose?

### Choose Standalone If:
- Building simple resources
- Learning FiveM development
- Don't need player/economy system
- Want maximum flexibility

### Choose ESX If:
- Building traditional RP server
- Want lots of compatible resources
- Need stable, proven system
- Prefer simpler structure
- Large community support is priority

### Choose QBCore If:
- Want modern features
- Building advanced RP server
- Need better performance
- Want active development
- Flexible enough to customize deeply

## Performance Comparison

**Standalone**: Best (no framework overhead)
**ESX**: Good (some overhead from player system)
**QBCore**: Good-Very Good (optimized but more features)

## Conclusion

All three options are valid depending on your needs:
- **Standalone** for simple/custom projects
- **ESX** for traditional RP with maximum compatibility
- **QBCore** for modern RP with advanced features

The "best" framework depends on your specific requirements, technical skill, and server goals.
