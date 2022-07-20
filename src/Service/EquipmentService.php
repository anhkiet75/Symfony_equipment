<?php

namespace App\Service;

use App\Http\Repositories\EquipmentRepository;

class EquipmentService {

    private $equipmentRepository;
    public function __construct(EquipmentRepository $equipmentRepository) {
        $this->equipmentRepository = $equipmentRepository;
    }
}