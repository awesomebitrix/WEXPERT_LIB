<?
$cfg['settings'] = array(				// �������� ��������� �����
	'js_validators' => true,			// ����, ������������ �� ����� � ������� js
	'js_error_list' => true,			// ����, �������� ������ js-��������� � ������ ������ �����
	'js_error_tooltips' => true,		// ����, �������� ������ js-��������� � ���� ��������
);
// ��� ���� ����� ��������� �� ��������� ����� jQuery

$cfg['method'] = 'POST';				// ����� �������� �����
$cfg['enctype'] = 'multipart/form-data';// ������ ����������� ������ ����� ��� �� �������� �� ������
//$cfg['action'] = '';					// ����������, � �������� ���������� ������ ����� ��� �� �������� �� ������
$cfg['name'] = 'form';					// ��� �����

$cfg['f']['first_name'] = array(
	'label'=>'��� ��������',
	'class'=>'inputbox',
	'validate'=>array(CFValidators::filled),
);
$cfg['f']['last_name'] = array(
	'label'=>'�������',
	'class'=>'inputbox',
	'validate'=>array(CFValidators::filled),
);
$cfg['f']['organization'] = array(
	'label'=>'�����������',
	'class'=>'inputbox',
	'validate'=>array(CFValidators::filled),
);
$cfg['f']['phone'] = array(
	'label'=>'�������',
	'class'=>'inputbox',
	'validate'=>array(CFValidators::phone, CFValidators::filled),
);
$cfg['f']['fax'] = array(
	'label'=>'����',
	'class'=>'inputbox',
);
$cfg['f']['email'] = array(
	'label'=>'E-�ail',
	'class'=>'inputbox',
	'validate'=>array(CFValidators::mail),
);
$cfg['f']['eb_posit'] = array(
	'label'=>'���������',
	'class'=>'inputbox',
);
$cfg['f']['eb_sphere'] = array(
	'type' => 'select',
	'label'=>'����� ������������',
	'class'=>'inputbox',
	'id'=>'eb_sphere',
	'options' => array(
		array('','�������'),
		array('������������','������������', 'selected'),
		array('�����������','�����������'),
		array('�������������� ����������','�������������� ����������'),
		array('������','������'),
	),
	'validate'=>array(CFValidators::filled),
);
$cfg['f']['eb_programs'] = array(
	'type' => 'textarea',
	'label'=>'����������� ��������, ������������ � ��������� �����',
	'class'=>'inputbox',
);
$cfg['f']['eb_variant'] = array(
	'type' => 'select',
	'label'=>'������� �������',
	'class'=>'inputbox',
	'options' => array(
		array('','�������'),
		array('�����������','�����������', 'selected'),
		array('������ 1-� ����','������ 1-� ����'),
		array('������ 2-� ����','������ 2-� ����'),
		array('������ ��������','������ ��������'),
	),
	'validate'=>array(CFValidators::filled),
);
$cfg['f']['eb_city'] = array(
	'type' => 'select',
	'label'=>'����� �������',
	'class'=>'inputbox',
	'id'=>'eb_city',
	'options' => array(
		array('','�������', 'selected'),
		array('���������','���������'),
		array('������-��-����','������-��-����'),
		array('������������','������������'),
		array('������','������'),
		array('���������','���������'),
		array('�������','�������'),
		array('����������','����������'),
		array('������','������'),
		array('������','������'),
	),
	'validate'=>array(CFValidators::filled),
);
$cfg['f']['comment'] = array(
	'type' => 'textarea',
	'label'=>'�����������',
	'class'=>'inputbox',
);
$cfg['f']['btnSubmit'] = array(
	'type' => 'submit',
	'value'=>'������������� �����������',
	'class'=>'button',
);
/*





// ������ - ��������� ����� �����
$cfg['f']['name'] = array(				// ���� [name="name"]
	'label'=>'���',						// ���(label) ����
	'value'=>'��������',				// �������� �� ���������
	'id'=>'ID',							// id
	'attr'=>'attr="val"',				// �������������� ���������
	'validate'=>array(CFValidators::filled, CFValidators::number),	// ����������, ����� �������� ��� ������� ��� ��������
);
$cfg['f']['send'] = array(				// ���� [name="send"]
	'type'=>'submit',					// ��� ������, �� ��������� "text"
	'value'=>'���������',				// ��������
	'id'=>'ID',
	'class'=>'CLASS',					// class
	'attr'=>'attr="val"',
);
$cfg['f']['country'] = array(			// ���� [name="country"]
	'type'=>'select',					// ��� select
	'label'=>'������',					// ���(label) ����
	'id'=>'cn',
	'class'=>'CLASS',
	'multiple'=>'multiple',				// ������������� ����, ������ ��� select - ��� ����������� �������������� ������
	'attr'=>'',
	'groups'=>array(					// ������ ����� <optgroup>
		'fst'=>'������',				// �������������� "��� ������" => "��������"
		'scnd'=>'������'
	),
	'options'=>array(					// ����� <option>
		array(
			'rf',						// ��������
			'������',					// ��������
			'',							// ��������� (�������� selected)
			'fst'						// ��� ������
		),
		array(							// � ����� � ��� �������
			'value'=>'md',				// ��������
			'name'=>'�������',			// ��������
			'attr'=>'',					// ��������� (�������� selected)
			'group'=>'fst'				// ��� ������
		),
		array(
			'kz',
			'���������',
			'',
			'scnd'
		)
	),
	'value'=>'md',						// �������� �� ���������
	'validate'=>CFValidators::filled,	// ���������
);
$cfg['f']['img'] = array(				// ���� [name="img"]
	'type'=>'file',						// ��� file
	'label'=>'��������',
	'id'=>'imgi',
	'class'=>'CLASSiKo',
	'multiple'=>'multiple',				// �������������, �� ����� �������� ���� ��������� ��� ������, � ��������� ������ ��������
	'attr'=>'',
	'validate'=>array(CFValidators::filesize => array('1Mb', '100Mb')),		// ��������� (����������� ������ 1Mb, ������������ 100Mb), ���� ������� 1 �������� - �� ����� ������������ ��������
);
$cfg['f']['mail'] = array(				// ���� [name="mail"]
	'label'=>'E-mail',
	'id'=>'ID',
	'class'=>'CLASS',
	'attr'=>'attr="val"',
	'validate'=>array(CFValidators::filled, CFValidators::mail),	// ���������� (��������� � ������������ ��������� ������)
);*/

// � ����� ������� �������� ������
global $MESS;
$MESS['CFB_file'] = '����';
$MESS['CFB_no_cfg'] = '�� ������ ������ ������������';
$MESS['CFB_no_data'] = '��� ������ �����';
$MESS['CFB_error_js_validators'] = '������: �� ��������� ������ JS ����������.';
$MESS['CFB_upper_size'] = '�������� ����������� ���������� ������';
$MESS['CFB_upper_max_f_size'] = '�������� �������� MAX_FILE_SIZE';
$MESS['CFB_was_giving_partly'] = '��� ������� ������ ��������';
$MESS['CFB_was_not_dnld'] = '�� ��� ��������';
$MESS['CFB_fld_empty'] = '���� "#label#" �� ���������';
$MESS['CFB_fld_mail'] = '���� "#label#" �� ��������� ���� ��������� �����������';
$MESS['CFB_fld_phone'] = '���� "#label#" �� �������� ���������';
$MESS['CFB_fld_number'] = '���� "#label#" �� �������� ������';
$MESS['CFB_fld_file_max_sz'] = '���� "#label#" ��������� ����������� ���������� ������';
$MESS['CFB_fld_file_min_sz'] = '���� "#label#" ������ ���������� ����������� �������';
?>
