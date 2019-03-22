<?php
/**
 * @package toolkit
 */
/**
 * Widget is a utility class that offers a number miscellaneous of
 * functions to help generate common HTML Forms elements as XMLElement
 * objects for inclusion in Symphony backend pages.
 */
class Widget
{
    /**
     * Generates a XMLElement representation of `<label>`
     *
     * @param string $name (optional)
     *  The text for the resulting `<label>`
     * @param XMLElement $child (optional)
     *  An XMLElement that this <label> will become the parent of.
     *  Commonly used with `<input>`.
     * @param string $class (optional)
     *  The class attribute of the resulting `<label>`
     * @param string $id (optional)
     *  The id attribute of the resulting `<label>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Label($name = null, XMLElement $child = null, $class = null, $id = null, array $attributes = null)
    {
        General::ensureType(array(
            'name' => array('var' => $name, 'type' => 'string', 'optional' => true),
            'class' => array('var' => $class, 'type' => 'string', 'optional' => true),
            'id' => array('var' => $id, 'type' => 'string', 'optional' => true)
        ));

        $obj = new XMLElement('label', ($name ? $name : null));

        if (is_object($child)) {
            $obj->appendChild($child);
        }

        if ($class) {
            $obj->setAttribute('class', $class);
        }

        if ($id) {
            $obj->setAttribute('id', $id);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of `<input>`
     *
     * @param string $name
     *  The name attribute of the resulting `<input>`
     * @param string $value (optional)
     *  The value attribute of the resulting `<input>`
     * @param string $type
     *  The type attribute for this `<input>`, defaults to "text".
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Input($name, $value = null, $type = 'text', array $attributes = null)
    {
        General::ensureType(array(
            'name' => array('var' => $name, 'type' => 'string'),
            'value' => array('var' => $value, 'type' => 'string', 'optional' => true),
            'type' => array('var' => $type, 'type' => 'string', 'optional' => true),
        ));

        $obj = new XMLElement('input');
        $obj->setAttribute('name', $name);

        if ($type) {
            $obj->setAttribute('type', $type);
        }

        if (strlen($value) !== 0) {
            $obj->setAttribute('value', $value);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of a `<input type='checkbox'>`. This also
     * includes the actual label of the Checkbox and any help text if required. Note that
     * this includes two input fields, one is the hidden 'no' value and the other
     * is the actual checkbox ('yes' value). This is provided so if the checkbox is
     * not checked, 'no' is still sent in the form request for this `$name`.
     *
     * @since Symphony 2.5.2
     * @param string $name
     *  The name attribute of the resulting checkbox
     * @param string $value
     *  The value attribute of the resulting checkbox
     * @param string $description
     *  This will be localisable and displayed after the checkbox when
     *  generated.
     * @param XMLElement $wrapper
     *  Passed by reference, if this is provided the elements will be automatically
     *  added to the wrapper, otherwise they will just be returned.
     * @param string $help (optional)
     *  A help message to show below the checkbox.
     * @throws InvalidArgumentException
     * @return XMLElement
     *  The markup for the label and the checkbox.
     */
    public static function Checkbox($name, $value, $description = null, XMLElement &$wrapper = null, $help = null)
    {
        General::ensureType(array(
            'name' => array('var' => $name, 'type' => 'string'),
            'value' => array('var' => $value, 'type' => 'string', 'optional' => true),
            'description' => array('var' => $description, 'type' => 'string'),
            'help' => array('var' => $help, 'type' => 'string', 'optional' => true),
        ));

        // Build the label
        $label = Widget::Label();
        if ($help) {
            $label->addClass('inline-help');
        }

        // Add the 'no' default option to the label, or to the wrapper if it's provided
        $default_hidden = Widget::Input($name, 'no', 'hidden');
        if (is_null($wrapper)) {
            $label->appendChild($default_hidden);
        } else {
            $wrapper->appendChild($default_hidden);
        }

        // Include the actual checkbox.
        $input = Widget::Input($name, 'yes', 'checkbox');
        if ($value === 'yes') {
            $input->setAttribute('checked', 'checked');
        }

        // Build the checkbox, then label, then help
        $label->setValue(__('%s ' . $description . ' %s', array(
            $input->generate(),
            ($help) ? ' <i>(' . $help . ')</i>' : ''
        )));

        // If a wrapper was given, add the label to it
        if (!is_null($wrapper)) {
            $wrapper->appendChild($label);
        }

        return $label;
    }

    /**
     * Generates a XMLElement representation of `<textarea>`
     *
     * @param string $name
     *  The name of the resulting `<textarea>`
     * @param integer $rows (optional)
     *  The height of the `<textarea>`, using the rows attribute. Defaults to 15
     * @param integer $cols (optional)
     *  The width of the `<textarea>`, using the cols attribute. Defaults to 50.
     * @param string $value (optional)
     *  The content to be displayed inside the `<textarea>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Textarea($name, $rows = 15, $cols = 50, $value = null, array $attributes = null)
    {
        General::ensureType(array(
            'name' => array('var' => $name, 'type' => 'string'),
            'rows' => array('var' => $rows, 'type' => 'int'),
            'cols' => array('var' => $cols, 'type' => 'int'),
            'value' => array('var' => $value, 'type' => 'string', 'optional' => true)
        ));

        $obj = new XMLElement('textarea', $value);

        $obj->setSelfClosingTag(false);

        $obj->setAttribute('name', $name);
        $obj->setAttribute('rows', $rows);
        $obj->setAttribute('cols', $cols);

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of `<a>`
     *
     * @param string $value
     *  The text of the resulting `<a>`
     * @param string $href
     *  The href attribute of the resulting `<a>`
     * @param string $title (optional)
     *  The title attribute of the resulting `<a>`
     * @param string $class (optional)
     *  The class attribute of the resulting `<a>`
     * @param string $id (optional)
     *  The id attribute of the resulting `<a>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Anchor($value, $href, $title = null, $class = null, $id = null, array $attributes = null)
    {
        General::ensureType(array(
            'value' => array('var' => $value, 'type' => 'string'),
            'href' => array('var' => $href, 'type' => 'string'),
            'title' => array('var' => $title, 'type' => 'string', 'optional' => true),
            'class' => array('var' => $class, 'type' => 'string', 'optional' => true),
            'id' => array('var' => $id, 'type' => 'string', 'optional' => true)
        ));

        $obj = new XMLElement('a', $value);
        $obj->setAttribute('href', $href);

        if ($title) {
            $obj->setAttribute('title', $title);
        }

        if ($class) {
            $obj->setAttribute('class', $class);
        }

        if ($id) {
            $obj->setAttribute('id', $id);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of `<form>`
     *
     * @param string $action
     *  The text of the resulting `<form>`
     * @param string $method
     *  The method attribute of the resulting `<form>`. Defaults to "post".
     * @param string $class (optional)
     *  The class attribute of the resulting `<form>`
     * @param string $id (optional)
     *  The id attribute of the resulting `<form>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Form($action = null, $method = 'post', $class = null, $id = null, array $attributes = null)
    {
        General::ensureType(array(
            'action' => array('var' => $action, 'type' => 'string', 'optional' => true),
            'method' => array('var' => $method, 'type' => 'string'),
            'class' => array('var' => $class, 'type' => 'string', 'optional' => true),
            'id' => array('var' => $id, 'type' => 'string', 'optional' => true)
        ));

        $obj = new XMLElement('form');
        $obj->setAttribute('action', $action);
        $obj->setAttribute('method', $method);

        if ($class) {
            $obj->setAttribute('class', $class);
        }

        if ($id) {
            $obj->setAttribute('id', $id);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of `<table>`
     * This is a simple way to create generic Symphony table wrapper
     *
     * @param XMLElement $header
     *  An XMLElement containing the `<thead>`. See Widget::TableHead
     * @param XMLElement $footer
     *  An XMLElement containing the `<tfoot>`
     * @param XMLElement $body
     *  An XMLElement containing the `<tbody>`. See Widget::TableBody
     * @param string $class (optional)
     *  The class attribute of the resulting `<table>`
     * @param string $id (optional)
     *  The id attribute of the resulting `<table>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Table(XMLElement $header = null, XMLElement $footer = null, XMLElement $body = null, $class = null, $id = null, Array $attributes = null)
    {
        General::ensureType(array(
            'class' => array('var' => $class, 'type' => 'string', 'optional' => true),
            'id' => array('var' => $id, 'type' => 'string', 'optional' => true)
        ));

        $obj = new XMLElement('table');

        if ($class) {
            $obj->setAttribute('class', $class);
        }

        if ($id) {
            $obj->setAttribute('id', $id);
        }

        if ($header) {
            $obj->appendChild($header);
        }

        if ($footer) {
            $obj->appendChild($footer);
        }

        if ($body) {
            $obj->appendChild($body);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of `<thead>` from an array
     * containing column names and any other attributes.
     *
     * @param array $columns
     *  An array of column arrays, where the first item is the name of the
     *  column, the second is the scope attribute, and the third is an array
     *  of possible attributes.
     *  `
     *   array(
     *      array('Column Name', 'scope', array('class'=>'th-class'))
     *   )
     *  `
     * @return XMLElement
     */
    public static function TableHead(array $columns = null)
    {
        $thead = new XMLElement('thead');
        $tr = new XMLElement('tr');

        if (is_array($columns) && !empty($columns)) {
            foreach ($columns as $col) {
                $th = new XMLElement('th');

                if (is_object($col[0])) {
                    $th->appendChild($col[0]);
                } else {
                    $th->setValue($col[0]);
                }

                if ($col[1] && $col[1] != '') {
                    $th->setAttribute('scope', $col[1]);
                }

                if (!empty($col[2]) && is_array($col[2])) {
                    $th->setAttributeArray($col[2]);
                }

                $tr->appendChild($th);
            }
        }

        $thead->appendChild($tr);

        return $thead;
    }

    /**
     * Generates a XMLElement representation of `<tbody>` from an array
     * containing `<tr>` XMLElements
     *
     * @see toolkit.Widget#TableRow()
     * @param array $rows
     *  An array of XMLElements of `<tr>`'s.
     * @param string $class (optional)
     *  The class attribute of the resulting `<tbody>`
     * @param string $id (optional)
     *  The id attribute of the resulting `<tbody>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function TableBody(array $rows, $class = null, $id = null, array $attributes = null)
    {
        General::ensureType(array(
            'class' => array('var' => $class, 'type' => 'string', 'optional' => true),
            'id' => array('var' => $id, 'type' => 'string', 'optional' => true)
        ));

        $tbody = new XMLElement('tbody');

        if ($class) {
            $tbody->setAttribute('class', $class);
        }

        if ($id) {
            $tbody->setAttribute('id', $id);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $tbody->setAttributeArray($attributes);
        }

        foreach ($rows as $row) {
            $tbody->appendChild($row);
        }

        return $tbody;
    }

    /**
     * Generates a XMLElement representation of `<tr>` from an array
     * containing column names and any other attributes.
     *
     * @param array $cells
     *  An array of XMLElements of `<td>`'s. See Widget::TableData
     * @param string $class (optional)
     *  The class attribute of the resulting `<tr>`
     * @param string $id (optional)
     *  The id attribute of the resulting `<tr>`
     * @param integer $rowspan (optional)
     *  The rowspan attribute of the resulting `<tr>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function TableRow(array $cells, $class = null, $id = null, $rowspan = null, Array $attributes = null)
    {
        General::ensureType(array(
            'class' => array('var' => $class, 'type' => 'string', 'optional' => true),
            'id' => array('var' => $id, 'type' => 'string', 'optional' => true),
            'rowspan' => array('var' => $rowspan, 'type' => 'int', 'optional' => true)
        ));

        $tr = new XMLElement('tr');

        if ($class) {
            $tr->setAttribute('class', $class);
        }

        if ($id) {
            $tr->setAttribute('id', $id);
        }

        if ($rowspan) {
            $tr->setAttribute('rowspan', $rowspan);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $tr->setAttributeArray($attributes);
        }

        foreach ($cells as $cell) {
            $tr->appendChild($cell);
        }

        return $tr;
    }

    /**
     * Generates a XMLElement representation of a `<td>`.
     *
     * @param XMLElement|string $value
     *  Either an XMLElement object, or a string for the value of the
     *  resulting `<td>`
     * @param string $class (optional)
     *  The class attribute of the resulting `<td>`
     * @param string $id (optional)
     *  The id attribute of the resulting `<td>`
     * @param integer $colspan (optional)
     *  The colspan attribute of the resulting `<td>`
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function TableData($value, $class = null, $id = null, $colspan = null, Array $attributes = null)
    {
        General::ensureType(array(
            'class' => array('var' => $class, 'type' => 'string', 'optional' => true),
            'id' => array('var' => $id, 'type' => 'string', 'optional' => true),
            'colspan' => array('var' => $colspan, 'type' => 'int', 'optional' => true)
        ));

        if (is_object($value)) {
            $td = new XMLElement('td');
            $td->appendChild($value);
        } else {
            $td = new XMLElement('td', $value);
        }

        if ($class) {
            $td->setAttribute('class', $class);
        }

        if ($id) {
            $td->setAttribute('id', $id);
        }

        if ($colspan) {
            $td->setAttribute('colspan', $colspan);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $td->setAttributeArray($attributes);
        }

        return $td;
    }

    /**
     * Generates a XMLElement representation of a `<time>`
     *
     * @since Symphony 2.3
     * @param string $string
     *  A string containing date and time, defaults to the current date and time
     * @param string $format (optional)
     *  A valid PHP date format, defaults to `__SYM_TIME_FORMAT__`
     * @param boolean $pubdate (optional)
     *  A flag to make the given date a publish date
     * @return XMLElement
     */
    public static function Time($string = 'now', $format = __SYM_TIME_FORMAT__, $pubdate = false)
    {
        // Parse date
        $date = DateTimeObj::parse($string);

        // Create element
        $obj = new XMLElement('time', Lang::localizeDate($date->format($format)));
        $obj->setAttribute('datetime', $date->format(DateTime::ISO8601));
        $obj->setAttribute('utc', $date->format('U'));

        // Pubdate?
        if ($pubdate === true) {
            $obj->setAttribute('pubdate', 'pubdate');
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of a `<select>`. This uses
     * the private function `__SelectBuildOption()` to build XMLElements of
     * options given the `$options` array.
     *
     * @see toolkit.Widget::__SelectBuildOption()
     * @param string $name
     *  The name attribute of the resulting `<select>`
     * @param array $options (optional)
     *  An array containing the data for each `<option>` for this
     *  `<select>`. If the array is associative, it is assumed that
     *  `<optgroup>` are to be created, otherwise it's an array of the
     *  containing the option data. If no options are provided an empty
     *  `<select>` XMLElement is returned.
     *  `
     *   array(
     *    array($value, $selected, $desc, $class, $id, $attr)
     *   )
     *   array(
     *    array('label' => 'Optgroup', 'data-label' => 'optgroup', 'options' = array(
     *        array($value, $selected, $desc, $class, $id, $attr)
     *    )
     *   )
     *  `
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params.
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Select($name, array $options = null, array $attributes = null)
    {
        General::ensureType(array(
            'name' => array('var' => $name, 'type' => 'string')
        ));

        $obj = new XMLElement('select');
        $obj->setAttribute('name', $name);

        $obj->setSelfClosingTag(false);

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        if (!is_array($options) || empty($options)) {
            if (!isset($attributes['disabled'])) {
                $obj->setAttribute('disabled', 'disabled');
            }

            return $obj;
        }

        foreach ($options as $o) {
            //  Optgroup
            if (isset($o['label'])) {
                $optgroup = new XMLElement('optgroup');
                $optgroup->setAttribute('label', $o['label']);

                if (isset($o['data-label'])) {
                    $optgroup->setAttribute('data-label', $o['data-label']);
                }

                foreach ($o['options'] as $option) {
                    $optgroup->appendChild(
                        Widget::__SelectBuildOption($option)
                    );
                }

                $obj->appendChild($optgroup);
            } else {
                $obj->appendChild(Widget::__SelectBuildOption($o));
            }
        }

        return $obj;
    }

    /**
     * This function is used internally by the `Widget::Select()` to build
     * an XMLElement of an `<option>` from an array.
     *
     * @param array $option
     *  An array containing the data a single `<option>` for this
     *  `<select>`. The array can contain the following params:
     *      string $value
     *          The value attribute of the resulting `<option>`
     *      boolean $selected
     *          Whether this `<option>` should be selected
     *      string $desc (optional)
     *          The text of the resulting `<option>`. If omitted $value will
     *          be used a default.
     *      string $class (optional)
     *          The class attribute of the resulting `<option>`
     *      string $id (optional)
     *          The id attribute of the resulting `<option>`
     *      array $attributes (optional)
     *          Any additional attributes can be included in an associative
     *          array with the key being the name and the value being the
     *          value of the attribute. Attributes set from this array
     *          will override existing attributes set by previous params.
     *  `array(
     *      array('one-shot', false, 'One Shot', 'my-option')
     *   )`
     * @return XMLElement
     */
    private static function __SelectBuildOption($option)
    {
        list($value, $selected, $desc, $class, $id, $attributes) = array_pad($option, 6, null);

        if (!$desc) {
            $desc = $value;
        }

        $obj = new XMLElement('option', "$desc");
        $obj->setSelfClosingTag(false);
        $obj->setAttribute('value', "$value");

        if (!empty($class)) {
            $obj->setAttribute('class', $class);
        }

        if (!empty($id)) {
            $obj->setAttribute('id', $id);
        }

        if (is_array($attributes) && !empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }

        if ($selected) {
            $obj->setAttribute('selected', 'selected');
        }

        return $obj;
    }

    /**
     * Generates a XMLElement representation of a `<fieldset>` containing
     * the "With selected…" menu. This uses the private function `__SelectBuildOption()`
     * to build `XMLElement`'s of options given the `$options` array.
     *
     * @since Symphony 2.3
     * @see toolkit.Widget::__SelectBuildOption()
     * @param array $options (optional)
     *  An array containing the data for each `<option>` for this
     *  `<select>`. If the array is associative, it is assumed that
     *  `<optgroup>` are to be created, otherwise it's an array of the
     *  containing the option data. If no options are provided an empty
     *  `<select>` XMLElement is returned.
     *  `
     *   array(
     *    array($value, $selected, $desc, $class, $id, $attr)
     *   )
     *   array(
     *    array('label' => 'Optgroup', 'options' = array(
     *        array($value, $selected, $desc, $class, $id, $attr)
     *    )
     *   )
     *  `
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Apply(array $options = null)
    {
        $fieldset = new XMLElement('fieldset', null, array('class' => 'apply'));
        $div = new XMLElement('div');
        $div->appendChild(Widget::Label(__('Actions'), null, 'accessible', null, array(
            'for' => 'with-selected'
        )));
        $div->appendChild(Widget::Select('with-selected', $options, array(
            'id' => 'with-selected'
        )));
        $fieldset->appendChild($div);
        $fieldset->appendChild(new XMLElement('button', __('Apply'), array('name' => 'action[apply]', 'type' => 'submit')));

        return $fieldset;
    }

    /**
     * Will wrap a `<div>` around a desired element to trigger the default
     * Symphony error styling.
     *
     * @since Symphony 2.3
     * @param XMLElement $element
     *  The element that should be wrapped with an error
     * @param string $message
     *  The text for this error. This will be appended after the $element,
     *  but inside the wrapping `<div>`
     * @throws InvalidArgumentException
     * @return XMLElement
     */
    public static function Error(XMLElement $element, $message)
    {
        General::ensureType(array(
            'message' => array('var' => $message, 'type' => 'string')
        ));

        $div = new XMLElement('div');
        $div->setAttributeArray(array('class' => 'invalid'));

        $div->appendChild($element);
        $div->appendChild(new XMLElement('p', $message));

        return $div;
    }

    /**
     * Generates a XMLElement representation of a Symphony drawer widget.
     * A widget is identified by it's `$label`, and it's contents is defined
     * by the `XMLElement`, `$content`.
     *
     * @since Symphony 2.3
     * @param string $id
     *  The id attribute for this drawer
     * @param string $label
     *  A name for this drawer
     * @param XMLElement $content
     *  An XMLElement containing the HTML that should be contained inside
     *  the drawer.
     * @param string $default_state
     *  This parameter defines whether the drawer will be open or closed by
     *  default. It defaults to closed.
     * @param string $context
     * @param array $attributes (optional)
     *  Any additional attributes can be included in an associative array with
     *  the key being the name and the value being the value of the attribute.
     *  Attributes set from this array will override existing attributes
     *  set by previous params, except the `id` attribute.
     * @return XMLElement
     */
    public static function Drawer($id = '', $label = '', XMLElement $content = null, $default_state = 'closed', $context = '', array $attributes = array())
    {
        $id = Lang::createHandle($id);

        $contents = new XMLElement('div', $content, array(
            'class' => 'contents'
        ));
        $contents->setElementStyle('html');

        $drawer = new XMLElement('div', $contents, $attributes);
        $drawer->setAttribute('data-default-state', $default_state);
        $drawer->setAttribute('data-context', $context);
        $drawer->setAttribute('data-label', $label);
        $drawer->setAttribute('data-interactive', 'data-interactive');
        $drawer->addClass('drawer');
        $drawer->setAttribute('id', 'drawer-' . $id);

        return $drawer;
    }

    /**
     * Generates a XMLElement representation of a Symphony calendar.
     *
     * @since Symphony 2.6
     * @param boolean $time
     *  Wheather or not to display the time, defaults to true
     * @return XMLElement
     */
    public static function Calendar($time = true)
    {
        $calendar = new XMLElement('div');
        $calendar->setAttribute('class', 'calendar');

        $date = DateTimeObj::convertDateToMoment(DateTimeObj::getSetting('date_format'));
        if ($date) {
            if ($time === true) {
                $separator = DateTimeObj::getSetting('datetime_separator');
                $time = DateTimeObj::convertTimeToMoment(DateTimeObj::getSetting('time_format'));

                $calendar->setAttribute('data-calendar', 'datetime');
                $calendar->setAttribute('data-format', $date . $separator . $time);
            } else {
                $calendar->setAttribute('data-calendar', 'date');
                $calendar->setAttribute('data-format', $date);
            }
        }

        return $calendar;
    }

    /**
     * SVG icons library
     *
     * @since Symphony 2.7.0
     */

    private static $svgicons = array(
        'add' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="27px" height="26px" viewBox="0 0 27 26"><path fill="currentColor" d="M26,24c-0.6,0-1-0.4-1-1V11.5L22.6,11c0,0,0,0,0,0C18.9,11,16,8.5,16,4.9V1c0-0.6,0.4-1,1-1s1,0.4,1,1v3.9c0,2.5,2,4.5,4.6,4.5L26,9.6c0.5,0,1,0.5,1,1V23C27,23.6,26.6,24,26,24z"/><path fill="currentColor" d="M24,26H8c-1.7,0-3-1.3-3-3c0-0.6,0.4-1,1-1s1,0.4,1,1c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1V10.1c-0.6-1.3-6.8-7.5-8.1-8.1H8C7.4,2,7,2.4,7,3c0,0.6-0.4,1-1,1S5,3.6,5,3c0-1.7,1.3-3,3-3h9c1.7,0,10,8.3,10,10v13C27,24.7,25.7,26,24,26z M25,10.2L25,10.2L25,10.2z M16.8,2L16.8,2L16.8,2z"/><path fill="currentColor" d="M11,14H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h10c0.6,0,1,0.4,1,1S11.6,14,11,14z"/><path fill="currentColor" d="M6,19c-0.6,0-1-0.4-1-1V8c0-0.6,0.4-1,1-1s1,0.4,1,1v10C7,18.6,6.6,19,6,19z"/></svg>',
        'arrow' => '<svg width="5" height="9" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7.82843L1 4.66634L4 1.57154" fill="currentColor"/><path d="M4 7.82843L1 4.66634L4 1.57154L4 7.82843Z" stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'associations' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="30px" height="24px" viewBox="0 0 30 24"><path fill="currentColor" d="M26,24c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C28.1,23.5,27.1,24,26,24z M26,18c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C27,18.2,26.5,18,26,18z"/><path fill="currentColor" d="M4,8C2.9,8,1.9,7.5,1.2,6.8c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C6.1,7.5,5.1,8,4,8z M4,2C3.5,2,3,2.2,2.6,2.5c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C5,2.2,4.5,2,4,2z"/><path fill="currentColor" d="M26,8c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C28.1,7.5,27.1,8,26,8z M26,2c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C27,2.2,26.5,2,26,2z"/><path fill="currentColor" d="M22.5,20.7c-7,0-8-4.9-8.9-8.8C12.8,8.1,12.1,5,7,5C6.4,5,6,4.5,6,4s0.4-1,1-1h16c0.6,0,1,0.4,1,1s-0.4,1-1,1H12.8c1.8,1.7,2.3,4.3,2.8,6.6c0.9,4,1.5,7.2,6.9,7.2c0.6,0,1,0.4,1,1S23,20.7,22.5,20.7z"/></svg>',
        'burger' => '<svg version="1" xmlns="http://www.w3.org/2000/svg" width="24" height="15" viewBox="0 0 24 15" class="line-height-0 valign-top width-full height-full block"><path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" d="M0 0v3h24V0H0zm0 9h24V6H0v3zm0 6h24v-3H0v3z"/></svg>',
        'chevron' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="9.2px" height="5.6px" viewBox="0 0 9.2 5.6"><path fill="currentColor" d="M4.6,5.6c-0.3,0-0.5-0.1-0.7-0.3L0.3,1.7c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l2.9,2.9l2.8-2.8c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4L5.3,5.3C5.2,5.5,4.9,5.6,4.6,5.6z"/></svg>',
        'delete' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26.2px" height="26px" viewBox="0 0 26.2 26"><path fill="currentColor" d="M25.2,24c-0.6,0-1-0.4-1-1V11.5L21.8,11c0,0,0,0,0,0c-3.7,0-6.6-2.5-6.6-6.1V1c0-0.6,0.4-1,1-1s1,0.4,1,1v3.9c0,2.5,2,4.5,4.6,4.5l3.4,0.1c0.5,0,1,0.5,1,1V23C26.2,23.6,25.8,24,25.2,24z"/><path fill="currentColor" d="M23.2,26h-16c-1.7,0-3-1.3-3-3c0-0.6,0.4-1,1-1s1,0.4,1,1c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1V10.1c-0.6-1.3-6.8-7.5-8.1-8.1H7.2c-0.6,0-1,0.4-1,1c0,0.6-0.4,1-1,1s-1-0.4-1-1c0-1.7,1.3-3,3-3h9c1.7,0,10,8.3,10,10v13C26.2,24.7,24.9,26,23.2,26z M24.3,10.2L24.3,10.2L24.3,10.2z M16.1,2L16.1,2L16.1,2z"/><path fill="currentColor" d="M1,18.2c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l8.5-8.5c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-8.5,8.5C1.5,18.1,1.3,18.2,1,18.2z"/><path fill="currentColor" d="M9.5,18.2c-0.3,0-0.5-0.1-0.7-0.3L0.3,9.5c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l8.5,8.5c0.4,0.4,0.4,1,0,1.4C10,18.1,9.7,18.2,9.5,18.2z"/></svg>',
        'edit' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26px" height="26px" viewBox="0 0 26 26"><path fill="currentColor" d="M13.2,26c-2.2,0-2.8-1.9-3.1-3c-0.1-0.4-0.2-0.7-0.4-1c0-0.1-0.1-0.2-0.1-0.2c-0.1,0-0.1,0-0.2,0.1c-0.3,0.1-0.6,0.3-1,0.5c-1,0.6-2.8,1.6-4.4,0c-1.6-1.6-0.7-3.3-0.2-4.4c0.2-0.4,0.4-0.7,0.4-1c0-0.1,0-0.2,0.1-0.2c-0.1,0-0.1-0.1-0.2-0.1c-0.3-0.1-0.6-0.2-1-0.3C2,16,0,15.5,0,13.3c0-2.2,1.9-2.8,3-3.2c0.4-0.1,0.7-0.2,1-0.4c0.1,0,0.2-0.1,0.2-0.1c0-0.1,0-0.1-0.1-0.2C4,9,3.8,8.7,3.6,8.4c-0.6-1-1.6-2.8,0-4.4C5.2,2.4,6.9,3.3,8,3.8C8.4,4,8.7,4.2,9,4.3c0.1,0,0.2,0,0.2,0.1c0-0.1,0.1-0.1,0.1-0.2c0.1-0.3,0.2-0.6,0.3-1C10,2,10.5,0,12.7,0c2.2,0,2.8,1.9,3.2,3c0.1,0.4,0.2,0.7,0.4,1c0.1,0.1,0.1,0.2,0.1,0.2c0.1,0,0.1,0,0.2-0.1C17,4,17.3,3.9,17.6,3.7c1-0.6,2.8-1.6,4.4,0c1.6,1.6,0.7,3.3,0.2,4.4c-0.2,0.4-0.4,0.7-0.4,1c0,0.1,0,0.2-0.1,0.2c0.1,0,0.1,0.1,0.2,0.1c0.3,0.1,0.6,0.2,1,0.3C24,10,26,10.5,26,12.7c0,2.3-1.9,2.8-3,3.2c-0.4,0.1-0.7,0.2-1,0.4c-0.1,0-0.2,0.1-0.2,0.1c0,0.1,0,0.1,0.1,0.2c0.1,0.3,0.3,0.6,0.5,1c0.6,1,1.6,2.8,0,4.4c-1.6,1.6-3.3,0.7-4.4,0.2c-0.4-0.2-0.7-0.4-1-0.4c-0.1,0-0.2,0-0.2-0.1c0,0.1-0.1,0.1-0.1,0.2c-0.1,0.3-0.2,0.6-0.3,1C16,24,15.5,26,13.2,26C13.2,26,13.2,26,13.2,26z M9.8,19.8c0.2,0,0.3,0,0.5,0.1c0.6,0.2,0.9,0.7,1.2,1.2c0.2,0.4,0.4,0.9,0.5,1.4c0.4,1.3,0.6,1.6,1.3,1.6c0.6,0,0.8-0.3,1.2-1.6c0.1-0.5,0.3-1,0.5-1.4c0.3-0.5,0.6-1,1-1.2c0.5-0.2,1-0.1,1.7,0c0.5,0.1,0.9,0.4,1.3,0.6c1.2,0.7,1.6,0.7,2,0.2c0.4-0.5,0.4-0.8-0.3-2c-0.2-0.4-0.5-0.9-0.6-1.3c-0.2-0.6-0.3-1.1-0.1-1.6c0.2-0.5,0.6-0.8,1.2-1.1c0,0,0,0,0,0c0.4-0.2,0.9-0.4,1.4-0.5c1.3-0.4,1.6-0.6,1.6-1.3s-0.3-0.8-1.6-1.2c-0.5-0.1-1-0.3-1.4-0.5c-0.5-0.3-1-0.6-1.2-1c-0.2-0.6-0.1-1.1,0-1.7c0.1-0.5,0.4-0.9,0.6-1.3c0.7-1.2,0.7-1.6,0.2-2c-0.5-0.4-0.8-0.4-2,0.3c-0.4,0.2-0.9,0.5-1.3,0.6c-0.6,0.2-1.1,0.3-1.6,0.1c-0.6-0.2-0.9-0.7-1.1-1.2C14.3,4.5,14.2,4,14,3.6C13.6,2.2,13.4,2,12.8,2c-0.6,0-0.8,0.3-1.2,1.6c-0.1,0.5-0.3,1-0.5,1.4c-0.3,0.5-0.6,1-1,1.2C9.5,6.5,9,6.4,8.4,6.2C8,6.1,7.5,5.8,7.1,5.6C5.8,5,5.5,4.9,5.1,5.4c-0.4,0.5-0.4,0.8,0.3,2C5.6,7.8,5.9,8.2,6,8.7c0.2,0.6,0.3,1.1,0.2,1.5c0,0,0,0.1,0,0.1c-0.2,0.4-0.6,0.8-1.2,1.1c-0.4,0.2-0.9,0.4-1.4,0.5C2.2,12.4,2,12.6,2,13.2c0,0.6,0.3,0.8,1.6,1.2c0.5,0.1,1,0.3,1.4,0.5c0.5,0.3,1,0.6,1.2,1c0.2,0.6,0.1,1.1,0,1.7c-0.1,0.5-0.4,0.9-0.6,1.3c-0.7,1.2-0.7,1.6-0.2,2c0.5,0.4,0.8,0.4,2-0.3c0.4-0.2,0.9-0.5,1.3-0.6C9.1,19.8,9.5,19.8,9.8,19.8z"/><path fill="currentColor" d="M13,19.2c-3.4,0-6.2-2.8-6.2-6.2S9.6,6.8,13,6.8s6.2,2.8,6.2,6.2S16.4,19.2,13,19.2z M13,8.8c-2.3,0-4.2,1.9-4.2,4.2s1.9,4.2,4.2,4.2s4.2-1.9,4.2-4.2S15.3,8.8,13,8.8z"/></svg>',
        'filter' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26px" height="26px" viewBox="0 0 26 26"><path fill="currentColor" d="M25,5H1C0.4,5,0,4.5,0,4s0.4-1,1-1h24c0.6,0,1,0.4,1,1S25.6,5,25,5z"/><ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -0.7512 6.1102)" fill="#fff" cx="7" cy="4" rx="3" ry="3"/><path fill="currentColor" d="M7,8C5.9,8,4.9,7.5,4.2,6.8c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C9.1,7.5,8.1,8,7,8z M7,2C6.5,2,6,2.2,5.6,2.5c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C8,2.2,7.5,2,7,2z"/><path fill="currentColor" d="M25,14H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h24c0.6,0,1,0.4,1,1S25.6,14,25,14z"/><ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -3.3076 17.9386)" fill="#fff" cx="20" cy="13" rx="3" ry="3"/><path fill="currentColor" d="M20,17c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C22.1,16.5,21.1,17,20,17z M20,11c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C21,11.2,20.5,11,20,11z"/><path fill="currentColor" d="M25,23H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h24c0.6,0,1,0.4,1,1S25.6,23,25,23z"/><ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -12.3076 14.2107)" fill="#fff" cx="11" cy="22" rx="3" ry="3"/><path fill="currentColor" d="M11,26c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C13.1,25.5,12.1,26,11,26z M11,20c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C12,20.2,11.5,20,11,20z"/></svg>',
        'save' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="28.7px" height="19.3px" viewBox="0 0 28.7 19.3"><path fill="currentColor" d="M21.2,19.3H6.4C2.8,19.3,0,16.5,0,13c0-2.9,2-5.4,4.7-6.1C5.5,2.9,9,0,13.2,0c2.3,0,4.4,0.9,6.1,2.5c0.4,0.4,0.4,1,0,1.4c-0.4,0.4-1,0.4-1.4,0C16.6,2.7,15,2,13.2,2C9.8,2,7,4.5,6.6,7.9c0,0.5-0.4,0.8-0.9,0.9C3.6,9.1,2,10.9,2,13c0,2.4,1.9,4.3,4.4,4.3h14.8c3.1,0,5.5-2.4,5.5-5.4c0-1.9-1.1-3.7-2.8-4.7c-0.5-0.3-0.6-0.9-0.4-1.4c0.3-0.5,0.9-0.6,1.4-0.4c2.3,1.3,3.8,3.8,3.8,6.4C28.7,16,25.4,19.3,21.2,19.3z"/><path fill="currentColor" d="M13.9,13.2L13.9,13.2c-0.3,0-0.5-0.1-0.7-0.3L9.5,9.3c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l2.9,2.9l9.3-9.3c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-10,10C14.4,13.1,14.1,13.2,13.9,13.2z"/></svg>',
        'view' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="33.7px" height="19.3px" viewBox="0 0 33.7 19.3"><path fill="currentColor" d="M16.8,19.3c-9.1,0-16.3-8.7-16.6-9c-0.3-0.4-0.3-0.9,0-1.3c0.3-0.4,7.5-9,16.6-9s16.3,8.7,16.6,9c0.3,0.4,0.3,0.9,0,1.3C33.2,10.7,26,19.3,16.8,19.3z M2.3,9.7c1.8,1.9,7.7,7.7,14.5,7.7c6.8,0,12.7-5.7,14.5-7.7C29.6,7.7,23.7,2,16.8,2C10,2,4.1,7.7,2.3,9.7z"/><path fill="currentColor" d="M16.8,15.3c-3.1,0-5.6-2.5-5.6-5.6c0-3.1,2.5-5.6,5.6-5.6s5.6,2.5,5.6,5.6C22.5,12.8,20,15.3,16.8,15.3zM16.8,6c-2,0-3.6,1.6-3.6,3.6s1.6,3.6,3.6,3.6s3.6-1.6,3.6-3.6S18.9,6,16.8,6z"/></svg>',
        'logout' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="17.9px" viewBox="0 0 18 17.9"><path fill="currentColor" d="M9,17.9c-5,0-9-4-9-9C0,6,1.4,3.2,3.9,1.5c0.5-0.3,1.1-0.2,1.4,0.2C5.6,2.2,5.5,2.8,5,3.1C3.1,4.5,2,6.6,2,8.9
        c0,3.9,3.1,7,7,7s7-3.2,7-7c0-2.3-1.1-4.4-3-5.8c-0.5-0.3-0.6-0.9-0.2-1.4c0.3-0.5,0.9-0.6,1.4-0.2C16.6,3.2,18,6,18,8.9
        C18,13.9,14,17.9,9,17.9z"/><path fill="currentColor" d="M9,10c-0.6,0-1-0.4-1-1V1c0-0.6,0.4-1,1-1s1,0.4,1,1v8C10,9.6,9.6,10,9,10z"/></svg>',
        'kebab' => '<svg xmlns="http://www.w3.org/2000/svg"><path d="M4 2C4 3.10457 3.10457 4 2 4C0.89543 4 0 3.10457 0 2C0 0.89543 0.89543 0 2 0C3.10457 0 4 0.89543 4 2Z" fill="currentColor"/><path d="M11 2C11 3.10457 10.1046 4 9 4C7.89543 4 7 3.10457 7 2C7 0.89543 7.89543 0 9 0C10.1046 0 11 0.89543 11 2Z" fill="currentColor"/><path d="M18 2C18 3.10457 17.1046 4 16 4C14.8954 4 14 3.10457 14 2C14 0.89543 14.8954 0 16 0C17.1046 0 18 0.89543 18 2Z" fill="currentColor"/></svg>'
    );

    /**
     * Allow Extensions to feed additional icons to $svgicons
     *
     * @since Symphony 2.7.0
     * @param  string $name
     * @param  string $vsg
     */
    public static function registerSVGIcon($name, $svg)
    {
        General::ensureType(array(
            'name' => array('var' => $name, 'type' => 'string'),
            'svg' => array('var' => $svg, 'type' => 'string'),
        ));
        static::$svgicons[$name] = $svg;
    }

     /**
      * Generates a SVG icon
      *
      * @since Symphony 2.7.0
      * @param string $icon
      *  Icon to output
      * @return XMLElement
      */
    public static function SVGIcon($name = '')
    {
        General::ensureType(array(
            'name' => array('var' => $name, 'type' => 'string'),
        ));
        if (!$name || !isset(static::$svgicons[$name])) {
            return '';
        }

        return static::$svgicons[$name];
    }

    /**
     * Generates a XMLElement representation of `<input>`
     *
     * @param string $icon
     *  Icon to output
     * @param string $content
     *  Content to wrap in the container
     * @return XMLElement
     */
    public static function SVGIconContainer($icon, XMLElement $content, array $attributes = null, $wrapped = false)
    {
        General::ensureType(array(
            'icon' => array('var' => $icon, 'type' => 'string'),
        ));

        $obj = new XMLElement('div', Widget::SVGIcon($icon));
        $obj->setAttribute('class', 'svg-icon-container');
        if (!empty($attributes)) {
            $obj->setAttributeArray($attributes);
        }
        if ($wrapped) {
            $obj->appendChild('<span><span>' . $content . '</span></span>');
        } else {
            $obj->appendChild($content);
        }


        return $obj;
    }
}
