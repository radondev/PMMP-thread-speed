<?php


namespace radondev\ac\threads;


use ClassLoader;
use pocketmine\Thread;
use radondev\ac\utils\InformationContainer;
use Threaded;
use ThreadedLogger;

class CalculationThread extends Thread
{
    /**
     * @var ThreadedLogger
     */
    private $logger;
    /**
     * @var array
     */
    private $todo;
    /**
     * @var array
     */
    private $done;
    /**
     * @var bool
     */
    private $state;

    /**
     * CalculationThread constructor.
     * @param ThreadedLogger $logger
     * @param ClassLoader $classLoader
     */
    public function __construct(ThreadedLogger $logger, ClassLoader $classLoader)
    {
        $this->logger = $logger;
        $this->todo = new Threaded();
        $this->done = new Threaded();
        $this->state = true;

        $this->setClassLoader($classLoader);

        $this->start();
    }

    public function run(): void
    {
        $this->registerClassLoader();

        while ($this->isActive()) {
            if (($container = $this->getFromTodo()) instanceof InformationContainer) {
                $this->logger->info("Val: " . $container->getValue());
                $container->setValue($container->getValue() * $container->getValue());
                $this->pushToDone($container);
            }

            sleep(0.1);
        }
    }

    /**
     * @return int
     */
    public function getTodoSize(): int
    {
        return count($this->todo);
    }

    /**
     * @return InformationContainer|null
     */
    private function getFromTodo(): ?InformationContainer
    {
        return $this->todo->shift();
    }

    /**
     * @param InformationContainer $container
     */
    public function pushToTodo(InformationContainer $container): void
    {
        $this->todo[] = $container;
    }

    /**
     * @return int
     */
    public function getDoneSize(): int
    {
        return count($this->todo);
    }

    /**
     * @return InformationContainer|null
     */
    public function getFromDone(): ?InformationContainer
    {
        return $this->done->shift();
    }

    /**
     * @param InformationContainer $container
     */
    private function pushToDone(InformationContainer $container): void
    {
        $this->done[] = $container;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->state;
    }

    public function deactivate(): void
    {
        $this->state = false;
    }

    public function quit()
    {
        $this->deactivate();
        $this->logger->alert("Quitting...");

        parent::quit();
    }
}