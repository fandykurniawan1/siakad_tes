<?php

namespace App\Http\Controllers\Backend;

use DB, Datatables;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;

class EmailTemplateController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-email-template-list');

        return view('backend.email.template.index');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-email-template');

        $template = EmailTemplate::FindOrFail($id);

        return view('backend.email.template.edit', compact('template'));
    }

    public function getData()
    {
        checkPermissionTo('view-email-template-list');

        $template = EmailTemplate::query();

        return Datatables::of($template)
                    ->editColumn('type', function($template) {
                        if ($template->type == 'registration')
                            return '<span class="tag tag-primary">Registration</span>';
                        else if ($template->type == 'invitation')
                            return '<span class="tag tag-info">Invitation</span>';
                        else if ($template->type == 'earning')
                            return '<span class="tag tag-success">Earning</span>';
                        else if ($template->type == 'redeem')
                            return '<span class="tag tag-warning">Redeem</span>';
                        else if ($template->type == 'blast')
                            return '<span class="tag tag-danger">Blast</span>';
                        else if ($template->type == 'birthday')
                            return '<span class="tag tag-birthday">Birthday</span>';
                    })
                    ->editColumn('action', function($template) {
                        $edit = '<a href="'. route('backend.email.template.edit', $template->id) .'" class="btn btn-sm btn-icon text-primary tl-tip" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="'. route('backend.email.template.destroy', $template->id) .'" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-email-template') ? $edit : '') . (userCan('delete-email-template') ? $delete : '');
                    })
                    ->rawColumns(['type', 'action'])
                    ->make(true);
    }
}
