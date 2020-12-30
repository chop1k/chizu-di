# chizu-di
chizu-di is a library which implements dependency injection functionality.

Package contains 3 classes:
* Dependency - represents dependency. You can set local arguments, which can be injected by setArguments and addArgument methods.
  * arguments - local arguments which will be injected by the container. Can be accessed by setArguments, getArguments, addArgument methods.
  * calls - method calls which will be executed after creation. Can be accessed by setCalls, getCalls, addCall methods.
  * instance - instance of dependency. If specified, container will return this instance.
* MethodCall - represents method call structure.
  * arguments - the same as dependency arguments.
  * name - method name.
* Container - main class which contains all dependencies.

## Requirements
 - PHP 7.4

## How to use
You can add your service to the container by different ways.

You can add class to the container and then when injecting your dependency container will create new dependency instance everytime.
```php
$container->add(YourService::class);

// equivalent to

$container->add(YourService::class, new Dependency(YourService::class));
```

You can add singleton dependency by adding the instance to the dependency.

```php
$container->add(YourService::class, new Dependency($instance));
```

### How to create instance
To create the instance using dependency injection you should use create methods of the container.

create($dependency) method creates instance of given dependency using local arguments and container dependencies.
```php
// arguments will be injected to the constructor without adding to the container.
// arguments take precedence over container.
$arguments = [
    'constructor_key' => 'value'
];

// you also can add method calls.

$calls = [
    new MethodCall('method', /* Array with arguments as shown above. */ [])
];

$instance = $container->create(new Dependency(YourService::Class, $arguments, $calls));
```

createByKey($key) method looks for the given key in the container and creates an instance based on the previously found dependency.
```php
$instance = $container->createByKey(YourService::class);
```

createByClass($class, $arguments[], $calls[]) creates the instance of given class using container dependencies. You can specify local arguments and method calls.
```php
$instance = $container->createByClass(YourService::class, $arguments, $calls);
```

### How to execute method
To execute method of instance using dependency injection you should use executeMethod methods of the container.

executeMethod($instance, $method, $arguments[]) executes given method using arguments from the container and local arguments and returns result.
```php
$result = $container->executeMethod($instance, 'method', $arguments);
```

executeDependencyMethod($dependency, $method) creates the instance of the dependency, executes method by local arguments and container dependencies, returns result.
```php
$result = $container->executeDependencyMethod($dependency, 'method');
```

executeClassMethod($class, $method, $arguments[], $calls[]) creates the instance of the class, executes method by local arguments and container dependencies, returns result.
```php
$result = $container->executeClassMethod(YourService::class, 'method', $arguments, $calls)
```
## License
chizu-di is licensed under MIT license. See [license file](LICENSE).