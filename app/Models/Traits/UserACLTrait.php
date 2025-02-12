<?php
	namespace App\Models\Traits;
	use App\Models\Cargo;
	trait UserACLTrait
    { 
    	/** Todas as Permissoes do Usuario */
        public function permissionsACL() 
        {
        	$pp = [];
        	$permissions = $this::where("id",auth()->user()->id)->with(['cargo','cargo.permissions'])->first();
        	foreach($permissions->cargo->permissions as $p) {
        		array_push($pp,$p->name);
        	}
        	return $pp;
        }

        public function hasPermission(string $permissionName)
        {
            return in_array($permissionName,$this->permissionsACL());
        }

        public function isAdmin()
        {
        	return in_array($this->email,config('acl.admins'));
        }

    }