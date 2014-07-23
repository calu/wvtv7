<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| such as the size rules. Feel free to tweak each of these messages.
	|
	*/

	"accepted"         => " :attribute moet aanvaard worden.",
	"active_url"       => " :attribute is geen geldige URL.",
	"after"            => " :attribute moet een datum zijn na :date.",
	"alpha"            => " :attribute mag enkel letters bevatten.",
	"alpha_dash"       => " :attribute mag enkel letters , cijfers, en strepen bevatten.",
	"alpha_num"        => " :attribute mag enkel letters en cijfers bevatten.",
	"before"           => " :attribute moet een datum zijn voor :date.",
	"between"          => array(
		"numeric" => " :attribute moet liggen tussen :min - :max.",
		"file"    => " :attribute moet liggen tussen :min - :max kilobytes.",
		"string"  => " :attribute moet liggen tussen :min - :max karakters.",
	),
	"confirmed"        => " :attribute bevestiging komt niet overeen.",
	"date"             => " :attribute is geen geldige datum.",
	"date_format"      => " :attribute komt niet overeen met het formaat :format.",
	"different"        => " :attribute en :or moet verschillend zijn.",
	"digits"           => " :attribute moet bestaan uit :digits cijfers.",
	"digits_between"   => " :attribute moet liggen tussen :min en :max cijfers.",
	"email"            => " :attribute formaat is ongeldig.",
	"exists"           => " gekozen :attribute is ongeldig.",
	"image"            => " :attribute moet een foto zijn.",
	"in"               => " gekozen :attribute is ongeldig.",
	"integer"          => " :attribute moet een geheel getal zijn.",
	"ip"               => " :attribute moet een geldig IP adres zijn.",
	"max"              => array(
		"numeric" => " :attribute mag niet groter zijn als :max.",
		"file"    => " :attribute mag niet groter zijn als :max kilobytes.",
		"string"  => " :attribute mag niet groter zijn als :max karakters.",
	),
	"mimes"            => " :attribute moet een bestand zijn van het type: :values.",
	"min"              => array(
		"numeric" => " :attribute met tenminste zijn :min.",
		"file"    => " :attribute met tenminste zijn :min kilobytes.",
		"string"  => " :attribute met tenminste zijn :min karakters.",
	),
	"not_in"           => "Het gekozen :attribute is ongeldig.",
	"numeric"          => " :attribute moet een getal zijn.",
	"regex"            => " :attribute formaat is ongeldig.",
	"required"         => " :attribute veld is verplicht.",
	"required_with"    => " :attribute veld is verplicht als :values aanwezig is.",
	"required_without" => " :attribute veld is verplicht als :values niet aanwezig is.",
	"same"             => " :attribute en :other moeten overeenkomen.",
	"size"             => array(
		"numeric" => " :attribute moet zijn :size.",
		"file"    => " :attribute moet zijn :size kilobytes.",
		"string"  => " :attribute moet zijn :size karakters.",
	),
	"unique"           => " :attribute werd reeds gekozen.",
	"url"              => " :attribute formaat is ongeldig.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
