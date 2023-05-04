<?php
namespace Tests\Feature\Models;

use Illuminate\Database\Eloquent\Model;

trait ModelHelperInsertTesting{
    public function test_insert_data(): void
    {
        $model = $this->model();
        $table= $model->getTable();
        $data = $model::factory()->make()->toArray();

        $table == 'users' ? $data['password'] = 123456 : null ;

        $model::create($data);
        $this->assertDatabaseHas($table , $data);
    }

    abstract protected function model() : Model;
}
