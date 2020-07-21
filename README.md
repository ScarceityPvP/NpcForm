# NPCFormAPI
**NPCFormAPI is a PocketMine-MP plugin that allows plugins to create and managing NpcForms!**

### Usage
You'll first need to import the `scarce\NPCFormAPI\NpcForm.php` class. This is should be the only class required to create and manage NpcForms.
```php
<?php
use scarce\NPCFormAPI\NpcForm;
```
**NOTE:** For the player to be able to see the form, they have to right-click on the NPC Entity
### Creating a NpcForm Instance
Creating a NpcForm Instance is relatively simple and is similar to creating a FormAPI form.
You first have to instantiate an NpcForm`$form = new NpcForm()`The NpcForm takes two required parameteres and one non required parameter `$form = new NpcForm(Callable $callable, Position $position, $yaw = 90)`
The callable paramter takes a player object and an integer paramenter which will be null if no response is given
```php
<?php
/** @var Position $position */
/** @var string $title */
//$position is where the NpcEntity will spawn
$form = new NpcForm(function(Player $player, ?int $data), $position, $yaw);
```
To set the title of the form(and the NpcEntity) use:
```php
/** @var string $title */
$form->setTitle($title);
```
You can aslo set the content of the NpcForm by using
```php
/** @var string $content */
$form->setContent($title);
```
And most importantly, you can add buttons by using:
```php
/** @var string $name */
$form->addButton($name);
```
It is also possible to get the NPC Entity that corresponds with your Form by using:
```php
$form->getEntity();
```
To spawn the NPC to a player use:
```php
/** @Player $player */
$form->spawnTo(Player $player);
```
You can also spawn the entity to all players by using
```php
$form->spawnToAll();
```
###Handiling NpcForm Callable
This will show an example of how to use the callable in the NpcForm class
```php
<?php
use scarce\NPCFormAPI\NpcForm;

public function sendNpcForm(Player $player){
        $form = new NpcForm(function(Player $player, ?int $data){
            //$data can be an integer, 0 is the first button, 1 is the second button etc...
            if ($data === null){
                return;
            }
            $player->sendMessage("$data");
        }, $player->asPosition(), $player->yaw);
        
        $form->setTitle("Number Selector");
        $form->setContent( "Chose a Button!");
        $form->addButton("0");
        $form->addButton("1");
        $form->addButton("2");
        $form->addButton("3");
        $form->addButton("4");
        $form->addButton("5");
        //Sending The Form entity to only $player
        $form->spawnTo($player);
    }
```     
###To Do
-Save Npc Data on Server Reset and add Method for it[]




