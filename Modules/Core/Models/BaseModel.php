<?php

namespace Modules\Core\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $beforeInsert = ['setCreatedAudit'];
    protected $beforeUpdate = ['setUpdatedAudit'];

    protected function setCreatedAudit(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['created_by'] = session('user_id');
        }

        return $data;
    }

    protected function setUpdatedAudit(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['updated_by'] = session('user_id');
        }

        return $data;
    }

    public function eliminar($id)
    {
        return $this->update($id, [
            'deleted_by' => session('user_id')
        ]) && $this->delete($id);
    }

    public function restaurar($id)
    {
        return $this->builder()
            ->where($this->primaryKey, $id)
            ->update([
                'deleted_at' => null,
                'deleted_by' => null
            ]);
    }
}