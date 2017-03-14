<?php
use App\Data\User;
use App\Data\TDGs\UserTDG;

class UnitTest_UserTDG extends TestCase
{
	public function unitTest_create(){
		
		// create a user then find it and compare 
		$userTDG = new UserTDG();
		$user = new user(123,"test","password", false);
		$userTDG->create($user);
		 
		$result  =  $userTDG->find($user->getId());
		$user1 = new User((int)$result[0], (string)$result[1], (string)$result[2], (double)$result[3], (bool)$result[4]);

		assert(compareUser($user,$user1));
		
	} 
	public function unitTest_update(){
		
		// create a user then update the name and compare with the updated user 
		$userTDG = new UserTDG();
		$user = new user(1234,"test","password", false);
		$userTDG->create($user);
		
		$userUpdated = new user(1234,"testUpdate","password", false);
		$userTDG->update(userUpdated);
		$result  =  $userTDG->find($user->getId());
		$user1 = new User((int)$result[0], (string)$result[1], (string)$result[2], (double)$result[3], (bool)$result[4]);

		assert(compareUser($user,$userUpdated));
		
	} 
	
	
	private function compareUser(User $u, User $u1){
		if($u->getId() == $u1->getId() && $u->getName() == $u1->getName() && $u->getPassword() == $u1->getPassword() && $u->getIsCapstone() == $u1->getIsCapstone())
		{
			return true;
		}
		return false;
	}
	

	
	

}

>