<?php

use Phinx\Migration\AbstractMigration;

class AssignedTasks extends AbstractMigration
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
         * assigned_tasks
         * Назначенные исполнителям задачи
         */
        $table = $this->table('assigned_tasks',      ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id',               'biginteger',   ['identity' => true])                                       // id
            ->addColumn('task_id',          'biginteger')                                                               // ID задачи
            ->addColumn('user_id',          'biginteger')                                                               // ID пользователя (role исполнитель)
            ->addColumn('comment',          'string',       ['limit' => 150, 'null' => true, 'default' => NULL])        // комментарий
            ->addColumn('created_at',       'timestamp',    ['default' => 'CURRENT_TIMESTAMP'])                         // когда была создана запись

            ->addIndex(['task_id'])
            ->addIndex(['user_id'])
            ->create();
    }
}
