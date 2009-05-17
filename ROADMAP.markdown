Mucking about with some ideas for spec syntax. It should be easy to adapt ztest's `Invoker` system to support this style of testing while maintaining compatibility with the more traditional xUnit style. Assertions would be rewritten to wrap matchers.

Possible syntax:

	<?php
	$this->describe("A user", array(

	  "before" => function() {
	    $this->user = new User;
	  },

	  "should accidentally the whole test" => function() {
	    assert_blank($this->user->get_forename());
	  },
  
	  "with friends" => array(
	    "before" => function() {
	      $this->user->add_friend(new User);
	    },
    
	    "should report as having friends" => function() {
	      assert_equal(1, $this->user->friend_count());
	    }
	  )
  
	));
	?>
	
This script would be included within the context of some class in order for the closures to have access to `$this`.
	
Matcher syntax:

	expect($foo)->isTrue();
	expect($foo)->isNotFalse();

* some __call() mojo required there, especially for negation - not sure I like it
* could alias `expect()` as `expect_that()` for vanity

We'd need a different reporter for this, too.