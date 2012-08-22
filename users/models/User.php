<?php
/** @TableAlias('u') */
class User extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('VARCHAR(120)') @Null 
		* @Email @Required
		*/ $email,
		/** @SqlType('VARCHAR(100)') @Null
		* @Required
		*/ $pwd,
		/** @SqlType('VARCHAR(100)') @Null
		* @MinLength(2)
		*/ $first_name,
		/** @SqlType('VARCHAR(100)') @Null
		* @MinLength(2)
		*/ $last_name;
		
	public function name(){
		return $this->first_name===null ? ($this->last_name === null ? $this->pseudo : $this->last_name) : $this->first_name.' '.$this->last_name;
	}
		
}