# FiveM Database Query Examples

Common database patterns and examples for COX MySQL operations in FiveM development.

## CRUD Operations

### Create (INSERT)

**Lua Example:**
```lua
MySQL.insert('users', {
    username = 'player_name',
    money = 1000,
    bank = 5000,
    created_at = os.date('%Y-%m-%d %H:%M:%S')
}, function(insertId)
    print('User created with ID: ' .. insertId)
end)
```

**JavaScript Example:**
```javascript
MySQL.insert('users', {
    username: 'player_name',
    money: 1000,
    bank: 5000,
    created_at: new Date().toISOString()
}, (insertId) => {
    console.log('User created with ID: ' + insertId);
});
```

### Read (SELECT)

**Get Multiple Rows:**
```lua
MySQL.query('SELECT * FROM users WHERE banned = ?', { 0 }, function(results)
    if results then
        for i = 1, #results do
            print(results[i].username .. ' - $' .. results[i].money)
        end
    end
end)
```

**Get Single Row:**
```lua
MySQL.single('SELECT * FROM users WHERE id = ?', { playerId }, function(user)
    if user then
        print('Player: ' .. user.username)
    end
end)
```

**Get Single Value:**
```lua
MySQL.scalar('SELECT COUNT(*) as count FROM users', {}, function(count)
    print('Total users: ' .. (count or 0))
end)
```

### Update (UPDATE)

**Update Single Record:**
```lua
MySQL.update('users', {
    money = 500,
    updated_at = os.date('%Y-%m-%d %H:%M:%S')
}, { id = playerId }, function(affectedRows)
    print('Updated ' .. affectedRows .. ' user(s)')
end)
```

**Update Multiple Records:**
```lua
MySQL.update('users', { banned = 1 }, { last_login = '<', '2024-01-01' }, function(updated)
    print('Banned ' .. updated .. ' inactive users')
end)
```

### Delete (DELETE)

**Lua Example:**
```lua
MySQL.query('DELETE FROM users WHERE banned = ? AND last_login < DATE_SUB(NOW(), INTERVAL 90 DAY)', { 1 }, function(result)
    if result then
        print('Cleanup completed')
    end
end)
```

## Prepared Statements (Security)

Always use prepared statements to prevent SQL injection:

**Secure Query:**
```lua
-- Using ? placeholders for parameters
MySQL.query('SELECT * FROM users WHERE username = ? AND password_hash = ?',
    { inputUsername, passwordHash },
    function(result)
        -- Safe from SQL injection
    end)
```

**Avoid This (Vulnerable):**
```lua
-- DO NOT DO THIS - Vulnerable to SQL injection
local query = 'SELECT * FROM users WHERE username = \'' .. inputUsername .. '\''
MySQL.query(query, {}, callback) -- BAD!
```

## Transactions

Transactions ensure atomicity - either all changes succeed or none:

**Money Transfer Example:**
```lua
MySQL.transaction.begin()

-- Deduct from source account
MySQL.query('UPDATE users SET money = money - ? WHERE id = ?', { amount, sourceId })

-- Add to destination account
MySQL.query('UPDATE users SET money = money + ? WHERE id = ?', { amount, destId })

-- Commit or rollback based on results
MySQL.transaction.commit(function(success)
    if success then
        print('Transfer success')
    else
        MySQL.transaction.rollback()
        print('Transfer failed and rolled back')
    end
end)
```

## Async Operations

Using async queries for better code organization:

**Lua Example:**
```lua
CreateThread(function()
    -- Query executes asynchronously
    local users = MySQL.async.query('SELECT id FROM users WHERE active = ?', { 1 })

    if users then
        print('Found ' .. #users .. ' active users')
    end
end)
```

## Error Handling

Always handle errors gracefully:

**Try-Catch Pattern:**
```lua
RegisterNetEvent('myResource:registerUser', function()
    local username = 'newuser'
    local email = 'user@example.com'

    MySQL.insert('users', { username = username, email = email }, function(insertId)
        if insertId then
            TriggerClientEvent('notify', source, 'Account created!', 'success')
        else
            TriggerClientEvent('notify', source, 'Account creation failed', 'error')
        end
    end)
end)
```

## Connection Checks

Always verify database is ready before executing queries:

```lua
if MySQL.ready then
    MySQL.query('SELECT 1', {}, function(result)
        print('Database is available')
    end)
else
    print('Waiting for database connection...')
end
```

## Common Queries

### Get Player Data
```lua
MySQL.single('SELECT * FROM users WHERE id = ?', { playerId }, function(user)
    if user then
        print('User: ' .. user.username .. ', Money: ' .. user.money)
    end
end)
```

### Update Player Money
```lua
MySQL.update('users', { money = newAmount }, { id = playerId }, callback)
```

### Check User Exists
```lua
MySQL.scalar('SELECT COUNT(*) FROM users WHERE username = ?', { username }, function(exists)
    if exists > 0 then
        print('User already exists')
    end
end)
```

### Get All Active Players
```lua
MySQL.query('SELECT id, username FROM users WHERE active = ? ORDER BY username ASC', { 1 }, callback)
```

### Batch Insert
```lua
for i = 1, 10 do
    MySQL.insert('logs', { action = 'test', timestamp = os.time() })
end
```

## Performance Tips

1. **Use WHERE clauses** to limit result sets
2. **Use LIMIT** for pagination: `SELECT * FROM users LIMIT 10 OFFSET 20`
3. **Use indexes** on frequently queried columns (id, username, etc.)
4. **Use transactions** for related operations
5. **Use prepared statements** always
6. **Cache results** when data doesn't change frequently
7. **Avoid N+1 queries** - fetch related data in one query
8. **Use EXPLAIN** to analyze query performance

## Debugging

Monitor slow queries and errors:

```lua
RegisterNetEvent('coxMySQL:queryError', function(sql, error)
    print('Query error: ' .. error)
    print('Failed query: ' .. sql)
    -- Log to file or monitoring service
end)

RegisterNetEvent('coxMySQL:slowQuery', function(sql, duration)
    if duration > 500 then
        print('SLOW QUERY (' .. duration .. 'ms): ' .. sql)
    end
end)
```
