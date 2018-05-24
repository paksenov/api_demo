<?php

use Phinx\Migration\AbstractMigration;

class UsersBb extends AbstractMigration
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
         * users
         * Пользователи
         */
        $table = $this->table('users',      ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id',               'biginteger',   ['identity' => true])                                       // id
            ->addColumn('email',            'string',       ['limit' => 150, 'null' => true, 'default' => NULL])        // email
            ->addColumn('fio',              'string',       ['limit' => 50,  'null' => true, 'default' => NULL])        // ФИО
            ->addColumn('role',             'integer',      ['limit' => 1,   'default' => 0])                          // роль пользователя (клиен/исполнитель)
            ->addColumn('created_at',       'timestamp',    ['default' => 'CURRENT_TIMESTAMP'])                         // когда была создана запись
            ->addColumn('updated_at',       'timestamp',    ['null'  => true, 'default' => NULL])                       // когда была изменена запись

            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
