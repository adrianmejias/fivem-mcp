# FiveM Development Best Practices

## Performance Optimization

### 1. Use Citizen.Wait() Efficiently
**Bad:**
```lua
Citizen.CreateThread(function()
    while true do
        Citizen.Wait(0) -- Runs every frame!
        -- Code that doesn't need to run every frame
    end
end)
```

**Good:**
```lua
Citizen.CreateThread(function()
    while true do
        Citizen.Wait(1000) -- Runs once per second
        -- Code here
    end
end)
```

### 2. Avoid GetPlayerPed(-1) in Loops
**Bad:**
```lua
while true do
    local ped = GetPlayerPed(-1) -- Called every iteration
    local coords = GetEntityCoords(ped)
    Citizen.Wait(0)
end
```

**Good:**
```lua
local ped = PlayerPedId() -- Cache outside loop
while true do
    local coords = GetEntityCoords(ped)
    Citizen.Wait(0)
end
```

### 3. Use Distance Checks Before Expensive Operations
```lua
local myCoords = GetEntityCoords(PlayerPedId())
local distance = #(myCoords - targetCoords)

if distance < 100.0 then
    -- Only do expensive operations when close
    DrawMarker(...)
end
```

### 4. Batch Database Queries
**Bad:**
```lua
for _, player in pairs(players) do
    MySQL.Async.fetchAll('SELECT * FROM users WHERE id = @id', {
        ['@id'] = player.id
    })
end
```

**Good:**
```lua
local ids = {}
for _, player in pairs(players) do
    table.insert(ids, player.id)
end

MySQL.Async.fetchAll('SELECT * FROM users WHERE id IN (@ids)', {
    ['@ids'] = table.concat(ids, ',')
})
```

## Security Best Practices

### 1. Always Validate Server Events
```lua
RegisterNetEvent('myresource:giveMoney', function(amount)
    local _source = source

    -- Validate source
    if not _source or _source < 1 then return end

    -- Validate amount
    if type(amount) ~= 'number' or amount <= 0 or amount > 10000 then
        print(('Invalid amount from player %s'):format(_source))
        return
    end

    -- Process only after validation
    Player(_source).addMoney(amount)
end)
```

### 2. Never Trust Client Data
- All game-changing logic should be on the server
- Validate all client requests
- Use server-side checks for important operations

### 3. Don't Expose Admin Events
**Bad:**
```lua
RegisterNetEvent('admin:giveWeapon') -- Client can trigger!
```

**Good:**
```lua
-- Use server commands instead
RegisterCommand('giveweapon', function(source, args)
    if IsPlayerAceAllowed(source, 'admin.weapons') then
        -- Safe to execute
    end
end, true)
```

## Code Organization

### 1. Use Config Files
```lua
-- config.lua
Config = {}
Config.MaxDistance = 100.0
Config.CheckInterval = 1000
Config.EnableDebug = false

-- client.lua
if distance < Config.MaxDistance then
    -- Easy to tweak without touching logic
end
```

### 2. Modularize Your Code
```lua
-- utils.lua
function GetPlayerDistance(player1, player2)
    local coords1 = GetEntityCoords(GetPlayerPed(player1))
    local coords2 = GetEntityCoords(GetPlayerPed(player2))
    return #(coords1 - coords2)
end

-- main.lua
local distance = GetPlayerDistance(source, target)
```

### 3. Use Proper Event Naming
```lua
-- Good naming convention
RegisterNetEvent('resourcename:category:action')
RegisterNetEvent('cardealer:purchase:vehicle')
RegisterNetEvent('banking:account:withdraw')

-- Avoid generic names
RegisterNetEvent('action') -- Too generic!
RegisterNetEvent('event1') -- Not descriptive!
```

## Resource Management

### 1. Clean Up After Yourself
```lua
AddEventHandler('onResourceStop', function(resource)
    if resource == GetCurrentResourceName() then
        -- Clean up threads, blips, entities, etc.
        isRunning = false
        RemoveBlip(myBlip)
        DeleteEntity(myVehicle)
    end
end)
```

### 2. Don't Create Unnecessary Entities
```lua
-- Delete vehicles when no longer needed
if DoesEntityExist(vehicle) then
    DeleteEntity(vehicle)
end

-- Clean up peds
if DoesEntityExist(ped) then
    DeletePed(ped)
end
```

### 3. Manage Network Ownership
```lua
-- Set network IDs for entities you want to sync
NetworkRegisterEntityAsNetworked(entity)
local netId = NetworkGetNetworkIdFromEntity(entity)
```

## Testing & Debugging

### 1. Use Conditional Debug Prints
```lua
if Config.Debug then
    print(('[DEBUG] Player %s at coords: %s'):format(source, coords))
end
```

### 2. Error Handling
```lua
local success, error = pcall(function()
    -- Code that might error
    MySQL.Async.fetchAll(query)
end)

if not success then
    print(('Error: %s'):format(error))
end
```

### 3. Use Convars for Configuration
```lua
-- Set in server.cfg
setr myresource:maxdistance "100"

-- Read in script
local maxDistance = GetConvarInt('myresource:maxdistance', 50)
```

## Framework-Specific Tips

### ESX
```lua
-- Always check if ESX is loaded
ESX = exports['es_extended']:getSharedObject()

-- Use ESX callbacks for server communication
ESX.TriggerServerCallback('myresource:getData', function(data)
    print(data)
end)
```

### QBCore
```lua
-- Get QBCore object properly
local QBCore = exports['qb-core']:GetCoreObject()

-- Use QBCore functions
local Player = QBCore.Functions.GetPlayer(source)
if Player then
    Player.Functions.AddMoney('cash', amount)
end
```

## Common Pitfalls

1. **Using GetPlayerPed(-1) instead of PlayerPedId()** - Less efficient
2. **Not cleaning up on resource restart** - Memory leaks
3. **Trusting client-side coordinates** - Security risk
4. **Running expensive operations every frame** - Performance issues
5. **Not validating server events** - Exploits waiting to happen
6. **Hardcoding values** - Use config files instead
7. **Not using exports properly** - Creates tight coupling
