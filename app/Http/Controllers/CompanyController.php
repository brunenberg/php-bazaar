<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    function updateInfo(Request $request)
    {
        $validatedData = $request->validate([
            'winkelnaam' => 'required|string|max:255',
            'winkelbeschrijving' => 'nullable|string',
            'slug' => 'nullable',
            'image' => 'nullable|image',
            'achtergrondkleur' => 'nullable|string|max:7',
            'tekstkleur' => 'nullable|string|max:7',
        ]);

        $company = Company::find($request->input('companyId'));
        $company->name = $validatedData['winkelnaam'];
        $company->description = $validatedData['winkelbeschrijving'];
        $company->slug = $validatedData['slug'];
        $company->background_color = $validatedData['achtergrondkleur'];
        $company->text_color = $validatedData['tekstkleur'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $company->image = $name;
        }

        $company->save();
        return redirect()->back();
    }

    public function show(string $slug = null)
    {
        if (auth()->user()) {
            $company = Company::slug($slug)->first();
        } else {
            $company = Company::slug($slug)->active()->first();
        }

        if (!$company) {
            abort(404);
        }

        $templates = $company->templates;

        return view('company.show', compact('company', 'templates'));
    }

    public function addTemplate(Request $request)
    {
        $company = Company::find($request->input('companyId'));
        $templateId = $request->input('templateId');
        $order = $company->templates()->max('order') + 1;
        $company->templates()->attach($templateId, ['order' => $order]);

        return redirect()->back();
    }

    public function removeTemplate(Request $request)
    {
        $pivotId = $request->input('pivotId');
        $company = Company::find($request->input('companyId'));
        $company->templates()->wherePivot('id', $pivotId)->detach();

        return redirect()->back()->with('success', 'Template succesvol verwijderd.');
    }

    public function orderUp(Request $request)
    {
        $pivotId = $request->input('pivotId');
        $company = auth()->user()->company;
        $template = $company->templates()->wherePivot('id', $pivotId)->first(); // Haal het specifieke template op
        $currentOrder = $template->pivot->order; // Gebruik pivot om het order op te halen

        // Verhoog het order van alle templates met een order lager dan het huidige template
        $company->templates()->wherePivot('order', '<', $currentOrder)->increment('order');
        // Verlaag het order van het geselecteerde template met 1 in de koppeltabel
        $company->templates()->updateExistingPivot($template->id, ['order' => $currentOrder - 1]);

        return redirect()->back();
    }

    public function orderDown(Request $request)
    {
        $pivotId = $request->input('pivotId');
        $company = auth()->user()->company;
        $template = $company->templates()->wherePivot('id', $pivotId)->first(); // Haal het specifieke template op
        $currentOrder = $template->pivot->order; // Gebruik pivot om het order op te halen

        // Verlaag het order van alle templates met een order hoger dan het huidige template
        $company->templates()->wherePivot('order', '>', $currentOrder)->decrement('order');
        // Verhoog het order van het geselecteerde template met 1 in de koppeltabel
        $company->templates()->updateExistingPivot($template->id, ['order' => $currentOrder + 1]);

        return redirect()->back();
    }

}
