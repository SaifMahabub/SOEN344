<?php
use App\Data\EquipmentIdentityMap;
use App\Data\Equipment;

class UnitTest_EquipmentIdentityMap extends TestCase
{
	public function unitTest_get(){
		
		// insert an equipment than get it 
		$equipmentIdentityMap = new EquipmentIdentityMap();
		$id = 654321;
		$equipment = new Equipment($id ,"test",123456);
		$equipmentIdentityMap->add($equipment);
		$equipment1 = $equipmentIdentityMap->get($id);
		assert(compareEquipment($equipment,$equipment1));
		// get an equipment that does not exsit
		$equipment2 = $equipmentIdentityMap->get(888888);
		assert($equipment2 == null);

	} 
	
	
	public function unitTest_add(){
		
		// if the added equipment matches the retrived one the test passes
		$equipmentIdentityMap = new EquipmentIdentityMap();
		$id = 654321;
		$equipment = new Equipment($id ,"test",123456);
		$equipmentIdentityMap->add($equipment);
		$equipment1 = $equipmentIdentityMap->get($id);
		assert(compareEquipment($equipment,$equipment1));
		

	} 
	
	public function unitTest_delete(){
		
		// if the added equipment is deleted it should not be able to be retrived
		$equipmentIdentityMap = new EquipmentIdentityMap();
		$id = 654321;
		$equipment = new Equipment($id ,"test",123456);
		$equipmentIdentityMap->add($equipment);
		
		$equipmentIdentityMap->delete($equipment);
		
		$equipment1 = $equipmentIdentityMap->get($id);
		assert($equipment1 == null);
		

	} 
	
	
	
	
	private function compareEquipment(Equipment $e , Equipment $e1){
		if($e->getId() == $e1->getId() && $e->getName() == $e1->getName() && $e->getAmount() == $e1->getAmount())
		{
			return true;
		}
		return false;
	}
}

>