<?php
global $form_count;
$form_count = rand_id();

definition([
        'F2_TITLE'	=> 'Заголовок группы полей',
        'F2_HIDDEN'	=> 'Скрытый элемент',
        'F2_DESC'	=> 'Описание группы полей',
        'F2_CODE'	=> 'Вставка кода',
        'F2_INPUT'	=> 'Текстовое поле',
        'F2_NUMBER'	=> 'Цифровое поле',
        'F2_PASS'	=> 'Пароль',
        'F2_PHONE'	=> 'Телефон (с проверкой)',
        'F2_EMAIL'	=> 'Email (с проверкой)',
        'F2_AREA'	=> 'Текстовый блок',
        'F2_SELECT'	=> 'Выпадающее меню',
        'F2_AUTO'	=> 'Поисковое поле',
        'F2_DATE'	=> 'Дата',
        'F2_TIME'	=> 'Время (отдельно)',
        'F2_SET'	=> 'Установки (галочки)',
        'F2_UPGAL'	=> 'Изображения (галлерея)',

        'F2_STRIPNAME'	=> 'TITLE,DESC',
        'F2_STRIPLABEL'	=> 'TITLE,DESC',
        'F2_STRIPVALUE'	=> 'DESC,UPGAL,AUTO',
        'F2_LISTSPLIT'	=> 'SELECT,SET',
        'F2_STRIPSELECT'=> 'SELECT',

        'F2_LABEL'		=> "надпись",
        'F2_NAME'		=> "переменная",
        'F2_VALUE'		=> "значение",
        'F2_CHANGE_TITLE'	=> "Внесите изменения в данные<br/><b class='green'>[KIND]</b>&nbsp;<b class='blue'>[VALUE]</b>",
        'F2_EDITOR_SET'		=> 'установки',
        'F2_ENABLE_EDITOR'	=> 'редактор вкл',
        'F2_ENABLE_COMPACT'	=> 'компактный вид',
        'F2_ENABLE_REQUIRED'	=> 'обязательное',
        'F2_MIN'		=> 'минимальное значение',
        'F2_MAX'		=> 'максимальное значние',
        'F2_MULTI'		=> 'множитель (для чисел)',

        'USER_LOGIN'	=> 'Логин',
        'USER_PASSWORD'	=> 'Пароль',
        ]);

global $form2_defaults;
class itForm2
{
    public $ed_rec;
    public $data;
    public $rec_id;
    public $table_name;
    public $action;
    public $method;
    public $reCaptcha;
    public $form_id;
    public $element_id;
    public $title_xml;
    public $ed_xml;
    public $buttons_xml;
    public $hiddens_xml;
    public $state;
    public $edclass;
    public $debug;
    public $error;
    public $class;
    public $field;
    public $column;
    public $accepted;
    public $code;
    public $fields_xml;

    public function __construct($options=NULL)
    {
        global $form_count;
        $form_count ++;
        $this->table_name = isset($options['table_name']) ? $options['table_name'] : DEFAULT_FORM_TABLE;
        $this->init($options);
        }

    public static function normalize_field_kind($kind, $default='INPUT')
        {
        global $form2_defaults;

        $default = isset($form2_defaults[$default]) ? $default : 'INPUT';
        $kind = strtoupper((string) ready_val($kind, $default));

        return isset($form2_defaults[$kind]) ? $kind : $default;
        }

    protected static function requestValue($key, $default=NULL)
        {
        return (isset($_REQUEST) AND is_array($_REQUEST) AND array_key_exists($key, $_REQUEST))
            ? $_REQUEST[$key]
            : $default;
        }

    protected static function requestText($key, $default='')
        {
        $value = self::requestValue($key, $default);
        return is_array($value) ? $default : (string) $value;
        }

    protected static function sessionArray($key)
        {
        if (!isset($_SESSION) OR !is_array($_SESSION))
            {
            return [];
            }

        return (isset($_SESSION[$key]) AND is_array($_SESSION[$key]))
            ? $_SESSION[$key]
            : [];
        }

    protected static function fieldRowValue($row, $key, $default=NULL)
        {
        return (is_array($row) AND array_key_exists($key, $row))
            ? $row[$key]
            : $default;
        }

    protected static function normalizedFieldRow($row, $fallback_key=0)
        {
        $row = is_array($row) ? $row : [];
        $row['kind'] = self::normalize_field_kind(self::fieldRowValue($row, 'kind', 'INPUT'));

        if (!isset($row['name']) OR trim((string) $row['name'])==='')
            {
            $row['name'] = 'field_'.$fallback_key;
            }

        if (!isset($row['element']) OR trim((string) $row['element'])==='')
            {
            $row['element'] = $row['name'];
            }

        if (!isset($row['array']) OR !is_array($row['array']))
            {
            $row['array'] = [];
            }

        return $row;
        }

    public function index_name($kind)
        {
        $kind = self::normalize_field_kind($kind);
        $index = 1;
        if ($kind!='HIDDEN')
            {
            if (is_array($this->fields_xml))
                foreach($this->fields_xml as $row)
                    {
                    if (isset($row['kind']) && ($row['kind'] == $kind))
                        {
                        $index++;
                        }
                    }
            } else $index = (is_array($this->hiddens_xml) ? count($this->hiddens_xml) : 0) + 1;
        return strtolower($kind).$index;
        }

    public function init($options=NULL)
        {
        global $form_count;
        $form_count ++;

        $options = is_array($options) ? $options : [];
        $this->error = false;

        if  (isset($options['rec_id']) AND is_array($this->data = itMySQL::_get_rec_from_db($this->table_name, $this->rec_id = $options['rec_id']))) {
            $options = array_replace($this->data, $options);
            }

        $this->title_xml	= ready_val($options['title_xml']);
        $this->ed_xml		= ready_val($options['ed_xml']);
        $this->action		= ready_val($options['action'], DEFAULT_FORM_ACTION);
        $this->method		= ready_val($options['method'], DEFAULT_FORM_METHOD);

        $this->form_id =
            isset($options['form_id'])
                ? $options['form_id']
                : ( (intval($this->rec_id)>1)
                    ? "form-{$this->table_name}-{$this->rec_id}"
                    : "form-{$form_count}");

        $this->element_id =
            isset($options['element_id'])
                ? $options['element_id']
                : $this->form_id;

        $this->fields_xml 	= ready_val($options['fields_xml']);

        $this->buttons_xml 	= ready_val($options['buttons_xml']);
        $this->hiddens_xml 	= ready_val($options['hiddens_xml']);

        $this->state 	= ready_val($options['state'], DEFAULT_FORMSTATE);
        $this->class 	= ready_val($options['class']);
        $this->debug 	= ready_val($options['debug']);

        $this->field	= ready_val($options['field'], DEFAULT_F2_EDITOR_FIELD);
        $this->column	= ready_val($options['column'], DEFAULT_F2_EDITOR_COLUMN);

        $this->data		= [];

        $this->reCaptcha = (get_const('USE_CAPTCHA')==true)
            ? ready_val($options['reCaptcha'], DEFAULT_FORM_CAPTCHA)
            : false;
        }

    public function md5hash()
        {
        $hash_arr = NULL;

        if (is_array($this->fields_xml)) {
            $hash_arr['field'] = array_column($this->fields_xml, 'kind');
            }

        if (is_array($this->buttons_xml)) {
            $hash_arr['buttons'] = array_column($this->buttons_xml, 'kind');
            }

        if (is_array($this->hiddens_xml)) {
            $hash_arr['hiddens'] = array_column($this->hiddens_xml, 'kind');
            }

        return md5(serialize($hash_arr));
        }

    public function action($action=NULL)
        {
        return is_null($action) ? $this->action : ($this->action = $action);
        }

    public function store()
        {
        @decode_json_values($this->fields_xml);

        $reCaptcha_value = ($this->reCaptcha===true OR $this->reCaptcha===1 OR $this->reCaptcha==='1')
            ? 1
            : 0;

        $values_arr = [
            'action' 	=> $this->action,
            'method'	=> $this->method,
            'reCaptcha'	=> $reCaptcha_value,
            'form_id'	=> $this->form_id,
            'element_id'	=> $this->element_id,
            'title_xml'	=> $this->title_xml,
            'ed_xml'	=> $this->ed_xml,
            'fields_xml'	=> $this->fields_xml,
            'buttons_xml'	=> $this->buttons_xml,
            'hiddens_xml'	=> $this->hiddens_xml,
            ];

        if (intval($this->rec_id))
            {
            itMySQL::_update_db_rec($this->table_name, $this->rec_id, $values_arr);
            } else 	{
                $this->init([
                    'rec_id' => itMySQL::_insert_rec($this->table_name, $values_arr),
                    ]);
                }
        }

    protected function getCollection($property)
    {
        return is_array($this->{$property}) ? $this->{$property} : [];
    }

    protected function sortCollection($property)
    {
        $collection = $this->getCollection($property);
        $this->{$property} = array_values($collection);

        return true;
    }

    protected function insertCollectionItem($property, $ed_key, $item)
    {
        $collection = $this->getCollection($property);
        $position = is_numeric($ed_key) ? ((int) $ed_key + 1) : count($collection);

        array_splice($collection, $position, 0, [$item]);

        $this->{$property} = $collection;
        $this->sortCollection($property);
    }

    protected function moveCollectionItem($property, $ed_key, $direction)
    {
        $collection = $this->getCollection($property);
        $source = (int) $ed_key;
        $target = $source + (int) $direction;

        if (!isset($collection[$source]) || !isset($collection[$target])) {
            return $ed_key;
        }

        $tmp = $collection[$source];
        $collection[$source] = $collection[$target];
        $collection[$target] = $tmp;

        $this->{$property} = $collection;
        $this->sortCollection($property);

        return $target;
    }

    protected function buildInsertedField($kind, $data)
    {
        global $form2_defaults;

        $kind = self::normalize_field_kind($kind);
        $default = isset($form2_defaults[$kind]['default']) && is_array($form2_defaults[$kind]['default'])
            ? $form2_defaults[$kind]['default']
            : [];
        $field = $default;
        $field['kind'] = $kind;

        if (in_array($kind, ['SELECT', 'SET'], true)) {
            $field['array'] = isset($field['array']) && is_array($field['array']) ? $field['array'] : [];
            $field['titles'] = isset($field['titles']) ? $field['titles'] : 'title';
            $field['values'] = isset($field['values']) ? $field['values'] : 'value';
        }

        if (!in_array($kind, ['TITLE', 'DESC', 'CODE'], true)) {
            $field['name'] = ready_val($data['name'], $this->index_name($kind));
        }

        return $field;
    }

    protected function buildInsertedButton($kind, $data)
    {
        $button = ['kind' => $kind];

        if (is_array($data)) {
            $button = array_merge($button, array_skip($data, ['ed_key', 'kind']));
        }

        return $button;
    }

    public function insert_field($data=NULL)
    {
        $data = is_array($data) ? $data : [];
        $ed_key = ready_val($data['ed_key']);
        $kind = self::normalize_field_kind(ready_val($data['kind'], 'INPUT'));

        $this->insertCollectionItem('fields_xml', $ed_key, $this->buildInsertedField($kind, $data));
        }

    static function _insert_field($data=NULL)
        {
        $o_form = new itForm2($data);
        $o_form->insert_field($data);
        $o_form->store();
        unset($o_form);
        }

    public function sort_fields()
        {
        return $this->sortCollection('fields_xml');
        }

    public function up_field($ed_key)
        {
        return $this->moveCollectionItem('fields_xml', $ed_key, -1);
        }

    static function _up_field($data)
        {
        $o_form2 = new itForm2([
            'table_name'	=> $data['table_name'],
            'rec_id'	=> $data['rec_id'],
            ]);
        $result = $o_form2->up_field($data['ed_key']);
        $o_form2->store();
        unset($o_form2);
        return $result;
        }

    public function down_field($ed_key)
        {
        return $this->moveCollectionItem('fields_xml', $ed_key, 1);
        }

    static function _down_field($data)
        {
        $o_form2 = new itForm2([
            'table_name'	=> $data['table_name'],
            'rec_id'	=> $data['rec_id'],
            ]);
        $result = $o_form2->down_field($data['ed_key']);
        $o_form2->store();
        unset($o_form2);
        return $result;
        }

    public function insert_button($data=NULL)
        {
        $data = is_array($data) ? $data : [];
        $ed_key = ready_val($data['ed_key']);
        $kind = self::normalize_field_kind(ready_val($data['kind'], 'INPUT'));

        $this->insertCollectionItem('buttons_xml', $ed_key, $this->buildInsertedButton($kind, $data));
        }

    public function sort_buttons()
        {
        return $this->sortCollection('buttons_xml');
        }

    public function up_button($ed_key)
        {
        return $this->moveCollectionItem('buttons_xml', $ed_key, -1);
        }

    public function down_button($ed_key)
        {
        return $this->moveCollectionItem('buttons_xml', $ed_key, 1);
        }

    public function _correct_field_data(&$data)
        {
        $data['name'] = isset($data['name']) ? $data['name'] : $this->index_name($data['kind']);
        if (is_null(ready_val($data['element_id'])))
            {
            unset($data['element_id']);
            }
        }

    protected function addFieldFromDefaults($kind, $args, $default_kind=NULL, $extra=[], $with_more=true, $unset_empty_options=false)
        {
        global $form2_defaults;
        _arguments($args, $form2_defaults[ready_val($default_kind, $kind)]['default'], $data);

        if ($unset_empty_options AND is_null(ready_val($data['options'])))
            {
            unset($data['options']);
            }

        $data['kind'] = $kind;
        foreach ($extra as $key=>$value)
            {
            $data[$key] = $value;
            }

        $this->_correct_field_data($data);

        if ($with_more)
            {
            $data['more'] = isset($data['more']) ? $data['more'] : DEFAULT_MORE_STATE;
            }

        $this->fields_xml[] = $data;
        }

    public function add_title(...$args)
        {
        global $form2_defaults;
        _arguments($args, $form2_defaults['TITLE']['default'], $data);

        $data['kind'] = 'TITLE';
        $this->fields_xml[] = $data;
        }

    public function add_hidden(...$args)
        {
        global $form2_defaults;
        _arguments($args, $form2_defaults['HIDDEN']['default'], $data);

        $data['kind'] = 'HIDDEN';
        $data['element'] = isset($data['element_id']) ? $data['element_id'] : $this->form_id."-{$data['name']}";
        $this->_correct_field_data($data);
        $this->hiddens_xml[] = $data;
        }

    public function add_data($arr=NULL)
        {
        if (is_array($arr))
            {
            foreach($arr as $key=>$value) {
                    $this->data[$key] = $value;
                    }
            }
        }

    public function add_description(...$args)
        {
        return $this->addFieldFromDefaults('DESC', $args);
        }

    public function add_field(...$args)
        {
        return $this->addFieldFromDefaults('CODE', $args, NULL, [], false);
        }

    public function add_input(...$args)
        {
        return $this->addFieldFromDefaults('INPUT', $args);
        }

    public function add_phone(...$args)
        {
        return $this->addFieldFromDefaults('PHONE', $args, NULL, ['type' => 'phone']);
        }

    public function add_email(...$args)
        {
        return $this->addFieldFromDefaults('EMAIL', $args, NULL, ['type' => 'email']);
        }

    public function add_number(...$args)
        {
        return $this->addFieldFromDefaults('NUMBER', $args, 'PHONE', ['type' => 'number']);
        }
    public function add_password(...$args)
        {
        return $this->addFieldFromDefaults('PASS', $args);
        }

    public function add_area(...$args)
        {
        return $this->addFieldFromDefaults('AREA', $args);
        }

    public function add_selector(...$args)
        {
        return $this->addFieldFromDefaults('SELECT', $args, 'SELECT', [], true, true);
        }

    public function add_auto(...$args)
        {
        return $this->addFieldFromDefaults('AUTO', $args);
        }

    public function add_date(...$args)
        {
        return $this->addFieldFromDefaults('DATE', $args);
        }

    public function add_time(...$args)
        {
        return $this->addFieldFromDefaults('TIME', $args);
        }

    public function add_set(...$args)
        {
        return $this->addFieldFromDefaults('SET', $args);
        }

    public function add_upgal(...$args)
        {
        return $this->addFieldFromDefaults('UPGAL', $args);
        }
    public function add_button(...$args)
        {
        global $form2_defaults;
        _arguments($args, [
            'title' 	=> 'Ok',
            'type'		=> DEFAULT_BUTTON_TYPE,
            'options'	=> NULL,
            'color'		=> NULL,
            'element_id'	=> NULL,
            ], $data);

        $this->buttons_xml[] = $data;
        }

    static function _container_id($data)
        {
        return "form-{$data['rec_id']}-{$data['table_name']}-container";
        }

    public function container($options=NULL)
        {
        global $_USER;

        $this->state = isset($options['state']) ? $options['state'] : DEFAULT_FORMSTATE;
        $container_id = self::_container_id((array) $this);

        $data = itEditor::event_data([
            'table_name'	=> $this->table_name,
            'rec_id'	=> $this->rec_id,
            'container_id'	=> $container_id,
            'state'		=> $this->state,
            'class'		=> $this->class,
            'debug'		=> $this->debug,
            ]);

        return $_USER->is_logged(itEditor::moderators())
            ?
                TAB."<div class='ed_form{$this->edclass}' id='{$container_id}' rel='{$data}'>".
                ( ($this->state=='view')
                    ? $this->_view()
                    : $this->_edit() ).
                get_f2_async_event((array) $this).
                TAB."</div>".
                ""
            : 	TAB."<div class='ed_form'>".$this->_view().TAB."</div>";
        }

    public function _view_data()
        {
        $data_tmp = $this->data;
        $data_tmp['f2hash'] = $this->md5hash();
        $element_id = $this->form_id."-data";
        return TAB."<input name='data' id='{$element_id}' type='hidden' value='".itEditor::event_data($data_tmp)."'>";
        }

    public function _view()
        {
        $class_str	= !empty($this->class)	? " class=\"{$this->class}\"" : NULL;
        $captcha_str	= $this->reCaptcha ? " recv3='1'" : NULL;

        $recaptcha = self::sessionArray('v3checked');
        $recaptcha_title = $this->reCaptcha
            ? "<center><small style='opacity:.4;'>protected with reCaptcha".
                (array_key_exists('score', $recaptcha)
                    ? " score: ".ready_value($recaptcha['score'])
                    : NULL )."</small></center>"
            : NULL;

        $f2hash = self::requestValue('f2hash');

        $this->accepted = ($f2hash == $this->md5hash());

        return
            TAB."<div class='f2_form{$class_str}'>".
            TAB."<form id=\"{$this->form_id}\" action=\"{$this->action}\" method=\"{$this->method}\" accept-charset=\"utf-8\"{$class_str}{$captcha_str}>".
            $this->_view_fields().
            $recaptcha_title.
            $this->_view_hiddens().
            $this->_view_data().
            $this->_view_buttons().
            $this->_submit().
            TAB."</form>".
            TAB."</div>".
            "";

        }

    public function _edit()
        {
        $class_str	= !empty($this->class)	? " {$this->class}" : NULL;
        return
            TAB."<div class='f2_form{$class_str}'>".
            $this->_edit_fields().
            $this->_edit_hiddens().
            $this->_view_buttons().
            $this->_submit().
            TAB."</div>".
            "";
        }

    public function _view_hiddens()
        {
        $code = NULL;
        if (is_array($this->hiddens_xml))
        foreach ($this->hiddens_xml as $key=>$row)
            {
            $code .= function_exists($func = "_f2_hidden_view")
                ? $func($row)
                : add_error_message(debug_point("No field data handler for <b>".get_class($this)."</b> using type <b>{$row['kind']}</b>", debug_backtrace()));
            }

        return (!empty($code))
            ? 	TAB."<div class=\"modal_row hidden\">".
                $code.
                TAB."</div>"
            : 	NULL;
        }

    public function _edit_hiddens()
        {
        }

    protected function fieldBaseRow($row, $key, $last_field=NULL)
        {
        $row['table_name'] = $this->table_name;
        $row['form_id'] = $this->form_id;
        $row['rec_id'] = $this->rec_id;
        $row['ed_key'] = $key;
        $row['class'] = $this->class;

        if (!is_null($last_field))
            {
            $row['last_field'] = $last_field;
            }

        return $row;
        }

    protected function fieldLayoutState($row)
        {
        $row = self::normalizedFieldRow($row);
        return [
            'compact' => (ready_value(self::fieldRowValue($row, 'compact'), false) ? ' compact' : ''),
            'full' => (ready_value(self::fieldRowValue($row, 'more')) ? NULL : ' full'),
            'special' => ($row['kind']=='TITLE' ? ' title' : ($row['kind']=='DESC' ? ' description' : NULL)),
            'class' => (!empty($this->class) ? " {$this->class}" : NULL),
            ];
        }

    protected function fieldMoreEditorZone($key, $compact, $edit=false)
        {
        $o_ed = new itEditor([
            'table_name' => $this->table_name,
            'rec_id' => $this->rec_id,
            'column' => $this->column,
            'field' => $this->field,
            'root' => $key,
            ]);

        $result =
            TAB."<div class=\"more boxed{$compact}\">".
            ($edit ? $o_ed->container() : $o_ed->_view()).
            TAB."</div>";
        unset($o_ed);
        return $result;
        }

    protected function isSetFieldOptionChecked($row)
        {
        $row = self::normalizedFieldRow($row);
        if ($row['kind']!='SET')
            {
            return false;
            }

        foreach($row['array'] as $option_row)
            {
            $value = is_array($option_row) ? self::fieldRowValue($option_row, 'value') : NULL;
            if (is_null($value))
                {
                continue;
                }

            if (self::requestValue("{$row['name']}_{$value}")=='on')
                    {
                    return true;
                    }
            }

        return false;
        }

    protected function fieldValidationError($row, $options_checked, &$focus_str)
        {
        $focus_str = NULL;
        $row = self::normalizedFieldRow($row);
        $request_value = self::requestValue($row['name']);
        $request_text = is_array($request_value) ? '' : trim((string) $request_value);

        if (!$this->error
            AND $this->accepted
            AND ready_value(self::fieldRowValue($row, 'required'))
            AND !isset($_SESSION['focus'])
            AND (is_null($request_value) OR ($request_text==='')
                OR (($row['kind']=='PHONE') AND !isPhone($request_text))
                OR (($row['kind']=='EMAIL') AND !isEmail($request_text)))
            AND !$options_checked)
            {
            $_SESSION['focus']['element'] = ($row['kind']=='AUTO') ? "field-".$row['element'] : $row['element'];
            $focus_str = " focus";
            $this->error = true;
            return
                "<div id='error-{$row['element']}' class='modal_row error_msg f2_row focus'>".get_const('NEED_CORRECT')."</div>".
                minify_js(
                    "<script>
                    $(document).ready(function(){
                    $('#error-{$row['element']}').ScrollTo({duration:800, offsetTop:64, callback:function(){}});
                    });
                    </script>");
            }

        return NULL;
        }

    protected function debugFieldRow($data)
        {
        return
            "<div class='f2_row 'style=\"".F2_DEBUGSTYLE."\">".
            print_rr($data).
            "</div>";
        }

    public function _view_fields()
        {
        $code = NULL;

        if (is_array($this->fields_xml))
        foreach ($this->fields_xml as $key=>$row)
            {
            $row = $this->fieldBaseRow(self::normalizedFieldRow($row, $key), $key);
            $layout = $this->fieldLayoutState($row);
            $label_zone = self::_label_zone($row);
            unset($row['class']);

            $editor_zone = ready_val($row['more'])
                ? $this->fieldMoreEditorZone($key, $layout['compact'])
                : NULL;

            $row['no_label'] = ready_val($row['no_label'], true);
            $value_zone = function_exists($func = "_f2_".strtolower($row['kind'])."_view")
                ? $func($row)
                : add_error_message(debug_point("No field data handler for <b>".get_class($this)."</b> using type <b>{$row['kind']}</b>", debug_backtrace()));

            $focus_str = NULL;
            $error_str = $this->fieldValidationError($row, $this->isSetFieldOptionChecked($row), $focus_str);

            if (!isset($row['element']) OR trim((string) $row['element'])==='')
                {
                $row['element'] = isset($row['name']) ? $row['name'] : "field_{$key}";
                }

            $code .= !mempty($label_zone, $editor_zone, $value_zone)
                ? $error_str.
                    TAB."<div id='container-{$row['element']}' class=\"modal_row f2_row{$layout['special']}{$layout['compact']}{$layout['class']}{$focus_str}\">".
                    $label_zone.
                    $editor_zone.
                    TAB."<div class=\"value boxed{$layout['compact']}{$layout['full']}\">".$value_zone.TAB."</div>".
                    TAB."</div>"
                : NULL;
            }

        $this->accepted = !$this->error;
        return $code;
        }

    public function _edit_fields()
        {
        global $form_blocks;
        $code = NULL;

        if (empty($this->fields_xml))
            {
            $this->add_title("Form {$this->rec_id}");
            }

        if (is_array($this->fields_xml))
        foreach ($this->fields_xml as $key=>$row)
            {
            $row = $this->fieldBaseRow($row, $key, count($this->fields_xml)-1);
            $layout = $this->fieldLayoutState($row);
            $label_zone = self::_label_zone_edit($row);
            unset($row['class']);

            $editor_zone = ready_val($row['more'])
                ? $this->fieldMoreEditorZone($key, $layout['compact'], true)
                : NULL;

            $row['no_label'] = ready_val($row['no_label'], true);
            $value_zone = function_exists($func = "_f2_".strtolower($row['kind'])."_edit")
                ? $func($row)
                : add_error_message(debug_point("No field EDIT handler for <b>".get_class($this)."</b> using type <b>{$row['kind']}</b>", debug_backtrace()));

            $code .= !mempty($label_zone, $editor_zone, $value_zone)
                ? TAB."<div class=\"modal_row f2_row edit protected{$layout['special']}{$layout['compact']}{$layout['class']}\">".
                    $label_zone.
                    $editor_zone.
                    TAB."<div class=\"value boxed{$layout['compact']}\">".$value_zone.TAB."</div>".
                    TAB."</div>".
                    f2_button_set($row).
                    ($this->debug ? $this->debugFieldRow($row) : NULL)
                : NULL;
            }

        return $code;
        }

    static function _label_view($row)
        {
        return
            isset($row['label'])
                ? ( is_array($row['label'])
                    ? get_field_by_lang($row['label'], CMS_LANG, 'NO_TITLE')
                    : get_const($row['label']) )
                : ( isset($row['title_xml'])
                    ? get_field_by_lang($row['title_xml'])
                    : NULL );
        }

    static function _label_edit($row)
        {
        return self::_label_view($row);
        }

    static function _placeholder_view($row)
        {
        return
            isset($row['placeholder'])
                ? ( is_array($row['placeholder'])
                    ? get_field_by_lang($row['placeholder'], CMS_LANG, 'NO_TITLE')
                    : get_const($row['placeholder']) )
                : NULL;
        }

    static function _label_zone($row)
        {
        $row = self::normalizedFieldRow($row);
        $compact 	= ready_value(self::fieldRowValue($row, 'compact'), false) ? ' compact' : '';
        $class_str	=
            (ready_value(self::fieldRowValue($row, 'more')) ? NULL : " full").
            (!empty($row['class'])	? " {$row['class']}" : NULL);

        return (ready_value(self::fieldRowValue($row, 'no_label')) OR is_null($title_str = self::_label_view($row)))
                ? 	NULL
                :	TAB."<div class=\"label{$compact}{$class_str}\">".
                    $title_str.
                    ( ready_value(self::fieldRowValue($row, 'required')) ? "&nbsp;<span style='font-size:1.2em;'>*</span>" : NULL).
                    TAB."</div>";
        }

    static function _label_zone_edit($row)
        {
        $row = self::normalizedFieldRow($row);
        $compact 	= ready_value(self::fieldRowValue($row, 'compact'), false) ? ' compact' : '';
        $class_str	=
            (ready_value(self::fieldRowValue($row, 'more')) ? NULL : " full").
            (!empty($row['class'])	? " {$row['class']}" : NULL);

        return (ready_value(self::fieldRowValue($row, 'no_label')) OR is_null($title_str = self::_label_edit($row)))
                ? 	NULL
                :	TAB."<div class=\"label{$compact}{$class_str}\">".
                    $title_str.
                    ( ready_value(self::fieldRowValue($row, 'required')) ? "&nbsp;<span style='font-size:1.2em;'>*</span>" : NULL).
                    TAB."</div>";
        }

    public function _view_buttons()
        {
        $code = NULL;
        if (is_array($this->buttons_xml))
            {
            $code =
                TAB."<div class='modal_row'>".
                TAB."<div class='buttons_div'>";

            foreach ($this->buttons_xml as $key=>$row)
                {
                $row['table_name']	= $this->table_name;
                $row['form_id'] 	= $this->form_id;
                $row['rec_id']		= $this->rec_id;
                $row['ed_key']		= $key;

                $code .= _f2_button_view($row);
                }
                $code .=
                    TAB."</div>".
                    TAB."</div>";
                }
        return $code;
        }

    public function _submit() { $this->hidden_submit(); }
    public function hidden_submit()
        {
        return TAB."\t<input type=\"submit\" class=\"hidden_submit\" tabindex=\"-1\"/>";
        }

    static function _field_x($options)
        {
        if (!isset($options['ed_key'])) return;

        $o_form2 = new itForm2($options);
        if (isset($o_form2->fields_xml[$options['ed_key']]))
            unset($o_form2->fields_xml[$options['ed_key']]);
        $o_form2->sort_fields();
        $o_form2->store();
        unset($o_form2);
        }

    static function events($url='/', $path=UPLOADS_ROOT)
        {
        return f2_events($url, $path);
        }

    public function form_id()
        {
        return $this->form_id;
        }

    public function compile()
        {
        $this->code = $this->_view();
        }

    public function code()
        {
        return $this->code;
        }

    static function _result_info($options)
        {
        $rows = NULL;
        $empty = isset($options['empty']) ? $options['empty'] : true;

        $table_name = isset($options['table_name']) ? $options['table_name'] : NULL;
        $rec_id = isset($options['rec_id']) ? $options['rec_id'] : NULL;
        if (empty($table_name) OR empty($rec_id))
            {
            return $rows;
            }

        if ($form = itMySQL::_get_rec_from_db($table_name, $rec_id))
            {
            if (isset($form['fields_xml']) AND is_array($form['fields_xml']))
                foreach ($form['fields_xml'] as $row)
                    {
                    $row = self::normalizedFieldRow($row);
                    if ($row['kind']=='TITLE')
                        {
                        $value_title = self::fieldRowValue($row, 'value', []);
                        $str = "<div style='font-size:1.2em; margin-top:16px; font-weight:bold;'>".get_field_by_lang($value_title)."</div>";
                        $rows[] 	= $str;
                        continue;
                        }

                    if (in_array($row['kind'], explode(',', "TITLE,DESC,CODE")) OR is_null(self::requestValue($row['name'])))
                        {
                        continue;
                        }

                    $label_data = self::fieldRowValue($row, 'label', '');
                    $label = is_array($label_data) ? get_field_by_lang($label_data) : get_const($label_data);
                    $request_value = self::requestValue($row['name']);
                    $request_text = is_array($request_value) ? '' : (string) $request_value;
                    $value = NULL;

                    switch ($row['kind'])
                        {
                        case 'SELECT' :
                        case 'SET' : {
                            foreach ($row['array'] as $key=>$line)
                                {
                                if (!is_array($line))
                                    {
                                    continue;
                                    }

                                if (self::fieldRowValue($line, 'value')==$request_value)
                                    {
                                    $value = get_field_by_lang(self::fieldRowValue($line, 'title', []));
                                    }
                                }
                            break;
                            }
                        case 'UPGAL' : {
                            $images = explode("|", $request_text);
                            $ima_res = NULL;
                            foreach($images as $image_row)
                                {
                                if (!empty(trim($image_row)))
                                    {
                                    $ima_res[] =
                                        "<a href=\"".UPLOADS_HTTP.$image_row."\"' target='_blank'>".
                                        get_thumbnail($image_row, 'GAL_MAIL')."</a><br/>";
                                    }
                                }
                            $value = !is_null($ima_res)
                                ? "[GAL]".implode('', $ima_res)."[/GAL]"
                                : "-";
                            break;
                            }
                        case 'AREA' : {
                            $value = "<div style='padding:.8em; border:1px dashed black;'>{$request_text}</div>";
                            break;
                            }
                        default : {
                            $value = $request_text;
                            break;
                            }
                        }

                    if ($empty OR !empty($request_text))
                        {
                        $rows[] = "<div class='info'><span class='label'>{$label}</span>&nbsp;:&nbsp;{$value}</div>";
                        }
                    }
            }
        return $rows;
        }

    static function _change($options)
        {
        $options = is_array($options) ? $options : [];
        if (!isset($options['ed_key'])) return;

        $key = $options['ed_key'];

        $o_form2 = new itForm2($options);
        if (!is_array($o_form2->fields_xml))
            {
            unset($o_form2);
            return;
            }

        if (isset($o_form2->fields_xml[$key]))
            {
            $options['kind'] = self::normalize_field_kind(ready_val($options['kind'], ready_val($o_form2->fields_xml[$key]['kind'], 'INPUT')));
            if (isset($o_form2->fields_xml[$key]['label']) AND !isset($o_form2->fields_xml[$key]['label'][CMS_LANG]) AND !is_array($o_form2->fields_xml[$key]['label']))
                unset($o_form2->fields_xml[$key]['label']);

            if (isset($options['label']))
                $o_form2->fields_xml[$key]['label'][CMS_LANG] = $options['label'];
            if (isset($options['value']))
                {
                if (in_array($options['kind'], explode(',', 'TITLE')))
                    {
                    if (!is_array($o_form2->fields_xml[$key]['value']))
                        $o_form2->fields_xml[$key]['value'] = NULL;
                    $o_form2->fields_xml[$key]['value'][CMS_LANG] = $options['value'];
                    } else	{
                        $o_form2->fields_xml[$key]['value'] = $options['value'];
                        }
                }

            $o_form2->fields_xml[$key]['more'] = (ready_val($options['editor_more'])=='on');
            $o_form2->fields_xml[$key]['compact'] = (ready_val($options['editor_compact'])=='on');
            $o_form2->fields_xml[$key]['required'] = (ready_val($options['editor_required'])=='on');

            $titles_arr = isset($options['f2_titles']) ? explode("\n", str_replace("\r","",$options['f2_titles'])) : NULL;
            $values_arr = isset($options['f2_values']) ? explode("\n", str_replace("\r","",$options['f2_values'])) : NULL;

            if (is_array($titles_arr))
                {
                if (isset($o_form2->fields_xml[$key]['array']))
                    $o_form2->fields_xml[$key]['array'] = array_values($o_form2->fields_xml[$key]['array']);

                $index=0;
                $res_arr = NULL;

                $options['titles'] = $o_form2->fields_xml[$key]['titles'] = !is_null(ready_val($options['titles'])) ? $options['titles'] : 'title';
                $options['values'] = $o_form2->fields_xml[$key]['values'] = !is_null(ready_val($options['values'])) ? $options['values'] : 'value';

                foreach ($titles_arr as $row)
                    {
                    $res_arr[$index] = [
                        $options['titles'] =>
                            (isset($o_form2->fields_xml[$key]['array'][$index][$options['titles']]) AND is_array($o_form2->fields_xml[$key]['array'][$index][$options['titles']]))
                                ? $o_form2->fields_xml[$key]['array'][$index][$options['titles']]
                                : NULL,
                        $options['values'] =>
                            (isset($o_form2->fields_xml[$key]['array'][$index][$options['values']]) AND is_array($o_form2->fields_xml[$key]['array'][$index][$options['values']]))
                                ? $o_form2->fields_xml[$key]['array'][$index][$options['values']]
                                : NULL,
                            ];

                    $res_arr[$index][$options['titles']][CMS_LANG] = $row;
                    $res_arr[$index][$options['values']] =
                        (isset($values_arr[$index]) AND (trim($values_arr[$index])!=='')) ? $values_arr[$index] : ($index+1);
                    $index++;
                    }
                $o_form2->fields_xml[$key]['array'] = array_values($res_arr);

                switch ($options['kind'])
                    {
                    case 'SET' : {
                        foreach($o_form2->fields_xml[$key]['array'] as $row)
                            {
                            $value = ready_val($row['value']);
                            $root = "value_{$value}";

                            if (ready_val($options[$root])==='on')
                                {
                                $o_form2->fields_xml[$key]['value'][$value] = true;
                                } else	{
                                    if (isset($o_form2->fields_xml[$key]['value'][$value]))
                                        unset($o_form2->fields_xml[$key]['value'][$value]);
                                    }
                            }
                        break;
                        }
                    }
                }
            }

        switch ($options['kind'])
            {
            case 'NUMBER' : {
                $o_form2->fields_xml[$key]['multi'] = ready_val($options['multi'], 1);
                break;
                }
            }

        if (isset($options['name']))
            {
            $o_form2->fields_xml[$key]['name'] = $options['name'];
            }
        $o_form2->store();
        unset($o_form2);
        }

    static function _smart_value($value, $empty=false)
        {
        return (($value_str = (is_array($value)
            ? get_field_by_lang($value, CMS_LANG, ($empty ? NULL : 'NO_DATA'))
            : get_const($value)))!=='')
                ? $value_str
                : NULL;
        }

    static function _reCaptcha()
        {
        if (isset($_SESSION['v3checked'])) {
            return $_SESSION['v3checked'];
            }

        $v3resp = self::requestText('v3resp');
        if ($v3resp==='') {
            return NULL;
            }

        $secret = get_const('RECAPTCHA_SECRET');
        if (empty($secret))
            {
            $_SESSION['v3checked'] = false;
            return $_SESSION['v3checked'];
            }

        $resp_json = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".urlencode($secret)."&response=".urlencode($v3resp));
        $recaptcha = json_decode((string) $resp_json, true);
        $_SESSION['v3checked'] = (!is_array($recaptcha) OR !isset($recaptcha['success'])) ? false : $recaptcha;
        return $_SESSION['v3checked'];
        }

    static function _check_value($options, $name, $default=NULL)
        {
        $options = is_array($options) ? $options : [];
        $option_value = array_key_exists('value', $options) ? $options['value'] : NULL;
        if (is_array($option_value))
            {
            return self::_smart_value($option_value, true);
            }

        $request_value = self::requestValue($name);
        if (!is_null($request_value))
            {
            return $request_value;
            }

        if ((string) $option_value!=='' OR $option_value===0 OR $option_value==='0')
            {
            return $option_value;
            }

        return $default;
        }
    }

function _arguments($params, $defaults_arr=NULL, &$result=NULL)
    {
    $result_arr = NULL;
    if (is_array($params) AND is_array($params[0]))
        {
        $result_arr = $params[0];
        } else	{
            $index = 0;
            foreach($defaults_arr as $key=>$row)
                {
                if (isset($params[$index]) and is_array($params[$index]))
                    {
                    foreach ($params[$index] as $opt_key => $opt_row)
                        {
                        $result_arr[$opt_key] = $opt_row;
                        }
                    } else $result_arr[$key] = (isset($params[$index])) ? $params[$index] : $row;
                $index++;
                }
            }

    if (is_array($defaults_arr))
        {
        foreach($defaults_arr as $key => $row)
            {
            if (!isset($result_arr[$key]))
            $result_arr[$key] = $row;
            }
        }
    return $result = $result_arr;
    }