<?php
require_once dirname(__FILE__).'/vendor/lime.php';
require_once dirname(__FILE__).'/../lib/phpHtmlWriter.php';
require_once dirname(__FILE__).'/phpHtmlWriterTestHelper.php';

$tests = array(
  /**
   *  pure elements
   */
  array(                          // one test
    'params'  =>  array('div'),   // ->tag() parameters
    'result'  =>  '<div></div>'   // expected result
  ),
  array(
    'params'  =>  array('input'),
    'result'  =>  '<input />'
  ),
  /**
   *  invalid expressions
   */
  array(
    'params'  =>  array(null),
    'throws'  =>  'InvalidArgumentException'
  ),
  array(
    'params'  =>  array(' '),
    'throws'  =>  'InvalidArgumentException'
  ),
  array(
    'params'  =>  array(array()),
    'throws'  =>  'InvalidArgumentException'
  ),
  array(
    'params'  =>  array('#my_id'),
    'throws'  =>  'InvalidArgumentException'
  ),
  array(
    'params'  =>  array('.my_class.another.class'),
    'throws'  =>  'InvalidArgumentException'
  ),
  /**
   *  elements with id and classes
   */
  array(
    'params'  =>  array('p#my_id'),
    'result'  =>  '<p id="my_id"></p>'
  ),
  array(
    'params'  =>  array('p.my_class'),
    'result'  =>  '<p class="my_class"></p>'
  ),
  array(
    'params'  =>  array('p.my_class.another_class'),
    'result'  =>  '<p class="my_class another_class"></p>'
  ),
  array(
    'params'  =>  array('p#my_id.my_class.another_class'),
    'result'  =>  '<p id="my_id" class="my_class another_class"></p>'
  ),
  array(
    'params'  =>  array(' p#my_id.my_class.another_class '),
    'result'  =>  '<p id="my_id" class="my_class another_class"></p>'
  ),
  array(
    'params'  =>  array('p#my_id.my_class.another_class'),
    'result'  =>  '<p id="my_id" class="my_class another_class"></p>'
  ),
  /**
   * Elements with content
   */
  array(
    'params'  =>  array('div', 'tag content'),
    'result'  =>  '<div>tag content</div>'
  ),
  array(
    'params'  =>  array('div', '0'),
    'result'  =>  '<div>0</div>'
  ),
  array(
    'params'  =>  array('input', 'tag content'),
    'throws'  =>  'InvalidArgumentException'
  ),
  /**
   * Elements with content, id and classes
   */
  array(
    'params'  =>  array('div#my_id.my_class.another_class', 'tag content'),
    'result'  =>  '<div id="my_id" class="my_class another_class">tag content</div>'
  ),
  /**
   * Elements with parameters
   */
  array(
    'params'  =>  array('div', array('id' => 'my_id')),
    'result'  =>  '<div id="my_id"></div>'
  ),
  array(
    'params'  =>  array('p', array('class' => 'my_class')),
    'result'  =>  '<p class="my_class"></p>'
  ),
  array(
    'params'  =>  array('p', array('class' => 'my_class another_class')),
    'result'  =>  '<p class="my_class another_class"></p>'
  ),
  array(
    'params'  =>  array('p', array('id' => 'my_id', 'class' => 'my_class another_class')),
    'result'  =>  '<p id="my_id" class="my_class another_class"></p>'
  ),
  array(
    'params'  =>  array(' p ', array('id' => ' my_id ', 'class' => ' my_class another_class ')),
    'result'  =>  '<p id=" my_id " class=" my_class another_class "></p>'
  ),
  array(
    'params'  =>  array('a', array('href' => 'http://diem-project.org/')),
    'result'  =>  '<a href="http://diem-project.org/"></a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class', array('href' => 'http://diem-project.org/')),
    'result'  =>  '<a id="my_id" class="my_class another_class" href="http://diem-project.org/"></a>'
  ),
  /**
   * Elements with parameters that need html escaping
   */
  array(
    'params'  =>  array('a', array('title' => 'a " title')),
    'result'  =>  '<a title="a &quot; title"></a>'
  ),
  array(
    'params'  =>  array('a', array('title' => 'a <b> title')),
    'result'  =>  '<a title="a &lt;b&gt; title"></a>'
  ),
  /**
   * Elements with parameters and content
   */
  array(
    'params'  =>  array('div', array('id' => ' my_id ', 'class' => ' my_class another_class '), 'tag content'),
    'result'  =>  '<div id=" my_id " class=" my_class another_class ">tag content</div>'
  )
  /**
   * Elements with id, classes and parameters
   */,
  array(
    'params'  =>  array('a#my_id.my_class.another_class', array('href' => 'http://diem-project.org/')),
    'result'  =>  '<a id="my_id" class="my_class another_class" href="http://diem-project.org/"></a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class', array('id' => 'changed_id', 'href' => 'http://diem-project.org/')),
    'result'  =>  '<a id="changed_id" class="my_class another_class" href="http://diem-project.org/"></a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class', array('class' => 'added_class other_added_class', 'href' => 'http://diem-project.org/')),
    'result'  =>  '<a id="my_id" class="my_class another_class added_class other_added_class" href="http://diem-project.org/"></a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class', array('class' => 'added_class other_added_class my_class', 'href' => 'http://diem-project.org/', 'title' => 'my title')),
    'result'  =>  '<a id="my_id" class="my_class another_class added_class other_added_class" href="http://diem-project.org/" title="my title"></a>'
  ),
  /**
   * Elements inline attribute
   */
  array(
    'params'  =>  array('a href="http://diem-project.org/"', 'some content'),
    'result'  =>  '<a href="http://diem-project.org/">some content</a>'
  ),
  array(
    'params'  =>  array('a class=my_class', 'some content'),
    'result'  =>  '<a class="my_class">some content</a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class href="http://diem-project.org/"'),
    'result'  =>  '<a id="my_id" class="my_class another_class" href="http://diem-project.org/"></a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class id=changed_id href="http://diem-project.org/"'),
    'result'  =>  '<a id="changed_id" class="my_class another_class" href="http://diem-project.org/"></a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class class="added_class other_added_class" href="http://diem-project.org/"'),
    'result'  =>  '<a id="my_id" class="my_class another_class added_class other_added_class" href="http://diem-project.org/"></a>'
  ),
  array(
    'params'  =>  array('a#my_id.my_class.another_class class="added_class other_added_class my_class" href="http://diem-project.org/" title="my title"', 'some content'),
    'result'  =>  '<a id="my_id" class="my_class another_class added_class other_added_class" href="http://diem-project.org/" title="my title">some content</a>'
  )
);

$t = new lime_test(count($tests));

php_html_writer_run_tests($t, $tests, array(new phpHtmlWriter(), 'tag'));
