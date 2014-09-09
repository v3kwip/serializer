Adding a listener:

```php
use AndyTruong\Serializer\Serializer;
use AndyTruong\Serializer\Event;

$serializer = new Serializer();
$serializer->getDispatcher()->addListener('serialize.properties', function(Event $event) {
  // …
});
$serializer->toArray($myObject);
```

### Events

- `serialize.properties`
- `serialize.array`
- `serialize.json`
- `unserialize.array`
- `unserialize.json`
