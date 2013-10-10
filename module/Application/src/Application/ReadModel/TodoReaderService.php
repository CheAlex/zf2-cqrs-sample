<?php
/*
 * This file is part of the codeliner/zf2-cqrs-sample package.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\ReadModel;

use Application\Cqrs\Query\GetAllOpenTodosQuery;
use Application\Cqrs\Event\TodoCreatedEvent;

/**
 * ReadModel Class TodoReaderService
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class TodoReaderService
{
    use \Cqrs\Adapter\AdapterTrait;
    
    protected $openTodosFile = 'data/open-todos.json';
    
    protected $openTodos = array();


    public function __construct()
    {
        if (file_exists($this->openTodosFile)) {
            $this->openTodos = json_decode(file_get_contents($this->openTodosFile), true);
        }
    }
    
    public function onTodoCreated(TodoCreatedEvent $event) 
    {
        $this->addToOpenTodos($event->getPayload());
    }

    public function getAllOpenTodos(GetAllOpenTodosQuery $query)
    {
        return $this->openTodos;
    }
    
    protected function addToOpenTodos(array $todoData)
    {
        $this->openTodos[$todoData['id']] = $todoData;
        $this->writeTodosToFile();
    }
    
    protected function writeTodosToFile()
    {
        file_put_contents($this->openTodosFile, json_encode($this->openTodos));
    }
}
