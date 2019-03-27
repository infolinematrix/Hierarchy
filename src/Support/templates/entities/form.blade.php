<?php echo '<?php'; ?>

// WARNING! THIS IS A GENERATED FILE, PLEASE DO NOT EDIT!

namespace gen\Forms;


use Kris\LaravelFormBuilder\Form;

class {{ $name }} extends Form {

    /**
     * Form options
     */
    protected $formOptions = [
        'method' => 'PUT'
    ];

    public function buildForm()
    {
        $this->compose('Reactor\Hierarchy\Http\Forms\NodeSourceForm');
        @foreach($fields as $field)
        @if($field->isVisible())
        $this->add('{{ $field->name }}', '{{ $field->type }}', [
            'label' => '{{ $field->label }}',
            'help_block' => ['text' => '{{ $field->description }}'],

            @unless(empty($field->rules))
            'rules' => {!! $field->rules !!},
            @endunless

            @unless(empty($field->default_value))
            'default_value' => {!! $field->default_value !!},
            @endunless

            {!! $field->options !!}

        ]);
        @endif
        @endforeach
    }

}