<?php
namespace App\Controller;
use App\Controller\AppController;

class GymStoreController extends AppController
{
	public function sellRecord()
	{
		$data = $this->GymStore->find("all")->contain(['GymProduct','GymMember'])->select($this->GymStore)->select(["GymProduct.product_name","GymMember.first_name","GymMember.last_name"])->hydrate(false)->toArray();
		$this->set("data",$data);		
	}
	public function sellProduct()
	{
		$this->set("edit",false);
		
		$members = $this->GymStore->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id",'name' => $members->func()->concat(['first_name'=>'literal', ' ', 'last_name'=>'literal'])])->toArray();
		$this->set("members",$members);
	
		$products = $this->GymStore->GymProduct->find("list",["keyField"=>"id","valueField"=>"product_name"])->toArray();
		$this->set("products",$products);
	
		if($this->request->is("post"))
		{
			$row = $this->GymStore->newEntity();
			$this->request->data["sell_by"] = 1;
			$this->request->data["sell_date"] = date("Y-m-d",strtotime($this->request->data["sell_date"]));		
			$row = $this->GymStore->patchEntity($row,$this->request->data);
			if($this->GymStore->save($row))
			{
				$product = $this->GymStore->GymProduct->get($this->request->data["product_id"]);
				$product->quantity = ($product->quantity) - ($this->request->data["quantity"]);
				if($this->GymStore->GymProduct->save($product))
				{
					$this->Flash->success(__("Success! Record Successfully Saved."));
					return $this->redirect(["action"=>"sellRecord"]);
				}
			}else{
				$this->Flash->error(__("Error! Record Not Saved.Please Try Again."));
			}
		}		
	}
	public function editRecord($pid)
	{	
		$this->set("edit",true);		
		$row = $this->GymStore->get($pid);
		$this->set("data",$row->toArray());
		
		$members = $this->GymStore->GymMember->find("list",["keyField"=>"id","valueField"=>"name"])->where(["role_name"=>"member"]);
		$members = $members->select(["id",'name' => $members->func()->concat(['first_name'=>'literal', ' ', 'last_name'=>'literal'])])->toArray();
		$this->set("members",$members);
	
		$products = $this->GymStore->GymProduct->find("list",["keyField"=>"id","valueField"=>"product_name"])->toArray();
		$this->set("products",$products);
		if($this->request->is("post"))
		{
			$this->request->data["sell_date"] = date("Y-m-d",strtotime($this->request->data["sell_date"]));	
			$row = $this->GymStore->patchEntity($row,$this->request->data);
			if($this->GymStore->save($row))
			{
				$this->Flash->success(__("Success! Record Successfully Updated."));
				return $this->redirect(["action"=>"sellRecord"]);
			}else{
				$this->Flash->error(__("Error! Record Not Updated.Please Try Again."));
			}
		}
		$this->render("sellProduct");
	}
	
	public function deleteRecord($did)
	{
		$row = $this->GymStore->get($did);
		if($this->GymStore->delete($row))
		{
			$this->Flash->success(__("Success! Record Deleted Successfully Updated."));
			return $this->redirect(["action"=>"sellRecord"]); 
		} 		
	}
	
	
	public function isAuthorized($user)
	{
		$role_name = $user["role_name"];
		$curr_action = $this->request->action;
		$members_actions = ["sellRecord"];
		$staff__acc_actions = ["sellRecord","sellProduct","editRecord"];
		switch($role_name)
		{			
			CASE "member":
				if(in_array($curr_action,$members_actions))
				{return true;}else{return false;}
			break;
			
			CASE "staff_member":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{ return false;}
			break;
			
			CASE "accountant":
				if(in_array($curr_action,$staff__acc_actions))
				{return true;}else{return false;}
			break;
		}		
		return parent::isAuthorized($user);
	}
}