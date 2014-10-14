<?php


class CustomValidator extends Illuminate\Validation\Validator {
	public function validateCountrychosen($attribute, $value, $parameters)
	{
		return $value != 0;
	}
	
	public function validateUrlorfile($attribute, $value, $parameters)
	{
		return false;
		$url = $this->data['url'];
		die("url = #{$url}#");
		if (isset($url) && strlen($url) > 0) return true;
		
		$local = $this->data['localfilename'];
		if (isset($local) && strlen($url) > 0) return true;
		return false;		
	}
	
	public function validateTest($attribute, $value, $parameters)
	{
		die("tot hier[validations@validateTest]");
	}
	

}

Validator::resolver(function($translator, $data, $rules, $messages)
{
	return new CustomValidator($translator, $data, $rules, $messages);
});
?>