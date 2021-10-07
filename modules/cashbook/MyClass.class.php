<?php

require_once("rspa/components/Form.class.php");

error_reporting(E_ALL);

class MyClass  extends Form{
	var $counter=0;

    function MyClass() {
    }

    function  multiply() {
    	
	    if(phpversion() > 5){
	       $val = $this->text1->value * $this->text2->value;
	       //$this->text3->class= 'style1';
	       $this->text3->value = $val;
	       $this->counter++;
	    }
	    else{
	       $val = $this->controls["text1"]->getValue() * $this->controls["text2"]->getValue();
	       $this->controls["text3"]->setClass("style1");
	       $this->controls["text3"]->setValue($val);
	       $this->counter++;
	    }
    }
    
     function  getPointDesc() {

	    if(phpversion() > 5){
	       $val = $this->cash_point->value ;
	       $this->cpDesc->class= 'style1';
	       $this->cpDesc->value = 'george';
	       $this->counter++;
	    }
	    else{
	       $val = $this->controls["cash_point"]->getValue();
	       $this->controls["cpDesc"]->setClass("style1");
	       $this->controls["cpDesc"]->setValue($val);
	       $this->counter++;
	    }
    }

    function count(){
   		$this->counter++;
   		$this->controls["counter"]->setValue($this->counter);
    }

   function setOptions(){
   		$options = array("11"=>"Hello",
    					 "21"=>"Select",
    					 "31"=>"Option3"
    					);
    					
    	if(phpversion() > 5){
    		$this->select1->options = $options;
    		$this->select1->addtoSelection("21");
    		$this->select1->disabled = true;
    	}
    	else{
	    	$this->controls["select1"]->setOptions($options);
	    	$this->controls['select1']->addtoSelection("21");
	   		$this->controls["select1"]->setDisabled("true");
    	}
   }

   function functionWithParm($arg, $arg1){
   		if(phpversion() > 5){
   			$this->name2->value = $arg. "  ". $arg1;
   		}
   		else{
   			$this->controls["name2"]->setValue($arg. "  ". $arg1);
   		}
    	
    }

    function autoComplete() {
		$val = $this->controls["autocomplete1"]->getValue();
    	for($i=1;$i<11;$i++){
    	 	$this->controls["autocomplete1"]->addChoice($val. $i,"<B>Hello<u>H R U Sunish</u></b>");
    	}
    }

    function updateLabels() {
    
	    if(phpversion() > 5){
	    	$this->label1->value = "Time on Server is" .date("l dS of F Y h:i:s A");
	    	$this->label2->value = $this->label2->value ."<br>Append to RW Label";
	    }
	    else{
	    	
	    	$this->controls["label1"]->setValue( "Time on Server is" .date("l dS of F Y h:i:s A"));
	    	$this->controls["label2"]->setValue($this->controls["label2"]->getValue()."<br>Append to RW Label");
	    }
		//Testing style:
		$this->controls["label2"]->setStyle("border:1px solid #990099;");
		$this->clientCSS("testStyle", "color:#990099;" );
		$this->clientClass("testClass","class1");

    }

    function executeJS() {
    	$this->clientExecute("alert('Time on server is ".date('d/m/Y h:i:s')."');");
    }
}
?>