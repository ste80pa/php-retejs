
# PHP retejs

PHP implementation of the Engine for [ReteJS](https://github.com/retejs/rete) inspired by [D3 Node Engine](https://github.com/retejs/cpp-engine)

# Usage example

```php

<?php

declare(strict_types=1);

namespace Ste80pa\Retejs\Examples;

include_once(__DIR__ . '/../../vendor/autoload.php');

use Ste80pa\Retejs\Engine\Engine;
use Ste80pa\Retejs\Engine\EngineResolver;
use Ste80pa\Retejs\Engine\Processor;
use Ste80pa\Retejs\Engine\ReteLoader;
use Ste80pa\Retejs\Engine\VersionComparerEqualOrGreater;
use Ste80pa\Retejs\Engine\Worker\WorkerPool;
use Ste80pa\Retejs\Examples\Base\Workers\Add;
use Ste80pa\Retejs\Examples\Base\Workers\Add2;
use Ste80pa\Retejs\Examples\Base\Workers\Number;
use Ste80pa\Retejs\Examples\Base\Workers\Number2;
use Ste80pa\Retejs\InputOutput\FileReader;


//
// Engines
//

$workerPool1 = (new WorkerPool())
    ->add('add', new Add())
    ->add('number', new Number());

$workerPool2 = (new WorkerPool())
    ->add('add', new Add2())
    ->add('number', new Number2());


$engine1 = new Engine('demo', '0.1.0', new VersionComparerEqualOrGreater(), $workerPool1);
$engine2 = new Engine('demo2', '0.1.0', new VersionComparerEqualOrGreater(), $workerPool2);

$engineResolver = (new EngineResolver())
    ->add($engine1)
    ->add($engine2);

$reader = new FileReader();
$loader = new ReteLoader();
$processor = new Processor($engineResolver);

$result = $processor->process(
    $loader->load($reader->read(__DIR__ . '/demo.json')),
    []);
echo 'the result is : ', $result->getOutput() . PHP_EOL;

$result = $processor->process(
    $loader->load($reader->read(__DIR__ . '/demo2.json')),
    []
);
echo 'the result is : ', $result->getOutput() . PHP_EOL;

```

# License

[MIT](https://opensource.org/licenses/MIT)