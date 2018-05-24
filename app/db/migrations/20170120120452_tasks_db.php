<?php

use Phinx\Migration\AbstractMigration;

class TasksDb extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        /**
         * tasks
         * Задачи
         */
        $table = $this->table('tasks',      ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id',               'biginteger',   ['identity' => true])                                       // id
            ->addColumn('name',             'string',       ['limit' => 150])                                           // название задачи
            ->addColumn('user_id',          'biginteger')                                                               // ID пользователя (role клиент)
            ->addColumn('created_at',       'timestamp',    ['default' => 'CURRENT_TIMESTAMP'])                         // когда была создана запись

            ->addIndex(['user_id'])
            ->create();
    }
}
