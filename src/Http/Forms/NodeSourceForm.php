<?php

namespace Reactor\Hierarchy\Http\Forms;


use Kris\LaravelFormBuilder\Form;

class NodeSourceForm extends Form {

    public function buildForm()
    {
        $this->add('title', 'text', [
            'rules' => 'required|max:255|unique:node_sources,title',
            'attr' => ['autocomplete' => 'off','id' => 'title']
        ]);

        $this->add('node_name', 'slug', [
            'rules' => 'max:255|alpha_dash|unique:node_sources,node_name',
            'help_block' => ['text' => trans('hints.slug')],
            'attr' => ['autocomplete' => 'off','id' => 'node_name']
        ]);
    }

}
