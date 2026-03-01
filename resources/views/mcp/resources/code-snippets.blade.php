# Common FiveM Code Snippets

Quick reference for frequently used code patterns.

## Player Distance Check

### Lua
```lua
-- Check distance between two players
local ped1 = GetPlayerPed(source)
local ped2 = GetPlayerPed(targetPlayer)
local coords1 = GetEntityCoords(ped1)
local coords2 = GetEntityCoords(ped2)
local distance = #(coords1 - coords2)

if distance < 5.0 then
    -- Players are close enough
end
```

### JavaScript
```javascript
// Check distance between two players
const ped1 = GetPlayerPed(source);
const ped2 = GetPlayerPed(targetPlayer);
const coords1 = GetEntityCoords(ped1, false);
const coords2 = GetEntityCoords(ped2, false);
const distance = Math.sqrt(
    Math.pow(coords1[0] - coords2[0], 2) +
    Math.pow(coords1[1] - coords2[1], 2) +
    Math.pow(coords1[2] - coords2[2], 2)
);

if (distance < 5.0) {
    // Players are close enough
}
```

## Safe Event Handler

### Lua
```lua
-- Server-side event with validation
RegisterNetEvent('myresource:doSomething', function(data)
    local _source = source

    -- Validate source
    if not _source or _source < 1 then return end

    -- Validate data
    if type(data) ~= 'table' or not data.id then return end

    -- Process event safely
    print(('Player %s triggered event with ID: %s'):format(_source, data.id))
end)
```

### JavaScript
```javascript
// Server-side event with validation
onNet('myresource:doSomething', (data) => {
    const _source = source;

    // Validate source
    if (!_source || _source < 1) return;

    // Validate data
    if (typeof data !== 'object' || !data.id) return;

    // Process event safely
    console.log(`Player ${_source} triggered event with ID: ${data.id}`);
});
```

## Thread with Cleanup

### Lua
```lua
-- Citizen thread with proper cleanup
local isRunning = true

Citizen.CreateThread(function()
    while isRunning do
        Citizen.Wait(0)

        -- Your code here
        if IsControlJustPressed(0, 38) then -- E key
            print('E pressed!')
        end
    end
end)

-- Cleanup function
AddEventHandler('onResourceStop', function(resource)
    if resource == GetCurrentResourceName() then
        isRunning = false
    end
end)
```

### JavaScript
```javascript
// SetInterval with cleanup
let isRunning = true;

const intervalId = setInterval(() => {
    if (!isRunning) {
        clearInterval(intervalId);
        return;
    }

    // Your code here
    if (IsControlJustPressed(0, 38)) { // E key
        console.log('E pressed!');
    }
}, 0);

// Cleanup
on('onResourceStop', (resource) => {
    if (resource === GetCurrentResourceName()) {
        isRunning = false;
    }
});
```

## Database Query with Callback

### Lua
```lua
-- MySQL async query pattern
MySQL.Async.fetchAll('SELECT * FROM users WHERE identifier = @identifier', {
    ['@identifier'] = identifier
}, function(result)
    if result[1] then
        print(('Found user: %s'):format(result[1].name))
    else
        print('User not found')
    end
end)
```

### JavaScript
```javascript
// oxmysql query pattern
const result = await exports.oxmysql.query('SELECT * FROM users WHERE identifier = ?', [identifier]);

if (result.length > 0) {
    console.log(`Found user: ${result[0].name}`);
} else {
    console.log('User not found');
}
```

## NUI Callback

### Lua
```lua
-- Client-side NUI callback handler
RegisterNUICallback('buttonClick', function(data, cb)
    -- Process the callback
    print(('Button clicked: %s'):format(data.button))

    -- Send response back to NUI
    cb({ success = true, message = 'Action completed' })
end)
```

### JavaScript
```javascript
// Client-side NUI callback handler
RegisterNUICallback('buttonClick', (data, cb) => {
    // Process the callback
    console.log(`Button clicked: ${data.button}`);

    // Send response back to NUI
    cb({ success: true, message: 'Action completed' });
});
```
