<?php


namespace radondev\ac;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use radondev\ac\threads\CalculationThread;
use radondev\ac\utils\InformationContainer;

class Main extends PluginBase
{
    /**
     * @var CalculationThread
     */
    private $calculationThread;

    public function onEnable()
    {
        $this->calculationThread = new CalculationThread($this->getServer()->getLogger(), $this->getServer()->getLoader());
    }

    public function onDisable()
    {
        $this->calculationThread->deactivate();
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === "ac-test") {
            $t1 = hrtime(true);

            $container = new InformationContainer(2);
            $this->calculationThread->pushToTodo($container);

            $run = true;
            while ($run) {
                if (($container = $this->calculationThread->getFromDone()) !== null) {
                    $run = false;
                }
            }

            $t2 = hrtime(true);

            $sender->sendMessage("Time: " . ($t2 - $t1) / 1e+6 . "ms");
        }

        return true;
    }
}